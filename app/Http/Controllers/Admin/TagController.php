<?php /** @noinspection PhpUndefinedMethodInspection */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $tags = Tag::latest()->get();
        return view('admin.tag.index', compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|\Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.tag.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return Application|\Illuminate\Contracts\Routing\ResponseFactory|RedirectResponse|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required'
            ]);
        } catch (ValidationException $e) {
            return response('Invalid request', 404);
        }

        $tag = new Tag();
        $tag->name = $request->name;
        $tag->slug = Str::slug($request->name);

        if ($tag->save()) {
            Toastr::success('Save tag successfully', 'Succeed');
            return redirect()->route('admin.tag.index');
        } else {
            Toastr::warning('Failed to save tag', 'Failed');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View|\Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tag = Tag::find($id);
        return view('admin.tag.edit', compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return Application|\Illuminate\Contracts\Routing\ResponseFactory|RedirectResponse|\Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $this->validate($request, [
                'name' => 'required'
            ]);
        } catch (ValidationException $e) {
            return response('Invalid request', 404);
        }

        $tag = Tag::find($id);
        if ($tag == null) {
            return response('Invalid request', 404);
        }

        $tag->name = $request->name;
        $tag->slug = Str::slug($request->name);

        if ($tag->save()) {
            Toastr::success('Update tag successfully', 'Succeed');
            return redirect()->route('admin.tag.index');
        } else {
            Toastr::warning('Failed to update tag', 'Failed');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        if (Tag::find($id)->delete()) {
            Toastr::success('Delete tag successfully', 'Succeed');
        } else {
            Toastr::warning('Failed to delete tag', 'Failed');
        }
        return redirect()->back();
    }
}
