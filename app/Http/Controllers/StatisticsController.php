<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Planta;
use App\Models\Compra;
use App\Models\Proveedor;
use App\Models\Categoria;
use App\Models\DetalleCompra;
use App\Quotation;
use Response;
use Exception;
use DB;

class StatisticsController extends Controller
{
/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function statistics(){

        $categories = Categoria::where('delete',1)->get();
        $plantas = Planta::where('delete',1)->get();
        $providers = Proveedor::where('delete',1)->get();
        $compras = Compra::where('delete',1)->get();
        
        #Total de registros de cada módulo
        $tot_categories = $categories->count();
        $tot_providers= $providers->count();
        $tot_plants=$plantas->count();
        $tot_shopping=$compras->count();
        
        $meses = DB::select('SELECT MONTH( created_at ) AS mes, COUNT( * ) AS total FROM compras as c WHERE c.delete =1 GROUP BY mes');
        
        $providers = Proveedor::where('delete',1)->get();
        $catgories = Categoria::where('delete',1)->get();
        $dataProveedor=[];
        for ($i = 0; $i < count($providers); $i++) {
            $id = $providers[$i]->id_proveedor;
            $dataProveedor[$i]['total'] = count(Proveedor::find($id)->plantas);
            $dataProveedor[$i]['module'] = Proveedor::find($id)->nombre;
        }
        
        $dataCategory=[];
        for ($i = 0; $i < count($categories); $i++) {
            $id = $categories[$i]->id_categoria;
            $dataCategory[$i]['total'] = count(Categoria::find($id)->plantas);
            $dataCategory[$i]['module'] = Categoria::find($id)->nombre;
        }

        return response()->json(['status'=>'success',
                                 'message'=>'Registros' ,
                                 'data'=> [
                                     'tot_categories'=>$tot_categories, 
                                     'tot_providers'=>$tot_providers, 
                                     'tot_shopping'=> $tot_shopping, 
                                     'tot_plants'=> $tot_plants,
                                     'compras_meses' => $meses,
                                     'proveedores'=> $dataProveedor,    
                                     'categorias'=> $dataCategory
                                    ]
                                ],200);
    }
}