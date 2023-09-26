<!DOCTYPE html>
<html>
<head>
    <title>商品詳細画面</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="product-container">
        <h1>商品詳細画面</h1>
        <div class="content">
            <div class="product-details">
                <div class="details">
                    <div class="detail-item">
                        <p class="detail-label"><strong>ID:</strong></p>
                        <p class="detail-value">{{ $product->id }}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label"><strong>商品画像:</strong></p>
                        @if ($product->img_path)
                            <img src="{{ asset('storage/' . $product->img_path) }}" alt="商品画像" style="witdth:200px;height:200px;">
                        @else
                            <p class="detail-value">商品画像なし</p>
                        @endif
                    </div>
                    <div class="detail-item">
                        <p class="detail-label"><strong>商品名:</strong></p>
                        <p class="detail-value">{{ $product->product_name }}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label"><strong>価格:</strong></p>
                        <p class="detail-value">{{ $product->price }}円</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label"><strong>在庫数:</strong></p>
                        <p class="detail-value">{{ $product->stock }}個</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label"><strong>メーカー名:</strong></p>
                        <p class="detail-value">{{ $product->company->company_name }}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label"><strong>コメント:</strong></p>
                        <p class="detail-value">{{ $product->comment }}</p>
                    </div>
                </div>
            </div>
            <div class="button-group">
                <a href="{{ route('products.edit', ['id' => $product->id]) }}" class="edit-button">編集</a>
                <a href="{{ route('products') }}" class="cancel-button">戻る</a>
            </div>
        </div>
    </div>
</body>
</html>
