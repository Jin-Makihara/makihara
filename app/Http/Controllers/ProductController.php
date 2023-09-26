<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;
use App\Http\Requests\ProductCreateRequest;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $searchKeyword = $request->input('search', '');
        $selectedMaker = $request->input('maker', 0);

        $query = Product::query();

        if (!empty($searchKeyword)) {
            $query->where(function ($query) use ($searchKeyword) {
                $query->where('product_name', 'like', '%' . $searchKeyword . '%')
                    ->orWhere('comment', 'like', '%' . $searchKeyword . '%');
            });
        }

        if ($selectedMaker != 0) {
            $query->where('company_id', $selectedMaker);
        }

        $products = $query->get();
        $companies = Company::all();

        return view('products.index', compact('products', 'companies'));
    }


    public function create()
    {
        // 新規プロダクトの作成画面表示
        return view('products.create');
    }


    public function store(ProductCreateRequest $request)
    {
        // 新規プロダクトの保存
        // メーカー名でCompanyテーブルを検索
        $company = Company::where('company_name', $request->input('company_name'))->first();

        // メーカーが存在しない場合は新しいレコードを作成
        if (!$company) {
            $company = new Company();
            $company->company_name = $request->input('company_name');
            $company->save();
        }

        // 商品データを保存
        $product = new Product();
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

        return redirect()->route('products');
    }
    public function show($id)
    {
        // プロダクトの詳細表示
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }
    public function edit($id)
    {
        // プロダクトの編集画面表示
        $product = Product::findOrFail($id);
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
            $product->image = $imagePath;
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


