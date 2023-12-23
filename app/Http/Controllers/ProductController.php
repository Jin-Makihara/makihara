<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;
use App\Http\Requests\ProductCreateRequest;
use Illuminate\Support\Facades\DB;
use function Illuminate\Session\destroy;

class ProductController extends Controller
{
        public function index(Request $request)
        {
            $searchKeyword = $request->input('search', '');
            $selectedMaker = $request->input('maker', 0);
            $companies = Company::all();
            $model = new Product();
            $products = $model->searchProduct($searchKeyword, $selectedMaker);
            return view('products.index', compact('products', 'companies'));
        }

        // 非同期で呼び出される検索API (非同期なのでJSONで応答し、クライアントでテーブルを作る)
        public function search(Request $request)
        {
            $searchKeyword = $request->input('search', '');
            $selectedMaker = $request->input('maker', 0);
            // 値段の下限上限取得
            $priceLower = $request->input('price_lower', '');
            $priceUpper = $request->input('price_upper', '');
            // 在庫数の下限上限取得
            $stockLower = $request->input('stock_lower', '');
            $stockUpper = $request->input('stock_upper', '');

            $companies = Company::all();
            $model = new Product();
            $products = $model->searchProductDetail($searchKeyword, $selectedMaker,  $priceLower, $priceUpper,
                                              $stockLower, $stockUpper);
            foreach($products as $product) {
                $product->company_name = $product->company->company_name;
            }
            return response()->json($products);
        }

        //登録
        public function create()
        {
            DB::beginTransaction();
            try{
                $create_products = new product;
                $products = $create_products->products();
                $companies = $create_products->companies();
                DB::commit();            
            }catch(\Exception $e){
                DB::rollBack();
                return back();
            }
            return view('products.create',compact('products','companies'));

        }

        public function store(ProductCreateRequest $request)
        {
            DB::beginTransaction();
            try{
            // 新規プロダクトの保存
            // メーカー名でCompanyテーブルを検索
                $company = Company::where('company_name', $request->input('company_name'))->first();
                $model = new Product();
                $model->storeProduct($company, $request);
                DB::commit();
            }catch(\Exception $e){
                DB::rollBack();
                return back();
            }
            return redirect()->route('products');
        }

        public function show($id)
        {
            DB::beginTransaction();
            try{
                $show_product = new Product();
                $product = $show_product->show($id);
                DB::commit();
            }catch(\Exception $e){
                DB::rollBack();
                return back();
            }
            return view('products.show', compact('product'));
        }

        public function edit($id)
        {
            DB::beginTransaction();
            try{
                $edit_prodcut = new Product();
                $product = $edit_prodcut->edit($id);
                DB::commit();
            }catch(\Exception $e){
                DB::rollBack();
                return back();
            }
            return view('products.edit', compact('product'));
        }

        //更新
        public function update(ProductCreateRequest  $request, $id)
        {
            DB::beginTransaction();
            try{
            $model = new Product();
            $model->updateProduct($request,$id);
            DB::commit();
            }catch(\Exception $e){
                DB::rollBack();
                return back();
            }
            return redirect()->route('products')->with('success', '商品が更新されました。');
        }

        //削除
        public function destroy(Request $request, $id)
        {
            DB::beginTransaction();
            try{
                $product = Product::findOrFail($id);
                // 商品画像があれば削除
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                // 商品を削除
                $product->delete();
                DB::commit();
            }catch(\Exception $e){
                DB::rollback();
                return back();
            }
            return response()->json([ "result" => "success" ]);
        }
}