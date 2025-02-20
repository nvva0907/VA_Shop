
@foreach(Cart::content() as $item)
    <p>{{ $item->name }} - {{ $item->qty }} x {{ $item->price }} VNĐ</p>
@endforeach
<a href="{{ route('checkout') }}">Thanh toán</a>
