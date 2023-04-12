<?php

namespace App\Http\Controllers;
use App\Models\Post;
use App\Models\Category;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class BlogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index','show']);
    }

    public function index(Request $request)
    {
        if($request->search)
        {
            $posts = Post::where('title', 'like', '%' . $request->search . '%')
             ->orWhere('body', 'like', '%' . $request->search . '%')->latest()->get();
        }
        else if($request->category){
            $posts = Category::where('name', $request->category)->firstOrFail()->posts();
        }
        else{
            // $posts = Post::all();
            $posts = Post::latest()->get();
        }
        $categories = Category::all();

        return view('blogPosts.blog',compact('posts', 'categories'));
    }

    // public function show($slug)
    // {
    //     $post = Post::where('slug',$slug)->first();
    //     return view('blogPosts.single-post',compact('post'));
    // }

    // using route model binding
    public function show(Post $post)
    {
        return view('blogPosts.single-post',compact('post'));
    }

    public function delete(Post $post)
    {
        $post->delete();
        return redirect()->back()->with('status', 'Post Deleted Successfully');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'image' => 'required | image',
            'body' => 'required',
            'category_id' => 'required'
        ]);
        $title = $request->input('title');
        $category_id = $request->input('category_id');
        $slug = Str::slug($title,'-');
        $user_id = Auth::user()->id;
        $body = $request->input('body');

        // file upload
        $imgePath = 'storage/' . $request->file('image')->store('postsImages', 'public');

        $post = new Post();
        $post->title = $title;
        $post->slug = $slug;
        $post->user_id = $user_id;
        $post->category_id = $category_id;
        $post->body = $body;
        $post->imgePath = $imgePath;

        $post->save();

       return redirect()->back()->with('status', 'Post Created Successfully');
     }

    public function create()
    {
        $categories = Category::all();
        return view('blogPosts.create-post',compact('categories'));
    }

    public function edit(Post $post)
    {
        if(auth()->user()->id !== $post->user->id){
            abort(403);
        }
        return view('blogPosts.edit-post',compact('post'));
    }

    public function update(Request $request ,Post $post)
    {
        if(auth()->user()->id !== $post->user->id){
            abort(403);
        }
        $request->validate([
            'title' => 'required',
            'image' => 'required | image',
            'body' => 'required',
        ]);
        $title = $request->input('title');
        $postId = $post->id;
        $slug = Str::slug($title,'-');
        $body = $request->input('body');

        // file upload
        $imgePath = 'storage/' . $request->file('image')->store('postsImages', 'public');

        $post->title = $title;
        $post->slug = $slug;
        $post->body = $body;
        $post->imgePath = $imgePath;

        $post->save();

       return redirect()->back()->with('status', 'Post Edited Successfully');
    }
}
