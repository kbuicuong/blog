<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Post;
use App\Category;
use App\Tag;
use Purifier;
use Image;
use Storage;
use Session;

class PostController extends Controller
{

    //Only authenticated user can access it
    public function __construct(){
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //create a variable and store all the blog posts
        //$posts = Post::all();

        //using pagination to view a limit amount, index column is fastest
        $posts = Post::orderBy('id', 'desc')->paginate(10);

        //return a view and pass in the variable
        return view('posts.index')->withPosts($posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //find all the categories
        $categories = Category::all();
        $tags = Tag::all();

        return view('posts.create')->withCategories($categories)->withTags($tags);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //die and dump (debug tool)
        //dd($request);

        //validate the data
        $this->validate($request, array(

            'title' => 'required|max:255', //check the laravel docs
            'body' => 'required',    //server side validation, javascript validation is better
            'slug' => 'required|alpha_dash|min:5|max:255|unique:posts,slug',
            'category_id' => 'required|integer',
            'featured_image' => 'sometimes|image'
        ));
        //store in the database
        $post = new Post;

        $post->title = $request->title;
        $post->slug = $request->slug;
        $post->category_id = $request->category_id;
        $post->body = Purifier::clean($request->body); 

        //save our image
        if($request->hasFile('featured_image')){
            $image = $request->file('featured_image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $location = public_path('images/' . $filename);
            Image::make($image)->resize(800,400)->save($location);

            $post->image = $filename;
        }

        $post->save();
                                            //whether or not to override the existing association 
        $post->tags()->sync($request->tags, false);

                //put - exist until session is removed
        //Session::flash('key', 'value'); 
        Session::flash('success', 'The blog post was successfully saved!');

        //redirect to another page
        return redirect()->route('posts.show', $post->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //find - kinda like the where command, find primary ids (eloquent)
        $post = Post::find($id);
        return view('posts.show')->withPost($post); //post is the variable you use to access
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // find the post in the database and save it as a variable
        $post = Post::find($id);
        $categories = Category::all();

        $cats = [];
        foreach ($categories as $category) {
            $cats[$category->id] = $category->name;
        }
        
        
        $tags = Tag::all();
        $tags2 = array();
        foreach ($tags as $tag){
            $tags2[$tag->id] = $tag->name;
        }

        //return the view and pass in the variable we previously created
        return view('posts.edit')->withPost($post)->withCategories($cats)->withTags($tags2);

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
        //validate the data
        $post = Post::find($id);
        
        
        $this->validate($request, array(
            'title' => 'required|max:255', //check the laravel docs
            'category_id' => 'required|integer',
            'body' => 'required', //server side validation, javascript validation is better (kinda)
            'slug' => "required|alpha_dash|min:5|max:255|unique:posts,slug, $id",
            'featured_image' => 'image'
        ));
        
        //store the data
        $post->title = $request->input('title');
        $post->slug = $request->input('slug');
        $post->category_id = $request->input('category_id');
        //$request->body is the same as input below
        $post->body = Purifier::clean($request->input('body'));

        if($request->hasFile('featured_image')){
            //add the new image
            $image = $request->file('featured_image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $location = public_path('images/' . $filename);
            Image::make($image)->resize(800,400)->save($location);
            $oldFilename = $post->image;
            //update db to reflect 
            $post->image = $filename;
            //Delete the old image
            Storage::delete($oldFilename);
        }

        $post->save();

        if(isset($request->tags)){
            //default is true, no need to add it in after
            $post->tags()->sync($request->tags, true);
        }else{
            $post->tags()->sync(array());
        }

        //set flash data with success message
        Session::flash('success', 'This post was successfully saved.');

        //redirect with flash data to posts.show
        return redirect()->route('posts.show', $post->id);
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
        //remove any references of tags from post
        $post->tags()->detach();
        //delete the image
        Storage::delete('$post->image');

        $post->delete();

        Session::flash('success', 'The post was successfully deleted.');

        return redirect()->route('posts.index');
    }
}
