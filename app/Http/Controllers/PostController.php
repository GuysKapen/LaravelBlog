<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PostController extends Controller
{

    public function index()
    {
        $categories = Category::all();
        $posts = Post::latest()->paginate(6);
        return view('posts', compact('posts', 'categories'));
    }

    public function details($slug)
    {
        $post = Post::where('slug', $slug)->first();
        $randomPosts = Post::all()->random(3);
        $blogKey = "blog_" . $post->id;
        if (!Session::has($blogKey)) {
            $post->increment('view_count');
            Session::put($blogKey, 1);
        }

        return view('post', compact('post', 'randomPosts'));
    }

    public function postsByCategory($slug) {
        $category = Category::where('slug', $slug)->first();
        $posts = $category->posts;
        return view('category', compact('posts', 'category'));
    }

    public function postsByTag($slug) {
        $tag = Tag::where('slug', $slug)->first();
        $posts = $tag->posts;
        return view('tag', compact('posts', 'tag'));
    }
}
