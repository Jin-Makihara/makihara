<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginValidation;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }
    public function login(LoginValidation $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            // ログイン成功
            return redirect()->intended('/products'); // ログイン後のリダイレクト先（ここではルートのトップページに戻る）
        } else {
            // ログイン失敗
            return back()->withErrors(['error' => 'ログインに失敗しました。']);
        }
    }
}
