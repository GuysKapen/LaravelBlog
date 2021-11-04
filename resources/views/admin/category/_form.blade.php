<div class="body">
    <form
        action="{{ !isset($category) ? route('admin.category.store') :  route('admin.category.update',  $category->id ) }}"
        method="{{ "POST" }}"
        enctype="multipart/form-data">
        @isset($category->id)
            @method('PATCH')
        @endisset
        @csrf
        <div class="form-group form-float">
            <div class="form-line">
                <input type="text" id="name" class="form-control" name="name"
                       value="{{ old('name', isset($category) ? $category->name : "") }}"
                       required>
                <label class="form-label">Category Name</label>
            </div>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Select image</label>
            <input type="file" id="image" name="image">
        </div>

        <a class="btn btn-danger m-t-15 waves-effect" href="{{ route('admin.category.index') }}">BACK</a>
        <button type="submit" class="btn btn-primary m-t-15 waves-effect">SUBMIT</button>
    </form>
</div>
