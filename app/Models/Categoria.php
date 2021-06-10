<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;
    protected $table='categorias';
    protected $primaryKey = 'id_categoria';
    protected $fillable = [
        'id_categoria', 'nombre', 'descripcion', 'imagen', 'estado','delete'
    ];
    
    #protected $hidden = ['created_at','updated_at'];
    public $timestamps = false;

    public function plantas(){    
        // 1 categoria tiene muchas plantas
        // $this hace referencia al objeto que tengamos en ese momento de Categoría.
        return $this->hasMany('App\Models\Planta', 'id_categoria', 'id_categoria');
    }
}
