@extends('backend.layouts.master')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <!-- Create Permission Form -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Create New Grade</h4>
                                <form action="{{ route('grades.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Grade Name</label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="name" class="form-label">Grade Image</label>
                                        <input type="file" class="form-control" id="image" name="image" required>
                                    </div>
                                    {{-- <div class="mb-3">
                                        <label for="name" class="form-label">Choose Category</label>
                                        <select name="cat_id" id="" class="select2">
                                            <option value="">Choose Criculum Category</option>
                                            @foreach (App\Models\MainCategory::select('id','name')->get() as $item)
                                            <option value="{{ $item->id }}"> {{ $item->name }}</option>                                                
                                            @endforeach

                                        </select>
                                    </div> --}}


                                    <button type="submit" class="btn btn-primary">Create Grade</button>

                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Permissions Table -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">All Grade</h4>
                                <table class="table table-bordered" id="permissionsTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Grade Name</th>
                                            <th>Grade Image</th>
                                            <th>Grade Categroy</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- SweetAlert2 JS + DataTables -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#permissionsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('grades.index') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'image',
                        name: 'image'
                    },
                    {
                        data: 'Category',
                        name: 'Category'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>
@endsection
