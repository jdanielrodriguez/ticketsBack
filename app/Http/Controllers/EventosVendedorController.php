<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\EventosVendedor;
use Response;
use Validator;
class EventosVendedorController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        return Response::json(EventosVendedor::all(), 200);
    }
    
    public function getThisByFilter(Request $request, $id,$state)
    {
        if($request->get('filter')){
            switch ($request->get('filter')) {
                case 'state':{
                    $objectSee = EventosVendedor::whereRaw('usuario_admin=? and state=?',[$id,$state])->with('usuarios','administrador','eventos')->get();
                    break;
                }
                case 'evento_funcion':{
                    $objectSee = EventosVendedor::whereRaw('evento_funcion=?',[$state])->with('usuarios','administrador','eventos')->get();
                    break;
                }
                case 'usuario_admin':{
                    $objectSee = EventosVendedor::whereRaw('usuario_admin=?',[$id])->with('usuarios','administrador','eventos')->get();
                    break;
                }
                default:{
                    $objectSee = EventosVendedor::whereRaw('usuario=?',[$state])->with('usuarios','administrador','eventos')->get();
                    break;
                }
    
            }
        }else{
            $objectSee = EventosVendedor::whereRaw('usuario_admin=?',[$id])->with('usuarios','administrador','eventos')->get();
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
            'evento_funcion'          => 'required',
            'usuario_admin'          => 'required',
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
                $newObject = new EventosVendedor();
                $newObject->titulo            = $request->get('titulo');
                $newObject->porcentaje            = $request->get('porcentaje');
                $newObject->cantidad            = $request->get('cantidad');
                $newObject->descripcion            = $request->get('descripcion');
                $newObject->type            = $request->get('type');
                $newObject->state            = $request->get('state');
                $newObject->usuario            = $request->get('usuario');
                $newObject->usuario_admin            = $request->get('usuario_admin');
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
        $objectSee = EventosVendedor::find($id);
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
        $objectUpdate = EventosVendedor::find($id);
        if ($objectUpdate) {
            try {
                $objectUpdate->titulo = $request->get('titulo', $objectUpdate->titulo);
                $objectUpdate->porcentaje = $request->get('porcentaje', $objectUpdate->porcentaje);
                $objectUpdate->cantidad = $request->get('cantidad', $objectUpdate->cantidad);
                $objectUpdate->descripcion = $request->get('descripcion', $objectUpdate->descripcion);
                $objectUpdate->type = $request->get('type', $objectUpdate->type);
                $objectUpdate->state = $request->get('state', $objectUpdate->state);
                $objectUpdate->usuario = $request->get('usuario', $objectUpdate->usuario);
                $objectUpdate->usuario_admin = $request->get('usuario_admin', $objectUpdate->usuario_admin);
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
        $objectDelete = EventosVendedor::find($id);
        if ($objectDelete) {
            try {
                EventosVendedor::destroy($id);
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
