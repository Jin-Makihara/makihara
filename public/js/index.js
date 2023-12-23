// selectボックスの変更により表示アイテムを変える
function changeSelectType(event) {
    if (event.target.value == 'item') {
        // 商品名設定時
        $('#item').css('display', '');
        $('#price').css('display', 'none');
        $('#stock').css('display', 'none');
    } else if (event.target.value == 'price') {
        //  価格設定時
        $('#item').css('display', 'none');
        $('#price').css('display', '');
        $('#stock').css('display', 'none');
    } else {
        // 在庫数設定時
        $('#item').css('display', 'none');
        $('#price').css('display', 'none');
        $('#stock').css('display', '');
    }
    // 種別変更時は値を初期化
    $('#price_lower').val('');
    $('#price_upper').val('');
    $('#stock_lower').val('');
    $('#stock_upper').val('');
    $('#search').val('');
    $('#maker').val(0);
}

// 検索ボタンクリック
function searchProduct() {
    // 値の検証
    if ($('#choice_type').val() == 'price')  {
        if ($('#price_lower').val() == '' || $('#price_lower').val() == '') {
            alert('下限と上限を設定してください。')
            return;
        } 
    } else if ($('#choice_type').val() == 'price') {
        if ($('#stock_lower').val() == '' || $('#stock_upper').val() == '') {
            alert('下限と上限を設定してください。')
            return;
        } 
    }

    // ajax送信
    var url = '/products/search' + '?search=' + $('#search').val() + '&maker=' + $('#maker').val()
        + '&price_lower=' + $('#price_lower').val() + '&price_upper=' + $('#price_upper').val() 
        + '&stock_lower=' + $('#stock_lower').val()  + '&stock_upper=' + $('#stock_upper').val()  
    var $loading = $(".loading");

    $.ajax({
        url: url,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
           },
        beforeSend:function(){
            $loading.removeClass("is-hide");
        }
    }).done(function(data) {
        $loading.addClass("is-hide");

        // テーブルの削除
        $("#product_table > tbody > tr").remove();
        for (var product of data) {
            img = '';
            if (product.img_path != null) {
                img = '<img src="/storage/' + product.img_path +'" alt="商品画像">';
            } else {
                img = '<p>商品画像なし</p>';
            }

            html = '<tr id="t' + product.id + '">' + 
                    '<td>' + product.id + '</td>' + 
                    '<td>' + img + '</td>' + 
                    '<td>' + product.product_name + '</td>' + 
                    '<td>' + product.price + '円</td>' + 
                    '<td>' + product.stock + '個</td>' + 
                    '<td>' + product.company.company_name + '</td>' + 
                    '<td>' +
                    '<a href="products/'  + product.id +'/show" class="detail-button">詳細</a>' +
                    '<button class="delete-button" onclick=clickDelete("t' + product.id + '")>削除</button>' +
                    '</td>' +
                    '</tr>';
            $("#product_table > tbody:last").append(html);
        }
    }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
        // エラーの場合
        alert('商品の検索に失敗しました。')
        $loading.addClass("is-hide");
    })
}

// 削除ボタンクリック
function clickDelete(id) {
    // 表から削除(idがtr要素のid)
    var tr = $('#'+ id);
    tr.remove();

    // ajaxで非同期に削除
    hostUrl = '/products/'+ id.slice(1) + '/delete';
    $.ajax({
        url: hostUrl,
        type:'DELETE',
        timeout:3000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
           }
    }).done(function(data) {
        // 成功応答が返ってきた場合
        alert('商品の削除に成功しました。')
    }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
        // エラーの場合
        alert('商品の削除に失敗しました。')
    })
}

// ソート機能の実現
$(function() {
    // tablesorterを使用、初期はID(一番左の列を降順ソート)、最後の列はソート対象から除く
    $("#product_table").tablesorter({
        headers : {
            0: { sortInitialOrder: 'desc' },
            6: { sorter: false }
        },
        sortList: [[0,1]]
    });

});