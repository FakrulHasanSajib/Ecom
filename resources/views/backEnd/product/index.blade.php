@extends('backEnd.layouts.master')
@section('title', 'Product Manage')
@section('content')

    <style>
        .icon-btn {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.4rem;
            font-size: 14px;
            color: white;
        }

        .checkbox-outline {
            width: 32px;
            height: 32px;
            border: 2px solid #4A90E2;
            border-radius: 0.4rem;
        }

        .toggle-switch {
            width: 40px;
            height: 22px;
            background-color: #20c997;
            border-radius: 50px;
            position: relative;
        }

        .toggle-switch::before {
            content: '';
            position: absolute;
            top: 2px;
            left: 20px;
            width: 16px;
            height: 16px;
            background: white;
            border-radius: 50%;
        }

        .gradient-border-box {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            position: relative;
            z-index: 1;
        }

        .gradient-border-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: 8px;
            padding: 2px;
            background: linear-gradient(135deg, #3FA9F5, #9B59B6);
            -webkit-mask:
                linear-gradient(#fff 0 0) content-box,
                linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            z-index: -1;
        }

        .dropdown-icon {
            font-size: 16px;
            color: #999;
        }
    </style>
    <div class="container-fluid">

        <!-- start page title -->

        <!-- end page title -->
        {{-- <a href="{{route('products.create')}}" class="btn btn-danger rounded-pill"><i class="fe-shopping-cart"></i> Add Product</a> --}}
        <div class="row mt-3">
        <div class="col-12 col-md-3 col-lg-3  text-center ">

                <div class="col-md-12">
                    <h2 class="text-center text-md-start text-lg-start p-0">
                        Product Manage
                    </h2>

                </div>
            </div>
        <form action="{{ route('products.index') }}" method="GET" class="col-12 col-md-9 col-lg-9">
        <div class="row d-flex">
            <div class="col-4 col-mb-2 col-lg-2 p-1">
                <a href="{{ route('products.create') }}" class="btn btn-primary" role="button">
                    <i class="fa-solid fa-cart-plus me-2"></i>Add new
                </a>
            </div>
            
            <div class="col-4 col-mb-2 col-lg-2 p-1">
                <select name="bulk_action" class="form-select form-select-sm text-secondary p-1">
                    <option value="">Action</option>
                    <option value="1">Delete</option>
                    <option value="2">Edit</option>
                </select>
            </div>

            <div class="col-4 col-mb-2 col-lg-2 p-1">
                <select name="category" class="form-select form-select-sm text-secondary p-1">
                    <option value="">Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-8 col-mb-4 col-lg-4 p-1">
                <input type="text" name="keyword" class="form-control form-control-sm text-secondary p-1"
                    placeholder="Search: Phone, Name Or SKU" value="{{ request('keyword') }}" />
            </div>

            <div class="col-4 col-mb-2 col-lg-2 p-1">
                <button type="submit" class="btn btn-primary w-100 w-mb-75">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>
            </div>
        </div>
    </form>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-1">



                        <div class="table-responsive">
                            <table class="table table-bordered table-responsive table-striped">

                                <thead class="text-center bg-primary text-white">
                                    <tr>
                                        <th class="w-10">Action</th>
                                        <th class="w-10">Photo</th>
                                        <th class="w-10">Price</th>
                                        <th class="w-15">Name</th>
                                        <th class="w-15">Inventory</th>
                                        <th class="w-15">Added By</th>
                                        <th class="w-20">Deal & Feature</th>
                                        <th class="w-5"></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($data as $key => $value)
                                        <tr>
                                            <td class="action-icons-row d-flex gap-1 w-10">

                                                <input type="checkbox" value="{{ $value->id }}">


                                                <!-- Toggle Switch -->
                                                @if ($value->status == 1)
                                                    <form method="post" action="{{ route('products.inactive') }}"
                                                        class="mt-0">
                                                        @csrf
                                                        <input type="hidden" name="hidden_id" value="{{ $value->id }}">
                                                        <button type="submit" class="btn btn-link p-0 m-0"
                                                            title="Click to deactivate">
                                                            <i class="fa-solid fa-toggle-on text-success fa-2x"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <form method="post" action="{{ route('products.active') }}"
                                                        class="mt-0">
                                                        @csrf
                                                        <input type="hidden" name="hidden_id" value="{{ $value->id }}">
                                                        <button type="submit" class="btn btn-link p-0 m-0"
                                                            title="Click to activate">
                                                            <i class="fa-solid fa-toggle-off text-muted fa-2x"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                <!-- Eye Icon -->
                                                
                                                    <a class="icon-btn bg-warning text-white" href="{{ route('product',$value->slug) }}"><i class="fa-solid fa-eye"></i></a>
                                                    
                                                @if (auth()->user()->can('product-edit'))

                                                <!-- Money Icon -->
                                                <a href="{{ route('products.edit', $value->id) }}"
                                                    class="icon-btn bg-success text-white">
                                                    <i class="fa-solid fa-pen"></i>
                                                </a>
                                              @endif
                                                <!-- Copy Icon -->
                                                {{-- <div class="icon-btn bg-warning">
                                                <i class="fa-solid fa-trash"></i>
                                            </div> --}}
                                              @if (auth()->user()->can('product-delete'))
                                                <!-- Trash Icon -->
                                                <form action="{{route('products.destroy')}}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    <input type="hidden" value="{{$value->id}}" name="hidden_id">
                                                    <button type="submit" class="icon-btn bg-danger text-white border-0"
                                                        title="Delete">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                                @endif
                                                 @if (auth()->user()->can('product-list'))
                                                 <form action="{{route('products.duplicate')}}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    <input type="hidden" value="{{$value->id}}" name="order_id">
                                                    <button type="submit" class="icon-btn bg-danger text-white border-0"
                                                        title="Duplicate">
                                                        <i class="fa-solid fa-copy"></i>
                                                    </button>
                                                </form>
                                                @endif
                                                </span>

                                            </td>
                                            <td class="product-img w-10">
                                                <img src="{{ asset($value->image ? $value->image->image : '') }}"
                                                    class="backend-image" alt="">
                                            </td>
                                            <td class="stacked-text w-10">
                                                <div>{{ $value->new_price }}</div>
                                                <div></div>

                                            </td>
                                            <td class="stacked-text w-20">
                                                <div>{{ $value->name }}</div>
                                                <div>{{ $value->category ? $value->category->name : '' }}</div>

                                            </td>
                                            <td class="stacked-text w-15">
                                                <div>Stock: {{ $value->stock }}</div>
                                                <div>SKU: T105</div>

                                            </td>
                                            <td class="stacked-text w-10">
                                                <span>Admin</span>


                                            </td>
                                    <!--        {{-- <td class="stacked-text w-20">-->
                                    <!--    <div class="d-flex align-items-center gap-2 justify-content-between">-->
                                    <!--        <div>Hot Deals:</div>-->
                                    <!--        <form method="post" action="{{ route('products.toggleHotDeals') }}" class="mt-1">-->
                                    <!--            @csrf-->
                                    <!--            <input type="hidden" name="product_id" value="{{ $value->id }}">-->
                                    <!--            <button type="submit" class="btn btn-sm {{ $value->hot_deals ? 'btn-success' : 'btn-outline-secondary' }}" title="Toggle Hot Deals">-->
                                    <!--                <i class="fa-solid {{ $value->hot_deals ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>-->
                                    <!--            </button>-->
                                    <!--        </form>-->
                                    <!--    </div>-->

                                    <!--    <div class="d-flex align-items-center gap-2 justify-content-between">-->
                                    <!--        <div>Feature:</div>-->
                                    <!--        <form method="post" action="{{ route('products.toggleFeature') }}" class="mt-1">-->
                                    <!--            @csrf-->
                                    <!--            <input type="hidden" name="product_id" value="{{ $value->id }}">-->
                                    <!--            <button type="submit" class="btn btn-sm {{ $value->feature ? 'btn-success' : 'btn-outline-secondary' }}" title="Toggle Feature">-->
                                    <!--                <i class="fa-solid {{ $value->feature ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>-->
                                    <!--            </button>-->
                                    <!--        </form>-->
                                    <!--    </div>-->
                                    <!--</td> --}}-->
                                            <td class="stacked-text w-20">
                                                <div class="d-flex align-items-center gap-2 justify-content-between">
                                                    <div>Hot Deals:</div>
                                                    <div class="toggle-switch mt-1"></div>
                                                </div>
                                                <div class="d-flex align-items-center gap-2 justify-content-between">
                                                    <div>Feature:</div>
                                                    <div class="toggle-switch mt-1"></div>
                                                </div>

                                            </td>

                                            <td class="stacked-text w-5">
                                                <div class="gradient-border-box dropdown-btn dropdown">
                                                    <div class="dropdown-icon">&#9662;</div> <!-- Unicode down arrow -->
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="custom-paginate">
                            {{ $data->links('pagination::bootstrap-4') }}
                        </div>





                        {{-- main --}}

                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".checkall").on('change', function() {
                $(".checkbox").prop('checked', $(this).is(":checked"));
            });

            $(document).on('click', '.hotdeal_update', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                console.log('url', url);
                var product = $('input.checkbox:checked').map(function() {
                    return $(this).val();
                });
                var product_ids = product.get();
                if (product_ids.length == 0) {
                    toastr.error('Please Select A Product First !');
                    return;
                }
                $.ajax({
                    type: 'GET',
                    url: url,
                    data: {
                        product_ids
                    },
                    success: function(res) {
                        if (res.status == 'success') {
                            toastr.success(res.message);
                            window.location.reload();
                        } else {
                            toastr.error('Failed something wrong');
                        }
                    }
                });
            });
            $(document).on('click', '.update_status', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                var product = $('input.checkbox:checked').map(function() {
                    return $(this).val();
                });
                var product_ids = product.get();
                if (product_ids.length == 0) {
                    toastr.error('Please Select A Product First !');
                    return;
                }
                $.ajax({
                    type: 'GET',
                    url: url,
                    data: {
                        product_ids
                    },
                    success: function(res) {
                        if (res.status == 'success') {
                            toastr.success(res.message);
                            window.location.reload();
                        } else {
                            toastr.error('Failed something wrong');
                        }
                    }
                });
            });
            $(document).on('click', '.update_status', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                var product = $('input.checkbox:checked').map(function() {
                    return $(this).val();
                });
                var product_ids = product.get();
                if (product_ids.length == 0) {
                    toastr.error('Please Select A Product First !');
                    return;
                }
                $.ajax({
                    type: 'GET',
                    url: url,
                    data: {
                        product_ids
                    },
                    success: function(res) {
                        if (res.status == 'success') {
                            toastr.success(res.message);
                            window.location.reload();
                        } else {
                            toastr.error('Failed something wrong');
                        }
                    }
                });
            });


        })
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".dropdown").forEach((button) => {
                button.addEventListener("click", function() {
                    const row = this.closest("tr");
                    const nextRow = row.nextElementSibling;

                    // Close any open accordion rows
                    document
                        .querySelectorAll(".accordion-row")
                        .forEach((accordionRow) => {
                            accordionRow.remove();
                        });

                    if (!nextRow || !nextRow.classList.contains("accordion-row")) {
                        const accordionRow = document.createElement("tr");
                        accordionRow.classList.add("accordion-row");
                        const accordionCell = document.createElement("td");
                        accordionCell.colSpan =
                            9; // Adjust the colspan to match the number of columns
                        accordionCell.innerHTML = `
              <div class="accordion-content">
                  <div class="left-panel">
                  
                    <form action="{{ route('products.store') }}" method="POST" class="row" data-parsley-validate="" enctype="multipart/form-data">
            @csrf
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 p-1">  
              <div class="position-relative mb-2" >
                <span class="position-absolute top-0 ms-2 start-0 bg-white px-1  fw-bold fs-6" style="z-index: 1; transform: translateY(-50%);">
                  Product Name *
                </span>
                <input class="form-control border border-1  border-dark  rounded-3  " type="text" @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" id="name" required="" >
                @error('name')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
              </div>
            </div>
             <div class=" col-sm-12 col-md-6 col-lg-3 p-1">
              <div class="position-relative mb-2" >                
                <span for="category_id" class="position-absolute top-0 ms-2 start-0 bg-white px-1  fw-bold fs-6" style="z-index: 1; transform: translateY(-50%);">
                  Categories *
                </span>
                <select class="form-control border border-1 border-dark rounded-3 @error('category_id') is-invalid @enderror" name="category_id" value="{{ old('category_id') }}" id="category_id" required>
                  <option value="">Select..</option>                 
                </select>          
              </div>
            </div>

            <div class=" col-sm-12 col-md-6 col-lg-3 p-1">
              <div class="position-relative mb-2" >              
                <span for="subcategory_id" class="position-absolute top-0 ms-2 start-0 bg-white px-1  fw-bold fs-6" style="z-index: 1; transform: translateY(-50%);">
                  SubCategories                   
                </span>
                <select class="form-control border border-1 border-dark rounded-3 @error('subcategory_id') is-invalid @enderror" id="subcategory_id" name="subcategory_id" data-placeholder="Choose ...">
                  <optgroup>
                    <option value="">Select..</option>
                  </optgroup>
                </select>
                
              </div>
            </div>
            <!-- col end -->
            <div class=" col-sm-12 col-md-6 col-lg-3 p-1">
              <div class="position-relative mb-2" >                
                <span for="childcategory_id" class="position-absolute top-0 ms-2 start-0 bg-white px-1  fw-bold fs-6" style="z-index: 1; transform: translateY(-50%);">
                  Child Categories                  
                </span>
                <select class="form-control border border-1 border-dark rounded-3 @error('childcategory_id') is-invalid @enderror" id="childcategory_id" name="childcategory_id" data-placeholder="Choose ...">
                  <optgroup>
                    <option value="">Select..</option>
                  </optgroup>
                </select>
                
              </div>
            </div>
            <!-- col end -->
            <div class=" col-sm-12 col-md-6 col-lg-3 p-1">
              <div class="position-relative mb-2" >                
                <span for="category_id" class="position-absolute top-0 ms-2 start-0 bg-white px-1  fw-bold fs-6" style="z-index: 1; transform: translateY(-50%);">
                  Brands                 
                </span>
                <select class="form-control border border-1 border-dark rounded-3 @error('brand_id') is-invalid @enderror" value="{{ old('brand_id') }}" name="brand_id">
                  <option value="">Select..</option>                 
                </select>                
              </div>
            </div>
             <div class="col-sm-12 col-md-6 col-lg-2 p-1">
              <div class="position-relative mb-2">                
                <span class="position-absolute top-0 ms-1 start-0 bg-white px-1  fw-bold" style="z-index: 1; transform: translateY(-50%); font-size: 0.70rem;">
                  Prurchase Price *
                </span>
                <input type="text" class="form-control border border-1 border-dark rounded-3 @error('pro_unit') is-invalid @enderror" name="pro_unit" value="{{ old('pro_unit') }}" id="pro_unit" />
                
              </div>
            </div>
            <!-- col end -->
            <div class="col-sm-12 col-md-6 col-lg-2 p-1">
              <div class="position-relative mb-2">                
                <span class="position-absolute top-0 ms-1 start-0 bg-white px-1  fw-bold" style="z-index: 1; transform: translateY(-50%); font-size: 0.65rem;">
                 Wholesale Price
                </span>
                <input type="text" class="form-control border border-1 border-dark rounded-3 @error('pro_unit') is-invalid @enderror" name="pro_unit" value="{{ old('pro_unit') }}" id="pro_unit" />
                
              </div>
            </div>
            <!-- col end -->
            <div class="col-sm-12 col-md-6 col-lg-2 p-1">
              <div class="position-relative mb-2">                
                <span class="position-absolute top-0 ms-1 start-0 bg-white px-1  fw-bold" style="z-index: 1; transform: translateY(-50%); font-size: 0.70rem;">
                 Reseller Price
                </span>
                <input type="text" class="form-control border border-1 border-dark rounded-3 @error('pro_unit') is-invalid @enderror" name="pro_unit" value="{{ old('pro_unit') }}" id="pro_unit" />
                
              </div>
            </div>
            <!-- col end -->
            <div class="col-sm-12 col-md-6 col-lg-2 p-1">
              <div class="position-relative mb-2">                
                <span class="position-absolute top-0 ms-1 start-0 bg-white px-1  fw-bold" style="z-index: 1; transform: translateY(-50%); font-size: 0.70rem;">
                 Reguler Price *
                </span>
                <input type="text" class="form-control border border-1 border-dark rounded-3 @error('pro_unit') is-invalid @enderror" name="pro_unit" value="{{ old('pro_unit') }}" id="pro_unit" />
                
              </div>
            </div>
            <!-- col end -->
            <div class="col-sm-12 col-md-6 col-lg-2 p-1">
              <div class="position-relative mb-2">                
                <span class="position-absolute top-0 ms-1 start-0 bg-white px-1  fw-bold" style="z-index: 1; transform: translateY(-50%); font-size: 0.70rem;">
                 Offer sell Price *
                </span>
                <input type="text" class="form-control border border-1 border-dark rounded-3 @error('pro_unit') is-invalid @enderror" name="pro_unit" value="{{ old('pro_unit') }}" id="pro_unit" />
                
              </div>
            </div>
            <!-- col end -->
             <div class="col-sm-12 col-md-6 col-lg-2 p-1">
              <div class="position-relative mb-2">                
                <span class="position-absolute top-0 ms-1 start-0 bg-white px-1  fw-bold" style="z-index: 1; transform: translateY(-50%); font-size: 0.70rem;">
                Stock Quantity *
                </span>
                <input type="text" class="form-control border border-1 border-dark rounded-3 @error('pro_unit') is-invalid @enderror" name="pro_unit" value="{{ old('pro_unit') }}" id="pro_unit" />
                
              </div>
           
            



            </div>

             <div class="col-12 d-flex justify-content-center mt-3">
                <button type="submit" class="btn btn-danger text-white text-center" style="padding-left: 300px; padding-right: 300px;">Update</button>
            </div>


            </form>
            
                    
                            
            
           
                        
                
               
               
               
                
  
         
           
            
                      
        
               
                      
                
  
                
                </div>
              </div>
            `;
                        accordionRow.appendChild(accordionCell);
                        row.parentNode.insertBefore(accordionRow, row.nextSibling);
                    }
                });
            });
        });
    </script>
@endsection