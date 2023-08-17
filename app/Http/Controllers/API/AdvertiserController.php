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
   

    public function __construct(Request $request){
 
        // $getuser = $request->user();

        
    }
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


        // $userPackage = UserPackage::where('user_id', $request->user_id);
        // if (is_null($userPackage)) {
        //     return $this->sendError('Package not found.');
        // } 

        return $this->sendResponse('This is index page', 'Advertiser Controller'); 
    }

    public function profile_image(Request $request) {

        $extension = request()->file('profile_picture')->extension();

        // return $this->sendError($extension, 'only these extensions are allowed.'); 

        $extension_array = array('jpg', 'jpeg', 'png');

        if (!in_array($extension, $extension_array)) {
            return $this->sendError('jpg, jpeg, png', 'only these extensions are allowed.'); 
        }
 

        $getuser = $request->user();
        $user = User::where('id', $getuser->id)->latest()->first();    
        if (is_null($user)) {
            return $this->sendError('Profile not found.');
        }  

        if (request()->file('profile_picture')) {

            $filename = rand() . '_' . $request->file('profile_picture')->getClientOriginalName();
            $request->file('profile_picture')->storeAs('profiles', $filename, 'public');

            $user->update([
                'profile_picture' => $filename,
            ]);
        } 

        // $data = array('user' => $user);
        return $this->sendResponse($user, 'Advertiser Profile'); 
    }

    //View Profile
    public function view_profile(Request $request)
    {    
        $getuser = $request->user();
        $user = User::where('id', $getuser->id)->latest()->first();    
        if (is_null($user)) {
            return $this->sendError('Profile not found.');
        }  

        $data = array('user' => $user);
        return $this->sendResponse($data, 'Advertiser Profile'); 
    }


    // Edit Profile
    public function edit_profile(Request $request)
    {    
        $getuser = $request->user();
        $user = User::where('id', $getuser->id)->latest()->first();    
        if (is_null($user)) {
            return $this->sendError('Profile not found.');
        }  

        $data = array('user' => $user);
        return $this->sendResponse($data, 'Advertiser Profile'); 
    }


    //Update Profile
    public function update_profile(Request $request)
    {    
 
        $validator = Validator::make($request->all(), [ 
            'name' => 'required',  
            'phone_no' => 'required', 
            'biography' => 'required', 
            'country_id' => 'required', 
            'state_id' => 'required', 
            'city_id' => 'required', 
            'address' => 'required', 
            'postcode' => 'required',  
        ]); 

        if($validator->fails()){ 
            return $this->sendError('Validation Error.', $validator->errors());        
        }


        $getuser = $request->user();
        $user = User::where('id', $getuser->id)->latest()->first();    
        if (is_null($user)) {
            return $this->sendError('Profile not found.');
        }  

        $user->update([
            'name' => $request->name, 
            'phone_no' => $request->phone_no,
            'biography' => $request->biography,
            'country_id' => $request->country_id,
            'state_id' => $request->state_id,
            'city_id' => $request->city_id,
            'address' => $request->address,
            'postcode' => $request->postcode,
        ]);  


        $data = array('user' => $user);
        return $this->sendResponse($data, 'Advertiser Profile'); 
    }


    //User Packages
    public function user_packages(Request $request)
    {    
        $getuser = $request->user();
        $user_id = $getuser->id;
        $userPackage = UserPackage::where('user_id', $user_id)->latest()->first();    
        if (is_null($userPackage)) {
            return $this->sendError('Package not found.');
        }  
        return $this->sendResponse($userPackage, 'User Packages retrieved successfully.'); 
    }


    
    public function purchase_package(Request $request)
    {    

        $user = $request->user();
        $user_id = $user->id;
        $package_id = $request->package_id;
 

        if(is_null($user_id)) {
            return $this->sendError('User is not logged in.');
        }

        if(is_null($package_id)) {
            return $this->sendError('Please select a package and continue.');
        }

        


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