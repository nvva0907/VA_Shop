<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    @vite(['resources/css/app.css', 'resources/js/app.js']) <!-- Nếu dùng Vite -->
    @livewireStyles
</head>
<body>
    <h1>Chào mừng đến với cửa hàng!</h1>

    <!-- Nhúng component Livewire ProductList -->
    <livewire:product-list />

    @livewireScripts
</body>
</html>
