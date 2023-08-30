<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [  
        'title',	
        'sub_title',	
        'image',	
        'price',	
        'expirydays',	
        'survey_status',	
        'survey_price',	
        'unlimited_posts_status',	
        'post_analytics_status',	
        'ad_status',	
        'ai_content_status',	
        'share_targetted_status',	
        'social_media_accounts_status',	
        'social_media_accounts',	
        'users_status',	
        'users_nmbr',	
        'cancel_status',
    ];


    public function features(){
        return $this->hasMany(PackageFeature::class,'package_id');
    }


}
