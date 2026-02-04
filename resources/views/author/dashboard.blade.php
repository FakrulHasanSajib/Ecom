@extends('backEnd.layouts.master')
@section('title', 'Dashboard')
@section('css')
    <!-- Plugins css -->
    <link href="{{ asset('backEnd/') }}/assets/libs/flatpickr/flatpickr.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backEnd/') }}/assets/libs/selectize/css/selectize.bootstrap3.css" rel="stylesheet"
        type="text/css" />
@endsection
@section('content')
    <!-- Start Content-->
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                    </div>
                    <h4 class="page-title">Dashboard</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

       <div class="row">
            <!-- Today Order -->
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
                                    <h2 class="text-dark m-0"><span><strong>{{ $todayOrders ?? 0 }}</strong></span></h2>
                                    <h4 class="text-muted text-truncate m-0">
                                        <span><strong>{{ number_format($totalQty ?? 0) }}</strong></span>
                                    </h4>
                                    <p class="text-muted text-truncate m-0">Today Order</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loyalty Income -->
            <div class="col-md-6 col-xl-2 px-1">
                <div class="widget-rounded-circle card ">
                    <div class="border border-2 border-success rounded">
                        <div class="row align-items-center m-0">
                            <div class="col-4 d-flex justify-content-center align-items-center m-0 p-0">
                                <svg xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 -960 960 960"
                                    width="48px" fill="currentColor" class="text-success">
                                    <path
                                        d="M320-280q17 0 28.5-11.5T360-320q0-17-11.5-28.5T320-360q-17 0-28.5 11.5T280-320q0 17 11.5 28.5T320-280Zm0-160q17 0 28.5-11.5T360-480q0-17-11.5-28.5T320-520q-17 0-28.5 11.5T280-480q0 17 11.5 28.5T320-440Zm0-160q17 0 28.5-11.5T360-640q0-17-11.5-28.5T320-680q-17 0-28.5 11.5T280-640q0 17 11.5 28.5T320-600Zm120 320h240v-80H440v80Zm0-160h240v-80H440v80Zm0-160h240v-80H440v80ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm0-560v560-560Z" />
                                </svg>
                            </div>
                            <div class="col-8 px-1">
                                <div class="text-end">
                                    <h2 class="text-dark m-0"><span><strong>{{ number_format($totalLoyalty ?? 0, 2) }}</strong></span></h2>
                                    <h4 class="text-muted text-truncate m-0">
                                        <span><strong>৳{{ number_format($totalLoyalty ?? 0, 2) }}</strong></span>
                                    </h4>
                                    <p class="text-muted text-truncate m-0">Loyalty Income</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Withdrawal -->
            <div class="col-md-6 col-xl-2 px-1">
                <div class="widget-rounded-circle card ">
                    <div class="border border-2 border-warning rounded">
                        <div class="row align-items-center m-0">
                            <div class="col-4 d-flex justify-content-center align-items-center m-0 p-0">
                                <svg xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 -960 960 960"
                                    width="48px" fill="currentColor" class="text-warning">
                                    <path
                                        d="m691-150 139-138-42-42-97 95-39-39-42 43 81 81ZM240-600h480v-80H240v80ZM720-40q-83 0-141.5-58.5T520-240q0-83 58.5-141.5T720-440q83 0 141.5 58.5T920-240q0 83-58.5 141.5T720-40ZM120-80v-680q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v267q-19-9-39-15t-41-9v-243H200v562h243q5 31 15.5 59T486-86l-6 6-60-60-60 60-60-60-60 60-60-60-60 60Zm120-200h203q3-21 9-41t15-39H240v80Zm0-160h284q38-37 88.5-58.5T720-520H240v80Zm-40 242v-562 562Z" />
                                </svg>
                            </div>
                            <div class="col-8 pe-1 ps-0">
                                <div class="text-end">
                                    <h2 class="text-dark m-0"><strong>{{ number_format($totalPaid ?? 0, 2) }}</strong></h2>
                                    <h4 class="text-muted text-truncate m-0">
                                        <span><strong>৳{{ number_format($totalPaid ?? 0, 2) }}</strong></span>
                                    </h4>
                                    <p class="text-muted text-truncate m-0">Withdrawal</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending -->
            <div class="col-md-6 col-xl-2 px-1">
                <div class="widget-rounded-circle card ">
                    <div class="border border-2 border-info rounded">
                        <div class="row align-items-center m-0">
                            <div class="col-4 d-flex justify-content-center align-items-center m-0 p-0">
                                <svg xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 -960 960 960"
                                    width="48px" fill="currentColor" class="text-info">
                                    <path
                                        d="M662-60 520-202l56-56 85 85 170-170 56 57L662-60ZM296-280l-56-56 64-64-64-64 56-56 64 64 64-64 56 56-64 64 64 64-56 56-64-64-64 64ZM200-80q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-80h80v80h320v-80h80v80h40q33 0 56.5 23.5T840-720v254l-80 81v-175H200v400h250l79 80H200Zm0-560h560v-80H200v80Zm0 0v-80 80Z" />
                                </svg>
                            </div>
                            <div class="col-8 px-1">
                                <div class="text-end">
                                    <h2 class="text-dark m-0"><span><strong>{{ $totalInvoices ?? 0 }}</strong></span></h2>
                                    <h4 class="text-muted text-truncate m-0">
                                        <span><strong>৳{{ number_format($totalInvoiceAmount ?? 0, 2) }}</strong></span>
                                    </h4>
                                    <p class="text-muted text-truncate m-0">Pending</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Balance/Due -->
            <div class="col-md-6 col-xl-2 px-1">
                <div class="widget-rounded-circle card ">
                    <div class="border border-2 border-danger rounded">
                        <div class="row align-items-center m-0">
                            <div class="col-4 d-flex justify-content-center align-items-center m-0 p-0">
                                <svg xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 -960 960 960"
                                    width="48px" fill="currentColor" class="text-danger">
                                    <path
                                        d="m480-320 56-56-63-64h167v-80H473l63-64-56-56-160 160 160 160ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h168q13-36 43.5-58t68.5-22q38 0 68.5 22t43.5 58h168q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm280-590q13 0 21.5-8.5T510-820q0-13-8.5-21.5T480-850q-13 0-21.5 8.5T450-820q0 13 8.5 21.5T480-790ZM200-200v-560 560Z" />
                                </svg>
                            </div>
                            <div class="col-8 px-1">
                                <div class="text-end">
                                    <h2 class="text-dark m-0"><span><strong>{{ number_format($dueAmount ?? 0, 2) }}</strong></span></h2>
                                    <h4 class="text-muted text-truncate m-0">
                                        <span><strong>৳{{ number_format($dueAmount ?? 0, 2) }}</strong></span>
                                    </h4>
                                    <p class="text-muted text-truncate m-0">Balance</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loyalty Details Table -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">Loyalty Summary</h4>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Total Loyalty Earned</th>
                                        <th>Total Withdrawn</th>
                                        <th>Total Advance</th>
                                        <th>Balance Due</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-success fw-bold">৳{{ number_format($totalLoyalty ?? 0, 2) }}</td>
                                        <td class="text-warning fw-bold">৳{{ number_format($totalPaid ?? 0, 2) }}</td>
                                        <td class="text-info fw-bold">৳{{ number_format($totalAdvance ?? 0, 2) }}</td>
                                        <td class="text-danger fw-bold">৳{{ number_format($dueAmount ?? 0, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div> <!-- container -->
@endsection
@section('script')
    <!-- Plugins js-->
    <script src="{{ asset('backEnd/') }}/assets/libs/flatpickr/flatpickr.min.js"></script>
    <script src="{{ asset('backEnd/') }}/assets/libs/apexcharts/apexcharts.min.js"></script>
    <script src="{{ asset('backEnd/') }}/assets/libs/selectize/js/standalone/selectize.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection