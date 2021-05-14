<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proveedor;
use Response;

class ProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['status'=>'ok', 'data'=>Proveedor::all()]);
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!$request->input('razon_social') || !$request->input('direccion') || !$request->input('imagen') || !$request->input('telefono')){
            return response()->json(['errors'=> array(['code'=>402, 'message'=> 'Faltan datos necesarios para el proceso de alta']),402]);
        }

        $nuevoProveedor=Proveedor::create($request->all());

        $response = Response::make(json_encode(['data'=> $nuevoProveedor]), 201);

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id_proveedor)
    {   
        if(!$id_proveedor){
            return response()->json(['errors'=> array(['code'=>422, 'message'=>'Faltan valores para completar el proceso'])],422);
        }
        $proveedor=Proveedor::find($id_proveedor);
        /*if(!$proveedor){
            return response()->json(['errors'=> array(['code'=>404, 'message'=>'No se encuentra registrado el proveedor'])],404);
        }
        return response()->json(['status'=>'ok', 'data'=>$proveedor], 200);*/
        return response()->json(['status'=>'ok', 'data'=>$proveedor], 200);
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
    public function update(Request $request, $id_proveedor)
    {
        $proveedor= proveedor::find($id_proveedor);
        if(!$proveedor)
        {
            return response()->json(['errors'=> array(['code'=>404, 'message'=> 'No se encuentra el proveedor'])]);
        }
        
        // Listado de campos recibidos teóricamente.
		$razon_social=$request->input('razon_social');
		$direccion=$request->input('direccion');
		$imagen=$request->input('imagen');
        $telefono=$request->input('telefono');
        $estado=$request->input('estado');
        // Necesitamos detectar si estamos recibiendo una petición PUT o PATCH.
		// El método de la petición se sabe a través de $request->method();
		if ($request->method() === 'PATCH')
		{
			// Creamos una bandera para controlar si se ha modificado algún dato en el método PATCH.
			$bandera = false;
			// Actualización parcial de campos.
			if ($razon_social)
			{
				$proveedor->razon_social = $razon_social;
				$bandera=true;
			}

			if ($direccion)
			{
				$proveedor->direccion = $direccion;
				$bandera=true;
			}

			if ($imagen)
			{
				$proveedor->imagen = $imagen;
				$bandera=true;
			}
            if ($telefono){
                $proveedor->telefono = $telefono;
                $bandera=true;
            }

            if ($estado)
			{
				$proveedor->estado = $estado;
				$bandera=true;
			}

			if ($bandera)
			{
				// Almacenamos en la base de datos el registro.
				$proveedor->save();
				return response()->json(['status'=>'ok','data'=>$proveedor], 200);
			}
            else
			{
				// Se devuelve un array errors con los errores encontrados y cabecera HTTP 304 Not Modified – [No Modificada] Usado cuando el cacheo de encabezados HTTP está activo
				// Este código 304 no devuelve ningún body, así que si quisiéramos que se mostrara el mensaje usaríamos un código 200 en su lugar.
				return response()->json(['errors'=>array(['code'=>304,'message'=>'No se ha modificado ningún dato de la proveedor.'])],304);
			}
		}
        
        // Si el método es PUT y tendremos que actualizar todos los datos.
		if (!$razon_social || !$direccion || !$imagen || !$telefono)
		{
			// Se devuelve un array errors con los errores encontrados y cabecera HTTP 422 Unprocessable Entity – [Entidad improcesable] Utilizada para errores de validación.
			return response()->json(['errors'=>array(['code'=>422,'message'=>'Faltan valores para completar el proceso.'])],422);
		}

        $proveedor->razon_social = $razon_social;
		$proveedor->direccion = $direccion;
		$proveedor->imagen = $imagen;
        $proveedor->estado = $estado;
        $proveedor->telefono = $telefono;
		// Almacenamos en la base de datos el registro.
		$proveedor->save();
		return response()->json(['status'=>'ok','data'=>$proveedor], 200);
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
