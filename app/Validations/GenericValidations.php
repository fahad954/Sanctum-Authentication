<?php
namespace App\Validations;

use Illuminate\Support\Facades\Validator;

class GenericValidations
{
    public static function commonValidation($request,$validationAttribute)
    {
        $validator = Validator::make($request->all(), [
            $validationAttribute => 'required',
        ]);
        if ($validator->fails()) {
            return $validator;
        }
    }
    public static function RegisterUser($request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email:rfc,dns|unique:users,email,',
                'password' => 'required|min:6',
            ],
            [
                'email.required' => 'Email is required!',
                'password.required' => 'Password is required!',
            ]
        );
        if ($validator->fails()) {
            return $validator;
        }
    }
    public static function Login($request)
    {
        $validator = Validator::make($request->all(), [
            'email' =>'required|email:rfc,dns',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return $validator;
        }
    }
}

?>