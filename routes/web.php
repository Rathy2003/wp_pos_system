<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SaleController as AdminSaleController;
use App\Http\Controllers\SaleController;

use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\StoreController;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::group(['prefix' => 'admin','middleware' => ['auth','role:admin|stockholder|developer']], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Stores
    Route::group(['middleware' => ['role:developer']], function () {
        Route::resource('stores', StoreController::class);
    });

    // Products
    Route::group(['middleware' => ['permission:full manage product']], function () {
        Route::get('/products', [ProductController::class, 'index'])->name('admin.products');
        Route::get('/products/create', [ProductController::class, 'create'])->name('admin.products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('admin.products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('admin.products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('admin.products.destroy');
    });

    // Categories
    Route::group(['middleware' => ['permission:full manage category']], function () {
        Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories');
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('admin.categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('admin.categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');
    });


    // Customers
    Route::group(['middleware' => ['permission:full manage customer']], function () {
        Route::resource('customers', CustomerController::class);
    });

    // Sales
    Route::group(['middleware' => ['permission:full manage sale']], function () {
        Route::get('/sales', [AdminSaleController::class, 'index'])->name('admin.sales');
        Route::get('/sales/{sale}', [AdminSaleController::class, 'show'])->name('admin.sales.show');
    });

    Route::group(['middleware' => ['role:admin']], function () {
        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports');
        Route::get('/reports/sales', [ReportController::class, 'sales'])->name('admin.reports.sales');
        Route::get('/reports/products', [ReportController::class, 'products'])->name('admin.reports.products');
    });

    

    // Settings
    Route::group(['middleware' => ['permission:full manage setting']], function () {
        Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings');
        Route::post('/settings', [SettingController::class, 'update'])->name('admin.settings.update');
    });

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('admin.profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');

    // Users
    Route::group(['middleware' => ['permission:full manage user']], function () {
        Route::resource('users', UserController::class);
    });
});


// pos routes
Route::group(['middleware' => ['auth','role:admin|seller']], function () { 
    Route::get('/pos/invoice', function () {
        return view('pos.invoice');
    })->name('pos.invoice');

    Route::get('/', function () {
        return view('pos.index');
    });
 });


 Route::group(['prefix' => 'api'], function () {
    Route::get('roducts',function(){
        return App\Models\Product::where('store_id', auth()->user()->store_id)->get();
    })->name('api.products');
    Route::post('/products', [ProductController::class, 'store'])->name('api.products.store');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('api.products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('api.products.destroy');

    Route::get('/sales', [SaleController::class, 'index'])->name('api.sales');
    Route::post('/sales', [SaleController::class, 'store'])->name('api.sales.store'); 

    // categories
    Route::get('/categories',function(){
        return App\Models\Category::where('store_id', auth()->user()->store_id)->get(['id', 'name']);
    })->name('api.categories');
});




// // API Routes
// Route::get('/api/categories', function () {
//     return App\Models\Category::where('status', true)->get(['id', 'name']);
// });

// Reports Routes
Route::prefix('admin/reports')->name('admin.reports.')->middleware(['auth','role:admin'])->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('index');
    Route::get('/sales', [App\Http\Controllers\Admin\ReportController::class, 'sales'])->name('sales');
    Route::get('/products', [App\Http\Controllers\Admin\ReportController::class, 'products'])->name('products');
    Route::get('/inventory', [App\Http\Controllers\Admin\ReportController::class, 'inventory'])->name('inventory');
});

// Settings Routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('admin.settings');
    Route::put('/settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('admin.settings.update');
});
