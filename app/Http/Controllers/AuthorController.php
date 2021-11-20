<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthorController extends Controller
{
    public function profile($email)
    {
        $user = User::where('email', $email)->first();
        if (Auth::user()->cannot('view', $user)) {
            abort(403);
        }
        $posts = $user->posts->approved()->published()->get();

        return view('profile', compact('user', 'posts'));

    }
}
