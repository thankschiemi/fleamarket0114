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
                    return $queryBuilder->where('user_id', '!=', Auth::id());
                })
                ->when($query, function ($queryBuilder) use ($query) {
                    return $queryBuilder->where('name', 'LIKE', "%{$query}%");
                })
                ->orderByRaw("FIELD(status, 'available', 'trading', 'sold')")
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
        $sellingProducts = Product::where('user_id', $user->id)->latest()->get();

        $purchasedProducts = Purchase::where('user_id', $user->id)
            ->whereHas('product', function ($q) {
                $q->whereIn('status', ['sold', 'completed']);
            })
            ->with('product')
            ->get();

        $tradingPurchases = Purchase::query()
            ->whereIn('status', ['pending', 'sold', 'completed'])
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhereHas('product', fn($q2) => $q2->where('user_id', $user->id));
            })
            ->with('product')

            ->addSelect([
                'last_incoming_at' => Message::select('created_at')
                    ->whereColumn('purchase_id', 'purchases.id')
                    ->where('user_id', '!=', $user->id)
                    ->orderByDesc('created_at')
                    ->limit(1),
                'last_message_at' => Message::select('created_at')
                    ->whereColumn('purchase_id', 'purchases.id')
                    ->orderByDesc('created_at')
                    ->limit(1),
            ])

            ->withCount(['messages as unread_count' => function ($q) use ($user) {
                $q->where('user_id', '!=', $user->id)->where('is_read', false);
            }])

            ->orderByRaw('COALESCE(last_incoming_at, last_message_at) DESC')
            ->get();

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

        $unreadCounts = $tradingPurchases->pluck('unread_count', 'id')->toArray();

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

            $originalName = $file->getClientOriginalName();
            $extension = pathinfo($originalName, PATHINFO_EXTENSION);

            if (!$extension) {
                $extension = 'jpg';
            }

            $filename = time() . '_' . uniqid() . '.' . $extension;

            $file->storeAs('public/products', $filename);

            $imagePath = 'products/' . $filename;
        }

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
