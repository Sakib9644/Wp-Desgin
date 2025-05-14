@extends('backend.layouts.master')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Create New District</h4>
                        <form method="POST" action="{{ route('location.update',$district->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label">Country</label>
                                <select name="country_id" id="country_id" class="form-control select2" required>
                                    <option value="">Select Country</option>
                                    @foreach (App\Models\Country::select('id', 'name')->get() as $country)
                                        <option value="{{ $country->id }}" {{ $district->country_id == $country->id ? 'selected' : ''   }}>{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">District Name</label>
                                <input type="text" name="name" value="{{ $district->name }}" class="form-control" id="name" required>
                            </div>

                            <button type="submit" class="btn btn-success">Save</button>
                            <a href="{{ route('location.index') }}" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
