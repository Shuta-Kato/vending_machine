@extends('layouts.app')

@section('content')

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="container">
    <h1 class="mb-4">商品情報一覧</h1>

    <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">商品新規登録</a>

<!-- 検索フォームのセクション -->
<div class="search mt-5">
    
    <!-- 検索フォーム。GETメソッドで、商品一覧のルートにデータを送信 -->
    <form action="{{ route('products.index') }}" method="GET" class="row g-3">

        <!-- 商品名検索用の入力欄 -->
        <div class="col-sm-12 col-md-3">
            <input type="text" name="search" class="form-control" placeholder="商品名" value="{{ request('search') }}">
        </div>

        <div class="col-sm-12 col-md-3">
            <select class="form-select" name="company">
                <option value="">会社名</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" {{ request()->input('company') == $company->id ? 'selected' : '' }}>{{ $company->company_name }}</option>
                @endforeach
            </select>
        </div>

        <!-- 絞り込みボタン -->
        <div class="col-sm-12 col-md-1">
            <button class="btn btn-outline-secondary" type="submit">絞り込み</button>
        </div>
    </form>

    <!-- 検索条件をリセットするためのリンクボタン -->
    <a href="{{ route('products.index') }}" class="btn btn-success mt-3">検索条件を元に戻す</a>

</div>




    <div class="products mt-5">
        <h2>商品情報</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>商品名</th>
                    <th>メーカー</th>
                    <th>価格</th>
                    <th>在庫数</th>
                    <th>コメント</th>
                    <th>商品画像</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ $product->company->company_name }}</td>
                    <td>{{ $product->price }}円</td>
                    <td>{{ $product->stock }}</td>
                    <td>{{ $product->comment }}</td>
                    <td><img src="{{ asset($product->img_path) }}" alt="商品画像" width="100"></td>
                    </td>
                    <td>
                        <a href="{{ route('products.show', $product) }}" class="btn btn-info btn-sm mx-1">詳細表示</a>
                        <form method="POST" action="{{ route('products.destroy', $product) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm mx-1">削除</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    {{ $products->appends(request()->query())->links() }} 


    
</div>
@endsection

