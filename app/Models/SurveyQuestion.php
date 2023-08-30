<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model
{
    use HasFactory;

    protected $fillable = [ 
        'survey_id',
        'question',
        'status', 
    ];

    public function answers()
    {
        return $this->hasMany(SurveyAnswer::class, 'question_id');
    }
    
}