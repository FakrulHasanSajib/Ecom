@extends('backEnd.layouts.master')
@section('title', 'Shipping Manager')
@section('css')
    <style>
        .scanner-box {
            background: #f1f5f7;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            border: 2px dashed #6c757d;
        }

        .scan-input {
            font-size: 24px;
            text-align: center;
            height: 60px;
        }
        .courier-option {
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 10px;
            display: block;
        }
        .courier-option:hover {
            background-color: #f8f9fa;
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Shipping Manager (Stock Out)</h4>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="scanner-box mb-4">
                            <i class="fe-maximize font-28 text-muted mb-2"></i>
                            <h4>Scan Invoice QR or Enter Order ID</h4>
                            <div class="form-group mt-3">
                                <input type="text" id="invoice_id" class="form-control scan-input" placeholder="e.g. 1024"
                                    autofocus>
                            </div>
                            <button class="btn btn-primary mt-3" onclick="fetchOrder()">Find Order</button>
                        </div>

                        <div id="order_details_area" style="display: none;">
                            <h4 class="header-title text-success"><i class="fe-check-circle"></i> Order Found</h4>
                            <div class="table-responsive mt-3">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Customer</th>
                                        <td id="d_customer"></td>
                                        <th>Phone</th>
                                        <td id="d_phone"></td>
                                    </tr>
                                    <tr>
                                        <th>Address</th>
                                        <td colspan="3" id="d_address"></td>
                                    </tr>
                                </table>

                                <table class="table table-striped mt-3">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Product</th>
                                            <th>Qty</th>
                                            <th>Stock Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="d_items">
                                    </tbody>
                                </table>

                                <div class="text-end mt-4">
                                    <button class="btn btn-danger me-2" onclick="resetScanner()">Cancel</button>
                                    {{-- বাটন ক্লিক করলে এখন সরাসরি কনফার্ম না হয়ে মোডাল ওপেন হবে --}}
                                    <button class="btn btn-success btn-lg" id="btn_ship" onclick="openCourierModal()">
                                        <i class="fe-truck"></i> Confirm Ship & Deduct Stock
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="courierModal" tabindex="-1" aria-labelledby="courierModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="courierModalLabel">Select Courier Service</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted mb-3">Please select how you want to ship this order:</p>
                    
                    <div class="form-check courier-option">
                        <input class="form-check-input" type="radio" name="courier_service" id="courier_steadfast" value="steadfast">
                        <label class="form-check-label w-100" for="courier_steadfast">
                            <strong>Steadfast Courier</strong>
                        </label>
                    </div>

                    <div class="form-check courier-option">
                        <input class="form-check-input" type="radio" name="courier_service" id="courier_pathao" value="pathao">
                        <label class="form-check-label w-100" for="courier_pathao">
                            <strong>Pathao Courier</strong>
                        </label>
                    </div>

                    <div class="form-check courier-option">
                        <input class="form-check-input" type="radio" name="courier_service" id="courier_manual" value="manual" checked>
                        <label class="form-check-label w-100" for="courier_manual">
                            <strong>Manual / Others</strong>
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="confirmShipWithCourier()">Submit & Ship</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('#invoice_id').on('keypress', function (e) {
            if (e.which === 13) {
                fetchOrder();
            }
        });

        function fetchOrder() {
            let id = $('#invoice_id').val();
            if (!id) return;

            $.ajax({
                url: "{{route('admin.inventory.shipping_fetch')}}",
                type: "POST",
                data: {
                    invoice_id: id,
                    _token: "{{csrf_token()}}"
                },
                success: function (res) {
                    if (res.status == 'success') {
                        let order = res.data;

                        let name = order.shipping ? order.shipping.name : (order.customer ? order.customer.name : 'Guest');
                        let phone = order.shipping ? order.shipping.phone : (order.customer ? order.customer.phone : 'N/A');
                        let address = order.shipping ? order.shipping.address : (order.address ?? 'N/A');

                        $('#d_customer').text(name);
                        $('#d_phone').text(phone);
                        $('#d_address').text(address);

                        let rows = '';
                        order.orderdetails.forEach(item => {
                            rows += `
                            <tr>
                                <td>${item.product_name} <br> <small>${item.product_size ?? ''} ${item.product_color ?? ''}</small></td>
                                <td>${item.qty}</td>
                                <td><span class="badge bg-info">Ready</span></td>
                            </tr>
                           `;
                        });
                        $('#d_items').html(rows);

                        $('#order_details_area').slideDown();
                        $('#btn_ship').data('id', order.id); // Store ID for confirm

                    } else {
                        toastr.error(res.message);
                        $('#invoice_id').val('').focus();
                    }
                },
                error: function () {
                    toastr.error('Server Error');
                }
            });
        }

        // 1. Open Modal instead of direct confirm
        function openCourierModal() {
            let id = $('#btn_ship').data('id');
            if (!id) return;
            
            // Reset radio to manual or default
            $('input[name="courier_service"][value="manual"]').prop('checked', true);
            
            $('#courierModal').modal('show');
        }

        // 2. Handle Submit from Modal
        function confirmShipWithCourier() {
            let id = $('#btn_ship').data('id');
            if (!id) return;

            // Get selected courier
            let courier = $('input[name="courier_service"]:checked').val();
            
            if (!courier) {
                toastr.error('Please select a courier service');
                return;
            }

            // Close modal immediately or wait for success
            $('#courierModal').modal('hide');

            $.ajax({
                url: "{{route('admin.inventory.shipping_confirm')}}",
                type: "POST",
                data: {
                    order_id: id,
                    courier_service: courier, // Send courier info
                    _token: "{{csrf_token()}}"
                },
                success: function (res) {
                    if (res.status == 'success') {
                        toastr.success(res.message);
                        resetScanner();
                    } else {
                        toastr.error(res.message);
                    }
                },
                error: function () {
                    toastr.error('Action Failed');
                }
            });
        }

        function resetScanner() {
            $('#order_details_area').slideUp();
            $('#invoice_id').val('').focus();
            $('#d_items').html('');
        }
    </script>
@endsection