@extends('backend.layouts.master')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                {{-- index page content for gradecategory --}}
                <div class="row">
                    {{-- Left Column: Grade Category Table --}}
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">All Grade Categories</h4>
                                <table class="table table-bordered" id="permissionsTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Category Name</th>
                                            <th>Category Image</th>
                                            <th>Grade Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (App\Models\Gradecategory::select('id', 'name', 'garde_id', 'image')->get() as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td>
                                                    @if ($item->image)
                                                        <img src="{{ asset($item->image) }}" width="50">
                                                    @else
                                                        No Image
                                                    @endif
                                                </td>
                                                <td>{{ optional($item->grade)->name }}</td>
                                                <td>

                                                    <a href="{{ route('gradecategory.edit', $item->id) }}"
                                                        class="btn btn-sm btn-info">Edit</a>
                                                    <form action="{{ route('gradecategory.destroy', $item->id) }}"
                                                        method="POST" id="delete-form-{{ $item->id }}"
                                                        style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" onclick="confirmDelete({{ $item->id }})"
                                                            class="btn btn-sm btn-danger">
                                                            Delete
                                                        </button>
                                                    </form>

                                                </td>


                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column: Create Form --}}
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Create Grade Category</h4>
                                <form action="{{ route('gradecategory.store') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="name" class="form-label">Category Name</label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="grade_id" class="form-label">Select Grade</label>
                                        <select class="form-control" id="grade_id" name="grade_id" required>
                                            <option value="">-- Select Grade --</option>
                                            @foreach (App\Models\Grade::all() as $grade)
                                                <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="image" class="form-label">Category Image</label>
                                        <input type="file" class="form-control" id="image" name="image">
                                    </div>

                                    <button type="submit" class="btn btn-primary">Create</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div> {{-- end row --}}
            </div>
        </div>
    </div>
@endsection
