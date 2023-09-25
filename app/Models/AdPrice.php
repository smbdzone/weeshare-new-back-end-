<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'ad_places_id', 
        'ad_timeslots_id', 
        'price', 
        'views', 
        'clicks', 
        'saturday', 
        'sunday', 
        'tuesday', 
        'otherday' 
    ];
}
