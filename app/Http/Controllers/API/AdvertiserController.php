<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use App\Models\Package;
use App\Models\UserPackage;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
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
        
        // if (is_null($check_package)) {
        //     $user['package'] = null;
        //     // return $this->sendError('Package not found.');
        // } 


        $user = $request->user(); 
        $user_id = $user->id;

        // Check Package
        $check_package = UserPackage::where('user_id', $user_id)->first();

        // Survey Status



        $user['package'] = $check_package;
        return $this->sendResponse($user, 'Advertiser Dashboard'); 
    }

    public function profile_image(Request $request) {

        $extension = request()->file('profile_picture')->extension();

        // return $this->sendError($extension, 'only these extensions are allowed.'); 

        $extension_array = array('jpg', 'jpeg', 'png', 'webp');

        if (!in_array($extension, $extension_array)) {
            return $this->sendError('jpg, jpeg, png, webp', 'only these extensions are allowed.'); 
        }
 

        $getuser = $request->user();
        $user = User::where('id', $getuser->id)->latest()->first();    
        if (is_null($user)) {
            return $this->sendError('Profile not found.');
        }  

        if (request()->file('profile_picture')) {

            $filename = rand() . '_' . $request->file('profile_picture')->getClientOriginalName();
            $request->file('profile_picture')->storeAs('profiles', $filename, 'public');
            // $profile_picture_url = url('storage/profiles/'.$filename);
            $profile_picture_url = asset('storage/profiles/'. $filename);
            $user->update([
                'profile_picture' => $filename,
                'profile_picture_url' => $profile_picture_url,
            ]);
        } 

        // $data = array('user' => $user);
        return $this->sendResponse($profile_picture_url, 'Profile Image URL'); 
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

        $user = User::find($getuser->id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
    
        // return view('users.edit',compact('user','roles','userRole'));


        $data = array('user' => $user, 'roles' => $roles, 'userRole' => $userRole );
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
            return $this->sendError('You are not logged in, pleae login to continue.');
        }

        if(is_null($package_id)) {
            return $this->sendError('Please select a package and continue.');
        }

        
        // 1. Purchase free trial
        // - check if already have free trial


        // 2. Purchase Basic Plan
        // - Check if free trial is purchased, then add the days if it is not expired.

        // 3. Purchase Business Plan
        // - Check if free trial is purchased, then add the days if it is not expired.
        // - Check if basic plan is purchased, then add the days if it is not expired.
        

        $check_package = UserPackage::where('user_id', $user_id)
        ->where('expiry_status', '0')
        ->where('package_id', $package_id)
        ->latest()->first();

        // return $this->sendResponse($check_package, 'Check user package'); 
       

        if(!is_null($check_package) && $package_id == '1') {
            return $this->sendError('Please note that our 14-day trial is a one-time offer, and additional trials are not available once the initial trial period has been utilized');
        }

        

        $package = Package::where('id', $package_id)->where('status', '1')->first();
        $package_days = $package->expirydays;
        $expired_at = Carbon::now()->addDays($package_days);

        if($package_days > 0) {

            $userPackage = UserPackage::create([
                'user_id' => $user_id,
                'package_id' => $package_id,
                'expired_at' => $expired_at
            ]);    
            if($package_id == '1') {
                $message = 'Thank you for choosing the free trial package subscription! Please check your email for further instructions and details regarding your subscription.';
            } else {
                $message = 'Thank you for choosing '.$package->title.' package! Please check your email for further instructions and details regarding your subscription.';
            }
            return $this->sendResponse($userPackage, $message); 

        }

        
    }

   
    // public function logout(Request $request): LogoutResponse
    // {
    //     $this->guard->logout();

    //     $request->session()->invalidate();

    //     $request->session()->regenerateToken();

    //     return app(LogoutResponse::class);
    // }

   
    public function switch_profile(Request $request) {

        $data = [
            'currenct_role' => $request->currenct_role,
            'switchto_role' => $request->switchto_role, 
        ];

        
        return $this->sendResponse($data, 'Switch Profile'); 
    }



    public function edit(User $user) 
    {
        $data = [
            'user' => $user,
            'userRole' => $user->roles->pluck('name')->toArray(),
            'roles' => Role::latest()->get()
        ];

        return $this->sendResponse($data, 'User Roles'); 

    }



   

}