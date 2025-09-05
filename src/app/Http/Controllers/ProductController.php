<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Http\Requests\ExhibitionRequest;
use App\Models\Purchase;
use App\Models\Message;
use Illuminate\Support\Facades\DB;
use App\Models\Rating;


class ProductController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'recommend');
        $query = $request->query('query', '');

        if ($tab === 'mylist') {
            if (!Auth::check()) {
                return redirect()->route('login')->with('redirect_to', request()->fullUrl());
            }

            $products = Product::whereHas('favoritedByUsers', function ($queryBuilder) {
                $queryBuilder->where('users.id', Auth::id());
            })
                ->when($query, function ($queryBuilder) use ($query) {
                    return $queryBuilder->where('name', 'LIKE', "%{$query}%");
                })
                ->get();
        } else {
            $products = Product::query()
                ->when(Auth::check(), function ($queryBuilder) {
                    return $queryBuilder->where('user_id', '!=', Auth::id()); // 自分の商品は非表示
                })
                ->when($query, function ($queryBuilder) use ($query) {
                    return $queryBuilder->where('name', 'LIKE', "%{$query}%");
                })
                ->orderByRaw("FIELD(status, 'available', 'trading', 'sold')") // 並び順を適用
                ->get();
        }

        return view('products.product-list', [
            'products' => $products,
            'tab' => $tab,
            'searchQuery' => $query,
        ]);
    }


    public function show($id)
    {
        $product = Product::with(['favoritedByUsers', 'reviews.user', 'categories'])->findOrFail($id);

        return view('products.product-detail', compact('product'));
    }


    public function showProfile(Request $request)
    {
        $user = auth()->user();
        $tab  = $request->query('tab', 'sell');

        // 出品した商品（すべて）
        $sellingProducts = Product::where('user_id', $user->id)->latest()->get();

        // 購入した商品（= 完了済みのものを表示）
        $purchasedProducts = Purchase::where('user_id', $user->id)
            ->whereHas('product', function ($q) {
                $q->whereIn('status', ['sold', 'completed']);
            })
            ->with('product')
            ->get();

        // ===========================
        // ▼ ここが“取引中”の並び替え本体 ▼
        // ===========================
        $tradingPurchases = Purchase::query()
            ->whereIn('status', ['pending', 'sold', 'completed'])
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id) // 自分が購入者
                    ->orWhereHas('product', fn($q2) => $q2->where('user_id', $user->id)); // 自分が出品者
            })
            ->with('product')

            // 相手からの最新メッセージ時刻（なければ null）
            ->addSelect([
                'last_incoming_at' => Message::select('created_at')
                    ->whereColumn('purchase_id', 'purchases.id')
                    ->where('user_id', '!=', $user->id)
                    ->orderByDesc('created_at')
                    ->limit(1),
                // どちらからでも最新（相手からが無い場合のフォールバック）
                'last_message_at' => Message::select('created_at')
                    ->whereColumn('purchase_id', 'purchases.id')
                    ->orderByDesc('created_at')
                    ->limit(1),
            ])

            // 未読数（相手からのみ）
            ->withCount(['messages as unread_count' => function ($q) use ($user) {
                $q->where('user_id', '!=', $user->id)->where('is_read', false);
            }])

            // 並び順：相手からの最新 → 無ければ全体の最新
            ->orderByRaw('COALESCE(last_incoming_at, last_message_at) DESC')
            ->get();

        // Blade用の形に整形（従来のキー名を維持）
        $tradingProducts = $tradingPurchases->map(function ($p) use ($user) {
            $isSeller = optional($p->product)->user_id === $user->id;
            return (object) [
                'product'             => $p->product,
                'id'                  => $p->id,
                'status'              => $p->status,
                'is_seller'           => $isSeller,
                'unread_count'        => $p->unread_count,
                'latest_message_time' => $p->last_incoming_at ?? $p->last_message_at,
                'role_label'          => $isSeller ? 'SOLD' : '購入済',
            ];
        })->values();

        // 個別未読数の連想配列（必要なら Blade で使えるように残す）
        $unreadCounts = $tradingPurchases->pluck('unread_count', 'id')->toArray();

        // タブの赤丸：関連する全取引の未読合計（買い手/売り手の両方）
        $relatedPurchaseIds = Purchase::where('user_id', $user->id)
            ->orWhereHas('product', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->pluck('id');

        $unreadCountTotal = Message::where('is_read', false)
            ->where('user_id', '!=', $user->id)
            ->whereIn('purchase_id', $relatedPurchaseIds)
            ->count();

        $ratingStats = Rating::where('reviewed_user_id', $user->id)
            ->selectRaw('COUNT(*) AS cnt, ROUND(AVG(score), 0) AS avg_rounded')
            ->first();

        $ratingCount = (int) ($ratingStats->cnt ?? 0);
        $ratingAvg   = $ratingCount > 0 ? (int) $ratingStats->avg_rounded : null;


        return view('profile', compact(
            'user',
            'tab',
            'sellingProducts',
            'purchasedProducts',
            'tradingProducts',
            'unreadCounts',
            'unreadCountTotal',
            'ratingAvg',
            'ratingCount'
        ));
    }

    public function create()
    {

        if (!auth()->check()) {
            return redirect()->route('login')->with('redirect_to', request()->fullUrl());
        }
        $categories = Category::all();
        return view('products.product-exhibit', compact('categories'));
    }








    public function store(ExhibitionRequest $request)
    {
        $data = $request->validated();

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');

            // 拡張子が取得できない場合の対応
            $originalName = $file->getClientOriginalName();
            $extension = pathinfo($originalName, PATHINFO_EXTENSION);

            if (!$extension) {
                $extension = 'jpg'; // 拡張子が取得できない場合はデフォルトを設定
            }

            // ランダムなファイル名を生成
            $filename = time() . '_' . uniqid() . '.' . $extension;

            // public/storage/products に保存
            $file->storeAs('public/products', $filename);

            // データベースには 'products/filename.jpg' の形で保存
            $imagePath = 'products/' . $filename;
        }



        // 商品の保存
        $product = Product::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'condition' => $request->condition,
            'img_url' => $imagePath,
        ]);



        if ($request->has('category')) {
            $product->categories()->attach($request->category);
        }

        return redirect()->route('mypage', ['tab' => 'sell'])->with('success', '商品を出品しました！');
    }
}
