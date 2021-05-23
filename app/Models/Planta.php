<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Planta extends Model
{
    use HasFactory;
    protected $table = 'plantas';
    protected $primaryKey = 'id_planta';
    protected $fillable = [
        'id_planta', 'nombre', 'descripcion', 'precio_venta', 'precio_compra', 'imagen', 'cantidad', 'estado', 'id_categoria', 'id_proveedor'
    ];
    
    #protected $hidden = ['created_at','updated_at'];
    public $timestamps = false;

    public function categoria(){
        // 1 planta pertenece a una categoría
        // $this hace referencia al objeto que tengamos en ese momento de Planta.
        return $this->belongsTo('App\Models\Categoria', 'id_categoria', 'id_categoria');
    }

    public function proveedor(){
        // 1 planta pertenece a una categoría
        // $this hace referencia al objeto que tengamos en ese momento de Planta.
        return $this->belongsTo('App\Models\Proveedor', 'id_proveedor', 'id_proveedor');
    }
}