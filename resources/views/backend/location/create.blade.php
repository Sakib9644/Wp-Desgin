@extends('backend.layouts.master')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <div class="row mb-4 justify-content-center">
                    <!-- Create Campus Section -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Create New Campus for District</h4>

                                <form method="POST" action="{{ route('update_campus') }}">
                                    @csrf
                                    
                                    
                                   <div class="mb-3">
                                    <label class="form-label">District</label>
                                    <select name="district_id" id="district_id" class="form-control select2" >
                                        <option value="" readonly>Select District</option>
                                        @foreach(App\Models\District::select('id','name')->get() as $country)
                                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                    <div class="mb-3">
                                        <label class="form-label">Campus Name</label>
                                        <input type="text" class="form-control"  name="name" >
                                    </div>

                                    <button type="submit" class="btn btn-success">Save</button>
                                    <a href="{{ route('location.index') }}" class="btn btn-secondary">Cancel</a>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Locations Table Section -->
                </div>
            </div>
        </div>
    </div>
@endsection
