@extends('backend.layouts.master')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <div class="row d-flex justify-content-center">
                    <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Edit Novel Unit</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('novel.update', $novel->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="grade_category_id" class="form-label">Grade Category</label>
                                    <select name="grade_category_id" id="grade_category_id" class="form-control" required>
                                        @foreach (App\Models\Gradecategory::with('grade')->where('name', 'Novel Unit')->get() as $item)
                                            <option value="{{ $item->id }}"
                                                {{ $novel->grade_category_id == $item->id ? 'selected' : '' }}>
                                                {{ $item->name }} ({{ $item->grade->name ?? 'No Grade' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="unit_name" class="form-label">Novel Unit</label>
                                    <input type="text" name="unit_name" id="unit_name" class="form-control"
                                        value="{{ old('unit_name', $novel->unit_name) }}" required>
                                </div>

                                <button type="submit" class="btn btn-primary">Update</button>
                                <a href="{{ route('novel.index') }}" class="btn btn-secondary">Back</a>
                            </form>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
@endsection
