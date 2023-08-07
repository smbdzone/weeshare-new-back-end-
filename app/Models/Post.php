<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'social_id',	'type',	'media_img', 'media_content', 'post_on', 'country',	'city',	'gender',	'age',	'date',	'status'
    ];

}
