<?php

use App\Http\Controllers\AgendaController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\ContentGalleryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\SubcategoryController;
use App\Models\Content;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [LoginController::class, 'login'])->name('login');
Route::post('/', [LoginController::class, 'authenticate'])->name('auth');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    // agenda
    Route::prefix('/agenda')->name('agenda.')->group(function(){
        Route::get('/index', [AgendaController::class, 'index'])->name('index');
    });

    // banner
    Route::prefix('/banner')->name('banner.')->group(function(){
        Route::get('/index', [BannerController::class, 'index'])->name('index');
        Route::post('/index', [BannerController::class, 'store'])->name('post');
        Route::get('/datatables', [BannerController::class, 'datatables'])->name('datatables');
        Route::get('/get/{id}', [BannerController::class, 'show'])->name('get');
        Route::delete('/delete/{id}', [BannerController::class, 'destroy'])->name('delete');
    });

    // category
    Route::prefix('/category')->name('category.')->group(function(){
        Route::get('/index', [CategoryController::class, 'index'])->name('index');
        Route::post('/index', [CategoryController::class, 'store'])->name('post');
        Route::get('/datatables', [CategoryController::class, 'datatables'])->name('datatables');
        Route::get('/get/{id}', [CategoryController::class, 'show'])->name('get');
        Route::delete('/delete/{id}', [CategoryController::class, 'destroy'])->name('delete');
    });

    // subcategory
    Route::prefix('/subcategory')->name('subcategory.')->group(function(){
        Route::get('/index', [SubcategoryController::class, 'index'])->name('index');
        Route::post('/index', [SubcategoryController::class, 'store'])->name('post');
        Route::get('/datatables', [SubcategoryController::class, 'datatables'])->name('datatables');
        Route::get('/get/{id}', [SubcategoryController::class, 'show'])->name('get');
        Route::delete('/delete/{id}', [SubcategoryController::class, 'destroy'])->name('delete');
        // get
        Route::get('/categoryid', [SubcategoryController::class, 'getCategoryId'])->name('getcategory');
    });

    Route::prefix('/content')->name('content.')->group(function(){
        Route::get('/index', [ContentController::class, 'index'])->name('index');
        Route::post('/index', [ContentController::class, 'store'])->name('post');
        Route::get('/datatables', [ContentController::class, 'datatables'])->name('datatables');
        Route::get('/get/{id}', [ContentController::class, 'show'])->name('get');
        Route::delete('/delete/{id}', [ContentController::class, 'destroy'])->name('delete');
        // get
        Route::get('/category', [ContentController::class, 'getCategoryId'])->name('category');
        Route::get('/subcategory', [ContentController::class, 'getSubcategoryId'])->name('subcategory');
        Route::get('/feature', [ContentController::class, 'getFeatureId'])->name('feature');
    });

    Route::prefix('/feature')->name('feature.')->group(function(){
        Route::get('/index', [FeatureController::class, 'index'])->name('index');
        Route::post('/index', [FeatureController::class, 'store'])->name('post');
        Route::get('/datatables', [FeatureController::class, 'datatables'])->name('datatables');
        Route::get('/get/{id}', [FeatureController::class, 'show'])->name('get');
        Route::delete('/delete/{id}', [FeatureController::class, 'destroy'])->name('delete');
    });

    Route::prefix('/gallery')->name('gallery.')->group(function(){
        Route::get('/index', [ContentGalleryController::class, 'index'])->name('index');
        Route::post('/index', [ContentGalleryController::class, 'store'])->name('post');
        Route::get('/datatables', [ContentGalleryController::class, 'datatables'])->name('datatables');
        Route::get('/get/{id}', [ContentGalleryController::class, 'show'])->name('get');
        Route::delete('/delete/{id}', [ContentGalleryController::class, 'destroy'])->name('delete');
        // get
        Route::get('/content', [ContentGalleryController::class, 'getContent'])->name('content');
    });
});
