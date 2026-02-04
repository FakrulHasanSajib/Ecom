@extends('backEnd.layouts.master')
@section('title', 'Dealer Products')
@section('css')
    <link href="{{asset('public/backEnd')}}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <style>
        .product-card {
            transition: transform 0.2s;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .select2-container .select2-selection--single {
            height: 38px;
            border: 1px solid #ced4da;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 38px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid">
        <!-- Page Title & Breadcrumbs -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <a href="{{route('admin.dealer.index')}}"
                            class="btn btn-secondary rounded-pill waves-effect waves-light">
                            <i class="fe-arrow-left me-1"></i> Back to List
                        </a>
                    </div>
                    <h4 class="page-title">Manage Products for: <span class="text-primary">{{$dealer->name}}</span></h4>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Assign Product Form -->
            <div class="col-lg-4">
                <div class="card border-top border-primary border-3">
                    <div class="card-body">
                        <h4 class="header-title mb-3"><i class="fe-plus-circle me-1"></i> Assign New Product</h4>
                        <form action="{{route('admin.dealer.product.store')}}" method="POST">
                            @csrf
                            <input type="hidden" name="dealer_id" value="{{$dealer->id}}">

                            <div class="mb-3">
                                <label for="product_id" class="form-label">Select Product <span
                                        class="text-danger">*</span></label>
                                <select class="form-control select2" name="product_id" id="product_id" required
                                    data-toggle="select2">
                                    <option value="">Choose a Product...</option>
                                    @foreach($products as $product)
                                        <option value="{{$product->id}}">{{$product->name}} (Reg: {{$product->new_price}})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="dealer_price" class="form-label">Dealer Price <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">৳</span>
                                    <input type="number" step="0.01" class="form-control" name="dealer_price"
                                        id="dealer_price" required placeholder="0.00">
                                </div>
                                <small class="text-muted">Enter the special price for this dealer.</small>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary waves-effect waves-light">
                                    <i class="fe-save me-1"></i> Assign / Update Price
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Assigned Products List -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3"><i class="fe-list me-1"></i> Assigned Products List</h4>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-centered mb-0 bg-white">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50%;">Product Name</th>
                                        <th>Regular Price</th>
                                        <th>Dealer Price</th>
                                        <th class="text-center" style="width: 100px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($dealer->products as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($item->image)
                                                        <img src="{{asset($item->image->image)}}" alt="img" class="rounded me-2"
                                                            height="40" width="40">
                                                    @else
                                                        <div class="avatar-xs d-inline-block me-2">
                                                            <span class="avatar-title bg-soft-primary text-primary fs-12 rounded">
                                                                {{substr($item->name, 0, 1)}}
                                                            </span>
                                                        </div>
                                                    @endif
                                                    <h5 class="m-0 font-14">{{$item->name}}</h5>
                                                </div>
                                            </td>
                                            <td>৳ {{$item->new_price}}</td>
                                            <td class="text-success fw-bold">৳ {{$item->pivot->dealer_price}}</td>
                                            <td class="text-center">
                                                <form action="{{route('admin.dealer.product.destroy')}}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="dealer_id" value="{{$dealer->id}}">
                                                    <input type="hidden" name="product_id" value="{{$item->id}}">
                                                    <button type="submit" class="btn btn-danger btn-sm waves-effect waves-light"
                                                        onclick="return confirm('Are you sure you want to remove this product?')"
                                                        title="Remove">
                                                        <i class="fe-trash-2"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center p-4">
                                                <div class="text-muted">
                                                    <i class="fe-alert-circle font-24 d-block mb-2"></i>
                                                    No products assigned yet.
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('public/backEnd')}}/assets/libs/select2/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2({
                width: '100%',
                placeholder: 'Choose a Product...',
                allowClear: true
            });
        });
    </script>
@endsection