<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Admin\UserCrudController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\AdController;
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
Route::apiResource('categories', CategoryController::class);
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::middleware('admin')->group(function () {
    // Маршруты, доступные только для администраторов
});
Route::middleware('auth:sanctum')->group(function () {
    Route::get('profile', [ProfileController::class, 'show']);
    Route::put('profile', [ProfileController::class, 'update']);
});
Route::group(['prefix' => 'ads'], function () {
    Route::get('/', [AdController::class, 'index'])->name('ads.index');
    Route::post('/', [AdController::class, 'store'])->name('ads.store');
    Route::get('/{id}', [AdController::class, 'show'])->name('ads.show');
    Route::put('/{id}', [AdController::class, 'update'])->name('ads.update');
    Route::delete('/{id}', [AdController::class, 'destroy'])->name('ads.destroy');
});
