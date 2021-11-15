<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function add(Post $post) {
        $user = Auth::user();
        $isFavorite = $user->favorite_posts()->where('post_id', $post->id)->count();

        if ($isFavorite == 0) {
            $user->favorite_posts()->attach($post);
            Toastr::success('Add favorite successfully!', 'Succeed');

        } else {
            $user->favorite_posts()->detach($post);
            Toastr::success('Remove post from favorite list successfully!', 'Succeed');

        }
        return redirect()->back();
    }
}
