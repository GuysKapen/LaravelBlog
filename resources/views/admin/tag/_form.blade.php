<div class="body">
    <form
        action="{{ !isset($tag) ? route('admin.tag.store') :  route('admin.tag.update',  $tag->id ) }}"
        method="{{ "POST" }}">
        @isset($tag->id)
            @method('PATCH')
        @endisset
        @csrf
        <div class="form-group form-float">
            <div class="form-line">
                <input type="text" id="name" class="form-control" name="name"
                       value="{{ old('name', isset($tag) ? $tag->name : "") }}"
                       required>
                <label class="form-label">Tag Name</label>
            </div>
        </div>

        <a class="btn btn-danger m-t-15 waves-effect" href="{{ route('admin.tag.index') }}">BACK</a>
        <button type="submit" class="btn btn-primary m-t-15 waves-effect">SUBMIT</button>
    </form>
</div>
