<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Post;
use Validator;
use App\Http\Resources\PostResource;
   
class PostController extends BaseController
{
   

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $userID = auth()->user()->id;
        // return $this->sendResponse($userID, 'userID');
  
        $posts = Post::where('user_id', auth()->user()->id)->get(); 
        return $this->sendResponse(PostResource::collection($posts), 'Posts retrieved successfully.');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'user_id' => 'required',
            'social_id' => 'required', 
            'post_type' => 'required', 
            'media_img' => 'required', 
            'media_content' => 'required', 
            'post_on' => 'required', 
            'country' => 'required',  
            'state' => 'required', 
            'city' => 'required', 
            'gender' => 'required', 
            'age' => 'required', 
            'date' => 'required',  
        ]);
 
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $post = Post::create($input);
   
        return $this->sendResponse(new PostResource($post), 'Post created successfully.');
    } 
   
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
  
        if (is_null($post)) {
            return $this->sendError('Post not found.');
        }
   
        return $this->sendResponse(new PostResource($post), 'Post retrieved successfully.');
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'name' => 'required',
            'detail' => 'required'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $post->name = $input['name'];
        $post->detail = $input['detail'];
        $post->save();
   
        return $this->sendResponse(new PostResource($post), 'Post updated successfully.');
    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete(); 
        return $this->sendResponse([], 'Post deleted successfully.');
    }
}