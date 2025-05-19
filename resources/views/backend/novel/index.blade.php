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
                            <h4>Add Novel Unit</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('novel.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="grade_category_id" class="form-label">Grade Category</label>
                                    <select name="grade_category_id" id="grade_category_id" class="form-control select2" required>
                                        @foreach (App\Models\Gradecategory::with('grade')->where('name', 'Novel Unit')->get() as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->name }} ({{ $item->grade->name ?? 'No Grade' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="novel unit" class="form-label">Novel Unit</label>
                                    <input type="text" name="unit_name" id="grade" class="form-control" required>
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
                            <h4 class="mb-0">Novel Unit List</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered" id="teacherGradeTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Grade</th>
                                        <th>Grade Category</th>
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
    $(function () {
        $('#teacherGradeTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('novel.index') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'unit_name', name: 'unit_name' },
                { data: 'Grade Category', name: 'Grade Category' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
        });
    });
</script>

@endsection
