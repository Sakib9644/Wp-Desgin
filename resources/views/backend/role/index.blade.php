@extends('backend.layouts.master')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            {{-- Section 1: Create Role --}}
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Create New Role</h4>
                            <form id="createRoleForm">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Role Name</label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Create Role</button>
                            </form>
                            <div id="createRoleMsg" class="mt-2"></div>
                        </div>
                    </div>
                </div>

                {{-- Section 2: Assign Permissions --}}
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Assign Permissions to Role</h4>
                            <form id="assignPermissionsForm">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Select Role</label>
                                    <select name="role_id" class="form-control" required id="roleDropdown">
                                        <option value="">-- Select Role --</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Select Permissions</label>
                                    <div id="permissionsCheckboxes" class="row">
                                        @foreach ($permissions as $permission)
                                            <div class="col-md-4 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="perm_{{ $permission->id }}">
                                                    <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                        {{ ucfirst(str_replace('_', ' ', $permission->name)) }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success">Assign Permissions</button>
                            </form>
                            <div id="assignPermissionMsg" class="mt-2"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section 3: Yajra Roles Table --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">All Roles & Their Permissions</h4>
                            <table class="table table-bordered" id="rolesTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Role Name</th>
                                        <th>Permissions</th>
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

{{-- DataTables and AJAX Scripts --}}
<script>
    $(document).ready(function() {
        // Load DataTable
        $('#rolesTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('roles.index') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'permissions', name: 'permissions', orderable: false, searchable: false }
            ]
        });

        // Create Role
        $('#createRoleForm').submit(function(e) {
            e.preventDefault();
            let formData = $(this).serialize();
            $.ajax({
                url: "{{ route('roles.store') }}",
                method: "POST",
                data: formData,
                success: function(res) {
                    $('#createRoleMsg').html('<span class="text-success">Role created successfully!</span>');
                    $('#createRoleForm')[0].reset();
                    $('#rolesTable').DataTable().ajax.reload();
                    var newRoleOption = `<option value="${res.role.id}">${res.role.name}</option>`;
                    $('#roleDropdown').append(newRoleOption);
                },
                error: function(err) {
                    $('#createRoleMsg').html('<span class="text-danger">' + err.responseJSON.message + '</span>');
                }
            });
        });

        // Load permissions when role changes
        $('#roleDropdown').on('change', function() {
            var roleId = $(this).val();
            var url = "{{ route('find.permissions', 'id') }}".replace('id', roleId);
            $.ajax({
                url: url,
                method: 'GET',
                success: function(res) {
                    $('.form-check-input').prop('checked', false);
                    res.permissions.forEach(function(perm) {
                        $('input.form-check-input[value="' + perm.name + '"]').prop('checked', true);
                    });
                }
            });
        });

        // Assign Permissions
        $('#assignPermissionsForm').submit(function(e) {
            e.preventDefault();
            if ($('input[name="permissions[]"]:checked').length === 0) {
                $('#assignPermissionMsg').html('<span class="text-danger">Please select at least one permission.</span>');
                return;
            }
            let formData = $(this).serialize();
            $.ajax({
                url: "{{ route('roles.assign.permissions') }}",
                method: "POST",
                data: formData,
                success: function(res) {
                    $('#assignPermissionMsg').html('<span class="text-success">Permissions assigned successfully!</span>');
                    $('#rolesTable').DataTable().ajax.reload();
                },
                error: function(err) {
                    $('#assignPermissionMsg').html('<span class="text-danger">' + err.responseJSON.message + '</span>');
                }
            });
        });
    });
</script>
@endsection
