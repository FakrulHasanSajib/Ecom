@extends('backEnd.layouts.master')
@section('title',' Payment History')
@section('content')
<style>
.loading {
    opacity: 0.6;
    pointer-events: none;
}

.form-group {
    margin-bottom: 15px;
}

.filter-input {
    margin-bottom: 10px;
}

/* Optional: Add loading spinner */
.loading::after {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid #f3f3f3;
    border-top: 2px solid #3498db;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
<div class="container-fluid">
          
    <!-- end page title --> 
  <div class="row order_page">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm">
                        <ul class="action2-btn">
                            <li><a data-bs-toggle="modal" data-bs-target="#addpay" class="btn rounded-pill btn-success"><i class="fe-plus"></i> Add Payment</a></li>
                        </ul>
                    </div>
                    
                    <form class="custom_form" id="filterForm">
                        <div class="col-4">
                            <select name="date_filter" class="form-control w-100 filter-input">
                                <option value="">Select Date</option>
                                <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Today</option>
                                <option value="this_week" {{ request('date_filter') == 'this_week' ? 'selected' : '' }}>This Week</option>
                                <option value="this_month" {{ request('date_filter') == 'this_month' ? 'selected' : '' }}>This Month</option>
                            </select>
                        </div>
                        
                        <div class="col-sm-4">
                            <div class="form-group">
                                <select name="author_id" class="form-control w-100 filter-input">
                                    <option value="">Select Wholesaler</option>
                                    @foreach($author as $authors)
                                        <option value="{{$authors->id}}" {{ request('order_status') == $authors->id ? 'selected' : '' }}>
                                            {{$authors->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-sm-4">
                            <div class="form-group">
                                <input type="text" name="keyword" placeholder="Search" class="form-control filter-input" value="{{ request('keyword') }}">
                                <button type="button" class="btn rounded-pill btn-info" id="clearFilters">Clear</button>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="table-responsive" id="table-container">
                    <table id="datatable-buttons" class="table table-striped w-100">
                        <thead>
                            <tr>
                                <th style="width:2%">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input checkall" value="">
                                        </label>
                                    </div>
                                </th>
                                <th style="width:2%">SL</th>
                                <th style="width:8%">Action</th>
                                <th style="width:8%">Pay Id</th>
                                <th style="width:10%">Business Name</th>
                                <th style="width:10%">Payment Method</th>
                                <th style="width:10%">Amount</th>
                                <th style="width:10%">Date</th>
                                <th style="width:10%">Reference</th>
                            </tr>
                        </thead>
                        <tbody id="payment-table-body">
                            @include('backEnd.wholeseller.payment_table_content')
                        </tbody>
                    </table>
                </div>
                
                <div class="custom-paginate" id="pagination-container">
                    {{$paymentlist->appends(request()->all())->links('pagination::bootstrap-4')}}
                </div>
            </div>
        </div>
    </div>
</div>

   </div>

<!-- Payment User End -->
<div class="modal fade" id="addpay" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Whosaler Payment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{route('author.payupdate')}}" method="post">
        @csrf
      <div class="modal-body">
        <div class="mb-3">
              <label for="payment_method" class="form-label">Author Select</label>
              <select class="form-select" name="author_id">
                <option selected>Select a Author</option>
                @foreach($author as $whosaler)
                <option value="{{$whosaler->id}}">{{$whosaler->name}}</option>
                @endforeach
              </select>
            </div>
        <div class="mb-3">
              <label for="payment_method" class="form-label">Payment Method</label>
              <select class="form-select" name="payment_method">
                <option selected>Select a Payment</option>
                <option value="Cash">Cash</option>
                <option value="Bkash">Bkash</option>
                <option value="Bank Transfer">Bank Transfer</option>
                <option value="Advance">Advance Payment</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="advance" class="form-label">Pay Amount</label>
              <input type="text" class="form-control" name="advance">
            </div>
            <div class="mb-3">
              <label for="date" class="form-label">Pay Date</label>
              <input type="date" class="form-control" name="date">
            </div>
            <div class="mb-3">
              <label for="advance" class="form-label">Pay Reference</label>
              <textarea class="form-control" name="paynote"></textarea>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-success">Submit</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- Assign User End-->


<!-- Assign User End-->

<!-- pathao courier  End-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

    $(document).ready(function() {
    let searchTimeout;
    
    // Function to perform AJAX filtering
    function performFilter() {
        let formData = $('#filterForm').serialize();
        
        $.ajax({
            url: "{{ route('author.payment') }}", // Update with your actual route
            type: 'GET',
            data: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            beforeSend: function() {
                $('#table-container').addClass('loading');
                // You can add a loading spinner here
            },
            success: function(response) {
                $('#payment-table-body').html(response.html);
                $('#pagination-container').html(response.pagination);
                $('#table-container').removeClass('loading');
                
                // Update URL without page reload
                let url = new URL(window.location);
                let params = new URLSearchParams(formData);
                params.forEach((value, key) => {
                    if (value) {
                        url.searchParams.set(key, value);
                    } else {
                        url.searchParams.delete(key);
                    }
                });
                window.history.pushState({}, '', url);
            },
            error: function() {
                $('#table-container').removeClass('loading');
                console.log('Error occurred during filtering');
            }
        });
    }
    
    // Real-time filtering for select dropdowns
    $('.filter-input').on('change', function() {
        if ($(this).attr('name') !== 'keyword') {
            performFilter();
        }
    });
    
    // Debounced search for keyword input
    $('input[name="keyword"]').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            performFilter();
        }, 500); // 500ms delay
    });
    
    // Clear filters
    $('#clearFilters').on('click', function() {
        $('#filterForm')[0].reset();
        performFilter();
    });
    
    // Handle pagination clicks
    $(document).on('click', '#pagination-container .pagination a', function(e) {
        e.preventDefault();
        let url = $(this).attr('href');
        let formData = $('#filterForm').serialize();
        
        $.ajax({
            url: url,
            type: 'GET',
            data: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                $('#payment-table-body').html(response.html);
                $('#pagination-container').html(response.pagination);
                
                // Update URL
                window.history.pushState({}, '', url + '&' + formData);
            }
        });
    });
});
$(document).ready(function(){
    $(".checkall").on('change',function(){
      $(".checkbox").prop('checked',$(this).is(":checked"));
    });

    // order status change 
    $(document).on('submit', 'form#order_status_form', function(e){
        e.preventDefault();
        var url = $(this).attr('action');
        var method = $(this).attr('method');
        let order_status=$(document).find('select#order_status').val();
    
        var order = $('input.checkbox:checked').map(function(){
          return $(this).val();
        });
        var order_ids=order.get();
        
        if(order_ids.length ==0){
            toastr.error('Please Select An Order First !');
            return ;
        }
        
        $.ajax({
           type:'GET',
           url:url,
           data:{order_status,order_ids},
           success:function(res){
               if(res.status=='success'){
                toastr.success(res.message);
                window.location.reload();
                
            }else{
                toastr.error('Failed something wrong');
            }
           }
        });
    
    });
    
    
  
    
})
</script>
@endsection