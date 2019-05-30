<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\EventosVenta;
use Response;
use Validator;
class EventosVentaController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        return Response::json(EventosVenta::all(), 200);
    }
    
    public function getThisByFilter(Request $request, $id,$state)
    {
        if($request->get('filter')){
            switch ($request->get('filter')) {
                case 'state':{
                    $objectSee = EventosVenta::whereRaw('user=? and state=?',[$id,$state])->with('user')->get();
                    break;
                }
                case 'type':{
                    $objectSee = EventosVenta::whereRaw('user=? and tipo=?',[$id,$state])->with('user')->get();
                    break;
                }
                default:{
                    $objectSee = EventosVenta::whereRaw('user=? and state=?',[$id,$state])->with('user')->get();
                    break;
                }
    
            }
        }else{
            $objectSee = EventosVenta::whereRaw('user=? and state=?',[$id,$state])->with('user')->get();
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
            ''          => 'required',
            ''          => 'required',
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
                $newObject = new EventosVenta();
                $newObject->column            = $request->get('column');
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
    public function pagar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cantidad'          => 'required',
            'precio'            => 'required'
        ]);
        if ( $validator->fails() ) {
            $returnData = array (
                'status' => 400,
                'message' => 'No ha llenado los campos adecuadamente.',
                'validator' => $validator
            );
            return Response::json($returnData, 400);
        }
        else {
            /*
             * Lo primero es crear el objeto nusoap_client, al que se le pasa como
             * parámetro la URL de Conexión definida en la constante WSPG
             */

            // define("UID", "00a44a36adaab6310e6bf306b9d5969b");
            // define("WSK", "c6ef3680408284ca1addc87b64c48421");
            // define("SANDBOX", true);
            
                define("UID", "00a44a36adaab6310e6bf306b9d5969b");
                define("WSK", "c6ef3680408284ca1addc87b64c48421");
                define("SANDBOX", true);
            
            $Pagadito = new Pagadito(UID, WSK);
            /*
             * Si se está realizando pruebas, necesita conectarse con Pagadito SandBox. Para ello llamamos
             * a la función mode_sandbox_on(). De lo contrario omitir la siguiente linea.
             */
            if (SANDBOX) {
                $Pagadito->mode_sandbox_on();
            }
            /*
             * Validamos la conexión llamando a la función connect(). Retorna
             * true si la conexión es exitosa. De lo contrario retorna false
             */
            if ($Pagadito->connect()) {
                /*
                 * Luego pasamos a agregar los detalles
                 */
                if ($request->get("cantidad") > 0) {
                    $Pagadito->add_detail($request->get("cantidad"), $request->get("descripcion"), $request->get("precio"), $request->get("url"));
                }
                
                
                //Agregando campos personalizados de la transacción
                $Pagadito->set_custom_param("param1", "Valor de param1");
                $Pagadito->set_custom_param("param2", "Valor de param2");
                $Pagadito->set_custom_param("param3", "Valor de param3");
                $Pagadito->set_custom_param("param4", "Valor de param4");
                $Pagadito->set_custom_param("param5", "Valor de param5");
        
                //Habilita la recepción de pagos preautorizados para la orden de cobro.
                $Pagadito->enable_pending_payments();
        
                /*
                 * Lo siguiente es ejecutar la transacción, enviandole el ern.
                 *
                 * A manera de ejemplo el ern es generado como un número
                 * aleatorio entre 1000 y 2000. Lo ideal es que sea una
                 * referencia almacenada por el Pagadito Comercio.
                 */
                if($request->get("ern"))
                {
                    $ern = $request->get("ern");
                }
                else{
                    $ern = rand(1000, 2000);
                }
                $eeror = $Pagadito->exec_trans($ern);
                if (!$eeror) {
                    /*
                     * En caso de fallar la transacción, verificamos el error devuelto.
                     * Debido a que la API nos puede devolver diversos mensajes de
                     * respuesta, validamos el tipo de mensaje que nos devuelve.
                     */
                    switch($Pagadito->get_rs_code())
                    {
                        case "PG2001":
                            {
                                $returnData = array (
                                    'status' => 400,
                                    'message' => 'Data incompleta'
                                );
                                return Response::json($returnData, 400);
                                break;
                            /*Incomplete data*/
                        }
                        case "PG3002":
                            {
                                $returnData = array (
                                    'status' => 400,
                                    'message' => 'Error General'
                                );
                                return Response::json($returnData, 400);
                                break;
                        }
                            /*Error*/
                        case "PG3003":
                            {
                                $returnData = array (
                                    'status' => 400,
                                    'message' => 'transaccion sin registrar'
                                );
                                return Response::json($returnData, 400);
                                break;
                        }
                            /*Unregistered transaction*/
                        case "PG3004":
                            {
                                $returnData = array (
                                    'status' => 400,
                                    'message' => 'Error matematico'
                                );
                                return Response::json($returnData, 400);
                                break;
                        }
                            /*Match error*/
                        case "PG3005":
                            {
                                $returnData = array (
                                    'status' => 400,
                                    'message' => 'Error de conexion'
                                );
                                return Response::json($returnData, 400);
                                break;
                        }
                            /*Disabled connection*/
                        default:
                            $returnData = array (
                                'status' => 400,
                                'message' => 'No ha podido funcionar 1 '.$Pagadito->get_rs_code()
                            );
                            return Response::json($returnData, 400);
                            break;
                    }
                }else{
                    $returnData = array (
                        'status' => 200,
                        'message' => 'Funcionando genial',
                        'token' => $eeror
                    );
                    return Response::json($returnData, 200);
                }
            } else {
                /*
                 * En caso de fallar la conexión, verificamos el error devuelto.
                 * Debido a que la API nos puede devolver diversos mensajes de
                 * respuesta, validamos el tipo de mensaje que nos devuelve.
                 */
                switch($Pagadito->get_rs_code())
                {
                    case "PG2001":
                        /*Incomplete data*/
                    case "PG3001":
                        /*Problem connection*/
                    case "PG3002":
                        /*Error*/
                    case "PG3003":
                        /*Unregistered transaction*/
                    case "PG3005":
                        /*Disabled connection*/
                    case "PG3006":
                        /*Exceeded*/
                    default:
                        $returnData = array (
                            'status' => 400,
                            'message' => 'No ha podido funcionar 2'
                        );
                        return Response::json($returnData, 400);
                        break;
                }
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
        $objectSee = EventosVenta::find($id);
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
        $objectUpdate = EventosVenta::find($id);
        if ($objectUpdate) {
            try {
                $objectUpdate->column = $request->get('column', $objectUpdate->column);
    
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
        $objectDelete = EventosVenta::find($id);
        if ($objectDelete) {
            try {
                EventosVenta::destroy($id);
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
