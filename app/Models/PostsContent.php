<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostsContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'post_id', 
        'socialmediatype_id', 
        'post_type_id',  
        'post_heading',  
    ];

}
