<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use App\Models\Package;
use App\Models\UserPackage;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Http\Resources\PackageResource;
use Carbon\Carbon;

class AdvertiserController extends BaseController
{
   

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {  
        // $user = Auth::user(); 

        // $user = Auth::user(); 
        // print_r($user);
        // $packages = UserPackage::where('user', Auth::user());


        $userPackage = UserPackage::where('user_id', $request->user_id);
        if (is_null($userPackage)) {
            return $this->sendError('Package not found.');
        } 

        return $this->sendResponse($userPackage, 'User Packages retrieved successfully.'); 
    }


    public function user_packages($user_id)
    {    
        $userPackage = UserPackage::where('user_id', $user_id)->latest()->first();    
        if (is_null($userPackage)) {
            return $this->sendError('Package not found.');
        }  
        return $this->sendResponse($userPackage, 'User Packages retrieved successfully.'); 
    }


    
    public function purchase_package(Request $request)
    {    

        $user_id = $request->user_id;
        $package_id = $request->package_id;

        if(is_null($user_id) || is_null($package_id)) {
            return $this->sendError('Something went wrong, please try again.');
        }

        // $package_id = 1;

        $package = Package::where('id', $package_id)->first();
        $package_days = $package->expirydays;
        $expired_at = Carbon::now()->addDays($package_days);

        if($package_days > 0) {

            $userPackage = UserPackage::create([
                'user_id' => $user_id,
                'package_id' => $package_id,
                'expired_at' => $expired_at
            ]);    
            
            return $this->sendResponse($userPackage, 'This package has been purchased successfully.'); 

        }

        
    }

   

      
    
}