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
                $("#pagination").empty(); // ページネーションをクリア

                // レスポンスの形式を確認
                if (response && response.products && response.products.data) {
                    $.each(response.products.data, function(index, product) {
                        var imgHtml = product.img_path ? '<img src="' + product.img_path + '" alt="商品画像" style="width: 100px;">' : "画像なし";

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

                    // ページネーションのHTMLを追加
                    $("#pagination").html(response.pagination);
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

    // ソートボタンのクリックイベントを設定
    $(document).on("click", ".sort-link", function(event) {
        event.preventDefault(); // デフォルトのリンク動作を防ぐ

        var sortBy = $(this).data("sort-by"); // data-sort-by属性からソート項目を取得
        var sortOrder = $(this).data("sort-order"); // data-sort-order属性からソート順を取得
        var searchParams = $("#search-form").serializeArray(); // 現在の検索条件を取得
        // 検索条件をオブジェクトとして取得
        var searchParamsObj = {};
        $.each(searchParams, function(i, field) {
            searchParamsObj[field.name] = field.value;
        });

        $.ajax({
            type: "GET",
            url: sortUrl, // 定義した変数を使用
            data: $.extend(searchParamsObj, { sort_by: sortBy, sort_order: sortOrder }), // 検索条件とソートパラメータを送信
            success: function(response) {
                $("#product-list").empty(); // 以前の検索結果をクリア
                $("#pagination").empty(); // ページネーションをクリア

                // レスポンスの形式を確認
                if (response && response.products && response.products.data) {
                    $.each(response.products.data, function(index, product) {
                        var imgHtml = product.img_path ? '<img src="' + product.img_path + '" alt="商品画像" style="width: 100px;">' : "画像なし";

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

                    // ページネーションのHTMLを追加
                    $("#pagination").html(response.pagination);
                } else {
                    $("#product-list").append('<tr><td colspan="7">該当する商品はありません。</td></tr>');
                }
            },
            error: function(error) {
                console.error("AJAX request failed:", error);
                alert("ソートに失敗しました。");
            }
        });
    });
    
    // 削除ボタンのクリックイベントを設定
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
