@extends('backEnd.layouts.master')
@section('title', 'Loyalty Manage')
@section('css')
<link href="{{asset('backEnd')}}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('backEnd')}}/assets/libs/summernote/summernote-lite.min.css" rel="stylesheet" type="text/css" />
<style>
    #preview-image {
        max-width: 300px;
        max-height: 300px;
        margin-top: 10px;
        border: 2px solid #ddd;
        border-radius: 5px;
        padding: 5px;
    }
</style>
@endsection
@section('content')
@include('backEnd.layouts.author_menu')
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <a href="{{route('loylaty.manage')}}" class="btn btn-primary rounded-pill">Manage</a>
                </div>
                <h4 class="page-title">Loyalty Assigin</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
   <div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="{{route('loyality.store')}}" method="POST" class=row data-parsley-validate=""  enctype="multipart/form-data">
                    @csrf
                    
                     <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label for="title" class="form-label">Title *</label>
                            <input type="text" class="form-control" name="title"/>
                           
                            @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label for="title" class="form-label">Author *</label>
                            <select id="author_id" class="form-control @error('author_id') is-invalid @enderror" name="author_id" required>
                                <option>Select Option</option>
                                @foreach($user as $authors)
                                <option value="{{$authors->id}}">{{$authors->name}}</option>
                                @endforeach
                                </select>
                           
                            @error('author_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <!-- col-end -->
                    
                    <!-- col-end -->
                     
                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label for="product_id" class="form-label">Product</label>
                            <select id="products" class="select2 form-control @error('product_id') is-invalid @enderror" name="product_id[]" data-placeholder="Choose ..." required multiple>
                                @foreach(\App\Models\Product::orderBy('created_at', 'desc')->get() as $product)
                                    <option value="{{$product->id}}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group" id="discount_table">

                    </div>
                    <!-- col-end -->
                    <div class="col-sm-6 mb-3">
                        <div class="form-group">
                            <label for="status" class="d-block">Status</label>
                            <label class="switch">
                              <input type="checkbox" value="1" name="status" checked>
                              <span class="slider round"></span>
                            </label>
                            @error('status')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div> 
                    <!-- col end -->
                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label for="banner" class="form-label">Image *</label>
                            <input type="file" class="form-control @error('banner') is-invalid @enderror" name="banner" accept="image/*">
                            
                            <!-- Image Preview -->
                            <img id="preview-image" src="#" alt="Image Preview" style="display: none;">

                           
                            @error('banner')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <input type="submit" class="btn btn-success" value="Submit">
                    </div>

                </form>

            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->
   </div>
</div>
@endsection




@section('script')
<script src="{{asset('backEnd/')}}/assets/libs/parsleyjs/parsley.min.js"></script>
<script src="{{asset('backEnd/')}}/assets/js/pages/form-validation.init.js"></script>
<script src="{{asset('backEnd/')}}/assets/js/pages/form-advanced.init.js"></script>
<script src="{{asset('backEnd/')}}/assets/libs/select2/js/select2.min.js"></script>
<!-- Plugins js -->
<script src="{{asset('backEnd/')}}/assets/libs//summernote/summernote-lite.min.js"></script>
<script>
  $(document).ready(function () {
        $('.select2').select2();
        $('#products').on('change', function(){
                var product_id = $('#products').val();
                if(product_id.length > 0){
                    $.ajax({
    url: '{{ route('loyality.product') }}',  // Endpoint for the request
    type: 'POST',  // Type of the request (POST in this case)
    data: {
        _token: '{{ csrf_token() }}',  // CSRF token for security
        product_id: product_id  // Product IDs you're sending
    },
    success: function(data) {  // Callback function when the request is successful
        $('#discount_table').html(data);  // Insert the received data into the element with id 'discount_table'
    },
    error: function(xhr, status, error) {  // Callback for error handling
        console.error('AJAX request failed:', status, error);  // Log error details
    }
});

                }
                else{
                    $('#discount_table').html(null);
                }
            });
    });
    
    document.querySelector('input[name="banner"]').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('preview-image');
        if(file){
            const reader = new FileReader();
            reader.onload = function(e){
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    });
</script>
@endsection