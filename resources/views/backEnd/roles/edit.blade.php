@extends('backEnd.layouts.master')
@section('title','Roles Edit')
@section('css')
    <link href="{{asset('public/backEnd')}}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <style>
        .permission-card {
            height: 100%;
            border: 1px solid #eef2f7;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: all 0.3s ease;
        }
        .permission-card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .permission-header {
            background-color: #f7f9fb;
            border-bottom: 1px solid #eef2f7;
            padding: 10px 15px;
            font-weight: 600;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid">
    
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <a href="{{route('roles.index')}}" class="btn btn-primary rounded-pill">Manage Roles</a>
                </div>
                <h4 class="page-title">Edit Role</h4>
            </div>
        </div>
    </div>       
    <div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="{{route('roles.update')}}" method="POST" class="row" data-parsley-validate="" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="hidden_id" value="{{$edit_data->id}}">
                    
                    {{-- Role Name Input --}}
                    <div class="col-sm-12 mb-4">
                        <div class="form-group">
                            <label for="name" class="form-label">Role Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   name="name" value="{{ $edit_data->name }}" id="name" required placeholder="Enter role name">
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    {{-- Select All Checkbox --}}
                    <div class="col-12 mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="checkAllPermission">
                            <label class="form-check-label fw-bold text-primary" style="font-size: 16px; cursor:pointer;" for="checkAllPermission">Select All Permissions</label>
                        </div>
                        <hr>
                    </div>

                    {{-- Dynamic Permission Groups --}}
                    <div class="row">
                        @foreach($permission_structure as $groupName => $permissionsList)
                            <div class="col-md-4 mb-4">
                                <div class="card permission-card">
                                    {{-- Group Header --}}
                                    <div class="card-header permission-header">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" 
                                                   id="group_{{ Str::slug($groupName) }}"
                                                   onclick="checkPermissionByGroup('group-{{ Str::slug($groupName) }}', this)">
                                            <label class="form-check-label text-capitalize" style="cursor:pointer;" for="group_{{ Str::slug($groupName) }}">
                                                {{ $groupName }} Management
                                            </label>
                                        </div>
                                    </div>

                                    {{-- Individual Permissions --}}
                                    <div class="card-body">
                                        @foreach($permissionsList as $perm)
                                            <div class="form-check mb-2">
                                                <input type="checkbox" 
                                                       class="form-check-input group-{{ Str::slug($groupName) }}" 
                                                       name="permission[]" 
                                                       value="{{ $perm->id }}" 
                                                       id="perm_{{ $perm->id }}"
                                                       
                                                       {{-- Check if role already has this permission --}}
                                                       {{ $edit_data->hasPermissionTo($perm->name) ? 'checked' : '' }}
                                                       >
                                                
                                                <label class="form-check-label text-capitalize" style="cursor:pointer;" for="perm_{{ $perm->id }}">
                                                    {{ str_replace(['-', '.', '_'], ' ', $perm->name) }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="col-12 mt-3">
                        <button type="submit" class="btn btn-success waves-effect waves-light">
                            <i class="fe-save me-1"></i> Update Role
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

<script>
    /**
     * 1. Check/Uncheck All Permissions
     */
    $("#checkAllPermission").click(function(){
        if($(this).is(':checked')){
            $('input[type=checkbox]').prop('checked', true);
        }else{
            $('input[type=checkbox]').prop('checked', false);
        }
    });

    /**
     * 2. Check/Uncheck Specific Group
     * @param className (Unique class name for the group inputs)
     * @param checkThis (The group header checkbox element)
     */
    function checkPermissionByGroup(className, checkThis){
        const groupIdName = $("."+className);
        if($(checkThis).is(':checked')){
            groupIdName.prop('checked', true);
        }else{
            groupIdName.prop('checked', false);
        }
    }
</script>
@endsection