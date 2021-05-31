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
        'id_proveedor','nombre','direccion', 'correo','imagen','telefono', 'estado'
    ];

    public function getKeyName(){
        return "id_proveedor";
    }

    #protected $hidden = ['created_at','updated_at'];
    public $timestamps = false;
}
