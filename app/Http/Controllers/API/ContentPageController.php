<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\ContentPage; 
   
class ContentPageController extends BaseController
{
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function page_content(Request $request, $slug)
    { 
        $page = ContentPage::where('slug', $slug)
        ->where('status', 1) 
        ->first(); 

        if (is_null($page)) {
            return $this->sendError('Page not found.');
        }
   
        return $this->sendResponse($page, 'Page retrieved successfully.'); 
    }


    public function pages(Request $request) {
        $pages = ContentPage::where('status', 1)->get(); 
        if (is_null($pages)) {
            return $this->sendError('Page not found.');
        }
   
        return $this->sendResponse($pages, 'Pages retrieved successfully.'); 
    }
    
  
}