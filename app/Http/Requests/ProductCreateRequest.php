<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'product_name' => 'required|max:255',
            'company_name' => 'required|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'comment' => 'nullable|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
    public function messages()
    {
        return [
            'product_name.required' => '商品名は必須項目です。',
            'product_name.max' => '商品名は255文字以内で入力してください。',
            'company_name.required' => 'メーカー名は必須項目です。',
            'company_name.max' => 'メーカー名は255文字以内で入力してください。',
            'price.required' => '価格は必須項目です。',
            'price.numeric' => '価格は数値で入力してください。',
            'price.min' => '価格は0以上の値で入力してください。',
            'stock.required' => '在庫数は必須項目です。',
            'stock.integer' => '在庫数は整数で入力してください。',
            'stock.min' => '在庫数は0以上の値で入力してください。',
            'comment.max' => 'コメントは1000文字以内で入力してください。',
            'image.image' => '商品画像は画像ファイルを選択してください。',
            'image.mimes' => '商品画像はjpeg、png、jpg、gif形式の画像ファイルを選択してください。',
            'image.max' => '商品画像のサイズは2MB以下にしてください。',
        ];
    }
}
