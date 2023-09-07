<?php

   

namespace App\Http\Controllers\API;

   

use App\Models\EmailConfig; 
use App\Models\EmailTemplate;
use App\Models\PasswordResetToken;
use App\Models\VerifyUser;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

use App\Http\Controllers\API\BaseController as BaseController;

use App\Models\User; 

use Illuminate\Support\Facades\Auth;

use Validator;
use Illuminate\Support\Str;
   
use PHPMailer\PHPMailer\PHPMailer; 
use PHPMailer\PHPMailer\Exception;

use Mail;
// use App\Mail\LaraEmail;

class RegisterController extends BaseController

{

    /**

     * Register api

     *

     * @return \Illuminate\Http\Response

     */

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'user_type' => 'required',
            'name' => 'required', 
            'email' => 'required|email|unique:users', 
            'password' => [
                'required',
                Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised()
            ],
            'c_password' => 'required|same:password',

        ]);
        
   

        if($validator->fails()){

            return $this->sendError('Validation Error.', $validator->errors());       

        }

   

        $input = $request->all();

        $input['password'] = bcrypt($input['password']);

        $user = User::create(
            [
                'current_role_id' => $request->user_type,
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,  
            ]
        );

        $user_id = $user->id;


        if($user_id) {
            // $success['token'] =  $user->createToken('MyApp')->plainTextToken;
 
            $roles = [
                $request->user_type
            ];

            $user->assignRole($roles);

            $success['user_type'] =  $user->user_type; 
            $success['name'] =  $user->name;  
            $success['email'] =  $user->email; 
            $success['email_verified_at'] =  $user->email_verified_at; 
            $success['profile_picture'] =  $user->profile_picture; 
       

            $verifyUser = VerifyUser::where(['user_id' => $user_id])->first(['id']);

            if (empty($verifyUser))
            {
                $token  = Str::random(40);
                $verifyUserNewRecord          = new VerifyUser();
                $verifyUserNewRecord->user_id  = $user_id;
                $verifyUserNewRecord->token   = $token;
                $verifyUserNewRecord->save();
            }

            // url($company->companySEOURL .'/'. $seo->seo .'/verify-profile', $token)
            
            $email_config = EmailConfig::where('id', 1)->first();
            $email_content = EmailTemplate::where('type','=', 'registration')->first();
            $subject =  $email_content->subject;
            $tempbody =  $email_content->body;

            $userName = $user->name;
            $verificationLink = url('api/verify-user', $token);


            $search = array('{UserName}', '{VerificationLink}');
            $replace = array($userName, $verificationLink);

            $body = str_replace($search, $replace, $tempbody);


            $mailData = [
                'subject' => $subject,
                'emailAddress' => $user->email,
                'body' => $body,
                'verification_link' => $verificationLink,
            ];

            try {
                Mail::send('emails.register', $mailData, function($messages) use ($user){
                    $messages->to($user->email);
                    $messages->subject('WeeShare Successful Registration');
                });
                // Mail::to($user->email)->send(new LaraEmail($mailData));
                return $this->sendResponse($success, 'User register successfully.');
            } catch (Exception $e) { 
                return $this->sendError("Message could not be sent.");
            }
     
            // require 'PHPMailer/vendor/autoload.php';
            // //Create an instance; passing `true` enables exceptions
            // $mail = new PHPMailer(true);
            // try {

            //     $mail->SMTPDebug = 0;                      //Enable verbose debug output
            //     $mail->isSMTP();                                            //Send using SMTP
            //     $mail->Host       = $email_config->smtp_host;
            //     //Set the SMTP server to send through
            //     $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            //     $mail->Username   = $email_config->smtp_username;
            //     //SMTP username
            //     $mail->Password   = $email_config->smtp_password;                           //SMTP password
            //     $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption

            //     $mail->Port       = $email_config->smtp_port;                                     //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            //     //Recipients
            //     $mail->setFrom($email_config->from_address, $email_config->from_name);
            //     $mail->addAddress($user->email);
    
    
            //     //Content
            //     $mail->isHTML(true);                                  //Set email format to HTML
            //     $mail->Subject = $subject;
            //     $mail->Body = $body; 
            //     $mail->send();

            //     return $this->sendResponse($success, 'Thank you for registration, Please verify your email to continue.');
                
            // } catch (Exception $e) { 
            //     return $this->sendError("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
            // }
    

            // return $this->sendResponse($success, 'User register successfully.');

        } else {
            return $this->sendError('Something went wrong with the registration, please try agian.');
        }

        

    }

   

    /**

     * Login api

     *

     * @return \Illuminate\Http\Response

     */

    public function login(Request $request) 
    {


        $validator = Validator::make($request->all(), [

            'user_type' => 'required', 
            // 'current_role_id' => 'required', 
            'email' => 'required|email', 
            'password' => [
                'required',
                Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised()
            ], 

        ]);
        
   

        if($validator->fails()){ 
            return $this->sendError('Validation Error.', $validator->errors());      
        }


        // $check_user = User::where('email', $request->email)
        // // ->where('password', bcrypt($request->password))
        // ->where('status', '1')->where('twofa_status', '1') 
        // ->exists();

        // // return $this->sendResponse($check_user, 'Pin Code sent successfully.');

        // if($check_user) {

        //     $email_content = EmailTemplate::where('type','=', '2fa_enabled')->first();
        //     $subject =  $email_content->subject;
        //     $tempbody =  $email_content->body;

        //     $userName = $check_user->name;
        //     $twofaCode = random_int(100000, 999999); 

        //     $search = array('{UserName}', '{twofaCode}');
        //     $replace = array($userName, $twofaCode);
 

        //     $body = str_replace($search, $replace, $tempbody);


        //     $mailData = [
        //         'subject' => $subject,
        //         'emailAddress' => $check_user->email,
        //         'body' => $body,
        //         'twofaCode' => $twofaCode,
        //     ];

        //     try {
        //         Mail::send('emails.twofa', $mailData, function($messages) use ($check_user){
        //             $messages->to($check_user->email);
        //             $messages->subject('PIN CODE');
        //         });
        //         // Mail::to($user->email)->send(new LaraEmail($mailData));
        //         return $this->sendResponse('pincode', 'Pin Code sent successfully.');
        //     } catch (Exception $e) { 
        //         return $this->sendError("Message could not be sent.");
        //     }
        // }
        
        if(Auth::attempt(
            ['email' => $request->email, 
            'password' => $request->password, 
            'user_type' => $request->user_type, 
            'status' => '1', 
            // 'current_role_id' => $request->current_role_id
            ]
            )){ 

                $user = Auth::user(); 
                $success['session'] = $request->session()->regenerate();
                $success['token'] =  $user->createToken('MyApp')->plainTextToken; 
                $success['user_type'] =  $user->user_type; 
                $success['name'] =  $user->name;  
                $success['email'] =  $user->email; 
                $success['email_verified_at'] =  $user->email_verified_at; 
                $success['phone_no'] =  $user->phone_no; 
                $success['biography'] =  $user->biography; 
                $success['country_id'] =  $user->country_id; 
                $success['state_id'] =  $user->state_id; 
                $success['city_id'] =  $user->city_id; 
                $success['address'] =  $user->address; 
                $success['postcode'] =  $user->postcode; 
                $success['profile_picture'] =  $user->profile_picture;  

            return $this->sendResponse($success, 'User login successfully.');

        } 

        else{ 

            return $this->sendError('Unauthorised.', ['error'=>'Invalid username or password.']);

        } 

        
    }


    public function forgot_password(Request $request)
    {
        // dd($request);
 
        $primaryEmailAddress = $request->email;  
        $token = Str::random(64);

        $exists = User::where('email', $primaryEmailAddress)->exists();
        $success = array();
        if($exists == true) {

            PasswordResetToken::insert([
                'email' => $primaryEmailAddress,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);


            $email_config = EmailConfig::where('id', 1)->first();
            $email_content = EmailTemplate::where('type', '=', 'forgot_password')->first();
            $subject =  $email_content->subject;
            $body =  $email_content->body;

            
            $body_forgot = str_replace('{ResetPasswordLink}', url('reset-password', $token), $body);

            //Load Composer's autoloader
            require 'PHPMailer/vendor/autoload.php';

            //dd($email_config);
            //Create an instance; passing `true` enables exceptions
            $mail = new PHPMailer(true);
            try {
                //Server settings
                $mail->SMTPDebug = 0;                      //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = $email_config->smtp_host;
                //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = $email_config->smtp_username;
                //SMTP username
                $mail->Password   = $email_config->smtp_password;                           //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption

                $mail->Port       = $email_config->smtp_port;                                     //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
                //Recipients
                $mail->setFrom($email_config->from_address, $email_config->from_name);
                $mail->addAddress($primaryEmailAddress);

                //Content
                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->Subject =  $subject;
                $mail->Body =   $body_forgot;

                $send = $mail->send();

                // dd($send);

                return $this->sendResponse($success, 'We have send you an email to reset your password!');
 

                // $company = mh_companies::find(request()->get('companyID'));
                // $seo = mh_journals::find(request()->get('journalID'));
                //return redirect()->route('esubmit-login', [$company->companySEOURL, $seo->seo])->with('message', 'You are successfully registered, please check your email to activate your account.');

            } catch (Exception $e) {
                return $this->sendError('email not sent.',  "Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
                
            }


            // return $this->sendResponse($exists, 'User Exists');

        } else {
            return $this->sendResponse($exists, 'User Doesn\'t Exists');
        }

      
        


        // Mail::send('frontend.email-password', ['company' =>$company,'seo' =>$seo,'token' => $token, ], function($message) use($request){
        //     $message->to($request->email);
        //     $message->subject('Reset Password');
        // });


    }



    public function verify_user($token)
    {

        // dd('i m here');
        $success = array();
        if(!empty($token))
        {
            $token = VerifyUser::select('user_id')->where('token', $token)->first();

            $user_id = $token->user_id;

            if(!empty($user_id))
            {
                // dd($user_id);
                $profile = User::where('id', $user_id)->first();
                $profile->update([ 'status' => '1', 'email_verified_at' => NOW() ]); 

                $primaryEmailAddress = $profile->email;
                $profileName         =   $profile->name;
                //dd($primaryEmailAddress);


                $email_config = EmailConfig::where('id', 1)->first();
                $email_content = EmailTemplate::where('type', 'user_verified')->first();
                $subject =  $email_content->subject;
                $tempbody =  $email_content->body;
                //dd($body);

                $mailData = [
                    'login_link' => url('login')
                ];
                // $loginLink = url('login');

                try {
                    Mail::send('emails.verification', $mailData, function($messages) use ($profile){
                        $messages->to($profile->email);
                        $messages->subject('WeeShare Successful Email Verification');
                    });
                    // Mail::to($user->email)->send(new LaraEmail($mailData));
                    return $this->sendResponse($success, 'User verified successfully.');
                } catch (Exception $e) { 
                    return $this->sendError("Message could not be sent.");
                }

                
                // $search = array('{UserName}', '{LoginLink}');
                // $replace = array($profileName, $loginLink);

                // $body = str_replace($search, $replace, $tempbody);
                
                // //dd($userVerificationEmailTempInfo_msg);
                // //Load Composer's autoloader
                // require 'PHPMailer/vendor/autoload.php';

                // //Create an instance; passing `true` enables exceptions
                // $mail = new PHPMailer(true);
                // try {
                //     //Server settings
                //     $mail->SMTPDebug = 0;                      //Enable verbose debug output
                //     $mail->isSMTP();                                            //Send using SMTP
                //     $mail->Host       = $email_config->smtp_host;
                //     //Set the SMTP server to send through
                //     $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                //     $mail->Username   = $email_config->smtp_username;
                //     //SMTP username
                //     $mail->Password   = $email_config->smtp_password;                           //SMTP password
                //     $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                //     $mail->Port       = $email_config->smtp_port;                              //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                //     //Recipients
                //     $mail->setFrom($email_config->from_address, $email_config->from_name);
                //     $mail->addAddress($primaryEmailAddress);

                //     //Content
                //     $mail->isHTML(true);                                  //Set email format to HTML
                //     $mail->Subject =  $subject;
                //     $mail->Body =   $body;

                //     //dd('success');
                //     $send = $mail->send();


                //     return $this->sendResponse($success, 'Thank you, your email has been verified.');
                
                // } catch (Exception $e) { 
                //     return $this->sendError("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
                // }
        

            }



        }

    }

    public function logout(Request $request) {
        // Auth::user()->tokens()->delete();

        Auth::guard('web')->logout();
        
        Auth::logout();
 
        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return $this->sendResponse('logout', 'You are logged out.'); 
    }

    // public function countries(){
    //     $countries = Country::all();
    //     return $this->sendResponse($countries, 'Countries list');
    // }
    // public function states($countryID){ 
    //     $states = State::where('country_id', $countryID)->get();
    //     return $this->sendResponse($states, 'States list');
    // }
    // public function cities($countryID, $stateID){ 
    //     $cities = City::where('country_id', $countryID)->where('state_id', $stateID)->get();
    //     return $this->sendResponse($cities, 'Cities list');
    // }
    // public function industries(){
    //     $industries = Industry::all();
    //     return $this->sendResponse($industries, 'Industries list');
    // }
  
    // public function social_media_types(){
    //     $medias = SocialMediaType::where('status', '1');
    //     return $this->sendResponse($medias, 'Social Media Type list');
    // }


 
    // public function industries() {
    //    $industries =  $this->industries();
    //    return $this->sendResponse($industries, 'Industries list');
    // }

    // public function countries() {
    //     $countries =  $this->countries();
    //     return $this->sendResponse($countries, 'countries list');
    // }

    // public function states($countryID) {
    //     $states =  $this->states($countryID);
    //     return $this->sendResponse($states, 'states list');
    // }
    
    // public function cities($countryID, $stateID) {
    //     $cities =  $this->cities($countryID, $stateID);
    //     return $this->sendResponse($cities, 'cities list');
    // }

 

}



