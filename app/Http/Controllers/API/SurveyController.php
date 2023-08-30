<?php
   
namespace App\Http\Controllers\API;
   
use App\Models\Survey;
use App\Models\SurveyAnswer;
use App\Models\SurveyQuestion;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController; 
use Validator; 
   
class SurveyController extends BaseController
{
   

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $userID = auth()->user()->id;
       
        // if($userID != $request->user()->id) {
        //     return $this->sendError('Validation Error.', 'User is not the same');     
        // }   

        // $posts = Survey::where('user_id', $userID)
        // ->with('questions', 'answers')
        // // ->with('answers') 
        // ->latest()
        // ->get(); 


        $surveys = Survey::where('user_id', $userID)->with('surveyOwner')->get();

        // $surveys = Survey::where('user_id', $userID)
        
        // ->latest()
        // ->get();

        // return $this->through('questions')->has('answers');

        // $surveys = Survey::whereBelongsTo('user_id', $userID)->get();


        return $this->sendResponse($surveys, 'Survey retrieved successfully.');  
        
    }


    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [ 
            'survey_topic' => 'required',
            'start_date' => 'required',  
            'end_date' => 'required',  
        ]);  

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        // return $this->sendResponse($request->all(), 'Survey is saved successfully.');


        $userID = auth()->user()->id;

        $survey = Survey::create([
            'user_id' =>  $userID,  
            'survey_topic' => $request->survey_topic, 
            'start_date' => $request->start_date, 
            'end_date' => $request->end_date, 
            'status' => '1',  
        ]); 

        $survey_id = $survey->id;

        return $this->sendResponse($survey_id, 'Survey is saved successfully.');

    } 

    //Survey Questions
    public function survey_question(Request $request)
    {

        $validator = Validator::make($request->all(), [ 
            'survey_id' => 'required',
            'question' => 'required', 
        ]);  

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        } 

        $survey_id = $request->survey_id;

        $survey_question = SurveyQuestion::create([
            'survey_id' =>  $survey_id,  
            'question' => $request->question,  
            'status' => '1',  
        ]); 

        $survey_question_id = $survey_question->id;

        return $this->sendResponse($survey_question_id, 'Survey question added successfully.');

    } 


    // Add Options/Answers to Survey
    public function survey_answer(Request $request)
    {

        $validator = Validator::make($request->all(), [ 
            'survey_id' => 'required',
            'question_id' => 'required',
            'answer' => 'required', 
        ]);  

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        } 

        $survey_id = $request->survey_id;

        $survey_answer = SurveyAnswer::create([
            'survey_id' =>  $survey_id,  
            'question_id' => $request->question_id,  
            'answer' => $request->answer,  
            'status' => '1',  
        ]); 

        $survey_answer_id = $survey_answer->id;

        return $this->sendResponse($survey_answer_id, 'Survey answer added successfully.');

    } 


   
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
         

        $survey = Survey::where('user_id', $request->user()->id)
        ->where('id', $id) 
        ->get();


        if (is_null($survey)) {
            return $this->sendError('Survey not found.');
        }
   
        return $this->sendResponse($survey, 'Survey retrieved successfully.'); 
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Survey $survey, $survey_id)
    {
 
        return $this->sendResponse('Survey', 'Survey updated successfully.'); 
       
    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    { 
        $survey = Survey::find($id); 

        return $this->sendResponse($survey, 'Survey deleted successfully.'); 
    }
}