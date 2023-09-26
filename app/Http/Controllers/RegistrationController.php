<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegistValidation;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends Controller
{
    public function showRegistrationForm()
    {
        return view('register');
    }
    public function register(RegistValidation $request)
    {
        $err = "";
        if(isset($request->e)){
            $err =urldecode($request->e);
            return back()->withInput()->with(['err' => $err]);
        }


        // フォームから送信されたデータを取得
        $email = $request->input('email');
        $password = $request->input('password');

        // 新しいユーザーレコードを作成し、データベースに保存
        $user = User::create([
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        // ユーザーの登録が成功した場合
        if ($user) {
            // 登録後のリダイレクト先などの処理を記述
            return redirect()->route('login')->with('success', '新しいユーザーが登録されました。ログインしてください。');
        } else {
            // 登録が失敗した場合
            return back()->withInput()->with('error', 'ユーザーの登録に失敗しました。再度お試しください。');
        }
    }
}
