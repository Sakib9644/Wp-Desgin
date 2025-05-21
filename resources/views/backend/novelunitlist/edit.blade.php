@extends('backend.layouts.master')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Edit Novel </h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('novelunitdetails.update', $novelUnit->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="novel_id" class="form-label">Select Unit</label>
                                    <select name="novel_id" id="novel_id" class="form-control select2" required>
                                        @foreach (App\Models\Novel::with('grades_category')->get() as $item)
                                            <option value="{{ $item->id }}" {{ $novelUnit->novel_id == $item->id ? 'selected' : '' }}>
                                                {{ $item->unit_name }} ({{ $item->grades_category->grade->name ?? '' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="name" class="form-label">Novel Unit Name</label>
                                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $novelUnit->name) }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="title" class="form-label">Title</label>
                                    <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $novelUnit->title) }}">
                                </div>

                                <div class="mb-3">
                                    <label for="about" class="form-label">About</label>
                                    <textarea name="about" id="about" class="form-control">{{ old('about', $novelUnit->about) }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea name="description" id="description" class="form-control">{{ old('description', $novelUnit->description) }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="image" class="form-label">Image</label>
                                    @if($novelUnit->image)
                                        <div class="mb-2">
                                            <img src="{{ asset($novelUnit->image) }}" alt="Current Image" width="150" height="150">
                                        </div>
                                    @endif
                                    <input type="file" name="image" id="image" class="form-control" accept="image/*">
                                </div>

                                <button type="submit" class="btn btn-primary">Update</button>
                                <a href="{{ route('novelunitdetails.index') }}" class="btn btn-secondary">Back</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
