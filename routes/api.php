<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\PlantaController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\DetalleCompraController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']); //no disponible no autenticados

    Route::get('/categories', [CategoriaController::class, 'index']); //Todas las categorías
    Route::get('/category/{id_categoria}', [CategoriaController::class, 'show']); //Una categoría

    Route::get('/providers', [ProveedorController::class, 'index']); //Todos los proveedores
    Route::get('/provider/{id_proveedor}', [ProveedorController::class, 'show']); //Un proveedor

    Route::get('/plants', [PlantaController::class, 'index']); //Todas las plantas
    Route::get('/plant/{id_planta}', [PlantaController::class, 'show']); //Una planta

    Route::get('/shopping', [DetalleCompraController::class, 'index']); //Todas las compras


Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::get('/userinfo', [AuthController::class, 'userinfo']); //no disponible no autenticados
    Route::patch('/update-user', [AuthController::class, 'update']); //no disponible no autenticados

    Route::post('/create-category', [CategoriaController::class, 'store']); //Registra una categoría
    Route::patch('/update-category/{id_categoria}', [CategoriaController::class, 'update']); //Actualiza (solo lo necesario)
    Route::patch('/status-category/{id_categoria}', [CategoriaController::class, 'delete']); //Actualiza el estado (desabilita)
    Route::put('/update-category/{id_categoria}', [CategoriaController::class, 'update']); //Actualiza (todo)
    Route::delete('/delete-category/{id_categoria}', [CategoriaController::class, 'destroy']); //eliminar categoría

    Route::patch('/delete-category/{id_categoria}', [CategoriaController::class, 'deleteCategory']); //elimina el elemento guiño, guiño.


    Route::post('/create-provider', [ProveedorController::class, 'store']); //Registra un proveedor
    Route::patch('/update-provider/{id_proveedor}', [ProveedorController::class, 'update']); //Actualiza (solo lo necesario)
    Route::patch('/status-provider/{id_proveedor}', [ProveedorController::class, 'delete']); //Actualiza el estado (desabilita)
    Route::put('/update-provider/{id_proveedor}', [ProveedorController::class, 'update']); //Actualiza (todo)
    Route::delete('/delete-provider/{id_proveedor}', [ProveedorController::class, 'destroy']); //Eliminar proveedor
    Route::patch('/delete-provider/{id_proveedor}', [ProveedorController::class, 'deleteProvider']); //elimina el elemento guiño, guiño.


    Route::post('/create-plant', [PlantaController::class, 'store']); //Registra una planta
    Route::patch('/update-plant/{id_planta}', [PlantaController::class, 'update']); //Actualiza (solo lo necesario)
    Route::patch('/status-plant/{id_planta}', [PlantaController::class, 'delete']); //Actualiza el estado (desabilita)
    Route::put('/update-plant/{id_planta}', [PlantaController::class, 'update']); //Actualiza (todo)
    Route::delete('/delete-plant/{id_planta}', [PlantaController::class, 'destroy']); //Eliminar planta
    Route::patch('/delete-plant/{id_planta}', [PlantaController::class, 'deletePlant']); //elimina el elemento guiño, guiño.


    // Route::get('/shopping/{folio_compra}', [ShoppingController::class, 'show']); //Una compra
    Route::post('/create-shopping', [DetalleCompraController::class, 'store']); //Registra una compra
    // Route::patch('/update-shopping/{folio_compra}', [ShoppingController::class, 'update']); //Actualiza (solo lo necesario)
    Route::patch('/delete-shopping/{folio_compra}', [CompraController::class, 'deleteCompra']); //Elimina el elemento guiño guiño
    // Route::put('/update-shopping/{folio_compra}', [ShoppingController::class, 'update']); //Actualiza (todo)

    //Route::patch('/status-shopping/{folio_compra}', [CompraController::class, 'update']);
    Route::patch('/status-shopping/{folio_compra}', 'App\Http\Controllers\CompraController@update');
});