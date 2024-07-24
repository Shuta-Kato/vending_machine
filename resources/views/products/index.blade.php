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
                    <th>
                        id
                        <button type="button" class="btn btn-outline-secondary btn-sm sort-link" data-sort-by="id" data-sort-order="asc">↑</button>
                        <button type="button" class="btn btn-outline-secondary btn-sm sort-link" data-sort-by="id" data-sort-order="desc">↓</button>
                    </th>
                    <th>
                        商品名
                        <button type="button" class="btn btn-outline-secondary btn-sm sort-link" data-sort-by="product_name" data-sort-order="asc">↑</button>
                        <button type="button" class="btn btn-outline-secondary btn-sm sort-link" data-sort-by="product_name" data-sort-order="desc">↓</button>
                    </th>
                    <th>
                        メーカー
                        <button type="button" class="btn btn-outline-secondary btn-sm sort-link" data-sort-by="company_name" data-sort-order="asc">↑</button>
                        <button type="button" class="btn btn-outline-secondary btn-sm sort-link" data-sort-by="company_name" data-sort-order="desc">↓</button>
                    </th>
                    <th>
                        価格
                        <button type="button" class="btn btn-outline-secondary btn-sm sort-link" data-sort-by="price" data-sort-order="asc">↑</button>
                        <button type="button" class="btn btn-outline-secondary btn-sm sort-link" data-sort-by="price" data-sort-order="desc">↓</button>
                    </th>
                    <th>
                        在庫数
                        <button type="button" class="btn btn-outline-secondary btn-sm sort-link" data-sort-by="stock" data-sort-order="asc">↑</button>
                        <button type="button" class="btn btn-outline-secondary btn-sm sort-link" data-sort-by="stock" data-sort-order="desc">↓</button>
                    </th>                    
                    <th>画像</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody id="product-list">
                @foreach($products as $product)
                    <tr id="product-row-{{ $product->id }}">
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
                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-info">商品詳細</a>
                            <form id="delete-form-{{ $product->id }}" action="{{ route('products.destroy', $product->id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger delete-btn" data-product-id="{{ $product->id }}" onclick="return confirm('本当に削除しますか？')">削除</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div id="pagination">
            {{ $products->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<!-- jQueryとカスタムのsearch.jsスクリプトを含める -->
<script src="{{ asset('build/assets/product-search-dbd32648.js') }}"></script>
<script>
    var sortUrl = "{{ route('products.search', ['product' => $products->first()->id]) }}";
</script>


@endsection