<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;	
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Users;
use Response;
use Validator;
use Storage;

class AuthenticateController extends Controller
{
    public function login(Request $request) {
        if($request->get('google')=="google" || $request->get('google')=="facebook"){
            $objectSee = Users::whereRaw('email=? and google_id=?',[$request->get('email'),$request->get('google_id')])->with('comprados','myReferidos')->first();
            if ($objectSee) {
                $userdata = array(
                    'username'  => $request->get('username'),
                    'password'  => $request->get('password')
                );
                $token = JWTAuth::attempt($userdata);
                $objectSee->token = $token;
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
                    
                    $token = JWTAuth::attempt($userdata);
                    if($token) {
                        $user = Users::find(Auth::user()->id);
                        $user->last_conection = date('Y-m-d H:i:s');
                        $user->token = ($token);
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

    /**
     * Log out
     * Invalidate the token, so user cannot use it anymore
     * They have to relogin to get a new token
     *
     * @param Request $request
     */
    public function logout(Request $request) {
        $this->validate($request, ['token' => 'required']);
        
        try {
            JWTAuth::invalidate($request->input('token'));
            return response([
            'status' => 'success',
            'msg' => 'You have successfully logged out.'
        ]);
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response([
                'status' => 'error',
                'msg' => 'Failed to logout, please try again.'
            ]);
        }
    }

    public function refresh()
    {
        return response([
            'status' => 'success'
        ]);
    }
}
