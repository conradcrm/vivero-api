<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;
    protected $table = 'proveedores';
    protected $primarykey = 'id_proveedor';
    protected $fillable = [
        'id_proveedor','nombre','direccion', 'correo','imagen','telefono', 'estado','delete'
    ];

    public function getKeyName(){
        return "id_proveedor";
    }

    #protected $hidden = ['created_at','updated_at'];
    public $timestamps = false;

    public function plantas(){
        // 1 proveedor suministra muchas plantas
        // $this hace referencia al objeto que tengamos en ese momento de Proveedor`.
        return $this->hasMany('App\Models\Planta', 'id_proveedor', 'id_proveedor');
    }
    public function compras(){
        // 1 proveedor atiende  a una compra
        // $this hace referencia al objeto que tengamos en ese momento de Compra.
        return $this->hasMany('App\Models\Compra', 'id_proveedor', 'id_proveedor');
    }
}
