<?php
   
namespace App\Http\Controllers\API;
   
use App\Models\Survey;
use App\Models\SurveyAnswer;
use App\Models\SurveyQuestion;
use App\Models\SurveyResult;
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
        $userID = $request->user()->id; 
        $surveys = Survey::where('user_id', $userID)->whereNull('deleted_at')->with('questions.answers')->get(); 
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
            'multiple_answer' => $request->multiple_answer,  
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
            'survey_question_id' => 'required',
            'answer' => 'required', 
        ]);  

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        } 

        $survey_id = $request->survey_id;

        $survey_answer = SurveyAnswer::create([
            'survey_id' =>  $survey_id,  
            'survey_question_id' => $request->survey_question_id,  
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
        // return $this->sendResponse($id, 'Survey retrieved successfully.'); 

        $survey = Survey::where('user_id', $request->user()->id)
        ->where('id', $id)->whereNull('deleted_at')->with('questions.answers')
        ->first();


        if (is_null($survey)) {
            return $this->sendError('Survey not found.');
        }
   
        return $this->sendResponse($survey, 'Survey retrieved successfully.'); 
    }
    


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        // return $this->sendResponse($id, 'Survey retrieved successfully.'); 

        $survey = Survey::where('user_id', $request->user()->id)
        ->where('id', $id) 
        ->first(); 

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
    public function destroy($surveyId)
    { 
        // $survey = Survey::find($id); 

        $survey = Survey::with('questions.answers')->find($surveyId);

        if (!$survey) {
            return "Survey not found.";
        }

        // Delete the survey along with its questions and answers
        $survey->delete();
        

        return $this->sendResponse($survey, 'Survey deleted successfully.'); 
    }



    public function survey_list(Request $request){
        $surveys = Survey::where('status', '1')->whereNull('deleted_at')->get(); 
        return $this->sendResponse($surveys, 'Survey retrieved successfully.');  
    }

    public function survey_details(Request $request, $id)
    {
        $survey = Survey::where('status', '1')->where('id', $id)->whereNull('deleted_at')->with('questions.answers')->first();
        if (is_null($survey)) {
            return $this->sendError('Survey not found.');
        }
        return $this->sendResponse($survey, 'Survey retrieved successfully.'); 
    }
    
    public function submit_survey_answer(Request $request){

        $user = $request->user();

        $validator = Validator::make($request->all(), [ 
            'survey_id' => 'required',
            'survey_question_id' => 'required',
            'survey_answer_id' => 'required', 
        ]);  

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        } 

        $survey_id = $request->survey_id;
        $user_id = $user->id;


        $survey_answer = SurveyResult::updateOrCreate(
        
            ['user_id' => $user_id, 'survey_id' =>  $survey_id, 'survey_question_id' => $request->survey_question_id],    
            [
                'user_id' =>  $user_id,  
                'survey_id' =>  $survey_id,  
                'survey_question_id' => $request->survey_question_id,  
                'survey_answer_id' => $request->survey_answer_id,  
                'status' => '1',  
            ]); 

        $survey_answer_id = $survey_answer->id;

        return $this->sendResponse($survey_answer_id, 'Survey answer added successfully.');


    }

}