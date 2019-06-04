<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\EventosFuncionesAreaLugar;
use App\EventosFuncionesArea;
use Response;
use Validator;
class EventosFuncionesAreaController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        return Response::json(EventosFuncionesArea::all(), 200);
    }
    
    public function getThisByFilter(Request $request, $id,$state)
    {
        if($request->get('filter')){
            switch ($request->get('filter')) {
                case 'state':{
                    $objectSee = EventosFuncionesArea::whereRaw('state=?',[$state])->with('tipos','eventos')->get();
                    break;
                }
                case 'tipo':{
                    $objectSee = EventosFuncionesArea::whereRaw('tipo=?',[$state])->with('tipos','eventos')->get();
                    break;
                }
                case 'evento_funcion':{
                    $objectSee = EventosFuncionesArea::whereRaw('evento_funcion=?',[$state])->with('tipos','eventos')->get();
                    break;
                }
                default:{
                    $objectSee = EventosFuncionesArea::whereRaw('state=?',[$state])->with('tipos','eventos')->get();
                    break;
                }
    
            }
        }else{
            $objectSee = EventosFuncionesArea::with('tipos','eventos')->get();
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
            'tipo'          => 'required',
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
                $newObject = new EventosFuncionesArea();
                $newObject->titulo            = $request->get('titulo');
                $newObject->descripcion            = $request->get('descripcion');
                $newObject->precio            = $request->get('precio');
                $newObject->total            = $request->get('total');
                $newObject->vendidos            = $request->get('vendidos');
                $newObject->type            = $request->get('type');
                $newObject->state            = $request->get('state');
                $newObject->tipo            = $request->get('tipo');
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
        $objectSee = EventosFuncionesArea::with('eventos','lugares')->find($id);
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
    public function Vender(Request $request, $id)
    {
        $objectUpdate = EventosFuncionesArea::find($id);
        if ($objectUpdate) {
            try {
                foreach ($request->get('lugares') as $value) {
                    $objectUpdate->vendidos =  $objectUpdate->vendidos+1;
                    $objectUpdateLugar = EventosFuncionesAreaLugar::find($value['id']);
                    if($objectUpdateLugar){
                        $objectUpdateLugar->vendido = 1;
                        $objectUpdateLugar->save();
                    }
                }
                $objectUpdate->save();
                $objectUpdate->eventos;
                $objectUpdate->lugares;
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
    public function update(Request $request, $id)
    {
        $objectUpdate = EventosFuncionesArea::find($id);
        if ($objectUpdate) {
            try {
                $objectUpdate->titulo = $request->get('titulo', $objectUpdate->titulo);
                $objectUpdate->descripcion = $request->get('descripcion', $objectUpdate->descripcion);
                $objectUpdate->precio = $request->get('precio', $objectUpdate->precio);
                $objectUpdate->total = $request->get('total', $objectUpdate->total);
                $objectUpdate->vendidos = $request->get('vendidos', $objectUpdate->vendidos);
                $objectUpdate->type = $request->get('type', $objectUpdate->type);
                $objectUpdate->state = $request->get('state', $objectUpdate->state);
                $objectUpdate->tipo = $request->get('tipo', $objectUpdate->tipo);
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
        $objectDelete = EventosFuncionesArea::find($id);
        if ($objectDelete) {
            try {
                EventosFuncionesArea::destroy($id);
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
