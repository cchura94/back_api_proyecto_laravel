<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProductoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//  /v1/auth
Route::prefix('v1/auth')->group(function(){

    // /v1/auth/login,   /v1/auth/register
    Route::post("/login", [AuthController::class, "login"]);
    Route::post("/register", [AuthController::class, "registro"]);

    // /v1/auth/perfil,   /v1/auth/logout
    Route::middleware('auth:sanctum')->group(function(){
        Route::get("/perfil", [AuthController::class, "miPerfil"]);
        Route::post("/logout", [AuthController::class, "cerrar"]);
    });
});

// CRUD Api para Usuario

Route::prefix('admin')->middleware('auth:sanctum')->group(function(){
    
    Route::apiResource("usuario", UsuarioController::class);// ->middleware('auth:sanctum');
    Route::apiResource("categoria", CategoriaController::class);
    Route::apiResource("producto", ProductoController::class);
    Route::apiResource("cliente", ClienteController::class);
    Route::apiResource("pedido", PedidoController::class);
});