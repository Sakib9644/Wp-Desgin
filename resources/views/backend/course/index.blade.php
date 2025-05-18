@extends('backend.layouts.master')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <div class="row">


                    {{-- Left: Create Course Form --}}
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Create Course Syllabus </h4>
                                <form action="{{ route('course.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="name" class="form-label">Select Grade Category</label>
                                        <select name="grade_category_id" id="" class="from-control select2">
                                            <option value="" disabled selected>Select a Course Grade</option>
                                            @foreach (App\Models\Gradecategory::select('id', 'name', 'garde_id')->where('name', 'Course Syllabus')->get() as $item)
                                                <option value="{{ $item->id }}">
                                                    {{ $item->name }}({{ $item->grade->name ?? null }})</option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div class="mb-3">
                                        <label for="pdf" class="form-label">Upload Course PDF</label>
                                        <input type="file" name="image" class="form-control" accept="application/pdf"
                                            required>


                                    </div>

                                    <button type="submit" class="btn btn-primary">Create</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Right: Course Table --}}
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Courses Syllabus</h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="courses-table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Grade Category</th>
                                                <th>File</th>
                                                <th>Status</th>
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
    </div>

    <script>
        $(document).on('change', '.toggle-status', function() {
            var form = $(this).closest('form');
            var isChecked = $(this).is(':checked');
            form.find('input[name="status"]').val(isChecked ? 1 : 0);
            form.submit();
        });
    </script>
    <script>
        $(function() {
            $('#courses-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('course.index') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'grade_category',
                        name: 'grade_category'
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
                    },
                ]
            });
        });
    </script>
@endsection
