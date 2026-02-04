<?php
namespace App\Http\Controllers\Reseller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Reseller;
use App\Models\Product;
use App\Models\DealerProduct;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Shipping;
use App\Models\Payment;
use App\Models\Withdrawal;

class ResellerController extends Controller
{
    public function dashboard()
    {
        return view('backEnd.reseller.dashboard');
    }

    public function productList()
    {
        $reseller = Auth::guard('reseller')->user();
        

        
        $dealer_id = $reseller->dealer_id;

        if ($dealer_id) {
            // Fetch products assigned to this dealer
            $product_ids = DealerProduct::where('dealer_id', $dealer_id)->pluck('product_id');
            $products = Product::whereIn('id', $product_ids)->where('status', 1)->get();
        } else {
            $products = collect([]);
        }

        return view('backEnd.reseller.products', compact('products'));
    }

    public function orderCreate($id)
{
    // ১. প্রোডাক্ট ডাটা খুঁজে বের করা
    $product = Product::find($id);
    
    // ২. লগইন করা রিসেলার এবং তার ডিলার আইডি সংগ্রহ করা
    $reseller = Auth::guard('reseller')->user();
    
    // ৩. ডিফল্ট দাম হিসেবে প্রোডাক্টের রেগুলার প্রাইস সেট করা
    $final_buy_price = $product->new_price;

    // ৪. চেক করা রিসেলার কোন ডিলারের আন্ডারে আছে কি না
    if ($reseller->dealer_id) {
        $dealerProduct = DealerProduct::where('dealer_id', $reseller->dealer_id)
            ->where('product_id', $product->id)
            ->first();

        // ৫. যদি ডিলার এই প্রোডাক্টের জন্য কোন প্রাইস সেট করে থাকে
        if ($dealerProduct) {
            /** * গুরুত্বপূর্ণ পরিবর্তন: 
             * এখানে dealer_price এর পরিবর্তে reseller_price ব্যবহার করা হয়েছে।
             * যাতে রিসেলার ডিলারের কেনা দাম না দেখে ডিলারের সেট করা দাম দেখে।
             */
            $final_buy_price = $dealerProduct->reseller_price ?? $dealerProduct->dealer_price;
        }
    }

    /** * compact এ 'dealer_price' ভ্যারিয়েবলটিই পাঠানো হয়েছে যাতে আপনার ব্লেড 
     * ফাইলে কোন পরিবর্তন করতে না হয়। কিন্তু এতে এখন রিসেলার প্রাইসটি থাকবে।
     */
    return view('backEnd.reseller.order_create', [
        'product' => $product,
        'dealer_price' => $final_buy_price
    ]);
}

 public function orderStore(Request $request)
{
    $this->validate($request, [
        'name' => 'required',
        'phone' => 'required',
        'address' => 'required',
        'custom_amount' => 'required|numeric',
        'product_id' => 'required',
        'qty' => 'required|integer|min:1',
    ]);

    $product = Product::find($request->product_id);
    if (!$product) {
        return back()->with('error', 'Product not found');
    }

    // ১. ডিফল্ট বাইং প্রাইস (যদি ডিলারের প্রোডাক্ট না হয়)
    $buying_price = $product->new_price; 

    // ২. ডিলার প্রাইস লজিক (Reseller Price বের করা)
    $reseller = Auth::guard('reseller')->user();
    
    if ($reseller->dealer_id) {
        $dealerProduct = DealerProduct::where('dealer_id', $reseller->dealer_id)
            ->where('product_id', $product->id)
            ->first();
            
        // [FIXED HERE] আগে এখানে dealer_price নেওয়া হচ্ছিল, তাই হিসাব ভুল আসছিল।
        // এখন আমরা reseller_price চেক করছি।
        if ($dealerProduct) {
            if ($dealerProduct->reseller_price > 0) {
                $buying_price = $dealerProduct->reseller_price;
            } else {
                // যদি রিসেলার প্রাইস সেট না থাকে, তবে ডিলার প্রাইস বা রেগুলার প্রাইস ফলব্যাক হতে পারে
                $buying_price = $dealerProduct->dealer_price; 
            }
        }
    }

    // ৩. ভ্যালিডেশন: বিক্রির দাম কেনা দামের চেয়ে কম হতে পারবে না
    if ($request->custom_amount < $buying_price) {
        return back()->with('error', 'Selling price cannot be less than Buy Price (' . $buying_price . ')');
    }

    // --- Shipping Logic ---
    $base_charge = $request->delivery_charge ?? 100; // ডিফল্ট ১০০ রাখা হলো সেফটির জন্য
    $area_name = ($base_charge == 70) ? 'Inside Dhaka' : (($base_charge == 130) ? 'Outside Dhaka' : 'Regular');
    
    $qty = $request->qty;
    $extra_charge_per_item = 30; 

    if ($qty > 1) {
        $shipping_fee = $base_charge + (($qty - 1) * $extra_charge_per_item);
    } else {
        $shipping_fee = $base_charge;
    }

    // ৪. সিস্টেমের পাওনা এবং ইনভয়েস ক্যালকুলেশন
    // [গুরুত্বপূর্ণ] এখানে buying_price ব্যবহার করায় এখন রিসেলার প্রাইস দিয়ে গুণ হবে
    $buy_price_total = $buying_price * $qty; 
    $payable_to_system = $buy_price_total + $shipping_fee;

    $lastOrderId = Order::max('id') ?? 0;


   

    $order = new Order();
    $order->invoice_id = date('d') . '-' . ($lastOrderId + 1);
    $order->amount = ($request->custom_amount * $qty) + $shipping_fee; // কাস্টমারের ইনভয়েস
    $order->payable_amount = $payable_to_system; // সিস্টেম পাবে (Reseller Price + Shipping)
    $order->discount = 0;
    $order->shipping_charge = $shipping_fee;
    $order->customer_id = Auth::guard('reseller')->id();
    $order->order_status = 1; 

    // =========================================================
    // আপডেট করা লজিক: একাউন্ট পেন্ডিং থাকলে টাইপ হবে 'reseller_activation'
    // =========================================================
    if ($reseller->status == 'pending') {
        $order->order_type = 'reseller_activation'; 
    } else {
        $order->order_type = 'reseller'; 
    }
    // =========================================================

    $order->order_type = 'reseller'; 
    $order->note = 'Dropshipping Order';
    $order->save();

    // Shipping Info
    $shipping = new Shipping();
    $shipping->order_id = $order->id;
    $shipping->customer_id = Auth::guard('reseller')->id();
    $shipping->name = $request->name;
    $shipping->phone = $request->phone;
    $shipping->address = $request->address;
    $shipping->area = $area_name;
    $shipping->save();

    // Payment Info
    $payment = new Payment();
    $payment->order_id = $order->id;
    $payment->customer_id = Auth::guard('reseller')->id();
    $payment->payment_method = $request->payment_method;
    $payment->amount = $order->payable_amount;
    $payment->payment_status = 'pending';
    $payment->save();

    // Order Details
    $order_details = new OrderDetails();
    $order_details->order_id = $order->id;
    $order_details->product_id = $product->id;
    $order_details->product_name = $product->name;
    // এখানে buying_price (অর্থাৎ Reseller Price) সেভ করা হচ্ছে
    $order_details->purchase_price = $buying_price; 
    $order_details->sale_price = $request->custom_amount; 
    $order_details->product_color = 'N/A';
    $order_details->product_size = 'N/A';
    $order_details->qty = $qty;
    $order_details->save();

    if(function_exists('toastr')){
         Toastr::success('Order Placed Successfully', 'Success');
    }
    return redirect()->route('reseller.dashboard')->with('success', 'Order placed successfully!');
}

    public function orderList()
    {
        $orders = Order::where('customer_id', Auth::guard('reseller')->id())
            ->where('order_type', 'reseller')
            ->latest()
            ->paginate(10);
        return view('backEnd.reseller.orders', compact('orders'));
    }

    public function wallet()
    {
        $reseller = Auth::guard('reseller')->user();
        $withdrawals = Withdrawal::where('reseller_id', $reseller->id)->latest()->paginate(10);
        return view('backEnd.reseller.wallet', compact('reseller', 'withdrawals'));
    }

    public function withdrawalRequest(Request $request)
    {
        $this->validate($request, [
            'amount' => 'required|numeric|min:50',
            'method' => 'required',
            'account_info' => 'required',
        ]);

        $reseller = Auth::guard('reseller')->user();

        if ($request->amount > $reseller->balance) {
            return back()->with('error', 'Insufficient balance');
        }

        $withdrawal = new Withdrawal();
        $withdrawal->reseller_id = $reseller->id;
        $withdrawal->amount = $request->amount;
        $withdrawal->method = $request->method;
        $withdrawal->account_info = $request->account_info;
        $withdrawal->status = 'pending';
        $withdrawal->save();

        $reseller->balance -= $request->amount;
        $reseller->save();

        return back()->with('success', 'Withdrawal request submitted');
    }

   public function referralList()
{
    // Security Check: If referrer_id is NOT null (meaning they are Level 2), deny access.
    if (Auth::guard('reseller')->user()->referrer_id !== null) {
        return redirect()->route('reseller.dashboard')->with('error', 'You are not authorized to access this page.');
    }

    $referrals = Reseller::where('referrer_id', Auth::guard('reseller')->id())->paginate(10);
    return view('backEnd.reseller.referrals', compact('referrals'));
}

    public function referralProfile($id)
    {
        $reseller_id = Auth::guard('reseller')->id();
        
        // Ensure the referral actually belongs to this reseller
        $referral = Reseller::where('referrer_id', $reseller_id)->with(['orders', 'payments'])->find($id);

        if (!$referral) {
            return back()->with('error', 'Referral not found');
        }

        return view('backEnd.reseller.referral_profile', compact('referral'));
    }

    public function profile()
    {
        $profile = Auth::guard('reseller')->user();
        return view('backEnd.reseller.profile_settings', compact('profile'));
    }

    public function updateProfile(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'image' => 'nullable|image',
            'password' => 'nullable|min:6',
        ]);

        $reseller = Reseller::find(Auth::guard('reseller')->id());
        $reseller->name = $request->name;
        $reseller->email = $request->email;
        $reseller->phone = $request->phone;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time() . '-' . $image->getClientOriginalName();
            $path = public_path('uploads/reseller');
            $image->move($path, $name);
            
            // Delete old image if exists
            if ($reseller->image && File::exists(public_path($reseller->image))) {
                File::delete(public_path($reseller->image));
            }
            
            $reseller->image = 'uploads/reseller/' . $name;
        }

        if ($request->password) {
            $reseller->password = Hash::make($request->password);
        }

        $reseller->save();

        return back()->with('success', 'Profile updated successfully');
    }
}