<?php

use App\Http\Controllers\dashboard\BookController;
use App\Http\Controllers\dashboard\CategoryController;
use App\Http\Controllers\dashboard\MediaController;
use App\Http\Controllers\dashboard\subCategoryController;
use App\Http\Controllers\dashboard\UserController;
use App\Http\Controllers\dashboard\WatchController;
use App\Http\Controllers\HomeController;
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

Route::get('/login', [HomeController::class, 'login'])->name('login');
Route::post('/login', [HomeController::class, 'authenticate'])->name('authenticate');

Route::get('/', function () {
    return redirect()->route('dashboard');
});


Route::group(['middleware' => ['auth'], 'prefix' => 'dashboard'], function () {

    Route::get('/', [HomeController::class, 'index'])->name('dashboard');

    Route::resource('categories', CategoryController::class);
    Route::resource('subcategories', subCategoryController::class);
    Route::resource('media', MediaController::class);
    Route::resource('watch', WatchController::class);
    Route::resource('books', BookController::class);
    Route::resource('users', UserController::class);

});


