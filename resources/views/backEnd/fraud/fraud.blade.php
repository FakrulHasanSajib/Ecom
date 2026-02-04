@extends('backEnd.layouts.master')
@section('title','Fraud Order')
@section('css')
<link href="{{asset('backEnd')}}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('backEnd')}}/assets/css/switchery.min.css" rel="stylesheet" type="text/css" />
<style>
     .btn-custom-large {
        padding: 20px 40px; /* Increase padding */
        font-size: 24px;    /* Increase font size */
    }
</style>
@endsection
@section('content')
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <a href="{{route('brands.index')}}" class="btn btn-primary rounded-pill">Manage</a>
                </div>
                <h4 class="page-title">Fraud Order Check</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
   <div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
        <div class="card-body">
        <div id="deliveryTracker" class="container mt-5">
    <!-- Input for mobile number and submit button -->
    <div class="mt-4">
        <label for="">Number</label>
        <input type="text" id="mobileNumber" class="form-control" placeholder="Enter mobile number" />
        <button id="fetchButton" class="btn btn-primary mt-3" disabled>Fetch Info</button>
        <div id="loading" style="display:none;">Loading...</div>
        <div id="errorMessage" class="text-danger"></div>
    </div>

    <!-- Info Panel (Visible only when data exists) -->
    <div id="infoPanel" class="bg-white p-6 rounded-lg shadow-md" style="display: none;">
        <p class="text-xl font-semibold mb-4">Mobile Number: <span id="mobileNumberDisplay"></span></p>

        <!-- Result Table Design -->
        <div class="row row-cols-1 row-cols-md-2 g-4">
            <!-- Total Parcels -->
            <div class="col">
                <div class="bg-white p-4 rounded-lg shadow-sm text-center border border-gray-200 position-relative">
                    <i class="fas fa-box-open position-absolute top-0 start-0 mt-3 ms-3 text-primary" style="font-size: 2rem;"></i>
                    <p class="text-gray-500 font-semibold mb-2">Total Parcels</p>
                    <p id="totalParcels" class="text-3xl font-bold text-primary"></p>
                </div>
            </div>

            <!-- Total Delivered -->
            <div class="col">
                <div class="bg-white p-4 rounded-lg shadow-sm text-center border border-gray-200 position-relative">
                    <i class="fas fa-check-circle position-absolute top-0 start-0 mt-3 ms-3 text-success" style="font-size: 2rem;"></i>
                    <p class="text-gray-500 font-semibold mb-2">Total Delivered</p>
                    <p id="totalDelivered" class="text-3xl font-bold text-success"></p>
                </div>
            </div>

            <!-- Total Canceled -->
            <div class="col">
                <div class="bg-white p-4 rounded-lg shadow-sm text-center border border-gray-200 position-relative">
                    <i class="fas fa-times-circle position-absolute top-0 start-0 mt-3 ms-3 text-danger" style="font-size: 2rem;"></i>
                    <p class="text-gray-500 font-semibold mb-2">Total Canceled</p>
                    <p id="totalCanceled" class="text-3xl font-bold text-danger"></p>
                </div>
            </div>

            <!-- Activity Result -->
            <div class="col">
                <div class="bg-white p-4 rounded-lg shadow-sm text-center border border-gray-200 position-relative">
                    <i class="fas fa-info-circle position-absolute top-0 start-0 mt-3 ms-3 text-warning" style="font-size: 2rem;"></i>
                    <p class="text-gray-500 font-semibold mb-2">Activity Result</p>
                    <p id="activityResult" class="text-xl font-bold"></p>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
        </div> <!-- end card-->
    </div> <!-- end col-->
   </div>
</div>
@endsection


@section('script')
<script src="{{asset('backEnd/')}}/assets/libs/parsleyjs/parsley.min.js"></script>
<script src="{{asset('backEnd/')}}/assets/js/pages/form-validation.init.js"></script>
<script src="{{asset('backEnd/')}}/assets/libs/select2/js/select2.min.js"></script>
<script src="{{asset('backEnd/')}}/assets/js/pages/form-advanced.init.js"></script>
<script src="{{asset('backEnd/')}}/assets/js/switchery.min.js"></script>
<script>
   $(document).ready(function() {
    let cache = {}; // Cache the results
    let debounceTimer = null;

    // Trigger fetch when input value changes
    $('#mobileNumber').on('input', function() {
        const mobileNumber = $(this).val();
        $('#fetchButton').prop('disabled', mobileNumber.length !== 11); // Enable/Disable button based on mobile number length

        // Debounced fetch
        clearTimeout(debounceTimer);
        if (mobileNumber.length === 11) {
            debounceTimer = setTimeout(() => {
                fetchData(mobileNumber);
            }, 300);
        }
    });

    // Fetch data on button click
    $('#fetchButton').on('click', function() {
        const mobileNumber = $('#mobileNumber').val();
        if (mobileNumber.length === 11) {
            fetchData(mobileNumber);
        }
    });

    // Function to fetch delivery data
    async function fetchData(mobileNumber) {
        if (cache[mobileNumber]) {
            updateInfo(cache[mobileNumber]);
            return;
        }

        $('#loading').show();
        $('#errorMessage').text('');
        $('#infoPanel').hide();

        try {
            const response = await $.ajax({
                url: 'https://delivery.uuufoundation.xyz/api/delivery-tracker',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ phone: mobileNumber }),
                dataType: 'json',
            });

            if (response.error) {
                $('#errorMessage').text(response.error);
                updateInfo({});
            } else {
                updateInfo(response);
                cache[mobileNumber] = response; // Cache the result
            }
        } catch (error) {
            console.error('Error fetching data:', error);
            $('#errorMessage').text('Failed to fetch data. Please try again later.');
        } finally {
            $('#loading').hide();
        }
    }

    // Update the information displayed on the page
    function updateInfo(info) {
        if ($.isEmptyObject(info)) {
            $('#infoPanel').hide();
        } else {
            $('#mobileNumberDisplay').text(info.mobile_number); // Show the mobile number
            $('#totalParcels').text(info.total_parcels);
            $('#totalDelivered').text(info.total_delivered);
            $('#totalCanceled').text(info.total_cancel);
            $('#activityResult').text(getActivityMessage(info.activity_result)).addClass(getResultClass(info.activity_result));

            $('#infoPanel').show();
        }
    }

    // Get the activity message based on the result
    function getActivityMessage(result) {
        switch (result) {
            case 'Bad':
                return 'Advance payment required for future orders.';
            case 'Average':
                return 'Risk-free transactions, but be cautious.';
            case 'Good':
                return 'Parcel delivery is safe with no issues.';
            default:
                return 'No result available.';
        }
    }

    // Get result class based on the result
    function getResultClass(result) {
        switch (result) {
            case 'Good':
                return 'text-success';
            case 'Average':
                return 'text-warning';
            case 'Bad':
                return 'text-danger';
            default:
                return '';
        }
    }
});


</script>
@endsection