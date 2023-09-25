<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;   
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; 
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;



    public $guard_name = 'sanctum';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'current_role_id',
        'user_type',
        'name',
        'email',
        'password', 
        'phone_no',
        'biography',
        'country_id',
        'state_id',
        'city_id',
        'address',
        'postcode', 
        'twofa_status', 
        'status',
        'email_verified_at',
        'profile_picture',
        'profile_picture_url',
        'google2fa_secret'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'google2fa_secret'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ]; 

    public function user_posts(){
        return $this->hasMany(Post::class,'user_id');
    }

    public function currentRole(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'current_role_id');
    }

    /** 
     * Interact with the user's first name.
     *
     * @param  string  $value
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function google2faSecret(): Attribute
    {
        return new Attribute(
            get: fn ($value) =>  decrypt($value),
            set: fn ($value) =>  encrypt($value),
        );
    }


 
    public function generateReferralCodes()
    {
        // $codes = [];

        
        $code = $this->generateUniqueReferralCode();
        // $codes[] = $code;
        // for ($i = 0; $i < 5; $i++) {}

        return $code;
    }

    private function generateUniqueReferralCode()
    {
        $code = Str::random(8); // Adjust the code length as needed

        while (ReferralCode::where('code', $code)->exists()) {
            $code = Str::random(8);
        }

        return $code;
    }

    public function referralCodes()
    {
        return $this->hasMany(ReferralCode::class, 'user_id');
    }
    

    public static function use($code, $user_id)
    {
        // $user = auth()->user();
        $referralCode = ReferralCode::where('code', $code)->first();

        if ($referralCode && !$referralCode->used && $user_id !== $referralCode->user_id) {
            // Generate coupon codes for the referring and referred users
            $referringUser = $referralCode->user;
            $referredUser = $user_id;

            // You can generate coupon codes here and associate them with users as needed
            $referringUserCouponCode =  strtoupper(Str::random(10));
            $referredUserCouponCode =strtoupper(Str::random(10));

            // Associate the coupon codes with the users
            $referringUser->couponCodes()->create([
                'code' => $referringUserCouponCode,
                'discount_percentage' => 20, // Set the discount percentage as needed
            ]);

            $referredUser->couponCodes()->create([
                'code' => $referredUserCouponCode,
                'discount_percentage' => 20, // Set the discount percentage as needed
            ]);

            // Mark the referral code as used
            $referralCode->update(['used' => true, 'used_by' => $user_id]);
            return $this->sendResponse($referralCode, 'Referral code used successfully. Coupon codes generated.');  
            
        }
        return $this->sendError('Invalid referral code.');  
        // return redirect()->route('referral.index')->with('error', 'Invalid referral code.');
    }

    private function generateCouponCode()
    {
        // Generate a unique coupon code here as per your requirements
        return strtoupper(Str::random(10)); // Adjust the code length and format as needed
    }


    public function purchasedPackages()
    {
        return $this->hasMany(ReferralCode::class, 'user_id');
    }

}
