<div class="cards">
    <div class="card_title">
        Danh sách sản phẩm
    </div>
    <div class="card_list">
        @foreach($products as $productItem)
            <div class="card_item">
                <img id="card_image" src="{{ $productItem->image }}" alt="{{ $productItem->name }}">
                <p id="card_name">{{ $productItem->name }}</p>
                <p id="card_description">{{ $productItem->description }}</p>

                @if (!empty($productItem->variants))
                    @foreach($productItem->variants as $variant)
                        <div class="variant">
                            <p id="card_price">{{ number_format($variant['price']) }} đ</p>
                            <p id="card_atr">
                                @foreach($variant['attributes'] as $attribute)
                                    <span class="attribute">{{ $attribute['attribute_code'] }}: {{ $attribute['value'] }}</span><br>
                                @endforeach
                            </p>
                        </div>
                    @endforeach
                @endif

                <div id="button_container">
                    <a id="card_button_detail" href="{{ route('product.detail', $productItem->id) }}">
                        Chi tiết
                    </a>
                    <a id="card_button_add" href="{{ route('product.detail', $productItem->id) }}">
                        <i class="fa fa-shopping-cart"></i>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
