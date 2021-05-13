<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use Response;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['status'=>'ok','data'=>Categoria::all()], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!$request->input('nombre') || !$request->input('descripcion') || !$request->input('imagen'))
        {
            return response()->json(['errors'=>array(['code'=>422,'message'=>'Faltan datos necesarios para el proceso de alta.'])],422);
        }

        $nuevaCategoria=Categoria::create($request->all());
        
        $response = Response::make(json_encode(['data'=>$nuevaCategoria]), 201);
		
        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id_categoria)
    {
        $categoria=Categoria::find($id_categoria);
		// Si no existe ese categoria devolvemos un error.
		if (!$categoria)
		{
			return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra una categoria con ese id.'])],404);
		}

		return response()->json(['status'=>'ok','data'=>$categoria],200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id_categoria)
    {
        $categoria= Categoria::find($id_categoria);
        if(!$categoria)
        {
            return response()->json(['errors'=> array(['code'=>404, 'message'=> 'No se encuentra la categoria'])]);
        }
        
        // Listado de campos recibidos teóricamente.
		$nombre=$request->input('nombre');
		$descripcion=$request->input('descripcion');
		$imagen=$request->input('imagen');
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
				$categoria->nombre = $nombre;
				$bandera=true;
			}

			if ($descripcion)
			{
				$categoria->descripcion = $descripcion;
				$bandera=true;
			}

			if ($imagen)
			{
				$categoria->imagen = $imagen;
				$bandera=true;
			}
            if ($estado)
			{
				$categoria->estado = $estado;
				$bandera=true;
			}

			if ($bandera)
			{
				// Almacenamos en la base de datos el registro.
				$categoria->save();
				return response()->json(['status'=>'ok','data'=>$categoria], 200);
			}
            else
			{
				// Se devuelve un array errors con los errores encontrados y cabecera HTTP 304 Not Modified – [No Modificada] Usado cuando el cacheo de encabezados HTTP está activo
				// Este código 304 no devuelve ningún body, así que si quisiéramos que se mostrara el mensaje usaríamos un código 200 en su lugar.
				return response()->json(['errors'=>array(['code'=>304,'message'=>'No se ha modificado ningún dato de la categoría.'])],304);
			}
		}
        
        // Si el método es PUT y tendremos que actualizar todos los datos.
		if (!$nombre || !$descripcion || !$imagen || !$estado)
		{
			// Se devuelve un array errors con los errores encontrados y cabecera HTTP 422 Unprocessable Entity – [Entidad improcesable] Utilizada para errores de validación.
			return response()->json(['errors'=>array(['code'=>422,'message'=>'Faltan valores para completar el procesamiento.'])],422);
		}

        $categoria->nombre = $nombre;
		$categoria->descripcion = $descripcion;
		$categoria->imagen = $imagen;
        $categoria->estado = $estado;

		// Almacenamos en la base de datos el registro.
		$categoria->save();
		return response()->json(['status'=>'ok','data'=>$categoria], 200);
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
