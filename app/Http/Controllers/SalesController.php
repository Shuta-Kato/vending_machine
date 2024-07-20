<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;

class SalesController extends Controller
{
    public function purchase(Request $request){
        try{
        //バリデーションの追加
        $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1'
        ]);

        //リクエストから必要なデータを取得する
        $productId = $request->input('product_id');//product_id:n が贈られた場合はnが代入される。
        $quantity = $request->input('quantity', 1);//購入する数を代入する。空の場合は1を代入。

        //データベースから対象の商品を検索・取得する
        $product = Product::find($productId);//product_id:n が送られてきた場合、Product::find(n)の情報が代入される。

        //商品が存在しない、または在庫が不足している場合のバリデーションを追加
        if(!$product){
            return response()->json(['message' => '商品が存在しません'], 404);
        }
        if($product->stock < $quantity){
            return response()->json(['message' => '商品が在庫不足です'], 400);
        }

        //在庫を減少させる
        $product->stock -= $quantity;//$quantity=購入数。デフォルトは1。
        $product->save();

        //Salesテーブルに商品IDと購入日時を記録する
        $sale = new Sale([
            'product_id' => $productId,
            //created_at,とupdated_atは省略
        ]);

        $sale->save();

        //レスポンスを返す
        return response()->json(['message' => '購入成功']);
    }catch(\Exception $e){
        \log::error($e->getMessage());
        return response()->json(['message' => '内部サーバーエラー'], 500);
    }
    }
}
