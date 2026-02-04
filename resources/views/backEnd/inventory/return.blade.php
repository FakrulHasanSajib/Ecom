@extends('backEnd.layouts.master')
@section('title', 'Return Manager')
@section('css')
    <style>
        .scanner-box {
            background: #fff0f0;
            /* Light red for return context */
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            border: 2px dashed #dc3545;
        }

        .scan-input {
            font-size: 24px;
            text-align: center;
            height: 60px;
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Return Manager (Stock In)</h4>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="scanner-box mb-4">
                            <i class="fe-refresh-ccw font-28 text-danger mb-2"></i>
                            <h4>Scan Invoice QR or Enter Order ID to Return</h4>
                            <div class="form-group mt-3">
                                <input type="text" id="invoice_id" class="form-control scan-input" placeholder="e.g. 1024"
                                    autofocus>
                            </div>
                            <button class="btn btn-danger mt-3" onclick="fetchOrder()">Find Order</button>
                        </div>

                        <div id="order_details_area" style="display: none;">
                            <h4 class="header-title text-danger"><i class="fe-alert-circle"></i> Order Found</h4>
                            <div class="table-responsive mt-3">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Customer</th>
                                        <td id="d_customer"></td>
                                        <th>Status</th>
                                        <td id="d_status"></td>
                                    </tr>
                                </table>

                                <form id="return_form">
                                    <input type="hidden" name="order_id" id="h_order_id">
                                    <table class="table table-striped mt-3">
                                        <thead class="bg-light">
                                            <tr>
                                                <th style="width: 50px;">Select</th>
                                                <th>Product</th>
                                                <th>Sold Qty</th>
                                                <th>Return Qty</th>
                                            </tr>
                                        </thead>
                                        <tbody id="d_items">
                                        </tbody>
                                    </table>

                                    <div class="text-end mt-4">
                                        <button type="button" class="btn btn-secondary me-2"
                                            onclick="resetScanner()">Cancel</button>
                                        <button type="button" class="btn btn-warning" onclick="processReturn('partial')">
                                            <i class="fe-check"></i> Return Selected
                                        </button>
                                        <button type="button" class="btn btn-danger" onclick="processReturn('full')">
                                            <i class="fe-refresh-cw"></i> Return Full Order
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
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
                url: "{{route('admin.inventory.return_fetch')}}",
                type: "POST",
                data: {
                    invoice_id: id,
                    _token: "{{csrf_token()}}"
                },
                success: function (res) {
                    if (res.status == 'success') {
                        let order = res.data;

                        // [UPDATED CODE START] - Wholesaler Check Logic
                        let customerName = '';
                        if (order.wholesaler) {
                            customerName = order.wholesaler.name + ' (' + order.wholesaler.business_name + ')';
                        } else if (order.customer) {
                            customerName = order.customer.name;
                        } else if (order.shipping) {
                            customerName = order.shipping.name;
                        } else {
                            customerName = order.name || 'Unknown';
                        }
                        $('#d_customer').text(customerName);
                        // [UPDATED CODE END]

                        $('#d_customer').text(order.customer ? order.customer.name : order.name);
                        $('#d_status').text(res.status_text);
                        $('#h_order_id').val(order.id);

                        let rows = '';
                        order.orderdetails.forEach(item => {
                            rows += `
                                <tr>
                                    <td>
                                        <input type="checkbox" class="form-check-input item-check" name="items[${item.id}][checked]" value="1" checked onchange="toggleQty(${item.id})">
                                        <input type="hidden" name="items[${item.id}][product_id]" value="${item.product_id}">
                                    </td>
                                    <td>${item.product_name} <br> <small>${item.product_size ?? ''} ${item.product_color ?? ''}</small></td>
                                    <td>${item.qty}</td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm qty-input" 
                                            name="items[${item.id}][qty]" 
                                            id="qty_${item.id}"
                                            value="${item.qty}" 
                                            max="${item.qty}" 
                                            min="1" 
                                            style="width: 80px;">
                                    </td>
                                </tr>
                               `;
                        });
                        $('#d_items').html(rows);

                        $('#order_details_area').slideDown();

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

        function toggleQty(id) {
            let checked = $(`input[name="items[${id}][checked]"]`).is(':checked');
            $(`#qty_${id}`).prop('disabled', !checked);
        }

        function processReturn(type) {
            if (!confirm('Confirm functionality? Stock will be increased.')) return;

            let formData = $('#return_form').serializeArray();
            formData.push({ name: 'type', value: type });
            formData.push({ name: '_token', value: "{{csrf_token()}}" });

            $.ajax({
                url: "{{route('admin.inventory.return_process')}}",
                type: "POST",
                data: formData,
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
            $('#h_order_id').val('');
        }
    </script>
@endsection