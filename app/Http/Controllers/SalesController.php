<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    // curl -XPOST http://127.0.0.1:8000/api/sell/1 のように実行すると呼べる1の部分は販売するproduct_id
    public function sell(Request $request, $id)
    {
        DB::beginTransaction();
        try{
            $product = new Product();
            $result = $product->sellProduct($id);
            if ($result == false) {
                // ID誤りもしくは在庫が0 (コマンドプロンプトで日本語が文字化けしないようにJSONを設定)
                return response()->json(['result'=> '対象の商品は販売できません。'],
                    400,
                    ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                    JSON_UNESCAPED_UNICODE);
            }
            // salesテーブルの編集
            $sale = new Sale();
            $sale->sellProduct($id);
            // コミット
            DB::commit();
            // 応答返却
            return response()->json(['result'=> '対象の商品を販売しました。'],
                200,
                ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE);

        }catch(\Exception $e){
            DB::rollBack();
            return response()->json(['result'=> '異常が発生しました。'],
                500,
                ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE);

        }

    }
}
