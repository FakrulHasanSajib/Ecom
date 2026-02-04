@extends('backEnd.layouts.master')
@section('title', 'Dashboard')
@section('css')
    <link href="{{ asset('public/backEnd/') }}/assets/libs/flatpickr/flatpickr.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/backEnd/') }}/assets/libs/selectize/css/selectize.bootstrap3.css" rel="stylesheet"
        type="text/css" />

@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">

                    </div>
                    <h4 class="page-title">Dashboard</h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-xl-2 px-1">
                <div class="widget-rounded-circle card ">
                    <div class="border border-2 border-primary rounded">
                        <div class="row align-items-center m-0">
                            <div class="col-4 d-flex justify-content-center align-items-center m-0 p-0">
                                <svg xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 -960 960 960"
                                    width="48px" fill="currentColor" class="text-primary">
                                    <path
                                        d="m670-140 160-100-160-100v200ZM240-600h480v-80H240v80ZM720-40q-83 0-141.5-58.5T520-240q0-83 58.5-141.5T720-440q83 0 141.5 58.5T920-240q0 83-58.5 141.5T720-40ZM120-80v-680q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v267q-19-9-39-15t-41-9v-243H200v562h243q5 31 15.5 59T486-86l-6 6-60-60-60 60-60-60-60 60-60-60-60 60Zm120-200h203q3-21 9-41t15-39H240v80Zm0-160h284q38-37 88.5-58.5T720-520H240v80Zm-40 242v-562 562Z" />
                                </svg>
                            </div>
                            <div class="col-8 px-1">
                                <div class="text-end">
                                    <h2 class="text-dark m-0"><span><strong>{{ $today_total_orders }}</strong></span></h2>
                                    <h4 class="text-muted text-truncate m-0">
                                        <span><strong>{{ number_format($today_total_amount) }}/-</strong></span>
                                    </h4>
                                    <p class="text-muted text-truncate m-0">Today Order</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-2 px-1">
                <div class="widget-rounded-circle card ">
                    <div class="border border-2 border-primary rounded">
                        <div class="row align-items-center m-0">
                            <div class="col-4 d-flex justify-content-center align-items-center m-0 p-0">
                                {{-- <svg xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 -960 960 960" width="48px" fill="currentColor" class="text-primary">
                                <path d="m691-150 139-138-42-42-97 95-39-39-42 43 81 81ZM240-600h480v-80H240v80ZM720-40q-83 0-141.5-58.5T520-240q0-83 58.5-141.5T720-440q83 0 141.5 58.5T920-240q0 83-58.5 141.5T720-40ZM120-80v-680q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v267q-19-9-39-15t-41-9v-243H200v562h243q5 31 15.5 59T486-86l-6 6-60-60-60 60-60-60-60 60-60-60-60 60Zm120-200h203q3-21 9-41t15-39H240v80Zm0-160h284q38-37 88.5-58.5T720-520H240v80Zm-40 242v-562 562Z"/>
                            </svg> --}}
                                <svg xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 -960 960 960"
                                    width="48px" fill="currentColor" class="text-primary">
                                    <path
                                        d="M320-280q17 0 28.5-11.5T360-320q0-17-11.5-28.5T320-360q-17 0-28.5 11.5T280-320q0 17 11.5 28.5T320-280Zm0-160q17 0 28.5-11.5T360-480q0-17-11.5-28.5T320-520q-17 0-28.5 11.5T280-480q0 17 11.5 28.5T320-440Zm0-160q17 0 28.5-11.5T360-640q0-17-11.5-28.5T320-680q-17 0-28.5 11.5T280-640q0 17 11.5 28.5T320-600Zm120 320h240v-80H440v80Zm0-160h240v-80H440v80Zm0-160h240v-80H440v80ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm0-560v560-560Z" />
                                </svg>
                            </div>
                            <div class="col-8 px-1">
                                <div class="text-end">
                                    <h2 class="text-dark m-0"><span><strong>{{ $yesterday_total_orders }}</strong></span>
                                    </h2>
                                    <h4 class="text-muted text-truncate m-0">
                                        <span><strong>{{ number_format($yesterday_total_amount) }}/-</strong></span>
                                    </h4>
                                    <p class="text-muted text-truncate m-0">Yesterday Order</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-2 px-1">
                <div class="widget-rounded-circle card ">
                    <div class="border border-2 border-primary rounded">
                        <div class="row align-items-center m-0">
                            <div class="col-4 d-flex justify-content-center align-items-center m-0 p-0">
                                <svg xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 -960 960 960"
                                    width="48px" fill="currentColor" class="text-primary">
                                    <path
                                        d="m691-150 139-138-42-42-97 95-39-39-42 43 81 81ZM240-600h480v-80H240v80ZM720-40q-83 0-141.5-58.5T520-240q0-83 58.5-141.5T720-440q83 0 141.5 58.5T920-240q0 83-58.5 141.5T720-40ZM120-80v-680q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v267q-19-9-39-15t-41-9v-243H200v562h243q5 31 15.5 59T486-86l-6 6-60-60-60 60-60-60-60 60-60-60-60 60Zm120-200h203q3-21 9-41t15-39H240v80Zm0-160h284q38-37 88.5-58.5T720-520H240v80Zm-40 242v-562 562Z" />
                                </svg>
                            </div>
                            <div class="col-8 pe-1 ps-0">
                                <div class="text-end">
                                    <h2 class="text-dark m-0"><strong>{{ $total_order }}</strong></h2>
                                    <h4 class="text-muted text-truncate m-0">
                                        <span><strong>{{ number_format($total_order_amount) }}/-</strong></span>
                                    </h4>
                                    <p class="text-muted text-truncate m-0">Total Order</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-2 px-1">
                <div class="widget-rounded-circle card ">
                    <div class="border border-2 border-primary rounded">
                        <div class="row align-items-center m-0">
                            <div class="col-4 d-flex justify-content-center align-items-center m-0 p-0">
                                <svg xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 -960 960 960"
                                    width="48px" fill="currentColor" class="text-primary">
                                    <path
                                        d="M662-60 520-202l56-56 85 85 170-170 56 57L662-60ZM296-280l-56-56 64-64-64-64 56-56 64 64 64-64 56 56-64 64 64 64-56 56-64-64-64 64ZM200-80q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-80h80v80h320v-80h80v80h40q33 0 56.5 23.5T840-720v254l-80 81v-175H200v400h250l79 80H200Zm0-560h560v-80H200v80Zm0 0v-80 80Z" />
                                </svg>
                            </div>
                            <div class="col-8 px-1">
                                <div class="text-end">
                                    <h2 class="text-dark m-0"><span><strong>{{ $cancelled_total_orders }}</strong></span>
                                    </h2>
                                    <h4 class="text-muted text-truncate m-0">
                                        <span><strong>{{ number_format($cancelled_total_amount) }}/-</strong></span>
                                    </h4>
                                    <p class="text-muted text-truncate m-0">Total Cancel</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-2 px-1">
                <div class="widget-rounded-circle card ">
                    <div class="border border-2 border-primary rounded">
                        <div class="row align-items-center m-0">
                            <div class="col-4 d-flex justify-content-center align-items-center m-0 p-0">
                                <svg xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 -960 960 960"
                                    width="48px" fill="currentColor" class="text-primary">
                                    <path
                                        d="m480-320 56-56-63-64h167v-80H473l63-64-56-56-160 160 160 160ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h168q13-36 43.5-58t68.5-22q38 0 68.5 22t43.5 58h168q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm280-590q13 0 21.5-8.5T510-820q0-13-8.5-21.5T480-850q-13 0-21.5 8.5T450-820q0 13 8.5 21.5T480-790ZM200-200v-560 560Z" />
                                </svg>
                            </div>
                            <div class="col-8 px-1">
                                <div class="text-end">
                                    <h2 class="text-dark m-0"><span><strong>{{ $return_total_orders }}</strong></span></h2>
                                    <h4 class="text-muted text-truncate m-0">
                                        <span><strong>{{ number_format($return_total_amount) }}/-</strong></span>
                                    </h4>
                                    <p class="text-muted text-truncate m-0">Return</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-2 px-1">
                <div class="widget-rounded-circle card ">
                    <div class="border border-2 border-primary rounded">
                        <div class="row align-items-center m-0">
                            <div class="col-4 d-flex justify-content-center align-items-center m-0 p-0">
                                <svg xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 -960 960 960"
                                    width="48px" fill="currentColor" class="text-primary">
                                    <path
                                        d="M702-480 560-622l57-56 85 85 170-170 56 57-226 226Zm-342 0q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM40-160v-112q0-34 17.5-62.5T104-378q62-31 126-46.5T360-440q66 0 130 15.5T616-378q29 15 46.5 43.5T680-272v112H40Zm80-80h480v-32q0-11-5.5-20T580-306q-54-27-109-40.5T360-360q-56 0-111 13.5T140-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T440-640q0-33-23.5-56.5T360-720q-33 0-56.5 23.5T280-640q0 33 23.5 56.5T360-560Zm0 260Zm0-340Z" />
                                </svg>
                            </div>
                            <div class="col-8 px-1">
                                <div class="text-end">
                                    <h2 class="text-dark m-0"><span><strong>{{ $total_customer }}</strong></span></h2>
                                    <h4 class="text-white text-truncate m-0" style="background-color:white">
                                        <span>50000</span>
                                    </h4>
                                    <p class="text-muted text-truncate m-0">Customer</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>




<div class="widget-rounded-circle card ">
    <div class="border border-2 border-primary rounded-4">


        <div class="row align-items-center m-0">

            {{-- **১. ফিল্টার বক্স (Filter Box) - Unique Names Added** --}}
            <form method="GET">
                <div class="row filter-box">

                    <div class="col-md-3 col-6 mb-2">
                        <label>Lifetime / Filter</label>
                        {{-- **Changed name="date_filter" to name="status_date_filter"** --}}
                        <select name="status_date_filter" class="form-control filter-input" onchange="this.form.submit()">
                            <option value="">Lifetime</option>
                            {{-- **Changed request('date_filter') to request('status_date_filter')** --}}
                            <option value="today" {{ request('status_date_filter')=='today'?'selected':'' }}>Today</option>
                            <option value="this_week" {{ request('status_date_filter')=='this_week'?'selected':'' }}>This Week</option>
                            <option value="this_month" {{ request('status_date_filter')=='this_month'?'selected':'' }}>This Month</option>
                        </select>
                    </div>

                    <div class="col-md-3 col-6 mb-2">
                        <label>Start Date</label>
                        {{-- **Changed name="start_date" to name="status_start_date"** --}}
                        <input type="date" name="status_start_date"
                            {{-- **Changed request('start_date') to request('status_start_date')** --}}
                            value="{{ request('status_start_date') }}"
                            class="form-control filter-input"
                            onchange="this.form.submit()">
                    </div>

                    <div class="col-md-3 col-6 mb-2">
                        <label>End Date</label>
                        {{-- **Changed name="end_date" to name="status_end_date"** --}}
                        <input type="date" name="status_end_date"
                            {{-- **Changed request('end_date') to request('status_end_date')** --}}
                            value="{{ request('status_end_date') }}"
                            class="form-control filter-input"
                            onchange="this.form.submit()">
                    </div>
                </div>
            </form>

            {{-- **২. ডেটা লজিক (Centralized Data Access) - এখন ডায়নামিক** --}}
            @php
                // সমস্ত স্ট্যাটাসগুলিকে কন্ট্রোলার থেকে ডায়নামিক্যালি আনা হলো
                $allStatuses = $dashboardData['allStatusLabels'] ?? [];

                // স্ট্যাটাস ডেটা সহজে অ্যাক্সেস করার জন্য একটি ফাংশন তৈরি করা হলো
                function getStatusData($label, $dashboardData) {
                    if ($label === 'All Order') {
                        return (object) [
                            'count' => $dashboardData['allOrder']['count'] ?? 0,
                            'total_value' => $dashboardData['allOrder']['value'] ?? 0,
                        ];
                    }

                    $statusId = $dashboardData['statusMap'][$label] ?? null;
                    if (!$statusId) {
                        return (object) ['count' => 0, 'total_value' => 0];
                    }

                    // Integer key lookup
                    $data = $dashboardData['statusSummary'][$statusId] ?? null;

                    // Fallback to string key lookup
                    if (!$data) {
                        $data = $dashboardData['statusSummary'][(string) $statusId] ?? null;
                    }

                    return $data ?? (object) ['count' => 0, 'total_value' => 0];
                }

                // প্রতিটি সারির জন্য স্ট্যাটাস লেবেলগুলি ডায়নামিক্যালি ভাগ করা হলো
                // প্রতি সারিতে ৬টি করে স্ট্যাটাস (আপনার পূর্বের লেআউট অনুযায়ী)
                $statusesPerRowCount = 6;

                $firstRow = array_slice($allStatuses, 0, $statusesPerRowCount);
                $secondRow = array_slice($allStatuses, $statusesPerRowCount);
            @endphp

            {{-- **৩. প্রথম সারি (First Row) - এখন ডায়নামিক** --}}
            <div class="row">
                @foreach($firstRow as $label)
                    @php
                        $data = getStatusData($label, $dashboardData);
                    @endphp
                    <div class="col-md-2 mb-3">
                        <div class="status-card">
                            <div class="status-title">{{ $label }}</div>
                            <div class="status-amount">
                                {{ $data->count ?? 0 }} <br>
                                {{ number_format($data->total_value ?? 0) }}/-
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- **৪. দ্বিতীয় সারি (Second Row) - এখন ডায়নামিক** --}}
            <div class="row">
                @foreach($secondRow as $label)
                    @php
                        $data = getStatusData($label, $dashboardData);
                    @endphp
                    {{-- যদি স্ট্যাটাস কার্ডের সংখ্যা ৬টির কম হয় তবে col-md-2 এর পরিবর্তে col-md-3/4 ব্যবহার করতে পারেন --}}
                    <div class="col-md-2 mb-3">
                        <div class="status-card">
                            <div class="status-title">{{ $label }}</div>
                            <div class="status-amount">
                                {{ $data->count ?? 0 }} <br>
                                {{ number_format($data->total_value ?? 0) }}/-
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div> {{-- end row align-items-center m-0 --}}
    </div>
</div>







<div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">

                    </div>
                    <h4 class="page-title">Wholesale Activity</h4>
                </div>
            </div>
        </div>

<div class="row">
            {{-- 1. Today Wholesale --}}
            <div class="col-md-6 col-xl-2 px-1">
                <div class="widget-rounded-circle card ">
                    <div class="border border-2 border-primary rounded">
                        <div class="row align-items-center m-0">
                            <div class="col-4 d-flex justify-content-center align-items-center m-0 p-0">
                                <svg xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 -960 960 960"
                                    width="48px" fill="currentColor" class="text-primary">
                                    <path
                                        d="m670-140 160-100-160-100v200ZM240-600h480v-80H240v80ZM720-40q-83 0-141.5-58.5T520-240q0-83 58.5-141.5T720-440q83 0 141.5 58.5T920-240q0 83-58.5 141.5T720-40ZM120-80v-680q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v267q-19-9-39-15t-41-9v-243H200v562h243q5 31 15.5 59T486-86l-6 6-60-60-60 60-60-60-60 60-60-60-60 60Zm120-200h203q3-21 9-41t15-39H240v80Zm0-160h284q38-37 88.5-58.5T720-520H240v80Zm-40 242v-562 562Z" />
                                </svg>
                            </div>
                            <div class="col-8 px-1">
                                <div class="text-end">
                                    {{-- ⭐ Wholesale Variable --}}
                                    <h2 class="text-dark m-0"><span><strong>{{ $today_wholesale_orders }}</strong></span></h2>
                                    <h4 class="text-muted text-truncate m-0">
                                        <span><strong>{{ number_format($today_wholesale_amount) }}/-</strong></span>
                                    </h4>
                                    <p class="text-muted text-truncate m-0">Today Wholesale</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Yesterday Wholesale --}}
            <div class="col-md-6 col-xl-2 px-1">
                <div class="widget-rounded-circle card ">
                    <div class="border border-2 border-primary rounded">
                        <div class="row align-items-center m-0">
                            <div class="col-4 d-flex justify-content-center align-items-center m-0 p-0">
                                <svg xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 -960 960 960"
                                    width="48px" fill="currentColor" class="text-primary">
                                    <path
                                        d="M320-280q17 0 28.5-11.5T360-320q0-17-11.5-28.5T320-360q-17 0-28.5 11.5T280-320q0 17 11.5 28.5T320-280Zm0-160q17 0 28.5-11.5T360-480q0-17-11.5-28.5T320-520q-17 0-28.5 11.5T280-480q0 17 11.5 28.5T320-440Zm0-160q17 0 28.5-11.5T360-640q0-17-11.5-28.5T320-680q-17 0-28.5 11.5T280-640q0 17 11.5 28.5T320-600Zm120 320h240v-80H440v80Zm0-160h240v-80H440v80Zm0-160h240v-80H440v80ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm0-560v560-560Z" />
                                </svg>
                            </div>
                            <div class="col-8 px-1">
                                <div class="text-end">
                                    {{-- ⭐ Wholesale Variable --}}
                                    <h2 class="text-dark m-0"><span><strong>{{ $yesterday_wholesale_orders }}</strong></span>
                                    </h2>
                                    <h4 class="text-muted text-truncate m-0">
                                        <span><strong>{{ number_format($yesterday_wholesale_amount) }}/-</strong></span>
                                    </h4>
                                    <p class="text-muted text-truncate m-0">Yesterday Wholesale</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. Total Wholesale Order --}}
            <div class="col-md-6 col-xl-2 px-1">
                <div class="widget-rounded-circle card ">
                    <div class="border border-2 border-primary rounded">
                        <div class="row align-items-center m-0">
                            <div class="col-4 d-flex justify-content-center align-items-center m-0 p-0">
                                <svg xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 -960 960 960"
                                    width="48px" fill="currentColor" class="text-primary">
                                    <path
                                        d="m691-150 139-138-42-42-97 95-39-39-42 43 81 81ZM240-600h480v-80H240v80ZM720-40q-83 0-141.5-58.5T520-240q0-83 58.5-141.5T720-440q83 0 141.5 58.5T920-240q0 83-58.5 141.5T720-40ZM120-80v-680q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v267q-19-9-39-15t-41-9v-243H200v562h243q5 31 15.5 59T486-86l-6 6-60-60-60 60-60-60-60 60-60-60-60 60Zm120-200h203q3-21 9-41t15-39H240v80Zm0-160h284q38-37 88.5-58.5T720-520H240v80Zm-40 242v-562 562Z" />
                                </svg>
                            </div>
                            <div class="col-8 pe-1 ps-0">
                                <div class="text-end">
                                    {{-- ⭐ Wholesale Variable --}}
                                    <h2 class="text-dark m-0"><strong>{{ $total_wholesale_order }}</strong></h2>
                                    <h4 class="text-muted text-truncate m-0">
                                        <span><strong>{{ number_format($total_wholesale_amount) }}/-</strong></span>
                                    </h4>
                                    <p class="text-muted text-truncate m-0">Total Wholesale</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4. Wholesale Cancelled --}}
            <div class="col-md-6 col-xl-2 px-1">
                <div class="widget-rounded-circle card ">
                    <div class="border border-2 border-primary rounded">
                        <div class="row align-items-center m-0">
                            <div class="col-4 d-flex justify-content-center align-items-center m-0 p-0">
                                <svg xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 -960 960 960"
                                    width="48px" fill="currentColor" class="text-primary">
                                    <path
                                        d="M662-60 520-202l56-56 85 85 170-170 56 57L662-60ZM296-280l-56-56 64-64-64-64 56-56 64 64 64-64 56 56-64 64 64 64-56 56-64-64-64 64ZM200-80q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-80h80v80h320v-80h80v80h40q33 0 56.5 23.5T840-720v254l-80 81v-175H200v400h250l79 80H200Zm0-560h560v-80H200v80Zm0 0v-80 80Z" />
                                </svg>
                            </div>
                            <div class="col-8 px-1">
                                <div class="text-end">
                                    {{-- ⭐ Wholesale Variable --}}
                                    <h2 class="text-dark m-0"><span><strong>{{ $cancelled_wholesale_orders }}</strong></span>
                                    </h2>
                                    <h4 class="text-muted text-truncate m-0">
                                        <span><strong>{{ number_format($cancelled_wholesale_amount) }}/-</strong></span>
                                    </h4>
                                    <p class="text-muted text-truncate m-0">Wholesale Cancel</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 5. Wholesale Return --}}
            <div class="col-md-6 col-xl-2 px-1">
                <div class="widget-rounded-circle card ">
                    <div class="border border-2 border-primary rounded">
                        <div class="row align-items-center m-0">
                            <div class="col-4 d-flex justify-content-center align-items-center m-0 p-0">
                                <svg xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 -960 960 960"
                                    width="48px" fill="currentColor" class="text-primary">
                                    <path
                                        d="m480-320 56-56-63-64h167v-80H473l63-64-56-56-160 160 160 160ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h168q13-36 43.5-58t68.5-22q38 0 68.5 22t43.5 58h168q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm280-590q13 0 21.5-8.5T510-820q0-13-8.5-21.5T480-850q-13 0-21.5 8.5T450-820q0 13 8.5 21.5T480-790ZM200-200v-560 560Z" />
                                </svg>
                            </div>
                            <div class="col-8 px-1">
                                <div class="text-end">
                                    {{-- ⭐ Wholesale Variable --}}
                                    <h2 class="text-dark m-0"><span><strong>{{ $return_wholesale_orders }}</strong></span></h2>
                                    <h4 class="text-muted text-truncate m-0">
                                        <span><strong>{{ number_format($return_wholesale_amount) }}/-</strong></span>
                                    </h4>
                                    <p class="text-muted text-truncate m-0">Wholesale Return</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 6. Customer (Note: This still shows ALL customers as per controller code) --}}
            <div class="col-md-6 col-xl-2 px-1">
                <div class="widget-rounded-circle card ">
                    <div class="border border-2 border-primary rounded">
                        <div class="row align-items-center m-0">
                            <div class="col-4 d-flex justify-content-center align-items-center m-0 p-0">
                                <svg xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 -960 960 960"
                                    width="48px" fill="currentColor" class="text-primary">
                                    <path
                                        d="M702-480 560-622l57-56 85 85 170-170 56 57-226 226Zm-342 0q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM40-160v-112q0-34 17.5-62.5T104-378q62-31 126-46.5T360-440q66 0 130 15.5T616-378q29 15 46.5 43.5T680-272v112H40Zm80-80h480v-32q0-11-5.5-20T580-306q-54-27-109-40.5T360-360q-56 0-111 13.5T140-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T440-640q0-33-23.5-56.5T360-720q-33 0-56.5 23.5T280-640q0 33 23.5 56.5T360-560Zm0 260Zm0-340Z" />
                                </svg>
                            </div>
                            <div class="col-8 px-1">
                                <div class="text-end">
                                    <h2 class="text-dark m-0"><span><strong>{{ $total_customer }}</strong></span></h2>
                                    <h4 class="text-white text-truncate m-0" style="background-color:white">
                                        <span>50000</span>
                                    </h4>
                                    <p class="text-muted text-truncate m-0">Customer</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>











<div class="col-xl-12">
    <div class="card border border-2 border-primary rounded-4">
        <div class="card-header bg-primary text-white text-center"
            style="border-top-left-radius: 0.5rem; border-top-right-radius: 0.5rem;">
            <h4 class="header-title text-white m-0">Quick Tab</h4>
        </div>
        <div class="card-body p-1">

            <div class="container p-0">
                <div class="row g-1 justify-content-center">

                    {{-- ⭐ কুইক ট্যাব ডেটা লুপ করে দেখানো হচ্ছে ⭐ --}}
                    @if(isset($quickTabs))
                        @foreach($quickTabs as $quickTab)
                            {{-- is_active চেক করা হচ্ছে --}}
                            @if($quickTab->is_active)
                                <div class="col-6 col-md-4 col-lg-2 flex-fill mt-1">
                                    {{-- URL Path ব্যবহার করার জন্য `url()` helper ব্যবহার করা হয়েছে --}}
                                    <a href="{{ url($quickTab->tab_link) }}" class="text-decoration-none">
                                        <div class="card text-center bg-primary text-white border border-2 border-primary rounded-4 m-0 h-100 d-flex align-items-center justify-content-center">
                                            <div class="card-body p-1">
                                                <div class="d-flex justify-content-center">
                                                    {{-- আইকন --}}
                                                    <svg xmlns="http://www.w3.org/2000/svg" height="48px"
                                                         viewBox="0 -960 960 960" width="48px" fill="white">
                                                        <path
                                                            d="m691-150 139-138-42-42-97 95-39-39-42 43 81 81ZM240-600h480v-80H240v80ZM720-40q-83 0-141.5-58.5T520-240q0-83 58.5-141.5T720-440q83 0 141.5 58.5T920-240q0 83-58.5 141.5T720-40ZM120-80v-680q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v267q-19-9-39-15t-41-9v-243H200v562h243q5 31 15.5 59T486-86l-6 6-60-60-60 60-60-60-60 60-60-60-60 60Zm120-200h203q3-21 9-41t15-39H240v80Zm0-160h284q38-37 88.5-58.5T720-520H240v80Zm-40 242v-562 562Z" />
                                                    </svg>
                                                </div>
                                                {{-- ট্যাবের নাম ডাইনামিকভাবে লোড করা হচ্ছে --}}
                                                <p class="text-white m-0">{{ $quickTab->tab_name }}</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endif
                        @endforeach
                    @endif
                    
                    {{-- ⭐ নতুন Quick Tab যোগ করার জন্য প্লাস বাটন (আইকন ফিক্স করা হয়েছে) ⭐ --}}
                    <div class="col-6 col-md-4 col-lg-2 flex-fill mt-1">
                        {{-- 'admin.quick_tabs.create' রুটে লিংক করা হলো --}}
                        <a href="{{ route('admin.quick_tabs.create') }}" class="text-decoration-none">
                            <div
                                class="card text-center bg-success text-white border border-2 border-success rounded-4 m-0 h-100 d-flex align-items-center justify-content-center">
                                <div class="card-body p-1">
                                    <div class="d-flex justify-content-center">
                                        {{-- ✅ ফিক্সড প্লাস আইকন (সঠিক Material Icons Path) --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 -960 960 960" width="48px" fill="white">
                                            <path d="M440-200v-240H200v-80h240V-760h80v240h240v80H520v240h-80Z"/>
                                        </svg>
                                    </div>
                                    <p class="text-white m-0">নতুন ট্যাব যোগ</p>
                                </div>
                            </div>
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>


{{-- ⭐ [নতুন বিভাগ] Facebook Activity Chart Card ⭐ --}}
<div class="col-xl-12">
    <div class="card border border-2 border-primary rounded-4">
        <div class="card-header bg-primary text-white"
            style="border-top-left-radius: 0.5rem; border-top-right-radius: 0.5rem;">
            <div class="row align-items-center">
                <div class="col-md-6">
                    
                    <p class="card-category d-inline ms-2">Facebook Ad Performance Metrics</p>
                </div>
                {{-- ⭐ ডেট ফিল্টার ফর্ম (Apply বাটন ছাড়া) ⭐ --}}
                <div class="col-md-6 text-end">
                    <form action="{{ url()->current() }}" method="GET" class="d-inline-flex align-items-center">
                        {{-- অন্যান্য ফিল্টার প্যারামিটার যদি থাকে, সেগুলোর জন্য hidden ইনপুট রাখুন --}}
                        
                        <label for="fb_date_filter" class="me-2 fw-bold text-white">Filter:</label>
                        {{-- ⭐ onchange অ্যাট্রিবিউট যুক্ত করা হয়েছে ⭐ --}}
                        <select name="fb_date_filter" id="fb_date_filter" class="form-control form-control-sm me-2" style="width: 150px;" onchange="this.form.submit()">
                            <option value="last_7_days" {{ request()->get('fb_date_filter') == 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                            <option value="today" {{ request()->get('fb_date_filter') == 'today' ? 'selected' : '' }}>Today</option>
                            <option value="yesterday" {{ request()->get('fb_date_filter') == 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                            <option value="last_3_days" {{ request()->get('fb_date_filter') == 'last_3_days' ? 'selected' : '' }}>Last 3 Days</option>
                            <option value="last_14_days" {{ request()->get('fb_date_filter') == 'last_14_days' ? 'selected' : '' }}>Last 14 Days</option>
                            <option value="last_30_days" {{ request()->get('fb_date_filter') == 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
                            <option value="this_month" {{ request()->get('fb_date_filter') == 'this_month' ? 'selected' : '' }}>This Month</option>
                            <option value="last_month" {{ request()->get('fb_date_filter') == 'last_month' ? 'selected' : '' }}>Last Month</option>
                            <option value="custom" {{ (request()->get('fb_start_date') || request()->get('fb_end_date')) ? 'selected' : '' }}>Custom Range</option>
                        </select>
                        
                        {{-- ⭐ onchange অ্যাট্রিবিউট যুক্ত করা হয়েছে ⭐ --}}
                        <input type="date" name="fb_start_date" class="form-control form-control-sm me-1" style="width: 140px;" value="{{ request()->get('fb_start_date') }}" onchange="this.form.submit()">
                        {{-- ⭐ onchange অ্যাট্রিবিউট যুক্ত করা হয়েছে ⭐ --}}
                        <input type="date" name="fb_end_date" class="form-control form-control-sm me-2" style="width: 140px;" value="{{ request()->get('fb_end_date') }}" onchange="this.form.submit()">
                        
                        {{-- Apply বাটনটি সরিয়ে দেওয়া হলো --}}
                    </form>
                </div>
            </div>
        </div>
        
        {{-- ⭐ স্ক্রল যুক্ত করা হয়েছে: max-height এবং overflow-y: auto ⭐ --}}
        <div class="table-full-width table-responsive" style="max-height: 400px; overflow-y: auto;">
            <table class="table table-striped table-hover mb-0">
                <thead>
                    <tr>
                        <th class="text-left" style="min-width: 150px;">Campaign Name</th>
                        <th class="text-left" style="min-width: 150px;">Ad Name</th>
                        <th class="text-center" style="min-width: 80px;">CPM</th>
                        <th class="text-center" style="min-width: 80px;">Frequency</th>
                        <th class="text-center" style="min-width: 80px;">CTR</th>
                        <th class="text-center" style="min-width: 100px;">Link Clicks</th>
                        <th class="text-center" style="min-width: 80px;">CPC</th>
                        <th class="text-center" style="min-width: 100px;">Purchase ROAS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($adMetrics as $ad)
                        <tr>
                            {{-- Campaign Name কলাম --}}
                            <td class="text-left">
                                @if(isset($ad['ad_name']) && $ad['ad_name'] == 'Status')
                                    <strong class="text-danger">N/A</strong>
                                @else
                                    {{ $ad['campaign_name'] ?? 'N/A' }}
                                @endif
                            </td>
                            
                            {{-- Ad Name কলাম --}}
                            <td class="text-left">
                                @if(isset($ad['ad_name']) && $ad['ad_name'] == 'Status')
                                    <strong class="text-danger">{{ $ad['cpm'] ?? 'API Error' }}</strong> 
                                @else
                                    <strong>{{ $ad['ad_name'] ?? 'N/A' }}</strong>
                                @endif
                            </td>
                            
                            {{-- বাকি মেট্রিক্সগুলি --}}
                            <td class="text-center">
                                @if(isset($ad['ad_name']) && $ad['ad_name'] == 'Status')
                                    <span class="text-danger">{{ $ad['cpm'] ?? 'N/A' }}</span>
                                @else
                                    {{ $ad['cpm'] ?? 'N/A' }}
                                @endif
                            </td>
                            <td class="text-center">{{ $ad['frequency'] ?? 'N/A' }}</td>
                            <td class="text-center">{{ $ad['ctr'] ?? 'N/A' }}</td>
                            <td class="text-center">{{ $ad['link_clicks'] ?? 'N/A' }}</td>
                            <td class="text-center">{{ $ad['cpc'] ?? 'N/A' }}</td>
                            <td class="text-center">{{ $ad['purchase_roas'] ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-danger"> 
                                নির্বাচিত সময়ের মধ্যে কোনো অ্যাড মেট্রিক্স ডেটা পাওয়া যায়নি বা কোনো অ্যাড চলছে না।
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
{{-- **[পরিবর্তন শেষ]** --}}
{{-- ⭐ [নতুন বিভাগ] Facebook Activity Chart Card End ⭐ --}}

   


<div class="col-xl-12 mt-4">
    <div class="card border border-2 border-primary rounded-4">
        <div class="card-header bg-primary text-white"
            style="border-top-left-radius: 0.5rem; border-top-right-radius: 0.5rem;">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <i class="fab fa-tiktok"></i>
                    <p class="card-category d-inline ms-2">TikTok Ad Performance Metrics</p>
                </div>
                
                {{-- ডেট ফিল্টার ফর্ম --}}
                <div class="col-md-6 text-end">
                    <form action="{{ url()->current() }}" method="GET" class="d-inline-flex align-items-center">
                        <label for="tt_date_filter" class="me-2 fw-bold text-white">Filter:</label>
                        <select name="tt_date_filter" id="tt_date_filter" class="form-control form-control-sm me-2" style="width: 150px;" onchange="this.form.submit()">
                            <option value="last_7_days" {{ request()->get('tt_date_filter') == 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                            <option value="today" {{ request()->get('tt_date_filter') == 'today' ? 'selected' : '' }}>Today</option>
                            <option value="yesterday" {{ request()->get('tt_date_filter') == 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                            <option value="last_30_days" {{ request()->get('tt_date_filter') == 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
                            <option value="this_month" {{ request()->get('tt_date_filter') == 'this_month' ? 'selected' : '' }}>This Month</option>
                            <option value="custom" {{ (request()->get('tt_start_date') || request()->get('tt_end_date')) ? 'selected' : '' }}>Custom Range</option>
                        </select>
                        
                        <input type="date" name="tt_start_date" class="form-control form-control-sm me-1" style="width: 140px;" value="{{ request()->get('tt_start_date') }}" onchange="this.form.submit()">
                        <input type="date" name="tt_end_date" class="form-control form-control-sm me-2" style="width: 140px;" value="{{ request()->get('tt_end_date') }}" onchange="this.form.submit()">
                    </form>
                </div>
            </div>
        </div>
        
        <div class="table-full-width table-responsive" style="max-height: 400px; overflow-y: auto;">
            <table class="table table-striped table-hover mb-0">
                <thead>
                    <tr>
                        <th class="text-left" style="min-width: 150px;">Campaign Name</th>
                        <th class="text-left" style="min-width: 150px;">Ad Name</th>
                        <th class="text-center">CPM</th>
                        <th class="text-center">Frequency</th>
                        <th class="text-center">CTR</th>
                        <th class="text-center">Link Clicks</th>
                        <th class="text-center">CPC</th>
                        <th class="text-center">Purchase ROAS</th>
                    </tr>
                </thead>
               <tbody>
    @forelse ($tiktokReports as $report)
        {{-- আমরা এখানে সরাসরি $report অ্যারে ব্যবহার করব, $m ভেরিয়েবল দরকার নেই --}}
        <tr>
            <td class="text-left">{{ $report['campaign_name'] ?? 'N/A' }}</td>
            <td class="text-left"><strong>{{ $report['ad_name'] ?? 'N/A' }}</strong></td>
            <td class="text-center">{{ $report['cpm'] ?? 'N/A' }}</td>
            <td class="text-center">{{ $report['frequency'] ?? '1.00' }}</td>
            <td class="text-center">{{ $report['ctr'] ?? '0%' }}</td>
            <td class="text-center">{{ $report['link_clicks'] ?? '0' }}</td>
            <td class="text-center">{{ $report['cpc'] ?? '0' }}</td>
            <td class="text-center">{{ $report['purchase_roas'] ?? '0X' }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="8" class="text-center text-danger"> 
                কোনো টিকটক অ্যাড ডাটা পাওয়া যায়নি।
            </td>
        </tr>
    @endforelse
</tbody>
            </table>
        </div>
    </div>
</div>











<div class="col-xl-12 mt-4">
    <div class="card border border-2 border-primary rounded-4 overflow-hidden">
        {{-- পার্পল হেডার সেকশন যা টিকটকের সাথে মিলবে --}}
        <div class="card-header bg-primary text-white py-3"
            style="border-top-left-radius: 0.5rem; border-top-right-radius: 0.5rem;">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <i class="fas fa-users-viewfinder"></i>
                    <p class="card-category d-inline ms-2 fw-bold text-white" style="font-size: 16px;">Visitor Real-time Activity (Time & Scroll)</p>
                </div>
            </div>
        </div>
        
        {{-- টেবিল বডি সেকশন যা টিকটকের মতো স্ক্রলেবল --}}
        <div class="table-full-width table-responsive" style="max-height: 400px; overflow-y: auto;">
            <table class="table table-striped table-hover mb-0 align-middle">
                <thead class="bg-light">
                    <tr class="text-muted">
                        <th class="ps-3" style="min-width: 150px;">Date & Time</th>
                        <th style="min-width: 250px;">Page URL</th>
                        <th class="text-center">Time Spent</th>
                        <th class="text-center" style="min-width: 180px;">Scroll Depth</th>
                        <th class="text-center pe-3">IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent_activities as $activity)
                    <tr>
                        <td class="ps-3 text-dark">
                            <strong>{{ $activity->updated_at->format('h:i:s A') }}</strong><br>
                            <small class="text-muted">{{ $activity->updated_at->diffForHumans() }}</small>
                        </td>
                        <td>
                            <a href="{{ $activity->url }}" target="_blank" title="{{ $activity->url }}" class="text-primary fw-medium">
                                {{ Str::limit($activity->url, 60) }}
                            </a>
                        </td>
                        <td class="text-center">
                            <span class="badge badge-soft-info px-2 py-1" style="font-size: 13px;">
                                <i class="far fa-clock me-1"></i> {{ gmdate("H:i:s", $activity->time_spent) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="progress progress-sm flex-grow-1 mb-0" style="height: 8px; max-width: 100px; background-color: #eee;">
                                    <div class="progress-bar bg-success" role="progressbar" 
                                         style="width: {{ $activity->scroll_depth }}%;" 
                                         aria-valuenow="{{ $activity->scroll_depth }}" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                                <span class="ms-2 fw-bold text-dark" style="font-size: 12px;">{{ $activity->scroll_depth }}%</span>
                            </div>
                        </td>
                        <td class="text-center pe-3">
                            <code class="text-muted" style="font-size: 12px;">{{ $activity->ip_address }}</code>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-danger py-5"> 
                            <i class="fas fa-info-circle me-2"></i> বর্তমানে কোনো ভিজিটর অ্যাক্টিভিটি পাওয়া যায়নি।
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>







 <div class="row row-cols-1 row-cols-xl-2 g-4 align-items-stretch mb-4">
    
    {{-- 📦 কলম ১: Report Card --}}
  <div class="col">
    <div class="card border border-2 border-primary rounded-4 h-100">
        
        {{-- Report Form and Header --}}
        <form id="reportFilterForm" action="{{ url()->current() }}" method="GET"> 
            
            {{-- ⭐ নতুন যোগ: স্ক্রল পজিশন সেভ করার জন্য হিডেন ফিল্ড --}}
            <input type="hidden" name="scroll_y" id="scrollYInput" value="{{ request('scroll_y', 0) }}">

            <div class="card-header bg-primary text-white d-flex justify-content-center position-relative"
                style="border-top-left-radius: 0.5rem; border-top-right-radius: 0.5rem;">
                <h4 class="header-title text-white m-0 text-center w-100">Report</h4>
                <div class="position-absolute end-0 top-50 translate-middle-y me-2">
                    {{-- আইডি পরিবর্তন করা হলো, যাতে জাভাস্ক্রিপ্টের সাথে মেলে --}}
                    <select id="reportDurationSelect" name="report_duration" 
                        class="form-select form-select-sm bg-light text-dark border-0">
                        
                        {{-- ডিফল্ট অপশন --}}
                        <option disabled {{ !request('report_duration') ? 'selected' : '' }}>Select Report</option>
                        
                        {{-- ⭐ পরিবর্তন: শুধুমাত্র 'report_duration' চেক করা হচ্ছে ⭐ --}}
                        <option value="daily" {{ request('report_duration') == 'daily' ? 'selected' : '' }}>Daily</option>
                        <option value="weekly" {{ request('report_duration') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                        <option value="monthly" {{ request('report_duration') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                    </select>
                </div>
            </div>
        </form>
        
        {{-- Report Table Body --}}
        <div class="card-body d-flex flex-column">
            <div class="table-responsive flex-grow-1" style="max-height: 400px; overflow-y: auto;"> 
                <table class="table table-bordered table-sm mb-0">
                    <thead class="thead-light" style="position: sticky; top: 0; background-color: #f8f9fa; z-index: 1;">
                        <tr>
                            <th>Sku</th>
                            <th>Order</th>
                            <th>Ship</th>
                            <th>Cancel</th>
                            <th>Return</th>
                            <th>Hold</th>
                            <th>Pending</th>
                            <th>Delivered</th>
                        </tr>
                    </thead>
                    {{-- আইডি পরিবর্তন করা হলো --}}
                    <tbody id="sellReportBody"> 
                        
                        @forelse ($report_data as $item)
                            <tr>
                                <td>{{ $item->SKU }}</td>
                                <td>{{ $item->OrderCount ?? 0 }}</td>
                                <td>{{ $item->Shipped ?? 0 }}</td>
                                <td>{{ $item->Cancelled ?? 0 }}</td>
                                <td>{{ $item->Returned ?? 0 }}</td>
                                <td>{{ $item->Hold ?? 0 }}</td> 
                                <td>{{ $item->Pending ?? 0 }}</td>
                                <td>{{ $item->Delivered ?? 0 }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-danger">No Report Data Found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Pagination Links --}}
            <div class="mt-3 text-center">
                @if(isset($report_data) && method_exists($report_data, 'links'))
                    {{ $report_data->withQueryString()->links('pagination::bootstrap-5') }}
                @endif
            </div>
        </div>
    </div>
</div>

    {{-- 🎁 কলম ২: Latest Customers Card (কোনো পরিবর্তন নেই) --}}
    <div class="col">
        <div class="card border border-2 border-primary rounded-4 h-100">
            <div class="card-body d-flex flex-column">
                
                <div class="d-flex justify-content-between align-items-center mb-3"> 
                    <h4 class="header-title m-0">Latest Customers</h4>

                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="javascript:void(0);" class="dropdown-item">Edit Report</a>
                            <a href="javascript:void(0);" class="dropdown-item">Export Report</a>
                            <a href="javascript:void(0);" class="dropdown-item">Action</a>
                        </div>
                    </div>
                </div>
                
                {{-- টেবিল শুরু --}}
                <div class="table-responsive flex-grow-1">
                    <table class="table table-borderless table-nowrap table-hover table-centered m-0">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>Id</th>
                                <th>Products</th>
                                <th>Invoice</th>
                                <th>Amount</th>
                                <th>Customer</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- ⭐ $latest_order এর জন্য @forelse ব্যবহার --}}
                            @forelse ($latest_order as $order)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td style="width: 36px;">
                                        <img src="{{ asset($order->product ? $order->product->image->image : '' )}}"
                                            alt="contact-img" title="contact-img" class="rounded-circle avatar-sm" />
                                    </td>
                                    <td>{{ $order->invoice_id }}</td>
                                    <td>{{ $order->amount }}</td>
                                    <td>{{ $order->customer ? $order->customer->name : '' }}</td>
                                    <td>{{ $order->order_status }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-danger">No Latest Orders Found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{-- টেবিল শেষ --}}
            </div>
        </div>
    </div> {{-- /col 2 বন্ধ --}}
</div> {{-- /row বন্ধ --}}


<div class="card border-primary border-2 rounded-4 overflow-hidden h-100">
    {{-- Header Section --}}
    <div class="card-header bg-primary text-white py-3 border-0 rounded-0">
        <h5 class="mb-0 text-center text-white">Reseller Statistics (By Thana)</h5>
    </div>
    
    {{-- Body Section with Table --}}
    <div class="card-body p-0">
        {{-- Table Responsive Wrapper with Scroll --}}
        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
            <table class="table table-hover table-striped align-middle mb-0">
                {{-- Sticky Header (স্ক্রল করলেও উপরে আটকে থাকবে) --}}
                <thead class="bg-light text-dark sticky-top" style="z-index: 1;">
                    <tr>
                        <th class="ps-3 py-3 bg-light">Thana (District)</th>
                        <th class="text-center bg-light">Total</th>
                        <th class="text-end pe-3 bg-light">Status Breakdown</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reseller_stats as $stat)
                        <tr>
                            {{-- কলাম ১: থানা ও জেলা --}}
                            <td class="ps-3">
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-dark">{{ $stat->thana->name ?? 'Unknown Thana' }}</span>
                                    <small class="text-muted" style="font-size: 11px;">
                                        {{ $stat->district->name ?? 'Unknown District' }}
                                    </small>
                                </div>
                            </td>

                            {{-- কলাম ২: মোট সংখ্যা --}}
                            <td class="text-center">
                                <span class="badge bg-dark rounded-pill px-3">{{ $stat->total }}</span>
                            </td>

                            {{-- কলাম ৩: অ্যাক্টিভ ও পেন্ডিং --}}
                            <td class="text-end pe-3">
                                <div class="d-flex flex-column align-items-end gap-1">
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success px-2" style="width: 85px;">
                                        Active: <b>{{ $stat->active_count }}</b>
                                    </span>
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-2" style="width: 85px;">
                                        Pending: <b>{{ $stat->pending_count }}</b>
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Custom Scrollbar Style --}}
<style>
    .table-responsive::-webkit-scrollbar {
        width: 5px;
    }
    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1; 
    }
    .table-responsive::-webkit-scrollbar-thumb {
        background: #bbb; 
        border-radius: 4px;
    }
    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #888; 
    }
    /* স্টিকি হেডার ফিক্স */
    thead th {
        position: sticky;
        top: 0;
        box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.1);
    }
</style>

<script>
    $(document).ready(function() {
        // Report ড্রপডাউন পরিবর্তনের ইভেন্ট সেট করা
        $('#reportDurationSelect').on('change', function(e) {
            e.preventDefault();

            var duration = $(this).val(); 
            var dateFilterData = {
                report_duration: duration,
                start_date: $('input[name="start_date"]').val(), 
                end_date: $('input[name="end_date"]').val(),
                date_filter: $('select[name="date_filter"]').val()
            };
            
            // লোডিং স্ট্যাটাস দেখানো
            $('#sellReportBody').html('<tr><td colspan="8" class="text-center text-muted py-4"><i class="fas fa-spinner fa-spin me-2"></i>Loading Report Data...</td></tr>'); 

            // AJAX রিকোয়েস্ট
            $.ajax({
                // আপনার routes/web.php তে সেট করা AJAX রুট নেম
                url: '{{ route("sell-report-ajax") }}', 
                type: 'GET',
                data: dateFilterData,
                success: function(response) {
                    if (response.html) {
                        // সার্ভার থেকে আসা HTML দিয়ে শুধুমাত্র টেবিলের বডি আপডেট করা
                        $('#sellReportBody').html(response.html);
                    }
                },
                error: function(xhr) {
                    console.error("Error loading Sell Report data:", xhr.responseText);
                    $('#sellReportBody').html('<tr><td colspan="8" class="text-center text-danger py-4">Data loading failed.</td></tr>');
                }
            });
        });
    });
</script>








            <div class="col-xl-12">
                <div class="card border border-2 border-primary rounded-4">
                    <div class="card-body">
                        <div class="dropdown float-end">

                           <select id="topSellReport" name="report_duration" class="form-select form-select-sm bg-light text-dark border-0">
                                   <option disabled>Select Report</option>
                                    <option value="daily" {{ request('topsell') == 'daily' ? 'selected' : '' }}>Daily</option>
                                    <option value="weekly" {{ request('topsell') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                    <option value="monthly" {{ request('topsell') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                </select>
                        </div>

                        <h4 class="header-title mb-3">Top sell</h4>

                        <div class="table-responsive">
                            <table class="table table-borderless table-nowrap table-hover table-centered m-0">

                                <thead class="bg-primary text-white">
                                    <tr>
                                        {{-- <th>Id</th> --}}
                                        <th>Name</th>
                                        <th>Product</th>
                                        <th>Sku</th>
                                        <th>Sels</th>
                                        <th>Revenue</th>
                                    </tr>
                                </thead>
                                <tbody id="topSellBody">
                                    @foreach ($topSell as $item)
                                    <tr>
                                        {{-- <td>
                                                <h5 class="m-0 fw-normal">{{ $loop->iteration }}</h5>
                                            </td> --}}

                                        <td>
                                            {{ $loop->iteration }}.{{ $item->product_name }}
                                        </td>

                                        <td>
                                            {{-- <img src="{{$item->image}}" alt="p" srcset="" width="50"> --}}
                                            <img src="{{ asset($item->image) }}" width="25" />

                                        </td>

                                        <td>
                                            T-{{ $item->SKU }}
                                        </td>

                                        <td>
                                            {{ $item->PurchaseQty }}
                                        </td>
                                        <td>
                                            {{ $item->Revenue }}&#x09F3;
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div> </div>
                </div> </div>
        </div> <div class="row">
            <div class="col-xl-12">
                <div class="card border border-2 border-primary rounded-4">
                    <div class="card-body">
                        <div class="dropdown float-end">
                            <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="mdi mdi-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="javascript:void(0);" class="dropdown-item">Edit Report</a>
                                <a href="javascript:void(0);" class="dropdown-item">Export Report</a>
                                <a href="javascript:void(0);" class="dropdown-item">Action</a>
                            </div>
                        </div>

                        <h4 class="header-title mb-3">Top 10 Traffic Viewer</h4>

                        <div class="table-responsive">
                            <table class="table table-borderless table-nowrap table-hover table-centered m-0">

                                <thead class="bg-primary text-white">
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($latest_customer as $customer)
                                        <tr>
                                            <td>
                                                <h5 class="m-0 fw-normal">{{ $loop->iteration }}</h5>
                                            </td>

                                            <td>
                                                {{ $customer->name }}
                                            </td>

                                            <td>
                                                {{ $customer->phone }}
                                            </td>

                                            <td>
                                                {{ $customer->created_at->format('d-m-Y') }}
                                            </td>

                                            <td>
                                                {{ $customer->status }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div> </div>
                </div> </div> </div>

        </div> @endsection
@section('script')
    <script src="{{ asset('public/backEnd/') }}/assets/libs/flatpickr/flatpickr.min.js"></script>
    <script src="{{ asset('public/backEnd/') }}/assets/libs/apexcharts/apexcharts.min.js"></script>
    <script src="{{ asset('public/backEnd/') }}/assets/libs/selectize/js/standalone/selectize.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        var colors = ["#f1556c"],
            dataColors = $("#total-revenue").data("colors");
        dataColors && (colors = dataColors.split(","));
        var options = {

                chart: {
                    height: 242,
                    type: "radialBar"
                },
                plotOptions: {
                    radialBar: {
                        hollow: {
                            size: "65%"
                        }
                    }
                },
                colors: colors,
                labels: ["Delivery"]
            },
            chart = new ApexCharts(document.querySelector("#total-revenue"), options);
        chart.render();
        colors = ["#1abc9c", "#4a81d4"];
        (dataColors = $("#sales-analytics").data("colors")) && (colors = dataColors.split(","));
        options = {
            series: [{
                name: "Revenue",
                type: "column",
                data: [
                    @foreach ($monthly_sale as $sale)
                        {{ $sale->amount }},
                    @endforeach
                ]
            }, {
                name: "Sales",
                type: "line",
                data: [
                    @foreach ($monthly_sale as $sale)
                        {{ $sale->amount }},
                    @endforeach
                ]
            }],
            chart: {
                height: 378,
                type: "line",
            },
            stroke: {
                width: [2, 3]
            },
            plotOptions: {
                bar: {
                    columnWidth: "50%"
                }
            },
            colors: colors,
            dataLabels: {
                enabled: !0,
                enabledOnSeries: [1]
            },
            labels: [
                @foreach ($monthly_sale as $sale)
                    {{ date('d', strtotime($sale->date)) }} + '-' + {{ date('m', strtotime($sale->date)) }} +
                        '-' + {{ date('Y', strtotime($sale->date)) }},
                @endforeach
            ],
            legend: {
                offsetY: 7
            },
            grid: {
                padding: {
                    bottom: 20
                }
            },
            fill: {
                type: "gradient",
                gradient: {
                    shade: "light",
                    type: "horizontal",
                    shadeIntensity: .25,
                    gradientToColors: void 0,
                    inverseColors: !0,
                    opacityFrom: .75,
                    opacityTo: .75,
                    stops: [0, 0, 0]
                }
            },
            yaxis: [{
                title: {
                    text: "Net Revenue"
                }
            }]
        };
        (chart = new ApexCharts(document.querySelector("#sales-analytics"), options)).render(), $("#dash-daterange")
            .flatpickr({
                altInput: !0,
                mode: "range",
            });


        const ctx = document.getElementById('orderChart').getContext('2d');
        const orderChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5', 'Day 6', 'Day 7'],
                datasets: [{
                        label: 'Confirm',
                        data: [80, 90, 30, 100, 85, 75, 95],
                        backgroundColor: 'blue',
                        borderWidth: 1
                    },
                    {
                        label: 'Cancelled',
                        data: [10, 12, 8, 15, 10, 14, 11],
                        backgroundColor: 'orange',
                        borderWidth: 1
                    },
                    {
                        label: 'Return',
                        data: [5, 7, 6, 9, 8, 5, 7],
                        backgroundColor: 'red',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

{{-- <script>
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('topSellReport').addEventListener('change', function () {
        let filter = this.value;

        // Redirect with query parameter
        window.location.href = window.location.pathname + '?topsell=' + filter;
    });
});
</script> --}}



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
      var baseurl="{{url('/')}}";

        $(function(){
         $('#topSellReport').on('change', function() {
            let filter = $(this).val();
            $.ajax({
                url: "{{ route('topSellReport') }}", // Route you define in web.php
                type: 'GET',
                data: {
                    topsell: filter
                },
                success: function(response) {
                    // let data= JSON.parse(response.topSell)

                   console.log(response.topSell);


                  let html=``;
                  response.topSell.forEach((s,i)=>{
                    const imagePath = s.image.replace(/^public\//, '');
                    const fullImageUrl = `${baseurl}/public/${imagePath}`;
                     html+=`
                     <tr>
                         <td>
                            ${s.product_name}
                         </td>
                         <td>
                           <img src="${fullImageUrl}" alt="${s.product_name}" style="width: 30px; height: auto;">

                         </td>
                         <td>
                            ${s.SKU}
                         </td>
                         <td>
                            ${s.PurchaseQty}
                         </td>
                         <td>
                            ${s.Revenue} &#x09F3
                         </td>
                    </tr>
                     `
                  });

                  $("#topSellBody").html(html);
                }
            });
         });

         $('#reportFilter').on('change', function() {
            let filter = $(this).val();
            $.ajax({
                url: "{{ route('sellReport') }}", // Route you define in web.php
                type: 'GET',
                data: {
                    sellReport: filter
                },
                success: function(response) {

                   console.log(response.sellReport);

                  let html=``;
                  response.sellReport.forEach((s,i)=>{
                     html+=`
                     <tr>
                        <td>${s.SKU}</td>
                        <td>${s.OrderCount}</td>
                        <td>${s.Shipped}</td>
                        <td>${s.Cancelled}</td>
                        <td>${s.Returned}</td>
                        <td>${s.Hold}</td>
                        <td>${s.Pending}</td>
                        <td>${s.Delivered}</td>
                     </tr>
                     `
                  });
                   console.log(html);
                  $("#reportBody").html(html);
                }
            });
         });

           })



    </script>
    
    {{-- ⭐ [নতুন যুক্ত] Facebook Activity Chart Logic ⭐ --}}
    @if(isset($facebookActivityData) && !$facebookActivityData['error'])
    <script>
        // Controller থেকে পাঠানো JSON ডেটা Parsed হচ্ছে
        const facebookLabels = JSON.parse('{!! $facebookActivityData['labels'] !!}');
        const facebookPurchaseData = JSON.parse('{!! $facebookActivityData['data'] !!}');

        if (document.getElementById('facebookActivityChart')) {
            const facebookCtx = document.getElementById('facebookActivityChart').getContext('2d');
            new Chart(facebookCtx, {
                type: 'line',
                data: {
                    labels: facebookLabels,
                    datasets: [{
                        label: 'দৈনিক ক্রয় সংখ্যা (Facebook CAPI + Pixel)',
                        data: facebookPurchaseData,
                        borderColor: '#1877F2', // Facebook Blue কালার
                        backgroundColor: 'rgba(24, 119, 242, 0.3)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Purchases'
                            }
                        }
                    }
                }
            });
        }
    </script>
    @endif
    {{-- ⭐ [নতুন যুক্ত] Facebook Activity Chart Logic End ⭐ --}}
@endsection