<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function purchase(Request $request)
    {
        // バリデーションの追加
        $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1'
        ]);

        // トランザクションの開始
        DB::beginTransaction();

        try {
            // リクエストから必要なデータを取得する
            $productId = $request->input('product_id');
            $quantity = $request->input('quantity', 1);

            // データベースから対象の商品を検索・取得する
            $product = Product::find($productId);

            // 商品が存在しない、または在庫が不足している場合のバリデーションを追加
            if (!$product) {
                return response()->json(['message' => '商品が存在しません'], 404);
            }
            if ($product->stock < $quantity) {
                return response()->json(['message' => '商品が在庫不足です'], 400);
            }

            // 在庫を減少させる
            $product->stock -= $quantity;
            $product->save();

            // Salesテーブルに商品IDと購入日時を記録する
            $sale = new Sale([
                'product_id' => $productId,
                // created_at,とupdated_atは省略
            ]);
            $sale->save();

            // トランザクションのコミット
            DB::commit();

            // レスポンスを返す
            return response()->json(['message' => '購入成功']);
        } catch (\Exception $e) {
            // エラーログの記録
            \Log::error($e->getMessage());

            // トランザクションのロールバック
            DB::rollBack();

            return response()->json(['message' => '内部サーバーエラー'], 500);
        }
    }
}
