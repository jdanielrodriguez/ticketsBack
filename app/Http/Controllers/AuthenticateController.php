<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Users;
use Response;
use Validator;
use Storage;
use Auth;

class AuthenticateController extends Controller
{
    public function login(Request $request) {
        if($request->get('google')=="google"){
            $objectSee = Users::whereRaw('email=? and google_id=?',[$request->get('email'),$request->get('google_id')])->with('comprados','myReferidos')->first();
            if ($objectSee) {
                if($objectSee->google_token==$request->get('google_token')){
                    $objectSee->google_idToken=$request->get('google_idToken');
                    $objectSee->foto=$request->get('imagen');
                    $objectSee->save();
                    return Response::json($objectSee, 200);
                }else{
                    $objectSee->google_token=$request->get('google_token');
                    $objectSee->google_idToken=$request->get('google_idToken');
                    $objectSee->google_id=$request->get('google_id');
                    $objectSee->foto=$request->get('imagen');
                    $objectSee->save();
                    return Response::json($objectSee, 200);
                }
                
            }
            else {
                $returnData = array(
                    'status' => 404,
                    'message' => 'No record found'
                );
                return Response::json($returnData, 404);
            }
        }else{
            $validator = Validator::make($request->all(), [
                'username'  => 'required',
                'password'  => 'required'
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
                    $validator = Validator::make($request->all(), [
                        'username'  => 'email',
                    ]);
                    $userdata = array();
                    if ( $validator->fails() ) {
                        $userdata = array(
                            'username'  => $request->get('username'),
                            'password'  => $request->get('password')
                        );
                    }else{
                        // $field = (preg_match('/^[A-z0-9\\._-]+@[A-z0-9][A-z0-9-]*(\\.[A-z0-9_-]+)*\\.([A-z]{2,6})$/', $request->get('username'), null)) ? 'email' : 'username';
                        $userdata = array(
                            'email'  => $request->get('username'),
                            'password'  => $request->get('password')
                        );
                    }
                    
    
                    if(Auth::attempt($userdata, true)) {
                        $user = Users::find(Auth::user()->id);
                        $user->save();
                        return Response::json($user, 200);
                    }
                    else {
                        $returnData = array (
                            'status' => 401,
                            'message' => 'No valid Username or Password'
                        );
                        return Response::json($returnData, 401);
                    }
    
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
        
    }

    public function uploadAvatar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'avatar'      => 'required|image|mimes:jpeg,png,jpg',
            'carpeta'      => 'required'
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
            try {
                $path = Storage::disk('s3')->put($request->carpeta, $request->avatar);
                $returnData = array(
                    'status' => 200,
                    'message' => 'Picture Upload Success',
                    'picture' => Storage::disk('s3')->url($path)
                );
                return Response::json($returnData, 200);
            }
            catch (Exception $e) {
                $returnData = array(
                    'status' => 500,
                    'message' => 'Invalid Parameters',
                    'error' => $e
                );
                return Response::json($returnData, 500);
            }
    
        }
    }
}
