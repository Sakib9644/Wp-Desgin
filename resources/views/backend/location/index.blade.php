@extends('backend.layouts.master')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row mb-4">
                    <!-- All Locations Table -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">All Locations</h4>
                                <table class="table table-bordered" id="districts-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Country</th>
                                            <th>District</th>
                                            <th>Assigned Campuses</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Create New District + Campus Section -->
                    <div class="col-md-6">
                        <!-- Create New District -->
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Create New District</h4>
                                <form method="POST" action="{{ route('location.store') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Country</label>
                                        <select name="country_id" id="country_id" class="form-control select2" required>
                                            <option value="" disabled selected>Select Country</option>
                                            @foreach (App\Models\Country::select('id', 'name')->get() as $country)
                                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">District Name</label>
                                        <input type="text" name="name" class="form-control" required>
                                    </div>

                                    <button type="submit" class="btn btn-success">Save</button>
           
                                </form>
                            </div>
                        </div>

                        <!-- Campus List -->
                        <div class="card mt-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h4 class="card-title mb-0">Campus Names</h4>
                                    <a href="{{ route('location.create') }}" class="btn btn-primary">
                                        <i class="fa fa-plus"></i> Create Campus
                                    </a>
                                </div>
                                <table id="campusTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Campus Name</th>
                                            <th>District Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $campuses = App\Models\Campus::select('id', 'name', 'districts_id')
                                                ->with('districts')
                                                ->get();
                                        @endphp
                                        @forelse($campuses as $index => $campus)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $campus->name }}</td>
                                                <td>{{ $campus->districts->name ?? 'N/A' }}</td>
                                                <td>
                                                    <!-- Edit Button -->
                                                    <a class="btn btn-primary btn-sm"
                                                        href={{ route('campus.edit', $campus->id) }}>
                                                        Edit
                                                    </a>

                                                    <!-- Delete Button -->
                                                    <form id="delete-form-{{ $campus->id }}" method="POST"
                                                        action="{{ route('campus.update', $campus->id) }}"
                                                        style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" onclick="confirmDelete({{ $campus->id }})"
                                                            class="btn btn-sm btn-danger">Delete</button>
                                                    </form>


                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">No campus found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Campus Modal -->


            </div>
        </div>
    </div>



@if($campuses->first()){
    <script>
    $(document).ready(function() {
        $('#campusTable').DataTable({
            // Optional: customize table options
            "pageLength": 10,
            "lengthChange": true,
            "ordering": true,
            "info": true
        });
    });
</script>
@endif

}
    {{-- Scripts --}}
    <script>
    $(document).ready(function() {
        // Campus Table Initialization
       
        // Districts DataTable with Server-Side
        $('#districts-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('location.index') }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'Country', name: 'Country' },
                { data: 'name', name: 'name' },
                { data: 'Campus', name: 'Campus' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    });
</script>

@endsection
