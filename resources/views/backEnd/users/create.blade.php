@extends('backEnd.layouts.master')
@section('title','Users Create')

@section('css')
<link href="{{asset('public/backEnd')}}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('public/backEnd')}}/assets/css/switchery.min.css" rel="stylesheet" type="text/css" />

<style>
    /* Custom Form Styling */
    .card {
        border: none;
        box-shadow: 0 0 35px 0 rgba(154, 161, 171, 0.15);
        border-radius: 12px;
    }
    .card-header {
        background-color: #fff;
        border-bottom: 1px solid #f1f3fa;
        border-radius: 12px 12px 0 0;
        padding: 1.5rem;
    }
    .form-label {
        font-weight: 600;
        color: #6c757d;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }
    .form-control {
        padding: 10px 15px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    .form-control:focus {
        border-color: #727cf5; /* Primary Color */
        box-shadow: 0 0 0 0.2rem rgba(114, 124, 245, 0.25);
    }
    .select2-container .select2-selection--multiple {
        min-height: 45px;
        border-radius: 8px;
        border-color: #dee2e6;
    }
    .btn-submit {
        padding: 12px 40px;
        font-weight: 600;
        border-radius: 30px;
        box-shadow: 0 4px 10px rgba(10, 207, 151, 0.3);
    }
    .section-title {
        font-size: 16px;
        font-weight: 700;
        color: #323a46;
        margin-bottom: 20px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-left: 4px solid #727cf5;
        padding-left: 10px;
    }
</style>
@endsection

@section('content')

<div class="container-fluid">
    
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <a href="{{route('users.index')}}" class="btn btn-primary rounded-pill waves-effect waves-light">
                        <i class="mdi mdi-arrow-left me-1"></i> Back to Manage
                    </a>
                </div>
                <h4 class="page-title">Create New User</h4>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body p-4">
                <form action="{{ route('users.store') }}" method="POST" class="row g-3" data-parsley-validate enctype="multipart/form-data">
                    @csrf

                    <div class="col-12">
                        <h5 class="section-title">Account Information</h5>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-2">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                name="name" value="{{ old('name') }}" id="name" placeholder="Enter full name" required>
                            @error('name')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-2">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                name="email" value="{{ old('email') }}" id="email" placeholder="Enter email address" required>
                            @error('email')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-2">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                name="password" id="password" placeholder="Enter strong password" required>
                            @error('password')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-2">
                            <label for="confirm-password" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('confirm-password') is-invalid @enderror" 
                                name="confirm-password" id="confirm-password" placeholder="Re-enter password" required>
                            @error('confirm-password')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 mt-4">
                        <hr class="my-3">
                        <h5 class="section-title">Financial & Roles</h5>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-2">
                            <label for="commission_rate" class="form-label">Commission Rate (%)</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="commission_rate" value="0" step="0.01" placeholder="Ex: 2">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-2">
                            <label for="fixed_commission" class="form-label">Fixed Commission</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" name="fixed_commission" value="0" step="0.01" placeholder="Ex: 50">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-2">
                            <label for="roles" class="form-label">Assign Role <span class="text-danger">*</span></label>
                            <select class="form-control select2-multiple @error('roles') is-invalid @enderror" 
                                name="roles[]" data-toggle="select2" multiple data-placeholder="Select roles..." required>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                            @error('roles')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-2">
                            <label for="image" class="form-label">Profile Image <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                name="image" id="image" required>
                            @error('image')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-2 mt-2 p-2 border rounded">
                            <div class="d-flex align-items-center justify-content-between">
                                <label for="status" class="form-label mb-0 fw-bold">Active Status</label>
                                <div>
                                    <input type="checkbox" class="js-switch" name="status" value="1" checked />
                                </div>
                            </div>
                            @error('status')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 text-end mt-4">
                        <button type="submit" class="btn btn-success btn-submit waves-effect waves-light">
                            <i class="mdi mdi-check-circle me-1"></i> Create User
                        </button>
                    </div>

                </form>
            </div> </div> </div> </div>
</div>
@endsection

@section('script')
<script src="{{asset('public/backEnd/')}}/assets/libs/parsleyjs/parsley.min.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/js/pages/form-validation.init.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/libs/select2/js/select2.min.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/js/pages/form-advanced.init.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/js/switchery.min.js"></script>
<script>
    $(document).ready(function(){
        // Switchery Initialization if not handled by main theme js
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        elems.forEach(function(html) {
            var switchery = new Switchery(html, { size: 'small', color: '#1abc9c' });
        });
    });
</script>
@endsection