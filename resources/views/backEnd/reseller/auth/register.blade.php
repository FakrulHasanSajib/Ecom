@extends('backEnd.reseller.auth.layout')

@section('title', 'Reseller Registration')

@section('styles')
{{-- Tailwind CSS --}}
<script src="https://cdn.tailwindcss.com"></script>
{{-- Fonts --}}
<link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    /* ১. বডি এবং ফন্ট */
    body { font-family: 'Hind Siliguri', sans-serif; }

    /* ২. মেইন ব্যাকগ্রাউন্ড */
    .full-screen-bg {
        background-color: #111827;
        background-image: radial-gradient(at 0% 0%, hsla(253,16%,7%,1) 0, transparent 50%), radial-gradient(at 50% 0%, hsla(225,39%,30%,1) 0, transparent 50%), radial-gradient(at 100% 0%, hsla(339,49%,30%,1) 0, transparent 50%);
        min-height: 100vh;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* ৩. ফর্ম কার্ড ডিজাইন */
    .form-card {
        background: linear-gradient(180deg, #1e3a8a 0%, #1e293b 100%);
        border: 2px solid #3b82f6;
        border-radius: 20px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5);
        max-height: 98vh; 
        padding: 20px;
        overflow-y: auto; /* কন্টেন্ট বেশি হলে স্ক্রল হবে */
    }
    .form-card::-webkit-scrollbar { width: 0; } /* স্ক্রলবার লুকানো */

    /* ৪. ইনপুট ডিজাইন (সাদা ও গোল) */
    .custom-input {
        width: 100%; border-radius: 9999px; padding: 10px 20px;
        border: none; outline: none; font-size: 15px; color: #000;
        background: white; font-weight: 500; height: 45px;
    }

    /* ৫. Select2 ফিক্স (ড্রপডাউন গোল এবং সুন্দর করার জন্য) */
    .select2-container { width: 100% !important; display: block; }
    
    .select2-container--default .select2-selection--single {
        background-color: #fff !important;
        border: none !important;
        border-radius: 50px !important; /* গোল করা */
        height: 45px !important;
        display: flex !important;
        align-items: center !important;
        outline: none !important;
        padding-left: 0 !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #000 !important;
        line-height: 45px !important;
        padding-left: 20px !important;
        font-weight: 500 !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 43px !important;
        top: 1px !important;
        right: 15px !important;
    }

    /* ড্রপডাউন মেনু ফিক্স */
    .select2-dropdown {
        background-color: white !important;
        border-radius: 15px !important;
        border: 1px solid #ddd !important;
        overflow: hidden !important;
        z-index: 99999 !important;
    }
    .select2-results__option {
        color: black !important;
    }

    /* ৬. লেবেল কালার */
    .lbl-yellow { color: #facc15; font-weight: 600; margin-bottom: 4px; display: block; margin-left: 15px; font-size: 14px; }
    
    /* ৭. রেডিও বাটন ডিজাইন */
    .radio-hidden { display: none; }
    .radio-label {
        background-color: #22c55e; color: white; padding: 5px 15px;
        border-radius: 9999px; cursor: pointer; display: flex; align-items: center; gap: 5px; font-weight: bold; font-size: 14px;
    }
    .radio-hidden:checked + .radio-label { background-color: #15803d; border: 2px solid #facc15; }
    .dot { width: 10px; height: 10px; background: #facc15; border-radius: 50%; }
</style>
@endsection

@section('content')
<div class="full-screen-bg p-4">
    <div class="container mx-auto max-w-7xl grid grid-cols-1 lg:grid-cols-2 gap-10 items-center w-full">
        
        <div class="hidden lg:block pl-10">
            <div class="mb-8">
                 <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-16 mb-4">
            </div>

            <h1 class="text-6xl font-bold text-white leading-tight mb-4">নতুন একাউন্ট <br> খুলুন</h1>
            {{--<p class="text-gray-300 text-lg mb-8">আগের একাউন্টে আছ? তাহলে <a href="{{ route('login') }}" class="text-yellow-400 font-bold hover:underline">লগিন করুন</a></p>--}}
            <div class="w-24 h-1 bg-white rounded-full mb-8"></div>
            <p class="text-gray-400 max-w-md mb-8">আপনি আমাদের একজন মেম্বার হতে চলেছেন। আপনি আপনার সঠিক তথ্য দিয়ে পাশের ফর্ম পূরণ করুন</p>

            <button class="bg-yellow-400 text-black font-extrabold px-8 py-3 rounded-full text-xl shadow-lg transform -skew-x-6 hover:scale-105 transition">
                ইনকাম করুন আন লিমিটেড
            </button>
        </div>

        <div class="flex justify-center lg:justify-end w-full">
            <div class="form-card w-full max-w-md p-8 relative">
                <h2 class="text-center text-3xl font-bold text-yellow-400 mb-6">ফরম ফিলাপ করুন</h2>

                <form method="POST" action="{{ route('reseller.register.submit') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="dealer_id" value="{{ session('dealer_referral_id') }}">

                    <div>
                        <span class="lbl-yellow">আপনার নাম:</span>
                        <input type="text" name="name" class="custom-input" placeholder="আপনার ফুল নাম লিখুন..." required>
                    </div>

                    <div>
                        <span class="lbl-yellow">আপনার ফোন :</span>
                        <input type="text" name="phone" class="custom-input" placeholder="১১ সংখ্যার ফোন নাম্বার লিখুন.." required>
                    </div>

                    <div>
                        <span class="lbl-yellow">আপনার ঠিকানা:</span>
                        <input type="text" name="address" class="custom-input" placeholder="আপনার ফুল ঠিকানা লিখুন..." required>
                    </div>

                    <div>
                        <span class="lbl-yellow">আপনার জেলা:</span>
                        <select name="district_id" id="district_id" class="select2" required>
                            <option value="">জেলা সিলেক্ট করুন</option>
                            @foreach($districts as $district)
                                <option value="{{ $district->id }}" data-name="{{ $district->district }}">{{ $district->district }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <span class="lbl-yellow">আপনার থানা:</span>
                        <select name="thana_id" id="thana_id" class="select2" required>
                            <option value="">থানা সিলেক্ট করুন</option>
                        </select>
                    </div>

                    <div>
                        <span class="lbl-yellow">দোকানের / মসজিদের নাম:</span>
                        <input type="text" name="store_name" class="custom-input" placeholder="নাম লিখুন..." required>
                    </div>

                    <div class="hidden">
                        <input type="email" name="email" value="user{{ time() }}@mail.com">
                        <input type="password" name="password" value="12345678">
                    </div>

                    <div class="flex flex-wrap gap-2 justify-center mt-4">
                        <label><input type="radio" name="user_role" value="reseller" class="radio-hidden" checked><span class="radio-label"><span class="dot"></span>দোকান</span></label>
                        <label><input type="radio" name="user_role" value="librarian" class="radio-hidden"><span class="radio-label"><span class="dot"></span>খাদেম</span></label>
                        <label><input type="radio" name="user_role" value="agent" class="radio-hidden"><span class="radio-label"><span class="dot"></span>এজেন্ট</span></label>
                        <label><input type="radio" name="user_role" value="other" class="radio-hidden"><span class="radio-label"><span class="dot"></span>অন্যান্য</span></label>
                    </div>

                    <div class="text-center mt-6">
                        <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-full shadow-lg text-xl transition">কনফর্ম করুন</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
{{-- Select2 JS --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    const allLocations = @json($all_locations);

    $(document).ready(function() {
        // Select2 Initialize 
        $('.select2').select2({
            width: '100%',
            dropdownParent: $('body')
        });

        // জেলা পরিবর্তন লজিক
        $('#district_id').on('change', function () {
            let selectedOption = $(this).find(':selected');
            let districtName = selectedOption.data('name');
            let thanaDropdown = $('#thana_id');
            thanaDropdown.empty().append('<option value="">থানা সিলেক্ট করুন</option>');

            if (districtName) {
                let filteredThanas = allLocations.filter(function(item) {
                    return item.district === districtName;
                });

                filteredThanas.forEach(function(item) {
                    let nameToShow = item.area_name || item.name; 
                    let newOption = new Option(nameToShow, item.id, false, false);
                    thanaDropdown.append(newOption);
                });

                thanaDropdown.trigger('change'); 
            }
        });
    });
</script>
@endsection