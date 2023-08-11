<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Package;
use Validator;
use App\Http\Resources\PackageResource;
   
class PackageController extends BaseController
{
   

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {


        // return $this->sendResponse('I m here', 'userID');

        $packages = Package::all();
        return $this->sendResponse($packages, 'Packages retrieved successfully.');
        // return $this->sendResponse(PackageResource::collection($packages), 'Packages retrieved successfully.');

        // $userID = auth()->user()->id;
        // return $this->sendResponse($userID, 'userID');
  
        // $posts = Post::where('user_id', auth()->user()->id)->get(); 
        // return $this->sendResponse(PostResource::collection($posts), 'Posts retrieved successfully.');
    }
   
     
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $package = Package::find($id);
  
        if (is_null($package)) {
            return $this->sendError('Package not found.');
        }
   
        return $this->sendResponse(new PackageResource($package), 'Package retrieved successfully.');
    }
    
 
    
}