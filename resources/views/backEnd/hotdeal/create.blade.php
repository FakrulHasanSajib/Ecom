@extends('backEnd.layouts.master')
@section('title','Hot Deal')
@section('css')
<link href="{{asset('public/backEnd')}}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('public/backEnd')}}/assets/libs/summernote/summernote-lite.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <a href="{{route('hotdeal.index')}}" class="btn btn-primary rounded-pill">Manage</a>
                </div>
                
            </div>
        </div>
    </div>
    <!-- end page title -->
   <div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="{{route('hotdel.store')}}" method="POST" class=row data-parsley-validate=""  enctype="multipart/form-data">
                    @csrf
                    <div class="col-sm-6">
                        <div class="form-group mb-3">
                            <label for="title" class="form-label">title *</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" required="">
                            @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <!-- col-end -->
                    <div class="col-sm-6">
                        <div class="form-group mb-3">
                            <label for="banner" class="form-label">Banner  *</label>
                            <input type="file" class="form-control @error('banner') is-invalid @enderror" name="banner" value="{{ old('banner') }}"  id="banner" required="">
                            @error('banner')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <!-- col-end -->
                     <div style="display:none;">


                    <div class="col-sm-6">
                        <div class="form-group mb-3">
                            <label for="start_date" class="form-label">Start Date*</label>
                            <input type="date" class="form-control" name="start_date" >
                            @error('start_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group mb-3">
                            <label for="end_date" class="form-label">End Date*</label>
                            <input type="date" class="form-control" name="end_date" >
                            @error('end_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    </div>
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
<script src="{{asset('public/backEnd/')}}/assets/libs/parsleyjs/parsley.min.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/js/pages/form-validation.init.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/js/pages/form-advanced.init.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/libs/select2/js/select2.min.js"></script>
<!-- Plugins js -->
<script src="{{asset('public/backEnd/')}}/assets/libs//summernote/summernote-lite.min.js"></script>
<script>
  $(document).ready(function () {
        $('.select2').select2();
        $('#products').on('change', function(){
                var product_id = $('#products').val();
                if(product_id.length > 0){
                    $.ajax({
    url: '{{ route('hotdel.product') }}',  // Endpoint for the request
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
</script>
@endsection