<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;   
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; 
  


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;


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
        'profile_picture',
        'profile_picture_url'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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

}
