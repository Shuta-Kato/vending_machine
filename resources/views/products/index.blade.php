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

    <div class="search mt-5">
        <form id="search-form" class="row g-3">
            <div class="col-sm-12 col-md-3">
                <input type="text" id="search" name="search" class="form-control" placeholder="商品名" value="{{ request('search') }}">
            </div>
            
            <div class="col-sm-12 col-md-2">
                <input type="number" id="min_price" name="min_price" class="form-control" placeholder="下限価格" value="{{ request('min_price') }}">
            </div>

            <div class="col-sm-12 col-md-2">
                <input type="number" id="max_price" name="max_price" class="form-control" placeholder="上限価格" value="{{ request('max_price') }}">
            </div>

            <div class="col-sm-12 col-md-2">
                <input type="number" id="min_stock" name="min_stock" class="form-control" placeholder="下限在庫" value="{{ request('min_stock') }}">
            </div>

            <div class="col-sm-12 col-md-2">
                <input type="number" id="max_stock" name="max_stock" class="form-control" placeholder="上限在庫" value="{{ request('max_stock') }}">
            </div>

            <div class="col-sm-12 col-md-3">
                <select id="company" class="form-select" name="company">
                    <option value="">会社名</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ request()->input('company') == $company->id ? 'selected' : '' }}>{{ $company->company_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-12 col-md-1">
                <button type="button" id="search-button" data-url="{{ route('products.search', ['product' => $products->first()->id]) }}" class="btn btn-outline-secondary">検索</button>
            </div>        
        </form>
    </div>

    <div class="products mt-5">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>id
                        <a class="btn btn-outline-secondary" href="{{ request()->fullUrlWithQuery(['sort' => 'id', 'direction' => 'asc']) }}">↑</a>
                        <a class="btn btn-outline-secondary" href="{{ request()->fullUrlWithQuery(['sort' => 'id', 'direction' => 'desc']) }}">↓</a>
                    </th>
                    <th>商品名
                        <a class="btn btn-outline-secondary" href="{{ request()->fullUrlWithQuery(['sort' => 'product_name', 'direction' => 'asc']) }}">↑</a>
                        <a class="btn btn-outline-secondary" href="{{ request()->fullUrlWithQuery(['sort' => 'product_name', 'direction' => 'desc']) }}">↓</a>
                    </th>
                    <th>メーカー
                        <a class="btn btn-outline-secondary" href="{{ request()->fullUrlWithQuery(['sort' => 'company_name', 'direction' => 'asc']) }}">↑</a>
                        <a class="btn btn-outline-secondary" href="{{ request()->fullUrlWithQuery(['sort' => 'company_name', 'direction' => 'desc']) }}">↓</a>
                    </th>
                    <th>価格
                        <a class="btn btn-outline-secondary" href="{{ request()->fullUrlWithQuery(['sort' => 'price', 'direction' => 'asc']) }}">↑</a>
                        <a class="btn btn-outline-secondary" href="{{ request()->fullUrlWithQuery(['sort' => 'price', 'direction' => 'desc']) }}">↓</a>
                    </th>
                    <th>在庫数
                        <a class="btn btn-outline-secondary" href="{{ request()->fullUrlWithQuery(['sort' => 'stock', 'direction' => 'asc']) }}">↑</a>
                        <a class="btn btn-outline-secondary" href="{{ request()->fullUrlWithQuery(['sort' => 'stock', 'direction' => 'desc']) }}">↓</a>
                    </th>
                    <th>画像</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody id="product-list">
                @foreach($products as $product)
                    <tr id="product-row-{{ $product->id }}"><!-- 各行にユニークなIDを追加 -->
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->product_name }}</td>
                        <td>{{ $product->company->company_name }}</td>
                        <td>{{ $product->price }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>
                            @if($product->img_path)
                                <img src="{{ asset($product->img_path) }}" alt="商品画像" style="width: 100px;">
                            @else
                                画像なし
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-info btn-sm mx-1">商品詳細</a>
                            <form id="delete-form-{{ $product->id }}" action="{{ route('products.destroy', $product->id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger delete-btn" data-product-id="{{ $product->id }}" onclick="return confirm('本当に削除しますか？')">削除</button><!-- 削除ボタンにクラスとデータ属性を追加 -->
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $products->appends(request()->query())->links() }}
    </div>
</div>

<!-- jQueryとカスタムのsearch.jsスクリプトを含める -->
<script src="{{ asset('build/assets/product-search-7ae7844f.js') }}"></script>

@endsection
