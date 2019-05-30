<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\EventosFuncionesAreaLugar;
use Response;
use Validator;
class EventosFuncionesAreaLugarController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        return Response::json(EventosFuncionesAreaLugar::all(), 200);
    }
    
    public function getThisByFilter(Request $request, $id,$state)
    {
        if($request->get('filter')){
            switch ($request->get('filter')) {
                case 'state':{
                    $objectSee = EventosFuncionesAreaLugar::whereRaw('evento_funcion_area=? and state=?',[$id,$state])->with('eventos')->get();
                    break;
                }
                case 'butaca':{
                    $objectSee = EventosFuncionesAreaLugar::whereRaw('evento_funcion_area=? and butaca=?',[$id,$state])->with('eventos')->get();
                    break;
                }
                case 'vendido':{
                    $objectSee = EventosFuncionesAreaLugar::whereRaw('evento_funcion_area=? and vendido=?',[$id,$state])->with('eventos')->get();
                    break;
                }
                default:{
                    $objectSee = EventosFuncionesAreaLugar::whereRaw('evento_funcion_area=? and state=?',[$id,$state])->with('eventos')->get();
                    break;
                }
    
            }
        }else{
            $objectSee = EventosFuncionesAreaLugar::whereRaw('evento_funcion_area=?',[$id])->with('eventos')->get();
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
            'evento_funcion_area'          => 'required',
            'butaca'          => 'required',
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
                $newObject = new EventosFuncionesAreaLugar();
                $newObject->titulo            = $request->get('titulo');
                $newObject->descripcion            = $request->get('descripcion');
                $newObject->lugar            = $request->get('lugar');
                $newObject->numero            = $request->get('numero');
                $newObject->butaca            = $request->get('butaca');
                $newObject->vendido            = $request->get('vendido');
                $newObject->type            = $request->get('type');
                $newObject->state            = $request->get('state');
                $newObject->evento_funcion_area            = $request->get('evento_funcion_area');
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
        $objectSee = EventosFuncionesAreaLugar::with('eventos')->find($id);
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
        $objectUpdate = EventosFuncionesAreaLugar::find($id);
        if ($objectUpdate) {
            try {
                $objectUpdate->titulo = $request->get('titulo', $objectUpdate->titulo);
                $objectUpdate->descripcion = $request->get('descripcion', $objectUpdate->descripcion);
                $objectUpdate->lugar = $request->get('lugar', $objectUpdate->lugar);
                $objectUpdate->numero = $request->get('numero', $objectUpdate->numero);
                $objectUpdate->butaca = $request->get('butaca', $objectUpdate->butaca);
                $objectUpdate->vendido = $request->get('vendido', $objectUpdate->vendido);
                $objectUpdate->type = $request->get('type', $objectUpdate->type);
                $objectUpdate->state = $request->get('state', $objectUpdate->state);
                $objectUpdate->evento_funcion_area = $request->get('evento_funcion_area', $objectUpdate->evento_funcion_area);
    
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
        $objectDelete = EventosFuncionesAreaLugar::find($id);
        if ($objectDelete) {
            try {
                EventosFuncionesAreaLugar::destroy($id);
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
