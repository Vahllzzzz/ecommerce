<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\Admin\AdminController;
use App\Services\MidtransService;
use App\Http\Controllers\MidtransNotificationController;

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
Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Auth::routes();
Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])
    ->name('home');
    Route::get('/profile', [ProfileController::class, 'edit'])
    ->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])
    ->name('profile.update');   
});

Route::controller(GoogleController::class)->group(function () {
    Route::get('/auth/google', 'redirect')
        ->name('auth.google');

    Route::get('/auth/google/callback', 'callback')
        ->name('auth.google.callback');
        
});
// routes/web.php

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.updateAvatar');
    Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])->name('profile.avatar.destroy');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});

Route::get('/', [HomeController::class, 'index'])->name('home');

// Katalog Produk
Route::get('/products', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/products/{slug}', [CatalogController::class, 'show'])->name('catalog.show');

Route::middleware('auth')->group(function () {
    // Keranjang Belanja
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{item}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{item}', [CartController::class, 'remove'])->name('cart.remove');

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // Pesanan Saya
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/success', [OrderController::class, 'success'])->name('orders.success');
    Route::get('/orders/{order}/pending', [OrderController::class, 'pending'])->name('orders.pending');

    // Catalog
    route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
    route::get('/products/{slug}', [CatalogController::class, 'show'])->name('catalog.show');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('admin', [DashboardController::class, 'dashboard'])
        ->name('admin.dashboard');
});
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    // Manajemen Pesanan
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
});

    // Produk CRUD
    Route::resource('products', AdminProductController::class);

    // Kategori CRUD
    Route::resource('categories', AdminCategoryController::class);

route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('categories', AdminCategoryController::class);
    Route::resource('products', AdminProductController::class);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])
        ->name('checkout.index');

    Route::post('/checkout', [CheckoutController::class, 'store'])
        ->name('checkout.store');
});

    Route::middleware('auth')->group(function() {
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
});

Route::post('midtrans/notification', [MidtransNotificationController::class, 'handle'])
    ->name('midtrans.notification');

// Route::get('/debug-midtrans', function () {
//     // Cek apakah config terbaca
//     $config = [
//         'merchant_id'   => config('midtrans.merchant_id'),
//         'client_key'    => config('midtrans.client_key'),
//         'server_key'    => config('midtrans.server_key') ? '***SET***' : 'NOT SET',
//         'is_production' => config('midtrans.is_production'),
//     ];

//     // Test buat dummy token
//     try {
//         $service = new MidtransService();

//         // Buat dummy order untuk testing
//         $dummyOrder = new \App\Models\Order();
//         $dummyOrder->order_number = 'TEST-' . time();
//         $dummyOrder->total_amount = 10000;
//         $dummyOrder->shipping_cost = 0;
//         $dummyOrder->shipping_name = 'Test User';
//         $dummyOrder->shipping_phone = '08123456789';
//         $dummyOrder->shipping_address = 'Jl. Test No. 123';
//         $dummyOrder->user = (object) [
//             'name'  => 'Tester',
//             'email' => 'test@example.com',
//             'phone' => '08123456789',
//         ];
//         // Dummy items
//         $dummyOrder->items = collect([
//             (object) [
//                 'product_id'   => 1,
//                 'product_name' => 'Produk Test',
//                 'price'        => 10000,
//                 'quantity'     => 1,
//             ],
//         ]);

//         $token = $service->createSnapToken($dummyOrder);

//         return response()->json([
//             'status'  => 'SUCCESS',
//             'message' => 'Berhasil terhubung ke Midtrans!',
//             'config'  => $config,
//             'token'   => $token,
//         ]);

//     } catch (\Exception $e) {
//         return response()->json([
//             'status'  => 'ERROR',
//             'message' => $e->getMessage(),
//             'config'  => $config,
//         ], 500);
//     }
// });