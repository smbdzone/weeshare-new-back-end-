<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Post;
use App\Models\PostsContent;
use App\Models\PostsMedia;
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
        $userID = auth()->user()->id;
        // return $this->sendResponse($userID, 'userID');
   
        if($userID != $request->user()->id) {
            return $this->sendError('Validation Error.', 'User is not the same');     
        }  


        $posts = Post::where('user_id', $userID)
        ->with('posts_contents')
        // ->with('posts_medias')
        ->latest()
        ->get();


        // $posts = Post::where('user_id', $userID )
        // // ->join('social_media_types', 'posts.socialmediatype_id', 'social_media_types.id')
        // ->join('posts_contents', 'posts.id', 'posts_contents.post_id')
        // ->join('countries', 'posts.country_id', 'countries.id')
        // ->join('states', 'posts.state_id', 'states.id')
        // ->join('cities', 'posts.city_id', 'cities.id')
        // ->select(
        //     'posts.*',
        //     'countries.name as country_name',   
        //     'states.name as state_name',   
        //     'cities.name as city_name',     
        //     'posts_contents.post_heading as post_heading',     
        // )
        // // ->groupBy('')
        // ->get(); 

        return $this->sendResponse($posts, 'Posts retrieved successfully.');
        // return $this->sendResponse(PostResource::collection($posts), 'Posts retrieved successfully.');

        
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
        // return $this->sendResponse($input, 'Post created successfully.'); 

        $user = $request->user(); 

        $validator = Validator::make($input, [ 
            'social_id' => 'required', 
            'post_type_id' => 'required', 
            'post_heading' => 'required',  
            'schedule_at' => 'required', 
            'country' => 'required',  
            'state' => 'required', 
            'city' => 'required', 
            'gender' => 'required', 
            'age' => 'required',  
        ]);
 
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
    

        $social_id = $input['social_id'];
        $post_type = $input['post_type_id'];
        $post_heading = $input['post_heading']; 


        $post = Post::create([
            'user_id' =>  $user->id,  
            'country_id' => $input['country'], 
            'state_id' => $input['state'], 
            'city_id' => $input['city'], 
            'gender' => $input['gender'], 
            'age' => $input['age'],  
            'schedule_at' => $input['schedule_at'],
            'status' => 1  
        ]);

        $post_id = $post->id;

        // $output = array(); 

        for ($i=0; $i < count($social_id); $i++) {    
            $post = PostsContent::create([ 
                'post_id' =>  $post_id, 
                'socialmediatype_id' => $social_id[$i], 
                'post_type_id' => $post_type[$i], 
                'post_heading' => $post_heading[$i],  
            ]);
        }
        



        
   
        return $this->sendResponse('Post', 'Post created successfully.');

    } 
   
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        // $post = Post::find($id);
        // ->where('user_id', $request->user()->id);
  

        $post = Post::where('user_id', $request->user()->id)
        ->where('id', $id)
        ->with('posts_contents') 
        ->get();


        if (is_null($post)) {
            return $this->sendError('Post not found.');
        }
   
        return $this->sendResponse($post, 'Post retrieved successfully.');
        // return $this->sendResponse(new PostResource($post), 'Post retrieved successfully.');
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post, $post_id)
    {

        // return $this->sendError('Unauthorized', $request->user_id.'-'.auth()->user()->id); 

        if ($request->user_id != auth()->user()->id) {
            // return response()->json('Unauthorized', 401);
            return $this->sendError('Unauthorized', $request->user_id.'-'.auth()->user()->id); 
        }

        $input = $request->all();
        // return $this->sendResponse($input, 'Post created successfully.'); 

        // $user = $request->user(); 

        $validator = Validator::make($input, [ 
            'social_id' => 'required', 
            'post_type_id' => 'required', 
            'post_heading' => 'required',  
            'schedule_at' => 'required', 
            'country' => 'required',  
            'state' => 'required', 
            'city' => 'required', 
            'gender' => 'required', 
            'age' => 'required',  
        ]);
 
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
    

        $social_id = $input['social_id'];
        $post_type = $input['post_type_id'];
        $post_heading = $input['post_heading']; 

        $post = Post::where('user_id', auth()->user()->id)
        ->where('id', $post_id)
        ->update(
            [ 
                'country_id' => $input['country'], 
                'state_id' => $input['state'], 
                'city_id' => $input['city'], 
                'gender' => $input['gender'], 
                'age' => $input['age'],  
                'schedule_at' => $input['schedule_at'], 
            ]
        ); 

        // return $this->sendResponse('Post',$post);

        // $output = array(); 

        $post = Post::find($post_id);
        $post->posts_contents()->delete();

        for ($i=0; $i < count($social_id); $i++) {
            // $postContent = PostsContent::where('post_id', $post_id)
            // ->where('id', $post_content_id)
            // ->update(
            //     [
            //         'socialmediatype_id' => $social_id[$i], 
            //         'post_type_id' => $post_type[$i], 
            //         'post_heading' => $post_heading[$i], 
            //     ]
            // ); 
            $post = PostsContent::create([ 
                'post_id' =>  $post_id, 
                'socialmediatype_id' => $social_id[$i], 
                'post_type_id' => $post_type[$i], 
                'post_heading' => $post_heading[$i],  
            ]);
        }
        
   
        return $this->sendResponse('Post', 'Post updated successfully.');


        // $input = $request->all();
   
        // // return $this->sendResponse($input, 'Post ');

        // $validator = Validator::make($input, [
        //     'name' => 'required',
        //     'detail' => 'required'
        // ]);
   
        // if($validator->fails()){
        //     return $this->sendError('Validation Error.', $validator->errors());       
        // }
   
        // $post->name = $input['name'];
        // $post->detail = $input['detail'];
        // $post->save();
   
        // return $this->sendResponse(new PostResource($post), 'Post updated successfully.');
    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {


        $post = Post::find($id);
        $post->posts_contents()->delete(); // See below
        // $post->posts_medias()->delete(); // See below
        $post->delete();


        return $this->sendResponse($post, 'Post deleted successfully.');
        // $post->delete(); 
        // return $this->sendResponse([], 'Post deleted successfully.');
    }
}