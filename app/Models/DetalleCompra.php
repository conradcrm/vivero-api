<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleCompra extends Model
{
    use HasFactory;
    protected $table = 'detalle_compra';
    protected $primaryKey = 'id_detallecompra';
    protected $fillable = [
        'id_detallecompra', 'id_planta', 'folio_compra', 'cantidad','delete'
    ];
    
    protected $hidden = ['delete'];
    #protected $hidden = ['created_at','updated_at'];
    public $timestamps = false;

    public function plantas(){
        // 1 planta tiene muchas compras
        // $this hace referencia al objeto que tengamos en ese momento planta.
        return $this->belongsTo('App\Models\PLanta', 'id_planta', 'id_planta');
    }

    public function compras(){
        //1 detalle de compra es para 1 producto
        // 1 compra tiene varios productos -> tiene varios detalles de compra 
        // $this hace referencia al objeto que tengamos en ese momento de Planta.
        return $this->belongsTo('App\Models\compra', 'folio_compra', 'folio_compra');
    }
}