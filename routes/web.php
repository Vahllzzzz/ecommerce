<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// tugas
Route::get('/about', function () {
    return view('about');
});

// latihan
Route::get('/sapa/{nama}', function ($nama) {
    return "Hello, $nama! Selamat datang di Hahay Shop.";
});

Route::get('/kategori/{nama?}', function ($nama = 'Semua') {
    return "Menampilkan produk pada kategori: $nama";
});

Route::get('/produk/{id}', function ($id) {
    return "Detail Produk #$id di Hahay Shop.";
})->name('produk.detail');