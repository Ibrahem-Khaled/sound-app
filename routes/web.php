<?php

use App\Http\Controllers\dashboard\BookController;
use App\Http\Controllers\dashboard\CategoryController;
use App\Http\Controllers\dashboard\MediaController;
use App\Http\Controllers\dashboard\subCategoryController;
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

Route::get('/', function () {
    return view('welcome');
});


Route::resource('categories', CategoryController::class);
Route::resource('subcategories', subCategoryController::class);
Route::resource('media', MediaController::class);
Route::resource('books', BookController::class);

