<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;

class PublisherController extends BaseController
{
     
    public function index(Request $request) {
        $user = $request->user();
        return $this->sendResponse($user, 'Publisher Dashboard'); 
    }
}
