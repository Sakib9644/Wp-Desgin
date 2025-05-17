@extends('backend.layouts.master')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Edit Grade Category</h4>
                            <form action="{{ route('gradecategory.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="name" class="form-label">Category Name</label>
                                    <input type="text" class="form-control" name="name" value="{{ $category->name }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="grade_id" class="form-label">Select Grade</label>
                                    <select name="grade_id" class="form-control" required>
                                        @foreach ($grades as $grade)
                                            <option value="{{ $grade->id }}" {{ $grade->id == $category->garde_id ? 'selected' : '' }}>
                                                {{ $grade->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="image" class="form-label">Category Image</label><br>
                                    @if ($category->image)
                                        <img src="{{ asset($category->image) }}" width="80" class="mb-2">
                                    @endif
                                    <input type="file" class="form-control" name="image">
                                </div>

                                <button type="submit" class="btn btn-primary">Update</button>
                                <a href="{{ route('gradecategory.index') }}" class="btn btn-secondary">Back</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
