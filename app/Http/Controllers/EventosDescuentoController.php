<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\EventosDescuento;
use Response;
use Validator;
class EventosDescuentoController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        return Response::json(EventosDescuento::all(), 200);
    }
    
    public function getThisByFilter(Request $request, $id,$state)
    {
        if($request->get('filter')){
            switch ($request->get('filter')) {
                case 'state':{
                    $objectSee = EventosDescuento::whereRaw('state=?',[$state])->with('usuarios','eventos')->get();
                    break;
                }
                case 'usuario':{
                    $objectSee = EventosDescuento::whereRaw('usuario=?',[$state])->with('usuarios','eventos')->get();
                    break;
                }
                case 'evento_funcion':{
                    $objectSee = EventosDescuento::whereRaw('evento_funcion=?',[$state])->with('usuarios','eventos')->get();
                    break;
                }
                default:{
                    $objectSee = EventosDescuento::whereRaw('state=?',[$state])->with('usuarios','eventos')->get();
                    break;
                }
    
            }
        }else{
            $objectSee = EventosDescuento::with('usuarios','eventos')->get();
        }
    
        if ($objectSee) {
            return Response::json($objectSee, 200);
    
        }
        else {
            $returnData = array (
                'status' => 404,
                'message' => 'No record found'
            );
            return Response::json($returnData, 404);
        }
    }
    
    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        //
    }
    
    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'usuario'          => 'required',
            'evento_funcion'          => 'required',
        ]);
        if ( $validator->fails() ) {
            $returnData = array (
                'status' => 400,
                'message' => 'Invalid Parameters',
                'validator' => $validator
            );
            return Response::json($returnData, 400);
        }
        else {
            try {
                $newObject = new EventosDescuento();
                $newObject->titulo            = $request->get('titulo');
                $newObject->porcentaje            = $request->get('porcentaje');
                $newObject->cantidad            = $request->get('cantidad');
                $newObject->descripcion            = $request->get('descripcion');
                $newObject->type            = $request->get('type');
                $newObject->state            = $request->get('state');
                $newObject->usuario            = $request->get('usuario');
                $newObject->evento_funcion            = $request->get('evento_funcion');
                $newObject->save();
                return Response::json($newObject, 200);
    
            } catch (Exception $e) {
                $returnData = array (
                    'status' => 500,
                    'message' => $e->getMessage()
                );
                return Response::json($returnData, 500);
            }
        }
    }
    
    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
        $objectSee = EventosDescuento::find($id);
        if ($objectSee) {
            return Response::json($objectSee, 200);
    
        }
        else {
            $returnData = array (
                'status' => 404,
                'message' => 'No record found'
            );
            return Response::json($returnData, 404);
        }
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
        $objectUpdate = EventosDescuento::find($id);
        if ($objectUpdate) {
            try {
                $objectUpdate->titulo = $request->get('titulo', $objectUpdate->titulo);
                $objectUpdate->porcentaje = $request->get('porcentaje', $objectUpdate->porcentaje);
                $objectUpdate->cantidad = $request->get('cantidad', $objectUpdate->cantidad);
                $objectUpdate->descripcion = $request->get('descripcion', $objectUpdate->descripcion);
                $objectUpdate->type = $request->get('type', $objectUpdate->type);
                $objectUpdate->state = $request->get('state', $objectUpdate->state);
                $objectUpdate->usuario = $request->get('usuario', $objectUpdate->usuario);
                $objectUpdate->evento_funcion = $request->get('evento_funcion', $objectUpdate->evento_funcion);
    
                $objectUpdate->save();
                return Response::json($objectUpdate, 200);
            } catch (Exception $e) {
                $returnData = array (
                    'status' => 500,
                    'message' => $e->getMessage()
                );
                return Response::json($returnData, 500);
            }
        }
        else {
            $returnData = array (
                'status' => 404,
                'message' => 'No record found'
            );
            return Response::json($returnData, 404);
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
        $objectDelete = EventosDescuento::find($id);
        if ($objectDelete) {
            try {
                EventosDescuento::destroy($id);
                return Response::json($objectDelete, 200);
            } catch (Exception $e) {
                $returnData = array (
                    'status' => 500,
                    'message' => $e->getMessage()
                );
                return Response::json($returnData, 500);
            }
        }
        else {
            $returnData = array (
                'status' => 404,
                'message' => 'No record found'
            );
            return Response::json($returnData, 404);
        }
    }
}
