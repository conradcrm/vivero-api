<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\Compra;
use Response;
use Exception;

class CompraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['status'=>'ok', 'data'=> Compra::all()]);
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
            return Response::make(json_encode(['status'=>'success','code'=>200,'message'=>'La compra se ha completado con éxito.','data'=>$compra]), 200);
        }
        else if($estado == "Pendiente")
        {
            $compra->estado = 2;
            $compra->save();
            return Response::make(json_encode(['status'=>'success','code'=>200,'message'=>'Ahora el registro de compra se encuentra como pendiente.','data'=>$compra]), 200);
        }
        else{
            $compra->estado = 3;
            $compra->save();
        return Response::make(json_encode(['status'=>'success','code'=>200,'message'=>'La compra se ha cancelando con éxito.','data'=>$compra]), 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
