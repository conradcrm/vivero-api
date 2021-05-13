<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;
    protected $table = 'proveedores';
    protected $primarykey = 'id_proveedor';
    protected $fillabel = [
        'id_proveedor','razon_social','estado','telefono','direccion', 'imagen' 
    ];
    protected $hidden = ['created_at','updated_at'];
    /*public function plantas(){
        // 1 proveedor suministra muchas plantas
        // $this hace referencia al objeto que tengamos en ese momento de Categoría.
        return $this->hasMany('App\Planta');
    }*/
}
