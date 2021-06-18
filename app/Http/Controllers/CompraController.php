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

class CompraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $compras = Compra::all();
        $compra = $compras->where('delete',1);
        for ($i = 0; $i < count($compra); $i++) {
            $id_proveedor = $compra[$i]->id_proveedor;
            $compra[$i]['proveedor'] = Proveedor::find($id_proveedor);
            $detalle = Compra::find($compra[$i]->folio_compra)->detalleCompra;
            for ($j=0; $j < count($detalle) ; $j++) { 
                $id_planta = $detalle[$j]->id_planta;
                $detalle[$j]['planta'] = Planta::find($id_planta);
            }
            $compra[$i]['detalle'] = $detalle;
        }
        return response()->json(['status'=>'success','message'=>'Registro de compras' ,'data'=> $compra],200);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $folio_compra)
    {
        $compra= Compra::find($folio_compra);
        if(!$compra)
        {
            return response()->json(['status'=>'error', 'code'=>404, 'message'=> 'No se encuentra el registro de la compra seleccionada.'],404);
        }
        if(!$request->status){
            return response()->json(['status'=>'error', 'code'=>404, 'message'=> 'Faltan datos necesarios para completar el proceso'],404);
        }
        
        $estado = $request->status;
        if($estado == "Completado")
        {
            $compra->estado = 1;
            $compra->save();
            $compra["estado"]=1;
            return Response::make(json_encode(['status'=>'success','code'=>200,'message'=>'La compra se ha completado con éxito.','data'=>$compra]), 200);
        }
        else if($estado == "Pendiente")
        {
            $compra->estado = 2;
            $compra->save();
            $compra["estado"]=2;
            return Response::make(json_encode(['status'=>'success','code'=>200,'message'=>'Ahora el registro de compra se encuentra como pendiente.','data'=>$compra]), 200);
        }
        else{
            $compra->estado = 3;
            $compra->save();
            $compra["estado"]=3;
        return Response::make(json_encode(['status'=>'success','code'=>200,'message'=>'La compra se ha cancelando con éxito.','data'=>$compra]), 200);
        }
    }


     /**
     * delete the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id_compra
     * @return \Illuminate\Http\Response
     */
    public function deleteCompra(Request $request, $id_compra)
    {
        $compra= Compra::find($id_compra);
        if(!$compra)
        {
            return response()->json(['status'=>'error', 'code'=>404, 'message'=> 'El registro no existe.'],404);
        }
        
        $compra->delete = 2;
        $compra->estado = 2;
        $compra->save();
            return Response::make(json_encode(['status'=>'success','code'=>200,'message'=>'El registro fue eliminado con éxito.','data'=>$compra]), 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $compra = Compra::find($id);
            $compra->delete();
            return Response::make(json_encode(['status'=>'success','code'=>200,'message'=>'El registro fue eliminado con éxito.','data'=>$compra]), 200);   
        } catch (Exception $th) {
            return Response::make(json_encode(['status'=>'error','code'=>422,'message'=>'Ocurrió un error al intentar eliminar el registro.', 'errord'=> $th]), 422);
        }   
    }
}
