@extends('backEnd.reseller.layout.master')
@section('title', 'Profile Settings')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Profile Settings</h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('reseller.profile.update')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" name="name" value="{{$profile->name}}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-control" name="email" value="{{$profile->email}}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Phone Number</label>
                                <input type="text" class="form-control" name="phone" value="{{$profile->phone}}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Profile Image</label>
                                <input type="file" class="form-control" name="image">
                                @if($profile->image)
                                    <img src="{{asset($profile->image)}}" alt="" class="img-thumbnail mt-2" width="100">
                                @endif
                            </div>
                            <div class="mb-3">
                                <label class="form-label">New Password</label>
                                <input type="password" class="form-control" name="password"
                                    placeholder="Leave blank to keep current">
                            </div>
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection