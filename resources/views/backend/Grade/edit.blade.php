@extends('backend.layouts.master')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <!-- Edit Grade Form -->
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Edit Grade</h4>
                            <form action="{{ route('grades.update', $grade->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="name" class="form-label">Grade Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $grade->name) }}" required>
                                </div>

                                {{-- <div class="mb-3">
                                    <label for="category" class="form-label">Category</label>
                                    <select class="form-control select2" id="category" name="cat_id" >
                                        <option value="">Select Category</option>
                                        @foreach(App\Models\MainCategory::select('id','name')->get() as $category)
                                            <option value="{{ $category->id }}" 
                                                {{ $grade->main_cat_id == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div> --}}

                                <div class="mb-3">
                                    <label for="image" class="form-label">Grade Image</label>
                                    <input type="file" class="form-control" id="image" name="image">
                                    @if($grade->image)
                                        <div class="mt-2">
                                            <img src="{{ asset($grade->image) }}" width="100px" height="100px" />
                                        </div>
                                    @endif
                                </div>

                                <button type="submit" class="btn btn-primary">Update Grade</button>
                                <a href="{{ route('grades.index') }}" class="btn btn-secondary">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
