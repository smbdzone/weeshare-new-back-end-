<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Faq; 
   
class FaqController extends BaseController
{
    
    
    public function advertiser_faq(Request $request)
    { 
       
        $advertiser_faq = Faq::where('status', '1')
        ->where('faq_type', 'Advertiser')
        ->get();

        if (is_null($advertiser_faq)) {
            return $this->sendError('Faqs not found.');
        }
   
        return $this->sendResponse($advertiser_faq, 'Advertiser FAQs retrieved successfully.'); 
    }




    public function publisher_faq(Request $request)
    { 
       
        $publisher_faq = Faq::where('status', '1')
        ->where('faq_type', 'Publisher')
        ->get();

        if (is_null($publisher_faq)) {
            return $this->sendError('Faqs not found.');
        }
   
        return $this->sendResponse($publisher_faq, 'Publisher FAQs retrieved successfully.'); 
    }




    public function survey_faq(Request $request)
    { 
       
        $survey_faq = Faq::where('status', '1')
        ->where('faq_type', 'Survey')
        ->get();

        if (is_null($survey_faq)) {
            return $this->sendError('Faqs not found.');
        }
   
        return $this->sendResponse($survey_faq, 'Survey FAQs retrieved successfully.'); 
    }
    
  
}