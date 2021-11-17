<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index() {
        $comments = Comment::latest()->get();
        return view('admin.comments', compact('comments'));
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
