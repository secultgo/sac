@foreach ($products as $row => $product)
    <a href="{{ route('products.show', $row) }}">
        <h1>{{ $product['product_name'] }}</h1>       
    </a>

    <p>{{ $product['product_description'] }}</p>
    <p>{{ $product['product_code'] }}</p>
    <hr>
@endforeach

