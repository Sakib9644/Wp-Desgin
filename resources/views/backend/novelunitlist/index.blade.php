@extends('backend.layouts.master')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <!-- Form Column -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h4>Add Novel Unit Details</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('novelunitdetails.store') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="novel_id" class="form-label">Select Novel Unit</label>
                                        <select name="novel_id" id="novel_id" class="form-control select2" required>
                                            @foreach (App\Models\Novel::with('grades_category')->get() as $item)
                                                <option value="{{ $item->id }}">
                                                    {{ $item->unit_name }} ({{ $item->grades_category->grade->name }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="name" class="form-label"> Name</label>
                                        <input type="text" name="name" id="name" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="title" class="form-label">Title</label>
                                        <input type="text" name="title" id="title" class="form-control">
                                    </div>

                                    <div class="mb-3">
                                        <label for="about" class="form-label">About</label>
                                        <textarea name="about" id="about" class="form-control"></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea name="description" id="description" class="form-control"></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="image" class="form-label">Image</label>
                                        <input type="file" name="image" id="image" class="form-control"
                                            accept="image/*">
                                    </div>

                                    <button type="submit" class="btn btn-success">Save</button>
                                </form>

                            </div>
                        </div>
                    </div>

                    <!-- Table Column -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="mb-0">Novel Unit Details</h4>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered" id="teacherGradeTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Unit Name</th>
                                            <th>Title</th>
                                            <th>About</th>
                                            <th>Description</th>
                                            <th>Image</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- DataTables will populate --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        $(function() {
            $('#teacherGradeTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('novelunitdetails.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'unit_name',
                        name: 'unit_name'
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'about',
                        name: 'about'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'image',
                        name: 'image'
                    },
                    
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        });
    </script>
@endsection
