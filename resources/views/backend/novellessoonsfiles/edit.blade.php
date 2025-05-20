@extends('backend.layouts.master')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">

                    {{-- Edit Form --}}
                    <div class="col-md-6 offset-md-3">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Edit Novel Lesson File</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('novellessoonsfiles.update', $novellessoonsfiles->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-3">
                                        <label for="lesson_id" class="form-label">Select Lesson</label>
                                        <select name="lesson_id" id="lesson_id" class="form-control" required>
                                            @foreach (App\Models\Novelunitlessons::all() as $item)
                                                <option value="{{ $item->id }}" {{ $novellessoonsfiles->lesson_id == $item->id ? 'selected' : '' }}>
                                                    {{ $item->name }} -
                                                    {{ $item->novel_unit_lists->name }} -
                                                    {{ optional($item->novel_unit_lists->novel)->unit_name ?? 'No Unit Name' }} -
                                                    {{ optional(optional($item->novel_unit_lists->novel)->grades_category)->grade->name ?? 'No Grade' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="type" class="form-label">Type</label>
                                        <select name="type" id="type" class="form-control" required>
                                            <option value="teacher edition" {{ $novellessoonsfiles->type == 'teacher edition' ? 'selected' : '' }}>Teacher Edition</option>
                                            <option value="powerpoint" {{ $novellessoonsfiles->type == 'powerpoint' ? 'selected' : '' }}>PowerPoint</option>
                                            <option value="worksheet" {{ $novellessoonsfiles->type == 'worksheet' ? 'selected' : '' }}>Worksheet</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="image" class="form-label">Replace File (optional)</label>
                                        <input type="file" name="image" accept="*" class="form-control">
                                        
                                        @if ($novellessoonsfiles->file)
                                            <p class="mt-2">
                                                Current File: 
                                                <a href="{{ asset( $novellessoonsfiles->file) }}" target="_blank">View</a>
                                            </p>
                                        @endif
                                    </div>

                                    <button type="submit" class="btn btn-success">Update</button>
                                    <a href="{{ route('novellessoonsfiles.index') }}" class="btn btn-secondary">Back</a>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
