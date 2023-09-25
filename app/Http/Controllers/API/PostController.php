<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Post;
use App\Models\PostsContent;
use App\Models\PostsMedia;
use Validator;
use App\Http\Resources\PostResource;
use Carbon\Carbon;


class PostController extends BaseController
{
   

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        //  $this->middleware('permission:post-list|post-create|post-edit|post-delete', ['only' => ['index','show']]);
        //  $this->middleware('permission:post-create', ['only' => ['create','store']]);
        //  $this->middleware('permission:post-edit', ['only' => ['edit','update']]);
        //  $this->middleware('permission:post-delete', ['only' => ['destroy']]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $userID = $request->user()->id;
        // return $this->sendResponse(['userID' => $userID, 'request' => $request], 'userID');
   
        if($userID != $request->user()->id) {
            return $this->sendError('Validation Error.', 'User is not the same');     
        }  

        $startDate = $request->from;
        $endDate = $request->to;

        // $startDate = Carbon::createFromFormat('Y-m-d', $request->from);
        // $endDate = Carbon::createFromFormat('Y-m-d', $request->to);
 

        $posts = Post::where('user_id', $userID)
        ->with('posts_contents')  
        ->with('posts_medias')
        ->whereBetween('created_at', [$startDate, $endDate])
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



    public function upload_video(Request $request) {
        if($request->hasFile('video_file')) {

            $file = $request->file('video_file'); 
            $filename = $file->getClientOriginalName(); 
            $extension = $file->getClientOriginalExtension(); 

           

            $file->storeAs('media', $filename, 'public');  
            $file_url = asset('storage/media/'.$filename);

            
            return $this->sendResponse(['video_link' => $file_url], 'Video Uploaded');
        }

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
        $user = $request->user(); 
        // echo '<pre>';
        // print_r($request->file('photos'));
        // print_r($request->file('photos[]'));

        
        // dd($request->hasFile('photos'));
        // return $this->sendResponse($input, 'input returned');

        $validator = Validator::make($input, [ 
            'social_id' => 'required', 
            'post_type_id' => 'required', 
            'post_heading' => 'required',  
            // 'schedule_at' => 'required', 
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

        if($request->hasFile('photos')) {
            $media_status = '1';
        } else {
            $media_status = '0';
        }

        if($input['customize'] == true) {
            $customize_status = '1';
        } else {
            $customize_status = '0';
        }

        $post = Post::create([
            'user_id' =>  $user->id,  
            'countries' => json_encode($input['country']), 
            'states' => json_encode($input['state']), 
            'cities' => json_encode($input['city']), 
            'gender' => $input['gender'], 
            'age' => json_encode($input['age']),  
            'schedule_at' => $input['schedule_at'],
            'status' => 1,
            'media_status' => $media_status,
            'customize_status' => $customize_status  
        ]);

        $post_id = $post->id;

        // $output = array(); 

        // Images and Videos cannot be mixed
        // FB = Post, Reel
        // Insta = Post, Story
        // Tiktok = Reel

        // Post = 'Images'
        // Reel = 'Video', GIF
        // Story = 'Images and Videos'


 

 
        $images_mixed = false;
        $videos_mixed = false;
        $errors = array();
        $array_return = array();
        $array_return['file_urls'] = array();

        if($request->hasFile('photos'))
        {


            // $instagram_extensions = ['jpg','jpeg','png', 'BMP', 'webp', 'MP4', 'MOV' ]; //GIF 
            // $facebook_extensions = ['jpg','jpeg','png', 'webp', 'wmv', 'vob', 'ts', 'tod', 'qt', 'ogv', 'ogm', 'nsv', 'mts', 'mpg', 'mpeg4', 'mpeg', 'mpe', 'mp4', 'mov', 'mod', 'mkv', 'm4v', 'm2ts', 'gif', 'flv', 'f4v', 'dv', 'divx', 'dat', 'avi', 'asf', '3gpp', '3gp',  '3g2'];
            // $tiktok_extensions = ['MP4', 'MPEG', 'MOV', 'AVI'];
            
            // $files_array = array('1.jpg');
            // $allowedfileExtension=['jpg','jpeg','png', 'webp']; 

            $files = $request->file('photos'); 
             

            // $images_extensions = ['jpg','jpeg','png', 'webp'];
            // $videos_extensions = ['wmv', 'vob', 'ts', 'tod', 'qt', 'ogv', 'ogm', 'nsv', 'mts', 'mpg', 'mpeg4', 'mpeg', 'mpe', 'mp4', 'mov', 'mod', 'mkv', 'm4v', 'm2ts', 'gif', 'flv', 'f4v', 'dv', 'divx', 'dat', 'avi', 'asf', '3gpp', '3gp',  '3g2'];

            $checks = array();
            $checks['fb'] = array();
            $checks['insta'] = array();
            $checks['tiktok'] = array();

            $file_urls = array();
            foreach($files as $file){
                // $file->getClientDimentions();
                $filename = $file->getClientOriginalName(); 
                $extension = $file->getClientOriginalExtension(); 


                $file->storeAs('media', $filename, 'public');  
                $file_url = asset('storage/media/'.$filename);
 
                $post_media = PostsMedia::create([
                    'user_id' => $request->user()->id,
                    'post_id' => $post_id,	
                    // 'post_content_id' => $social_id[$i],
                    'post_content_id' => '1',	
                    'file_type' => $extension,	
                    'file_name' => $filename,	
                    'file_url' => $file_url,	 
                ]);

                $file_url = stripslashes($post_media->file_url);
                array_push($file_urls, $file_url );

                // return response()->json(
                //     ['url' => 'https://www.domain.com/api/test'], 200, [], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT
                //     );
  
            }
   

        }


        array_push($array_return['file_urls'], $file_urls);

        return $this->sendResponse($array_return, 'Post Media');

        // for ($i=0; $i < count($social_id); $i++) {    
           

            // echo '<br>i = '. $i; // 0,1,2
            // echo 'social_id = '. $social_id[$i]; // 1,2,3
            // check for facebook
            
            
           
           
           
            // $post_content = PostsContent::create([ 
            //     'post_id' =>  $post_id, 
            //     'socialmediatype_id' => $social_id[$i], 
            //     'post_type_id' => $post_type[$i], 
            //     'post_heading' => $post_heading[$i],  
            // ]);

            // $post_content_id = $post_content->id; 

           

        // }
        
        // if(in_array(['image', 'video'], $errors)) {
        //     return $this->sendError('Images and videos cannot be mixed.'); 
        // }
        
        
        // $files_array = array();
        
        
   
        // return $this->sendResponse('Post', 'Post created successfully.');

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