<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Vogueora Apparel Inventory Management System (Slide 12-16, 18)
|--------------------------------------------------------------------------
|
| Routing structure following Laravel fundamental principles:
| Request -> Route -> Controller -> Model -> Database -> Response
|
*/

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Authenticated Application Routes
Route::middleware('auth')->group(function () {
    // 1. Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // 2. Resource Controller: Products (Slide 18 & 38)
    Route::resource('products', ProductController::class);

    // 3. Resource Controller: Categories (Slide 16 & 18)
    Route::resource('categories', CategoryController::class);

    // 4. Resource Controller: Suppliers (Slide 18)
    Route::resource('suppliers', SupplierController::class);

    // 5. Stock Movements (Barang Masuk & Keluar)
    Route::prefix('stock')->name('stock.')->group(function () {
        Route::get('/', [StockMovementController::class, 'index'])->name('index');
        Route::get('/in', [StockMovementController::class, 'createIn'])->name('in');
        Route::post('/in', [StockMovementController::class, 'storeIn'])->name('storeIn');
        Route::get('/out', [StockMovementController::class, 'createOut'])->name('out');
        Route::post('/out', [StockMovementController::class, 'storeOut'])->name('storeOut');
    });

    // 6. Aggregate Reports (Slide 28)
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/print', [ReportController::class, 'print'])->name('print');
    });

    // 7. Administration: Users (Admin Only - Slide 16 & 38)
    Route::middleware('admin')->group(function () {
        Route::resource('users', UserController::class);
    });
});
