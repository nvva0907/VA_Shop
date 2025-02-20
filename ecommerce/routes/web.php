<?php

use App\Livewire\ProductDetail;
use App\Livewire\ProductList;
use Illuminate\Support\Facades\Route;

Route::get('/', ProductList::class)->name('product.list');
// Route::view('/', 'home')->name('home');
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/products/{id}', ProductDetail::class)->name('product.detail');

require __DIR__ . '/auth.php';
