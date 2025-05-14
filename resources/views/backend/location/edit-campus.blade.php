@extends('backend.layouts.master')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid mt-4">
                <div class="row justify-content-center">
                    <div class="col-md-6">


                        <div class="card">
                            <div class="card-header bg-info text-white">
                                <h4 class="mb-0">Edit Campus</h4>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('campus.update', $campus->id) }}">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-3">
                                        <label for="name" class="form-label">Campus Name</label>
                                        <input type="text" name="name" class="form-control"
                                            value="{{ old('name', $campus->name) }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="districts_id" class="form-label">District</label>
                                        <select name="district_id" class="form-control select2" required>
                                            <option value="">Select District</option>
                                            @foreach (App\Models\District::select('id', 'name')->get() as $district)
                                                <option value="{{ $district->id }}"
                                                    {{ $campus->districts_id == $district->id ? 'selected' : '' }}>
                                                    {{ $district->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <button type="submit" class="btn btn-success">Update Campus</button>
                                    <a href="{{ route('location.index') }}" class="btn btn-secondary">Back</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
