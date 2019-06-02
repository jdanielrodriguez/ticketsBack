<?php

namespace App\Http\Controllers;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;

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
                case 'usuario':{
                    $objectSee = EventosVenta::whereRaw('usuario=?',[$id])->with('usuarios','eventos','area','vendedores','descuentos')->get();
                    break;
                }
                case 'evento':{
                    $objectSee = EventosVenta::whereRaw('evento=?',[$id])->with('usuarios','eventos','area','vendedores','descuentos')->get();
                    break;
                }
                case 'usuario_evento':{
                    $objectSee = EventosVenta::whereRaw('usuario=? and evento=?',[$id,$state])->with('usuarios','eventos','area','vendedores','descuentos')->get();
                    break;
                }
                case 'evento_funcion':{
                    $objectSee = EventosVenta::whereRaw('evento_funcion=?',[$id])->with('usuarios','eventos','area','vendedores','descuentos')->get();
                    break;
                }
                case 'evento_funcion_area_lugar':{
                    $objectSee = EventosVenta::whereRaw('evento_funcion_area_lugar=?',[$id])->with('usuarios','eventos','area','vendedores','descuentos')->get();
                    break;
                }
                case 'evento_vendedor':{
                    $objectSee = EventosVenta::whereRaw('evento_vendedor=?',[$id])->with('usuarios','eventos','area','vendedores','descuentos')->get();
                    break;
                }
                case 'evento_funcion_evento_vendedor':{
                    $objectSee = EventosVenta::whereRaw('evento_funcion=? and evento_vendedor=?',[$id,$state])->with('usuarios','eventos','area','vendedores','descuentos')->get();
                    break;
                }
                case 'evento_funcion_usuario':{
                    $objectSee = EventosVenta::whereRaw('evento_funcion=? and usuario=?',[$id,$state])->with('usuarios','eventos','area','vendedores','descuentos')->get();
                    break;
                }
                default:{
                    $objectSee = EventosVenta::whereRaw('usuario=? and state=?',[$id,$state])->with('usuarios','eventos','area','vendedores','descuentos')->get();
                    break;
                }
    
            }
        }else{
            $objectSee = EventosVenta::whereRaw('usuario=?',[$id])->with('usuarios','eventos','area','vendedores','descuentos')->get();
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
            'evento_funcion_area_lugar'          => 'required',
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
                $objectSee = EventosVenta::whereRaw('usuario=? and evento_funcion_area_lugar=?',[$request->get('usuario'),$request->get('evento_funcion_area_lugar')])->with('usuarios','eventos','area','vendedores','descuentos')->first();
                if($objectSee){
                    return Response::json($objectSee, 200);
                }else{
                    $newObject = new EventosVenta();
                    $newObject->titulo            = $request->get('titulo');
                    $newObject->lugar            = $request->get('lugar');
                    $newObject->codigo            = $request->get('codigo');
                    $newObject->precio            = $request->get('precio');
                    $newObject->cantidad            = $request->get('cantidad');
                    $newObject->total            = $request->get('total');
                    $newObject->fechaAprobacion            = $request->get('fechaAprobacion');
                    $newObject->fechaAprobacionS            = $request->get('fechaAprobacionS');
                    $newObject->descripcion            = $request->get('descripcion');
                    $newObject->latitud            = $request->get('latitud');
                    $newObject->longitud            = $request->get('longitud');
                    $newObject->type            = $request->get('type');
                    $newObject->state            = $request->get('state');
                    $newObject->usuario            = $request->get('usuario');
                    $newObject->evento            = $request->get('evento');
                    $newObject->evento_funcion            = $request->get('evento_funcion');
                    $newObject->evento_funcion_area_lugar            = $request->get('evento_funcion_area_lugar');
                    $newObject->evento_vendedor            = $request->get('evento_vendedor');
                    $newObject->evento_descuento            = $request->get('evento_descuento');
                    $newObject->token            = $request->get('token');
                    $newObject->ern            = $request->get('ern');
                    $newObject->aprobacion            = $request->get('aprobacion');
                    $newObject->save();
                    $newObject->usuarios;
                    $newObject->eventos;
                    $newObject->area;
                    $newObject->vendedores;
                    $newObject->descuentos;
                    return Response::json($newObject, 200);
                }
            } catch (Exception $e) {
                $returnData = array (
                    'status' => 500,
                    'message' => $e->getMessage()
                );
                return Response::json($returnData, 500);
            }
        }
    }
    public function enviar(Request $request){

        Mail::send('emails.pago', ["SelectedData"=>$request->get('SelectedData'),"comprobante"=>$request->get('comprobante'),"nombres"=>$request->get('nombres'),"apellidos"=>$request->get('apellidos')], function (Message $message) use ($request){
            $message->from('info@foxylabs.gt', 'Info GTechnology')
                    ->sender('info@foxylabs.gt', 'Info GTechnology')
                    ->to($request->get('email'), $request->get('nombres').' '.$request->get('apellidos'))
                    ->replyTo('info@foxylabs.gt', 'Info GTechnology')
                    ->subject('Comprobante de Pago');
        });
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

            if($request->get('SANDBOX')){
                define("UID", "00a44a36adaab6310e6bf306b9d5969b");
                define("WSK", "c6ef3680408284ca1addc87b64c48421");
                define("SANDBOX", true);
            }else{
                define("UID", "050a50ed89944a938c66f35fb546ccbd");
                define("WSK", "900494115d190897617ab68eec2f1acd");
                define("SANDBOX", false);
            }
            
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
                        case "PG3009":
                            {
                                $returnData = array (
                                    'status' => 400,
                                    'message' => 'La cantidad del precio es incorrecta'
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
                            'message' => 'No ha podido funcionar 2'.$Pagadito->get_rs_code()
                        );
                        return Response::json($returnData, 400);
                        break;
                }
            }
        
        }
    }
    public function comprobanteCompra(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token'          => 'required',
            'ern'            => 'required'
        ]);
        if ( $validator->fails() ) {
            $returnData = array (
                'status' => 400,
                'message' => 'No ha llenado los campos adecuadamente.',
                'validator' => $validator
            );
            return Response::json($returnData, 400);
        }else{
            $objectSee = EventosVenta::whereRaw('token=? and ern=?',[$request->get('token'),$request->get('ern')])->with('usuarios','eventos','area','vendedores','descuentos')->first();
            if(!$objectSee){
                if ($request->get('token')) {
                    /*
                     * Lo primero es crear el objeto Pagadito, al que se le pasa como
                     * parámetros el UID y el WSK definidos en config.php
                     */
                    if($request->get('SANDBOX')){
                        define("UID", "00a44a36adaab6310e6bf306b9d5969b");
                        define("WSK", "c6ef3680408284ca1addc87b64c48421");
                        define("SANDBOX", true);
                    }else{
                        define("UID", "050a50ed89944a938c66f35fb546ccbd");
                        define("WSK", "900494115d190897617ab68eec2f1acd");
                        define("SANDBOX", false);
                    }
                        
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
                         * Solicitamos el estado de la transacción llamando a la función
                         * get_status(). Le pasamos como parámetro el token recibido vía
                         * GET en nuestra URL de retorno.
                         */
                        if ($Pagadito->get_status($request->get('token'))) {
        
                            /*
                             * Luego validamos el estado de la transacción, consultando el
                             * estado devuelto por la API.
                             */
                            switch($Pagadito->get_rs_status())
                            {
                                case "COMPLETED":{
                                    /*
                                     * Tratamiento para una transacción exitosa.
                                     */ ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                     $returnData11 = array (
                                        'status' => 200,
                                        'token' => $request->get('token'),
                                        'ern' => $request->get('ern'),
                                        'aprobacion' => $Pagadito->get_rs_reference(),
                                        'fecha' => $Pagadito->get_rs_date_trans(),
                                        'message' => 'Compra Exitosa'
                                    );
                                    return Response::json($returnData11, 200);
                                }
                                
                                case "REGISTERED":{
                                    
                                    /*
                                     * Tratamiento para una transacción aún en
                                     * proceso.
                                     */ ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                     $returnData = array (
                                        'status' => 400,
                                        'token' => $request->get('token'),
                                        'message' => "operacion Cancelada"
                                    );
                                    return Response::json($returnData, 400);
                                    break;
                                }
                                
                                case "VERIFYING":{
                                    
                                    /*
                                     * La transacción ha sido procesada en Pagadito, pero ha quedado en verificación.
                                     * En este punto el cobro xha quedado en validación administrativa.
                                     * Posteriormente, la transacción puede marcarse como válida o denegada;
                                     * por lo que se debe monitorear mediante esta función hasta que su estado cambie a COMPLETED o REVOKED.
                                     */ ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                    $returnData = array (
                                         'status' => 200,
                                         'token' => $request->get('token'),
                                         'aprobacion' => $Pagadito->get_rs_reference(),
                                         'fecha' => $Pagadito->get_rs_date_trans(),
                                         'message' => 'Compra en Validacion'
                                     );
                                     return Response::json($returnData, 200);
                                    break;}
                                
                                case "REVOKED":{
                                    
                                    /*
                                     * La transacción en estado VERIFYING ha sido denegada por Pagadito.
                                     * En este punto el cobro ya ha sido cancelado.
                                     */ ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                     $returnData = array (
                                        'status' => 400,
                                        'token' => $request->get('token'),
                                        'message' => "Compra Denegada"
                                    );
                                    return Response::json($returnData, 400);
                                    break;}
                                
                                case "FAILED":
                                    /*
                                     * Tratamiento para una transacción fallida.
                                     */
                                    {
                                        $returnData = array (
                                            'status' => 500,
                                            'token' => $request->get('token'),
                                            'message' => "Compra Denegada"
                                        );
                                        return Response::json($returnData, 500);
                                    }
                                default:
                                    {
                                    /*
                                     * Por ser un ejemplo, se muestra un mensaje
                                     * de error fijo.
                                     */ ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                     $returnData = array (
                                        'status' => 500,
                                        'token' => $request->get('token'),
                                        'message' => "Compra Denegada"
                                    );
                                    return Response::json($returnData, 500);
                                    break;}
                            }
                        } else {
                            /*
                             * En caso de fallar la petición, verificamos el error devuelto.
                             * Debido a que la API nos puede devolver diversos mensajes de
                             * respuesta, validamos el tipo de mensaje que nos devuelve.
                             */
                            switch($Pagadito->get_rs_code())
                            {
                                case "PG2001":
                                    /*Incomplete data*/
                                case "PG3002":
                                    /*Error*/
                                case "PG3003":
                                    /*Unregistered transaction*/
                                default:
                                    /*
                                     * Por ser un ejemplo, se muestra un mensaje
                                     * de error fijo.
                                     */ ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                     $returnData = array (
                                        'status' => 500,
                                        'token' => $request->get('token'),
                                        'message' => "error de transaccion Denegada"
                                    );
                                    return Response::json($returnData, 500);
                                    break;
                            }
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
                                /*
                                 * Aqui se muestra el código y mensaje de la respuesta del WSPG
                                 */
                                $returnData = array (
                                    'status' => 400,
                                    'token' => $request->get('token'),
                                    'message' => "Compra Denegada",
                                    'COD' => $Pagadito->get_rs_code(),
                                    'MSG' => $Pagadito->get_rs_message()
                                );
                                return Response::json($returnData, 400);
                                
                                break;
                        }
                    }
                } else {
                    /*
                     * Aqui se muestra el mensaje de error al no haber recibido el token por medio de la URL.
                     */
                    $returnData = array (
                        'status' => 400,
                        'token' => $request->get('token'),
                        'message' => "no se recibieron los datos",
                    );
                    return Response::json($returnData, 400);
                }
            }else{
                $returnData11 = array (
                    'status' => 203,
                    'token' => $objectSee->token,
                    'ern' => $objectSee->ern,
                    'aprobacion' => $objectSee->aprobacion,
                    'fecha' => $objectSee->fechaAprobacion,
                    'message' => 'Compra Exitosa'
                );
                return Response::json($returnData11, 200);
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
                $objectUpdate->evento_descuento = $request->get('evento_descuento', $objectUpdate->evento_descuento);
                $objectUpdate->evento_vendedor = $request->get('evento_vendedor', $objectUpdate->evento_vendedor);
                $objectUpdate->evento_funcion_area_lugar = $request->get('evento_funcion_area_lugar', $objectUpdate->evento_funcion_area_lugar);
                $objectUpdate->evento_funcion = $request->get('evento_funcion', $objectUpdate->evento_funcion);
                $objectUpdate->evento = $request->get('evento', $objectUpdate->evento);
                $objectUpdate->usuario = $request->get('usuario', $objectUpdate->usuario);
                $objectUpdate->state = $request->get('state', $objectUpdate->state);
                $objectUpdate->type = $request->get('type', $objectUpdate->type);
                $objectUpdate->longitud = $request->get('longitud', $objectUpdate->longitud);
                $objectUpdate->latitud = $request->get('latitud', $objectUpdate->latitud);
                $objectUpdate->descripcion = $request->get('descripcion', $objectUpdate->descripcion);
                $objectUpdate->total = $request->get('total', $objectUpdate->total);
                $objectUpdate->cantidad = $request->get('cantidad', $objectUpdate->cantidad);
                $objectUpdate->precio = $request->get('precio', $objectUpdate->precio);
                $objectUpdate->codigo = $request->get('codigo', $objectUpdate->codigo);
                $objectUpdate->lugar = $request->get('lugar', $objectUpdate->lugar);
                $objectUpdate->titulo = $request->get('titulo', $objectUpdate->titulo);
    
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
