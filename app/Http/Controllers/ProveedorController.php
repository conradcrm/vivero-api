<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proveedor;
use Response;
use Exception;

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
        if(!$request->input('nombre') || !$request->input('direccion') || !$request->input('correo') || !$request->input('imagen') || !$request->input('telefono')){
            return response()->json(['status'=>'error','code'=>402, 'message'=> 'Faltan datos necesarios para el proceso de registro.'],402);
        }

        try{
            $nuevoProveedor=Proveedor::create($request->all());
        }catch(Exception $e){
            return response()->json(['status'=>'error','code'=>409,'message'=>'Ya existe un proveedor con el mismo nombre.'],409);
        }

        $response = Response::make(json_encode(['status'=>'success','code'=>201,'message'=> 'El proveedor ha sido agregado con éxito.','data'=> $nuevoProveedor]), 201);
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
            return response()->json(['status'=>'error','code'=>422, 'message'=>'Faltan valores para completar el proceso.'],422);
        }
        $proveedor=Proveedor::find($id_proveedor);
        /*if(!$proveedor){
            return response()->json(['errors'=> array(['code'=>404, 'message'=>'No se encuentra registrado el proveedor'])],404);
        }
        return response()->json(['status'=>'ok', 'data'=>$proveedor], 200);*/
        return response()->json(['status'=>'success', 'message'=>'Proveedor encontrado.', 'data'=>$proveedor], 200);
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
        $proveedor= Proveedor::find($id_proveedor);
        if(!$proveedor)
        {
            return response()->json(['status'=>'error','code'=>404, 'message'=> 'No se encuentra el proveedor.'],404);
        }
        
        // Listado de campos recibidos teóricamente.
		$nombre=$request->input('nombre');
		$direccion=$request->input('direccion');
		$imagen=$request->input('imagen');
        $correo=$request->input('correo');
        $telefono=$request->input('telefono');
        $estado=$request->input('estado');
        // Necesitamos detectar si estamos recibiendo una petición PUT o PATCH.
		// El método de la petición se sabe a través de $request->method();
		if ($request->method() === 'PATCH')
		{
			// Creamos una bandera para controlar si se ha modificado algún dato en el método PATCH.
			$bandera = false;
			// Actualización parcial de campos.
			if ($nombre)
			{
				$proveedor->nombre = $nombre;
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

            if ($correo)
			{
				$proveedor->correo = $correo;
				$bandera=true;
			}

			if ($bandera)
			{
				// Almacenamos en la base de datos el registro.
				$proveedor->save();
				return response()->json(['status'=>'success','code'=>200,'message'=>'El proveedor ha sido modificado con éxito.','data'=>$proveedor], 200);
			}
            else
			{
				// Se devuelve un array errors con los errores encontrados y cabecera HTTP 304 Not Modified – [No Modificada] Usado cuando el cacheo de encabezados HTTP está activo
				// Este código 304 no devuelve ningún body, así que si quisiéramos que se mostrara el mensaje usaríamos un código 200 en su lugar.
				return response()->json(['status'=>'error','code'=>304,'message'=>'No se ha modificado ningún dato de la proveedor.'],304);
			}
		}
        
        // Si el método es PUT y tendremos que actualizar todos los datos.
		if (!$nombre || !$direccion || !$correo || !$imagen || !$telefono)
		{
			// Se devuelve un array errors con los errores encontrados y cabecera HTTP 422 Unprocessable Entity – [Entidad improcesable] Utilizada para errores de validación.
			return response()->json(['status'=>'error','code'=>422,'message'=>'Faltan valores para completar el proceso.'],422);
		}

        $proveedor->nombre = $nombre;
		$proveedor->direccion = $direccion;
		$proveedor->imagen = $imagen;
        $proveedor->estado = $estado;
        $proveedor->correo = $correo;
        $proveedor->telefono = $telefono;
		// Almacenamos en la base de datos el registro.
		$proveedor->save();
		return response()->json(['status'=>'success','code'=>200,'message'=>'El proveedor ha sido moficiado con éxito.','data'=>$proveedor], 200);
    }

    
    /**
     * delete the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id_proveedor
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $id_proveedor)
    {
        $proveedor= Proveedor::find($id_proveedor);
        if(!$proveedor)
        {
            return response()->json(['status'=>'error', 'code'=>404, 'message'=> 'El proveedor no existe.'],404);
        }

        if($proveedor->estado == 2)
        {
            $proveedor->estado = 1;
            $proveedor->save();
            return response()->json(['status'=>'success', 'code'=>200, 'message'=> 'El proveedor fue dado de alta con éxito.'],200);
        }
        else{
            $proveedor->estado = 2;
            $proveedor->save();
            return Response::make(json_encode(['status'=>'success','code'=>200,'message'=>'El proveedor fue dado de baja con éxito.','data'=>$proveedor]), 200);
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
