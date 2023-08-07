<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\UserDetail;
use Validator; 

class UserDetailController extends BaseController 
{
    public function index(Request $request){

        
        $user_id = $request->user_id; 
        return $user_id;
    }
}
