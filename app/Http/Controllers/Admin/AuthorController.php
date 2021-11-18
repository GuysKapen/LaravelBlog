<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthorController extends Controller
{
    public function index()
    {
        $authors = User::latest()->authors()->get();
        return view('admin.authors', compact('authors'));
    }

    public function destroy(User $user)
    {
        if (Auth::user()->cannot('delete', $user)) {
            abort(403);
        }

        if ($user->delete()) {
            Toastr::success("User successfully deleted", "Success");
            return redirect()->back();
        }

        Toastr::error("Failed to delete user", "Error");
        return redirect()->back();
    }
}
