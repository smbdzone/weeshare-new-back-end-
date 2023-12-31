<?php

namespace App\Http\Controllers\API;


use Crypt;
use Google2FA;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use \ParagonIE\ConstantTime\Base32;

class Google2FAController extends BaseController
{
    use ValidatesRequests;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('sanctum');
    }

    /**
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function enableTwoFactor(Request $request)
    {
        //generate new secret
        $secret = $this->generateSecret();

        //get user
        $user = $request->user();

        //encrypt and then save secret
        $user->google2fa_secret = Crypt::encrypt($secret);
        $user->save();

        //generate image for QR barcode
        $imageDataUri = Google2FA::getQRCodeInline(
            $request->getHttpHost(),
            $user->email,
            $secret,
            200
        );

        return $this->sendResponse(['image' => $imageDataUri,
        'secret' => $secret], 'Advertiser FAQs retrieved successfully.'); 

        // return view('2fa/enableTwoFactor', ['image' => $imageDataUri,
        //     'secret' => $secret]);
    }

    /**
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function disableTwoFactor(Request $request)
    {
        $user = $request->user();

        //make secret column blank
        $user->google2fa_secret = null;
        $user->save();

        return $this->sendResponse(['user_2fa' => $user->google2fa_secret], 'User 2FA Disabled'); 

        // return view('2fa/disableTwoFactor');
    }

    /**
     * Generate a secret key in Base32 format
     *
     * @return string
     */
    private function generateSecret()
    {
        $randomBytes = random_bytes(10);

        return $this->sendResponse(['user_secret' => Base32::encodeUpper($randomBytes) ], 'generateSecret'); 

        // return Base32::encodeUpper($randomBytes) 
    }
}