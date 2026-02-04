<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\District;
use App\Models\OrderStatus;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Shipping;
use App\Models\ShippingCharge;
use App\Models\Payment;
use App\Models\Product;
use App\Models\City;
use App\Models\Thana;
use App\Models\Category;
use App\Models\User;
use App\Models\Courierapi;
use App\Models\SmsTeamplate;
use App\Models\SmsGateway;
use App\Models\Wholesaler;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Jobs\SendServerSideEventJob;
use App\Models\InventoryLog;
use App\Models\GeneralSetting;
use Session;
use Cart;
use Auth;
use Toastr;
use Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderController extends Controller
{




    public function index(Request $request, $slug = 'all')
    {
        // Initialize order query
        $show_data = Order::orderBy('id', 'desc')
            ->with('shipping.shipping_charge', 'customer', 'orderdetails.product.image', 'status', 'user')
            ->where(function ($query) {
                $query->whereNull('customer_type')
                    ->orWhere('customer_type', '!=', 'wholesale');
            });
        // If author, filter orders by userorder_id
        $isAuthor = auth()->user()->hasRole('Book Author');
        if ($isAuthor) {
            $show_data = $show_data->where('userorder_id', Auth::user()->id);
        }

        // Handle slug-based status filtering
        if ($slug === 'all') {
            $order_status = (object) [
                'name' => 'All',
                'orders_count' => Order::count(),
            ];
        } else {
            $order_status = OrderStatus::where('slug', $slug)->withCount('orders')->first();

            if ($order_status) {
                $show_data = $show_data->where('order_status', $order_status->id);
            } else {
                $order_status = (object) [
                    'name' => 'Unknown',
                    'orders_count' => 0,
                ];
                $show_data->whereRaw('1 = 0'); // No results
            }
        }

        // Apply all filters
        $show_data = $show_data

         // Date filter logic (today, week, month)
->when($request->filled('date_filter'), function ($query) use ($request) {
    $date_filter = $request->date_filter;

    if ($date_filter === 'today') {
        // whereDate এর বদলে whereBetween ব্যবহার করা হয়েছে
        $query->whereBetween('created_at', [
            Carbon::today()->startOfDay(), 
            Carbon::today()->endOfDay()
        ]);
    } elseif ($date_filter === 'this_week') {
        $query->whereBetween('created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ]);
    } elseif ($date_filter === 'this_month') {
        // whereMonth এর বদলে whereBetween ব্যবহার করা হয়েছে
        $query->whereBetween('created_at', [
            Carbon::now()->startOfMonth(), 
            Carbon::now()->endOfMonth()
        ]);
    }
})

            // ⭐ NEW — Custom Date Range Filter
            ->when($request->filled('start_date') && $request->filled('end_date'), function ($query) use ($request) {
                $query->whereBetween('created_at', [
                    $request->start_date . ' 00:00:00',
                    $request->end_date . ' 23:59:59'
                ]);
            })

            // Order source
            ->when($request->filled('order_source'), function ($query) use ($request) {
                $query->where('order_source', $request->order_source);
            })

            // Order status filter only when slug = all
            ->when($request->filled('order_status') && $slug === 'all', function ($query) use ($request) {
                $query->where('order_status', $request->order_status);
            })

            // Assign status filter
            ->when($request->filled('assign_status'), function ($query) use ($request) {
                $query->where('user_id', $request->assign_status);
            })

            // Keyword search filter
            ->when($request->filled('keyword'), function ($query) use ($request) {
                $keyword = $request->keyword;

                $query->where(function ($subQuery) use ($keyword) {
                    $subQuery->where('invoice_id', 'LIKE', '%' . $keyword . '%')
                        ->orWhereHas('shipping', function ($shippingQuery) use ($keyword) {
                            $shippingQuery->where('phone', 'LIKE', '%' . $keyword . '%')
                                ->orWhere('name', 'LIKE', '%' . $keyword . '%');
                        });
                });
            });

        // Final order results
        // Final order results
$show_data = $show_data->paginate(30);

        // Extra Data
     // ১. ইউজার ডাটা অপ্টিমাইজ (শুধু নাম ও আইডি সিলেক্ট করা হয়েছে)
        $users = User::where('status', 1)->select('id', 'name')->get();
        
        $orderstatus = OrderStatus::where('status', 1)->get();
        $steadfast = Courierapi::where(['status' => 1, 'type' => 'steadfast'])->first();
        $pathao_info = Courierapi::where(['status' => 1, 'type' => 'pathao'])->first();

        // Pathao courier data
        $pathaocities = [];
        $pathaostore = [];

        if ($pathao_info && $pathao_info->token) {
            // ২. API কল ক্যাশ করা হয়েছে (২৪ ঘন্টার জন্য মেমোরিতে সেভ থাকবে)
            // বারবার Pathao সার্ভারে রিকোয়েস্ট যাবে না, তাই লোডিং হবে সুপার ফাস্ট
            $pathaocities = Cache::remember('pathao_cities', 60 * 24, function () use ($pathao_info) {
                try {
                    $response = Http::timeout(5)->withHeaders([
                        'Authorization' => 'Bearer ' . $pathao_info->token,
                        'Content-Type' => 'application/json',
                    ])->get($pathao_info->url . '/aladdin/api/v1/city-list');

                    return $response->successful() ? $response->json() : ['data' => ['data' => []]];
                } catch (\Exception $e) {
                    return ['data' => ['data' => []]];
                }
            });

            $pathaostore = Cache::remember('pathao_stores', 60 * 24, function () use ($pathao_info) {
                try {
                    $response = Http::timeout(5)->withHeaders([
                        'Authorization' => 'Bearer ' . $pathao_info->token,
                        'Content-Type' => 'application/json',
                    ])->get($pathao_info->url . '/aladdin/api/v1/stores');

                    return $response->successful() ? $response->json() : ['data' => ['data' => []]];
                } catch (\Exception $e) {
                    return ['data' => ['data' => []]];
                }
            });
        }

        // ৩. প্রোডাক্ট এবং সিটি ডাটা অপ্টিমাইজ (অপ্রয়োজনীয় কলাম বাদ দেওয়া হয়েছে)
        // আগে পুরো টেবিল লোড হতো, এখন শুধু আইডি আর নাম লোড হবে
        $producthf = Product::where('status', 1)->select('id', 'name', 'product_code')->get();
        $cityg = City::where('status', 1)->select('id', 'name')->get();
        $smsteamplate = SmsTeamplate::where('status', 1)->get();

        // If user is NOT an author:
        if (!$isAuthor) {
            return view("backEnd.order.all_order", compact(
                'show_data',
                'order_status',
                'users',
                'steadfast',
                'pathaostore',
                'pathaocities',
                'producthf',
                'cityg',
                'smsteamplate',
                'orderstatus'
            ));
        }

        // If user IS an author:
        $authorId = Auth::user()->id;

        $orderDetails = OrderDetails::with(['product.image', 'order.shipping', 'order.status'])
            ->whereHas('product', function ($query) use ($authorId) {
                $query->where('author_id', $authorId);
            })
            ->whereHas('order', function ($query) {
                $query->whereNotIn('order_status', [6, 7]); // Exclude cancelled/returned
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $productSummary = OrderDetails::select(
            'product_id',
            DB::raw('SUM(qty) as total_quantity'),
            DB::raw('COUNT(DISTINCT order_id) as total_orders')
        )
            ->whereHas('product', function ($query) use ($authorId) {
                $query->where('author_id', $authorId);
            })
            ->whereHas('order', function ($query) {
                $query->whereNotIn('order_status', [6, 7]);
            })
            ->groupBy('product_id')
            ->with('product')
            ->get();

        $totalRoyalty = 0;
        $totalQuantity = 0;
        $totalOrders = $orderDetails->count();

        foreach ($orderDetails as $detail) {
            $royalty = optional($detail->product)->loyalty ?? 0;
            $totalRoyalty += ($royalty * $detail->qty);
            $totalQuantity += $detail->qty;
        }

        return view('author.orderRoality', compact(
            'orderDetails',
            'productSummary',
            'totalRoyalty',
            'totalQuantity',
            'totalOrders'
        ));
    }


 public function sound()
    {
        // [OPTIMIZED] লুপ বাদ দিয়ে এক লাইনে সব আপডেট (মাত্র ১টি ডাটাবেস কুয়েরি)
        $updatedCount = Order::where('sound_status', 0)->update(['sound_status' => 1]);

        if ($updatedCount > 0) {
            return response('Succefull', 200);
        } else {
            return response('No New Orders', 200);
        }
    }
    public function pathaocity(Request $request)
    {
        $pathao_info = Courierapi::where(['status' => 1, 'type' => 'pathao'])->select('id', 'type', 'url', 'token', 'status')->first();
        if ($pathao_info) {

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $pathao_info->token,
                'Content-Type' => 'application/json',
            ])->get($pathao_info->url . '/aladdin/api/v1/cities/' . $request->city_id . '/zone-list');
            $pathaozones = $response->json();
            return response()->json($pathaozones);
        } else {
            return response()->json([]);
        }
    }
    public function pathaozone(Request $request)
    {
        $pathao_info = Courierapi::where(['status' => 1, 'type' => 'pathao'])->select('id', 'type', 'url', 'token', 'status')->first();
        if ($pathao_info) {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $pathao_info->token,
                'Content-Type' => 'application/json',
            ])->get($pathao_info->url . '/aladdin/api/v1/zones/' . $request->zone_id . '/area-list');
            $pathaoareas = $response->json();
            return response()->json($pathaoareas);
        } else {
            return response()->json([]);
        }
    }

    public function order_pathao(Request $request)
    {

        $orders_id = explode(',', $request->selected_order_ids);
        $results = [];


        foreach ($orders_id as $order_id) {
            $order = Order::with('shipping')->find($order_id);
            $order_count = OrderDetails::select('order_id')->where('order_id', $order->id)->count();

            if (!$order) {
                $results[] = [
                    'order_id' => $order_id,
                    'status' => 'failed',
                    'message' => 'Invalid order or courier configuration.'
                ];
                continue;
            }

            // Determine which courier service to use
            $courier_type = $request->courier_type; // Add this field to your form

            if ($courier_type == 'pathao') {
                // Pathao integration
                $pathao_info = Courierapi::where(['status' => 1, 'type' => 'pathao'])->select('id', 'type', 'url', 'token', 'status')->first();
                if ($pathao_info) {
                    $response = Http::withHeaders([
                        'Authorization' => 'Bearer ' . $pathao_info->token,
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                    ])->post($pathao_info->url . '/aladdin/api/v1/orders', [
                                'store_id' => $request->pathaostore,
                                'merchant_order_id' => $order->invoice_id,
                                'sender_name' => 'Test',
                                'sender_phone' => $order->shipping->phone ?? '',
                                'recipient_name' => $order->shipping->name ?? '',
                                'recipient_phone' => $order->shipping->phone ?? '',
                                'recipient_address' => $order->shipping->address ?? '',
                                'recipient_city' => $request->pathaocity,
                                'recipient_zone' => $request->pathaozone,
                                'recipient_area' => $request->pathaoarea,
                                'delivery_type' => 48,
                                'item_type' => 2,
                                'special_instruction' => 'Special note- product must be check after delivery',
                                'item_quantity' => $order_count,
                                'item_weight' => 0.5,
                                'amount_to_collect' => round($order->amount),
                                'item_description' => 'Special note- product must be check after delivery',
                            ]);

                    $order->courier = "pathao";
                    $order->tracking_id = $response['data']['consignment_id'];
                    $order->order_status = 5;

                    $order->save();


                    if ($response->status() == 200) {
                        $results[] = [
                            'order_id' => $order->id,
                            'status' => 'success',
                            'tracking_id' => $response['data']['consignment_id']
                        ];

                        Toastr::success("Order #{$order->id} → Tracking ID: " . $response['data']['consignment_id'], 'Courier Assigned');
                    } else {
                        $results[] = [
                            'order_id' => $order->id,
                            'status' => 'failed',
                            'message' => $response['message'] ?? 'Unknown error'
                        ];
                        Toastr::error("Order #{$order->id} → " . ($response['message'] ?? 'Courier Order Failed'), 'Failed');
                    }
                }
            } elseif ($courier_type == 'steadfast') {
                // Steadfast integration
                $steadfast_info = Courierapi::where(['status' => 1, 'type' => 'steadfast'])->select('id', 'type', 'url', 'token', 'api_key', 'secret_key', 'status')->first();
                if ($steadfast_info) {
                    $cityname = City::find($order->shipping->district)?->name ?? '';

                    // Order products
                    $products = OrderDetails::where('order_id', $order->id)->get()->map(function ($detail) {
                        return [
                            'product_id' => $detail->product_id,
                            'product_name' => $detail->product_name,
                            'product_quantity' => $detail->qty,
                            'unit_price' => $detail->unit_price,
                        ];
                    })->toArray();
                    $order_count = count($products);

                    // API Request
                    $response = Http::withHeaders([
                        'Api-Key' => $steadfast_info->api_key,
                        'Secret-Key' => $steadfast_info->secret_key,
                        'Accept' => 'application/json',
                    ])->post($steadfast_info->url . '/create_order', [
                                'invoice' => $order->invoice_id,
                                'recipient_name' => $order->shipping->name ?? '',
                                'recipient_phone' => $order->shipping->phone ?? '',
                                'recipient_address' => $order->shipping->address ?? '',
                                'recipient_city' => $cityname,
                                'recipient_zone' => $order->shipping->thana ?? '',
                                'num_items' => $order_count,
                                'parcel_weight' => 0.5,
                                'payment_method' => 'COD',
                                'node' => 'Handle with care',
                                'cod_amount' => round($order->amount),
                                'products' => $products,
                            ]);

                    $responseData = $response->json();

                    $order->courier = "steadfast";
                    $order->tracking_id = $responseData['consignment']['tracking_code'];
                    $order->order_status = 5;

                    $order->save();

                    if ($responseData['status'] == 200 && isset($responseData['consignment'])) {
                        $results[] = [
                            'order_id' => $order->id,
                            'status' => 'success',
                            'tracking_id' => $responseData['consignment']['tracking_code']
                        ];
                        Toastr::success("Order #{$order->id} → Tracking ID: " . $responseData['consignment']['tracking_code'], 'Courier Assigned');
                    } else {
                        $results[] = [
                            'order_id' => $order->id,
                            'status' => 'failed',
                            'message' => $responseData['message'] ?? 'Unknown error'
                        ];
                        Toastr::error("Order #{$order->id} → " . ($responseData['message'] ?? 'Courier Order Failed'), 'Failed');
                    }

                }
            } elseif ($courier_type == 'redx') {
                // RedX integration
                $redx_info = Courierapi::where(['status' => 1, 'type' => 'redx'])->select('id', 'type', 'url', 'token', 'status')->first();
                if ($redx_info) {
                    // Get order details for the products
                    $orderDetails = OrderDetails::where('order_id', $order->id)->get();

                    // Prepare package details
                    $package_details = [];
                    foreach ($orderDetails as $detail) {
                        $package_details[] = [
                            'name' => $detail->product_name,
                            'quantity' => $detail->qty,
                            'price' => $detail->unit_price,
                        ];
                    }

                    $response = Http::withHeaders([
                        'API-ACCESS-TOKEN' => $redx_info->token,
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                    ])->post($redx_info->url . '/api/v1/order', [
                                'invoice' => $order->invoice_id,
                                'payment_method' => $order->payment_type == 'cod' ? 'COD' : 'Prepaid',
                                'store_id' => $request->redx_store_id, // Add this field to your form
                                'pickup_address' => $request->redx_pickup_address, // Add this field to your form
                                'customer_name' => $order->shipping ? $order->shipping->name : '',
                                'customer_phone' => $order->shipping ? $order->shipping->phone : '',
                                'customer_address' => $order->shipping ? $order->shipping->address : '',
                                'area_id' => $request->redx_area_id, // Add this field to your form
                                'district_id' => $request->redx_district_id, // Add this field to your form
                                'delivery_charge' => $request->redx_delivery_charge ?? 0, // Optional, add if needed
                                'cod_charge' => $request->redx_cod_charge ?? 0, // Optional, add if needed
                                'cash_collection' => round($order->amount),
                                'parcel_weight' => $request->redx_weight ?? 0.5, // Add this field or use default
                                'note' => 'Special note- product must be check after delivery',
                                'package_details' => $package_details,
                            ]);

                    if ($response->successful()) {
                        Toastr::success($response['tracking_id'] ?? $response['tracking_number'], 'Courier Tracking ID');
                        return response()->json([
                            'status' => 'success',
                            'message' => $response['tracking_id'] ?? $response['tracking_number'],
                            'Courier Tracking ID'
                        ]);
                    } else {
                        Toastr::error($response['message'] ?? 'RedX courier order failed', 'Courier Order Failed');
                        return response()->json([
                            'status' => 'failed',
                            'message' => $response['message'] ?? 'RedX courier order failed',
                            'Courier Order Failed'
                        ]);
                    }
                }
            }
        }

        return redirect()->back()->with('courier_results', $results);
    }


   public function courier_type(Request $request)
    {
        // [FIX 1] টাইমআউট বাড়ানো (৫ মিনিট)
        set_time_limit(300);

        $orders_id = explode(',', $request->selected_order_ids);
        
        // [FIX 2] Pathao ইনফো ক্যাশ করা বা একবার আনা
        $pathao_info = Courierapi::where(['status' => 1, 'type' => 'pathao'])
            ->select('id', 'type', 'url', 'token', 'status')
            ->first();

        // [FIX 3] সব অর্ডার এবং শিপিং ইনফো একবারে লোড করা (N+1 Query ফিক্স)
        $orders = Order::whereIn('id', $orders_id)->with('shipping')->get();

        $results = [];

        // এখন মেমোরি থেকে অর্ডার নিয়ে লুপ চালানো হচ্ছে
        foreach ($orders as $order) {
            
            if (!$pathao_info) {
                $results[] = [
                    'order_id' => $order->id,
                    'status' => 'failed',
                    'message' => 'Courier configuration missing.'
                ];
                continue;
            }

            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $pathao_info->token,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])->post($pathao_info->url . '/aladdin/api/v1/orders', [
                    'store_id' => $request->pathaostore,
                    'merchant_order_id' => $order->invoice_id,
                    'sender_name' => 'Test',
                    'sender_phone' => $order->shipping->phone ?? '',
                    'recipient_name' => $order->shipping->name ?? '',
                    'recipient_phone' => $order->shipping->phone ?? '',
                    'recipient_address' => $order->shipping->address ?? '',
                    'recipient_city' => $request->pathaocity,
                    'recipient_zone' => $request->pathaozone,
                    'recipient_area' => $request->pathaoarea,
                    'delivery_type' => 48,
                    'item_type' => 2,
                    'special_instruction' => 'Special note- product must be check after delivery',
                    'item_quantity' => 1,
                    'item_weight' => 0.5,
                    'amount_to_collect' => round($order->amount),
                    'item_description' => 'Special note- product must be check after delivery',
                ]);

                if ($response->status() == 200) {
                    $results[] = [
                        'order_id' => $order->id,
                        'status' => 'success',
                        'tracking_id' => $response['data']['consignment_id']
                    ];
                    Toastr::success("Order #{$order->id} → Tracking ID: " . $response['data']['consignment_id'], 'Courier Assigned');
                } else {
                    $results[] = [
                        'order_id' => $order->id,
                        'status' => 'failed',
                        'message' => $response['message'] ?? 'Unknown error'
                    ];
                    Toastr::error("Order #{$order->id} → " . ($response['message'] ?? 'Courier Order Failed'), 'Failed');
                }
            } catch (\Exception $e) {
               // \Log::error("Courier Assign Error Order {$order->id}: " . $e->getMessage());
                $results[] = [
                    'order_id' => $order->id,
                    'status' => 'failed',
                    'message' => 'System error: ' . $e->getMessage()
                ];
            }
        }

        return redirect()->back()->with('courier_results', $results);
    }
    public function invoice($invoice_id)
    {
        $order = Order::where(['invoice_id' => $invoice_id])->with('orderdetails', 'payment', 'shipping', 'customer')->firstOrFail();
        $smsteamplate = SmsTeamplate::get();
        $smsBalancse = $this->balanceCheck();
        return view('backEnd.order.invoice', compact('order', 'smsteamplate', 'smsBalancse'));
    }
    public function whosalesinvoice($invoice_id)
    {
        $order = Order::where(['invoice_id' => $invoice_id])->with('orderdetails', 'payment', 'shipping', 'customer')->firstOrFail();
        return view('backEnd.order.whosalesinvoice', compact('order'));
    }
    public function process($invoice_id)
    {
        $data = Order::where(['invoice_id' => $invoice_id])->select('id', 'invoice_id', 'order_status')->with('orderdetails')->first();
        $shippingcharge = ShippingCharge::where('status', 1)->get();
        return view('backEnd.order.process', compact('data', 'shippingcharge'));
    }

   
       public function order_process(Request $request)
    {
        
   

        $link = OrderStatus::find($request->status)->slug;
        $order = Order::find($request->id);
        $previous_status = $order->order_status;
        $courier = $order->order_status;
        $order->order_status = $request->status;
        $order->admin_note = $request->admin_note;
        $order->save();




      




      // =========================================================
    // SMART STOCK MANAGEMENT (PREVENT DOUBLE DECREMENT)
    // =========================================================
    
    // টার্গেট স্ট্যাটাস: In Courier (5) অথবা Confirmed (6)
    // শর্ত: নতুন স্ট্যাটাস ৫ বা ৬ হতে হবে এবং আগের স্ট্যাটাস ৫ বা ৬ থাকা যাবে না।
    
    if ( ($request->status == 5 || $request->status == 6) && ($previous_status != 5 && $previous_status != 6) ) {
        
        $orderDetails = OrderDetails::where('order_id', $order->id)->get();

        foreach ($orderDetails as $details) {
            $product = Product::find($details->product_id);
            if ($product) {
                // স্টক কমানো
                $product->decrement('stock', $details->qty);
                
                // লগ রাখা
                InventoryLog::create([
                    'product_id' => $product->id,
                    'type' => 'sale',
                    'quantity' => -$details->qty,
                    'ref_id' => $order->invoice_id,
                    'note' => 'Stock Out (Status ' . $request->status . ')',
                    'current_stock' => $product->stock
                ]);
            }
        }
    }

    // স্টক রিটার্ন লজিক (যদি Returned 11 হয়)
    if ($request->status == 11 && $previous_status != 11) {
        $orderDetails = OrderDetails::where('order_id', $order->id)->get();
        foreach ($orderDetails as $details) {
            $product = Product::find($details->product_id);
            if ($product) {
                $product->increment('stock', $details->qty);
                
                InventoryLog::create([
                    'product_id' => $product->id,
                    'type' => 'return',
                    'quantity' => $details->qty,
                    'ref_id' => $order->invoice_id,
                    'note' => 'Order Returned',
                    'current_stock' => $product->stock
                ]);
            }
        }
    }






        // Commission Logic
        if ($request->status == 10 && $order->order_type == 'reseller') {
          

            $orderDetails = OrderDetails::where('order_id', $order->id)->get();
            $reseller = \App\Models\Reseller::find($order->customer_id);

            if ($reseller) {
                $total_commission = 0;
                foreach ($orderDetails as $details) {
                    $product = Product::find($details->product_id);
                    $dealerProduct = \App\Models\DealerProduct::where('dealer_id', $reseller->dealer_id)
                        ->where('product_id', $details->product_id)
                        ->first();

                    if ($dealerProduct) {
                        // New Logic Defined by User:

                        // 1. Referral Commission (The "Commission" field in Dealer Product)
                        // "30 je je order korbe tar referral pabe" -> Referral gets Commission Amount
                        $referral_bonus = $dealerProduct->commission_amount;

                        // 2. Dealer Profit
                        // "Reseller Price - (My Price + Commission) = Dealer Profit"
                        $dealer_base_price = $product->purchase_price; // System Price changed from new_price to purchase_price
                        $dealer_profit_per_unit = $dealerProduct->reseller_price - ($dealer_base_price + $referral_bonus);

                        // Distribute Dealer Profit
                        $dealer = \App\Models\Dealer::find($reseller->dealer_id);

                        // Debug
                        \Log::info("Commission Debug Order #{$order->id}: DealerProfitPerUnit: $dealer_profit_per_unit, ResellerPrice: {$dealerProduct->reseller_price}, Base: $dealer_base_price, RefBonus: $referral_bonus");

                        // Only add if positive
                        if ($dealer && $dealer_profit_per_unit > 0) {
                            $dealer->balance += ($dealer_profit_per_unit * $details->qty);
                            $dealer->save();
                        }

                        // Distribute Referral Bonus
                        // The Reseller who placed the order has a referrer.
                        if ($reseller->referrer_id) {
                            $referrer = \App\Models\Reseller::find($reseller->referrer_id);
                            if ($referrer) {
                                $referrer->balance += ($referral_bonus * $details->qty);
                                $referrer->save();
                            }
                        }
                    }
                }

                // Reseller Balance Update (Bulk check)
                // Reseller Profit = Invoice Amount - Payable Amount

                $reseller_profit = $order->amount - $order->payable_amount;
                if ($reseller_profit > 0) {
                    $reseller->balance += $reseller_profit;
                    $reseller->save();
                }
            }
        }








       $shipping_update = Shipping::where('order_id', $order->id)->first();
$shippingfee = ShippingCharge::find($request->area);

// [FIXED] রিসেলার অর্ডার হলে শিপিং চার্জ অটো পরিবর্তন হবে না
if ($order->order_type != 'reseller') { 
    if ($shippingfee && $shipping_update && $shippingfee->name != $shipping_update->area) {
        // Only update amount if the area ACTUALLY changed.
        $diff = $shippingfee->amount - $order->shipping_charge;
        $total = $order->amount + $diff;
        
        $order->shipping_charge = $shippingfee->amount;
        $order->amount = $total;
        $order->save();
    }
}

        $shipping_update->name = $request->name;
        $shipping_update->phone = $request->phone;
        $shipping_update->address = $request->address;
        $shipping_update->area = $shippingfee->name;
        $shipping_update->save();







        if ($request->status == 5 && $courier != 5) {
            $courier_info = Courierapi::where(['status' => 1, 'type' => 'steadfast'])->first();
            if ($courier_info) {
                $consignmentData = [
                    'invoice' => $order->invoice_id,
                    'recipient_name' => $order->shipping ? $order->shipping->name : 'InboxHat',
                    'recipient_phone' => $order->shipping ? $order->shipping->phone : '01750578495',
                    'recipient_address' => $order->shipping ? $order->shipping->address : '01750578495',
                    'cod_amount' => $order->amount
                ];
                $client = new Client();
                $response = $client->post('$courier_info->url', [
                    'json' => $consignmentData,
                    'headers' => [
                        'Api-Key' => '$courier_info->api_key',
                        'Secret-Key' => '$courier_info->secret_key',
                        'Accept' => 'application/json',
                    ],
                ]);

                $responseData = json_decode($response->getBody(), true);
            } else {
                return "ok";
            }
            if ($order->order_type == 'reseller') {
                Toastr::success('Success', 'Order status change successfully');
                return redirect()->route('admin.dealer.orders');
            }
            Toastr::success('Success', 'Order status change successfully');
            return redirect('admin/order/' . $link);
        }
        if ($order->order_type == 'reseller') {
            Toastr::success('Success', 'Order status change successfully');
            return redirect()->route('admin.dealer.orders');
        }
        Toastr::success('Success', 'Order status change successfully');
        return redirect('admin/order/' . $link);
    }


    public function destroy(Request $request)
    {
        $order = Order::where('id', $request->id)->delete();
        $order_details = OrderDetails::where('order_id', $request->id)->delete();
        $shipping = Shipping::where('order_id', $request->id)->delete();
        $payment = Payment::where('order_id', $request->id)->delete();
        Toastr::success('Success', 'Order delete success successfully');
        return redirect()->back();
    }

    public function returnupdate(Request $request)
    {
        $id = $request->idss;
        $order = Order::find($id);
        $order->order_status = 11;
        $order->save();
        Toastr::success('Success', 'Order return Update successfully');
        return redirect()->back();
    }

    public function order_assign(Request $request)
{
    
    $products = Order::whereIn('id', $request->input('order_ids'))
                     ->update(['assign_user_id' => $request->user_id]); // কলামের নাম assign_user_id দিন
    
    return response()->json(['status' => 'success', 'message' => 'Order user id assign']);
}

public function order_status(Request $request)

{
    

    

    // ১. আইডি নির্ধারণ করা (বাল্ক এবং সিঙ্গেল উভয় সিস্টেমের জন্য)
    if ($request->id) {
        $order_ids = [$request->id];
        $status_id = $request->status;
    } else {
        $order_ids = $request->input('order_ids');
        $status_id = $request->order_status;
    }

    if (!$order_ids || !$status_id) {
        return response()->json(['status' => 'error', 'message' => 'অর্ডার বা স্ট্যাটাস সিলেক্ট করা হয়নি!']);
    }


   // =========================================================
    // SMART STOCK MANAGEMENT (Logic Starts)
    // =========================================================
    
    // টার্গেট: In Courier (5), Confirmed (6), Returned (11)
    if ($status_id == 5 || $status_id == 6 || $status_id == 11) {
        
        // অর্ডারগুলো লোড করা হচ্ছে প্রোডাক্টসহ
        $orders = Order::whereIn('id', $order_ids)->with('orderdetails.product')->get();

        foreach ($orders as $order) {
            $previous_status = $order->order_status; // আগের স্ট্যাটাস

            // [LOGIC 1] স্টক কমানো (Sale)
            // নতুন স্ট্যাটাস ৫ বা ৬ এবং আগের স্ট্যাটাস ৫ বা ৬ না থাকলে -> স্টক কমবে
            if (($status_id == 5 || $status_id == 6) && ($previous_status != 5 && $previous_status != 6)) {
                
                foreach ($order->orderdetails as $detail) {
                    if ($detail->product) {
                        $detail->product->decrement('stock', $detail->qty);
                        // এখানে ইনভেন্টরি লগ রাখতে পারেন যদি চান
                    }
                }
            }

            // [LOGIC 2] স্টক বাড়ানো (Return)
            // নতুন স্ট্যাটাস ১১ এবং আগে ১১ ছিল না -> স্টক বাড়বে
            if ($status_id == 11 && $previous_status != 11) {
                
                foreach ($order->orderdetails as $detail) {
                    if ($detail->product) {
                        $detail->product->increment('stock', $detail->qty);
                    }
                }
            }
        }
    }
    // =========================================================






   // =========================================================
    // MODERATOR COMMISSION SYSTEM (For List & Bulk Update)
    // =========================================================
    
    // [FIX] আপনার সিস্টেম অনুযায়ী আইডি ১০ এবং ১১ সেট করা হলো
    $success_status_id = 10;   // Delivered
    $return_status_id = 11;    // Returned

    // স্ট্যাটাস যদি সাকসেস বা রিটার্ন হয়, তবেই কমিশন চেক করবে
    if ($status_id == $success_status_id || $status_id == $return_status_id) {
        
        // সব অর্ডার লোড করা হচ্ছে
        $orders_for_commission = Order::whereIn('id', $order_ids)->get();
        
        foreach ($orders_for_commission as $order) {
            $previous_status = $order->order_status;
            
            // মডারেটর চেক (অ্যাসাইন করা আছে কি না)
            if ($order->assign_user_id) {
                $moderator = \App\Models\User::find($order->assign_user_id);
                
                if ($moderator) {
                    // কমিশনের হিসাব
                    $commission_amount = ($order->amount * ($moderator->commission_rate / 100)) + $moderator->fixed_commission;
                    
                    // ১. সাকসেস হলে ব্যালেন্স যোগ (আগে সাকসেস ছিল না, এখন ১০ হলো)
                    if ($status_id == $success_status_id && $previous_status != $success_status_id) {
                        $moderator->increment('balance', $commission_amount);
                        \Log::info("Bulk Commission Added: User {$moderator->id}, Order {$order->id}");
                    }
                    
                    // ২. রিটার্ন হলে ব্যালেন্স কর্তন (আগে সাকসেস ছিল, এখন ১১ হলো)
                    elseif ($status_id == $return_status_id && $previous_status == $success_status_id) {
                        $moderator->decrement('balance', $commission_amount);
                        \Log::info("Bulk Commission Deducted: User {$moderator->id}, Order {$order->id}");
                    }
                }
            }
        }
    }
    // =========================================================


    
    // ২. ডাটাবেসে স্ট্যাটাস আপডেট করা
    Order::whereIn('id', $order_ids)->update(['order_status' => $status_id]);

    // ==============================================================
    // [START] Bulk/Quick Reseller Activation
    // ==============================================================
  if ($status_id == 10) {
        $orders_check = Order::whereIn('id', $order_ids)->get();
        foreach($orders_check as $single_order){
            $reseller_active = \App\Models\Reseller::find($single_order->customer_id);
            
            if ($reseller_active) {
                // আমরা চেক করছি স্ট্যাটাস 1 (Active) না হলে আপডেট করবে
                // আগে চেক করছিলেন != 'active' যা ভুল হতে পারে যদি কলাম integer হয়
                if ($reseller_active->status != 1) {
                    
                    // [FIX] 'active' এর বদলে 1 ব্যবহার করা হলো
                    $reseller_active->status = 1; 
                    
                    $reseller_active->save();
                   // \Log::info("Activated Reseller ID: " . $reseller_active->id . " set to 1");
                }
            }
        }
    }
    // ==============================================================


// ================= SST TRIGGER (Final Clean Version) =================
        try {
            $gs = \App\Models\GeneralSetting::first();

            // শর্ত: স্ট্যাটাস যদি Confirmed (6) হয় এবং সেটিংস যদি 'admin' মোডে থাকে
            if ($status_id == 6 && $gs && $gs->pixel_trigger_type == 'admin') {
                
                // অর্ডার লোড করা
                $orders = Order::whereIn('id', $order_ids)->with(['orderdetails', 'shipping', 'customer'])->get();

                foreach ($orders as $order) {
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

                    $trackingData = [
                        'event_name'      => 'Purchase',
                        'event_id'        => (string) $order->id,
                        'name'            => $order->shipping->name ?? null,
                        'city'            => $order->shipping->district ?? $order->shipping->area,
                        'address'         => $order->shipping->address ?? null,
                        'zip'             => $order->shipping->zip_code ?? null,
                        'country'         => 'bd',
                        'email'           => $order->shipping->email ?? null,
                        'phone'           => $order->shipping->phone,
                        'external_id'     => (string) ($order->customer_id ?? $order->shipping->phone),
                        'amount'          => $order->amount,
                        'currency'        => 'BDT',
                        'source_url'      => url('/'),
                        'fbp'             => $order->fbp, 
                        'fbc'             => $order->fbc,
                        'ttp'             => $order->ttp,
                        'gclid'           => $order->gclid,
                        'ip'              => $order->ip_address ?? null, 
                        'user_agent'      => $order->user_agent ?? null,
                        'content_ids'     => $content_ids,
                        'contents_tiktok' => $contents_tiktok,
                        'ip'          => $order->ip_address,
                    ];

                    SendServerSideEventJob::dispatch($trackingData);

                    // শুধুমাত্র এই লগটি রাখা হয়েছে যা কনফার্ম করবে কোড কাজ করেছে
                    \Log::info('Facebook CAPI Event Dispatched Successfully for Order ID: ' . $order->id);
                }
            }
        } catch (\Exception $e) {
            // কোনো এরর হলে শুধু এরর মেসেজ রাখবে
            \Log::error('SST Error: ' . $e->getMessage());
        }
        // ================= SST END =================



    

    // নতুন নাম এবং কালার নিয়ে আসা (মডেলের নাম আপনার ডাটাবেস অনুযায়ী চেক করে নিন)
    // এখানে উদাহরণ হিসেবে \App\Models\OrderStatus ব্যবহার করা হয়েছে
    $statusInfo = \App\Models\OrderStatus::find($status_id);

    return response()->json([
        'status' => 'success', 
        'message' => 'Order status changed successfully',
        'new_name' => $statusInfo->name ?? 'Updated',
        'new_color' => $statusInfo->colorcode ?? '#6c757d'
    ]);
}
   public function bulk_destroy(Request $request)
    {
        $orders_id = $request->order_ids;
        
        // [OPTIMIZATION] লুপ বাদ দিয়ে whereIn ব্যবহার
        // আগে যেখানে প্রতি অর্ডারের জন্য ৪টি কুয়েরি চলত, এখন সব মিলিয়ে মাত্র ৪টি কুয়েরি চলবে
        
        OrderDetails::whereIn('order_id', $orders_id)->delete();
        Shipping::whereIn('order_id', $orders_id)->delete();
        Payment::whereIn('order_id', $orders_id)->delete();
        
        // সব শেষে মেইন অর্ডার ডিলিট করা
        Order::whereIn('id', $orders_id)->delete();

        return response()->json(['status' => 'success', 'message' => 'Order delete successfully']);
    }
    public function order_print(Request $request)
    {


        $orders = Order::whereIn('id', $request->input('order_ids'))
            ->with('orderdetails', 'payment', 'shipping', 'customer')
            ->get();
        $view = view('backEnd.order.print', ['orders' => $orders])->render();
        return response()->json(['status' => 'success', 'view' => $view]);
    }

 public function order_csv(Request $request)
{
    // যদি কোনো আইডি সিলেক্ট না থাকে
    if (!$request->has('ids') || empty($request->ids)) {
        return redirect()->back();
    }

    // সময় বাড়ানো হলো
    ini_set('max_execution_time', 600);

    $invoice_data = explode(',', $request->ids);
    $columns = ["Invoice", "Date", "Name", "Address", "Phone", "Amount", "Status"];

    return response()->streamDownload(function () use ($invoice_data, $columns) {
        
        // [গুরুত্বপূর্ণ] বাফারিং ক্লিয়ার করা (ফাইল ব্ল্যাঙ্ক আসা বন্ধ করবে)
        if (ob_get_contents()) ob_end_clean();

        $file = fopen('php://output', 'w');
        
        // Excel এর জন্য BOM যোগ করা (বাংলা ফন্ট বা বিশেষ ক্যারেক্টার ঠিক রাখতে)
        fputs($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

        // হেডার লেখা
        fputcsv($file, $columns);

        // ডাটাবেস থেকে ডাটা আনা
        $orders = \App\Models\Order::whereIn('id', $invoice_data)
            ->with('shipping', 'status')
            ->get()
            ->keyBy('id');

        foreach ($invoice_data as $id) {
            if (isset($orders[$id])) {
                $order = $orders[$id];
                
                // [FIX] ফোন নাম্বারের আগে ট্যাব (\t) যোগ করা হলো
                // এটি এক্সেলকে বলবে এটি টেক্সট, তাই 0 কাটবে না
                $phone_number = "\t" . ($order->shipping->phone ?? ''); 

                fputcsv($file, [
                    $order->invoice_id,
                    $order->created_at->format('d-m-Y'),
                    $order->shipping->name ?? '',
                    $order->shipping->address ?? '',
                    $phone_number, // এখানে ফোন নাম্বার বসবে
                    $order->amount,
                    $order->status->name ?? ''
                ]);
            }
        }

        fclose($file);
    }, "export_" . date("Y.m.d") . ".csv", [
        "Content-Type" => "text/csv",
        "Cache-Control" => "no-store, no-cache",
        "Pragma" => "no-cache",
        "Expires" => "0",
    ]);
}



    public function bulk_courier($slug, Request $request)
    {
        // [FIX 1] সার্ভার যাতে বন্ধ না হয় তার জন্য সময় বাড়ানো হলো
        set_time_limit(300);

        $courier_info = Courierapi::where(['status' => 1, 'type' => $slug])->first();

        if ($courier_info) {
            $orders_id = $request->order_ids;

            // [FIX 2] লুপের ভেতর বারবার ডাটাবেস কল না করে সব অর্ডার একবারে আনা
            $orders = Order::whereIn('id', $orders_id)->with('shipping')->get();
            
            $client = new Client();
            $successCount = 0;
            $failCount = 0;

            foreach ($orders as $order) {
                $courier = $order->order_status;

                // কুরিয়ারে ইতিমধ্যে পাঠানো না হলে পাঠাবে (স্ট্যাটাস ৫ মানে In Courier)
                if ($request->status == 5 && $courier != 5) {

                    $consignmentData = [
                        'invoice' => $order->invoice_id,
                        'recipient_name' => $order->shipping ? $order->shipping->name : 'InboxHat',
                        'recipient_phone' => $order->shipping ? $order->shipping->phone : '01750578495',
                        'recipient_address' => $order->shipping ? $order->shipping->address : 'Dhaka',
                        'cod_amount' => $order->amount
                    ];

                    try {
                        // [FIX 3] ভেরিয়েবল ঠিক করা হলো (আগে সিঙ্গেল কোটেশন ছিল যা ভুল)
                        $response = $client->post($courier_info->url, [
                            'json' => $consignmentData,
                            'headers' => [
                                'Api-Key' => $courier_info->api_key,
                                'Secret-Key' => $courier_info->secret_key,
                                'Accept' => 'application/json',
                            ],
                        ]);

                        $responseData = json_decode($response->getBody(), true);

                        if (isset($responseData['status']) && $responseData['status'] == 200) {
                            $order->order_status = 4; // অথবা ৫ (আপনার লজিক অনুযায়ী)
                            $order->save();
                            $successCount++;
                        } else {
                            $failCount++;
                        }
                    } catch (\Exception $e) {
                        // কোনো এরর হলে লগ ফাইলে রাখবে, কিন্তু লুপ থামাবে না
                        \Log::error("Bulk Courier Failed for Order {$order->id}: " . $e->getMessage());
                        $failCount++;
                    }
                }
            }

            // [FIX 4] লুপ শেষ হওয়ার পর ফাইনাল রেসপন্স রিটার্ন করা
            if ($successCount > 0) {
                return response()->json([
                    'status' => 'success', 
                    'message' => "$successCount orders placed successfully. $failCount failed."
                ]);
            } else {
                return response()->json([
                    'status' => 'failed', 
                    'message' => 'Failed to place orders to courier. Check configurations.'
                ]);
            }

        } else {
            return response()->json(['status' => 'failed', 'message' => 'Courier configuration not found']);
        }
    }
    public function stock_report(Request $request)
    {
        $products = Product::select('id', 'name', 'new_price', 'stock')
            ->where('status', 1);
        if ($request->keyword) {
            $products = $products->where('name', 'LIKE', '%' . $request->keyword . "%");
        }
        if ($request->category_id) {
            $products = $products->where('category_id', $request->category_id);
        }
        if ($request->start_date && $request->end_date) {
            $products = $products->whereBetween('updated_at', [$request->start_date, $request->end_date]);
        }
        // [OPTIMIZED START]
        // আগের ৩টি আলাদা sum() লাইন মুছে ফেলে এই অংশটুকু বসান:
        $aggregates = (clone $products)->selectRaw('
            SUM(purchase_price * stock) as total_purchase,
            SUM(stock) as total_stock,
            SUM(new_price * stock) as total_price
        ')->first();

        $total_purchase = $aggregates->total_purchase ?? 0;
        $total_stock = $aggregates->total_stock ?? 0;
        $total_price = $aggregates->total_price ?? 0;
        // [OPTIMIZED END]

        // পেজিনেশন এবং ভিউ রিটার্ন আগের মতোই থাকবে
        $products = $products->paginate(10);
        $categories = Category::where('status', 1)->get();
        
        return view('backEnd.reports.stock', compact('products', 'categories', 'total_purchase', 'total_stock', 'total_price'));
    }
   public function order_report(Request $request)
{
    // ১. ইউজার লিস্ট অপ্টিমাইজ (শুধু আইডি ও নাম)
    $users = User::where('status', 1)->select('id', 'name')->get();

    // ২. অর্ডার রিলেশন ঠিক করা
    // 'shipping' সাধারণত Order এর সাথে থাকে, OrderDetails এর সাথে নয়।
    // তাই 'order.shipping' ব্যবহার করা ভালো।
    $orders = OrderDetails::with('order.shipping')
        ->whereHas('order', function ($query) {
            $query->where('order_status', 6); // শুধু ডেলিভারড অর্ডার
        });

    // ফিল্টারিং
    if ($request->keyword) {
        // 'name' কলামটি আপনার ডাটাবেসে আছে তো? সাধারণত এটি 'product_name' হয়
        $orders = $orders->where('product_name', 'LIKE', '%' . $request->keyword . "%");
    }

    if ($request->user_id) {
        $orders = $orders->whereHas('order', function ($query) use ($request) {
            $query->where('user_id', $request->user_id);
        });
    }

    if ($request->start_date && $request->end_date) {
        $orders = $orders->whereBetween('updated_at', [$request->start_date, $request->end_date]);
    }

    // ৩. টোটাল ক্যালকুলেশন অপ্টিমাইজেশন (সুপার ফাস্ট)
    // আগে ৩ বার আলাদা কোয়েরি হতো, এখন ১ বারেই ৩টি টোটাল বের হবে।
    // 'clone' ব্যবহার করা হয়েছে যাতে মেইন $orders ভেরিয়েবল নষ্ট না হয়।
    $totals = (clone $orders)->selectRaw('
        SUM(purchase_price * qty) as total_purchase,
        SUM(qty) as total_item,
        SUM(sale_price * qty) as total_sales
    ')->first();

    $total_purchase = $totals->total_purchase ?? 0;
    $total_item = $totals->total_item ?? 0;
    $total_sales = $totals->total_sales ?? 0;

    // ৪. পেজিনেশন (১০ এর জায়গায় ২০ বা ৫০ দিতে পারেন)
    $orders = $orders->paginate(20);

    return view('backEnd.reports.order', compact('orders', 'users', 'total_purchase', 'total_item', 'total_sales'));
}

    public function order_create()
    {
        $products = Product::select('id', 'name', 'new_price', 'product_code');
        $isAuthor = auth()->user()->hasRole('Book Author');
        if ($isAuthor) {
            $idsuser = Auth::user()->id;
            $products = $products->where('author_id', $idsuser);

        }

        $products = $products->where(['status' => 1])->get();
        $cartinfo = Cart::instance('pos_shopping')->content();
        $shippingcharge = ShippingCharge::where('status', 1)->get();
        return view('backEnd.order.create', compact('products', 'cartinfo', 'shippingcharge'));
    }

    public function order_store(Request $request)
    {
        if ($request->whosale_customer_id) {
            $this->validate($request, [
                'name' => 'required',
                'phone' => 'required',
                'address' => 'required',
            ]);
        } else {
            $this->validate($request, [
                'name' => 'required',
                'phone' => 'required',
                'address' => 'required',
                'area' => 'required',
            ]);

        }


        if (Cart::instance('pos_shopping')->count() <= 0) {
            Toastr::error('Your shopping empty', 'Failed!');
            return redirect()->back();
        }

        $subtotal = Cart::instance('pos_shopping')->subtotal();
        $subtotal = str_replace(',', '', $subtotal);
        $subtotal = str_replace('.00', '', $subtotal);
        $discount = Session::get('pos_discount') + Session::get('product_discount');
        if ($request->whosale_customer_id) {
            $wholesale_customer = Wholesaler::find($request->whosale_customer_id);

            if (!$wholesale_customer) {
                Toastr::error('Wholesale customer not found', 'Failed!');
                return redirect()->back();
            }

            $customer_id = $wholesale_customer->id;
            $customer_name = $wholesale_customer->name;
            $customer_phone = $wholesale_customer->phone;
            $customer_address = $wholesale_customer->address;
            $shippingamount = 0.00;
            $shippingarea = "Office Collection";
        } else {

            $shippingfee = ShippingCharge::find($request->area);
            $shippingamount = $shippingfee->amount;
            $shippingarea = $shippingfee->name;
            $exits_customer = Customer::where('phone', $request->phone)->select('phone', 'id', 'name', 'address')->first();
            if ($exits_customer) {
                $customer_id = $exits_customer->id;
                $customer_name = $exits_customer->name;
                $customer_phone = $exits_customer->phone;
                $customer_address = $request->address; 

    // চাইলে কাস্টমার টেবিলেও নতুন অ্যাড্রেস আপডেট করে দিতে পারেন:
    $exits_customer->address = $request->address;
    $exits_customer->save();

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
                $customer_name = $request->name;
                $customer_phone = $request->phone;
                $customer_address = $request->address;

            }

        }


        $today = date('Y-m-d');

        // Count how many orders were placed today
        $todayCount = $lastOrderId = Order::max('id') ?? 0;

        // order data save
        $order = new Order();
        $order->invoice_id = date('d') . '-' . ($lastOrderId + 1);
        $order->amount = ($subtotal + $shippingamount) - $discount;
        $order->discount = $discount ? $discount : 0;
        $order->shipping_charge = $shippingamount;
        $order->customer_id = $customer_id;
        $order->order_status = !$request->return_satus ? 1 : $request->return_satus;

        $order->customer_type = $request->whosale_customer_id ? 'wholesale' : 'retail';
        ;
        $order->note = $request->note;
        $order->order_source = $request->source_id;
        $order->assign_user_id = $request->assign_user_id ? $request->assign_user_id : auth()->id();
        $order->save();







        // shipping data save
        $shipping = new Shipping();
        $shipping->order_id = $order->id;
        $shipping->customer_id = $customer_id;
        $shipping->name = $customer_name;
        $shipping->phone = $customer_phone;
        $shipping->address = $customer_address;
        $shipping->area = $shippingarea;
        $shipping->save();

        // payment data save
        $payment = new Payment();
        $payment->order_id = $order->id;
        $payment->customer_id = $customer_id;
        $payment->payment_method = 'Cash On Delivery';
        $payment->amount = $order->amount;
        $payment->payment_status = 'pending';
        $payment->save();

        if ($request->whosale_customer_id) {
            $wholesale_payment = Wholesaler::find($request->whosale_customer_id);
            if ($request->return_satus == 11) {
                $pay = $wholesale_payment->payment - $order->amount;
            } else {
                $pay = $wholesale_payment->payment + $order->amount;
            }

            $wholesale_payment->payment = $pay;
            $wholesale_payment->save();

        }

        // order details data save
      // order details data save
        foreach (Cart::instance('pos_shopping')->content() as $cart) {
            $order_details = new OrderDetails();
            $order_details->order_id = $order->id;
            $order_details->product_id = $cart->id;
            $order_details->product_name = $cart->name;
            $order_details->purchase_price = $cart->options->purchase_price;
            $order_details->product_discount = $cart->options->product_discount;
            $order_details->sale_price = $cart->price;
            $order_details->qty = $cart->qty;
            $sizeData = $request->size;
    $order_details->product_size = isset($sizeData[$cart->rowId]) ? $sizeData[$cart->rowId] : ($cart->options->size ?? '');
            $order_details->save();

            // ====================================================
            // NEW CODE: WHOLESALE RETURN LOGIC ADDED HERE
            // ====================================================
            if ($request->return_satus == 11) {
                $product = Product::find($cart->id);
                if ($product) {
                    // ১. স্টক বাড়ানো (Stock Increment)
                    $product->increment('stock', $cart->qty);

                    // ২. ইনভেন্টরি লগে এন্ট্রি (Inventory Log Entry)
                    InventoryLog::create([
                        'product_id'    => $product->id,
                        'type'          => 'wholesale_return', 
                        'quantity'      => $cart->qty,
                        'ref_id'        => $order->invoice_id,
                        'note'          => 'Wholesale Return Received',
                        'current_stock' => $product->stock
                    ]);
                }
            }
            // ====================================================
        }

        
        Cart::instance('pos_shopping')->destroy();
        Session::forget('pos_shipping');
        Session::forget('pos_discount');
        Session::forget('product_discount');
        Toastr::success('Thanks, Your order place successfully', 'Success!');
        if ($request->whosale_customer_id) {
            return redirect('admin/order/wholeseller');
        } else {
            return redirect('admin/order/pending');
        }
    }

    public function whosalesCustomers()
    {
        $customers = Wholesaler::where('status', 'active')
            ->select('id', 'name', 'phone')
            ->orderBy('name', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'customers' => $customers
        ]);
    }

    /**
     * Get specific wholesale customer information
     */
    public function whosalesCustomerInfo(Request $request)
    {
        $customer = Wholesaler::find($request->id);

        if ($customer) {
            return response()->json([
                'success' => true,
                'customer' => [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'phone' => $customer->phone,
                    'address' => $customer->address ?? '',
                    'area' => $customer->area ?? ''
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Customer not found'
        ]);
    }
    public function cart_add(Request $request)
    {
        $product = Product::select('id', 'name', 'stock', 'new_price', 'old_price', 'purchase_price', 'slug')->where(['id' => $request->id])->first();
        $qty = 1;
        $cartinfo = Cart::instance('pos_shopping')->add([
            'id' => $product->id,
            'name' => $product->name,
            'qty' => $qty,
            'price' => $product->new_price,
            'options' => [
                'slug' => $product->slug,
                'image' => $product->image->image,
                'old_price' => $product->old_price,
                'purchase_price' => $product->purchase_price,
                'product_discount' => 0,
            ],
        ]);
        return response()->json(compact('cartinfo'));
    }
    public function whosalescart_add(Request $request)
    {
        $product = Product::select('id', 'name', 'stock', 'new_price', 'old_price', 'purchase_price', 'offer_price', 'slug')->where(['id' => $request->id])->first();
        $qty = 1;
        $cartinfo = Cart::instance('pos_shopping')->add([
            'id' => $product->id,
            'name' => $product->name,
            'qty' => $qty,
            'price' => $product->offer_price,
            'options' => [
                'slug' => $product->slug,
                'image' => $product->image->image,
                'old_price' => $product->old_price,
                'purchase_price' => $product->purchase_price,
                'product_discount' => 0,
            ],
        ]);
        return response()->json(compact('cartinfo'));
    }
    public function cart_content()
    {
        $cartinfo = Cart::instance('pos_shopping')->content();
        return view('backEnd.order.cart_content', compact('cartinfo'));
    }
    public function whosalescart_content()
    {
        $cartinfo = Cart::instance('pos_shopping')->content();
        return view('backEnd.order.whosalescart_content', compact('cartinfo'));
    }
    public function cart_details()
    {
        $cartinfo = Cart::instance('pos_shopping')->content();
        $discount = 0;
        foreach ($cartinfo as $cart) {
            $discount += $cart->options->product_discount * $cart->qty;
        }
        Session::put('product_discount', $discount);
        return view('backEnd.order.cart_details', compact('cartinfo'));
    }

  public function cart_detailswhosales()
    {
        $cartinfo = Cart::instance('pos_shopping')->content();
        
        // টোটাল ডিসকাউন্ট ক্যালকুলেশন
        $discount = 0;
        foreach ($cartinfo as $cart) {
            // (?? 0) ব্যবহার করা হয়েছে যাতে null ভ্যালু থাকলে এরর না দেয়
            $discount += ($cart->options->product_discount ?? 0) * $cart->qty;
        }
        
        // সেশনে লেটেস্ট ডিসকাউন্ট আপডেট করা
        Session::put('product_discount', $discount);

        return view('backEnd.order.whosalescart_detalis', compact('cartinfo'));
    }
    public function cart_increment(Request $request)
    {
        $qty = $request->qty + 1;
        $cartinfo = Cart::instance('pos_shopping')->update($request->id, $qty);
        return response()->json($cartinfo);
    }
    public function cart_decrement(Request $request)
    {
        $qty = $request->qty - 1;
        $cartinfo = Cart::instance('pos_shopping')->update($request->id, $qty);
        return response()->json($cartinfo);
    }
    public function cart_remove(Request $request)
    {
        $remove = Cart::instance('pos_shopping')->remove($request->id);
        $cartinfo = Cart::instance('pos_shopping')->content();
        return response()->json($cartinfo);
    }

public function product_discount(Request $request)
{
    // ১. ডিবাগিং: কী ডাটা আসছে তা লগে প্রিন্ট করা
    \Log::info('--- Product Discount Debug Start ---');
    \Log::info('Request Data:', $request->all());

    $rowId = $request->id;
    $productId = $request->product_id; 
    $discount = (float) $request->discount;
    
    // কার্ট চেক
    $cart = Cart::instance('pos_shopping');
    
    // ২. ডিবাগিং: কার্টে কী কী আছে তা দেখা
    \Log::info('Current Cart Content Keys:', $cart->content()->keys()->toArray());

    $item = null;
    $targetRowId = $rowId;

    // আইটেম খোঁজার চেষ্টা
    try {
        $item = $cart->get($rowId);
        \Log::info('Item found via Row ID: ' . $rowId);
    } catch (\Exception $e) {
        \Log::info('Item NOT found via Row ID: ' . $rowId);
        $item = null;
    }

    // ব্যাকআপ প্ল্যান: Product ID দিয়ে খোঁজা
    if (!$item) {
        if ($productId) {
            \Log::info('Searching via Product ID: ' . $productId);
            foreach ($cart->content() as $key => $cartItem) {
                if ($cartItem->id == $productId) {
                    $item = $cartItem;
                    $targetRowId = $key;
                    \Log::info('Item FOUND via Product ID. New Row ID: ' . $key);
                    break;
                }
            }
        } else {
            \Log::warning('Product ID is MISSING in the request!');
        }
    }

    // আপডেট লজিক
    if ($item) {
        $options = $item->options->merge(['product_discount' => $discount])->toArray();
        $updatedItem = $cart->update($targetRowId, ['options' => $options]);

        // টোটাল আপডেট
        $total_discount = 0;
        foreach ($cart->content() as $ci) {
            $total_discount += ($ci->options->product_discount ?? 0) * $ci->qty;
        }
        Session::put('product_discount', $total_discount);
        Session::save();

        \Log::info('Update Successful. Sending response.');
        \Log::info('--- Debug End ---');

        return response()->json([
            'status' => 'success',
            'new_rowId' => $updatedItem->rowId 
        ]);
    }

    \Log::error('Item finally NOT FOUND.');
    return response()->json(['status' => 'error', 'message' => 'Item not found'], 404);
}

// আলাদা হেল্পার ফাংশন (ক্যালকুলেশন ঠিক রাখতে)
private function calculateTotalDiscount($cart) {
    $total = 0;
    foreach ($cart->content() as $item) {
        $total += ($item->options->product_discount ?? 0) * $item->qty;
    }
    return $total;
}

public function product_price(Request $request)
{
    $id = $request->id;
    $price = $request->price;
    $cartInstance = Cart::instance('pos_shopping');

    try {
        $cartItem = $cartInstance->get($id);

        // অপশনগুলো হারানো রোধ করতে বর্তমান অপশনগুলোই পুনরায় পাঠানো হচ্ছে
        $cartInstance->update($id, [
            'price' => $price,
            'options' => $cartItem->options->toArray() 
        ]);

        // ডিসকাউন্ট কন্সিস্টেন্সি বজায় রাখা
        $total_discount = 0;
        foreach ($cartInstance->content() as $item) {
            $total_discount += ($item->options->product_discount ?? 0) * $item->qty;
        }
        Session::put('product_discount', $total_discount);
        Session::save();

        return response()->json(['status' => 'success', 'message' => 'Price updated']);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error', 
            'message' => 'Invalid Row ID for price update'
        ], 404);
    }
}
    public function cart_shipping(Request $request)
    {
        $shipping = ShippingCharge::where(['status' => 1, 'id' => $request->id])->first()->amount;
        Session::put('pos_shipping', $shipping);
        return response()->json($shipping);
    }

    public function cart_clear(Request $request)
    {
        $cartinfo = Cart::instance('pos_shopping')->destroy();
        Session::forget('pos_shipping');
        Session::forget('pos_discount');
        Session::forget('product_discount');
        return redirect()->back();
    }
    public function order_edit($invoice_id)
    {
        $products = Product::select('id', 'name', 'new_price', 'product_code')->where(['status' => 1])->get();
        $shippingcharge = ShippingCharge::where('status', 1)->get();
        $order = Order::where('invoice_id', $invoice_id)->first();
        $cartinfo = Cart::instance('pos_shopping')->destroy();
        $shippinginfo = Shipping::where('order_id', $order->id)->first();
        Session::put('product_discount', $order->discount);
        Session::put('pos_shipping', $order->shipping_charge);
        $orderdetails = OrderDetails::where('order_id', $order->id)->get();
        foreach ($orderdetails as $ordetails) {
            $cartinfo = Cart::instance('pos_shopping')->add([
                'id' => $ordetails->product_id,
                'name' => $ordetails->product_name,
                'qty' => $ordetails->qty,
                'price' => $ordetails->sale_price,
                'options' => [
                    'image' => $ordetails->image->image,
                    'purchase_price' => $ordetails->purchase_price,
                    'product_discount' => $ordetails->product_discount,
                    'details_id' => $ordetails->id,
                    'size' => $ordetails->product_size,
                ],
            ]);
        }
        $cartinfo = Cart::instance('pos_shopping')->content();
        return view('backEnd.order.edit', compact('products', 'cartinfo', 'shippingcharge', 'shippinginfo', 'order'));
    }

    public function whosalesorder_edit($invoice_id)
    {
        $products = Product::select('id', 'name', 'new_price', 'product_code')->where(['status' => 1])->get();
        $shippingcharge = ShippingCharge::where('status', 1)->get();
        $order = Order::where('invoice_id', $invoice_id)->first();

        // Get customers for wholesale dropdown
        $customers = Wholesaler::select('id', 'name', 'phone', 'address')
            ->where('status', 'active')
            ->get();

        // Clear existing cart
        $cartinfo = Cart::instance('pos_shopping')->destroy();

        // Get shipping info
        $shippinginfo = Shipping::where('order_id', $order->id)->first();

        // Set session variables
        Session::put('product_discount', $order->discount);
        Session::put('pos_shipping', $order->shipping_charge);

        // Rebuild cart from order details
        $orderdetails = OrderDetails::where('order_id', $order->id)->get();
        foreach ($orderdetails as $ordetails) {
            $cartinfo = Cart::instance('pos_shopping')->add([
                'id' => $ordetails->product_id,
                'name' => $ordetails->product_name,
                'qty' => $ordetails->qty,
                'price' => $ordetails->sale_price,
                'options' => [
                    'image' => $ordetails->image->image ?? 'default.jpg',
                    'purchase_price' => $ordetails->purchase_price,
                    'product_discount' => $ordetails->product_discount,
                    'details_id' => $ordetails->id,
                ],
            ]);
        }

        $cartinfo = Cart::instance('pos_shopping')->content();

        return view('backEnd.order.whosaleredit', compact(
            'products',
            'cartinfo',
            'shippingcharge',
            'shippinginfo',
            'order',
            'customers'  // Add customers to the compact
        ));
    }

 public function order_update(Request $request)
    {
        // ১. ভ্যালিডেশন
        if ($request->whosale_customer_id) {
            $this->validate($request, [
                'name' => 'required',
                'phone' => 'required',
                'address' => 'required',
            ]);
        } else {
            $this->validate($request, [
                'name' => 'required',
                'phone' => 'required',
                'address' => 'required',
                'area' => 'required',
            ]);
        }

        // ২. কার্ট চেক
        if (Cart::instance('pos_shopping')->count() <= 0) {
            Toastr::error('Your shopping cart is empty', 'Failed!');
            return redirect()->back();
        }

        // ৩. কাস্টমার এবং শিপিং ইনফো সেটআপ
        if ($request->whosale_customer_id) {
            $wholesale_customer = Wholesaler::find($request->whosale_customer_id);

            if (!$wholesale_customer) {
                Toastr::error('Wholesale customer not found', 'Failed!');
                return redirect()->back();
            }

            $customer_id = $wholesale_customer->id;
            $customer_name = $wholesale_customer->name;
            $customer_phone = $wholesale_customer->phone;
            $customer_address = $wholesale_customer->address;
            $shippingamount = 0.00;
            $shippingarea = "Office Collection";
        } else {
            $shippingfee = ShippingCharge::find($request->area);
            $shippingamount = $shippingfee ? $shippingfee->amount : 0;
            $shippingarea = $shippingfee ? $shippingfee->name : 'General';
            
            $exits_customer = Customer::where('phone', $request->phone)->first();
            if ($exits_customer) {
                $customer_id = $exits_customer->id;
                $customer_name = $exits_customer->name;
                $customer_phone = $exits_customer->phone;
                $customer_address = $request->address;
            } else {
                $password = rand(111111, 999999);
                $store = new Customer();
                $store->name = $request->name;
                $store->slug = Str::slug($request->name);
                $store->phone = $request->phone;
                $store->address = $request->address;
                $store->password = bcrypt($password);
                $store->verify = 1;
                $store->status = 'active';
                $store->save();

                $customer_id = $store->id;
                $customer_name = $request->name;
                $customer_phone = $request->phone;
                $customer_address = $request->address;
            }
        }

        // ৪. আগের অর্ডার এবং অ্যামাউন্ট লোড
        $order = Order::findOrFail($request->order_id);
        $old_amount = $order->amount; // ব্যালেন্স এডজাস্ট করার জন্য আগের অ্যামাউন্ট রাখা হলো

        // ৫. [গুরুত্বপূর্ণ ফিক্স] রিমুভ করা আইটেম ডাটাবেস থেকে ডিলিট করা
        // বর্তমানে কার্টে যেসব আইটেম আছে তাদের details_id সংগ্রহ করা
        $cart_details_ids = [];
        foreach (Cart::instance('pos_shopping')->content() as $item) {
            if (isset($item->options->details_id) && $item->options->details_id) {
                $cart_details_ids[] = $item->options->details_id;
            }
        }

        // ডাটাবেসে আছে কিন্তু কার্টে নেই -> এমন আইটেম ডিলিট করা
        OrderDetails::where('order_id', $order->id)
            ->whereNotIn('id', $cart_details_ids)
            ->delete();

        // ৬. কার্ট থেকে আইটেম আপডেট অথবা নতুন ক্রিয়েট করা
        foreach (Cart::instance('pos_shopping')->content() as $cart) {
           
        $sizeData = $request->size;
    $finalSize = isset($sizeData[$cart->rowId]) ? $sizeData[$cart->rowId] : ($cart->options->size ?? '');
        $existing_detail = null;
            
            // যদি details_id থাকে তবে ডাটাবেস থেকে খুঁজে বের করা
            if(isset($cart->options->details_id)) {
                $existing_detail = OrderDetails::where('id', $cart->options->details_id)->first();
            }

            if ($existing_detail) {
                // পুরানো আইটেম আপডেট
                $existing_detail->product_discount = $cart->options->product_discount;
                $existing_detail->sale_price = $cart->price; // এডিট করা নতুন প্রাইস
                $existing_detail->qty = $cart->qty;
                $existing_detail->product_size = $finalSize;
                $existing_detail->save();
            } else {
                // নতুন আইটেম তৈরি
                $new_detail = new OrderDetails();
                $new_detail->order_id = $order->id;
                $new_detail->product_id = $cart->id;
                $new_detail->product_name = $cart->name;
                $new_detail->purchase_price = $cart->options->purchase_price;
                $new_detail->product_discount = $cart->options->product_discount;
                $new_detail->sale_price = $cart->price;
                $new_detail->qty = $cart->qty;
                $new_detail->product_size = $finalSize;
                $new_detail->save();
            }
        }

        // ৭. টোটাল অ্যামাউন্ট ডাটাবেস থেকে নির্ভুলভাবে ক্যালকুলেট করা
        // সেশনের ওপর নির্ভর না করে সরাসরি ডিটেইলস টেবিল থেকে যোগফল বের করা হচ্ছে
        $precise_subtotal = 0;
        $order_details_all = OrderDetails::where('order_id', $order->id)->get();
        
        foreach($order_details_all as $detail) {
             // লজিক: (বিক্রয় মূল্য - ডিসকাউন্ট) * পরিমাণ
             $item_discount = $detail->product_discount ?? 0;
             $precise_subtotal += ($detail->sale_price - $item_discount) * $detail->qty;
        }

        // গ্লোবাল ডিসকাউন্ট (যদি থাকে)
        $pos_discount = Session::get('pos_discount') ?? 0;
        $total_discount = $pos_discount + Session::get('product_discount'); // শুধু সেভ করার জন্য

        // ফাইনাল অ্যামাউন্ট = সাবটোটাল + শিপিং - গ্লোবাল ডিসকাউন্ট
        // (নোট: প্রোডাক্ট ডিসকাউন্ট লুপের ভেতরেই মাইনাস করা হয়েছে)
        $new_total_amount = $precise_subtotal + $shippingamount - $pos_discount;

        // ৮. মেইন অর্ডার আপডেট
        $order->amount = $new_total_amount;
        $order->discount = $total_discount;
        $order->shipping_charge = $shippingamount;
        $order->order_source = $request->source_id; 
    $order->assign_user_id = $request->assign_user_id ? $request->assign_user_id : auth()->id();
        
        // রিসেলার অর্ডার না হলে কাস্টমার আপডেট
        if ($order->order_type != 'reseller') {
            $order->customer_id = $customer_id;
            $order->customer_type = $request->whosale_customer_id ? 'wholesale' : 'retail';
        }
        
        $order->note = $request->note;
        $order->save();

        // ৯. হোলসেলার ব্যালেন্স আপডেট লজিক
        if ($request->whosale_customer_id) {
            $wholesaler = Wholesaler::find($request->whosale_customer_id);

            if ($wholesaler) {
                // পার্থক্য বের করা
                $diff_amount = $new_total_amount - $old_amount; 

                // যদি অর্ডারটি রিটার্ন (Status 11) হয়
                if ($order->order_status == 11) {
                    // রিটার্ন অ্যামাউন্ট বাড়লে হোলসেলারের পাওনা (Advance) কমবে
                    $wholesaler->advance -= $diff_amount;
                } else {
                    // সাধারণ সেল হলে অ্যামাউন্ট বাড়লে হোলসেলারের দেনা বাড়বে (অথবা Advance বাড়বে)
                    $wholesaler->advance += $diff_amount;
                }
                
                $wholesaler->save();
            }
        }

        // ১০. শিপিং আপডেট
        $shipping = Shipping::where('order_id', $order->id)->first();
        if ($shipping) {
            $shipping->customer_id = $customer_id;
            $shipping->name = $customer_name;
            $shipping->phone = $customer_phone;
            $shipping->address = $customer_address;
            $shipping->area = $shippingarea;
            $shipping->save();
        } else {
            // যদি শিপিং না থাকে তবে তৈরি করুন
            $shipping = new Shipping();
            $shipping->order_id = $order->id;
            $shipping->customer_id = $customer_id;
            $shipping->name = $customer_name;
            $shipping->phone = $customer_phone;
            $shipping->address = $customer_address;
            $shipping->area = $shippingarea;
            $shipping->save();
        }

        // ১১. পেমেন্ট আপডেট
        $payment = Payment::where('order_id', $order->id)->first();
        if ($payment) {
            $payment->customer_id = $customer_id;
            $payment->amount = $order->amount;
            $payment->save();
        } else {
             $payment = new Payment();
             $payment->order_id = $order->id;
             $payment->customer_id = $customer_id;
             $payment->payment_method = 'Cash On Delivery';
             $payment->amount = $order->amount;
             $payment->payment_status = 'pending';
             $payment->save();
        }

        // ১২. কার্ট ক্লিয়ার এবং রিডাইরেক্ট
        Cart::instance('pos_shopping')->destroy();
        Session::forget('pos_shipping');
        Session::forget('pos_discount');
        Session::forget('product_discount');

        Toastr::success('Order updated successfully', 'Success!');
        
        if ($request->whosale_customer_id) {
            return redirect('admin/order/wholeseller');
        } else {
            return redirect('admin/order/pending');
        }
    }

    public function payinvoice(Request $request)
    {
        $payment = new Payment();
        $payment->order_id = $request->id;
        $payment->customer_id = $request->customer_id;
        $payment->payment_method = $request->payment_method;
        $payment->advance = $request->advance;
        $payment->save();

        return back();
    }


    public function smsinvoice(Request $request)
    {

        $number = $request->customer_number;
        $content = $request->bodysms;
        $cleanContent = preg_replace('/\[\[\s*(.*?)\s*\]\]|\{\{\s*(.*?)\s*\}\}/', '$1$2', $content);
        // Format the phone number
        $formattedPhone = $this->formatPhoneNumber($number);

        // Check if the phone number is valid
        if ($formattedPhone === null) {
            \Log::error('Invalid phone number: ' . $number);
            Toastr::error('Error', 'Invalid phone number');
            return back();
        }

        if ($request->message_type == "sms") {


            // Send the SMS
            $my = $this->sendSms($formattedPhone, $cleanContent);



            Toastr::success('Success', 'SMS Sent successfully');
            return back();
        } else {
            $encodedMessage = urlencode($cleanContent);

            // Check if the user is on mobile or desktop
            $isMobile = strpos(strtolower($_SERVER['HTTP_USER_AGENT']), "mobile") !== false;

            // Generate the WhatsApp URL based on the device type
            if ($isMobile) {
                // For mobile users, redirect to the WhatsApp mobile app link
                $whatsAppUrl = "https://wa.me/{$formattedPhone}?text={$encodedMessage}";
            } else {
                // For desktop users, redirect to the WhatsApp Web link
                $whatsAppUrl = "https://web.whatsapp.com/send?phone={$formattedPhone}&text={$encodedMessage}";
            }

            // Redirect to the appropriate WhatsApp URL
            return redirect()->away($whatsAppUrl);
        }



    }

    public function order_textview(Request $request)
    {
        return view('backEnd.order.textview');
    }

    public function Allorder(Request $request, $slug = 'all')
    {
        // [OPTIMIZATION 1] latest() এর বদলে orderBy ব্যবহার (Fast Sorting)
        $show_data = Order::orderBy('id', 'desc')
            ->with('shipping', 'customer', 'orderdetails.product.image', 'status', 'user')
            ->where('customer_type', 'wholesale');

        // Handle the $slug logic
        if ($slug === 'all') {
            $order_status = (object) [
                'name' => 'All',
                'orders_count' => Order::where('customer_type', 'wholesale')->count(), // [FIX] শুধু হোলসেল কাউন্ট
            ];
        } else {
            $order_status = OrderStatus::where('slug', $slug)->withCount(['orders' => function($q){
                $q->where('customer_type', 'wholesale');
            }])->first();

            if ($order_status) {
                $show_data = $show_data->where('order_status', $order_status->id);
            } else {
                $order_status = (object) [
                    'name' => 'Unknown',
                    'orders_count' => 0,
                ];
                $show_data = $show_data->whereRaw('1 = 0'); 
            }
        }

        // Apply filters
        $show_data = $show_data
            // [OPTIMIZATION 2] Date filter with whereBetween
            ->when($request->filled('date_filter'), function ($query) use ($request) {
                $date_filter = $request->date_filter;
                if ($date_filter === 'today') {
                    $query->whereBetween('created_at', [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()]);
                } elseif ($date_filter === 'this_week') {
                    $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                } elseif ($date_filter === 'this_month') {
                    $query->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
                }
            })
            ->when($request->filled('order_status'), function ($query) use ($request) {
                $query->where('order_status', $request->order_status);
            })
            ->when($request->filled('whosales_order'), function ($query) use ($request) {
                $query->where('customer_id', $request->whosales_order);
            })
            ->when($request->filled('keyword'), function ($query) use ($request) {
                $keyword = $request->keyword;
                $query->where(function ($subQuery) use ($keyword) {
                    $subQuery->where('invoice_id', 'LIKE', '%' . $keyword . '%')
                        ->orWhereHas('shipping', function ($shippingQuery) use ($keyword) {
                            $shippingQuery->where('phone', 'LIKE', '%' . $keyword . '%')
                                ->orWhere('name', 'LIKE', '%' . $keyword . '%');
                        });
                });
            });

        // Pagination
        $show_data = $show_data->paginate(20); 
        $show_data->appends($request->query());

        // [OPTIMIZATION 3] Select specific columns
        $users = User::where('status', 1)->select('id', 'name')->get();
        $orderstatus = OrderStatus::where('status', 1)->get();
        $steadfast = Courierapi::where(['status' => 1, 'type' => 'steadfast'])->first();
        $pathao_info = Courierapi::where(['status' => 1, 'type' => 'pathao'])->first();
        $wholesaes = Wholesaler::select('id', 'name', 'business_name')->get();// Select specific columns

        // [OPTIMIZATION 4] Cache API Responses
        $pathaocities = [];
        $pathaostore = [];

        if ($pathao_info && $pathao_info->token) {
            $pathaocities = \Illuminate\Support\Facades\Cache::remember('pathao_cities', 60 * 24, function () use ($pathao_info) {
                try {
                    $response = Http::timeout(5)->withHeaders([
                        'Authorization' => 'Bearer ' . $pathao_info->token,
                        'Content-Type' => 'application/json',
                    ])->get($pathao_info->url . '/aladdin/api/v1/city-list');
                    return $response->successful() ? $response->json() : ['data' => ['data' => []]];
                } catch (\Exception $e) {
                    return ['data' => ['data' => []]];
                }
            });

            $pathaostore = \Illuminate\Support\Facades\Cache::remember('pathao_stores', 60 * 24, function () use ($pathao_info) {
                try {
                    $response = Http::timeout(5)->withHeaders([
                        'Authorization' => 'Bearer ' . $pathao_info->token,
                        'Content-Type' => 'application/json',
                    ])->get($pathao_info->url . '/aladdin/api/v1/stores');
                    return $response->successful() ? $response->json() : ['data' => ['data' => []]];
                } catch (\Exception $e) {
                    return ['data' => ['data' => []]];
                }
            });
        }

        // [OPTIMIZATION 5] Heavy data selection optimized
        $producthf = Product::where('status', 1)->select('id', 'name', 'product_code')->get();
        $cityg = City::where('status', 1)->select('id', 'name')->get();
        $smsteamplate = SmsTeamplate::where('status', 1)->get();

        return view("backEnd.order.test_all_order", compact(
            'show_data', 'order_status', 'users', 'steadfast', 'pathaostore', 
            'pathaocities', 'producthf', 'cityg', 'smsteamplate', 'orderstatus', 'wholesaes'
        ));
    }


public function order_whosalescreate()
    {
        // [FIX] শুধু তখনই কার্ট ক্লিয়ার করবে যখন কোনো এরর নেই।
        // অর্থাৎ সাবমিট করে ফেইল হলে কার্ট ডিলিট হবে না।
        if (!Session::has('errors')) {
            Cart::instance('pos_shopping')->destroy();
            Session::forget('pos_shipping');
            Session::forget('pos_discount');
            Session::forget('product_discount');
        }
        
        // বাকি কোড যেমন আছে তেমনই থাকবে
        $products = Product::select('id', 'name', 'new_price', 'product_code')
            ->whereNotNull('offer_price')
            ->where(['status' => 1])
            ->get();
            
        $cartinfo = Cart::instance('pos_shopping')->content();
        
        $shippingcharge = ShippingCharge::where('status', 1)->get();
        $customers = Wholesaler::where('status', 'active')
            ->select('id', 'name', 'phone')
            ->orderBy('name', 'asc')
            ->get();
            
        return view('backEnd.order.whosales_create', compact('products', 'cartinfo', 'shippingcharge', 'customers'));
    }

  public function order_whosalesreturn()
    {
        // [FIX] এখানেও একই লজিক
        if (!Session::has('errors')) {
            Cart::instance('pos_shopping')->destroy();
            Session::forget('pos_shipping');
            Session::forget('pos_discount');
            Session::forget('product_discount');
        }

        // বাকি কোড যেমন আছে তেমনই থাকবে
        $products = Product::select('id', 'name', 'new_price', 'product_code')
            ->whereNotNull('offer_price')
            ->where(['status' => 1])
            ->get();
            
        $cartinfo = Cart::instance('pos_shopping')->content(); 
        
        $shippingcharge = ShippingCharge::where('status', 1)->get();
        $customers = Wholesaler::where('status', 'active')
            ->select('id', 'name', 'phone')
            ->orderBy('name', 'asc')
            ->get();
            
        return view('backEnd.order.returninvoice', compact('products', 'cartinfo', 'shippingcharge', 'customers'));
    }

   public function order_whosalesstore(Request $request)
{
    // ১. ভ্যালিডেশন
    if ($request->whosales == 'whosales') {
        $this->validate($request, [
            'whosale_customer_id' => 'required', // নিশ্চিত করা যে আইডি আছে
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);
    } else {
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'area' => 'required',
        ]);
    }

    // ২. কার্ট খালি কিনা চেক করা
    if (Cart::instance('pos_shopping')->count() <= 0) {
        Toastr::error('Your shopping cart is empty', 'Failed!');
        return redirect()->back();
    }

    // ৩. টোটাল ক্যালকুলেশন
    $subtotal = Cart::instance('pos_shopping')->subtotal();
    $subtotal = str_replace(',', '', $subtotal);
    $subtotal = str_replace('.00', '', $subtotal);
    $discount = Session::get('pos_discount') + Session::get('product_discount');
    $shippingfee = ShippingCharge::find($request->area);

    $customer_id = null;
    $customer_name = '';
    $customer_phone = '';
    $customer_address = '';

    if ($request->whosales == 'whosales') {
        $wholesale_customer = Wholesaler::find($request->whosale_customer_id);

        if (!$wholesale_customer) {
            Toastr::error('Wholesale customer not found', 'Failed!');
            return redirect()->back();
        }

        $customer_id = $wholesale_customer->id;
        $customer_name = $wholesale_customer->name;
        $customer_phone = $wholesale_customer->phone;
        $customer_address = $wholesale_customer->address;
        $shippingamount = 0.00;
        $shippingarea = "Office Collection";

    } else {
        $shippingamount = $shippingfee ? $shippingfee->amount : 0;
        $shippingarea = $shippingfee ? $shippingfee->name : "General";
        $exits_customer = Customer::where('phone', $request->phone)
            ->select('phone', 'id', 'name', 'address')
            ->first();

        if ($exits_customer) {
            $customer_id = $exits_customer->id;
            $customer_name = $exits_customer->name;
            $customer_phone = $exits_customer->phone;
            $customer_address = $request->address;
        } else {
            $password = rand(111111, 999999);
            $store = new Customer();
            $store->name = $request->name;
            $store->slug = Str::slug($request->name);
            $store->phone = $request->phone;
            $store->address = $request->address;
            $store->password = bcrypt($password);
            $store->verify = 1;
            $store->status = 'active';
            $store->save();

            $customer_id = $store->id;
            $customer_name = $request->name;
            $customer_phone = $request->phone;
            $customer_address = $request->address;
        }
    }

    // ৪. অর্ডার ডাটা সেভ
    $today = date('Y-m-d');
    $lastOrderId = Order::max('id') ?? 0; // ডুপ্লিকেট ইনভয়েস এড়াতে ID ব্যবহার

    $order = new Order();
    $order->invoice_id = date('Ymd') . '-' . ($lastOrderId + 1);
    $order->amount = ($subtotal + $shippingamount) - $discount;
    $order->discount = $discount ? $discount : 0;
    $order->shipping_charge = $shippingamount;
    $order->customer_id = $customer_id;
    $order->customer_type = $request->whosales;
    
    // যদি রিটার্ন ফর্ম থেকে আসে তবে স্ট্যাটাস ১১ (Returned), নাহলে ১ (Pending)
    $order->order_status = ($request->return_satus == 11) ? 11 : 1;
    
    $order->note = $request->note;
    $order->order_source = $request->source_id;
    $order->save();

    // ৫. শিপিং ডাটা সেভ
    $shipping = new Shipping();
    $shipping->order_id = $order->id;
    $shipping->customer_id = $customer_id;
    $shipping->name = $customer_name;
    $shipping->phone = $customer_phone;
    $shipping->address = $request->whosales == 'retail' ? $request->address : $customer_address;
    $shipping->area = $shippingarea;
    $shipping->save();

    // ৬. পেমেন্ট ডাটা সেভ
    $payment = new Payment();
    $payment->order_id = $order->id;
    $payment->customer_id = $customer_id;
    $payment->payment_method = 'Cash On Delivery';
    $payment->amount = $order->amount;
    $payment->payment_status = 'pending';
    $payment->save();

    // ====================================================
    // ৭. হোলসেলার ব্যালেন্স আপডেট
    // ====================================================
    if ($request->whosales == 'whosales') {
        $wholesale_payment = Wholesaler::find($request->whosale_customer_id);
        if ($wholesale_payment) {
            // যদি রিটার্ন ইনভয়েস হয় তবে টাকা কমবে, নাহলে অর্ডারের টাকা বাড়বে
            if ($request->return_satus == 11) {
                $wholesale_payment->advance -= $order->amount;
            } else {
                $wholesale_payment->advance += $order->amount;
            }
            $wholesale_payment->save();
        }
    }

    // ৮. অর্ডার ডিটেইলস এবং স্টক ম্যানেজমেন্ট
    foreach (Cart::instance('pos_shopping')->content() as $cart) {
        $order_details = new OrderDetails();
        $order_details->order_id = $order->id;
        $order_details->product_id = $cart->id;
        $order_details->product_name = $cart->name;
        $order_details->purchase_price = $cart->options->purchase_price;
        $order_details->product_discount = $cart->options->product_discount;
        $order_details->sale_price = $cart->price;
        $order_details->qty = $cart->qty;
        $order_details->save();

        // যদি এটি রিটার্ন ইনভয়েস হয়, তবে স্টক বাড়িয়ে দিতে হবে
        if ($request->return_satus == 11) {
            $product = Product::find($cart->id);
            if ($product) {
                $product->increment('stock', $cart->qty);
                
                // ইনভেন্টরি লগ রাখা
                InventoryLog::create([
                    'product_id'    => $product->id,
                    'type'          => 'wholesale_return', 
                    'quantity'      => $cart->qty,
                    'ref_id'        => $order->invoice_id,
                    'note'          => 'Wholesale Return Received',
                    'current_stock' => $product->stock
                ]);
            }
        }
    }

    // ৯. কার্ট এবং সেশন ক্লিয়ার করা
    Cart::instance('pos_shopping')->destroy();
    Session::forget('pos_shipping');
    Session::forget('pos_discount');
    Session::forget('product_discount');

    Toastr::success('Thanks, Your order has been placed successfully', 'Success!');
    
    // হোলসেল হলে হোলসেল লিস্টে আর রিটেইল হলে পেন্ডিং লিস্টে পাঠাবে
    if ($request->whosales == 'whosales') {
        return redirect('admin/order/wholeseller');
    } else {
        return redirect('admin/order/pending');
    }
}





   public function testSaveorder(Request $request)
{
    // ১. ভ্যালিডেশন
    $this->validate($request, [
        'customerName' => 'required',
        'phoneNumber' => 'required',
        'products' => 'required|array',
    ]);

    // ২. কাস্টমার হ্যান্ডেল করা
    $existingCustomer = Customer::where('phone', $request->phoneNumber)->first();

    if ($existingCustomer) {
        $existingCustomer->name = $request->customerName;
        $existingCustomer->slug = $request->customerName;
        $existingCustomer->phone = $request->phoneNumber;
        $existingCustomer->save();
        $customerId = $existingCustomer->id;
    } else {
        // নতুন কাস্টমার তৈরির প্রয়োজন হলে এখানে লজিক দিতে পারেন
        $customerId = null; 
    }

    $subtotal = 0;
    $discount = 0;
    $products = $request->products;

    foreach ($products as $product) {
        $subtotal += $product['sellPrice'] ?? 0;
    }

    // ৩. অর্ডার আপডেট
    $order = Order::find($request->orderId);
    $previous_status = $order->order_status;
    if ($order) {
        $order->amount = ($subtotal + $request->delivery_charge) - $discount;
        $order->discount = $discount;
        $order->shipping_charge = $request->delivery_charge;
        if ($order->order_type != 'reseller' && $order->customer_type != 'wholesale') {
            $order->customer_id = $customerId ?? $order->customer_id;
        }
        $order->order_status = $request->orderStatus;
        $order->assign_user_id = $request->assign;
        $order->note = $request->comment;
        $order->save();
    // =========================================================
        // MODERATOR COMMISSION SYSTEM (For Quick Edit)
        // =========================================================
       // [FIX] ID আপডেট করা হয়েছে
        $success_status_id = 10; // Delivered
        $return_status_id = 11;  // Returned
        
        if ($order->assign_user_id) {
            $moderator = \App\Models\User::find($order->assign_user_id);
            
            if ($moderator) {
                $commission_amount = ($order->amount * ($moderator->commission_rate / 100)) + $moderator->fixed_commission;

                if ($request->orderStatus == $success_status_id && $previous_status != $success_status_id) {
                    $moderator->increment('balance', $commission_amount);
                }
                elseif ($request->orderStatus == $return_status_id && $previous_status == $success_status_id) {
                    $moderator->decrement('balance', $commission_amount);
                }
            }
        }
        // =========================================================

        // ==============================================================
        // [START] Reseller Auto Activation (For Quick Edit Modal)
        // ==============================================================
    

try {
        // [DEBUG LINE] এই লাইনটি সবসময় লগ ফাইলে আসবে
       // \Log::info("Quick Edit Hit. Order ID: " . $order->id . " | Type: " . $order->order_type . " | Status Request: " . $request->orderStatus);

        if ($request->orderStatus == 10) { 
            
            // চেক করা হচ্ছে এটি রিসেলার বা হোলসেল অর্ডার কি না
            if ($order->order_type == 'reseller' || $order->customer_type == 'wholesale') {
                
               // \Log::info("Reseller Logic Started for Order: " . $order->id);

                $reseller_active = \App\Models\Reseller::find($order->customer_id);

                if ($reseller_active) {
                    if ($reseller_active->status != 1) {
                        $reseller_active->status = 1; 
                        $reseller_active->save();
                        \Log::info("SUCCESS: Reseller " . $reseller_active->id . " Activated.");
                    } else {
                       // \Log::info("INFO: Reseller " . $reseller_active->id . " is already active.");
                    }
                } else {
                   // \Log::info("WARNING: Reseller ID " . $order->customer_id . " not found in database.");
                }
            } else {
                //\Log::info("SKIPPED: This is a '" . $order->order_type . "' order, not a Reseller order.");
            }
        }
    } catch (\Throwable $e) {
       // \Log::error("Reseller Activation Error: " . $e->getMessage());
    }





        // ==============================================================








       
    }

    // ৪. শিপিং ডাটা আপডেট
    $shipping = Shipping::where('order_id', $request->orderId)->first();
    if ($shipping) {
        $shipping->name = $request->customerName;
        $shipping->phone = $request->phoneNumber;
        if (!is_null($request->addressDistrict)) {
            $shipping->district = $request->addressDistrict;
        }
        if (!is_null($request->addressThana)) {
            $shipping->thana = $request->addressThana;
        }
        $shipping->address = $request->address_info;
        $shipping->save();
    }

    // ৫. পেমেন্ট ডাটা আপডেট
    $payment = Payment::where('order_id', $request->orderId)->first();
    if ($payment) {
        $payment->amount = $order->amount;
        $payment->save();
    }

    // ৬. অর্ডার ডিটেইলস (প্রোডাক্টস) আপডেট
    foreach ($products as $product) {
        $orderDetails = OrderDetails::where('order_id', $request->orderId)
            ->where('product_id', $product['id'])->first();

        if (!$orderDetails) {
            $orderDetails = new OrderDetails();
            $orderDetails->order_id = $request->orderId;
            $orderDetails->product_id = $product['id'];
            $orderDetails->product_name = $product['name'] ?? 'N/A';
            $orderDetails->product_size = $product['size'];
            $orderDetails->sale_price = $product['sellPrice'];
            $orderDetails->qty = 1;
            $orderDetails->product_discount = $product['discount'] ?? 0;
            $orderDetails->save();
        } else {
            $orderDetails->sale_price = $product['sellPrice'];
            $orderDetails->product_discount = $product['discount'] ?? 0;
            $orderDetails->save();
        }
    }





 // ================= SST TRIGGER (For testSaveorder) =================
        try {
            $gs = \App\Models\GeneralSetting::first();
            
            // শর্ত: স্ট্যাটাস যদি Confirmed (6) হয় এবং সেটিংস 'admin' মোডে থাকে
            if ($request->orderStatus == 6 && $gs && $gs->pixel_trigger_type == 'admin') {

             $order->refresh();
                
                $order->load(['orderdetails', 'shipping', 'customer']);
                
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

                $trackingData = [
                    'event_name'      => 'Purchase',
                    'event_id'        => (string) $order->id,
                    'name'            => $order->shipping->name ?? null,
                    'city'            => $order->shipping->district ?? $order->shipping->area,
                    'address'         => $order->shipping->address ?? null,
                    'zip'             => $order->shipping->zip_code ?? null,
                    'country'         => 'bd',
                    'email'           => $order->shipping->email ?? null,
                    'phone'           => $order->shipping->phone,
                    'external_id'     => (string) ($order->customer_id ?? $order->shipping->phone),
                    'amount'          => $order->amount,
                    'currency'        => 'BDT',
                    'source_url'      => url('/'),
                    'fbp'             => null, 
                    'fbc'             => null,
                    'ttp'             => null,
                    'gclid'           => $order->gclid,
                    'ip'              => $order->ip_address ?? null, 
                    'user_agent'      => $order->user_agent ?? null,
                    'content_ids'     => $content_ids,
                    'contents_tiktok' => $contents_tiktok
                ];

                SendServerSideEventJob::dispatch($trackingData);
                
                // কনফার্মেশন লগ
                \Log::info('SST Purchase Sent from testSaveorder for Order: ' . $order->id);
            }
        } catch (\Exception $e) {
            \Log::error('SST Error in testSaveorder: ' . $e->getMessage());
        }
        // ================= SST END =================










    // ৭. AJAX এর জন্য প্রয়োজনীয় ডাটা রিটার্ন করা (মূল সমাধান এখানে)
    $statusInfo = \App\Models\OrderStatus::find($request->orderStatus);

    return response()->json([
        'status' => 'success',
        'message' => 'Order updated successfully!',
        'status_name' => $statusInfo->name ?? 'Updated',
        'status_color' => $statusInfo->colorcode ?? '#6c757d'
    ]);
}

    public function getThanas(Request $request)
    {
        $districtId = $request->district_id;

        $thanas = Thana::where('city_id', $districtId)->get(['id', 'name']);

        return response()->json($thanas);
    }
  public function removePro(Request $request)
{
    $orderId = $request->ordersId;
    $deleted = OrderDetails::where('order_id', $orderId)
        ->where('product_id', $request->product_id)->delete();

    if ($deleted) {
        // [FIX] আইটেম ডিলিট হওয়ার পর অর্ডারের মোট টাকা পুনরায় হিসেব করা
        $order = Order::find($orderId);
        if($order) {
            $order->updateOrderTotal(); 
        }
        return response()->json(['status' => 'success', 'message' => 'Product removed and order total updated']);
    } else {
        return response()->json(['status' => 'error', 'message' => 'Product not found or already deleted'], 404);
    }
}

    public function singlesms(Request $request)
    {

        $content = $request->sms_text;

        $order_id = $request->selectedBanknumbers;

        $shipping = Shipping::where('order_id', $orderId)->first();


        $this->sendSms($shipping->phone, $content);


        return response('status', 'Sms Sent Successfully');

    }

    public function multisms(Request $request)
    {

        $content = $request->sms_text;
        $orderIds = $request->banknumber;

        if (!is_array($orderIds) && !is_object($orderIds)) {
            return response()->json(['error' => 'Invalid data for order_id.'], 400);
        }

        foreach ($orderIds as $id) {
            $orderId = (int) trim($id);

            $shipping = Shipping::where('order_id', $orderId)->first();

            if (!$shipping || empty($shipping->phone)) {
                // Skip invalid entries instead of stopping the whole loop
                continue;
            }

            $this->sendSms($shipping->phone, $content);
        }

        return response()->json(['message' => 'SMS sent successfully.'], 200);


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


    private function sendSms($phone, $message)
    {
        $smsgateway = SmsGateway::latest()->first(); // Get the latest record

        if (!$smsgateway) {
            \Log::error('No SMS gateway configuration found.');
            return 'SMS Gateway configuration missing.';
        }

        // Use the actual values, not strings
        $url = $smsgateway->url;

        // Set up the data to send to the API
        $data = [
            "UserName" => $smsgateway->username,
            "Apikey" => $smsgateway->api_key,
            "MobileNumber" => $phone,
            "CampaignId" => "",
            "SenderName" => $smsgateway->serderid,
            "TransactionType" => "T",
            "Message" => $message,
        ];

        // Initialize cURL session
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

        // Log the response body
        \Log::info("API Response: HTTP Code $httpCode. Response: $response");

        // Check for cURL errors
        if (curl_errno($ch)) {
            \Log::error('SMS cURL error: ' . curl_error($ch));
        }

        // Close the cURL session
        curl_close($ch);

        return $response;

    }

    private function balanceCheck()
    {
        $smsgateway = SmsGateway::latest()->first();
        $username = urlencode($smsgateway->username);
        $apiKey = urlencode($smsgateway->api_key);

        $url = "https://api.mimsms.com/api/SmsSending/balanceCheck?userName={$username}&Apikey={$apiKey}";

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Accept: application/json'],
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);

        curl_close($ch);

        // Debug logging
        \Log::info("MiMSMS API Response (HTTP $httpCode): $response");

        // Handle cURL errors
        if ($curlError) {
            \Log::error("MiMSMS cURL Error: $curlError");
            return null;
        }

        // Check HTTP response code
        if ($httpCode !== 200) {
            \Log::error("MiMSMS Balance Check failed. HTTP Code: $httpCode. Raw response: $response");
            return null;
        }

        // Decode response
        $data = json_decode($response, true);

        // Check if JSON decoding worked
        if (json_last_error() !== JSON_ERROR_NONE) {
            \Log::error("JSON decode error: " . json_last_error_msg());
            return null;
        }

        // Return balance
        return $data['responseResult'] ?? null;
    }

    public function productReport(Request $request)
    {
        $filter = $request->get('filter', 'month');
        $search = $request->get('search');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Date period format
        switch ($filter) {
            case 'date':
                $periodExpr = DB::raw('DATE(o.created_at)');
                break;
            case 'week':
                $periodExpr = DB::raw('YEARWEEK(o.created_at, 1)');
                break;
            case 'month':
            default:
                $periodExpr = DB::raw("DATE_FORMAT(o.created_at, '%Y-%m')");
                break;
        }

        // ✅ Dynamically fetch statuses from product_statuses table
        $statuses = DB::table('order_statuses')->pluck('name', 'id')->toArray(); // [id => name]

        // Start query
        $query = DB::table('orders as o')
            ->join('order_details as od', 'o.id', '=', 'od.order_id')
            ->join('products as p', 'od.product_id', '=', 'p.id')
            ->select(
                'od.product_id',
                'p.product_code as SKU',
                'p.name as ProductName'
            )
            ->selectRaw($periodExpr . ' as period')
            ->selectRaw('COUNT(DISTINCT o.id) as TotalOrders');

        // ✅ Add dynamic status count columns
        foreach ($statuses as $id => $name) {
            $query->selectRaw("SUM(CASE WHEN o.order_status = ? THEN 1 ELSE 0 END) as `$name`", [$id]);
        }

        // Search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('p.product_code', 'like', "%$search%")
                    ->orWhere('p.name', 'like', "%$search%");
            });
        }

        // Date filter
        if ($startDate && $endDate) {
            $query->whereBetween('o.created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        }

        // Group by
        $query->groupBy(
            'od.product_id',
            'p.product_code',
            'p.name',
            $periodExpr
        );

        // Execute
        $report_data = $query->orderByDesc('period')->paginate(20)->withQueryString();

        return view('backEnd.reports.product_report', compact(
            'report_data',
            'filter',
            'search',
            'statuses',
            'startDate',
            'endDate'
        ));
    }

    public function productReport1(Request $request)
    {
        $filter = $request->get('filter', 'month');
        $search = $request->get('search');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        // Define period expression dynamically
        switch ($filter) {
            case 'date':
                $periodExpr = DB::raw('DATE(o.created_at)');
                break;
            case 'week':
                $periodExpr = DB::raw('YEARWEEK(o.created_at, 1)');
                break;
            case 'month':
            default:
                $periodExpr = DB::raw("DATE_FORMAT(o.created_at, '%Y-%m')");
                break;
        }

        // Define human-readable statuses
        $statusMap = [
            1 => 'Pending',
            2 => 'Processing',
            3 => 'OnTheWay',
            4 => 'Hold',
            5 => 'InCourier',
            6 => 'Confirmed',
            7 => 'Cancelled',
            8 => 'NotResponding',
            10 => 'Delivered',
            11 => 'Returned',
            12 => 'Shipped',
        ];

        // Start the query
        $query = DB::table('orders as o')
            ->join('order_details as od', 'o.id', '=', 'od.order_id')
            ->join('products as p', 'od.product_id', '=', 'p.id')
            ->select(
                'od.product_id',
                'p.product_code as SKU',
                'p.name as ProductName'
            )
            ->selectRaw($periodExpr . ' as period')
            ->selectRaw('COUNT(DISTINCT o.id) as TotalOrders');

        // Add dynamic status columns
        foreach ($statusMap as $code => $label) {
            $query->selectRaw("SUM(CASE WHEN o.order_status = $code THEN 1 ELSE 0 END) as $label");
        }

        // Apply search if provided
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('p.product_code', 'like', "%$search%")
                    ->orWhere('p.name', 'like', "%$search%");
            });
        }
        if ($startDate && $endDate) {
            $query->whereBetween('o.created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        }

        // Apply group by
        $query->groupBy(
            'od.product_id',
            'p.product_code',
            'p.name',
            $periodExpr
        );

        // Execute query with pagination
        $report_data = $query->orderByDesc('period')->paginate(10)->withQueryString();

        // Pass data to view
        return view('backEnd.reports.product_report', compact('report_data', 'filter', 'search', 'statusMap', 'startDate', 'endDate'));
    }

    public function Wholeseller()
    {
        return view('backEnd.wholeseller.index'); // or any view path you want
    }
    public function getOrderStatusReport()
    {
        $statuses = ['pending', 'processing', 'completed', 'cancelled'];
        $reportData = [];
        foreach ($statuses as $status) {
            $reportData[$status] = Order::where('status', $status)->count();
        }
        return view('admin.order_status_report', compact('reportData'));
    }

public function cart_update(Request $request)
{
    $request->validate([
        'rowId' => 'required',
        'qty'   => 'required|integer|min:1',
    ]);

    $cartinfo = Cart::instance('pos_shopping')
        ->update($request->rowId, $request->qty);

    return response()->json([
        'success' => true,
        'cart' => $cartinfo
    ]);
}

public function product_size(Request $request)
{
    $cart = Cart::instance('pos_shopping');
    $item = $cart->get($request->id);

    if ($item) {
        // কার্ট সেশনে সাইজ আপডেট করা হচ্ছে
        $options = $item->options->merge(['size' => $request->size])->toArray();
        $cart->update($request->id, ['options' => $options]);
        return response()->json(['status' => 'success']);
    }
    return response()->json(['status' => 'error'], 404);
}

}