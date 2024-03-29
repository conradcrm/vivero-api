<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Planta;
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
        $plantas = Planta::where('delete',1)->get();
        return response()->json(['status'=>'ok', 'message'=> 'Registro de plantas', 'data'=> $plantas], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexPaginate(Request $request)
    {
        $page_size = $request->page_size;
        $plantas = Planta::where('delete',1)->paginate($page_size);
        return response()->json(['status'=>'ok', 'message'=> 'Registro de plantas', 'data'=> $plantas], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!$request->input('nombre') || !$request->input('descripcion') || !$request->input('precio_venta') || !$request->input('precio_compra'))
        {
            return response()->json(['status'=>'error','code'=>402, 'message'=> 'Faltan datos necesarios para el proceso de registro.'],402);
        }

        $plantDuplicate=Planta::where('nombre', $request->input('nombre'))->orderBy('id_planta', 'DESC')->first();
        if($plantDuplicate==null || $plantDuplicate!=null && $plantDuplicate->delete==2){            
            try{
                $nuevaPlanta=Planta::create($request->all());
            }catch(Exception $e){
                return response()->json(['status'=>'error','code'=>400,'message'=>'Ha ocurrido un error al intentar agregar la planta', 'e'=>$e],400);
            }
        }
        else{
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
            return response()->json(['status'=>'error','code'=>'404', 'message'=> 'No se encuentra registrada la planta'],404);
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
        $planta= Planta::find($id_planta);
        if(!$planta)
        {
            return response()->json(['status'=>'error','code'=>404, 'message'=> 'No se encuentra la planta.'],404);
        }
        
        // Listado de campos recibidos teóricamente.
		$nombre=$request->input('nombre');
		$descripcion=$request->input('descripcion');
        $precio_venta=$request->input('precio_venta');
        $precio_compra=$request->input('precio_compra');
		$imagen=$request->input('imagen');
        $cantidad=$request->input('cantidad');
        $estado=$request->input('estado');
        $id_planta=$request->input('id_planta');
        $id_categoria=$request->input('id_categoria');
        $id_proveedor=$request->input('id_proveedor');
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

            if($id_planta)
            {
                $planta->id_planta = $id_planta;
                $bandera=true;
            }

            if($id_categoria)
            {
                $planta->id_categoria = $id_categoria;
                $bandera=true;
            }
            
            if($id_proveedor)
            {
                $planta->id_proveedor = $id_proveedor;
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
				return response()->json(['status'=>'success','code'=>'200','message'=>'La planta ha sido modificada con éxito.','data'=>$planta], 200);
			}
            else
			{
				// Se devuelve un array errors con los errores encontrados y cabecera HTTP 304 Not Modified – [No Modificada] Usado cuando el cacheo de encabezados HTTP está activo
				// Este código 304 no devuelve ningún body, así que si quisiéramos que se mostrara el mensaje usaríamos un código 200 en su lugar.
				return response()->json(['status'=>'error','code'=>304,'message'=>'No se ha modificado ningún dato de la planta.'],304);
			}
		}
        
        // Si el método es PUT y tendremos que actualizar todos los datos.
		if (!$nombre || !$descripcion || !$precio_compra || !$precio_venta || !$imagen || !$cantidad)
		{
			// Se devuelve un array errors con los errores encontrados y cabecera HTTP 422 Unprocessable Entity – [Entidad improcesable] Utilizada para errores de validación.
			return response()->json(['status'=>'error','code'=>422,'message'=>'Faltan valores para completar el proceso.'],422);
		}

        $planta->nombre = $nombre;
		$planta->descripcion = $descripcion;
        $planta->precio_venta = $precio_venta;
        $planta->precio_compra = $precio_compra;
		$planta->imagen = $imagen;
        $planta->cantidad = $cantidad;
        $planta->estado = $estado;
        $planta->id_planta = $id_planta;
        $planta->id_categoria = $id_categoria;
        
		// Almacenamos en la base de datos el registro.
		$planta->save();
		return response()->json(['status'=>'success','code'=>'200','message'=>'La planta ha sido cambiada con éxito.','data'=>$planta], 200);
    }

    public function delete(Request $request, $id_planta)
    {
        $planta= Planta::find($id_planta);
        if(!$planta)
        {
            return response()->json(['status'=>'error', 'code'=>404, 'message'=> 'La planta no existe.'],404);
        }

        if($planta->estado == 2)
        {
            $planta->estado = 1;
            $planta->save();
            return Response::make(json_encode(['status'=>'success','code'=>200,'message'=>'La planta fue dada de alta con éxito.','data'=>$planta]), 200);
        }
        else{
            $planta->estado = 2;
            $planta->save();
            return Response::make(json_encode(['status'=>'success','code'=>200,'message'=>'La planta fue dada de baja con éxito.','data'=>$planta]), 200);
        }
    }

    public function deletePlant(Request $request, $id_planta)
    {
        $planta= Planta::find($id_planta);
        if(!$planta)
        {
            return response()->json(['status'=>'error', 'code'=>404, 'message'=> 'La planta no existe.'],404);
        }

        $planta->estado = 2;
        $planta->delete = 2;
        $planta->save();
        return Response::make(json_encode(['status'=>'success','code'=>200,'message'=>'La planta fue dada eliminada con éxito.','data'=>$planta]), 200);
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
            $planta = Planta::find($id);
            $planta->delete();
            return Response::make(json_encode(['status'=>'success','code'=>200,'message'=>'La planta fue eliminada con éxito.','data'=>$planta]), 200);   
        } catch (Exception $th) {
            return Response::make(json_encode(['status'=>'error','code'=>422,'message'=>'Ocurrió un error al intentar eliminar la planta.', 'errord'=> $th]), 422);
        }   
    }
}
