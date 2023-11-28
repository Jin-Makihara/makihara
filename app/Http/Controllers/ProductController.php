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
        //登録
        public function create($id)
        {
            $product = Product::find($id);
            DB::beginTransaction();
            try{
            $create_products = new product;
            $products = $create_products->products();
            $companies = $create_products->companies();
            return view('products.create',compact('products','companies'));
            DB::commit();
            }catch(\Exception $e){
                DB::rollBack();
                return back();
            }
        }

        public function store(ProductCreateRequest $request,$id)
        {
            $product = Product::find($id);
            DB::beginTransaction();
            try{
            // 新規プロダクトの保存
            // メーカー名でCompanyテーブルを検索
            $company = Company::where('company_name', $request->input('company_name'))->first();
            $model = new Product();
            $model->storeProduct($company, $request);
            return redirect()->route('products');
            DB::commit();
            }catch(\Exception $e){
                DB::rollBack();
                return back();
            }
        }

        public function show($id)
        {
            $product = Product::find($id);
            DB::beginTransaction();
            try{
            $show_product = new Product();
            $product = $show_product->show($id);
            return view('products.show', compact('product'));
            DB::commit();
            }catch(\Exception $e){
                DB::rollBack();
                return back();
            }
        }

        public function edit($id)
        {
            $product = Product::find($id);
            DB::beginTransaction();
            try{
            $edit_prodcut = new Product();
            $product = $edit_prodcut->edit($id);
            return view('products.edit', compact('product'));
            DB::commit();
            }catch(\Exception $e){
                DB::rollBack();
                return back();
            }
        }
        //更新
        public function update(ProductCreateRequest  $request, $id)
        {
            $product = Product::find($id);
            DB::beginTransaction();
            try{
            $model = new Product();
            $model->updateProduct($request,$id);
            return redirect()->route('products')->with('success', '商品が更新されました。');
            DB::commit();
            }catch(\Exception $e){
                DB::rollBack();
                return back();
            }
        }
        //削除
        public function destroy(Request $request, $id)
        {
            $product = Product::find($id);
            DB::beginTransaction();
            try{
                $product = Product::findOrFail($id);
                // 商品画像があれば削除
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                // 商品を削除
                $product->delete();
                return redirect()->route('products')->with('success', '商品が削除されました。');
                DB::commit();
            }catch(\Exception $e){
                DB::rollback();
                return back();
            }
        }
}