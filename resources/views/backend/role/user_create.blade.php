@extends('backend.layouts.master')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <!-- Left: Create Form -->
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Create New User</h4>
                            <form action="{{ route('user.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">User Name</label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Role</label>
                                    <select class="form-control" name="role_name" required>
                                        @foreach (Spatie\Permission\Models\Role::select('name')->get() as $role)
                                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" class="form-control" name="password" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control" name="password_confirmation" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Create User</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Right: DataTable -->
                <div class="col-md-7">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">All Users</h4>
                            <table class="table table-bordered" id="userTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody></tbody> <!-- DataTables handles this -->
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Editing User -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editUserForm" method="POST" action="">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">User Name</label>
                        <input type="text" class="form-control" id="editName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" id="editEmail" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select class="form-control" id="editRole" name="role_name" required>
                            @foreach (Spatie\Permission\Models\Role::select('name')->get() as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(function () {
    var table = $('#userTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("users.create") }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'role', name: 'role' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ]
    });

    // Handle the edit button click event
    $(document).on('click', '.edit-btn', function () {
        var userId = $(this).data('id');
        
        // Make an AJAX request to get user data
        $.ajax({
            url: '/users/' + userId + '/edit', // You need to define this route in your web.php
            method: 'GET',
            success: function(data) {
                // Fill modal fields with the user data
                $('#editName').val(data.name);
                $('#editEmail').val(data.email);
                $('#editRole').val(data.role);

                // Update the form action for the specific user
                $('#editUserForm').attr('action', '/users/' + userId); // Direct route for update

                // Show the modal
                $('#editUserModal').modal('show');
            }
        });
    });
});
</script>
@endsection
