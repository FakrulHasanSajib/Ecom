@extends('backEnd.layouts.master')
@section('title','Wholesaler Payment')
@section('css')
<link href="{{asset('public/backEnd')}}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="container-fluid">
    
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <a href="{{route('wholeseller.payment')}}" class="btn btn-primary rounded-pill">Manage</a>
                </div>
                <h4 class="page-title">Wholesaler Payment</h4>
            </div>
    </div>       
    <!-- end page title --> 
   <div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                            <form action="{{ route('wholesellerpay.updates') }}" method="POST" class="row" data-parsley-validate
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" value="{{ $edit_pay->id }}" name="hidden_id">

                                <!-- Name -->
                                <div class="col-sm-6">
                                    <div class="position-relative mb-3">
                                        <span class="position-absolute top-0 start-0 ms-2 bg-white px-1 fw-bold fs-6"
                                            style="z-index: 1; transform: translateY(-50%);">Whosaler *</span>
                                        <select class="form-select" name="whosaler_id">
						                <option selected>Select a Whosaler</option>
						                @foreach($wholesaler as $whosaler)
						                <option value="{{$whosaler->id}}" @if($whosaler->id==$edit_pay->whosales_id ) selected @endif>{{$whosaler->business_name}}</option>
						                @endforeach
						              </select>
                                        @error('whosaler_id')
                                            <span class="invalid-feedback"
                                                role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="col-sm-6">
                                    <div class="position-relative mb-3">
                                        <span class="position-absolute top-0 start-0 ms-2 bg-white px-1 fw-bold fs-6"
                                            style="z-index: 1; transform: translateY(-50%);">Payment Method *</span>
                                        <select class="form-select" name="payment_method">
                                    <option disabled {{ is_null($edit_pay->payment_method) ? 'selected' : '' }}>Select a Payment</option>
                                    <option value="Cash On Delivery" @if($edit_pay->payment_method == "Cash On Delivery") selected @endif>Cash On Delivery</option>
                                    <option value="In Courier" @if($edit_pay->payment_method == "In Courier") selected @endif>In Courier</option>
                                    <option value="Bkash" @if($edit_pay->payment_method == "Bkash") selected @endif>Bkash</option>
                                </select>

                                        @error('payment_method')
                                            <span class="invalid-feedback"
                                                role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Mobile -->
                                <div class="col-sm-6">
                                    <div class="position-relative mb-3">
                                        <span class="position-absolute top-0 start-0 ms-2 bg-white px-1 fw-bold fs-6"
                                            style="z-index: 1; transform: translateY(-50%);">Pay Amount *</span>
                                        <input type="number"
                                            class="form-control border border-dark rounded-3 @error('advance') is-invalid @enderror"
                                            name="advance" value="{{ $edit_pay->pay_amount }}" id="advance" required>
                                        @error('advance')
                                            <span class="invalid-feedback"
                                                role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Password -->
                                <div class="col-sm-6">
                                    <div class="position-relative mb-3">
                                        <span class="position-absolute top-0 start-0 ms-2 bg-white px-1 fw-bold fs-6"
                                            style="z-index: 1; transform: translateY(-50%);">Pay Date *</span>
                                        <input type="date"
                                            class="form-control border border-dark rounded-3 @error('date') is-invalid @enderror"
                                            name="date" id="date" value="{{$edit_pay->date}}">
                                        @error('date')
                                            <span class="invalid-feedback"
                                                role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Confirm Password -->
                                <div class="col-sm-6">
                                    <div class="position-relative mb-3">
                                        <span class="position-absolute top-0 start-0 ms-2 bg-white px-1 fw-bold fs-6"
                                            style="z-index: 1; transform: translateY(-50%);">Pay Reference</span>
                                        <textarea name="paynote" class="form-control"> {{$edit_pay->paynode}}</textarea>
                                        @error('paynote')
                                            <span class="invalid-feedback"
                                                role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>


                                <!-- Submit -->
                                <div class="col-12">
                                    <input type="submit" class="btn btn-success px-4" value="Submit">
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
<script src="{{asset('public/backEnd/')}}/assets/libs/select2/js/select2.min.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/js/pages/form-advanced.init.js"></script>
@endsection