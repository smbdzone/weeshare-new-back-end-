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
        'multiple_answer'
    ];

    // public function answers()
    // {
    //     return $this->hasMany(SurveyAnswer::class, 'question_id');
    // }
    
    public function answers()
    {
        return $this->hasMany(SurveyAnswer::class);
    }

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

}