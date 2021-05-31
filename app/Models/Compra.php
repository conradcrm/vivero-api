<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;
    protected $table = 'compras';
    protected $primaryKey = 'folio_compra';
    protected $fillable = [
        'folio_compra', 'id_proveedor'
    ];
    
    #protected $hidden = ['created_at','updated_at'];
    #public $timestamps = false;

    public function proveedores(){
        // 1 compra es atendidad por una proveedor
        // $this hace referencia al objeto que tengamos en ese momento de Compra.
        return $this->belongsTo('App\Models\Proveedor', 'id_proveedor', 'id_proveedor');
    }
}
