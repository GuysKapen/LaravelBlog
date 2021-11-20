<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
        $category_count = Category::all()->count();
        $tag_count = Tag::all()->count();
        $author_count = User::authors()->count();
        $active_authors = User::authors()
            ->withCount('posts')
            ->withCount('comments')
            ->withCount('favorite_posts')
            ->orderBy('posts_count', 'desc')
            ->orderBy('comments_count', 'desc')
            ->orderBy('favorite_posts_count', 'desc')
            ->take(10)
            ->get();
        $new_authors_today = User::authors()->whereDate('created_at', Carbon::today())->count();
        $total_views = $posts->sum('view_count');
        return view('admin.dashboard', compact('user', 'posts', 'popular_posts', 'total_pending_posts', 'total_views', 'category_count', 'tag_count', 'author_count', 'new_authors_today', 'active_authors'));    }
}
