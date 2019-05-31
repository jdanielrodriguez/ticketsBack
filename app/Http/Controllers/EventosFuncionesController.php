<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\EventosFunciones;
use Response;
use Validator;
class EventosFuncionesController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        return Response::json(EventosFunciones::all(), 200);
    }
    
    public function getThisByFilter(Request $request, $id,$state)
    {
        if($request->get('filter')){
            switch ($request->get('filter')) {
                case 'evento':{
                    $objectSee = EventosFunciones::whereRaw('evento=?',[$state])->with('eventos','vendedores')->get();
                    break;
                }
                case 'proximos':{
                    $objectSee = EventosFunciones::whereRaw('fecha_inicio>?',[$id])->with('eventos','vendedores')->get();
                    break;
                }
                case 'buscar':{
                    $objectSee = EventosFunciones::whereRaw('fecha_inicio=? and titulo=?',[$state,$id])->with('eventos','vendedores')->first();
                    break;
                }
                case 'proximos-principales':{
                    $objectSee = EventosFunciones::whereRaw('fecha_inicio>? and type=2',[$id])->with('eventos','vendedores')->get();
                    break;
                }
                case 'actuales':{
                    $objectSee = EventosFunciones::whereRaw('inicio<? and fin>?',[$id])->with('eventos','vendedores')->get();
                    break;
                }
                case 'pasados':{
                    $objectSee = EventosFunciones::whereRaw('fecha_fin<?',[$id,$state])->with('eventos','vendedores')->get();
                    break;
                }
                case 'proximos_eventos':{
                    $objectSee = EventosFunciones::whereRaw('inicio>? and evento=?',[$state,$id])->with('eventos','areas','vendedores')->get();
                    break;
                }
                case 'actuales_eventos':{
                    $objectSee = EventosFunciones::whereRaw('fin>? and evento=?',[$state,$id])->with('eventos','areas','vendedores')->get();
                    break;
                }
                case 'pasados_eventos':{
                    $objectSee = EventosFunciones::whereRaw('fin<? and evento=?',[$state,$id])->with('eventos','areas','vendedores')->get();
                    break;
                }
                case 'evento':{
                    $objectSee = EventosFunciones::whereRaw('evento=?',[$id])->with('eventos','vendedores')->get();
                    break;
                }
                default:{
                    $objectSee = EventosFunciones::whereRaw('evento=? and state=?',[$id,$state])->with('eventos','vendedores')->get();
                    break;
                }
    
            }
        }else{
            $objectSee = EventosFunciones::whereRaw('evento=?',[$id])->with('eventos','vendedores')->get();
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
            'evento'          => 'required',
            'inicio'          => 'required',
            'fin'          => 'required',
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
                $newObject = new EventosFunciones();
                $newObject->titulo            = $request->get('titulo');
                $newObject->imagen            = $request->get('imagen');
                $newObject->descripcion            = $request->get('descripcion');
                $newObject->direccion            = $request->get('direccion');
                $newObject->hora_inicio            = $request->get('hora_inicio');
                $newObject->hora_fin            = $request->get('hora_fin');
                $newObject->fecha_inicio            = $request->get('fecha_inicio');
                $newObject->fecha_fin            = $request->get('fecha_fin');
                $newObject->inicio            = $request->get('fecha_inicio')." ".$request->get('hora_inicio');
                $newObject->fin            = $request->get('fecha_fin')." ".$request->get('hora_fin');
                $newObject->latitud            = $request->get('latitud');
                $newObject->longitud            = $request->get('longitud');
                $newObject->type            = $request->get('type');
                $newObject->state            = $request->get('state');
                $newObject->evento            = $request->get('evento');
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
        $objectSee = EventosFunciones::find($id);
        if ($objectSee) {
            $objectSee->eventos;
            $objectSee->vendedores;
            $objectSee->areas;
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
        $objectUpdate = EventosFunciones::find($id);
        if ($objectUpdate) {
            try {
                $objectUpdate->titulo = $request->get('titulo', $objectUpdate->titulo);
                $objectUpdate->imagen = $request->get('imagen', $objectUpdate->imagen);
                $objectUpdate->descripcion = $request->get('descripcion', $objectUpdate->descripcion);
                $objectUpdate->direccion = $request->get('direccion', $objectUpdate->direccion);
                $objectUpdate->hora_inicio = $request->get('hora_inicio', $objectUpdate->hora_inicio);
                $objectUpdate->hora_fin = $request->get('hora_fin', $objectUpdate->hora_fin);
                $objectUpdate->fecha_inicio = $request->get('fecha_inicio', $objectUpdate->fecha_inicio);
                $objectUpdate->fecha_fin = $request->get('fecha_fin', $objectUpdate->fecha_fin);
                $objectUpdate->inicio = $request->get('inicio', $objectUpdate->inicio);
                $objectUpdate->fin = $request->get('fin', $objectUpdate->fin);
                $objectUpdate->latitud = $request->get('latitud', $objectUpdate->latitud);
                $objectUpdate->longitud = $request->get('longitud', $objectUpdate->longitud);
                $objectUpdate->type = $request->get('type', $objectUpdate->type);
                $objectUpdate->state = $request->get('state', $objectUpdate->state);
                $objectUpdate->evento = $request->get('evento', $objectUpdate->evento);
    
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
        $objectDelete = EventosFunciones::find($id);
        if ($objectDelete) {
            try {
                EventosFunciones::destroy($id);
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
