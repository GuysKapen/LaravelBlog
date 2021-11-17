<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $this->validate($request, ['comment' => 'required']);
        $comment = new Comment();
        $comment->user_id = Auth::id();
        $comment->post_id = $post->id;
        $comment->comment = $request->comment;

        if ($comment->save()) {
            Toastr::success("Comment successful published", "Success");
            return redirect()->back();
        }

        Toastr::error("Failed to publish comment", "Failed");
        return redirect()->back();
    }
}
