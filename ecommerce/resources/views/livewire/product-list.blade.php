<div class="cards">
    <div class="card_title">Danh sách sản phẩm</div>
    <div class="card_list">
        @foreach($products as $productItem)
                @php
                    $colors = [];
                    $storages = [];
                    $priceMap = [];
                    foreach ($productItem->variants as $variant) {
                        $color = "";
                        $storage = "";
                        foreach ($variant['attributes'] as $attribute) {
                            if ($attribute['attribute_code'] === 'COLOR') {
                                $colors[$attribute['code']] = $attribute['value'] . '|' . $attribute['name'];
                                $color = $attribute['code'];
                            }
                            if ($attribute['attribute_code'] === 'STORAGE') {
                                $storages[$attribute['code']] = $attribute['value'];
                                $storage = $attribute['code'];
                            }
                        }
                        $priceMap[$color . '|' . $storage] = $variant['price'];
                    }
                @endphp

                <div class="card_item" id="product_item_{{ $productItem->product_id }}"
                    data-price="{{ json_encode($priceMap) }}">
                    <img id="card_image" src="{{ $productItem->image }}" alt="{{ $productItem->product_name }}">
                    <p id="card_name">{{ $productItem->product_name }}</p>
                    <p id="card_description">{{ $productItem->description }}</p>


                    <div class="variant">
                        <p id="price_{{ $productItem->product_id }}" class="price">
                            <span>{{ number_format($productItem->variants[0]['price']) }}</span> đ
                        </p>
                        <div class="storage-options">
                            @foreach($storages as $storage)
                                <button class="storage-btn"
                                    onclick="chooseStorage(this,'{{ $storage }}', '{{ $productItem->product_id }}')">{{ $storage }}</button>
                            @endforeach
                        </div>
                        <div class="color-options">
                            @foreach($colors as $key => $value)
                                <div class="color-container">
                                    <span class="color-circle tooltip" style="background-color: {{ explode('|', $value)[0] }};"
                                        data-tooltip="{{ explode('|', $value)[1] }}"
                                        onclick="chooseColor(this, '{{ $key }}', '{{ $productItem->product_id }}')" />
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div id="button_container">
                        <a id="card_button_detail" href="{{ route('product.detail', $productItem->product_id) }}">
                            Chi tiết
                        </a>
                        <a id="card_button_add" href="{{ route('product.detail', $productItem->product_id) }}">
                            <i class="fa fa-shopping-cart"></i>
                        </a>
                    </div>
                </div>
        @endforeach
    </div>
</div>

<script>
    let colorPick = "";
    let storagePick = "";
    let currentProductId = 0;
    function chooseColor(element, colorCode, productId) {
        colorPick = colorCode;
        if (currentProductId && currentProductId !== productId) {
            storagePick = ""
        }
        currentProductId = productId
        document.querySelectorAll(`#product_item_${productId} .color-circle`).forEach(el => {
            el.classList.remove("active");
        });
        element.classList.add("active");
        updatePrice();
    }
    function chooseStorage(element, storageCode, productId) {
        storagePick = storageCode;
        if (currentProductId && currentProductId !== productId) {
            colorPick = ""
        }
        currentProductId = productId
        updatePrice();
    }

    function updatePrice() {
        const priceInfo = JSON.parse(document.getElementById("product_item_" + currentProductId).getAttribute("data-price"));
        if (colorPick && storagePick) {
            let key = colorPick + "|" + storagePick;
            let newPrice = priceInfo[key] || "0";
            document.getElementById(`price_${currentProductId}`).innerHTML = `<span>${new Intl.NumberFormat().format(newPrice)}</span> đ`;
        }
    }
</script>