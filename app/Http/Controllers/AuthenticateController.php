<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Users;
use Response;
use Validator;
use Auth;

class AuthenticateController extends Controller
{
    public function login(Request $request) {
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

                $userdata = array(
                    'username'  => $request->get('username'),
                    'password'  => $request->get('password')
                );

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
