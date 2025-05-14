@extends('backend.layouts.master')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <div class="row">
                    <!-- Create Form -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Assign School Admin to Campus</h4>
                                <form method="POST" action="{{ route('school-campus.store') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="user_id" class="form-label">Select School Admin</label>
                                        <select name="user_id" class="form-control select2">
                                            <option value="" disabled selected>Select School Admin</option>
                                            @foreach ($schoolAdmins as $admin)
                                                <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="country_id" class="form-label">Select Country</label>
                                        <select name="country_id" id="country_id" class="form-control select2">
                                            <option value="" disabled selected>Select Country</option>
                                            @foreach (App\Models\Country::all() as $country)
                                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="district_id" class="form-label">Select District</label>
                                        <select name="district_id" id="district_id" class="form-control select2">
                                            <option value="" disabled selected>Select District</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="campus_id" class="form-label">Select Campus</label>
                                        <select name="campus_id" id="campus_id" class="form-control select2">
                                            <option value="" disabled selected>Select Campus</option>
                                        </select>
                                    </div>


                                    <button type="submit" class="btn btn-success">Assign</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- DataTable -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Assigned School Admins</h4>
                                <table id="datatable" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Campus</th>
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

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#country_id').on('change', function() {
            let country_id = $(this).val();

            $('#district_id').html('<option value="" selected>Loading...</option>');
            $('#campus_id').html('<option value="" selected>Select Campus</option>');

            if (country_id) {
                $.ajax({
                    url: '{{ route('ajax.get-districts', 'id') }}'.replace('id', country_id),
                    method: 'GET',
                    data: {
                        country_id: country_id
                    },
                    success: function(res) {
                        if (res && res.length > 0) {
                            let options = '<option value="" selected>Select District</option>';
                            res.forEach(function(district) {
                                options += '<option value="' + district.id + '">' + district
                                    .name + '</option>';
                            });
                           
                            $('#district_id').html(options);

                        } else {
                            $('#district_id').html(
                                '<option value="" selected>No districts found</option>');
                        }
                    },
                    error: function() {
                        alert("Error loading districts");
                    }
                });
            }
        });


        $('#district_id').on('change', function() {
            let district_id = $(this).val();
            $('#campus_id').html('<option value="" selected>Loading...</option>');

             if (district_id) {
                $.ajax({
                    url: '{{ route('ajax.get-campuses', 'id') }}'.replace('id', country_id),
                    method: 'GET',
                    data: {
                        district_id: district_id
                    },
                    success: function(res) {
                        if (res && res.length > 0) {
                            let options = '<option value="" selected>Select Campus</option>';
                            res.forEach(function(district) {
                                options += '<option value="' + district.id + '">' + district
                                    .name + '</option>';
                            });
                           
                            $('#campus_id').html(options);

                        } else {
                            $('#district_id').html(
                                '<option value="" selected>No Campus found</option>');
                        }
                    },
                    error: function() {
                        alert("Error loading districts");
                    }
                });
            }
        });
    </script>

    <script type="text/javascript">
        $(function() {
            $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('school-campus.index') }}",
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
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'campus',
                        name: 'campus'
                    },

                ]
            });
        });
    </script>
@endsection
