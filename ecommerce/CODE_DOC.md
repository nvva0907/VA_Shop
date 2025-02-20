# PROJECT INIT DOCUMENTAION

# 1. Cài đặt Composer trên Ubuntu
sudo apt update && sudo apt upgrade -y
sudo apt install php-cli unzip php-curl php-xml php-mysql -y
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
composer -V

# 2. Cài đặt Laravel + Livewire
composer create-project --prefer-dist laravel/laravel ecommerce
cd ecommerce
composer require livewire/livewire
composer require laravel/breeze
php artisan breeze:install livewire

# 2.1 Thêm Livewire vào resources/views/layouts/app.blade.php
<!DOCTYPE html>
<html>
<head>
    ...
    <!-- Livewire Styles -->
    @livewireStyles
</head>
<body>
    ...
    <!-- Livewire Scripts -->
    @livewireScripts
</body>
</html>

# 2.2 Thiết kế Database & Migration
php artisan make:model Product -m
php artisan make:model Category -m
php artisan make:model Order -m

Sửa cấu trúc bảng products (database/migrations/2025_02_18_031505_create_products_table.php):
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->decimal('price', 10, 2);
    $table->text('description')->nullable();
    $table->integer('stock')->default(0);
    $table->string('image')->nullable();
    $table->foreignId('category_id')->constrained();
    $table->timestamps();
});

... Sửa các cấu trúc bảng khác

## Chạy migration
Chỉnh sửa thông tin kết nối DB trong .env
php artisan migrate

# 2.3 Xây dựng trang danh sách & chi tiết sản phẩm
php artisan make:livewire ProductList
php artisan make:livewire ProductDetail

Trong app/Http/Livewire/ProductList.php
Sửa thành 
public function render() {
    $products = Product::paginate(10);
    return view('livewire.product-list', compact('products'));
}
Trong resources/views/livewire/product-list.blade.php
Sửa thành 
@foreach($products as $product)
    <div>
        <img src="{{ $product->image }}" alt="{{ $product->name }}">
        <h2>{{ $product->name }}</h2>
        <p>{{ $product->price }} VNĐ</p>
        <a href="{{ route('product.detail', $product->id) }}">Xem chi tiết</a>
    </div>
@endforeach
Để hiển thị trang này, thêm vào routes/web.php
Route::get('/products', ProductList::class)->name('product.list');
Route::get('/products/{id}', ProductDetail::class)->name('product.detail');

# 2.4  Xây dựng Giỏ hàng & Thanh toán
php artisan make:livewire Cart
php artisan make:livewire Checkout
Trong Cart.php thêm hàm:

public function addToCart($productId) {
    $product = Product::find($productId);
    Cart::add($product->id, $product->name, 1, $product->price);
    session()->flash('message', 'Đã thêm vào giỏ hàng!');
}
Giao diện giỏ hàng trong cart.blade.php:
@foreach(Cart::content() as $item)
    <p>{{ $item->name }} - {{ $item->qty }} x {{ $item->price }} VNĐ</p>
@endforeach
<a href="{{ route('checkout') }}">Thanh toán</a>

# 2.5 Gen Key
php artisan key:generate

# 2.6 Run App
php artisan serve