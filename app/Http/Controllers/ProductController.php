<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;
use App\Http\Requests\ProductCreateRequest;

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

    public function create()
    {
        $create_products = new product;
        $products = $create_products->products();
        $companies = $create_products->companies();
        return view('products.create',compact('products','companies'));
    }

    public function store(ProductCreateRequest $request)
    {
        // 新規プロダクトの保存
        // メーカー名でCompanyテーブルを検索
        $company = Company::where('company_name', $request->input('company_name'))->first();
        $model = new Product();
        $model->storeProduct($company, $request);
        return redirect()->route('products');
    }

    public function show($id)
    {
        $show_product = new Product();
        $product = $show_product->show($id);
        return view('products.show', compact('product'));
    }

    public function edit($id)
    {
        $edit_prodcut = new Product();
        $product = $edit_prodcut->edit($id);
        return view('products.edit', compact('product'));
    }

    public function update(ProductCreateRequest  $request, $id)
    {
        // プロダクトの更新
        $product = Product::findOrFail($id);

        // メーカー名でCompanyテーブルを検索
        $company = Company::where('company_name', $request->input('company_name'))->first();

        // メーカーが存在しない場合は新しいレコードを作成
        if (!$company) {
            $company = new Company();
            $company->company_name = $request->input('company_name');
            $company->save();
        }

        $product->product_name = $request->input('product_name');
        $product->company_id = $company->id;
        $product->price = $request->input('price');
        $product->stock = $request->input('stock');
        $product->comment = $request->input('comment');

        // 商品画像を保存
        if ($request->hasFile('product_image')) {
            $imagePath = $request->file('product_image')->store('product_images', 'public');
            $product->img_path = $imagePath;
        }
        $product->save();
        return redirect()->route('products')->with('success', '商品が更新されました。');
    }
    
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        // 商品画像があれば削除
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        // 商品を削除
        $product->delete();
        return redirect()->route('products')->with('success', '商品が削除されました。');
    }
}