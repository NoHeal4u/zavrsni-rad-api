<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;

use JWTAuth;
use Validator, DB, Hash;
use Illuminate\Support\Facades\Password;
use App\User;

class RegisterController extends Controller
{
   
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function register(Request $request)
    {
        $credentials = $request->only('first_name', 'last_name', 'accepted_terms_and_conditions', 'email', 'password', 'password_confirmation');
        
        $rules = [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'accepted_terms_and_conditions' => 'required|boolean:true',
            'email' => 'required|email|max:255|unique:users',
            'password' => ['required', 'min:8', 'regex:/^(?:[0-9]+[a-z]|[a-z]+[0-9])[a-z0-9]*$/i', 'confirmed'],
            // 'password_confirmation'//ovo polje ne treba po pravilu - testirati
        ];
        $validator = Validator::make($credentials, $rules);
        if($validator->fails()) {
            return response()->json(['success'=> false, 'error'=> $validator->messages()]);
        }
        $first_name = $request->first_name;
        $last_name = $request->last_name;
        $email = $request->email;
        $password = $request->password;
        $accepted_terms_and_conditions = $request->accepted_terms_and_conditions;
        // $remember_token = str_random(10);

        // var_dump($remember_token);
        
        User::create(['first_name' => $first_name, 'last_name' => $last_name, 'email' => $email, 'accepted_terms_and_conditions' => $accepted_terms_and_conditions, 'password' => Hash::make($password)]);

        return response()->json(['success'=> true, 'message'=> 'Thank you for signing up!']);
    }
   
}
