<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SoundController;
use App\Http\Controllers\VideoController;

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

Route::get('/', function () {
    return response()->json(['message' => 'Sucesso', 'details' => "Teste realizado com sucesso."], 200);
});


Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/validade', [AuthController::class, 'validade']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

Route::prefix('videos')->group(function () {
    Route::get('/', [VideoController::class, 'index']);
    Route::get('/{id}', [VideoController::class, 'show']);
    Route::post('/', [VideoController::class, 'store'])->middleware('auth:sanctum');
    Route::put('/{id}', [VideoController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/{id}', [VideoController::class, 'destroy'])->middleware('auth:sanctum');
});

Route::prefix('sounds')->group(function () {
    Route::get('/', [SoundController::class, 'index']);
    Route::get('/{id}', [SoundController::class, 'show']);
    Route::post('/', [SoundController::class, 'store'])->middleware('auth:sanctum');
    Route::put('/{id}', [SoundController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/{id}', [SoundController::class, 'destroy'])->middleware('auth:sanctum');
});

Route::prefix('home')->group(function () {
    Route::get('/', [HomeController::class, 'index']);
});
