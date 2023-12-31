<?php

use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShipmentController;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => true,
        'canResetPassword' => true
    ]);
})->name('home');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard')->breadcrumb('Dashboard');;

    Route::prefix('product-categories')->group(function () {
        Route::get('/', [ProductCategoryController::class, 'index'])->name('product_categories')->breadcrumb('Product Categories');
        Route::get('/create', [ProductCategoryController::class, 'create'])->name('product_categories.create')->breadcrumb('Create', 'product_categories');
        Route::post('/store', [ProductCategoryController::class, 'store'])->name('product_categories.store');
        Route::get('/{id}', [ProductCategoryController::class, 'show'])->name('product_categories.show')
            ->breadcrumb(fn (int $id) => ProductCategory::find($id)?->getAttribute('name'), 'product_categories');
        Route::patch('/{id}', [ProductCategoryController::class, 'update'])->name('product_categories.update');
        Route::delete('/{id}', [ProductCategoryController::class, 'delete'])->name('product_categories.delete');
    });

    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('products')->breadcrumb('Products');
        Route::get('/create', [ProductController::class, 'create'])->name('products.create')->breadcrumb('Create', 'products');
        Route::post('/store', [ProductController::class, 'store'])->name('products.store');
        Route::get('/{id}', [ProductController::class, 'show'])->name('products.show')
            ->breadcrumb(fn (int $id) => Product::find($id)?->getAttribute('name'), 'products');
        Route::patch('/{id}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/{id}', [ProductController::class, 'delete'])->name('products.delete');
    });

    Route::prefix('product-attributes')->group(function () {
        Route::get('/', [ProductAttribute::class, 'index'])->name('product_attributes')->breadcrumb('Attributes');
        Route::get('/create', [ProductAttribute::class, 'create'])->name('product_attributes.create')->breadcrumb('Create', 'product_attributes');
        Route::post('/store', [ProductAttribute::class, 'store'])->name('product_attributes.store');
        Route::get('/{id}', [ProductAttribute::class, 'show'])->name('product_attributes.show')->breadcrumb('Attribute Details', 'product_attributes');
        Route::patch('/{id}', [ProductAttribute::class, 'update'])->name('product_attributes.update');
        Route::delete('/{id}', [ProductAttribute::class, 'delete'])->name('product_attributes.delete');
    });

    Route::prefix('shipments')->group(function () {
        Route::get('/', [ShipmentController::class, 'index'])->name('shipments')->breadcrumb('Shipments');
        Route::get('/create', [ShipmentController::class, 'create'])->name('shipments.create')->breadcrumb('Create', 'shipments');
        Route::post('/store', [ShipmentController::class, 'store'])->name('shipments.store');
        Route::get('/{id}', [ShipmentController::class, 'show'])->name('shipments.show')->breadcrumb('Shipment Details', 'shipments');
        Route::patch('/{id}', [ShipmentController::class, 'update'])->name('shipments.update');
        Route::delete('/{id}', [ShipmentController::class, 'delete'])->name('shipments.delete');
    });
});
