@extends('backend.layouts.master')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <h2 class="mb-3">Main Categories</h2>

                <div class="row">



                    {{-- Table Column --}}
                    <div class="col-md-8 mb-5">
                        <div class="card">
                            <div class="card-body table-responsive">
                                <h5>Category List</h5>
                                <table class="table table-bordered" id="category-table">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Name</th>
                                            <th>Image</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <!-- Table Body will be dynamically populated -->
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-5">
                        <div class="card">
                            <div class="card-body">
                                <h5>Create Category</h5>
                                <form action="{{ route('main-category.store') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Name</label>
                                        <input type="text" name="name" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Image (optional)</label>
                                        <input type="file" name="image" class="form-control">
                                    </div>
                                    <button type="submit" class="btn btn-success">Create</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div> <!-- End of row -->
             

                {{-- Edit Category Modal --}}
                <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form id="editCategoryForm" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="id" id="category-id">

                                    <div class="mb-3">
                                        <label class="form-label">Name</label>
                                        <input type="text" name="name" id="category-name" class="form-control"
                                            required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Image (optional)</label>
                                        <input type="file" name="image" id="category-image" class="form-control">
                                        <img src="" id="current-image" class="mt-2" width="100px" height="100px"
                                            style="display:none;" alt="Current Image">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div> <!-- container-fluid -->
        </div> <!-- End Page-content -->
    </div> <!-- End main-content -->

    {{-- Scripts --}}
    <script>
        $(function() {
            $('#category-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('main-category.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'image',
                        name: 'image',
                        orderable: false,
                        searchable: false
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

    <script>
        const editCategoryModal = document.getElementById('editCategoryModal');
        if (editCategoryModal) {
            editCategoryModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const categoryId = button.getAttribute('data-id');
                const categoryName = button.getAttribute('data-name');
                const categoryImage = button.getAttribute('data-image');

                const routeTemplate = "{{ route('main-category.update', ':id') }}";
                const updateUrl = routeTemplate.replace(':id', categoryId);
                document.getElementById('editCategoryForm').action = updateUrl;

                document.getElementById('category-id').value = categoryId;
                document.getElementById('category-name').value = categoryName;

                const imgElement = document.getElementById('current-image');
                if (categoryImage) {
                    imgElement.src = categoryImage;
                    imgElement.style.display = 'block';
                } else {
                    imgElement.style.display = 'none';
                }
            });
        }
    </script>
@endsection
