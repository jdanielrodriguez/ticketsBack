<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\EventosDescuentoArea;
use Response;
use Validator;
class EventosDescuentoAreaController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        return Response::json(EventosDescuentoArea::all(), 200);
    }
    
    public function getThisByFilter(Request $request, $id,$state)
    {
        if($request->get('filter')){
            switch ($request->get('filter')) {
                case 'state':{
                    $objectSee = EventosDescuentoArea::whereRaw('state=?',[$state])->with('descuentos','eventos')->get();
                    break;
                }
                case 'evento_descuento':{
                    $objectSee = EventosDescuentoArea::whereRaw('evento_descuento=?',[$state])->with('descuentos','eventos')->get();
                    break;
                }
                case 'evento_funcion_area':{
                    $objectSee = EventosDescuentoArea::whereRaw('evento_funcion_area=?',[$state])->with('descuentos','eventos')->get();
                    break;
                }
                default:{
                    $objectSee = EventosDescuentoArea::whereRaw('state=?',[$state])->with('descuentos','eventos')->get();
                    break;
                }
    
            }
        }else{
            $objectSee = EventosDescuentoArea::with('descuentos','eventos')->get();
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
            'evento_descuento'          => 'required',
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
                $newObject = new EventosDescuentoArea();
                $newObject->titulo            = $request->get('titulo');
                $newObject->descripcion            = $request->get('descripcion');
                $newObject->cantidad            = $request->get('cantidad');
                $newObject->type            = $request->get('type');
                $newObject->state            = $request->get('state');
                $newObject->evento_descuento            = $request->get('evento_descuento');
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
        $objectSee = EventosDescuentoArea::find($id);
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
        $objectUpdate = EventosDescuentoArea::find($id);
        if ($objectUpdate) {
            try {
                $objectUpdate->titulo = $request->get('titulo', $objectUpdate->titulo);
                $objectUpdate->descripcion = $request->get('descripcion', $objectUpdate->descripcion);
                $objectUpdate->cantidad = $request->get('cantidad', $objectUpdate->cantidad);
                $objectUpdate->state = $request->get('state', $objectUpdate->state);
                $objectUpdate->type = $request->get('type', $objectUpdate->type);
                $objectUpdate->evento_descuento = $request->get('evento_descuento', $objectUpdate->evento_descuento);
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
        $objectDelete = EventosDescuentoArea::find($id);
        if ($objectDelete) {
            try {
                EventosDescuentoArea::destroy($id);
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
