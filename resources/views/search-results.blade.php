@if($products->isEmpty())
    <tr>
        <td colspan="8">該当する商品はありません。</td>
    </tr>
@else
    @foreach ($products as $product)
        <tr>
            <td>{{ $product->id }}</td>
            <td>{{ $product->product_name }}</td>
            <td>{{ $product->company->company_name }}</td>
            <td>{{ $product->price }}円</td>
            <td>{{ $product->stock }}</td>
            <td>
                @if($product->img_path)
                    <img src="{{ asset($product->img_path) }}" alt="商品画像" width="100">
                @else
                    画像なし
                @endif
            </td>
            <td>
                <a href="{{ route('products.show', $product->id) }}" class="btn btn-info btn-sm mx-1">詳細表示</a>
                <button onclick="confirmDelete({{ $product->id }})" class="btn btn-danger btn-sm mx-1">削除</button>
                <form id="deleteForm{{ $product->id }}" method="POST" action="{{ route('products.destroy', $product->id) }}" class="d-inline">
                    @csrf
                    @method('DELETE')
                </form>
            </td>
        </tr>
        <script>
            function confirmDelete(productId) {
                if (confirm('本当に削除しますか？')) {
                    document.getElementById('deleteForm' + productId).submit();
                }
            }
        </script>
    @endforeach
@endif
