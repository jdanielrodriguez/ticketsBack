<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;

use App\Http\Requests;
use App\Users;
use Faker\Factory as Faker;
use Response;
use Validator;
use Hash;
use DB;


class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Response::json(Users::with('roles')->get(), 200);
    }

    public function getUsersByRol($id)
    {
        return Response::json(Users::whereRaw('rol=?',$id)->with('roles')->get(), 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *'password'      => 'required|min:3|regex:/^.*(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!-,:-@]).*$/',
     */
     public function store(Request $request)
     {
         $validator = Validator::make($request->all(), [
             'password'      => 'required|min:3',
             'email'         => 'required|email'
         ]);
         
 
         if ($validator->fails()) {
             $returnData = array(
                 'status' => 400,
                 'message' => 'Invalid Parameters',
                 'validator' => $validator->messages()->toJson()
             );
             return Response::json($returnData, 400);
         }
         else {
             $email = $request->get('email');
             $email_exists  = Users::whereRaw("email = ?", $email)->count();
             $user = $request->get('username');
             $user_exists  = Users::whereRaw("username = ?", $user)->count();
             if($email_exists == 0 && $user_exists == 0){    
                     $newObject = new Users();
                     $newObject->username = $request->get('username');
                     $newObject->password = Hash::make($request->get('password'));
                     $newObject->email = $email;
                     $newObject->nombres = $request->get('nombres');
                     $newObject->apellidos = $request->get('apellidos');
                     $newObject->rol = $request->get('rol');
                     $newObject->nacimiento = $request->get('nacimiento');
                     $newObject->descripcion = $request->get('descripcion', '');
                     $newObject->state = $request->get('state',21);
                     $newObject->save();
                     $objectSee = Users::whereRaw('id=?',$newObject->id)->with('roles')->first();
                     if ($objectSee) {
                        Mail::send('emails.confirm', ['empresa' => 'Jose Daniel Rodriguez', 'url' => 'https://www.JoseDanielRodriguez.com', 'app' => 'http://me.JoseDanielRodriguez.gt', 'password' => $request->get('password'), 'username' => $objectSee->username, 'email' => $objectSee->email, 'name' => $objectSee->nombres.' '.$objectSee->apellidos,], function (Message $message) use ($objectSee){
                            $message->from('jdanielr61@gmail.com', 'Info Jose Daniel Rodriguez')
                                    ->sender('jdanielr61@gmail.com', 'Info Jose Daniel Rodriguez')
                                    ->to($objectSee->email, $objectSee->nombres.' '.$objectSee->apellidos)
                                    ->replyTo('jdanielr61@gmail.com', 'Info Jose Daniel Rodriguez')
                                    ->subject('Usuario Creado');
                        
                        });
                         return Response::json($objectSee, 200);
                        }
                        else {
                            $returnData = array (
                                'status' => 404,
                                'message' => 'No record found'
                            );
                            return Response::json($returnData, 404);
                        }
             }else{
                $returnData = array(
                    'status' => 400,
                    'message' => 'User already exists',
                    'validator' => $validator->messages()->toJson()
                );
                return Response::json($returnData, 400);
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
        $objectSee = Users::whereRaw('id=?',$id)->with('roles')->first();
        if ($objectSee) {
            return Response::json($objectSee, 200);
        }
        else {
            $returnData = array(
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
        $objectUpdate = Users::whereRaw('id=?',$id)->first();
        if ($objectUpdate) {
            try {
                $objectUpdate->username = $request->get('username', $objectUpdate->username);
                $objectUpdate->email = $request->get('email', $objectUpdate->email);
                $objectUpdate->nombres = $request->get('nombres', $objectUpdate->nombres);
                $objectUpdate->apellidos = $request->get('apellidos', $objectUpdate->apellidos);
                $objectUpdate->descripcion = $request->get('descripcion', $objectUpdate->descripcion);
                $objectUpdate->nacimiento = $request->get('nacimiento', $objectUpdate->nacimiento);
                $objectUpdate->state = $request->get('state', $objectUpdate->state);
                $objectUpdate->rol = $request->get('rol', $objectUpdate->rol);
                $objectUpdate->save();
                $objectUpdate->roles;

                return Response::json($objectUpdate, 200);
            }catch (\Illuminate\Database\QueryException $e) {
                if($e->errorInfo[0] == '01000'){
                    $errorMessage = "Error Constraint";
                }  else {
                    $errorMessage = $e->getMessage();
                }
                $returnData = array (
                    'status' => 505,
                    'SQLState' => $e->errorInfo[0],
                    'message' => $errorMessage
                );
                return Response::json($returnData, 500);
            }
            catch (Exception $e) {
                $returnData = array(
                    'status' => 500,
                    'message' => $e->getMessage()
                );
            }
        }
        else {
            $returnData = array(
                'status' => 404,
                'message' => 'No record found'
            );
            return Response::json($returnData, 404);
        }
    }

    public function recoveryPassword(Request $request){
        $objectUpdate = Users::whereRaw('email=? or username=?',[$request->get('username'),$request->get('username')])->first();
        if ($objectUpdate) {
            try {
                $faker = Faker::create();
                // $pass = $faker->password(8,15,true,true);
                $pass = $faker->regexify('[a-zA-Z0-9-_=+*%@!]{8,15}');
                $objectUpdate->password = Hash::make($pass);
                $objectUpdate->state = 21;
                
                Mail::send('emails.recovery', ['empresa' => 'Jose Daniel Rodriguez', 'url' => 'https://www.JoseDanielRodriguez.com', 'password' => $pass, 'username' => $objectUpdate->username, 'email' => $objectUpdate->email, 'name' => $objectUpdate->nombres.' '.$objectUpdate->apellidos,], function (Message $message) use ($objectUpdate){
                    $message->from('jdanielr61@gmail.com', 'Info Jose Daniel Rodriguez')
                            ->sender('jdanielr61@gmail.com', 'Info Jose Daniel Rodriguez')
                            ->to($objectUpdate->email, $objectUpdate->nombres.' '.$objectUpdate->apellidos)
                            ->replyTo('jdanielr61@gmail.com', 'Info JoseDanielRodriguez')
                            ->subject('Contraseña Reestablecida');
                
                });
                
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

    public function changePassword(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'new_pass' => 'required|min:3',
            'old_pass'      => 'required'
        ]);

        if ($validator->fails()) {
            $returnData = array(
                'status' => 400,
                'message' => 'Invalid Parameters',
                'validator' => $validator->messages()->toJson()
            );
            return Response::json($returnData, 400);
        }
        else {
            $old_pass = $request->get('old_pass');
            $new_pass_rep = $request->get('new_pass_rep');
            $new_pass =$request->get('new_pass');
            $objectUpdate = Users::find($id);
            if ($objectUpdate) {
                try {
                    if(Hash::check($old_pass, $objectUpdate->password))
                    {                       
                        if($new_pass_rep != $new_pass)
                        {
                            $returnData = array(
                                'status' => 404,
                                'message' => 'Passwords do not match'
                            );
                            return Response::json($returnData, 404);
                        }

                        if($old_pass == $new_pass)
                        {
                            $returnData = array(
                                'status' => 404,
                                'message' => 'New passwords it is same the old password'
                            );
                            return Response::json($returnData, 404);
                        }
                        $objectUpdate->password = Hash::make($new_pass);
                        $objectUpdate->state = 1;
                        $objectUpdate->save();

                        return Response::json($objectUpdate, 200);
                    }else{
                        $returnData = array(
                            'status' => 404,
                            'message' => 'Invalid Password'
                        );
                        return Response::json($returnData, 404);
                    }
                }
                catch (Exception $e) {
                    $returnData = array(
                        'status' => 500,
                        'message' => $e->getMessage()
                    );
                }
            }
            else {
                $returnData = array(
                    'status' => 404,
                    'message' => 'No record found'
                );
                return Response::json($returnData, 404);
            }
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
        $objectDelete = Users::find($id);
        if ($objectDelete) {
            try {
                Users::destroy($id);
                return Response::json($objectDelete, 200);
            }
            catch (Exception $e) {
                $returnData = array(
                    'status' => 500,
                    'message' => $e->getMessage()
                );
                return Response::json($returnData, 500);
            }
        }
        else {
            $returnData = array(
                'status' => 404,
                'message' => 'No record found'
            );
            return Response::json($returnData, 404);
        }
    }
}
