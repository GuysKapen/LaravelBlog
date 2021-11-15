<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function details($slug) {
        $post = Post::where('slug', $slug)->first();
        $randomPosts = Post::all()->random(3);

        return view('post', compact('post', 'randomPosts'));
    }
}
