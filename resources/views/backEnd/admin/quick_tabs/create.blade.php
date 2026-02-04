@extends('backEnd.layouts.master')
@section('title','Create Quick Tab')
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8 col-md-10">
        <div class="card shadow-lg border-0 rounded-lg mt-5">
            <div class="card-header bg-success text-white text-center py-3"> 
                <h4 class="m-0 font-weight-bold"><i class="fas fa-plus-circle me-2"></i>Create Quick Tab </h4>
            </div>
            <div class="card-body p-4">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>ত্রুটি!</strong> ফর্ম পূরণে কিছু সমস্যা আছে।
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                <form action="{{ route('admin.quick_tabs.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group mb-4">
                        <label for="tab_name" class="form-label font-weight-bold">শর্টকাট-এর নাম <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-tag"></i></span>
                            <input type="text" name="tab_name" id="tab_name" class="form-control form-control-lg" value="{{ old('tab_name') }}" required placeholder="উদাহরণ: সকল অর্ডার">
                        </div>
                        <small class="form-text text-muted mt-1">ড্যাশবোর্ডে এই নামটিই লিংক হিসেবে দেখা যাবে।</small>
                    </div>

                    <div class="form-group mb-4">
                        <label for="tab_link" class="form-label font-weight-bold">Laravel Url Path <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-link"></i></span>
                            <input type="text" name="tab_link" id="tab_link" class="form-control form-control-lg" value="{{ old('tab_link') }}" required placeholder="উদাহরণ: admin/orders">
                        </div>
                        <small class="form-text text-muted mt-1">আপনার web.php ফাইলে সংজ্ঞায়িত সঠিক রুট নামটি লিখুন।</small>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg shadow-sm mt-3"><i class="fas fa-rocket me-1"></i> Quick Tab সেভ করুন</button>
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">ফিরে যান</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection