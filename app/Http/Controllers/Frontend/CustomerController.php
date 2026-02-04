<?php

namespace App\Http\Controllers\Frontend;
use shurjopayv2\ShurjopayLaravelPackage8\Http\Controllers\ShurjopayController;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PayStationController;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Intervention\Image\Facades\Image;
use App\Models\Customer;
use App\Models\District;
use App\Models\Order;
use App\Models\Product;
use App\Models\ShippingCharge;
use App\Models\OrderDetails;
use App\Models\Payment;
use App\Models\Shipping;
use App\Models\Review;
use App\Models\PaymentGateway;
use App\Models\SmsGateway;
use App\Models\Wholesaler;
use App\Models\SmsTeamplate;
use App\Models\GeneralSetting;
use App\Jobs\SendServerSideEventJob;

use Session;
use Hash;
use Auth;
use Cart;
use Mail;
use Str;
use DB;

class CustomerController extends Controller
{
    function __construct()
    {
        $this->middleware('customer', ['except' => ['register', 'store', 'verify', 'resendotp', 'account_verify', 'login', 'signin', 'logout', 'checkout', 'forgot_password', 'forgot_verify', 'forgot_reset', 'forgot_store', 'forgot_resend', 'order_save', 'order_newsave', 'order_success', 'order_track', 'order_track_result','setReferral','ajaxTrackEvent']]);
    }

    public function review(Request $request)
    {
        $this->validate($request, [
            'ratting' => 'required',
            'review' => 'required',
        ]);

        // data save
        $review = new Review();
        $review->name = Auth::guard('customer')->user()->name ? Auth::guard('customer')->user()->name : 'N / A';
        $review->email = Auth::guard('customer')->user()->email ? Auth::guard('customer')->user()->email : 'N / A';
        $review->product_id = $request->product_id;
        $review->review = $request->review;
        $review->ratting = $request->ratting;
        $review->customer_id = Auth::guard('customer')->user()->id;
        $review->status = 'pending';
        $review->save();

        Toastr::success('Thanks, Your review send successfully', 'Success!');
        return redirect()->back();
    }

    public function login()
    {
        return view('frontEnd.layouts.customer.login');
    }

    public function signin(Request $request)
    {
        $auth_check = Customer::where('phone', $request->phone)->first();
        if ($auth_check) {
            if (Auth::guard('customer')->attempt(['phone' => $request->phone, 'password' => $request->password])) {
                Toastr::success('You are login successfully', 'success!');
                if (Cart::instance('shopping')->count() > 0) {
                    return redirect()->route('customer.checkout');
                }
                return redirect()->intended('customer/account');
            }
            Toastr::error('message', 'Opps! your phone or password wrong');
            return redirect()->back();
        } else {
            Toastr::error('message', 'Sorry! You have no account');
            return redirect()->back();
        }
    }

    public function register()
    {
        return view('frontEnd.layouts.customer.register');
    }

public function setReferral($id)
    {
        // Check if ID is a Dealer
        $dealer = \App\Models\Dealer::find($id);
        if ($dealer) {
            Session::put('dealer_referral_id', $dealer->id);
        } else {
            // Check if ID is a Reseller
            $reseller = \App\Models\Reseller::find($id);
            if ($reseller) {
                Session::put('dealer_referral_id', $reseller->dealer_id);
                // Track the specific referrer
                Session::put('referrer_reseller_id', $reseller->id);
            }
        }

        Toastr::success('Referral code applied!', 'Success');
        return redirect()->route('reseller.register');
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required|unique:customers',
            'password' => 'required|min:6'
        ]);

        $last_id = Customer::orderBy('id', 'desc')->first();
        $last_id = $last_id ? $last_id->id + 1 : 1;
        $store = new Customer();
        $store->name = $request->name;
        $store->slug = strtolower(Str::slug($request->name . '-' . $last_id));
        $store->phone = $request->phone;
        $store->email = $request->email;
        $store->password = bcrypt($request->password);
        $store->verify = 1;
        $store->status = 'active';
        $store->save();

        Toastr::success('Success', 'Account Create Successfully');
        return redirect()->route('customer.login');
    }
    public function verify()
    {
        return view('frontEnd.layouts.customer.verify');
    }
    public function resendotp(Request $request)
    {
        $customer_info = Customer::where('phone', session::get('verify_phone'))->first();
        $customer_info->verify = rand(1111, 9999);
        $customer_info->save();
        $site_setting = GeneralSetting::where('status', 1)->first();
        $sms_gateway = SmsGateway::where('status', 1)->first();
        if ($sms_gateway) {
            $url = "$sms_gateway->url";
            $data = [
                "api_key" => "$sms_gateway->api_key",
                "contacts" => $customer_info->phone,
                "type" => 'text',
                "senderid" => "$sms_gateway->serderid",
                "msg" => "Dear $customer_info->name!\r\nYour account verify OTP is $customer_info->verify \r\nThank you for using $site_setting->name"
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);

        }
        Toastr::success('Success', 'Resend code send successfully');
        return redirect()->back();
    }
    public function account_verify(Request $request)
    {
        $this->validate($request, [
            'otp' => 'required',
        ]);
        $customer_info = Customer::where('phone', session::get('verify_phone'))->first();
        if ($customer_info->verify != $request->otp) {
            Toastr::error('Success', 'Your OTP not match');
            return redirect()->back();
        }

        $customer_info->verify = 1;
        $customer_info->status = 'active';
        $customer_info->save();
        Auth::guard('customer')->loginUsingId($customer_info->id);
        return redirect()->route('customer.account');
    }
    public function forgot_password()
    {
        return view('frontEnd.layouts.customer.forgot_password');
    }

    public function forgot_verify(Request $request)
    {
        $customer_info = Customer::where('phone', $request->phone)->first();
        if (!$customer_info) {
            Toastr::error('Your phone number not found');
            return back();
        }
        $customer_info->forgot = rand(1111, 9999);
        $customer_info->save();
        $site_setting = GeneralSetting::where('status', 1)->first();
        $sms_gateway = SmsGateway::where(['status' => 1, 'forget_pass' => 1])->first();
        if ($sms_gateway) {
            $url = "$sms_gateway->url";
            $data = [
                "api_key" => "$sms_gateway->api_key",
                "contacts" => $customer_info->phone,
                "type" => 'text',
                "senderid" => "$sms_gateway->serderid",
                "msg" => "Dear $customer_info->name!\r\nYour forgot password verify OTP is $customer_info->forgot \r\nThank you for using $site_setting->name"
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
        }

        session::put('verify_phone', $request->phone);
        Toastr::success('Your account register successfully');
        return redirect()->route('customer.forgot.reset');
    }

    public function forgot_resend(Request $request)
    {
        $customer_info = Customer::where('phone', session::get('verify_phone'))->first();
        $customer_info->forgot = rand(1111, 9999);
        $customer_info->save();
        $site_setting = GeneralSetting::where('status', 1)->first();
        $sms_gateway = SmsGateway::where(['status' => 1])->first();
        if ($sms_gateway) {
            $url = "$sms_gateway->url";
            $data = [
                "api_key" => "$sms_gateway->api_key",
                "contacts" => $customer_info->phone,
                "type" => 'text',
                "senderid" => "$sms_gateway->serderid",
                "msg" => "Dear $customer_info->name!\r\nYour forgot password verify OTP is $customer_info->forgot \r\nThank you for using $site_setting->name"
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);

        }

        Toastr::success('Success', 'Resend code send successfully');
        return redirect()->back();
    }
    public function forgot_reset()
    {
        if (!Session::get('verify_phone')) {
            Toastr::error('Something wrong please try again');
            return redirect()->route('customer.forgot.password');
        }
        ;
        return view('frontEnd.layouts.customer.forgot_reset');
    }
    public function forgot_store(Request $request)
    {

        $customer_info = Customer::where('phone', session::get('verify_phone'))->first();

        if ($customer_info->forgot != $request->otp) {
            Toastr::error('Success', 'Your OTP not match');
            return redirect()->back();
        }

        $customer_info->forgot = 1;
        $customer_info->password = bcrypt($request->password);
        $customer_info->save();
        if (Auth::guard('customer')->attempt(['phone' => $customer_info->phone, 'password' => $request->password])) {
            Session::forget('verify_phone');
            Toastr::success('You are login successfully', 'success!');
            return redirect()->intended('customer/account');
        }
    }
    public function account()
    {
        return view('frontEnd.layouts.customer.account');
    }
    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();
        Toastr::success('You are logout successfully', 'success!');
        return redirect()->route('customer.login');
    }
  public function checkout()
    {
        $product = Cart::instance('shopping')->content();

        $productslug = [];

        foreach (Cart::instance('shopping')->content() as $value) {
            $id = $value->options->slug ?? null;
            if ($id) {
                $productslug[] = $id;
            }
        }

        // Remove duplicates, clean array
        $proslugs = array_filter(array_unique($productslug));

        $requiresShipping = Product::whereIn('slug', $proslugs)
            ->where('free_shipping', 1)
            ->exists();

        $shippingcharge = ShippingCharge::where('status', 1)->get();
        $select_charge = ShippingCharge::where('status', 1)->first();
        $bkash_gateway = PaymentGateway::where(['status' => 1, 'type' => 'bkash'])->first();
        $shurjopay_gateway = PaymentGateway::where(['status' => 1, 'type' => 'shurjopay'])->first();
        $paystation = PaymentGateway::where(['status' => 1, 'type' => 'paystation'])->first();
        
        $shippingAmount = $select_charge->amount ?? 0.00;
        
        if ($requiresShipping) {
            // If any product has free shipping enabled, set shipping cost to 0
            $shippingAmount = 0.00;
        }
        
        Session::put('shipping', $shippingAmount);
        
        return view('frontEnd.layouts.customer.checkout', compact('shippingcharge', 'bkash_gateway', 'shurjopay_gateway', 'paystation'));
    }
    public function order_save(Request $request)
    { 


        // [DEBUG STEP 1] রিকোয়েস্ট আসার সাথে সাথে চেক করা
    \Log::info('Order Save Started. Phone: ' . $request->phone . ' | IP: ' . $request->ip());
    \Log::info('Cart Count at Start: ' . Cart::instance('shopping')->count());
    \Log::info('Cart Content: ', Cart::instance('shopping')->content()->toArray());
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required|numeric|digits:11',
            'address' => 'required',
            'area' => 'required',
        ]);
\Log::info('Validation Passed. Checking Cart again...');

       // ১. আগে ডুপ্লিকেট অর্ডার চেক করুন (Duplicate Order Check First)
        $recentOrder = Order::whereHas('shipping', function ($q) use ($request) {
            $q->where('phone', $request->phone);
        })
        ->where('created_at', '>=', now()->subMinute()) // ১ মিনিটের মধ্যে অর্ডার থাকলে আটকাবে
        ->first();

        if ($recentOrder) {
            // যদি অর্ডার অলরেডি হয়ে গিয়ে থাকে
            \Log::warning('Duplicate Order Detected for Phone: ' . $request->phone);
            
            // আপনি চাইলে এখানে Success মেসেজ দিয়ে অর্ডার সাকসেস পেজেও পাঠিয়ে দিতে পারেন
            // অথবা শুধু ওয়ার্নিং দিয়ে ব্যাক করতে পারেন
            Toastr::warning('Your order is processing or already placed.', 'Please Wait!');
            return redirect()->back();
        }

        // ২. এরপর কার্ট চেক করুন (Cart Check Second)
        if (Cart::instance('shopping')->count() <= 0) {
            \Log::error('Cart Found Empty INSIDE Controller! Redirecting back.');
            Toastr::error('Your shopping cart is empty', 'Failed!');
            return redirect()->back();
        }

        $subtotal = Cart::instance('shopping')->subtotal();

        $subtotal = str_replace(',', '', $subtotal);
        
        $subtotal = str_replace('.00', '', $subtotal);
$discount = Session::get('discount');

$shipping_area = ShippingCharge::where('id', $request->area)->first();

// ২. প্রাথমিক শিপিং ফি নির্ধারণ
if ($shipping_area) {
    $shippingfee = $shipping_area->amount;
} else {
    $shippingfee = Session::get('shipping') ? Session::get('shipping') : 0;
}

// ৩. [FIX] কার্টে থাকা প্রোডাক্টগুলোর মধ্যে কোনো ফ্রি শিপিং প্রোডাক্ট আছে কিনা চেক করা
$cart_content = Cart::instance('shopping')->content();
foreach ($cart_content as $item) {
    $product = Product::select('free_shipping')->find($item->id);
    if ($product && $product->free_shipping == 1) {
        $shippingfee = 0; // ফ্রি শিপিং থাকলে চার্জ ০ হবে
        break; // লুপ থামিয়ে দাও
    }
}

        if (Auth::guard('customer')->user()) {
            $customer_id = Auth::guard('customer')->user()->id;
        } else {
            $exits_customer = Customer::where('phone', $request->phone)->select('phone', 'id')->first();
            if ($exits_customer) {
                $customer_id = $exits_customer->id;
            } else {
                $password = rand(111111, 999999);
                $store = new Customer();
                $store->name = $request->name;
                $store->slug = $request->name;
                $store->phone = $request->phone;
                $store->password = bcrypt($password);
                $store->verify = 1;
                $store->status = 'active';
                $store->save();
                $customer_id = $store->id;
            }

        }
        $today = date('Y-m-d');

        // Count how many orders were placed today
        $todayCount = $lastOrderId = Order::max('id') ?? 0;


        // order data save
        $order = new Order();
        $order->invoice_id = date('d') . '-' . ($lastOrderId + 1);
        $order->amount = ($subtotal + $shippingfee) - $discount;
        $order->discount = $discount ? $discount : 0;
        $order->shipping_charge = $shippingfee;
        $order->customer_id = $customer_id;
        $order->order_status = 1;
        $order->note = $request->note;
        $order->order_type = $request->order_type ?? 'website';
        $order->ip_address = $request->ip();
        $order->user_agent = $request->header('User-Agent');
        $order->fbp = $request->cookie('_fbp');
        $order->fbc = $request->cookie('_fbc');
        $order->ttp = $request->cookie('_ttp');
        $order->gclid = $request->cookie('gclid');
        $order->save();

        // shipping data save
        $shipping = new Shipping();
        $shipping->order_id = $order->id;
        $shipping->customer_id = $customer_id;
        $shipping->name = $request->name;
        $shipping->phone = $request->phone;
        $shipping->address = $request->address;
        
        
        
        $shipping->area = $shipping_area ? $shipping_area->name : ($request->area == 'free' ? 'Free Shipping' : 'N/A');
        $shipping->save();

        // payment data save
        $payment = new Payment();
        $payment->order_id = $order->id;
        $payment->customer_id = $customer_id;
        $payment->payment_method = $request->payment_method;
        $payment->amount = $order->amount;
        $payment->payment_status = 'pending';
        $payment->save();

        // order details data save
        foreach (Cart::instance('shopping')->content() as $cart) {
            $order_details = new OrderDetails();
            $order_details->order_id = $order->id;
            $order_details->product_id = $cart->id;
            $order_details->product_name = $cart->name;
            $order_details->purchase_price = $cart->options->purchase_price;
            $order_details->product_color = $cart->options->product_color;
            $order_details->product_size = $cart->options->product_size;
            $order_details->sale_price = $cart->price;
            $order_details->qty = $cart->qty;
            $order_details->save();
        }

        Cart::instance('shopping')->destroy();

        Toastr::success('Thanks, Your order place successfully', 'Success!');
        $site_setting = GeneralSetting::where('status', 1)->first();
        $formattedPhone = $this->formatPhoneNumber($request->phone);
        $sms_gateway = SmsGateway::latest()->first();
        $sms_content = SmsTeamplate::where('status', 1)->where('dynamic', 'on')->first();

        if ($sms_gateway && $sms_content) {
            $contents = $sms_content->smsteamplate; // Assuming this is the template text
            $businessName = $site_setting->name ?? 'Your Business';

            $invoiceURL = route('front.invoice', [
                'id' => $order->id,
                'day' => \Carbon\Carbon::parse($order->created_at)->format('md')
            ]);

            // Replace dynamic values
            $message = str_replace(
                ['[[name]]', '[[invoiceURL]]', '[[BusinessName]]'],
                [$request->name ?? '', $invoiceURL, $businessName],
                $contents
            );

            $url = $sms_gateway->url;

            $data = [
                "UserName" => $sms_gateway->username,
                "Apikey" => $sms_gateway->api_key,
                "MobileNumber" => $formattedPhone,
                "CampaignId" => "",
                "SenderName" => $sms_gateway->serderid, // likely a typo: should be 'senderid'
                "TransactionType" => "T",
                "Message" => $message
            ];

            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

        }

        // Handle payment methods
        if ($request->payment_method == 'paystation') {
            $paystation_service = new PayStationController();

            return $paystation_service->createPayment($request, $order);


        } elseif ($request->payment_method == 'bkash') {
            return redirect('/bkash/checkout-url/create?order_id=' . $order->id);
        } elseif ($request->payment_method == 'shurjopay') {
            if ($request->area == 1) {
                $area = "Dhaka";
            } else {
                $area = "Outside Dhaka";
            }

            $info = array(
                'currency' => "BDT",
                'amount' => $order->amount,
                'order_id' => uniqid(),
                'discsount_amount' => 0,
                'disc_percent' => 0,
                'client_ip' => $request->ip(),
                'customer_name' => $request->name,
                'customer_phone' => $request->phone,
                'email' => "customer@gmail.com",
                'customer_address' => $request->address,
                'customer_city' => $area,
                'customer_state' => $area,
                'customer_postcode' => "1212",
                'customer_country' => "BD",
                'value1' => $order->id
            );

            $shurjopay_service = new ShurjopayController();

            return $shurjopay_service->checkout($info);
        } else {
            return redirect('customer/order-success/' . $order->id);
        }

    }
    public function order_newsave(Request $request)
    {

        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required|numeric|digits:11',
            'address' => 'required',
            'area' => 'required',
        ]);
       // ১. আগে ডুপ্লিকেট অর্ডার চেক করুন (Duplicate Order Check First)
        // ডাবল ক্লিকের কারণে ২য় রিকোয়েস্ট যাতে 'Cart Empty' এরর না খায়, তাই এটি আগে দেওয়া হলো
        $recentOrder = Order::whereHas('shipping', function ($q) use ($request) {
            $q->where('phone', $request->phone);
        })
        ->where('created_at', '>=', now()->subMinute()) // ১ মিনিট দেওয়া হলো যাতে তাৎক্ষণিক ডাবল সাবমিট আটকানো যায়
        ->first();

        if ($recentOrder) {
            // যদি অর্ডার অলরেডি হয়ে গিয়ে থাকে
            Toastr::warning('Your order is processing. Please wait.', 'Warning!');
            return redirect()->back();
        }

        // ২. এরপর কার্ট চেক করুন (Cart Check Second)
        if (Cart::instance('shopping')->count() <= 0) {
            Toastr::error('Your shopping empty', 'Failed!');
            return redirect()->back();
        }
        $rowIdsArray = $request->temid[0];
        $rowId = explode(',', $rowIdsArray);
        $subtotal = 0;
        foreach ($rowId as $id) {
            $item = null;
            foreach (Cart::instance('shopping')->content() as $cartItem) {
                if ($cartItem->rowId == $id) {
                    $item = $cartItem;
                    break;  // Exit the loop once we find the item
                }
            }
            if ($item) {
                $subtotal += $item->price * $item->qty;
            }
        }




        $subtotal = str_replace(',', '', $subtotal);
        $subtotal = str_replace('.00', '', $subtotal);
        $discount = Session::get('discount');

        $shippingfee = Session::get('shipping');
        $shipping_area = ShippingCharge::where('id', $request->area)->first();
        if (Auth::guard('customer')->user()) {
            $customer_id = Auth::guard('customer')->user()->id;
        } else {
            $exits_customer = Customer::where('phone', $request->phone)->select('phone', 'id')->first();
            if ($exits_customer) {
                $customer_id = $exits_customer->id;
            } else {
                $password = rand(111111, 999999);
                $store = new Customer();
                $store->name = $request->name;
                $store->slug = $request->name;
                $store->phone = $request->phone;
                $store->password = bcrypt($password);
                $store->verify = 1;
                $store->status = 'active';
                $store->save();
                $customer_id = $store->id;
            }

        }
        $today = date('Y-m-d');

        // Count how many orders were placed today
        $todayCount = Order::whereDate('created_at', $today)->count();
        // order data save
        $order = new Order();
        $order->invoice_id = date('Ymd') . '-' . str_pad($todayCount + 1, 3, '0', STR_PAD_LEFT);
        $order->amount = ($subtotal + $shippingfee) - $discount;
        $order->discount = $discount ? $discount : 0;
        $order->shipping_charge = $shippingfee;
        $order->customer_id = $customer_id;
        $order->order_status = 1;
        $order->note = $request->note;
        $order->order_type = $request->order_type ?? 'website';
        $order->ip_address = $request->ip();
        $order->user_agent = $request->header('User-Agent');
        $order->fbp = $request->cookie('_fbp');
         $order->fbc = $request->cookie('_fbc');
        $order->ttp = $request->cookie('_ttp');
        $order->save();

        // shipping data save
        $shipping = new Shipping();
        $shipping->order_id = $order->id;
        $shipping->customer_id = $customer_id;
        $shipping->name = $request->name;
        $shipping->phone = $request->phone;
        $shipping->address = $request->address;
        
        
        $shipping->area = $shipping_area ? $shipping_area->name : ($request->area == 'free' ? 'Free Shipping' : 'N/A');
        $shipping->save();

        // payment data save
        $payment = new Payment();
        $payment->order_id = $order->id;
        $payment->customer_id = $customer_id;
        $payment->payment_method = $request->payment_method;
        $payment->amount = $order->amount;
        $payment->payment_status = 'pending';
        $payment->save();

        // order details data save
        foreach ($rowId as $id) {
            // Loop through the cart contents and find the item by rowId
            $cartItem = null;
            foreach (Cart::instance('shopping')->content() as $cart) {
                if ($cart->rowId == $id) {
                    $cartItem = $cart;
                    break; // Exit the loop once we find the item
                }
            }

            // If the item is found in the cart
            if ($cartItem) {
                // Create a new OrderDetails record and save the order details
                $order_details = new OrderDetails();
                $order_details->order_id = $order->id;
                $order_details->product_id = $cartItem->id;
                $order_details->product_name = $cartItem->name;
                $order_details->purchase_price = $cartItem->options->purchase_price;
                $order_details->product_color = $cartItem->options->product_color;
                $order_details->product_size = $cartItem->options->product_size;
                $order_details->sale_price = $cartItem->price;
                $order_details->qty = $cartItem->qty;
                $order_details->save();
            }
        }


        Cart::instance('shopping')->destroy();

        Toastr::success('Thanks, Your order place successfully', 'Success!');
        $site_setting = GeneralSetting::where('status', 1)->first();

        $formattedPhone = $this->formatPhoneNumber($request->phone);
        $sms_gateway = SmsGateway::latest()->first();
        if ($sms_gateway) {
            $url = $sms_gateway->url;
            $data = [
                "UserName" => $sms_gateway->username,
                "Apikey" => $sms_gateway->api_key,
                "MobileNumber" => $formattedPhone,
                "CampaignId" => "",
                "SenderName" => $sms_gateway->serderid,
                "TransactionType" => "T",
                "Message" => "Dear $request->name!\r\nYour order has been successfully placed. check your customer panel on our website to know more about your order. Thank you for using $site_setting->name"
            ];
            $ch = curl_init($url);

            // Configure cURL options
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            ]);

            // Execute the cURL request
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        }

        // Handle payment methods
        if ($request->payment_method == 'paystation') {
            $paystation_service = new PayStationController();
            return $paystation_service->createPayment($request, $order);


        } elseif ($request->payment_method == 'bkash') {
            return redirect('/bkash/checkout-url/create?order_id=' . $order->id);
        } elseif ($request->payment_method == 'shurjopay') {
            $info = array(
                'currency' => "BDT",
                'amount' => $order->amount,
                'order_id' => uniqid(),
                'discsount_amount' => 0,
                'disc_percent' => 0,
                'client_ip' => $request->ip(),
                'customer_name' => $request->name,
                'customer_phone' => $request->phone,
                'email' => "customer@gmail.com",
                'customer_address' => $request->address,
                'customer_city' => $request->area,
                'customer_state' => $request->area,
                'customer_postcode' => "1212",
                'customer_country' => "BD",
                'value1' => $order->id
            );
            $shurjopay_service = new ShurjopayController();
            return $shurjopay_service->checkout($info);
        } else {
            return redirect('customer/order-success/' . $order->id);
        }

    }

    public function orders()
    {
        $orders = Order::where('customer_id', Auth::guard('customer')->user()->id)->with('status')->latest()->get();
        return view('frontEnd.layouts.customer.orders', compact('orders'));
    }
    public function order_success($id)
    {
        // আগের কোড ছিল: $order = Order::where('id', $id)->firstOrFail();
        // এখন আমরা রিলেশনসহ ডাটা আনব যাতে ট্র্যাকিং এ সুবিধা হয়
        $order = Order::with(['orderdetails', 'shipping', 'customer'])->where('id', $id)->firstOrFail();

        // ================= SST TRIGGER (Customer Side) =================
        
        // ১. সেটিংস চেক করা
        $gs = GeneralSetting::first();

        // ২. শর্ত: অর্ডার থাকতে হবে এবং সেটিংসে 'customer' মোড সিলেক্ট করা থাকতে হবে
        // নোট: যদি আপনি আগের ধাপগুলো ফলো করে থাকেন, তবেই 'pixel_trigger_type' কলামটি কাজ করবে
        if ($order && $gs && $gs->pixel_trigger_type == 'customer') {
            try {
                // ক. কনটেন্ট ডাটা প্রস্তুত করা
                $content_ids = [];
                $contents_tiktok = [];
                
                foreach ($order->orderdetails as $detail) {
                    $content_ids[] = (string) $detail->product_id;
                    $contents_tiktok[] = [
                        'content_id' => (string) $detail->product_id,
                        'content_type' => 'product',
                        'quantity' => $detail->qty,
                        'price' => $detail->sale_price
                    ];
                }

                // খ. ট্র্যাকিং ডাটা অ্যারে
                $trackingData = [
                    'event_name'      => 'Purchase',
                    'event_id'        => (string) $order->id,
                    'name'            => $order->shipping->name ?? null,
                    'city'            => $order->shipping->area ?? null,
                    'address'         => $order->shipping->address ?? null,
                    'zip'             => $order->shipping->zip_code ?? null,
                    'country'         => 'bd',
                    'email'           => $order->shipping->email ?? null,
                    'phone'           => $order->shipping->phone,
                    'external_id'     => (string) ($order->customer_id ?? $order->shipping->phone),
                    'amount'          => $order->amount,
                    'currency'        => 'BDT',
                    'source_url'      => url()->current(), // বর্তমান পেজের URL
                    
                    // ফ্রন্টএন্ড থেকে লাইভ কুকি ডাটা
                    'fbp'             => request()->cookie('_fbp'), 
                    'fbc'             => request()->cookie('_fbc'),
                    'ttp'             => request()->cookie('_ttp'),
                    'ip'              => request()->ip(), 
                    'user_agent'      => request()->userAgent(),
                    
                    'content_ids'     => $content_ids,
                    'contents_tiktok' => $contents_tiktok,
                    'ttp'             => $_COOKIE['_ttp'] ?? request()->cookie('_ttp'), // কুকি থেকে ttp নেওয়া
                    'user_agent'      => request()->header('User-Agent'), // সরাসরি হেডার থেকে নেওয়া ভালো
                    'ip'              => request()->ip(),
                    'gclid'           => $order->gclid,
                ];

                // গ. জব ডিসপ্যাচ করা
                SendServerSideEventJob::dispatch($trackingData);

            } catch (\Exception $e) {
                \Log::error('Customer SST Dispatch Error: ' . $e->getMessage());
            }
        }
        // ================= SST END =================
        return view('frontEnd.layouts.customer.order_success', compact('order'));
    }
    public function invoice(Request $request)
    {
        $order = Order::where(['id' => $request->id, 'customer_id' => Auth::guard('customer')->user()->id])->with('orderdetails', 'payment', 'shipping', 'customer')->firstOrFail();
        return view('frontEnd.layouts.customer.invoice', compact('order'));
    }
    public function order_note(Request $request)
    {
        $order = Order::where(['id' => $request->id, 'customer_id' => Auth::guard('customer')->user()->id])->firstOrFail();
        return view('frontEnd.layouts.customer.order_note', compact('order'));
    }
    public function profile_edit(Request $request)
    {
        $profile_edit = Customer::where(['id' => Auth::guard('customer')->user()->id])->firstOrFail();
        $districts = District::distinct()->select('district')->get();
        $areas = District::where(['district' => $profile_edit->district])->select('area_name', 'id')->get();
        return view('frontEnd.layouts.customer.profile_edit', compact('profile_edit', 'districts', 'areas'));
    }
    public function profile_update(Request $request)
    {
        $update_data = Customer::where(['id' => Auth::guard('customer')->user()->id])->firstOrFail();

        $image = $request->file('image');
        if ($image) {
            // image with intervention 
            $name = time() . '-' . $image->getClientOriginalName();
            $name = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp', $name);
            $name = strtolower(Str::slug($name));
            $uploadpath = 'public/uploads/customer/';
            $imageUrl = $uploadpath . $name;
            $img = Image::make($image->getRealPath());
            $img->encode('webp', 90);
            $width = 120;
            $height = 120;
            $img->resize($width, $height);
            $img->save($imageUrl);
        } else {
            $imageUrl = $update_data->image;
        }

        $update_data->name = $request->name;
        $update_data->phone = $request->phone;
        $update_data->email = $request->email;
        $update_data->address = $request->address;
        $update_data->district = $request->district;
        $update_data->area = $request->area;
        $update_data->image = $imageUrl;
        $update_data->save();

        Toastr::success('Your profile update successfully', 'Success!');
        return redirect()->route('customer.account');
    }

    public function order_track()
    {
        return view('frontEnd.layouts.customer.order_track');
    }

    public function order_track_result(Request $request)
    {

        $phone = $request->phone;
        $invoice_id = $request->invoice_id;

        if ($phone != null && $invoice_id == null) {
            $order = DB::table('orders')
                ->join('shippings', 'orders.id', '=', 'shippings.order_id')
                ->where(['shippings.phone' => $request->phone])
                ->get();

        } else if ($invoice_id && $phone) {
            $order = DB::table('orders')
                ->join('shippings', 'orders.id', '=', 'shippings.order_id')
                ->where(['orders.invoice_id' => $request->invoice_id, 'shippings.phone' => $request->phone])
                ->get();
        }

        if ($order->count() == 0) {

            Toastr::error('message', 'Something Went Wrong !');
            return redirect()->back();
        }

        //   return $order->count();



        return view('frontEnd.layouts.customer.tracking_result', compact('order'));
    }


    public function change_pass()
    {
        return view('frontEnd.layouts.customer.change_password');
    }

    public function password_update(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required_with:new_password|same:new_password|'
        ]);

        $customer = Customer::find(Auth::guard('customer')->user()->id);
        $hashPass = $customer->password;

        if (Hash::check($request->old_password, $hashPass)) {

            $customer->fill([
                'password' => Hash::make($request->new_password)
            ])->save();

            Toastr::success('Success', 'Password changed successfully!');
            return redirect()->route('customer.account');
        } else {
            Toastr::error('Failed', 'Old password not match!');
            return redirect()->back();
        }
    }
    public function paystationSuccess(Request $request)
    {
        // Verify payment with PayStation
        $order_id = $request->invoice_id;
        $paystation_service = new PayStationController();
        $json = $paystation_service->verifyPayment($order_id);
        $data = json_decode($json);
        if ($data[0]->status_code != 200) {
            Toastr::error('Failed', 'Your order place failed');
            return "failed";
            return redirect()->route('customer.checkout');
        }
        $order = Order::where('id', $data[0]->opt_a)->first();
        $order->order_status = 2;
        $order->save();

        $payment = Payment::where(['order_id' => $order->id])->first();
        $payment->payment_method = $data[0]->payment_method;
        $payment->trx_id = $data[0]->trx_id;
        $payment->sender_number = $data[0]->payer_mobile_no;
        $payment->payment_status = $data[0]->trx_status;
        $payment->save();

        Cart::instance('shopping')->destroy();
        Toastr::success('Thanks, Your order place successfully', 'Success!');
        return redirect('customer/order-success/' . $order->id);



    }

    public function paystationFail(Request $request)
    {
        return redirect()->route('checkout')->with('error', 'Payment failed!');
    }

    public function paystationCancel(Request $request)
    {
        return redirect()->route('checkout')->with('warning', 'Payment cancelled!');
    }


    private function formatPhoneNumber($phone)
    {


        // Remove any non-numeric characters (spaces, dashes, plus signs, etc.)
        $phone = preg_replace('/\D/', '', $phone);

        // Check if the phone number starts with the local prefix (e.g., '017', '176')
        if (substr($phone, 0, 1) == '0' && strlen($phone) == 11) {
            // If the number starts with '0', replace '0' with '880' (Bangladesh country code)
            $phone = '880' . substr($phone, 1);
        } elseif (substr($phone, 0, 3) == '880' && strlen($phone) == 12) {
            // If the number already starts with '880', it's in international format, remove the '+' if present
            $phone = '880' . substr($phone, 3);
        } elseif (substr($phone, 0, 1) == '1' && strlen($phone) == 10) {
            // If the number starts with '1' and is 10 digits, prefix it with '880'
            $phone = '880' . $phone;
        } elseif (substr($phone, 0, 3) == '017' && strlen($phone) == 11) {
            // If the number starts with '017' and is 11 digits, prefix it with '880'
            $phone = '880' . substr($phone, 1);
        }


        return $phone;
    }


   // CustomerController.php

public function ajaxTrackEvent(Request $request)
{
    // ১. জাভাস্ক্রিপ্ট থেকে পাঠানো Event ID নেওয়া (না থাকলে নতুন তৈরি হবে)
    $eventId = $request->event_id ?? ('evt_' . time() . '_' . rand(1000, 9999));
    $eventName = $request->event_name ?? 'Contact';

    // ২. ইউজার ডাটা সংগ্রহ (লগিন ইউজার অথবা রিকোয়েস্ট থেকে)
    $user = Auth::guard('customer')->user();
    
    // স্মার্ট ডাটা পিকিং: যদি লগিন থাকে ভালো, না হলে জাভাস্ক্রিপ্ট থেকে আসা ডাটা নিবে
    $phone = $user ? $user->phone : $request->phone;
    $email = $user ? $user->email : $request->email;
    $name  = $user ? $user->name  : $request->name;

    // ৩. জব ডিসপ্যাচ
    $trackingData = [
        'event_name'      => $eventName,
        'event_id'        => $eventId,     // সেইম আইডি
        'source_url'      => $request->source_url,
        'phone'           => $phone,
        'email'           => $email,
        'name'            => $name,
        'external_id'     => $phone,
        'fbp'             => $request->fbp, 
        'fbc'             => $request->fbc,
        'ttp'             => $request->ttp,
        'ip'              => $request->ip(), 
        'user_agent'      => $request->header('User-Agent'),
    ];

    try {
        session()->save();
        \App\Jobs\SendServerSideEventJob::dispatchAfterResponse($trackingData);
        // ডিবাগিং এর জন্য লগ রাখা হলো
        \Log::info('SST Job Dispatched for: ' . $eventName . ' ID: ' . $eventId);
        return response()->json(['status' => 'success']);
    } catch (\Exception $e) {
        \Log::error('SST Dispatch Error: ' . $e->getMessage());
        return response()->json(['status' => 'error']);
    }
}
}