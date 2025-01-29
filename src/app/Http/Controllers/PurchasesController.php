<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Product;

class PurchasesController extends Controller
{
    /**
     * 購入履歴の一覧を表示
     */
    public function index()
    {
        $purchases = Purchase::with(['user', 'product'])->get(); // リレーション込みで取得
        return view('purchases.index', compact('purchases'));
    }

    public function show($item_id)
    {
        $product = Product::findOrFail($item_id); // 商品情報を取得
        return view('products.product-purchase', compact('product'));
    }
    public function complete(Request $request, $item_id)
    {
        $purchase = new Purchase();
        $purchase->user_id = $request->user_id;
        $purchase->product_id = $item_id;
        $purchase->payment_method = $request->payment_method;
        $purchase->status = 'pending';
        $purchase->purchase_date = now();
        $purchase->save();

        return redirect()->route('purchase.show', ['item_id' => $item_id])->with('success', '購入が完了しました！');
    }
}
