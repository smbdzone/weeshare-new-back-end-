<?php

namespace App\Http\Controllers\API;


use App\Models\SocialMediaType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;

// use App\Models\Industry;
// use App\Models\Country;
// use App\Models\State;
// use App\Models\City;

class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message)
    {
    	$response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];


        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 422)
    {
    	$response = [
            'success' => false,
            'message' => $error,
        ];


        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }


        return response()->json($response, $code);
    }


    // public function countries(){
    //     $industries = Country::all();
    //     return $this->sendResponse($industries, 'Countries list');
    // }
    // public function states($countryID){
    //     $industries = State::where('country_id', $countryID);
    //     return $this->sendResponse($industries, 'States list');
    // }
    // public function cities($countryID, $stateID){
    //     $industries = City::where('country_id', $countryID)->where('state_id', $stateID);
    //     return $this->sendResponse($industries, 'Cities list');
    // }

    // public function industries(){
    //     $industries = Industry::all();
    //     return $this->sendResponse($industries, 'Industries list');
    // }
 

    // public function social_media_types(){
    //     $medias = SocialMediaType::where('status', '1');
    //     return $this->sendResponse($medias, 'Social Media Type list');
    // }


}