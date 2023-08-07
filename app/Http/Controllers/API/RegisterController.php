<?php

   

namespace App\Http\Controllers\API;

   

use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

use App\Http\Controllers\API\BaseController as BaseController;

use App\Models\User;


use Illuminate\Support\Facades\Auth;

use Validator;

   

class RegisterController extends BaseController

{

    /**

     * Register api

     *

     * @return \Illuminate\Http\Response

     */

    public function register(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'user_type' => 'required',
            'name' => 'required', 
            'email' => 'required|email', 
            'password' => [
                'required',
                Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised()
            ],
            'c_password' => 'required|same:password',

        ]);
        
   

        if($validator->fails()){

            return $this->sendError('Validation Error.', $validator->errors());       

        }

   

        $input = $request->all();

        $input['password'] = bcrypt($input['password']);

        $user = User::create($input);

        $success['token'] =  $user->createToken('MyApp')->plainTextToken;

        $success['name'] =  $user->name;

   

        return $this->sendResponse($success, 'User register successfully.');

    }

   

    /**

     * Login api

     *

     * @return \Illuminate\Http\Response

     */

    public function login(Request $request) 
    {


        $validator = Validator::make($request->all(), [

            'user_type' => 'required', 
            'email' => 'required|email', 
            'password' => [
                'required',
                Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised()
            ], 

        ]);
        
   

        if($validator->fails()){ 
            return $this->sendError('Validation Error.', $validator->errors());      
        }


        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 

            $user = Auth::user(); 

            $success['token'] =  $user->createToken('MyApp')->plainTextToken; 
            $success['user_type'] =  $user->user_type; 
            $success['name'] =  $user->name;  
            $success['email'] =  $user->email; 
            $success['email_verified_at'] =  $user->email_verified_at; 
            $success['profile_picture'] =  $user->profile_picture; 

            return $this->sendResponse($success, 'User login successfully.');

        } 

        else{ 

            return $this->sendError('Unauthorised.', ['error'=>'Invalid username or password.']);

        } 

    }

 
    public function index() {
       $industries =  $this->industries();
       return $this->sendResponse($industries, 'Industries list');
    }

    // public function industries(){
    //     $industries = Industry::all();
    //     return $this->sendResponse($industries, 'Industries list');
    // }

    // public function countries(){
    //     $industries = Industry::all();
    //     return $this->sendResponse($industries, 'Industries list');
    // }


}



