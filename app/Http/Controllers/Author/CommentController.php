<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index() {
        $posts = Auth::user()->posts;
        return view('author.comments', compact('posts'));
    }

    public function destroy(Comment $comment) {
        if ($comment->delete()) {
            Toastr::success("Comment successful published", "Success");
            return redirect()->back();
        }

        Toastr::error("Failed to delete comment", "Error");
        return redirect()->back();
    }
}
