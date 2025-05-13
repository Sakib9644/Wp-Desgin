@extends('backend.layouts.master')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            @if(isset($permission))
                                <h4 class="card-title">Edit Permission</h4>

                                <form action="{{ route('permissions.update', $permission->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-3">
                                        <label for="name" class="form-label">Permission Name</label>
                                        <input type="text" class="form-control" id="name" name="name" value="{{ $permission->name }}" required>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Update Permission</button>
                                    <a href="{{ route('permissions.index') }}" class="btn btn-secondary">Cancel</a>
                                </form>
                            
                            @else
                                <h4 class="card-title">Create New Permission</h4>

                                <form action="{{ route('permissions.store') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Permission Name</label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                        <small class="form-text text-muted">Example: edit-posts, delete-users, etc.</small>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Create Permission</button>
                                    <a href="{{ route('permissions.index') }}" class="btn btn-secondary">Cancel</a>
                                </form>
                            @endif

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
