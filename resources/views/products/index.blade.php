<!DOCTYPE html>
<html>
<head>
    <title>商品一覧画面</title>
    <!-- ajaxで使用するCSRFトークン -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <!-- ソート機能のCSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.0/css/theme.default.min.css">
    <!-- Scripts -->
    <!-- jQueryのJS -->
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <!-- ソート機能のJS-->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.0/js/jquery.tablesorter.min.js"></script>
    <!-- 本画面に対応するJS -->
    <script src="{{ asset('js/index.js') }}"></script>
</head>
<body>
    <div class="product-container">
        <h1>商品一覧画面</h1>
        <select id="choice_type" onchange="changeSelectType(event)">
            <option value="item">商品名</option>
            <option value="price">価格</option>
            <option value="stock">在庫数</option>
        </select>

        <div id="item" class="form-group">
            <input name="search" id="search" placeholder="検索キーワード" value="{{ request('search') }}">
            <select name="maker" id="maker">
                <option value="0" {{ request('maker') == 0 ? 'selected' : '' }}>メーカー名</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" {{ request('maker') == $company->id ? 'selected' : '' }}>
                        {{ $company->company_name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div id="price" style="display:none;">
            <input name="price_lower" type="number" min=0 id="price_lower" placeholder="下限" value="{{ request('price_lower') }}">
            <input name="price_upper" type="number" min=0 id="price_upper" placeholder="上限" value="{{ request('price_upper') }}">
        </div>            
        <div id="stock" style="display:none;">
            <input name="stock_lower" type="number" min=0 id="stock_lower" placeholder="下限" value="{{ request('stock_lower') }}">
            <input name="stock_upper" type="number" min=0 id="stock_upper" placeholder="上限" value="{{ request('stock_upper') }}">
        </div>            
        <button type="button" class="search-button" onclick=searchProduct()>検索</button>
        <table id="product_table">
            <thead style="height:50px;">
                <tr>
                    <th>ID</th>
                    <th>商品画像</th>
                    <th>商品名</th>
                    <th>価格</th>
                    <th>在庫数</th>
                    <th>メーカー名</th>
                    <th><a href="{{ route('products.create') }}" class="register-button">新規登録</a></th>
                </tr>
            </thead>
            @foreach($products as $product)
                <tr id="t{{ $product->id }}">
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
                        <button class="delete-button" onclick=clickDelete("t{{ $product->id }}")>削除</button>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
    <!-- ローディングアイコン用のエレメント -->
    <div class="loading is-hide">
        <div class="loading_icon"></div>
    </div>    
</body>
</html>
