<?php

namespace App\Http\Controllers\Wholesales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\SmsTeamplate;
use App\Models\PaymentHistory;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class WholesalesorderController extends Controller
{


    public function index(Request $request){ 
    $wholesalerId = Auth::guard('wholesaler')->user()->id;
     $show_data = Order::with([
        'shipping',
        'wholesaler',
        'orderdetails.product.image',
        'status',
        'user'
    ])
    ->where('customer_type', 'wholesale')
    ->where('customer_id', $wholesalerId)
    ->latest();

   // dd($show_data);


// Count total before filters (if needed)
$order_count = $show_data->count();

// Apply filters
$show_data = $show_data
    ->when($request->filled('date_filter'), function ($query) use ($request) {
        $date_filter = $request->date_filter;

        if ($date_filter === 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($date_filter === 'this_week') {
            $query->whereBetween('created_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ]);
        } elseif ($date_filter === 'this_month') {
            $query->whereMonth('created_at', Carbon::now()->month)
                  ->whereYear('created_at', Carbon::now()->year);
        }
    })
    ->when($request->filled('keyword'), function ($query) use ($request) {
        $keyword = $request->keyword;

        $query->where(function ($subQuery) use ($keyword) {
            $subQuery->where('invoice_id', 'LIKE', "%{$keyword}%")
                ->orWhereHas('shipping', function ($shippingQuery) use ($keyword) {
                    $shippingQuery->where('phone', 'LIKE', "%{$keyword}%")
                        ->orWhere('name', 'LIKE', "%{$keyword}%");
                });
        });
    });

  // Paginate results
  $show_data = $show_data->paginate(10)->appends($request->query());

    return view("wholesaler.order_invoicelist", compact('show_data','order_count'));
}

public function returnlist(Request $request){ 
    $wholesalerId = Auth::guard('wholesaler')->user()->id;
     $show_data = Order::with([
        'shipping',
        'wholesaler',
        'orderdetails.product.image',
        'status',
        'user'
    ])
    ->where('customer_type', 'wholesale')
    ->where('customer_id', $wholesalerId)
    ->where('order_status',5)->latest();

   // dd($show_data);


// Count total before filters (if needed)
$order_count = $show_data->count();

// Apply filters
$show_data = $show_data
    ->when($request->filled('date_filter'), function ($query) use ($request) {
        $date_filter = $request->date_filter;

        if ($date_filter === 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($date_filter === 'this_week') {
            $query->whereBetween('created_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ]);
        } elseif ($date_filter === 'this_month') {
            $query->whereMonth('created_at', Carbon::now()->month)
                  ->whereYear('created_at', Carbon::now()->year);
        }
    })
    ->when($request->filled('keyword'), function ($query) use ($request) {
        $keyword = $request->keyword;

        $query->where(function ($subQuery) use ($keyword) {
            $subQuery->where('invoice_id', 'LIKE', "%{$keyword}%")
                ->orWhereHas('shipping', function ($shippingQuery) use ($keyword) {
                    $shippingQuery->where('phone', 'LIKE', "%{$keyword}%")
                        ->orWhere('name', 'LIKE', "%{$keyword}%");
                });
        });
    });

  // Paginate results
  $show_data = $show_data->paginate(10)->appends($request->query());

    return view("wholesaler.return_invoicelist", compact('show_data','order_count'));
}

public function invoice($invoice_id){
        $order = Order::where(['invoice_id'=>$invoice_id])->with('orderdetails','payment','shipping','customer')->firstOrFail();
        $smsteamplate = SmsTeamplate::get();
        return view('wholesaler.invoice',compact('order','smsteamplate'));
    }


    public function historypay(Request $request){
        $wholesalerId = Auth::guard('wholesaler')->user()->id;
        $query = PaymentHistory::where('whosales_id',$wholesalerId);
    
    // Date filtering
    if ($request->filled('date_filter')) {
        $dateFilter = $request->date_filter;
        switch ($dateFilter) {
            case 'today':
                $query->whereDate('date', today());
                break;
            case 'this_week':
                $query->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'this_month':
                $query->whereMonth('date', now()->month)
                      ->whereYear('date', now()->year);
                break;
        }
    }
    
   // Keyword search
    if ($request->filled('keyword')) {
        $keyword = $request->keyword;
        $query->where(function($q) use ($keyword) {
            $q->where('pay_code', 'like', "%{$keyword}%")
              ->orWhere('payment_method', 'like', "%{$keyword}%")
              ->orWhere('pay_amount', 'like', "%{$keyword}%")
              ->orWhere('paynode', 'like', "%{$keyword}%");
        });
    }
    
    $paymentlist = $query->paginate(15);
    
    
    // If AJAX request, return only the table content
    if ($request->ajax()) {
        return response()->json([
            'html' => view('wholesaler.payment_table_content', compact('paymentlist'))->render(),
            'pagination' => $paymentlist->appends($request->all())->links('pagination::bootstrap-4')->render()
        ]);
    }

    return view('wholesaler.historypay', compact('paymentlist'));

    }
}