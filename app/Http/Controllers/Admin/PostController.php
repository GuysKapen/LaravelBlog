<?php /** @noinspection DuplicatedCode */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Subscriber;
use App\Models\Tag;
use App\Notifications\AuthorPostApproved;
use App\Notifications\NewPostNotify;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::latest()->get();
        return view('admin.post.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        $tags = Tag::all();
        $categories = Category::all();
        return view('admin.post.create', compact('tags', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            "title" => "required",
            "image" => "required",
            "body" => "required",
            "categories" => "required",
            "tags" => "required"
        ]);

        $image = $request->file('image');
        $slug = Str::slug($request->title);
        if (isset($image)) {
            $currentDate = Carbon::now()->toDateString();
            $imageName = $slug . '-' . $currentDate . '-' . uniqid() . '.' . $image->getClientOriginalExtension();

            if (!Storage::disk('public')->exists("post")) {
                Storage::disk("public")->makeDirectory("post");
            }

            Storage::disk("public")->put("post/" . $imageName, file_get_contents($image));
        } else {
            $imageName = "default.png";
        }

        $post = new Post();
        $post->title = $request->title;
        $post->slug = $slug;
        $post->image = $imageName;
        $post->body = $request->body;
        $post->user_id = Auth::id();
        $post->is_approved = true;
        $post->status = $request->status ?? false;

        if ($post->save()) {
            $post->categories()->attach($request->categories);
            $post->tags()->attach($request->tags);
            Toastr::success('Save post successfully', 'Succeed');

            $subscribers = Subscriber::all();
            foreach ($subscribers as $subscriber) {
                Notification::route('mail', $subscriber->email)
                    ->notify(new NewPostNotify($post));
            }

            return redirect()->route('admin.post.index');
        } else {
            Toastr::error('Error saving post', 'Failed');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Post $post
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return view('admin.post.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Post $post
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $tags = Tag::all();
        $categories = Category::all();
        return view('admin.post.edit', compact('post', 'tags', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Post $post
     * @return RedirectResponse
     */
    public function update(Request $request, Post $post)
    {
        $this->validate($request, [
            "title" => "required",
            "image" => "required",
            "body" => "required",
            "categories" => "required",
            "tags" => "required"
        ]);

        $image = $request->file('image');
        $slug = Str::slug($request->title);
        if (isset($image)) {
            $currentDate = Carbon::now()->toDateString();
            $imageName = $slug . '-' . $currentDate . '-' . uniqid() . '.' . $image->getClientOriginalExtension();

            if (!Storage::disk('public')->exists("post")) {
                Storage::disk("public")->makeDirectory("post");
            }

            if (!Storage::disk("public")->exists("post/" . $post->image)) {
                Storage::disk("public")->delete("post/" . $post->image);
            }

            Storage::disk("public")->put("post/" . $imageName, file_get_contents($image));
        } else {
            $imageName = $post->image;
        }

        $post->title = $request->title;
        $post->slug = $slug;
        $post->image = $imageName;
        $post->body = $request->body;
        $post->user_id = Auth::id();
        $post->status = $request->status ?? false;

        if ($post->save()) {
            $post->categories()->attach($request->categories);
            $post->tags()->attach($request->tags);
            Toastr::success('Save post successfully', 'Succeed');
            return redirect()->route('admin.post.index');
        } else {
            Toastr::error('Error saving post', 'Failed');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Post $post
     * @return RedirectResponse
     */
    public function destroy(Post $post)
    {
        if (Storage::disk("public")->exists("post/" . $post->image)) {
            Storage::disk("public")->delete("post/" . $post->image);
        }

        if ($post->delete()) {
            $post->categories()->detach();
            $post->tags()->detach();
            Toastr::success('Delete post successfully', 'Succeed');
            return redirect()->back();
        }

        Toastr::error('Failed to delete post', 'Failed');
        return redirect()->back();
    }

    public function pending()
    {
        $posts = Post::where('is_approved', false)->get();
        return view('admin.post.pending', compact('posts'));
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function approve($id): RedirectResponse
    {
        $post = Post::find($id);
        if ($post->is_approved == false) {
            $post->is_approved = true;
            $post->save();
            Toastr::success('Post approved successfully', 'Succeed');
            $post->user->notify(new AuthorPostApproved($post));

            $subscribers = Subscriber::all();
            foreach ($subscribers as $subscriber) {
                Notification::route('mail', $subscriber->email)
                    ->notify(new NewPostNotify($post));
            }
        }

        return redirect()->back();
    }
}
