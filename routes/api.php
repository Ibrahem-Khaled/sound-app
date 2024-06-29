<?php

use App\Http\Controllers\api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('categorieyVideo', [ApiController::class, 'categorieyVideo']);
Route::get('categorieyAudio', [ApiController::class, 'categorieyAudio']);
Route::get('subCategoreys/{id}', [ApiController::class, 'subCategoreys']);
Route::get('media/{type}', [ApiController::class, 'media']);
Route::get('sovieMedia/{id}', [ApiController::class, 'sovieMedia']);
Route::get('aboutUs', [ApiController::class, 'aboutUs']);
