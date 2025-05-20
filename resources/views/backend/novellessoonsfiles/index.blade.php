@extends('backend.layouts.master')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">

                    {{-- Left Column: Create Form --}}
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Add Novel Lesson File</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('novellessoonsfiles.store') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="lesson_id" class="form-label">Select Lesson</label>
                                        <select name="lesson_id" id="lesson_id" class="form-control" required>
                                            @foreach (App\Models\Novelunitlessons::all() as $item)
                                                <option value="{{ $item->id }}">
                                                    {{ $item->name }} -
                                                    {{ $item->novel_unit_lists->name }} -
                                                    {{ optional($item->novel_unit_lists->novel)->unit_name ?? 'No Unit Name' }} -
                                                    {{ optional(optional($item->novel_unit_lists->novel)->grades_category)->grade->name ?? 'No Grade' }}
                                                </option>
                                            @endforeach

                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="type" class="form-label">Type</label>
                                        <select name="type" id="type" class="form-control" required>
                                            <option value="teacher edition">Teacher Edition</option>
                                            <option value="powerpoint">PowerPoint</option>
                                            <option value="worksheet">Worksheet</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="image" class="form-label">Upload File</label>
                                        <input type="file" name="image" accept="*" class="form-control" required>
                                    </div>


                                    <button type="submit" class="btn btn-success">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column: DataTable --}}
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title">Novel Lesson Files List</h4>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped" id="novel-files-table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Lesson</th>
                                            <th>Type</th>
                                            <th>File</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
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
            $('#novel-files-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('novellessoonsfiles.index') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'lesson_name',
                        name: 'lesson.name'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'file',
                        name: 'file'
                    },
                    {
                        data: 'status',
                        name: 'status'
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
