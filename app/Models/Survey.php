<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = [ 
        'user_id',
        'survey_topic',
        'start_date',
        'end_date',
        'status' 
    ];




    // public function surveyOwner(): HasOneThrough
    // {
    //     return $this->hasOneThrough(SurveyQuestion::class, SurveyAnswer::class);
    // }


    public function surveyOwner(): HasOneThrough
    {
        return $this->hasOneThrough(
            SurveyQuestion::class,
            SurveyAnswer::class,
            'survey_id', // Foreign key on the questions table...
            'questionsss_id', // Foreign key on the answers table...
            'id', // Local key on the question table...
            'id' // Local key on the answers table...
        );
    }


    // public function questions()
    // {
    //     return $this->hasMany(SurveyQuestion::class, 'survey_id');
    // }

    // public function answers()
    // {
    //     return $this->hasMany(SurveyAnswer::class, 'question_id');
    // }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}