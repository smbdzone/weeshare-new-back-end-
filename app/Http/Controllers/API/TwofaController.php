<?php
   
namespace App\Http\Controllers\API;
   
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;

use Validator;
   
class TwofaController extends BaseController
{
    
    
    public function twofa_status(Request $request)
    { 

        $input = $request->all();

        $validator = Validator::make($input, [ 
            'twofa_status' => 'required',  
        ]);
 
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
    
       
        $twofa_status = $request->twofa_status;

        $user = $request->user();

        $twofa = User::where('id', $user->id)->update(['twofa_status' => $twofa_status]); 

        return $this->sendResponse($twofa, '2FA enabled.'); 

    }
 


    
  
}