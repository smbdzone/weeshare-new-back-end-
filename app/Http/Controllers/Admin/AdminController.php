<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\API\BaseController as BaseController; 
use Illuminate\Http\Request;
use Validator;


class AdminController extends BaseController
{
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|max:191',
            'password' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        } 
        else 
        {
            $input = $request->all();

            $admin = Admin::where('email', $request->email)->first();

            
        }
       


    }
}
