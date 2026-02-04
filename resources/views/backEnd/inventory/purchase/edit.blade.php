@extends('backEnd.layouts.master')
@section('title', 'Edit Purchase')
@section('css')
    <link href="{{asset('public/backEnd')}}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <a href="{{route('admin.purchase.index')}}" class="btn btn-secondary rounded-pill"><i
                                class="fe-list"></i> Purchase History</a>
                    </div>
                    <h4 class="page-title">Edit Purchase #{{$purchase->invoice_no}}</h4>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('admin.purchase.update')}}" method="POST">
                            @csrf
                            <input type="hidden" name="purchase_id" value="{{$purchase->id}}">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group mb-3">
                                        <label for="supplier_id" class="form-label">Supplier Name *</label>
                                        <div class="input-group">
                                            <select class="form-control select2" name="supplier_id" id="supplier_id" required>
                                                <option value="">Select Supplier</option>
                                                @foreach(\App\Models\Supplier::where('status', 'active')->get() as $supplier)
                                                    <option value="{{ $supplier->id }}" {{ $purchase->supplier_id == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }} ({{ $supplier->phone }})</option>
                                                @endforeach
                                            </select>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                                                <i class="fe-plus"></i>
                                            </button>
                                        </div>
                                        @error('supplier_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group mb-3">
                                        <label for="invoice_no" class="form-label">Challan / Invoice No</label>
                                        <input type="text" class="form-control @error('invoice_no') is-invalid @enderror"
                                            name="invoice_no" value="{{ old('invoice_no', $purchase->invoice_no) }}" id="invoice_no">
                                        @error('invoice_no')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label>Purchase Date</label>
                                        <input type="date" name="purchase_date" class="form-control"
                                            value="{{$purchase->purchase_date}}">
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th width="40%">Product Search</th>
                                            <th>Unit Price</th>
                                            <th>Quantity</th>
                                            <th>Subtotal</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="purchaseTable">
                                        @foreach($purchase->details as $index => $detail)
                                        <tr id="row_{{$index + 1}}">
                                            <td>
                                                <select class="form-control select2 product-search" name="product_id[]" required data-row="{{$index + 1}}" style="width: 100%;">
                                                    <option value="{{$detail->product_id}}" selected>
                                                        [{{$detail->product->product_code}}] {{$detail->product->name}} (Stock: {{$detail->product->stock}})
                                                    </option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" name="purchase_price[]" class="form-control"
                                                    id="price_{{$index + 1}}" onkeyup="calcTotal({{$index + 1}})" value="{{$detail->purchase_price}}">
                                            </td>
                                            <td>
                                                <input type="number" name="qty[]" class="form-control" id="qty_{{$index + 1}}"
                                                    onkeyup="calcTotal({{$index + 1}})" value="{{$detail->qty}}" min="1">
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" class="form-control total-cost" id="total_{{$index + 1}}"
                                                    readonly value="{{$detail->total_price}}">
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm" onclick="removeRow({{$index + 1}})">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>Grand Total</strong></td>
                                            <td>
                                                <input type="number" step="0.01" name="total_amount" id="grand_total"
                                                    class="form-control" readonly value="{{$purchase->total_amount}}">
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-success btn-sm" onclick="addRow()">
                                                    <i class="fa fa-plus"></i> Add Item
                                                </button>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="form-group mt-3">
                                <label>Note</label>
                                <textarea name="note" class="form-control" rows="3">{{$purchase->note}}</textarea>
                            </div>

                            <div class="form-group mt-3 text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Update Purchase
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Supplier Modal -->
    <div class="modal fade" id="addSupplierModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Add New Supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addSupplierForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label>Name *</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Phone *</label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Address</label>
                            <textarea name="address" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="{{asset('public/backEnd')}}/assets/libs/select2/js/select2.min.js"></script>
    <script src="{{ asset('public/backEnd') }}/assets/js/pages/form-advanced.init.js"></script>
    <script src="{{ asset('public/backEnd') }}/assets/libs/flatpickr/flatpickr.min.js"></script>
    <script>
        let rowCount = {{count($purchase->details)}};

        $(document).ready(function () {
            // Init existing rows
            $('.product-search').each(function() {
                initSelect2($(this));
            });

            $(".select2").select2();
            flatpickr(".flatdate", {});
            
            // Add Supplier AJAX
            $('#addSupplierForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.supplier.store') }}",
                    data: $(this).serialize(),
                    success: function(response) {
                        if(response.success) {
                            var newOption = new Option(response.supplier.name + ' (' + response.supplier.phone + ')', response.supplier.id, true, true);
                            $('#supplier_id').append(newOption).trigger('change');
                            $('#addSupplierModal').modal('hide');
                            $('#addSupplierForm')[0].reset();
                            toastr.success('Supplier Added Successfully');
                        }
                    },
                    error: function(err) {
                        toastr.error('Something went wrong');
                    }
                });
            });
        });

        function initSelect2(selector) {
            selector.select2({
                placeholder: 'Search Product (Name/SKU)',
                ajax: {
                    url: "{{route('admin.purchase.search_product')}}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term // search term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: '[' + item.product_code + '] ' + item.name + ' (Stock: ' + item.stock + ')',
                                    id: item.id,
                                    price: item.purchase_price
                                }
                            })
                        };
                    },
                    cache: true
                },
                minimumInputLength: 0,
                allowClear: true
            });

            selector.on('select2:select', function (e) {
                let data = e.params.data;
                let row = $(this).data('row');
                $('#price_' + row).val(data.price);
                calcTotal(row);
            });
        }

        function addRow() {
            rowCount++;
            let html = `
                <tr id="row_${rowCount}">
                    <td>
                        <select class="form-control select2 product-search" name="product_id[]" required data-row="${rowCount}" style="width: 100%;">
                            <option value="">Search Product...</option>
                        </select>
                    </td>
                    <td>
                        <input type="number" step="0.01" name="purchase_price[]" class="form-control" id="price_${rowCount}" onkeyup="calcTotal(${rowCount})">
                    </td>
                    <td>
                        <input type="number" name="qty[]" class="form-control" id="qty_${rowCount}" onkeyup="calcTotal(${rowCount})">
                    </td>
                    <td>
                        <input type="number" step="0.01" class="form-control total-cost" id="total_${rowCount}" readonly>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(${rowCount})">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            $('#purchaseTable').append(html);
            initSelect2($('#row_' + rowCount + ' .product-search'));
        }

        function removeRow(rowId) {
            $('#row_' + rowId).remove();
            calcGrandTotal();
        }

        function calcTotal(rowId) {
            let price = parseFloat($('#price_' + rowId).val()) || 0;
            let qty = parseFloat($('#qty_' + rowId).val()) || 0;
            let total = price * qty;
            $('#total_' + rowId).val(total.toFixed(2));
            calcGrandTotal();
        }

        function calcGrandTotal() {
            let grandTotal = 0;
            $('.total-cost').each(function () {
                grandTotal += parseFloat($(this).val()) || 0;
            });
            $('#grand_total').val(grandTotal.toFixed(2));
        }
    </script>
@endsection
