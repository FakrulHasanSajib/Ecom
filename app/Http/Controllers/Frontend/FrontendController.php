<?php

namespace App\Http\Controllers\Frontend;

use shurjopayv2\ShurjopayLaravelPackage8\Http\Controllers\ShurjopayController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Childcategory;
use App\Models\Product;
use App\Models\Flashdeal;
use App\Models\District;
use App\Models\CreatePage;
use App\Models\Campaign;
use App\Models\Banner;
use App\Models\BannerCategory;
use App\Models\Contact;
use App\Models\Orderissue;
use App\Models\ShippingCharge;
use App\Models\Productcolor;
use App\Models\Productsize;
use App\Models\Customer;
use App\Models\OrderDetails;
use App\Models\Authorloylity;
use App\Models\Payment;
use App\Models\Order;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Reseller;
use App\Models\Area;
use App\Models\City;
use App\Models\Thana;
use App\Models\RecruitmentCampaign;
use App\Models\VisitorActivity;
use Session;
use Cart;
use Auth;
use Carbon\Carbon;

class FrontendController extends Controller
{
    public function index()
    {
        $sevenDaysAgo = Carbon::now()->subDays(7);
        $frontcategory = Category::where(['status' => 1])
            ->select('id', 'name', 'image', 'slug', 'status')
            ->get();

        $sliders = Banner::where(['status' => 1, 'category_id' => 1])
            ->select('id', 'image', 'link')
            ->get();

        $sliderbottomads = Banner::where(['status' => 1, 'category_id' => 5])
            ->select('id', 'image', 'link')
            ->limit(3)
            ->get();

        $footertopads = Banner::where(['status' => 1, 'category_id' => 6])
            ->select('id', 'image', 'link')
            ->limit(2)
            ->get();

        $middlebanner = Banner::where('status', 1)
            ->where('category_id', 7)
            ->select('id', 'image', 'link')
            ->orderByDesc('id')
            ->first();
        $sliderbanner = Banner::where('status', 1)
            ->where('category_id', 8)
            ->select('id', 'image', 'link')
            ->orderByDesc('id')
            ->get();

        $hotdeal_top = Product::where(['status' => 1, 'topsale' => 1])
            ->orderBy('id', 'DESC')
            ->select('id', 'name', 'slug', 'new_price', 'old_price', 'free_shipping')
            ->with('prosizes', 'procolors')
            ->inRandomOrder()
            ->limit(12)
            ->get();
        // return $hotdeal_top;
        $new_arrival = Product::orderBy('id', 'DESC')
            ->select('id', 'name', 'slug', 'new_price', 'old_price', 'free_shipping')
            ->with('prosizes', 'procolors')
            ->limit(12)
            ->get();
        $new_freeshiping = Product::orderBy('id', 'DESC')
            ->select('id', 'name', 'slug', 'new_price', 'old_price', 'free_shipping')
            ->with('prosizes', 'procolors')
            ->where('free_shipping', 1)->limit(12)
            ->get();
        $bestSellingProducts = Product::leftJoin('order_details', 'order_details.product_id', '=', 'products.id')
            ->leftJoin('orders', 'orders.id', '=', 'order_details.order_id')
            ->selectRaw('SUM(order_details.qty) as total_sales')
            ->select('products.id', 'products.name', 'products.slug', 'products.old_price', 'products.new_price')
            ->where('orders.created_at', '>=', $sevenDaysAgo) // Filter by orders in the last 7 days
            ->groupBy('products.id', 'products.name', 'products.slug', 'products.old_price', 'products.new_price') // Group by all selected columns
            ->orderByRaw('SUM(order_details.qty) DESC') // Order by the sum of qty in order_details
            ->with('prosizes', 'procolors')
            ->get();

        $hotdeal_bottom = Product::where(['status' => 1, 'topsale' => 1])
            ->select('id', 'name', 'slug', 'new_price', 'old_price', 'free_shipping')
            ->skip(12)
            ->limit(12)
            ->get();

        $homeproducts = Category::where(['front_view' => 1, 'status' => 1])
            ->orderBy('id', 'ASC')
            ->with(['products', 'products.image', 'products.prosize', 'products.procolor'])
            ->get()
            ->map(function ($query) {
                $query->setRelation('products', $query->products->take(12));
                return $query;
            });
        $products = Product::paginate(12);
        // return $homeproducts;
        return view('frontEnd.layouts.pages.index', compact('sliders', 'frontcategory', 'hotdeal_top', 'hotdeal_bottom', 'homeproducts', 'sliderbottomads', 'footertopads', 'products', 'middlebanner', 'new_arrival', 'bestSellingProducts', 'sliderbanner', 'new_freeshiping'));
    }

    public function getMoreProducts(Request $request)
    {
        if ($request->ajax()) {
            // Fetch next set of products based on page number
            $products = Product::paginate(12); // This ensures that 8 products per page are fetched

            // Return the partial view that will be appended
            return view('frontEnd.layouts.pages.partial.productview', compact('products'));
        }
    }

    public function hotdeals()
    {

        $products = Product::where(['status' => 1, 'topsale' => 1])
            ->select('id', 'name', 'slug', 'new_price', 'old_price')
            ->paginate(36);
        return view('frontEnd.layouts.pages.hotdeals', compact('products'));
    }

    public function flashdealsdetails($slug)
    {

        $flash_deal = Flashdeal::where('slug', $slug)->first();


        if ($flash_deal != null)
            return view('frontEnd.layouts.pages.flashdeal', compact('flash_deal'));
        else {
            abort(404);
        }

    }
    public function authorprodetails($slug)
    {

        $author_page = Authorloylity::where('page_link', $slug)->first();

        if ($author_page != null) {
            // Decode the JSON product_ids
            $product_ids = json_decode($author_page->product_id, true);

            // Get the author details from user table
            $author = User::find($author_page->author_id);

            // Get all products for this author page
            $products = Product::whereIn('id', $product_ids)->get();

            return view('frontEnd.layouts.pages.authorpage', compact('author_page', 'products', 'author'));
        } else {
            abort(404);
        }

    }

    public function freeshippingdetails()
    {

        $banner_category = BannerCategory::where('name', 'Free Shipping')->first();


        if ($banner_category != null) {
            $banaer = Banner::where('category_id', $banner_category->id)->first();
            $products = Product::where(['status' => 1, 'free_shipping' => 1])
                ->select('id', 'name', 'slug', 'new_price', 'old_price')
                ->paginate(36);
            return view('frontEnd.layouts.pages.freeshipping', compact('banaer', 'products'));
        } else {
            abort(404);
        }

    }

    public function category($slug, Request $request)
    {
        $category = Category::where(['slug' => $slug, 'status' => 1])->first();
        $products = Product::where(['status' => 1, 'category_id' => $category->id])
            ->select('id', 'name', 'slug', 'new_price', 'old_price', 'category_id');
        $subcategories = Subcategory::where('category_id', $category->id)->get();

        // return $request->sort;
        if ($request->sort == 1) {
            $products = $products->orderBy('created_at', 'desc');
        } elseif ($request->sort == 2) {
            $products = $products->orderBy('created_at', 'asc');
        } elseif ($request->sort == 3) {
            $products = $products->orderBy('new_price', 'desc');
        } elseif ($request->sort == 4) {
            $products = $products->orderBy('new_price', 'asc');
        } elseif ($request->sort == 5) {
            $products = $products->orderBy('name', 'asc');
        } elseif ($request->sort == 6) {
            $products = $products->orderBy('name', 'desc');
        } else {
            $products = $products->latest();
        }

        $min_price = $products->min('new_price');
        $max_price = $products->max('new_price');
        if ($request->min_price && $request->max_price) {
            $products = $products->where('new_price', '>=', $request->min_price);
            $products = $products->where('new_price', '<=', $request->max_price);
        }

        $selectedSubcategories = $request->input('subcategory', []);
        $products = $products->when($selectedSubcategories, function ($query) use ($selectedSubcategories) {
            return $query->whereHas('subcategory', function ($subQuery) use ($selectedSubcategories) {
                $subQuery->whereIn('id', $selectedSubcategories);
            });
        });

        $products = $products->paginate(24);
        return view('frontEnd.layouts.pages.category', compact('category', 'products', 'subcategories', 'min_price', 'max_price'));
    }

    public function subcategory($slug, Request $request)
    {
        $subcategory = Subcategory::where(['slug' => $slug, 'status' => 1])->first();
        $products = Product::where(['status' => 1, 'subcategory_id' => $subcategory->id])
            ->select('id', 'name', 'slug', 'new_price', 'old_price', 'category_id', 'subcategory_id');
        $childcategories = Childcategory::where('subcategory_id', $subcategory->id)->get();

        // return $request->sort;
        if ($request->sort == 1) {
            $products = $products->orderBy('created_at', 'desc');
        } elseif ($request->sort == 2) {
            $products = $products->orderBy('created_at', 'asc');
        } elseif ($request->sort == 3) {
            $products = $products->orderBy('new_price', 'desc');
        } elseif ($request->sort == 4) {
            $products = $products->orderBy('new_price', 'asc');
        } elseif ($request->sort == 5) {
            $products = $products->orderBy('name', 'asc');
        } elseif ($request->sort == 6) {
            $products = $products->orderBy('name', 'desc');
        } else {
            $products = $products->latest();
        }

        $min_price = $products->min('new_price');
        $max_price = $products->max('new_price');
        if ($request->min_price && $request->max_price) {
            $products = $products->where('new_price', '>=', $request->min_price);
            $products = $products->where('new_price', '<=', $request->max_price);
        }

        $selectedChildcategories = $request->input('childcategory', []);
        $products = $products->when($selectedChildcategories, function ($query) use ($selectedChildcategories) {
            return $query->whereHas('childcategory', function ($subQuery) use ($selectedChildcategories) {
                $subQuery->whereIn('id', $selectedChildcategories);
            });
        });

        $products = $products->paginate(24);
        // return $products;
        $impproducts = Product::where(['status' => 1, 'topsale' => 1])
            ->with('image')
            ->limit(6)
            ->select('id', 'name', 'slug')
            ->get();

        return view('frontEnd.layouts.pages.subcategory', compact('subcategory', 'products', 'impproducts', 'childcategories', 'max_price', 'min_price'));
    }

    public function products($slug, Request $request)
    {
        $childcategory = Childcategory::where(['slug' => $slug, 'status' => 1])->first();
        $childcategories = Childcategory::where('subcategory_id', $childcategory->subcategory_id)->get();
        $products = Product::where(['status' => 1, 'childcategory_id' => $childcategory->id])->with('category')
            ->select('id', 'name', 'slug', 'new_price', 'old_price', 'category_id', 'subcategory_id', 'childcategory_id');


        // return $request->sort;
        if ($request->sort == 1) {
            $products = $products->orderBy('created_at', 'desc');
        } elseif ($request->sort == 2) {
            $products = $products->orderBy('created_at', 'asc');
        } elseif ($request->sort == 3) {
            $products = $products->orderBy('new_price', 'desc');
        } elseif ($request->sort == 4) {
            $products = $products->orderBy('new_price', 'asc');
        } elseif ($request->sort == 5) {
            $products = $products->orderBy('name', 'asc');
        } elseif ($request->sort == 6) {
            $products = $products->orderBy('name', 'desc');
        } else {
            $products = $products->latest();
        }

        $min_price = $products->min('new_price');
        $max_price = $products->max('new_price');
        if ($request->min_price && $request->max_price) {
            $products = $products->where('new_price', '>=', $request->min_price);
            $products = $products->where('new_price', '<=', $request->max_price);
        }

        $products = $products->paginate(24);
        // return $products;
        $impproducts = Product::where(['status' => 1, 'topsale' => 1])
            ->with('image')
            ->limit(6)
            ->select('id', 'name', 'slug')
            ->get();

        return view('frontEnd.layouts.pages.childcategory', compact('childcategory', 'products', 'impproducts', 'min_price', 'max_price', 'childcategories'));
    }


   public function details($slug)
{
    // ১. মেইন প্রোডাক্ট লোড (Eager Loading সহ সব রিলেশন একসাথে আনা হচ্ছে)
    // আলাদা করে Productcolor বা Productsize কুয়েরি আর লাগবে না
    $details = Product::where(['slug' => $slug, 'status' => 1])
        ->with([
            'image', 
            'images', 
            'category', 
            'subcategory', 
            'childcategory',
            'brand',            // ভিউ ফাইলে ব্র্যান্ড নেম দেখানোর জন্য
            'procolors.color',  // কালার রিলেশন একসাথে লোড
            'prosizes.size',    // সাইজ রিলেশন একসাথে লোড
            'reviews.customer'  // রিভিউ এবং কাস্টমার ইনফো একসাথে লোড
        ])
        ->firstOrFail();

    // ২. রিলেটেড প্রোডাক্ট (এখানেও prosizes ও procolors লোড করা হলো যাতে লুপে কুয়েরি না হয়)
    $products = Product::where(['category_id' => $details->category_id, 'status' => 1])
        ->where('id', '!=', $details->id)
        ->with(['image', 'prosizes', 'procolors']) 
        ->latest()
        ->take(10)
        ->get();

    // ৩. ফলব্যাক প্রোডাক্ট (যদি রিলেটেড প্রোডাক্ট না পাওয়া যায়)
    if ($products->count() == 0) {
        $products = Product::where('status', 1)
            ->where('id', '!=', $details->id)
            ->with(['image', 'prosizes', 'procolors'])
            ->latest()
            ->take(10)
            ->get();
    }

    // ৪. শিপিং চার্জ
    $shippingcharge = ShippingCharge::where('status', 1)->get();

    // ৫. ভেরিয়েবল অ্যাসাইনমেন্ট (আলাদা কুয়েরি না করে $details থেকে ডাটা নেওয়া হচ্ছে)
    // এটি আপনার ভিউ ফাইলের আগের কোড ঠিক রাখবে
    $productcolors = $details->procolors;
    $productsizes = $details->prosizes;
    $reviews = $details->reviews;

    return view('frontEnd.layouts.pages.details', compact('details', 'products', 'shippingcharge', 'productcolors', 'productsizes', 'reviews'));
}
    public function quickview(Request $request)
    {
        $data['data'] = Product::where(['id' => $request->id, 'status' => 1])->with('images')->withCount('reviews')->first();
        $data = view('frontEnd.layouts.ajax.quickview', $data)->render();
        if ($data != '') {
            echo $data;
        }
    }
    public function livesearch(Request $request)
    {
        $products = Product::select('id', 'name', 'slug', 'new_price', 'old_price')
            ->where('status', 1)
            ->with('image');
        if ($request->keyword) {
            $products = $products->where('name', 'LIKE', '%' . $request->keyword . "%");
        }
        if ($request->category) {
            $products = $products->where('category_id', $request->category);
        }
        $products = $products->get();

        if (empty($request->category) && empty($request->keyword)) {
            $products = [];
        }
        return view('frontEnd.layouts.ajax.search', compact('products'));
    }
    public function search(Request $request)
    {
        $products = Product::select('id', 'name', 'slug', 'new_price', 'old_price')
            ->where('status', 1)
            ->with('image');
        if ($request->keyword) {
            $products = $products->where('name', 'LIKE', '%' . $request->keyword . "%");
        }
        if ($request->category) {
            $products = $products->where('category_id', $request->category);
        }
        $products = $products->paginate(36);
        $keyword = $request->keyword;
        return view('frontEnd.layouts.pages.search', compact('products', 'keyword'));
    }


public function shipping_charge(Request $request)
    {
        // ১. প্রথমে এরিয়া আইডি দিয়ে শিপিং চার্জের তথ্য নিয়ে আসা
        $shippingCharge = ShippingCharge::find($request->id);
        $shippingAmount = $shippingCharge->amount ?? 0.00;

        // ২. কার্ট ইন্সট্যান্স থেকে সব প্রোডাক্ট চেক করা (Landing Page ও Main Website উভয়ের জন্য)
        $cart_content = Cart::instance('shopping')->content();
        
        $isFreeShipping = false;
        foreach ($cart_content as $item) {
            // প্রোডাক্ট টেবিল থেকে ঐ প্রোডাক্টের free_shipping স্ট্যাটাস চেক করা
            $product = Product::find($item->id);
            if ($product && $product->free_shipping == 1) {
                $isFreeShipping = true;
                break; 
            }
        }

        // ৩. যদি কোনো একটি প্রোডাক্টও ফ্রি শিপিং হয়, তবে শিপিং চার্জ ০ করে দেওয়া
        if ($isFreeShipping) {
            $shippingAmount = 0.00;
        }

        // ৪. সেশনে নতুন শিপিং অ্যামাউন্ট সেভ করা
        Session::put('shipping', $shippingAmount);

        // ৫. রিকোয়েস্ট অনুযায়ী সঠিক ব্লেড ভিউ রিটার্ন করা
        if ($request->mycart == 2) {
            return view('frontEnd.layouts.ajax.myscart');
        } else {
            return view('frontEnd.layouts.ajax.cart');
        }
    }

    public function contact(Request $request)
    {
        return view('frontEnd.layouts.pages.contact');
    }

    public function page($slug)
    {
        $page = CreatePage::where('slug', $slug)->firstOrFail();
        return view('frontEnd.layouts.pages.page', compact('page'));
    }
    public function districts(Request $request)
    {
        $areas = District::where(['district' => $request->id])->pluck('area_name', 'id');
        return response()->json($areas);
    }
public function campaign($slug)
{
    $campaign_data = Campaign::where('slug', $slug)->with('images')->first();
    if (!$campaign_data) {
        return redirect()->back();
    }

    $productIds = json_decode($campaign_data->product_id);

    $products = Product::whereIn('id', $productIds)
        ->where('status', 1)
        ->with('image')
        ->get();

    // Clear old cart and add campaign products
    Cart::instance('shopping')->destroy();
    
    foreach ($products as $product) {
        Cart::instance('shopping')->add([
            'id' => $product->id,
            'name' => $product->name,
            'qty' => 1,
            'price' => $product->new_price,
            'options' => [
                'slug' => $product->slug,
                'image' => $product->image->image ?? '',
                'old_price' => $product->old_price,
                'purchase_price' => $product->purchase_price,
            ],
        ]);
    }

    // Single product for themes that use single object
    $product = $products->first();
    
    // Shipping Charge Logic
    $shippingcharge = ShippingCharge::where('status', 1)->get();
    $select_charge = ShippingCharge::where('status', 1)->first();
    $shipping_amt = $select_charge->amount ?? 0;

    // Check if any product in cart has free shipping enabled
    foreach (Cart::instance('shopping')->content() as $item) {
        $pro = Product::find($item->id);
        if ($pro && $pro->free_shipping == 1) {
            $shipping_amt = 0;
            break; // Ekta free shipping product thaklei purata free hobe
        }
    }
    
    // Session-e shipping amount save kora
    Session::put('shipping', $shipping_amt);

    $contact = \App\Models\Contact::first();

    // Return view based on theme_id
    if ($campaign_data->theme_id == 1) {
        return view('frontEnd.layouts.pages.campaign.campaign', compact('campaign_data', 'product', 'shippingcharge'));
    } elseif ($campaign_data->theme_id == 2) {
        return view('frontEnd.layouts.pages.campaign.campaign-two', compact('campaign_data', 'product', 'shippingcharge'));
    } elseif ($campaign_data->theme_id == 4) {
        return view('frontEnd.layouts.pages.campaign.campaign-four', compact('campaign_data', 'product', 'shippingcharge'));
    } elseif ($campaign_data->theme_id == 5) {
        return view('frontEnd.layouts.pages.campaign.campain-five', compact('campaign_data', 'product', 'shippingcharge'));
    } elseif ($campaign_data->theme_id == 6) {
        return view('frontEnd.layouts.pages.campaign.campaign-six', compact('campaign_data', 'product', 'shippingcharge'));
    } elseif ($campaign_data->theme_id == 7) {
        return view('frontEnd.layouts.pages.campaign.campaign-seven', compact('campaign_data', 'product', 'shippingcharge'));
    } 
    
    // ==========================================
    // ২. এই অংশটুকু নতুন যোগ করুন (Theme 8)
    // ==========================================
    elseif ($campaign_data->theme_id == 8) {
        return view('frontEnd.layouts.pages.campaign.campaign-eight', compact('campaign_data', 'product', 'shippingcharge', 'contact'));
    } 
    // ==========================================





    // ==========================================
    // ⭐ এই অংশটুকু নতুন যোগ করুন (Theme 9) ⭐
    // ==========================================
    elseif ($campaign_data->theme_id == 9) {
        return view('frontEnd.layouts.pages.campaign.campaign-nine', compact('campaign_data', 'product', 'shippingcharge', 'contact'));
    }
    // ==========================================

    elseif ($campaign_data->theme_id == 10) {
    // এখানে $products ভেরিয়েবলটি ইতিমধ্যে কন্ট্রোলারের উপরের অংশে ডিফাইন করা আছে
    return view('frontEnd.layouts.pages.campaign.campaign-ten', compact('campaign_data', 'products', 'product', 'shippingcharge', 'contact'));
}

  elseif ($campaign_data->theme_id == 11) {
    // এখানে $products ভেরিয়েবলটি ইতিমধ্যে কন্ট্রোলারের উপরের অংশে ডিফাইন করা আছে
    return view('frontEnd.layouts.pages.campaign.campaign-eleven', compact('campaign_data', 'products', 'product', 'shippingcharge', 'contact'));
}
    
    else {
        return view('frontEnd.layouts.pages.campaign.campain-three', compact('campaign_data', 'product', 'shippingcharge'));
    }
}
    public function payment_success(Request $request)
    {
        $order_id = $request->order_id;
        $shurjopay_service = new ShurjopayController();
        $json = $shurjopay_service->verify($order_id);
        $data = json_decode($json);

        if ($data[0]->sp_code != 1000) {
            Toastr::error('Your payment failed, try again', 'Oops!');
            if ($data[0]->value1 == 'customer_payment') {
                return redirect()->route('home');
            } else {
                return redirect()->route('home');
            }
        }

        if ($data[0]->value1 == 'customer_payment') {

            $customer = Customer::find(Auth::guard('customer')->user()->id);

            // order data save
            $order = new Order();
            $order->invoice_id = $data[0]->id;
            $order->amount = $data[0]->amount;
            $order->customer_id = Auth::guard('customer')->user()->id;
            $order->order_status = $data[0]->bank_status;
            $order->ip_address = request()->ip();
            $order->user_agent = request()->header('User-Agent');
            $order->save();

            // payment data save
            $payment = new Payment();
            $payment->order_id = $order->id;
            $payment->customer_id = Auth::guard('customer')->user()->id;
            $payment->payment_method = 'shurjopay';
            $payment->amount = $order->amount;
            $payment->trx_id = $data[0]->bank_trx_id;
            $payment->sender_number = $data[0]->phone_no;
            $payment->payment_status = 'paid';
            $payment->save();
            // order details data save
            foreach (Cart::instance('shopping')->content() as $cart) {
                $order_details = new OrderDetails();
                $order_details->order_id = $order->id;
                $order_details->product_id = $cart->id;
                $order_details->product_name = $cart->name;
                $order_details->purchase_price = $cart->options->purchase_price;
                $order_details->sale_price = $cart->price;
                $order_details->qty = $cart->qty;
                $order_details->save();
            }

            Cart::instance('shopping')->destroy();
            Toastr::error('Thanks, Your payment send successfully', 'Success!');
            return redirect()->route('home');
        }

        Toastr::error('Something wrong, please try agian', 'Error!');
        return redirect()->route('home');
    }
    public function payment_cancel(Request $request)
    {
        $order_id = $request->order_id;
        $shurjopay_service = new ShurjopayController();
        $json = $shurjopay_service->verify($order_id);
        $data = json_decode($json);

        Toastr::error('Your payment cancelled', 'Cancelled!');
        if ($data[0]->sp_code != 1000) {
            if ($data[0]->value1 == 'customer_payment') {
                return redirect()->route('home');
            } else {
                return redirect()->route('home');
            }
        }
    }

    public function offers()
    {
        return view('frontEnd.layouts.pages.offers');
    }

    public function invoice($id, $day)
    {

        $order = Order::where(['id' => $id])->with('orderdetails.product', 'payment', 'shipping', 'customer', 'user')->firstOrFail();
        $contact = Contact::first();
        return view('frontEnd.layouts.pages.invoice', compact('order', 'day', 'contact'));
    }
    public function orderissue(Request $request)
    {
        $order = new Orderissue;
        $order->invoice_id = $request->invoice_id;
        $order->customer_number = $request->customer_number;
        $order->descroption = $request->issueDescription;
        $order->category_issue = $request->issueCategory;
        $order->save();
        Toastr::error('Your Report is Submit', 'Plz Wait!');
        return redirect()->route('home');

    }

    public function paymentinvoice(Request $request)
    {
        // dd($request->all());
        $id = $request->order_id;
        if ($request->payment_method == 'bkash') {
            return redirect('/bkash/checkout-url/create?order_id=' . $id);
        }
        Toastr::error('Your Payment is Submit', 'Plz Wait!');
        return back();
    }


public function recruitmentPage($slug) {
    $page = RecruitmentCampaign::where('slug', $slug)->where('status', 1)->firstOrFail();
    
    // ডিস্ট্রিক্ট লিস্ট (ডুপ্লিকেট রিমুভ করা হয়েছে)
    $districts = District::select('id', 'district')->orderBy('district', 'asc')->get()->unique('district');
    
    // আপনার Thana টেবিল থেকে সরাসরি কলামগুলো নিয়ে আসা হচ্ছে
    $all_locations = Thana::select('id', 'city_id', 'name')->get(); 

    return view('frontEnd.layouts.pages.campaign.recruitment_landing', compact('page', 'districts', 'all_locations'));
}
    /**
     * রেজিস্ট্রেশন সম্পন্ন করার মেথড
     */
    public function recruitmentRegister(Request $request) {
        // ডাটা ভ্যালিডেশন
        $request->validate([
            'name' => 'required',
            'phone' => 'required|unique:resellers,phone',
            'password' => 'required|min:6',
            'district_id' => 'required',
            'thana_id' => 'required',
            'store_name' => 'required',
        ]);

        $reseller = new Reseller();
        $reseller->name = $request->name;
        $reseller->phone = $request->phone;
        $reseller->store_name = $request->store_name;
        $reseller->district_id = $request->district_id;
        $reseller->thana_id = $request->thana_id;
        $reseller->password = Hash::make($request->password);
        $reseller->status = 'pending'; // প্রাথমিকভাবে পেন্ডিং থাকবে

        /**
         * ডিলার এসাইন লজিক (রেফারেল কোড থাকলে)
         * ল্যান্ডিং পেজের হিডেন ইনপুট 'referral_code' থেকে আসা ডিলার আইডি সেভ করা হচ্ছে।
         */
        if($request->referral_code){
            $reseller->dealer_id = $request->referral_code;
        }

        $reseller->save();

        Toastr::success('রেজিস্ট্রেশন সফল হয়েছে!', 'ধন্যবাদ');
        return redirect()->route('reseller.login');
    }
    // FrontendController.php

// FrontendController.php

public function campaign_cart_add(Request $request)
{
    $product = Product::where(['id' => $request->id, 'status' => 1])->first();
    
    if ($product) {
        // ১. চেক করি প্রোডাক্টটি ইতিমধ্যে কার্টে আছে কি না
        $exists = false;
        foreach(Cart::instance('shopping')->content() as $item){
            if($item->id == $product->id){
                $exists = true;
                break;
            }
        }

        // ২. যদি না থাকে, শুধুমাত্র তখনই অ্যাড করব
        if(!$exists){
            Cart::instance('shopping')->add([
                'id' => $product->id,
                'name' => $product->name,
                'qty' => 1,
                'price' => $product->new_price,
                'options' => [
                    'slug' => $product->slug,
                    'image' => $product->image->image ?? '',
                    'old_price' => $product->old_price,
                    'purchase_price' => $product->purchase_price,
                ],
            ]);
        }
    }
    
    // শিপিং চার্জ ক্যালকুলেশন (আগের মতোই)
    $shipping_amt = 0;
    $select_charge = ShippingCharge::where('status', 1)->first();
    if($select_charge) {
        $shipping_amt = $select_charge->amount;
    }

    foreach (Cart::instance('shopping')->content() as $item) {
        $pro = Product::find($item->id);
        if ($pro && $pro->free_shipping == 1) {
            $shipping_amt = 0;
            break;
        }
    }
    Session::put('shipping', $shipping_amt);

    return view('frontEnd.layouts.ajax.cart');
}
// FrontendController.php

public function campaign_cart_remove_item(Request $request)
{
    $productId = $request->id;
    
    // কার্টে খুঁজুন এই প্রোডাক্ট আইডি আছে কিনা
    $cartContent = Cart::instance('shopping')->content();
    $rowId = null;
    
    foreach($cartContent as $item) {
        if($item->id == $productId) {
            $rowId = $item->rowId;
            break;
        }
    }
    
    if($rowId) {
        Cart::instance('shopping')->remove($rowId);
    }
    
    // শিপিং লজিক আবার (সেম আগের মতো)
    $shipping_amt = 0;
    $select_charge = ShippingCharge::where('status', 1)->first();
    if($select_charge) {
        $shipping_amt = $select_charge->amount;
    }
    foreach (Cart::instance('shopping')->content() as $item) {
        $pro = Product::find($item->id);
        if ($pro && $pro->free_shipping == 1) {
            $shipping_amt = 0;
            break;
        }
    }
    Session::put('shipping', $shipping_amt);

    return view('frontEnd.layouts.ajax.cart');
}


public function trackActivity(Request $request)
{
    // session_id যদি জাভাস্ক্রিপ্ট থেকে না আসে, তবে লারাভেলের নিজস্ব সেশন আইডি নিবে
    $sessionId = $request->session_id ?? session()->getId();

    \App\Models\VisitorActivity::updateOrCreate(
        [
            'session_id' => $sessionId,
            'url'        => $request->url,
            'date'       => date('Y-m-d'),
        ],
        [
            'ip_address'   => $request->ip(),
            'time_spent'   => $request->time_spent ?? 0, 
            'scroll_depth' => $request->scroll_depth ?? 0,
        ]
    );

    return response()->json(['status' => 'success']);
}

}
