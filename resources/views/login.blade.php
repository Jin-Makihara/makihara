<!DOCTYPE html>
<html>
<head>
    <title>ユーザーログイン画面</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="login-container">
        <h1>ユーザーログイン画面</h1>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        <form method="POST" action="{{ route('login.submit') }}">
            @csrf
            <div class="form-group">
                <input type="email" name="email" id="email" placeholder="アドレス" required>
            </div>

            <div class="form-group">
                <input type="password" name="password" id="password" placeholder="パスワード" required>
            </div>

            <div class="button-group">
                <a href="{{ route('register') }}" class="register-button">新規登録</a>
                <button type="submit" class="login-button">ログイン</button>
            </div>
        </form>
    </div>
</body>
</html>