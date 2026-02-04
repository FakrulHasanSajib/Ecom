<?php

namespace App\Http\Controllers\Wholesales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Wholesaler;
use App\Models\Product;  
use App\Models\Category;
use App\Models\Brand;
use App\Models\PaymentHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class WholesaleDashboardController extends Controller
{
     public function __construct()
    {
        $this->middleware('wholesaler.auth');
    }

    public function index()
    {
       
        $wholesaler = Auth::guard('wholesaler')->user();
    $wholesalerId = Auth::guard('wholesaler')->user()->id;
    
    // Get recent orders with all related data
    $show_data = Order::with([
        'shipping',
        'wholesaler',
        'orderdetails.product.image',
        'status',
        'user'
    ])
    ->where('order_status','!=', 11)
    ->where('customer_type', 'wholesale')
    ->where('customer_id', $wholesalerId)
    ->latest()
    ->limit(10) // Get more recent orders for display
    ->get();
    
    
    
    // Count total invoices
    $totalinvoice = Order::where('customer_type', 'wholesale')
        ->where('customer_id', $wholesalerId)
        ->count();
    
    // Calculate total order amount and total items
    $orderSummary = Order::where('customer_type', 'wholesale')
        ->where('customer_id', $wholesalerId)
        ->where('order_status','!=', 11)
    ->selectRaw('
            SUM(amount) as total_amount,
            SUM((SELECT SUM(qty) FROM order_details WHERE order_details.order_id = orders.id)) as total_items
        ')
        ->first();
    
    $totalOrderAmount = $orderSummary->total_amount ?? 0;
    $totalOrderItems = $orderSummary->total_items ?? 0;
    
    // Calculate return data (assuming you have a returns table or return orders)
    $returnSummary = Order::where('customer_type', 'wholesale')
        ->where('customer_id', $wholesalerId)
        ->where('order_status', 11) // Assuming you have order_type field
        ->selectRaw('
            SUM(amount) as total_return_amount,
            SUM((SELECT SUM(qty) FROM order_details WHERE order_details.order_id = orders.id)) as total_return_items
        ')
        ->first();
    
    $totalReturnAmount = $returnSummary->total_return_amount ?? 0;
    $totalReturnItems = $returnSummary->total_return_items ?? 0;
    
    // Get recent return orders
    $return_data = Order::with([
        'shipping',
        'wholesaler',
        'orderdetails.product.image',
        'status',
        'user'
    ])
    ->where('customer_type', 'wholesale')
    ->where('customer_id', $wholesalerId)
    ->where('order_status', 11)
    ->latest()
    ->limit(10)
    ->get();
    
    $paymenthistory = PaymentHistory::where('whosales_id', $wholesalerId)->get();
    // Calculate total due (assuming payment field exists in wholesaler table)
   $advance = $paymenthistory->sum('pay_amount');
   $totalPaid = $advance ?? 0;
$totalAdvance = $wholesaler->advance ?? 0;
// সঠিক লজিক: যেহেতু $totalOrderAmount এ রিটার্ন প্রোডাক্টের দাম নেই, তাই শুধু পেইড মাইনাস হবে
$totalDue = $totalOrderAmount - $totalPaid;
    
    // Get sell report data (product-wise summary)
    $sellReport = DB::table('orders')
        ->join('order_details', 'orders.id', '=', 'order_details.order_id')
        ->join('products', 'order_details.product_id', '=', 'products.id')
        ->leftJoin('productimages', 'products.id', '=', 'productimages.product_id')
        ->where('orders.customer_type', 'wholesale')
        ->where('orders.customer_id', $wholesalerId)
        ->selectRaw('
            products.id,
            products.name as product_name,
            products.product_code,
            productimages.image,
            SUM(CASE WHEN orders.order_status != 11 THEN order_details.qty ELSE 0 END) as total_ordered,
            SUM(CASE WHEN orders.order_status = 11 THEN order_details.qty ELSE 0 END) as total_returned,
            SUM(CASE WHEN orders.order_status != 11 THEN order_details.qty ELSE 0 END) - 
            SUM(CASE WHEN orders.order_status = 11 THEN order_details.qty ELSE 0 END) as net_quantity,
            SUM(CASE WHEN orders.order_status != 11 THEN (order_details.qty * order_details.sale_price) ELSE 0 END) as total_revenue
        ')
        ->groupBy('products.id', 'products.name', 'products.product_code', 'productimages.image')
        ->orderByDesc('total_revenue')
        ->limit(20)
        ->get();
    
    return view('wholesaler.dashboard', compact(
        'wholesaler',
        'totalinvoice',
        'show_data',
        'return_data',
        'totalOrderAmount',
        'totalOrderItems',
        'totalReturnAmount',
        'totalReturnItems',
        'totalPaid',
        'totalDue',
        'sellReport'
    ));
    }

    public function profile()
    {
        $wholesaler = Auth::guard('wholesaler')->user();
        return view('wholesaler.profile', compact('wholesaler'));
    }

     public function updateProfile(Request $request)
    {
        $wholesaler = Auth::guard('wholesaler')->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:wholesalers,email,' . $wholesaler->id,
            'phone' => 'nullable|string|max:20',
            'business_name' => 'required|string|max:255',
            'address' => 'required|string',
        ]);
        
        $wholesaler->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'business_name' => $request->business_name,
            'address' => $request->address,
        ]);
        
        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
    
    public function updatePassword(Request $request)
    {
        $wholesaler = Auth::guard('wholesaler')->user();
        
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password'
        ]);
        
        $user = Wholesaler::find($wholesaler->id);
        $hashPass = $user->password;
        
        if (Hash::check($request->old_password, $hashPass)) {
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);
            
            return redirect()->back()->with('success', 'Password changed successfully!');
        } else {
            return redirect()->back()->withErrors(['old_password' => 'Current password is incorrect.']);
        }
    }
    
    public function updateContact(Request $request)
    {
        $wholesaler = Auth::guard('wholesaler')->user();
        
        $request->validate([
            'website' => 'nullable|url|max:255',
            'fb_page' => 'nullable|url|max:255',
            'bank_name' => 'nullable|string|max:255',
            'account_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:50',
            'routing_name' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
        ]);
        
        $wholesaler->update([
            'website' => $request->website,
            'fb_page' => $request->fb_page,
            'bank_name' => $request->bank_name,
            'account_name' => $request->account_name,
            'account_number' => $request->account_number,
            'routing_name' => $request->routing_name,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
        ]);
        
        return redirect()->back()->with('success', 'Contact information updated successfully!');
    }
    
    public function productshow(Request $request){
        
        $query = Product::with('image', 'category')
            ->whereNotNull('offer_price')
            ->where('status', 1);
    
    // Apply filters if provided
    if ($request->filled('brand')) {
        $query->where('brand_id', $request->brand);
    }
    
    if ($request->filled('category')) {
        $query->where('category_id', $request->category);
    }
    
    // Order by latest first
    $query->orderBy('created_at', 'desc');
    
    // Paginate results with proper parameter preservation
    $getPro = $query->paginate(20)->withQueryString();
    
    // Get categories and brands for filters
    $categories = Category::where('parent_id', '=', '0')
                         ->where('status', 1)
                         ->select('id', 'name', 'status')
                         ->with('childrenCategories')
                         ->get();
                         
    $brands = Brand::where('status', '1')
                   ->select('id', 'name', 'status')
                   ->get();
         
    return view('wholesaler.productshow', compact('getPro', 'categories', 'brands'));
        
    }
}