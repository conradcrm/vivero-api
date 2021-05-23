<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\Planta;
use Response;
use Exception;

class PlantaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['status'=>'ok', 'data'=> Planta::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!$request->input('nombre') || !$request->input('descripcion') || !$request->input('precio_venta') || !$request->input('precio_compra')|| !$request->input('imagen') || !$request->input('cantidad'))
        {
            return response()->json(['status'=>'error','code'=>402, 'message'=> 'Faltan datos necesarios para el proceso de registro.'],402);
        }

        try{
            $nuevaPlanta=Planta::create($request->all());
        }catch(Exception $e){
            return response()->json(['status'=>'error','code'=>409,'message'=>'Ya existe una planta con el mismo nombre.'],409);
        }

        $response = Response::make(json_encode(['status'=>'success','code'=>201,'message'=> 'La planta ha sido agregada con éxito.','data'=> $nuevaPlanta]), 201);
        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id_planta)
    {
        $planta = Planta::find($id_planta);
        if(!$planta){
            return response()->json(['errors'=>array(['code'=>'404', 'message'=> 'No se encuentra registrada la planta'])],404);
        }

        return response()->json(['status'=>'ok','data'=>$planta],200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id_planta)
    {
        $planta= planta::find($id_planta);
        if(!$planta)
        {
            return response()->json(['errors'=> array(['code'=>404, 'message'=> 'No se encuentra la planta'])]);
        }
        
        // Listado de campos recibidos teóricamente.
		$nombre=$request->input('nombre');
		$descripcion=$request->input('descripcion');
        $precio_venta=$request->input('precio_venta');
        $precio_compra=$request->input('precio_compra');
		$imagen=$request->input('imagen');
        $cantidad=$request->input('cantidad');
        $estado=$request->input('estado');
        $id_proveedor=$request->input('id_proveedor');
        $id_categoria=$request->input('id_categoria');
        // Necesitamos detectar si estamos recibiendo una petición PUT o PATCH.
		// El método de la petición se sabe a través de $request->method();
		if ($request->method() === 'PATCH')
		{
			// Creamos una bandera para controlar si se ha modificado algún dato en el método PATCH.
			$bandera = false;
			// Actualización parcial de campos.
			if ($nombre)
			{
				$planta->nombre = $nombre;
				$bandera=true;
			}

			if ($descripcion)
			{
				$planta->descripcion = $descripcion;
				$bandera=true;
			}

            if ($precio_venta)
            {
                $planta->precio_venta = $precio_venta;
                $bandera=true;
            }

            if($precio_compra)
            {
                $planta->precio_compra = $precio_compra;
                $bandera=true;
            }

			if ($imagen)
			{
				$planta->imagen = $imagen;
				$bandera=true;
			}

            if($cantidad)
            {
                $planta->cantidad = $cantidad;
                $bandera=true;
            }

            if($id_proveedor)
            {
                $planta->id_proveedor = $id_proveedor;
                $bandera=true;
            }

            if($id_categoria)
            {
                $planta->id_categoria = $id_categoria;
                $bandera=true;
            }

            if ($estado)
			{
				$planta->estado = $estado;
				$bandera=true;
			}

			if ($bandera)
			{
				// Almacenamos en la base de datos el registro.
				$planta->save();
				return response()->json(['status'=>'ok','data'=>$planta], 200);
			}
            else
			{
				// Se devuelve un array errors con los errores encontrados y cabecera HTTP 304 Not Modified – [No Modificada] Usado cuando el cacheo de encabezados HTTP está activo
				// Este código 304 no devuelve ningún body, así que si quisiéramos que se mostrara el mensaje usaríamos un código 200 en su lugar.
				return response()->json(['errors'=>array(['code'=>304,'message'=>'No se ha modificado ningún dato de la planta.'])],304);
			}
		}
        
        // Si el método es PUT y tendremos que actualizar todos los datos.
		if (!$nombre || !$descripcion || !$precio_compra || !$precio_venta || !$imagen || !$cantidad)
		{
			// Se devuelve un array errors con los errores encontrados y cabecera HTTP 422 Unprocessable Entity – [Entidad improcesable] Utilizada para errores de validación.
			return response()->json(['errors'=>array(['code'=>422,'message'=>'Faltan valores para completar el proceso.'])],422);
		}

        $planta->nombre = $nombre;
		$planta->descripcion = $descripcion;
        $planta->precio_venta = $precio_venta;
        $planta->precio_compra = $precio_compra;
		$planta->imagen = $imagen;
        $planta->cantidad = $cantidad;
        $planta->estado = $estado;
        $planta->id_proveedor = $id_proveedor;
        $planta->id_categoria = $id_categoria;
        
		// Almacenamos en la base de datos el registro.
		$planta->save();
		return response()->json(['status'=>'ok','data'=>$planta], 200);
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
