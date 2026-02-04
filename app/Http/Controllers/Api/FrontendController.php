<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\GeneralSetting;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\CreatePage;
use App\Models\SocialMedia;
use App\Models\Contact;
use App\Models\Customer;
use App\Models\ShippingCharge;
use App\Models\Campaign;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Payment;
use App\Models\PaymentGateway;
use App\Models\SmsGateway;
use App\Models\SmsTeamplate;
use Carbon\Carbon;
use Response;
use Hash;
use Cart;
use Session;
use Auth;
use Mail;
use Str;
use DB;
class FrontendController extends Controller
{
   public function appconfig()
    {
        $data = GeneralSetting::where('status',1)->select('id','name','white_logo','dark_logo','favicon')->first();
        return response()->json(['status' => 'success','message'=>'Data fatch successfully','data'=>$data]);
    }
    
    public function slider()
    {
        $data = Banner::where(['status'=>1,'category_id'=>1])->select('id','image','status','category_id','link')->get();
        return response()->json(['status' => 'success','message'=>'Data fatch successfully','data'=>$data]);
    }
    
    public function categorymenu(){
        $data = Category::where(['status'=>1])->select('id','slug','name','image')->with('menusubcategories','menusubcategories.menuchildcategories')->get();
        return response()->json(['status' => 'success','message'=>'Data fatch successfully','data'=>$data]);
   }
   
    public function hotdealproduct(){
        $data = Product::where(['status'=>1])->select('id','slug','name','topsale','old_price','new_price')->with('image')->get();
        return response()->json(['status' => 'success','message'=>'Data fatch successfully','data'=>$data]);
   }
   
   public function homepageproduct(){
        $data = Category::where(['status'=>1])->select('id','slug','name')->with('products','products.image')->get();
        return response()->json(['status' => 'success','message'=>'Data fatch successfully','data'=>$data]);
   }
   
   public function footermenuleft(){
        $data = CreatePage::where(['status'=>1])->select('id','slug','name')->limit(3)->get();
        return response()->json(['status' => 'success','message'=>'Data fatch successfully','data'=>$data]);
   }
   public function footermenuright(){
        $data = CreatePage::where(['status'=>1])->select('id','slug','name')->skip(3)->limit(10)->get();
        return response()->json(['status' => 'success','message'=>'Data fatch successfully','data'=>$data]);
   }
   public function socialmedia(){
        $data = SocialMedia::where(['status'=>1])->get();
        return response()->json(['status' => 'success','message'=>'Data fatch successfully','data'=>$data]);
   }
   public function contactinfo(){
        $data = Contact::where(['status'=>1])->first();
        return response()->json(['status' => 'success','message'=>'Data fatch successfully','data'=>$data]);
   }
   
//   Home Page Function End ====================

    public function catproduct($id){
        $category = Category::where(['status'=>1, 'id'=>$id])->select('id','name','slug')->first();
        $data = Product::where(['status'=>1, 'category_id'=>$category->id])->select('id','slug','name','old_price','new_price', 'category_id')->with('image')->orderBy('id','DESC')->get();
        return response()->json(['status' => 'success','message'=>'Data fatch successfully','data'=>$data, 'category'=>$category]);
    }
    
    


// In your FrontendController class
public function landingpage($slug)
{
    try {
        // Get campaign data with images
        $campaign_data = Campaign::where('slug', $slug)->with('images')->first();
        
        if (!$campaign_data) {
            return response()->json([
                'success' => false,
                'message' => 'Campaign not found',
                'data' => null
            ], 404);
        }

        // Get product IDs from campaign
        $productIds = json_decode($campaign_data->product_id);
        
        if (!$productIds || !is_array($productIds)) {
            return response()->json([
                'success' => false,
                'message' => 'No valid products found for this campaign',
                'data' => null
            ], 400);
        }

        // Get products
        $products = Product::whereIn('id', $productIds)
            ->where('status', 1)
            ->with('image')
            ->get();

        if ($products->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No active products found',
                'data' => null
            ], 404);
        }

        // Clear cart and add products (for session-based functionality)
        Cart::instance('shopping')->destroy();
        $cart_count = Cart::instance('shopping')->count();
        
        if ($cart_count == 0) {
            foreach($products as $product) {
                Cart::instance('shopping')->add([
                    'id' => $product->id,
                    'name' => $product->name,
                    'qty' => 1,
                    'price' => $product->new_price,
                    'options' => [
                        'slug' => $product->slug,
                        'image' => $product->image ? $product->image->image : null,
                        'old_price' => $product->old_price,
                        'purchase_price' => $product->purchase_price,
                    ],
                ]);
            }
        }

        // Get first product for featured display
        $featured_product = $products->first();

        // Get shipping charges
        $shippingcharges = ShippingCharge::where('status', 1)->get();
        $default_shipping = ShippingCharge::where('status', 1)->first();
        
        if ($default_shipping) {
            Session::put('shipping', $default_shipping->amount);
        }

        // Get cart contents for API response
        $cart_items = Cart::instance('shopping')->content()->map(function($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'qty' => $item->qty,
                'price' => $item->price,
                'subtotal' => $item->subtotal,
                'options' => $item->options
            ];
        });

        // Prepare response data
        $response_data = [
            'campaign' => [
                'id' => $campaign_data->id,
                'slug' => $campaign_data->slug,
                'banner' => $campaign_data->banner ?? null,
                'title' => $campaign_data->banner_title ?? null,
                'video' => $campaign_data->video ?? null,
                'short_description' => $campaign_data->short_description ?? null,
                'short_description' => $campaign_data->short_description ?? null,
                'description' => $campaign_data->description ?? null,
                'review' => $campaign_data->review,
            ],
            'products' => $products->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'new_price' => $product->new_price,
                    'old_price' => $product->old_price,
                    'purchase_price' => $product->purchase_price,
                    'status' => $product->status,
                    'description' => $product->description ?? null,
                    'image' => $product->image ? [
                        'id' => $product->image->id,
                        'image_url' => $product->image->image,
                        'alt_text' => $product->image->alt_text ?? null
                    ] : null
                ];
            }),
            'featured_product' => $featured_product ? [
                'id' => $featured_product->id,
                'name' => $featured_product->name,
                'slug' => $featured_product->slug,
                'new_price' => $featured_product->new_price,
                'old_price' => $featured_product->old_price,
                'image' => $featured_product->image ? [
                    'id' => $featured_product->image->id,
                    'image_url' => $featured_product->image->image,
                    'alt_text' => $featured_product->image->alt_text ?? null
                ] : null
            ] : null,
            'shipping' => [
                'charges' => $shippingcharges->map(function($charge) {
                    return [
                        'id' => $charge->id,
                        'name' => $charge->name ?? null,
                        'amount' => $charge->amount,
                        'description' => $charge->description ?? null
                    ];
                }),
                'default_charge' => $default_shipping ? [
                    'id' => $default_shipping->id,
                    'amount' => $default_shipping->amount,
                    'name' => $default_shipping->name ?? null
                ] : null
            ],
            'cart' => [
                'items' => $cart_items,
                'count' => Cart::instance('shopping')->count(),
                'subtotal' => Cart::instance('shopping')->subtotal(),
                'total' => Cart::instance('shopping')->total()
            ]
        ];

        return response()->json([
            'success' => true,
            'message' => 'Landing page data retrieved successfully',
            'data' => $response_data
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while retrieving data',
            'error' => $e->getMessage()
        ], 500);
    }
}



 
  public function mydata_save(Request $request){
      try {
            // Validation
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'phone' => 'required|numeric|digits:11',
                'address' => 'required|string|max:500',
                'area' => 'required',
                'note' => 'nullable|string|max:500',
            ]);
            
            

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check if cart is empty
            if (empty($request->cart_items)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your shopping cart is empty',
                ], 400);
            }

            // Prevent duplicate order within 1 hour by phone number
            $recentOrder = Order::whereHas('shipping', function ($q) use ($request) {
                $q->where('phone', $request->phone);
            })
            ->where('created_at', '>=', now()->subHour())
            ->first();

            if ($recentOrder) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already placed an order in the last hour. Please wait before placing another order.',
                ], 429);
            }

            // Calculate subtotal from cart items
            $subtotal = collect($request->cart_items)->sum(function ($item) {
                return $item['price'] * $item['qty'];
            });

            // Get discount and shipping
            $discount = $request->discount ?? 0;
            $shipping_area = ShippingCharge::find($request->area);
            $shippingfee = $shipping_area->charge ?? 0;

            // Handle customer creation/retrieval
            $customer_id = $this->handleCustomer($request);

            DB::beginTransaction();

            // Create order
            $order = new Order();
            $order->invoice_id = $this->generateInvoiceId();
            $order->amount = ($subtotal + $shippingfee) - $discount;
            $order->discount = $discount;
            $order->shipping_charge = $shippingfee;
            $order->customer_id = $customer_id;
            $order->order_status = 1;
            $order->note = $request->note;
            $order->save();

            // Create shipping record
            $shipping = new Shipping();
            $shipping->order_id = $order->id;
            $shipping->customer_id = $customer_id;
            $shipping->name = $request->name;
            $shipping->phone = $request->phone;
            $shipping->address = $request->address;
            $shipping->area = $shipping_area->name;
            $shipping->save();

            // Create payment record
            $payment = new Payment();
            $payment->order_id = $order->id;
            $payment->customer_id = $customer_id;
            $payment->payment_method = $request->payment_method;
            $payment->amount = $order->amount;
            $payment->payment_status = 'pending';
            $payment->save();

            // Create order details
            foreach ($request->cart_items as $item) {
                $order_details = new OrderDetails();
                $order_details->order_id = $order->id;
                $order_details->product_id = $item['id'];
                $order_details->product_name = $item['name'];
                $order_details->purchase_price = $item['options']['purchase_price'] ?? $item['price'];
                $order_details->product_color = $item['options']['product_color'] ?? null;
                $order_details->product_size = $item['options']['product_size'] ?? null;
                $order_details->sale_price = $item['price'];
                $order_details->qty = $item['qty'];
                $order_details->save();
            }

            DB::commit();

            // Send SMS notification
            $smsResult = $this->sendSmsNotification($request, $order);

            // Prepare response data
            $responseData = [
                'success' => true,
                'message' => 'Order placed successfully!',
                'data' => [
                    'order_id' => $order->id,
                    'invoice_id' => $order->invoice_id,
                    'amount' => $order->amount,
                    'order_status' => $order->order_status,
                    'sms_sent' => $smsResult['sent'],
                    'sms_message' => $smsResult['message']
                ]
            ];

            return response()->json($responseData, 201);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Order creation failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to process order. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    
    
}


private function handleCustomer(Request $request)
    {
        if (Auth::guard('customer')->user()) {
            return Auth::guard('customer')->user()->id;
        }

        $existingCustomer = Customer::where('phone', $request->phone)
            ->select('phone', 'id')
            ->first();

        if ($existingCustomer) {
            return $existingCustomer->id;
        }

        // Create new customer
        $password = rand(111111, 999999);
        $customer = new Customer();
        $customer->name = $request->name;
        $customer->slug = str_slug($request->name) ?: str_slug('customer-' . $request->phone);
        $customer->phone = $request->phone;
        $customer->password = bcrypt($password);
        $customer->verify = 1;
        $customer->status = 'active';
        $customer->save();

        return $customer->id;
    }

    private function generateInvoiceId()
    {
        do {
            $invoiceId = rand(11111, 99999);
        } while (Order::where('invoice_id', $invoiceId)->exists());

        return $invoiceId;
    }

    private function sendSmsNotification(Request $request, Order $order)
    {
        try {
            $site_setting = GeneralSetting::where('status', 1)->first();
            $formattedPhone = $this->formatPhoneNumber($request->phone);
            $sms_gateway = SmsGateway::latest()->first();
            $sms_content = SmsTeamplate::where('status', 1)->where('dynamic', 'on')->first();

            if (!$sms_gateway || !$sms_content) {
                return ['sent' => false, 'message' => 'SMS gateway not configured'];
            }

            $businessName = $site_setting->name ?? 'Your Business';
            $invoiceURL = route('front.invoice', [
                'id' => $order->id,
                'day' => Carbon::parse($order->created_at)->format('md')
            ]);

            $message = str_replace(
                ['[[name]]', '[[invoiceURL]]', '[[BusinessName]]'],
                [$request->name ?? '', $invoiceURL, $businessName],
                $sms_content->smsteamplate
            );

            $data = [
                "UserName" => $sms_gateway->username,
                "Apikey" => $sms_gateway->api_key,
                "MobileNumber" => $formattedPhone,
                "CampaignId" => "",
                "SenderName" => $sms_gateway->serderid,
                "TransactionType" => "T",
                "Message" => $message
            ];

            $ch = curl_init($sms_gateway->url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
                CURLOPT_TIMEOUT => 30,
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            if ($httpCode == 200) {
                return ['sent' => true, 'message' => 'SMS sent successfully'];
            } else {
                return ['sent' => false, 'message' => 'Failed to send SMS'];
            }

        } catch (\Exception $e) {
            Log::error('SMS sending failed: ' . $e->getMessage());
            return ['sent' => false, 'message' => 'SMS service unavailable'];
        }
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

    // Get order details API
    public function show($id)
    {
        try {
            $order = Order::with(['shipping', 'payment', 'orderDetails', 'customer'])
                ->find($id);

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $order
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve order'
            ], 500);
        }
    }
    
    public function cartRemoveApi(Request $request)
{
    try {
        $itemId = $request->id;

        // Remove item by setting quantity to 0
        Cart::instance('shopping')->update($itemId, 0);

        // Get updated cart content
        $cartItems = Cart::instance('shopping')->content();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart.',
            'cart' => $cartItems
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to remove item from cart.',
            'error' => $e->getMessage()
        ], 500);
    }
}

    // Get shipping areas API
    public function shippingChargeApi(Request $request)
    {
        $productSlugs = $request->proid;

    // Ensure $productSlugs is an array
    if (!is_array($productSlugs)) {
        $productSlugs = explode(',', $productSlugs);
    }

    // Determine if any of the selected products require shipping (free_shipping = 0)
    $requiresShipping = Product::whereIn('slug', $productSlugs)
        ->where('free_shipping', 1)
        ->exists();

    // Get the selected shipping charge
    $shippingCharge = ShippingCharge::find($request->id);

    // Apply shipping logic
    $shippingAmount = $shippingCharge->amount ?? 0.00;

    if ($requiresShipping) {
        $shippingAmount = 0.00;
    }

    // Optional: store in session only if using session-based APIs
    // session(['shipping' => $shippingAmount]);

        return response()->json([
            'success' => true,
            'shipping_amount' => $shippingAmount,
        ]);
    }
    
    public function cartMyIncrementApi(Request $request)
 {
    try {
        $itemId = $request->id;

        $item = Cart::instance('shopping')->get($itemId);

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found in cart.'
            ], 404);
        }

        $newQty = $item->qty + 1;
        Cart::instance('shopping')->update($itemId, $newQty);

        return response()->json([
            'success' => true,
            'message' => 'Item quantity incremented.',
            'item' => [
                'id' => $itemId,
                'name' => $item->name,
                'qty' => $newQty,
                'price' => $item->price,
                'subtotal' => $item->price * $newQty
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error incrementing item.',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function cartMyDecrementApi(Request $request)
{
    try {
        $itemId = $request->id;

        $item = Cart::instance('shopping')->get($itemId);

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found in cart.'
            ], 404);
        }

        $newQty = $item->qty - 1;

        if ($newQty <= 0) {
            // Remove the item from the cart if quantity goes below or equals 0
            Cart::instance('shopping')->remove($itemId);

            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart.',
                'item_id' => $itemId
            ]);
        }

        Cart::instance('shopping')->update($itemId, $newQty);

        return response()->json([
            'success' => true,
            'message' => 'Item quantity decremented.',
            'item' => [
                'id' => $itemId,
                'name' => $item->name,
                'qty' => $newQty,
                'price' => $item->price,
                'subtotal' => $item->price * $newQty
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error decrementing item.',
            'error' => $e->getMessage()
        ], 500);
    }
}
   
   
    
}