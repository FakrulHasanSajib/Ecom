<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>@yield('title') | {{ $generalsetting->name }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" href="{{ asset($generalsetting->favicon) }}">

    <link href="{{ asset('public/backEnd/assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/backEnd/assets/css/app.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/backEnd/assets/css/icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/backEnd/assets/css/toastr.min.css') }}" rel="stylesheet">
    
    {{-- Select2 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    @yield('styles') {{-- চাইল্ড পেজের CSS এখানে লোড হবে --}}
</head>

<body class="loading">

    {{-- 
       আগে এখানে <div class="container"> ছিল যা পেজ ছোট করে ফেলত।
       এখন সেটি নেই, তাই পেজটি ১০০% উইডথ পাবে।
    --}}
    
    @yield('content')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('public/backEnd/assets/js/toastr.min.js') }}"></script>
    {!! Toastr::message() !!}

    @yield('scripts') {{-- চাইল্ড পেজের স্ক্রিপ্ট --}}

</body>
</html>