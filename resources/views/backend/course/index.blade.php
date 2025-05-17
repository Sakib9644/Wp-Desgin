@extends('backend.layouts.master')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Create Course</h4>
                            <form action="{{ route('course.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="mb-3">
                                    <label for="name" class="form-label"> Title</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="image" class="form-label">Course Image</label>
                                    <input type="file" name="image" class="form-control" accept="image/*" required>
                                </div>

                                <div class="mb-3">
                                    <label for="pdf" class="form-label">Course PDF</label>
                                    <input type="file" name="pdf[]" class="form-control" accept="application/pdf" required>
                                </div>

                                <button type="submit" class="btn btn-primary">Create</button>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- Left: Course Table --}}
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Courses syllabus</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="courses-table">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>pdf</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right: Create Course Form --}}
                
            </div>

        </div>
    </div>
</div>


<script>
    $(function () {
        $('#courses-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("course.index") }}',
            columns: [
                { data: 'image', name: 'image' },
                { data: 'name', name: 'name' },
                { data: 'pdf', name: 'pdf' }
            ]
        });
    });
</script>
@endsection
