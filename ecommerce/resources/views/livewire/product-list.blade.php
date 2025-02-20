<!-- resources/views/livewire/product-list.blade.php -->
<div class="product-list grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 p-4">
    @foreach($products as $productItem)
        <div class="product-item bg-white p-4 rounded-lg shadow-md">
            <img src="{{ $productItem->image }}" alt="{{ $productItem->name }}" class="w-full h-40 object-cover rounded-md">
            <h2 class="text-lg font-semibold mt-2">{{ $productItem->name }}</h2>
            <p class="text-red-500 font-bold">{{ number_format($productItem->price) }} VNĐ</p>
            <a href="{{ route('product.detail', $productItem->id) }}" 
               class="block text-center bg-blue-500 text-white py-2 rounded-md mt-2 hover:bg-blue-600">
                Xem chi tiết
            </a>
        </div>
    @endforeach
</div>
