@extends('backend.layouts.master')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <div class="row">
                    <!-- Create Form (left side) -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h4>Create Teacher Grade</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('teachergrades.store') }}" method="POST">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="user_id" class="form-label">Select User</label>
                                        <select name="user_id" id="user_id" class="form-control" required>
                                            <option value="">-- Select User --</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="grade_id" class="form-label">Select Grade(s)</label>
                                        <select name="grade_id[]" id="grade_id" class="form-control select2"
                                            multiple="multiple" required>
                                            @foreach ($grades as $grade)
                                                <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-success">Create</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Index Table (right side) -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h4>Teacher Grades List</h4>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered" id="teacherGradeTable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>User</th>
                                            <th>Grade</th>
                                            <th>Created At</th>
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
        @php
        @endphp
    </div>
    <script>
        $(document).ready(function() {
            $('#user_id').change(function() {
                var userId = $(this).val();
                var url = "{{ route('teachergrades', ':userId') }}".replace(':userId', userId);

                $.ajax({
                    url: url,
                    method: 'GET',
                    data: {
                        user_id: userId
                    },
                    success: function(response) {
                        // Replace options
                        $('#grade_id').html(response);

    $('#grade_id').select2();

                    }
                });
            });


        });
    </script>


    <script>
        $(function() {
            $('#teacherGradeTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('teachergrades.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'user_name', // Ensure this field is available in the response from the controller
                        name: 'user_name' // Corresponds to the alias or column name in the database response
                    },
                    {
                        data: 'grade_name', // Ensure this field is available in the response from the controller
                        name: 'grade_name' // Corresponds to the alias or column name in the database response
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
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
