@extends('backEnd.reseller.auth.layout')

@section('title', 'Reseller Login')

@section('styles')
{{-- Tailwind CSS --}}
<script src="https://cdn.tailwindcss.com"></script>
{{-- Fonts --}}
<link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    /* ১. ফন্ট ফ্যামিলি */
    body { font-family: 'Hind Siliguri', sans-serif; }

    /* ২. ব্যাকগ্রাউন্ড ডিজাইন (রেজিস্টার পেজের মতো) */
    .full-screen-bg {
        background-color: #111827;
        background-image: radial-gradient(at 0% 0%, hsla(253,16%,7%,1) 0, transparent 50%), radial-gradient(at 50% 0%, hsla(225,39%,30%,1) 0, transparent 50%), radial-gradient(at 100% 0%, hsla(339,49%,30%,1) 0, transparent 50%);
        min-height: 100vh;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* ৩. কার্ড ডিজাইন */
    .form-card {
        background: linear-gradient(180deg, #1e3a8a 0%, #1e293b 100%);
        border: 2px solid #3b82f6;
        border-radius: 20px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5);
        padding: 40px;
        width: 100%;
        max-width: 450px;
    }

    /* ৪. ইনপুট ডিজাইন */
    .custom-input {
        width: 100%; border-radius: 9999px; padding: 12px 25px;
        border: none; outline: none; font-size: 16px; color: #000;
        background: white; font-weight: 500; height: 50px;
        transition: all 0.3s ease;
    }
    .custom-input:focus {
        box-shadow: 0 0 0 4px rgba(250, 204, 21, 0.5); /* ফোকাস করলে হলুদ গ্লো */
    }

    /* ৫. লেবেল ডিজাইন */
    .lbl-yellow { color: #facc15; font-weight: 600; margin-bottom: 8px; display: block; margin-left: 15px; font-size: 15px; }

    /* ৬. বাটন ডিজাইন */
    .btn-login {
        background: linear-gradient(to right, #f59e0b, #d97706);
        color: white; font-weight: bold; font-size: 18px;
        padding: 12px; border-radius: 9999px; width: 100%;
        transition: transform 0.2s;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
    }
    .btn-login:hover { transform: scale(1.05); }

</style>
@endsection

@section('content')
<div class="full-screen-bg p-4">
    <div class="container mx-auto max-w-6xl grid grid-cols-1 lg:grid-cols-2 gap-10 items-center w-full">
        
        {{-- বাম পাশের টেক্সট এরিয়া (ডেস্কটপে দেখাবে) --}}
        <div class="hidden lg:block pl-10">
            <div class="mb-8">
                 <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-20 mb-6 drop-shadow-lg">
            </div>

            <h1 class="text-6xl font-bold text-white leading-tight mb-4">স্বাগতম <br> ফিরে আসার জন্য</h1>
            <p class="text-gray-300 text-lg mb-8">আপনার ড্যাশবোর্ডে প্রবেশ করতে লগিন করুন এবং আপনার ব্যবসা পরিচালনা করুন।</p>
            <div class="w-24 h-1 bg-yellow-400 rounded-full mb-8"></div>
            
            <div class="flex items-center gap-4 text-gray-400">
                <div class="bg-gray-800 p-3 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                </div>
                <p>সুরক্ষিত এবং নিরাপদ লগিন ব্যবস্থা</p>
            </div>
        </div>

        {{-- ডান পাশের লগিন ফর্ম --}}
        <div class="flex justify-center lg:justify-end w-full">
            <div class="form-card relative">
                
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-white">লগিন করুন</h2>
                    <p class="text-gray-300 text-sm mt-2">আপনার রিসেলার একাউন্টে প্রবেশ করুন</p>
                </div>

                <form action="{{ route('reseller.login.submit') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div>
                        <span class="lbl-yellow">ইমেইল / ইউজার আইডি:</span>
                        <input type="email" name="email" class="custom-input" placeholder="আপনার ইমেইল লিখুন..." required>
                    </div>

                    <div>
                        <span class="lbl-yellow">পাসওয়ার্ড:</span>
                        <input type="password" name="password" class="custom-input" placeholder="পাসওয়ার্ড লিখুন..." required>
                    </div>

                    <div class="text-center pt-2">
                        <button type="submit" class="btn-login">
                            প্রবেশ করুন
                        </button>
                    </div>
                </form>

                <div class="mt-8 text-center border-t border-gray-600 pt-6">
                    <p class="text-gray-300">এখনও একাউন্ট নেই?</p>
                    <a href="{{ route('reseller.register') }}" class="text-yellow-400 font-bold hover:text-white transition text-lg mt-1 inline-block">
                        নতুন একাউন্ট খুলুন &rarr;
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection