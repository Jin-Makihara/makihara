<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Requests\ProductCreateRequest;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'product_name',
        'price',
        'stock',
        'comment',
        'img_path',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function products()
    {
        $query = Self::query();
        $products = $query->get();
        return $products;
    }
    
    public function companies()
    {
        $companies = Company::all();
        return $companies;
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return $product;
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return $product;
    }

    public function searchProduct($searchKeyword,$selectedMaker)
    {
        $query = Self::query();

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
        return $products;
    }
    
    //下の部分は見てもらっている(11/16)
    public function storeProduct($company,$request)
    {
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
        return $company;

    }
    //ここまでがみてもらっている

    //ここからupdate
    public function updateProduct($request,$id)
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
        return $product;
    }

}
