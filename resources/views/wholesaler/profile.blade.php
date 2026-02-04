@extends('wholesaler.master')
@section('title','Profile Management')
@section('css')
<link href="{{asset('public/backEnd')}}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<style>
    .toggle-buttons {
        margin-bottom: 20px;
    }
    .toggle-buttons .btn {
        margin-right: 10px;
        border-radius: 25px;
        padding: 10px 20px;
    }
    .toggle-buttons .btn.active {
        background-color: #5664d2;
        border-color: #5664d2;
        color: white;
    }
    .form-section {
        display: none;
    }
    .form-section.active {
        display: block;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('wholesaler.dashboard') }}">Dashboard</a></li>
                    </ol>
                </div>
                <h4 class="page-title">Profile Management</h4>
            </div>
        </div>
    </div>       
    <!-- end page title --> 
    
    <div class="row">
        <div class="col-lg-12">
            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <div class="card">
                <div class="card-body">
                    <!-- Toggle Buttons -->
                    <div class="toggle-buttons">
                        <button type="button" class="btn btn-outline-primary active" onclick="showForm('profile')">
                            <i class="mdi mdi-account"></i> Profile Update
                        </button>
                        <button type="button" class="btn btn-outline-primary" onclick="showForm('password')">
                            <i class="mdi mdi-lock"></i> Change Password
                        </button>
                        <button type="button" class="btn btn-outline-primary" onclick="showForm('contact')">
                            <i class="mdi mdi-phone"></i> Contact Information
                        </button>
                    </div>

                    <!-- Profile Update Form -->
                    <div id="profile-form" class="form-section active">
                        <h5 class="mb-3">Update Profile Information</h5>
                        <form action="{{route('wholesaler.profile.update')}}" method="POST" class="row" data-parsley-validate="" enctype="multipart/form-data">
                            @csrf
                            <div class="col-sm-6">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $wholesaler->name) }}" id="name" required="">
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $wholesaler->email) }}" id="email" required="">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group mb-3">
                                    <label for="phone" class="form-label">Mobile Number *</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone', $wholesaler->phone) }}" id="phone" required="">
                                    @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group mb-3">
                                    <label for="business_name" class="form-label">Business Name *</label>
                                    <input type="text" class="form-control @error('business_name') is-invalid @enderror" name="business_name" value="{{ old('business_name', $wholesaler->business_name) }}" id="business_name" required="">
                                    @error('business_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group mb-3">
                                    <label for="address" class="form-label">Address *</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" name="address" id="address" rows="3" required="">{{ old('address', $wholesaler->address) }}</textarea>
                                    @error('address')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <input type="submit" class="btn btn-success" value="Update Profile">
                            </div>
                        </form>
                    </div>

                    <!-- Change Password Form -->
                    <div id="password-form" class="form-section">
                        <h5 class="mb-3">Change Password</h5>
                        <form action="{{route('wholesaler.password.update')}}" method="POST" class="row" data-parsley-validate="">
                            @csrf
                            <div class="col-sm-12">
                                <div class="form-group mb-3">
                                    <label for="old_password" class="form-label">Current Password *</label>
                                    <input type="password" class="form-control @error('old_password') is-invalid @enderror" name="old_password" id="old_password" required="">
                                    @error('old_password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group mb-3">
                                    <label for="new_password" class="form-label">New Password *</label>
                                    <input type="password" class="form-control @error('new_password') is-invalid @enderror" name="new_password" id="new_password" required="">
                                    @error('new_password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group mb-3">
                                    <label for="confirm_password" class="form-label">Confirm Password *</label>
                                    <input type="password" class="form-control @error('confirm_password') is-invalid @enderror" name="confirm_password" id="confirm_password" required="">
                                    @error('confirm_password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <input type="submit" class="btn btn-success" value="Update Password">
                            </div>
                        </form>
                    </div>

                    <!-- Contact Information Form -->
                    <div id="contact-form" class="form-section">
                        <h5 class="mb-3">Contact Information</h5>
                        <form action="{{route('wholesaler.contact.update')}}" method="POST" class="row" data-parsley-validate="">
                            @csrf
                            <div class="col-sm-6">
                                <div class="form-group mb-3">
                                    <label for="website" class="form-label">Website URL</label>
                                    <input type="url" class="form-control @error('website') is-invalid @enderror" name="website" value="{{ old('website', $wholesaler->website) }}" id="website" placeholder="https://example.com">
                                    @error('website')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group mb-3">
                                    <label for="fb_page" class="form-label">Facebook Page</label>
                                    <input type="url" class="form-control @error('fb_page') is-invalid @enderror" name="fb_page" value="{{ old('fb_page', $wholesaler->fb_page) }}" id="fb_page" placeholder="https://facebook.com/yourpage">
                                    @error('fb_page')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group mb-3">
                                    <label for="bank_name" class="form-label">Bank Name</label>
                                    <input type="text" class="form-control @error('bank_name') is-invalid @enderror" name="bank_name" value="{{ old('bank_name', $wholesaler->bank_name) }}" id="bank_name">
                                    @error('bank_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group mb-3">
                                    <label for="account_name" class="form-label">Account Name</label>
                                    <input type="text" class="form-control @error('account_name') is-invalid @enderror" name="account_name" value="{{ old('account_name', $wholesaler->account_name) }}" id="account_name">
                                    @error('account_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group mb-3">
                                    <label for="account_number" class="form-label">Account Number</label>
                                    <input type="text" class="form-control @error('account_number') is-invalid @enderror" name="account_number" value="{{ old('account_number', $wholesaler->account_number) }}" id="account_number">
                                    @error('account_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group mb-3">
                                    <label for="routing_name" class="form-label">Routing Number</label>
                                    <input type="text" class="form-control @error('routing_name') is-invalid @enderror" name="routing_name" value="{{ old('routing_name', $wholesaler->routing_name) }}" id="routing_name">
                                    @error('routing_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group mb-3">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror" name="city" value="{{ old('city', $wholesaler->city) }}" id="city">
                                    @error('city')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group mb-3">
                                    <label for="postal_code" class="form-label">Postal Code</label>
                                    <input type="text" class="form-control @error('postal_code') is-invalid @enderror" name="postal_code" value="{{ old('postal_code', $wholesaler->postal_code) }}" id="postal_code">
                                    @error('postal_code')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <input type="submit" class="btn btn-success" value="Update Contact Information">
                            </div>
                        </form>
                    </div>

                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col-->
    </div>
</div>
@endsection

@section('script')
<script src="{{asset('public/backEnd/')}}/assets/libs/parsleyjs/parsley.min.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/js/pages/form-validation.init.js"></script>
<script>
    function showForm(formType) {
        // Hide all forms
        document.querySelectorAll('.form-section').forEach(function(form) {
            form.classList.remove('active');
        });
        
        // Remove active class from all buttons
        document.querySelectorAll('.toggle-buttons .btn').forEach(function(btn) {
            btn.classList.remove('active');
            btn.classList.add('btn-outline-primary');
            btn.classList.remove('btn-primary');
        });
        
        // Show selected form
        document.getElementById(formType + '-form').classList.add('active');
        
        // Add active class to clicked button
        event.target.classList.add('active');
        event.target.classList.remove('btn-outline-primary');
        event.target.classList.add('btn-primary');
    }

    // Handle form errors - show the form with errors
    @if($errors->has('old_password') || $errors->has('new_password') || $errors->has('confirm_password'))
        showFormByType('password');
    @elseif($errors->has('website') || $errors->has('fb_page') || $errors->has('bank_name') || $errors->has('account_name') || $errors->has('account_number') || $errors->has('routing_name') || $errors->has('city') || $errors->has('postal_code'))
        showFormByType('contact');
    @elseif($errors->has('name') || $errors->has('email') || $errors->has('phone') || $errors->has('business_name') || $errors->has('address'))
        showFormByType('profile');
    @endif

    function showFormByType(formType) {
        document.querySelectorAll('.form-section').forEach(function(form) {
            form.classList.remove('active');
        });
        
        document.querySelectorAll('.toggle-buttons .btn').forEach(function(btn) {
            btn.classList.remove('active');
            btn.classList.add('btn-outline-primary');
            btn.classList.remove('btn-primary');
        });
        
        document.getElementById(formType + '-form').classList.add('active');
        
        // Find and activate the correct button
        const buttons = document.querySelectorAll('.toggle-buttons .btn');
        buttons.forEach(function(btn) {
            if (btn.getAttribute('onclick').includes(formType)) {
                btn.classList.add('active');
                btn.classList.remove('btn-outline-primary');
                btn.classList.add('btn-primary');
            }
        });
    }
</script>
@endsection