<?php

use Illuminate\Support\Facades\Route;
use App\Models\Categoria;
use App\Models\Planta;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    /* 
    $plant = new Planta();
$plant -> nombre = 'rosa azul';
$plant -> descripcion = 'Una bella rosa azul cielo';
$plant -> precio_venta = 159.90;
$plant -> precio_compra = 120.00;
$plant -> imagen = '\imagen';
$plant -> cantidad = 5;
$plant -> id_categoria = 2;
$plant->save();*/
#$plant = Planta::find(2)->categoria;
#$plant = Categoria::find(2)->plantas;
#return $plant;
});