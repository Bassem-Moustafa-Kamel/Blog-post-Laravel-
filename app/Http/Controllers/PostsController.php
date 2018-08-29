<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Post;
use DB;

class PostsController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth' , ['except' => ['index' , 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        // $posts = Post::all();
        // return Post::where('title' , 'Post two')->get();
        // $posts = Post::select('SELECT * From posts');
        // $posts = Post::orderBy('title' , 'desc')->get();
        //This is used to get all the titles ordered in an descending order to make the new post comes first

        $posts = Post::orderBy('created_at' , 'desc')->paginate(10);
        //paginate will amke the links of the posts to appear in the index file after the tenth post
        return view('posts.index')->with('posts' , $posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate($request , [
            'title' => 'required',
            'body' => 'required',
            'cover_image' => 'image|nullable|max:1999'
            // image means the data enterd should be image . we choosed 1999 as a lot of apache servers have a default upload size 2MB
        ]);

        //Handle File Upload 
        if ($request->hasFile('cover_image')) {
            //Get the filename with the extension
            $fileNameWithExt = $request->file('cover_image')->getClientOriginalName();

            //Get tthe filename separated
            $fileName = pathinfo($fileNameWithExt , PATHINFO_FILENAME);

            //Get the file extension
            $extension = $request->file('cover_image')->getClientOriginalExtension();

            //Filename to store
            $fileNameToStore = $fileName.'_'.time().'.'.$extension;

            //Upload image
            $path = $request->file('cover_image')->storeAs('public/cover_images' ,$fileNameToStore);
            //The folder will be created in storage/app/ folder

        } else {
            $fileNameToStore = 'noimage.png';
        }

        $post = new Post;
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->user_id = auth()->user()->id;
        $post->cover_image = $fileNameToStore;
        $post->save();

        return redirect('/posts')->with('success' , 'Post Created !!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $post = Post::find($id); //This is used to fetch the posts of each user using the id
        return view('posts.show')->with('post'  , $post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $post = Post::find($id); //This is used to fetch the posts of each user using the id
        if (auth()->user()->id !== $post->user_id) {
            # code...
        return redirect('/posts')->with('error'  , 'Unauthorized page');
        //This is made to make unauthorized users unable to edit posts not created by them using the url
        }
        return view('posts.edit')->with('post'  , $post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $this->validate($request , [
            'title' => 'required',
            'body' => 'required'
        ]);

        if ($request->hasFile('cover_image')) {
            //Get the filename with the extension
            $fileNameWithExt = $request->file('cover_image')->getClientOriginalName();

            //Get tthe filename separated
            $fileName = pathinfo($fileNameWithExt , PATHINFO_FILENAME);

            //Get the file extension
            $extension = $request->file('cover_image')->getClientOriginalExtension();

            //Filename to store
            $fileNameToStore = $fileName.'_'.time().'.'.$extension;

            //Upload image
            $path = $request->file('cover_image')->storeAs('public/cover_images' ,$fileNameToStore);
            //The folder will be created in storage/app/ folder

        }

        $post = Post::find($id);
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        if ($request->hasFile('cover_image')) {
            $post->cover_image = $fileNameToStore;
        }
        $post->save();

        return redirect('/posts')->with('success' , 'Post Updated !!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $post = Post::find($id);
        if (auth()->user()->id !== $post->user_id) {
            # code...
        return redirect('/posts')->with('error'  , 'Unauthorized page');
        //This is made to make unauthorized users unable to delete posts not created by them using the url
        }

        if ($post->cover_image != 'noimage.png') {
            # code...
            Storage::delete('public/cover_image/' .$post->cover_image);
        }

        $post->delete();
        return redirect('/posts')->with('success' , 'Post Deleted !!');
    }
}
