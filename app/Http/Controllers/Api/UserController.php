<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Validations\GenericValidations;
use App\Http\Resources\UserResource;
class UserController extends Controller
{
    public function register(Request $request){
        try {

			$validator = GenericValidations::RegisterUser($request);
			if (!empty($validator)) {
				$errorResponse =combinedFieldsResponse($validator);
				return sendError(422, $errorResponse, (object)[]);
			}

			$input = $request->all();
			$email = User::where('email', $input['email'])->first();
			if ($email) {
				return sendError(422, 'There is already an account associated with this email', (object)[]);

			}else{

				$data = new User();
				$data->name = $input['name'];
				$data->email = $input['email'];
				$data->password = Hash::make($input['password']);
                $data->save();
                $data->token = $data->createToken('mytoken')->plainTextToken;
                
                return sendResponse(200, 'Registration Successful!', new UserResource($data));
			}
        } catch (Exception $e) {
            $response = sendError(500, $e->getMessage(), (object)[]);
            return $response;
        }
    }
    
    public function login(Request $request){

        try{
            $validator = GenericValidations::Login($request);
            if (!empty($validator)) {
              $errorResponse = combinedFieldsResponse($validator);
              return sendResponse(422, $errorResponse, (object)[]);
            }
            $input = $request->all();
            $user = User::where(['email' => $input['email']])->first();
            if ($user) {
                  if (Hash::check($input['password'], $user->password)) {
                    $user->token = $user->createToken('mytoken')->plainTextToken;
                    $response = sendError(200, 'Login Successfully!', new UserResource($user));
                    } else {
                    $response = sendError(202, 'Password is incorrect!', (object)[]);
                    return $response;
                  }
                }else {
                $response = sendError(202, 'No User found with this email', (object)[]);
              }
              return $response;    
        } catch (\Illuminate\Database\QueryException $ex) {
            $response = sendError(500, $ex->getMessage(), (object)[]);
            return $response;
          }
       
    }

    public function logout(Request $request){
        try{
             auth()->user()->tokens()->delete();
             return sendResponse(200, 'Logout Successfull!', (object)[]);

        } catch (Exception $e) {
            $response = sendError(500, $e->getMessage(), (object)[]);
            return $response;
        }
    }

}
