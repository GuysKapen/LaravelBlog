<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $posts = $user->posts;
        $popular_posts = $user->posts()
            ->withCount('comments')
            ->withCount('favorite_to_users')
            ->orderBy('view_count', 'desc')
            ->orderBy('comments_count', 'desc')
            ->orderBy('favorite_to_users_count', 'desc')
            ->take(5)
            ->get();

        $total_pending_posts = $posts->where('is_approved', false)->count();
        $category_count = $posts->count('category');
        $tag_count = 2;
        $author_count = 2;
        $active_authors = User::authors();
        $new_authors_today = 2;
        $total_views = $posts->sum('view_count');
        return view('author.dashboard', compact('user', 'posts', 'popular_posts', 'total_pending_posts', 'total_views', 'category_count', 'tag_count', 'author_count', 'new_authors_today', 'active_authors'));
    }
}
