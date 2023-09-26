<!DOCTYPE html>
<html>
<head>
    <title>新規登録</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="login-container">
        <h1>新規登録</h1>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        <form method="POST" action="{{ route('register.submit') }}">
            @csrf
            <div class="form-group">
                <input type="email" name="email" id="email" placeholder="アドレス" required>
            </div>

            <div class="form-group">
                <input type="password" name="password" id="password" placeholder="パスワード" required>
            </div>

            <div class="button-group">
                <button type="submit" class="register-button">新規登録</button>
                <a href="{{ route('login') }}" class="login-button">戻る</a>
            </div>
        </form>
    </div>
</body>
</html>
