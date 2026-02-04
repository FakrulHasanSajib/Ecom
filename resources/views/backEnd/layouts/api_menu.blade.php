<div class="row justify-content-center mb-4 mt-2">
    <div class="col-xl-10 col-lg-12">
        {{-- সাদা ও গোল (Rounded) ব্যাকগ্রাউন্ড --}}
        <div class="card shadow-sm border-0" style="border-radius: 50px;">
            <div class="card-body p-2">
                
                <nav class="nav nav-pills nav-fill">
                    
                    {{-- 1. Payment Gateway --}}
                    @if (auth()->user()->can('payment-gateway'))
                    <a href="{{ route('paymentgeteway.manage') }}" 
                       class="nav-link fw-bold {{ request()->routeIs('paymentgeteway.manage') ? 'active rounded-pill' : 'text-dark' }}">
                       <i data-feather="credit-card" class="icon-dual me-1" style="height: 16px; width: 16px;"></i> Payment
                    </a>
                    @endif

                    {{-- 2. SMS Gateway --}}
                    @if (auth()->user()->can('sms-gateway'))
                    <a href="{{ route('smsgeteway.manage') }}" 
                       class="nav-link fw-bold {{ request()->routeIs('smsgeteway.manage') ? 'active rounded-pill' : 'text-dark' }}">
                       <i data-feather="message-square" class="icon-dual me-1" style="height: 16px; width: 16px;"></i> SMS
                    </a>
                    @endif

                    {{-- 3. Courier API --}}
                    @if (auth()->user()->can('courier-api'))
                    <a href="{{ route('courierapi.manage') }}" 
                       class="nav-link fw-bold {{ request()->routeIs('courierapi.manage') ? 'active rounded-pill' : 'text-dark' }}">
                       <i data-feather="truck" class="icon-dual me-1" style="height: 16px; width: 16px;"></i> Courier
                    </a>
                    @endif

                    {{-- 4. Tiktok Pixels --}}
                    @if (auth()->user()->can('tiktok-pixel-setting'))
                    <a href="{{ route('tiktok_pixels.index') }}" 
                       class="nav-link fw-bold {{ request()->routeIs('tiktok_pixels.*') ? 'active rounded-pill' : 'text-dark' }}">
                       <i data-feather="video" class="icon-dual me-1" style="height: 16px; width: 16px;"></i> Tiktok
                    </a>
                    @endif

                    {{-- 5. Google Tag Manager --}}
                    @if (auth()->user()->can('tag-manager'))
                    <a href="{{ route('tagmanagers.index') }}" 
                       class="nav-link fw-bold {{ request()->routeIs('tagmanagers.*') ? 'active rounded-pill' : 'text-dark' }}">
                       <i data-feather="tag" class="icon-dual me-1" style="height: 16px; width: 16px;"></i> GTM
                    </a>
                    @endif

                    {{-- 6. Facebook Pixel --}}
                    @if (auth()->user()->can('pixel-setting'))
                    <a href="{{ route('pixels.index') }}" 
                       class="nav-link fw-bold {{ request()->routeIs('pixels.*') ? 'active rounded-pill' : 'text-dark' }}">
                       <i data-feather="activity" class="icon-dual me-1" style="height: 16px; width: 16px;"></i> Pixel
                    </a>
                    @endif

                </nav>
            </div>
        </div>
    </div>
</div>