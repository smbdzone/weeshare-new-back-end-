<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
       'name', 
    ];

    // public function states(){
    //     return $this->hasMany(State::class,'country_id');
    // }
    
    // public function cities(){
    //     return $this->hasMany(City::class,'state_id');
    // }

}
