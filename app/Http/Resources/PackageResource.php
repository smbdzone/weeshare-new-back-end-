<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        return [ 
            'id'	 => $this->id,
            'title'	 => $this->title,
            'sub_title'	 => $this->sub_title,
            'image'	 => $this->image,
            'price'	 => $this->price,
            'expirydays'	 => $this->expirydays,
            'survey_status'	 => $this->survey_status,
            'survey_price'	 => $this->survey_price,
            'unlimited_posts_status'	 => $this->unlimited_posts_status,
            'post_analytics_status'	 => $this->post_analytics_status,
            'ad_status'	 => $this->ad_status,
            'ai_content_status'	 => $this->ai_content_status,
            'share_targetted_status'	 => $this->share_targetted_status,
            'social_media_accounts_status'	 => $this->social_media_accounts_status,
            'social_media_accounts'	 => $this->social_media_accounts,
            'users_status'	 => $this->users_status,
            'users_nmbr'	 => $this->users_nmbr,
            'cancel_status' => $this->cancel_status,
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at->format('d/m/Y'),
        ];
        
    }
}
