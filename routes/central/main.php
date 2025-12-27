<?php

use App\Http\Controllers\Central\AdminCatalogController;
use App\Http\Controllers\Central\CompanyController;
use App\Http\Controllers\Central\DashboardController;
use App\Http\Controllers\Central\FileController;
use App\Http\Controllers\Central\ProductsController;
use App\Http\Controllers\Central\SparePartsController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->to('/central-dashboard');
})->name('central.home');
Route::get('/central-dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/testMail', [DashboardController::class, 'testMail'])->name('testMail');

///Catalogs
Route::get('/catalog/attributes/{id}', [AdminCatalogController::class, 'getAttributes'])->name('catalog.attributes');
Route::get('/catalog/{type}', [AdminCatalogController::class, 'index'])->name('catalog.index');
Route::post('/catalog/{type}/list', [AdminCatalogController::class, 'list'])->name('catalog.list');
Route::post('/catalog/{type}/store', [AdminCatalogController::class, 'store'])->name('catalog.store');
Route::delete('/catalog/{adminCatalog}', [AdminCatalogController::class, 'destroy'])->name('catalog.destroy');
Route::get('/catalog/{type}/{id}', [AdminCatalogController::class, 'get'])->name('catalog.get');
Route::post('/catalog/{type}/updateOrder', [AdminCatalogController::class, 'updateOrder'])->name('catalog.updateOrder');

///Products
Route::resource('/products', ProductsController::class, ['names' => ['index' => 'products']]);
Route::post('/products/list', [ProductsController::class, 'list'])->name('products.list');
Route::get('/products/pdf/{pid}', [ProductsController::class, 'pdf'])->name('products.central.pdf');
Route::post('/products/sync-all', [ProductsController::class, 'syncAllProductsToTenants'])->middleware(['auth:sanctum', 'auth'])->name('products.sync.all');

///Spare Parts
Route::resource('/spare-parts', SparePartsController::class, 
    ['names' => ['index' => 'parts', 'create' => 'parts.create', 'edit' => 'parts.edit', 'store' => 'parts.store', 'destroy' => 'parts.destroy']]);
Route::post('/spare-parts/list', [SparePartsController::class, 'list'])->name('parts.list');

///Companies
Route::resource('/companies', CompanyController::class, ['names' => ['index' => 'companies']]);
Route::post('/companies/list', [CompanyController::class, 'list'])->name('companies.list');
Route::post('/companies/changeStatus/{cid}', [CompanyController::class, 'changeStatus'])->name('companies.change.status');

///TMP files
Route::post('/uploads/tmp/{type}', [FileController::class, 'uploadFile'])->name('upload.tmp');

Route::get('/test2', [ProfileController::class, 'edit'])->name('test2');
Route::get('/test3', [ProfileController::class, 'edit'])->name('test3');


Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

