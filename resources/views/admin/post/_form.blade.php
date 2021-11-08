<form
    action="{{ !isset($post) ? route('admin.post.store') :  route('admin.post.update',  $post->id ) }}"
    method="{{ "POST" }}"
    enctype="multipart/form-data">
@isset($post->id)
    @method('PATCH')
@endisset
@csrf
<!-- Vertical Layout | With Floating Label -->
    <div class="row clearfix">
        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        @isset($post->id)
                            UPDATE POST
                        @else
                            ADD NEW POST
                        @endisset
                    </h2>
                </div>

                <div class="body">

                    <div class="form-group form-float">
                        <div class="form-line">
                            <input type="text" id="title" class="form-control" name="title"
                                   value="{{ old('name', isset($post) ? $post->title : "") }}"
                                   required>
                            <label class="form-label">Post title</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Featured image</label>
                        <input type="file" id="image" name="image">
                    </div>

                    <div class="form-group m-t-15">
                        <input type="checkbox" id="publish" class="filled-in" name="status" {{$post->status ? "checked" : ""}}>
                        <label for="publish">Publish</label>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Categories and Tags
                    </h2>
                </div>

                <div class="body">

                    <div class="form-group form-float">
                        <div class="form-line {{$errors->has('categories') ? "focused error" : ""}}">
                            <label for="category">Select categories</label>
                            <select name="categories[]" id="category" class="form-control show-tick"
                                    data-live-search="true" multiple>
                                @foreach($categories as $category)
                                    <option
                                        value="{{$category->id}}" {{$post->categories->map(function($category): int  {return $category->id;})->contains($category->id) ? 'selected' : '' }}>{{$category->name}}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>

                    <div class="form-group form-float">
                        <div class="form-line {{$errors->has('tags') ? "focused error" : ""}}">
                            <label for="category">Select tags</label>
                            <select name="tags[]" id="tag" class="form-control show-tick" data-live-search="true"
                                    multiple>
                                @foreach($tags as $tag)
                                    <option value="{{ $tag->id }}"
                                        {{$post->tags->map(function($tag): int  {return $tag->id;})->contains($tag->id) ? 'selected' : '' }}
                                    >{{ $tag->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <a class="btn btn-danger m-t-15 waves-effect" href="{{ route('admin.category.index') }}">BACK</a>
                    <button type="submit" class="btn btn-primary m-t-15 waves-effect">SUBMIT</button>
                </div>

            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Body
                    </h2>
                </div>

                <div class="body">

                    <textarea name="body" id="tinymce">{{$post->body}}</textarea>

                </div>

            </div>
        </div>
    </div>
</form>
