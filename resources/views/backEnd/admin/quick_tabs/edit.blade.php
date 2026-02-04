@extends('backEnd.layouts.master') {{-- আপনার create ফাইলে backEnd.layouts.master ছিল --}}
@section('title', 'Update Quick Tab ')
@section('content')
<div class="row">
    <div class="col-lg-8 offset-lg-2">
        <div class="card shadow-lg mb-4">
            <div class="card-header bg-warning text-dark">
                {{-- এখানে ধরে নিচ্ছি quickTab মডেলে 'tab_name' আছে --}}
                <h4 class="m-0">Update Quick Tab: {{ $quickTab->tab_name }}</h4> 
            </div>
            <div class="card-body">
                                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                                <form action="{{ route('admin.quick_tabs.update', $quickTab->id) }}" method="POST">
                    @csrf
                    @method('PUT') {{-- রিসোর্স রুটের জন্য PUT মেথড ব্যবহার করা --}}
                    
                                        <div class="form-group mb-3">
                        <label for="tab_name">শর্টকাট-এর নাম <span class="text-danger">*</span></label>
                        {{-- create ফাইলের সাথে মিল রেখে 'tab_name' ব্যবহার করা হয়েছে --}}
                        <input type="text" name="tab_name" id="tab_name" class="form-control" 
                            value="{{ old('tab_name', $quickTab->tab_name) }}" 
                            required placeholder="উদাহরণ: সকল অর্ডার">
                        <small class="form-text text-muted">ড্যাশবোর্ডে এই নামটিই লিংক হিসেবে দেখা যাবে।</small>
                    </div>
                    
                                        <div class="form-group mb-3">
                        <label for="tab_link">Laravel Url Path <span class="text-danger">*</span></label>
                        {{-- create ফাইলের সাথে মিল রেখে 'tab_link' ব্যবহার করা হয়েছে --}}
                        <input type="text" name="tab_link" id="tab_link" class="form-control" 
                            value="{{ old('tab_link', $quickTab->tab_link) }}" 
                            required placeholder="উদাহরণ: admin/orders">
                        <small class="form-text text-muted">আপনার web.php ফাইলে সংজ্ঞায়িত সঠিক রুট নামটি লিখুন।</small>
                    </div>

                    {{-- **ঐচ্ছিক:** যদি আপনি এই Quick Tab-এ অতিরিক্ত প্যারামিটার বা অর্ডার চান, তবে নিচের অংশটি রাখতে পারেন। --}}
                    
                                        <div class="form-group mb-4">
                        <label for="order">ক্রম (Order By)</label>
                        <input type="number" name="order" id="order" class="form-control" 
                            value="{{ old('order', $quickTab->order ?? '') }}" {{-- null coalescing operator ব্যবহার করা হয়েছে --}}
                            placeholder="ক্রমিক সংখ্যা">
                        <small class="form-text text-muted">ছোট সংখ্যা আগে দেখাবে।</small>
                    </div>
                    
                    <button type="submit" class="btn btn-success btn-block"><i class="fa fa-sync"></i> Quick Tab আপডেট করুন</button>
                    <a href="{{ route('admin.quick_tabs.index') }}" class="btn btn-secondary btn-block mt-2">ফিরে যান</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection