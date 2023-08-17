<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'socialmediatype_id', 
        'post_type', 
        'post_media', 
        'post_content', 
        'country_id', 
        'state_id',	
        'city_id',	
        'gender',	
        'age',	
        'schedule_at',	
        'status'
    ];

    public function posts_contents(){
        return $this->hasMany(PostsContent::class,'post_id');
    }

    public function posts_medias(){
        return $this->hasMany(PostsMedia::class,'post_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }


    // public function category() {
    //     return $this->belongsTo('App\Category', 'category_id');
    // }


}
