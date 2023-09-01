<?php

   

namespace App\Http\Controllers\API;
 
use Illuminate\Http\Request; 

use App\Http\Controllers\API\BaseController as BaseController;
 
use App\Models\Industry;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\SocialMediaType; 
 

   

class CommonController extends BaseController 
{ 
    
    public function countries(){
        // $countries = Country::with('states')->with('cities')->get();
        $countries = Country::all();
        return $this->sendResponse($countries, 'Countries list');
    }
    public function states($countryID){ 
        $states = State::where('country_id', $countryID)->get();
        return $this->sendResponse($states, 'States list');
    }
    public function cities($countryID, $stateID){ 
        $cities = City::where('country_id', $countryID)->where('state_id', $stateID)->get();
        return $this->sendResponse($cities, 'Cities list');
    }
    public function industries(){
        $industries = Industry::all();
        return $this->sendResponse($industries, 'Industries list');
    }
  
    public function social_media_types(){
        $medias = SocialMediaType::where('status', '1');
        return $this->sendResponse($medias, 'Social Media Type list');
    } 

}



