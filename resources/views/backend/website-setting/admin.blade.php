@extends('backend.layouts.master')

@section('content')
    <div class="main-content mt-5">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row vh-100 mt-4">
                    <div class="col-12">
                        <div class="card mt-xxl-n5">
                            <div class="card-header">
                                <h5 class="mb-0">Admin Website Setup</h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('websitesetup.update') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf

                                    <div class="row">
                                        <div class="col-lg-6 mb-3">
                                            <label for="website_name" class="form-label">Admin Name</label>
                                            <input type="text" class="form-control" name="website_name"
                                                value="{{ $data->website_name ?? '' }}" placeholder="Enter Website Name">
                                        </div>
                                       
                                        <div class="col-lg-6 mb-3">
                                            <label for="admin_footer" class="form-label">Admin Footer Text</label>
                                            <input type="text" class="form-control" name="admin_footer"
                                                value="{{ $data->admin_footer ?? '' }}" placeholder="Enter footer text">
                                        </div>

                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label">Site Icon</label><br>
                                            @if (!empty($data->site_icon))
                                                <img id="site_icon_preview" src="{{ asset($data->site_icon) }}"
                                                    class="mb-2" width="120" height="70">
                                            @endif
                                            <input onchange="previewImage(event, 'site_icon_preview')" type="file"
                                                name="site_icon" class="form-control">
                                        </div>

                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label">Logo</label><br>
                                            @if (!empty($data->logo))
                                                <img id="logo_preview" src="{{ asset($data->logo) }}" class="mb-2"
                                                    width="120" height="70">
                                            @endif
                                            <input onchange="previewImage(event, 'logo_preview')" type="file"
                                                name="logo" class="form-control">
                                        </div>

                                    </div>
                                    <div class="col-12 text-end">
                                        <button type="submit" class="btn btn-success">Update Settings</button>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div> <!-- end col -->
                </div> <!-- end row -->
            </div> <!-- container-fluid -->
        </div> <!-- page-content -->
    </div>
@endsection
