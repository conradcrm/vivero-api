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
        'id_proveedor','razon_social','direccion','imagen','telefono', 'estado'
    ];

    public function getKeyName(){
        return "id_proveedor";
    }

    #protected $hidden = ['created_at','updated_at'];
    public $timestamps = false;
/*
    public function plantas(){
        // 1 proveedor suministra muchas plantas
        // $this hace referencia al objeto que tengamos en ese momento de Proveedor`.
        return $this->hasMany('App\Models\Planta', 'id_proveedor', 'id_proveedor');
    }*/
}
