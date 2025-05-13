@extends('backend.layouts.master')

@section('content')
<div class="main-content mt-5">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row vh-100 mt-4">
                <div class="col-xxl-3">
                    <div class="card mt-n5">
                        <div class="card-body p-4">
                            <div class="text-center">
                                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                                    <div class="profile-user position-relative d-inline-block mx-auto mb-4">
                                        <img id="profile_preview" src="{{ auth()->user()->image ? asset(auth()->user()->image) : asset('assets/images/users/avatar-1.jpg') }}"
                                             class="rounded-circle avatar-xl img-thumbnail user-profile-image material-shadow"
                                             alt="user-profile-image"> 
                                        <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                            <input id="profile-img-file-input" name="image" type="file" class="profile-img-file-input" onchange="previewImage(event, 'profile_preview')">
                                            <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                                <span class="avatar-title rounded-circle bg-light text-body material-shadow">
                                                    <i class="ri-camera-fill"></i>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                    <h5 class="fs-16 mb-1">{{ auth()->user()->name }}</h5>
                                    <p class="text-muted mb-0">{{ auth()->user()->role }}</p>
                            </div>
                            
                        </div>
                    </div>
                </div>


             
            
             
                <div class="col-xxl-9">
                    <div class="card mt-xxl-n5">
                        <div class="card-header">
                            <h5 class="mb-0">Profile Settings</h5>
                        </div>
                        <div class="card-body">
                                @method('PUT')
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6 mb-3">
                                        <label for="firstnameInput" class="form-label"> Name</label>
                                        <input type="text" class="form-control" id="firstnameInput" name="name" value="{{ auth()->user()->name ?? '' }}" placeholder="Enter your first name">
                                    </div>
                                
                                    <div class="col-lg-6 mb-3">
                                        <label for="emailInput" class="form-label">Email Address</label>
                                        <input type="email" class="form-control" id="emailInput" name="email" value="{{ auth()->user()->email ?? '' }}" placeholder="Enter your email">
                                    </div>

                                    <div class="col-12">
                                        <hr>
                                        <h6 class="mb-3">Change Password</h6>
                                    </div>

                                 
                                    <div class="col-lg-4 mb-3">
                                        <label for="newpasswordInput" class="form-label">New Password*</label>
                                        <input type="password" class="form-control"  name="password" placeholder="Enter new password" autocomplete="new-password">
                                    </div>
                                    <div class="col-lg-4 mb-3">
                                        <label for="confirmpasswordInput" class="form-label">Confirm Password*</label>
                                        <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Confirm new password" value={{ old('password') }}>
                                    </div>

                                    {{-- <div class="col-lg-12 mb-3">
                                        <a href="#" class="link-primary text-decoration-underline">Forgot Password?</a>
                                    </div> --}}

                                    <div class="col-lg-12 text-end">
                                        <button type="submit" class="btn btn-primary">Update Profile</button>
                                    </div>
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
