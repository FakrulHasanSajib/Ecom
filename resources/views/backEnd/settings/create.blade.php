@extends('backEnd.layouts.master')
@section('title','General Setting Create')
@section('css')
<link href="{{asset('backEnd')}}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('backEnd')}}/assets/libs/summernote/summernote-lite.min.css" rel="stylesheet" type="text/css" />
<style>
    /* কাস্টম স্টাইল */
    .color-picker-input {
        height: 50px;
        cursor: pointer;
        padding: 5px;
    }
    .section-title {
        border-bottom: 2px solid #f1f1f1;
        padding-bottom: 10px;
        margin-bottom: 20px;
        margin-top: 30px;
        font-weight: 600;
        color: #444;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <a href="{{route('settings.index')}}" class="btn btn-primary rounded-pill">Manage</a>
                </div>
                <h4 class="page-title">General Setting Create</h4>
            </div>
        </div>
    </div>      
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('settings.store')}}" method="POST" class="row" data-parsley-validate=""  enctype="multipart/form-data">
                        @csrf
                        
                        <div class="col-sm-6">
                            <div class="form-group mb-3">
                                <label for="name" class="form-label">Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" id="name" required="">
                                @error('name')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-6 mb-3">
                            <div class="form-group">
                                <label for="headercolor" class="form-label">Header Color</label>
                                <input type="text" name="headercolor" class="form-control" value="{{ old('headercolor', '#027547') }}" placeholder="#027547">
                            </div>
                        </div>

                        <div class="col-sm-4 mb-3">
                            <div class="form-group">
                                <label for="white_logo" class="form-label">White Logo *</label>
                                <input type="file" class="form-control @error('white_logo') is-invalid @enderror" name="white_logo" value="{{ old('white_logo') }}"  id="white_logo" required="">
                                @error('white_logo')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-4 mb-3">
                            <div class="form-group">
                                <label for="dark_logo" class="form-label">Dark Logo</label>
                                <input type="file" class="form-control @error('dark_logo') is-invalid @enderror" name="dark_logo" value="{{ old('dark_logo') }}"  id="dark_logo">
                                @error('dark_logo')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-4 mb-3">
                            <div class="form-group">
                                <label for="favicon" class="form-label">Favicon Logo *</label>
                                <input type="file" class="form-control @error('favicon') is-invalid @enderror" name="favicon" value="{{ old('favicon') }}"  id="favicon" required="">
                                @error('favicon')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-12 mb-3">
                            <div class="form-group">
                                <label for="status" class="d-block">Status</label>
                                <label class="switch">
                                  <input type="checkbox" value="1" name="status" checked>
                                  <span class="slider round"></span>
                                </label>
                                @error('status')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12 mt-4">
                            <h4 class="section-title">Header Nav Menu</h4>
                            <div id="menu-items-container">
                                <div class="menu-item row mb-2">
                                    <div class="col-5">
                                        <input type="text" class="form-control" name="header_menu_labels[]" placeholder="Label (e.g. Home)">
                                    </div>
                                    <div class="col-5">
                                        <input type="text" class="form-control" name="header_menu_links[]" placeholder="Link (e.g. /home)">
                                    </div>
                                    <div class="col-2">
                                        <button type="button" class="btn btn-danger" onclick="removeRow(this)"><i class="fas fa-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                            <a id="add-new-btn" class="btn btn-sm btn-primary mt-2"><i class="fas fa-plus"></i> Add New Menu</a>
                        </div>

                        <div class="col-12 mt-4">
                            <h4 class="section-title">Footer Nav Menu</h4>
                            <div id="footer-items-container">
                                <div class="menu-item row mb-2">
                                    <div class="col-5">
                                        <input type="text" class="form-control" name="footer_menu_labels[]" placeholder="Label (e.g. Contact Us)">
                                    </div>
                                    <div class="col-5">
                                        <input type="text" class="form-control" name="footer_menu_links[]" placeholder="Link (e.g. /contact)">
                                    </div>
                                    <div class="col-2">
                                        <button type="button" class="btn btn-danger" onclick="removeRow(this)"><i class="fas fa-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                            <a id="add-new-footer" class="btn btn-sm btn-primary mt-2"><i class="fas fa-plus"></i> Add New Footer Link</a>
                        </div>

                        <div class="col-12 mt-4">
                            <h4 class="section-title">Footer Color Settings</h4>
                            <div class="row">
                                <div class="col-sm-4 mb-3">
                                    <label class="form-label font-weight-bold">Footer Top Color (Shape 1)</label>
                                    <input type="color" name="footer_color_1" class="form-control color-picker-input" 
                                           value="#1a1a1a">
                                </div>
                                
                                <div class="col-sm-4 mb-3">
                                    <label class="form-label font-weight-bold">Footer Middle Color (Shape 2)</label>
                                    <input type="color" name="footer_color_2" class="form-control color-picker-input" 
                                           value="#0e4f35">
                                </div>
                                
                                <div class="col-sm-4 mb-3">
                                    <label class="form-label font-weight-bold">Footer Bottom Color (Shape 3)</label>
                                    <input type="color" name="footer_color_3" class="form-control color-picker-input" 
                                           value="#082e1f">
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mt-2">
                            <h4 class="section-title">Footer Copyright Info</h4>
                            <div class="row">
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label">Powered By Name</label>
                                    <input type="text" name="footer_text" class="form-control" value="{{ old('footer_text') }}">
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label">Powered By Link</label>
                                    <input type="text" name="footer_link" class="form-control" value="{{ old('footer_link') }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mt-4">
                            <input type="submit" class="btn btn-success btn-lg px-5" value="Submit">
                        </div>

                    </form>

                </div> </div> </div> </div>
</div>
@endsection


@section('script')
<script src="{{asset('backEnd/')}}/assets/libs/parsleyjs/parsley.min.js"></script>
<script src="{{asset('backEnd/')}}/assets/js/pages/form-validation.init.js"></script>
<script src="{{asset('backEnd/')}}/assets/libs/select2/js/select2.min.js"></script>
<script src="{{asset('backEnd/')}}/assets/js/pages/form-advanced.init.js"></script>
<script src="{{asset('backEnd/')}}/assets/libs//summernote/summernote-lite.min.js"></script>
<script>
    $(".summernote").summernote({
        placeholder: "Enter Your Text Here",
    });

    // Global Remove Function
    function removeRow(button) {
        $(button).closest('.menu-item').remove();
    }
    
    $(document).ready(function() {
        const menuItemsContainer = $("#menu-items-container");
        const footerItemsContainer = $("#footer-items-container");

        // Add Header Menu Item
        $("#add-new-btn").click(function() {
            const newItem = `
                <div class="menu-item row mb-2">
                    <div class="col-5">
                        <input type="text" class="form-control" name="header_menu_labels[]" placeholder="Label">
                    </div>
                    <div class="col-5">
                        <input type="text" class="form-control" name="header_menu_links[]" placeholder="Link">
                    </div>
                    <div class="col-2">
                        <button type="button" class="btn btn-danger" onclick="removeRow(this)"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
            `;
            menuItemsContainer.append(newItem);
        });
        
        // Add Footer Menu Item
        $("#add-new-footer").click(function() {
            const newfootItem = `
                <div class="menu-item row mb-2">
                    <div class="col-5">
                        <input type="text" class="form-control" name="footer_menu_labels[]" placeholder="Label">
                    </div>
                    <div class="col-5">
                        <input type="text" class="form-control" name="footer_menu_links[]" placeholder="Link">
                    </div>
                    <div class="col-2">
                        <button type="button" class="btn btn-danger" onclick="removeRow(this)"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
            `;
            footerItemsContainer.append(newfootItem);
        });

    });
</script>
@endsection