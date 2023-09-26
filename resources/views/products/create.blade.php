<!DOCTYPE html>
<html>
<head>
    <title>商品新規登録画面</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="product-container">
        <h1>商品新規登録</h1>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        <div class="content">
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf <!-- LaravelのCSRFトークンを含めるためのディレクティブ -->
                <div class="form-group">
                    <label for="product_name">商品名</label>
                    <input type="text" name="product_name" id="product_name" required>
                </div>
                <div class="form-group">
                    <label for="company_name">メーカー名</label>
                    <input type="text" name="company_name" id="company_name" required>
                </div>
                <div class="form-group">
                    <label for="price">価格</label>
                    <input type="number" name="price" id="price" required>
                </div>
                <div class="form-group">
                    <label for="stock">在庫数</label>
                    <input type="number" name="stock" id="stock" required>
                </div>
                <div class="form-group">
                    <label for="comment">コメント</label>
                    <textarea name="comment" id="comment" rows="4" ></textarea>
                </div>
                <div class="form-group">
                    <label for="product_image">商品画像</label>
                    <input type="file" name="product_image" id="product_image" accept="image/*">
                </div>
                <div class="button-group">
                    <button type="submit" class="register-button">登録</button>
                    <a href="{{ route('products') }}" class="cancel-button">キャンセル</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
