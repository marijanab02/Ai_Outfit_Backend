<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

use App\Http\Controllers\ClothingItemController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/wardrobe', [ClothingItemController::class, 'store']);
    Route::get('/wardrobe', [ClothingItemController::class, 'index']); // nova ruta za prikaz ormara
    Route::delete('/wardrobe/{id}', [ClothingItemController::class, 'destroy']);
});
use App\Http\Controllers\OutfitController;

Route::get('/outfit/suggest', [OutfitController::class, 'suggest'])
    ->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->post(
    '/outfit/select',
    [OutfitController::class, 'store']
);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/outfits', [OutfitController::class, 'index']);
});
use App\Http\Controllers\UserController;

Route::middleware('auth:sanctum')->group(function () {
    Route::patch('/users/{user}', [UserController::class, 'update']);
});
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);