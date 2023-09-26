<!DOCTYPE html>
<html>
<head>
    <title>商品一覧画面</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="product-container">
        <h1>商品一覧画面</h1>
        <form action="{{ route('products') }}" method="GET">
            <div class="form-group">
                <input name="search" id="search" placeholder="検索キーワード" value="{{ request('search') }}">
                <select name="maker" id="maker">
                    <option value="0" {{ request('maker') == 0 ? 'selected' : '' }}>メーカー名</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ request('maker') == $company->id ? 'selected' : '' }}>
                            {{ $company->company_name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="search-button">検索</button>
            </div>
        </form>
        <table>
            <tr>
                <th>ID</th>
                <th>商品画像</th>
                <th>商品名</th>
                <th>価格</th>
                <th>在庫数</th>
                <th>メーカー名</th>
                <th><a href="{{ route('products.create') }}" class="register-button">新規登録</a></th>
            </tr>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>
                        @if ($product->img_path)
                            <img src="{{ asset('storage/' . $product->img_path) }}" alt="商品画像">
                        @else
                            <p>商品画像なし</p>
                        @endif
                    </td>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ $product->price }}円</td>
                    <td>{{ $product->stock }}個</td>
                    <td>{{ $product->company->company_name }}</td>
                    <td>
                        <a href="{{ route('products.show', ['id' => $product->id]) }}" class="detail-button">詳細</a>
                        <form action="{{ route('products.destroy', ['id' => $product->id]) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-button">削除</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</body>
</html>
