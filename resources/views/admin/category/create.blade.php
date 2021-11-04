@extends('layouts.backend.app')

@section('title','New category')

@push('css')
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Vertical Layout | With Floating Label -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            ADD NEW CATEGORY
                        </h2>
                    </div>

                    @include('admin.category._form')

                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')

@endpush
