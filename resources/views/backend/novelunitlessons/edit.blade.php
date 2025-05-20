@extends('backend.layouts.master')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <div class="row">
                    <div id="clock" style="font-size: 24px; font-weight: bold;"></div>

                    <!-- Edit Form Column -->
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Edit Novel Unit Lesson</h4>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('novelunitlessons.update', $novelunitlesson->id) }}"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')

                                        <div class="mb-3">
                                            <label for="novel_unit_list_id" class="form-label">Select Novel Unit
                                                Name</label>
                                            <select name="novel_unit_list_id" id="novel_unit_list_id"
                                                class="form-control select2" required>
                                                @foreach (App\Models\Novelunitlist::with('novel.grades_category.grade')->get() as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ $item->id == $novelunitlesson->novel_unit_list_id ? 'selected' : '' }}>
                                                        {{ $item->name }} -
                                                        {{ $item->novel->unit_name ?? 'No Unit Name' }} -
                                                        {{ optional($item->novel->grades_category->grade)->name ?? 'No Grade' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="name" class="form-label">Lesson Name</label>
                                            <input type="text" name="name" id="name" class="form-control"
                                                required value="{{ old('name', $novelunitlesson->name) }}">
                                        </div>

                                        <button type="submit" class="btn btn-primary">Update</button>
                                        <a href="{{ route('novelunitlessons.index') }}" class="btn btn-secondary">Back</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
