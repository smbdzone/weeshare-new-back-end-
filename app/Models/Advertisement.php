<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',	
        'ad_position_id',	
        'ad_timeslot_id',	
        'ad_image',	
        'ad_image_url',	
        'ad_link',
        'ad_from_date',
        'ad_to_date',
        'ad_status'	
    ];
}
