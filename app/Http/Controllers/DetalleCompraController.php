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

class DetalleCompraController extends Controller
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
        $listPlantas = $request->inputs;
        $id_proveedor = $request->id_proveedor;
        
        if(!$id_proveedor || !$listPlantas)
        {
            return response()->json(['status'=>'error','code'=>402, 'message'=> 'Faltan datos necesarios para el proceso de registro.'],402);
        }
        
        try{
            DB::beginTransaction();
            $nuevaCompra=Compra::create(array('id_proveedor' => $id_proveedor));
            for ($i=0; $i < count($listPlantas) ; $i++) { 
                $id_planta = $listPlantas[$i]['id_planta'];
                $cantidad = $listPlantas[$i]['cantidad'];
                $detalleCompra = DetalleCompra::create(array(
                    'id_planta' => $id_planta,
                    'cantidad' => $cantidad,
                    'folio_compra' => $nuevaCompra['folio_compra'])
                );
                
                $planta = Planta::find($id_planta);
                $cantidadOld = $planta->cantidad;
                $planta->cantidad = $cantidadOld + $cantidad;
                $planta->save();
            }
            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
            return response()->json(['status'=>'error','code'=>409,'message'=>'Ha ocurrido un error al intentar registrar la compra. Inténtelo más tarde.', $e],409);
        }
        
        $folio_compra = $nuevaCompra->folio_compra;
        $proveedor = Proveedor::find($id_proveedor);
        $nuevaCompra['proveedor'] = $proveedor;
        $detalle = Compra::find($folio_compra)->detalleCompra;
        for ($j=0; $j < count($detalle) ; $j++) { 
            $id_planta = $detalle[$j]->id_planta;
            $detalle[$j]['planta'] = Planta::find($id_planta);
        }

        $nuevaCompra['detalle'] = $detalle;
        $nuevaCompra['estado'] = 2;
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
