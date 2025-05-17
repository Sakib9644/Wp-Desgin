@extends('backend.layouts.master')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                {{-- edit page content for course --}}

                <div class="row justify-content-center">
                    <div class="col-md-4  ">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Create Course Syllabus </h4>
                                <form action="{{ route('course.update', $course->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('put')
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Select Grade Category</label>
                                        @php
                                            $courses = App\Models\Gradecategory::select('id', 'name', 'garde_id')
                                                ->where('name', 'Course Syllabus')
                                                ->get();
                                        @endphp

                                        <select name="grade_category" class="form-control select2" required>
                                            <option value="" disabled>
                                                Select a Course Grade</option>
                                            @foreach ($courses as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ $item->id == $course->id ? 'selected' : '' }}>
                                                    {{ $item->name }} ({{ $item->grade->name ?? 'N/A' }})
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>


                                    <div class="mb-3">
                                        <label for="pdf" class="form-label">Upload Course PDF</label>
                                        <input type="file" name="image" class="form-control" accept="application/pdf"
                                            >

                                        @if (!empty($course->file) && file_exists(public_path($course->file)))
                                            <a href="{{ asset($course->file) }}" target="_blank">
                                                <img src="https://cdn-icons-png.flaticon.com/512/337/337946.png"
                                                    alt="PDF" width="50" />
                                            </a>
                                        @else
                                            <p>No PDF uploaded.</p>
                                        @endif
                                    </div>


                                    <button type="submit" class="btn btn-primary">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
