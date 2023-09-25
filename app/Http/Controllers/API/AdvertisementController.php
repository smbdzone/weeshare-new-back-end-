<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use Validator; 
use App\Models\User;
use App\Models\Advertisement;
use App\Models\AdPrice;
use App\Models\Transaction;
use Carbon\Carbon;


class AdvertisementController extends BaseController
{
    
    public function index() {
        $ads = [];

        
        return $this->sendResponse($ads, 'Advertisements'); 
    }


    public function add_advertisement(Request $request) {

        $validator = Validator::make($request->all(), [ 
            'ad_image' => 'required',
            'ad_link' => 'required', 
            'ad_position' => 'required', 
            'ad_timeslot' => 'required',  
            'ad_from_date' => 'required',  
            'ad_to_date' => 'required',  
        ]);  

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        } 

        $ad_position = $request->ad_position;
        $ad_timeslot = $request->ad_timeslot;

        $extension = request()->file('ad_image')->extension();

        // return $this->sendError($extension, 'only these extensions are allowed.'); 

        $extension_array = array('jpg', 'jpeg', 'png', 'webp', 'gif');

        if (!in_array($extension, $extension_array)) {
            return $this->sendError('jpg, jpeg, png, webp, gif', 'only these extensions are allowed.'); 
        }


        $getuser = $request->user();
        $user = User::where('id', $getuser->id)->latest()->first();    
        if (is_null($user)) {
            return $this->sendError('Profile not found.');
        }  

        if (request()->file('ad_image')) {

            $filename = rand() . '_' . $request->file('ad_image')->getClientOriginalName();
            $request->file('ad_image')->storeAs('advertisements', $filename, 'public');
        
            $image_url = asset('storage/advertisements/'. $filename);
            
            
        } 

      
        $advertisement = Advertisement::create([
            'user_id' =>  $user->id,  
            'ad_position_id' => $ad_position, 
            'ad_timeslot_id' => $ad_timeslot, 
            'ad_image' => $filename,
            'ad_image_url' => $image_url,
            'ad_link' => $request->ad_link,  
            'ad_from_date' => $request->ad_from_date,  
            'ad_to_date' => $request->ad_to_date,  
            'ad_status' => '0',   
        ]); 

        $advertisement_id = $advertisement->id;

        $data = [
            'advertisement_id' => $advertisement_id,
            // 'transaction_id' => $transaction_id,
        ];

        return $this->sendResponse($data, 'Advertisement added successfully.');

    }


    public function add_balance(Request $request){

        $getuser = $request->user();
        $user = User::where('id', $getuser->id)->latest()->first();    
        if (is_null($user)) {
            return $this->sendError('Profile not found.');
        }  

        $validator = Validator::make($request->all(), [ 
            'amount' => 'required|numeric|min:1',
        ]);  

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        } 
        else 
        {
            $amount = $request->amount;
            // $account_type_id = $request->account_type;

            $transaction = Transaction::create([
                'user_id' =>  $user->id,  
                'account_type_id' =>  '1', // adding balance to credit  
                'advertisement_id' => '0', 
                'amount' => $amount, 
                'payment_status' => '1'
            ]); 
    
            $transaction_id = $transaction->id;

            $data = ['transaction_id' => $transaction_id];

            return $this->sendResponse($data, 'Balance added to the account.');
            
        }



    }


    

    public function getAvailableBalance(Request $request)
    {
        $getuser = $request->user();
        $user = User::where('id', $getuser->id)->latest()->first();    
        if (is_null($user)) {
            return $this->sendError('Profile not found.');
        }  

        $transactions = Transaction::where('user_id', $user->id)->get();

        $balance = 0;

        foreach ($transactions as $transaction) {
            $balance += $transaction->credit - $transaction->debit;
        }

        $data = [
            'balance' => $balance
        ];

        return $this->sendResponse($data, 'Available balance');
 
    }

    public function add_transaction(Request $request, $advertisement_id, $transaction_type) {


        $getuser = $request->user();
        $user = User::where('id', $getuser->id)->latest()->first();    
        if (is_null($user)) {
            return $this->sendError('Profile not found.');
        }  



        if($transaction_type == 'view') {
            $account_type_id = '2';
        } else if($transaction_type == 'click') {
            $account_type_id = '3';
        } else {
            return $this->sendError('Validation Error.', 'Account type is missing');  
        }

        if (is_null($advertisement_id) || !$advertisement_id) {
            return $this->sendError('Validation Error.', 'Advertisement ID is missing'); 
        } 

         


        if($advertisement_id > 0) {


            // get ad position

            $ad_details = Advertisement::where('id', $advertisement_id)->first();
            $position_id = $ad_details->ad_position_id;
            $timeslot_id = $ad_details->ad_timeslot_id;
            $ad_user_id = $ad_details->user_id;

            if (is_null($ad_user_id) || !$ad_user_id) {
                return $this->sendError('Validation Error.', 'User ID is missing'); 
            } 

            // get price of the ad position
            $ad_price = AdPrice::where('ad_position_id', $position_id)
            ->where('ad_timeslot_id', $timeslot_id) 
            ->first();

            if(!is_null($ad_price)) {

                $position_price = $ad_price->price;

                if($transaction_type == 'view') 
                {
                    $view_click_price = $ad_price->views;
                } 
                else if($transaction_type == 'click') 
                {
                    $view_click_price = $ad_price->clicks;
                }
               
                $today = Carbon::now();
    
                if ($today->isTuesday()) { 
                    $day_price = $ad_price->tuesday;
                } else if ($today->isSaturday()) { 
                    $day_price = $ad_price->saturday;
                } else if ($today->isSunday()) { 
                    $day_price = $ad_price->sunday;
                } else { 
                    $day_price = $ad_price->otherday;
                }
    
    
                $amount =  $position_price + $view_click_price + $day_price;
    
                $transaction_type = 'debit';

                $transaction = Transaction::create([
                    'transaction_type' => $transaction_type,
                    'user_id' =>  $ad_user_id,  
                    'by_user_id' =>  $user->id,  
                    'account_type_id' =>  $account_type_id,  
                    'advertisement_id' => $advertisement_id, 
                    'credit' => '0', 
                    'debit' => $amount, 
                    'payment_status' => '1'
                ]); 
        
                $transaction_id = $transaction->id;
    
                $data = [ 
                    'transaction_id' => $transaction->id,
                    'account_type_id' => $account_type_id,
                    'advertisement_id' => $advertisement_id,
                    'amount' => $amount,
                ];
    
                return $this->sendResponse($data, 'transaction added successfully.');
            } else {
                return $this->sendError('transaction failed.');
            }
            

        }

    }

    public function ad_details(Request $request) {

    }
}
