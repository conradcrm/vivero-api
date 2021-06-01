<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\Planta;
use App\models\Compra;
use App\models\Proveedor;
use App\models\Categoria;
use App\models\DetalleCompra;
use App\Quotation;
use Response;
use Exception;
use DB;

class DetalleCompraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $compra = Compra::all();
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
        return response()->json(['status'=>'success', 'code'=>200,'message'=>'Registro de compras' ,'data'=> $compra],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!$request->input('id_proveedor') || !$request->input('id_planta') || !$request->input('cantidad'))
        {
            return response()->json(['status'=>'error','code'=>402, 'message'=> 'Faltan datos necesarios para el proceso de registro.'],402);
        }
        
        try{
            DB::beginTransaction();
            $nuevaCompra=Compra::create(array('id_proveedor' => $request->input('id_proveedor')));
            $detalleCompra = DetalleCompra::create(array(
                'id_planta' => $request->input('id_planta'),
                'cantidad' => $request->input('cantidad'),
                'folio_compra' => 2));
            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
            return response()->json(['status'=>'error','code'=>409,'message'=>'Ha ocurrido un error al intentar registrar la compra. Inténtelo más tarde.', $e],409);
        }

        $response = Response::make(json_encode(['status'=>'success','code'=>201,'message'=> 'El registro de compra se completó con éxito','data'=> $nuevaCompra]), 201);
        return $response;      
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
    public function update(Request $request, $id)
    {
        //
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
