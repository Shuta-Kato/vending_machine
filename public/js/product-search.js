$(document).ready(function() {
    // 検索ボタンのクリックイベントを設定
    $("#search-button").click(function() {
        var url = $(this).data("url"); // data-url属性を取得

        $.ajax({
            type: "GET",
            url: url, // 取得したURLを使用
            data: $("#search-form").serialize(), // フォームのデータをシリアライズ
            success: function(response) {
                $("#product-list").empty(); // 以前の検索結果をクリア

                // ページネーションのデータにアクセス
                if (response.products.data.length > 0) { 
                    $.each(response.products.data, function(index, product) {
                        var imgHtml = product.img_path ? '<img src="' + product.img_path + '" alt="商品画像" style="width: 100px;">' : "画像なし";

                        // ここで商品行にid属性を追加
                        var rowHtml = `
                            <tr id="product-row-${product.id}">
                                <td>${product.id}</td>
                                <td>${product.product_name}</td>
                                <td>${product.company.company_name}</td>
                                <td>${product.price}円</td>
                                <td>${product.stock}</td>
                                <td>${imgHtml}</td>
                                <td>
                                    <a href="/products/${product.id}" class="btn btn-info">詳細表示</a>
                                    <form id="delete-form-${product.id}" action="/products/${product.id}" method="POST" style="display: inline-block;">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="button" class="btn btn-danger delete-btn" data-product-id="${product.id}">削除</button>
                                    </form>
                                </td>
                            </tr>
                        `;
                        $("#product-list").append(rowHtml); // 商品行を追加
                    });
                } else {
                    $("#product-list").append('<tr><td colspan="7">該当する商品はありません。</td></tr>');
                }
            },
            error: function(error) {
                console.error("AJAX request failed:", error);
                alert("検索に失敗しました。");
            }
        });
    });

    // 削除ボタンのクリックイベントを設定（動的に生成されるため、documentでイベントを捕捉）
    $(document).off("click", ".delete-btn").on("click", ".delete-btn", function() {
        var productId = $(this).data("product-id");
        var deleteForm = $("#delete-form-" + productId);

        $.ajax({
            type: "POST",
            url: deleteForm.attr("action"), // フォームのaction属性を使用
            data: {
                _method: 'DELETE',
                _token: $('meta[name="csrf-token"]').attr('content') // CSRFトークンを含める
            },
            success: function(response) {
                alert("商品を削除しました。");
                // 該当の行を削除
                $("#product-row-" + productId).remove(); // 商品行をDOMから削除
            },
            error: function(error) {
                console.error("AJAX request failed:", error);
                alert("削除に失敗しました。");
            }
        });
    });
});
