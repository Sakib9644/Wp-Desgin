@extends('backend.layouts.master')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div id="clock" style="font-size: 24px; font-weight: bold;"></div>

                    <!-- Form Column -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h4>Add Novel Lesson</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('novelunitlessons.store') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="novel_unit_list_id" class="form-label">Select Novel Name</label>
                                        <select name="novel_unit_list_id" id="novel_unit_list_id"
                                            class="form-control select2" required>
                                            @foreach (App\Models\Novelunitlist::with('novel.grades_category.grade')->get() as $item)
                                                <option value="{{ $item->id }}">
                                                    {{ $item->name }} -
                                                   {{ $item->novel->unit_name ?? 'No Unit Name' }} -
                                                    
                                                    {{ optional($item->novel->grades_category->grade)->name ?? 'No Grade' }}
                                                </option>
                                            @endforeach

                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="name" class="form-label">Lesson Name</label>
                                        <input type="text" name="name" id="name" class="form-control" required>
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
                                <h4 class="mb-0">Novel Lessons</h4>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered" id="novelUnitDetailsTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Novel Name</th>
                                            <th>Lesson Name</th>
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
            $('#novelUnitDetailsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('novelunitlessons.index') }}",
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
                        data: 'name',
                        name: 'name'
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
