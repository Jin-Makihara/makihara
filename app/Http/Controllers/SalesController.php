<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function sell(Request $request, $id)
    {
        DB::beginTransaction();
        try{
            $product = new Product();
            $result = $product->sellProduct($id);
            if ($result == false) {
                // ID誤りもしくは在庫が0
                return response()->json(['result'=> '対象の商品は販売できません。'],
                    400,
                    ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                    JSON_UNESCAPED_UNICODE);
            }
            $sale = new Sale();
            $sale->sellProduct($id);
            DB::commit();            
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
