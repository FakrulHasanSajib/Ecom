@extends('backEnd.layouts.master')
@section('title','Fraud Order')
@section('css')
<link href="{{asset('backEnd')}}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('backEnd')}}/assets/css/switchery.min.css" rel="stylesheet" type="text/css" />
<style>
    .table table-bordered{
            background: linear-gradient(45deg, #007bff, #6610f2);
                    color: white;
                    padding: 15px;
                    text-align: center;
                    font-size: 28px;
                    font-weight: bold;
                    border-radius: 10px 10px 0 0;
                    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .header {
            background: linear-gradient(45deg, #007bff, #6610f2);
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            border-radius: 10px 10px 0 0;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }



        .card {
            width: 90%;
            max-width: 800px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .card-body {
            padding: 20px;
        }

        .check-btn {
            background: linear-gradient(45deg, #17a2b8, #20c997);
            color: white;
            font-size: 18px;
            font-weight: bold;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.3s;
        }

        .check-btn:hover {
            background: #138496;
            transform: scale(1.05);
        }

        .thead-blue {
            background-color: #007bff;
            color: black;
        }

        .total-row {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .total-row-custom {
            background-color: #f76c6c !important;
            color: white;
        }

        .progress-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-top: 20px;
        }

        .progress-label {
            font-weight: bold;
            color: #555;
        }

        .success-bar, .fail-bar {
            height: 30px;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            line-height: 30px;
            color: white;
        }

        .success-bar {
            background-color: #28a745;
        }

        .fail-bar {
            background-color: #dc3545;
        }

        @media (max-width: 768px) {
            .header {
                font-size: 24px;
                padding: 10px;
            }
            .table table-bordered{
                font-size: 24px;
                padding: 10px;
            }

            .check-btn {
                font-size: 16px;
            }

            .progress-container {
                flex-direction: column;
                align-items: center;
                gap: 10px;
            }

            .progress-label {
                margin-bottom: 5px;
            }

            .progress-container > div {
                width: 100%;
            }

            .text-center {
                text-align: center;
            }

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

    <div class="card">
    <div class="header">Courier Report Checker</div>
    <div class="card-body">
        <form id="statusForm">
            <div class="form-group">
                <label for="phone-number">Enter Phone Number</label>
                <input type="text" id="mobile_number" name="mobile_number" class="form-control" placeholder="e.g., 013xxxxxxxx">
            </div>
            <div class="text-center mt-4">
                <button type="button" id="btn-check" class="btn check-btn">Check Report</button>
            </div>
        </form>
    </div>
</div>

<!-- Card for Courier List -->
<div class="card" style="margin-bottom:0 ;">

    <div class="card-body">
        <table class="table table-bordered text-center" id="courierTable">
            <thead class="thead-blue">
                <tr>
                    <th>Courier List</th>
                    <th>Total Orders</th>
                    <th>Delivered</th>
                    <th>Cancelled</th>
                    <th>Success Rate</th>
                </tr>
            </thead>
            <tbody id="courierTableBody">
                
            </tbody>
        </table>

    </div>
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
 $(document).ready(function () {
    $('#btn-check').on('click', function () {
        var phoneNumber = $('#mobile_number').val();

        if (!phoneNumber) {
            alert("Please enter a phone number");
            return;
        }

        $.ajax({
            url: '/admin/fraud-check/combined',
            type: 'POST',
            data: {
                number: phoneNumber,
                _token: '{{ csrf_token() }}' // Make sure CSRF token is available
            },
            success: function (response) {
                console.log(response); // for debugging

                const pathao = response.providers.pathao;
                const steadfast = response.providers.steadfast;

                $('#courierTableBody').empty();

                // Format each provider's data into a row
                if (pathao?.success && pathao.data) {
                $('#courierTableBody').append(
                    `<tr>
                        <td>Pathao</td>
                        <td>${pathao.data.total_orders ?? 'N/A'}</td>
                        <td>${pathao.data.delivered ?? 'N/A'}</td>
                        <td>${pathao.data.cancelled ?? 'N/A'}</td>
                        <td>${calculateSuccessRate(pathao.data)}%</td>
                    </tr>`
                );
            }

                if (steadfast?.success && steadfast.data) {
                $('#courierTableBody').append(
                    `<tr>
                        <td>Steadfast</td>
                        <td>${steadfast.data.total_parcels ?? 'N/A'}</td>
                        <td>${steadfast.data.total_delivered ?? 'N/A'}</td>
                        <td>${steadfast.data.total_cancelled ?? 'N/A'}</td>
                        <td>${calculateSuccessRate(steadfast.data)}%</td>
                    </tr>`
                );
            }

                if (!pathao?.success && !steadfast?.success) {
                $('#courierTableBody').append(
                    `<tr><td colspan="5">No data available or both providers failed</td></tr>`
                );
            }
            const analysis = response.combined_analysis;
            if (analysis) {
                $('#fraudSummary').html(
                    `<p><strong>Risk Level:</strong> ${analysis.risk_level}</p>
                     <p><strong>Fraud Suspected:</strong> ${analysis.is_fraud ? 'Yes' : 'No'}</p>
                     <p><strong>Confidence:</strong> ${analysis.confidence}%</p>`
                );
            }
            },
            error: function (xhr) {
            console.error(xhr);
            alert("An error occurred while checking the report.");
        }
        });
    });

    function calculateSuccessRate(data) {
    const delivered = parseInt(data.total_delivered || 0);
    const total = parseInt(data.total_parcels || 0);
    if (!total || total === 0) return 0;
    return ((delivered / total) * 100).toFixed(2);
}
});

</script>
@endsection