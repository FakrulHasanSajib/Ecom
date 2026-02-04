@extends('backEnd.layouts.master')
@section('title','Users Edit')

@section('css')
<link href="{{asset('public/backEnd')}}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('public/backEnd')}}/assets/css/switchery.min.css" rel="stylesheet" type="text/css" />

<style>
    /* Custom Form Styling for Edit Page */
    .card {
        border: none;
        box-shadow: 0 0 35px 0 rgba(154, 161, 171, 0.15);
        border-radius: 12px;
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
    .form-label {
        font-weight: 600;
        color: #6c757d;
        margin-bottom: 0.5rem;
    }
    .form-control {
        padding: 10px 15px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        transition: border-color 0.3s;
    }
    .form-control:focus {
        border-color: #727cf5;
        box-shadow: 0 0 0 0.2rem rgba(114, 124, 245, 0.25);
    }
    .current-img-box {
        padding: 5px;
        border: 1px dashed #dee2e6;
        border-radius: 8px;
        display: inline-block;
        margin-top: 10px;
    }
    .btn-submit {
        padding: 12px 40px;
        border-radius: 30px;
        box-shadow: 0 4px 10px rgba(10, 207, 151, 0.3);
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
                <h4 class="page-title">Edit User Information</h4>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-4">
                    <form action="{{ route('users.update') }}" method="POST" class="row g-3" data-parsley-validate enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" value="{{ $edit_data->id }}" name="hidden_id">

                        <div class="col-12">
                            <h5 class="section-title">Account Information</h5>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                    name="name" value="{{ $edit_data->name }}" id="name" required>
                                @error('name')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                    name="email" value="{{ $edit_data->email }}" id="email" required>
                                @error('email')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="mobile" class="form-label">Mobile Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('mobile') is-invalid @enderror" 
                                    name="mobile" value="{{ $edit_data->mobile }}" id="mobile" required>
                                @error('mobile')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6"></div>

                        <div class="col-12 mt-3">
                            <h5 class="section-title">Security (Optional)</h5>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                    name="password" id="password" placeholder="Leave blank to keep current password">
                                @error('password')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="confirm-password" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control @error('confirm-password') is-invalid @enderror" 
                                    name="confirm-password" id="confirm-password" placeholder="Re-enter new password">
                                @error('confirm-password')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12 mt-3">
                            <h5 class="section-title">Financial & Configuration</h5>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="commission_rate" class="form-label">Commission Rate (%)</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="commission_rate" 
                                        value="{{ $edit_data->commission_rate }}" step="0.01">
                                    <span class="input-group-text bg-light">%</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="fixed_commission" class="form-label">Fixed Commission</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">$</span>
                                    <input type="number" class="form-control" name="fixed_commission" 
                                        value="{{ $edit_data->fixed_commission }}" step="0.01">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="roles" class="form-label">Assign Role <span class="text-danger">*</span></label>
                                <select class="form-control select2-multiple @error('roles') is-invalid @enderror" 
                                    name="roles[]" data-toggle="select2" multiple data-placeholder="Choose roles..." required>
                                    <optgroup label="Available Roles">
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}"
                                                @foreach ($edit_data->roles as $srole)
                                                    {{ $srole->id == $role->id ? 'selected' : '' }} 
                                                @endforeach
                                            >
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                </select>
                                @error('roles')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="image" class="form-label">Profile Image</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                    name="image" id="image">
                                
                                <div class="current-img-box">
                                    <span class="text-muted d-block small mb-1">Current Image:</span>
                                    <img src="{{ asset($edit_data->image) }}" alt="Current Profile" 
                                        class="rounded" height="60" width="60" style="object-fit: cover;">
                                </div>

                                @error('image')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-2 mt-2 p-2 border rounded bg-light-50">
                                <div class="d-flex align-items-center justify-content-between">
                                    <label for="status" class="form-label mb-0 fw-bold">Active Status</label>
                                    <div>
                                        <input type="checkbox" class="js-switch" name="status" value="1" 
                                            {{ $edit_data->status == 1 ? 'checked' : '' }} />
                                    </div>
                                </div>
                                @error('status')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12 text-end mt-4">
                            <button type="submit" class="btn btn-success btn-submit waves-effect waves-light">
                                <i class="mdi mdi-content-save-edit me-1"></i> Update User
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
        // Initialize Switchery manually if needed, or rely on form-advanced.init.js
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        elems.forEach(function(html) {
            var switchery = new Switchery(html, { size: 'small', color: '#1abc9c' });
        });
    });
</script>
@endsection