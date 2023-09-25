<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\ReferralCode;
use App\Models\CouponCode;
use Illuminate\Support\Str;

class ReferralController extends BaseController
{
    public function index()
    {
        $user = auth()->user();
        $codes = $user->referralCodes;

        return $this->sendResponse($codes, 'Referral Codes.');

        // return view('referral.index', compact('codes'));
    }

    public function generate(Request $request)
    {
        $user = auth()->user();

        if ($user->referralCodes()->count() < 5) {
            $code = $user->generateReferralCodes();

            ReferralCode::create([
                'code' => $code,
                'user_id' => $user->id,
            ]); 

            // foreach ($codes as $code) { }
            
            return $this->sendResponse($code, 'Referral code generated successfully.'); 
        }
        return $this->sendError('You have already generated 5 referral codes.'); 
    }

    // public function use($code)
    // {
    //     $user = auth()->user();
    //     $referralCode = ReferralCode::where('code', $code)->first();

    //     if ($referralCode && !$referralCode->used && $user->id !== $referralCode->user_id) {
    //         // Apply your logic to handle code usage and provide discounts if needed
    //         $referralCode->update(['used' => true]);
    //         return $this->sendResponse($referralCode, 'Referral code used successfully.');  
    //     }
    //     return $this->sendError('Invalid referral code.');  
    // }



    public static function use( $code, $user_id)
    {
        // $user = auth()->user();
        $referralCode = ReferralCode::where('code', $code)->first();

        // $return = array(
        //     'referralCode'=> $referralCode, 
        //     'used' => $referralCode->used, 
        //     'userID' => $user_id, 
        //     // 'req_user' =>  $request->user(), 
        //     'refcode_userid'=> $referralCode->user_id 
        // );

        // return  $return ;

        if ($referralCode && !$referralCode->used && $user_id !== $referralCode->user_id) {
            // Generate coupon codes for the referring and referred users
            $referringUser = $referralCode->user;
            $referredUser = $user_id;

            // You can generate coupon codes here and associate them with users as needed
            // $referringUserCouponCode = $this->generateCouponCode();
            // $referredUserCouponCode = $this->generateCouponCode();

            $referringUserCouponCode =  strtoupper(Str::random(10));
            $referredUserCouponCode = strtoupper(Str::random(10));

            // Associate the coupon codes with the users
            // $referringUser->couponCodes()->create([
            //     'code' => $referringUserCouponCode,
            //     'discount_percentage' => 20, // Set the discount percentage as needed
            // ]);


            CouponCode::create([
                'code' => $referringUserCouponCode,
                'user_id' => $referringUser->id,
                'discount_percentage' => 20, // Set the discount percentage as needed
            ]);

            CouponCode::create([
                'code' => $referredUserCouponCode,
                'user_id' => $user_id,
                'discount_percentage' => 20, // Set the discount percentage as needed
            ]);


            // $referredUser->couponCodes()->create([
            //     'code' => $referredUserCouponCode,
            //     'discount_percentage' => 20, // Set the discount percentage as needed
            // ]);

            // Mark the referral code as used
            $referralCode->update(['used' => true, 'used_by' => $user_id]);
            return $referralCode;
            // return $this->sendResponse($referralCode, 'Referral code used successfully. Coupon codes generated.');  
            
        }

        $response = [
            'success' => false,
            'message' => 'Invalid referral code.',
        ]; 

        return response()->json($response, 422);

        // return $this->sendError('Invalid referral code.');  
        // return redirect()->route('referral.index')->with('error', 'Invalid referral code.');
    }

    public function generateCouponCode()
    {
        // Generate a unique coupon code here as per your requirements
        return strtoupper(Str::random(10)); // Adjust the code length and format as needed
    }



}
