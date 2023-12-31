<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyResult extends Model
{
    use HasFactory;

    protected $fillable = [ 
        'user_id',
        'survey_id',
        'survey_question_id',
        'survey_answer_id', 
    ];
}