<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Productimage;
use App\Models\Productprice;
use App\Models\Productcolor;
use App\Models\Productsize;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Childcategory;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Size;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use Toastr;
use File;
use Str;
use Image;
use DB;
use Auth;

class ProductController extends Controller
{
    public function getSubcategory(Request $request)
    {
        $subcategory = DB::table("subcategories")
        ->where("category_id", $request->category_id)
        ->pluck('subcategoryName', 'id');
        return response()->json($subcategory);
    }
    public function getChildcategory(Request $request)
    {
        $childcategory = DB::table("childcategories")
        ->where("subcategory_id", $request->subcategory_id)
        ->pluck('childcategoryName', 'id');
        return response()->json($childcategory);
    }
    
    
    function __construct()
    {
         $this->middleware('permission:product-list|product-create|product-edit|product-delete', ['only' => ['index','show']]);
         $this->middleware('permission:product-create', ['only' => ['create','store']]);
         $this->middleware('permission:product-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:product-delete', ['only' => ['destroy']]);
    }
    
    
public function index(Request $request)
{
    $keyword = $request->keyword;
    $category_id = $request->category;

    // প্রোডাক্ট কুয়েরি শুরু
    $query = Product::orderBy('id', 'DESC')->with('image', 'category');

    // সার্চ লজিক
    if ($keyword) {
        $query->where(function($q) use ($keyword) {
            $q->where('name', 'LIKE', '%' . $keyword . '%')
              ->orWhere('product_code', 'LIKE', '%' . $keyword . '%');
        });
    }

    // ক্যাটাগরি ফিল্টার
    if ($category_id) {
        $query->where('category_id', $category_id);
    }

    // রেজাল্ট নিয়ে আসা
    $data = $query->paginate(50);

    // ড্রপডাউনের জন্য ক্যাটাগরি
    $categories = Category::where('status', 1)->orderBy('name', 'ASC')->get();
    
    // কিউয়ার্ডটি ইনপুট বক্সে ধরে রাখার জন্য ডাটা পাঠানো
    return view('backEnd.product.index', compact('data', 'categories', 'keyword'));
}
    public function authorpro(Request $request)
    {
        $id = Auth::user()->id;
        
            $data = Product::orderBy('id','DESC')->with('image','category')->where('author_id', $id)->paginate(20);
        return view('author.productshow',compact('data'));
    }
    public function create()
    {
        $categories = Category::where('parent_id','=','0')->where('status',1)->select('id','name','status')->with('childrenCategories')->get();
        $brands = Brand::where('status','1')->select('id','name','status')->get();
        $colors = Color::where('status','1')->get();
        $sizes = Size::where('status','1')->get();
        return view('backEnd.product.create',compact('categories','brands','colors','sizes'));
    }
public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'new_price' => 'required',
            'purchase_price' => 'required',
            'stock' => 'required',
            'category_id' => 'required',
            'description' => 'required',
        ]);
        
        $last_id = Product::orderBy('id', 'desc')->select('id')->first();
        $last_id = $last_id ? $last_id->id + 1 : 1;

        $input = $request->except([
            'image', 'files', 'proSize', 'proColor', 
            'shipping_area', 'charge_in', 'charge_out', 
            'charge_inside', 'charge_outside',
            'gallery_images', 'banner', 'charge',
            'ঢাকার_ভিতরে', 'ঢাকার_বাহিরে'
        ]);

        $input['slug'] = strtolower(preg_replace('/[\/\s]+/', '-', $request->name.'-'.$last_id));
        $input['short_dec'] = $request->short_dec;

        // ✅ ফিক্স: ট্যাগের সাইজ কমিয়ে ১০০ করা হলো যাতে ডাটাবেস এরর না দেয়
        if ($request->filled('tag')) {
            $input['tag'] = substr($request->tag, 0, 100); 
        }

        // টগল বাটন ফিক্স
        $input['status'] = $request->has('status') ? 1 : 0;
        $input['topsale'] = $request->has('topsale') ? 1 : 0;
        $input['feature_product'] = $request->has('feature_product') ? 1 : 0;
        $input['reselling_sell'] = $request->has('reselling_sell') ? 1 : 0;
        $input['continue_sell'] = $request->has('continue_sell') ? 1 : 0;
        $input['hot_deal'] = $request->has('hot_deal') ? 1 : 0;
        $input['free_shipping'] = $request->has('free_shipping') ? 1 : 0;

        // শিপিং লজিক
        if ($input['free_shipping'] == 1) {
            $input['shipping_charge'] = 0;
        } else {
            if ($request->shipping_area == 'outside') {
                $input['shipping_charge'] = $request->charge_out ?? $request->charge_outside ?? 150;
            } else {
                $input['shipping_charge'] = $request->charge_in ?? $request->charge_inside ?? 80;
            }
        }

        // ব্যানার ইমেজ
        if ($request->filled('banner')) {
            $input['banner'] = $request->banner;
        } elseif ($request->hasFile('banner')) {
            $banner = $request->file('banner');
            $name = time().'-'.$banner->getClientOriginalName();
            $name = strtolower(preg_replace('/\s+/', '-', $name));
            $uploadPath = 'public/frontEnd/banner/';
            $banner->move($uploadPath, $name);
            $input['banner'] = $uploadPath.$name;
        }

        if ($request->has('testimonials')) {
    // array_values ব্যবহার করা হয়েছে যাতে JSON ডাটা সবসময় অ্যারে হিসেবে থাকে
    $input['testimonials'] = json_encode(array_values($request->testimonials));
} else {
    $input['testimonials'] = null;
}

        $save_data = Product::create($input);

        // রিলেশন সেভ
        $save_data->sizes()->attach($request->proSize);
        $save_data->colors()->attach($request->proColor);
        
        // গ্যালারি ইমেজ
        if ($request->has('gallery_images') && is_array($request->gallery_images)) {
            foreach ($request->gallery_images as $imageUrl) {
                if ($imageUrl) {
                    $pimage = new Productimage();
                    $pimage->product_id = $save_data->id;
                    $pimage->image = $imageUrl;
                    $pimage->save();
                }
            }
        }

        $images = $request->file('image');
        if ($images) {
            foreach ($images as $key => $image) {
                $name = time().'-'.$image->getClientOriginalName();
                $name = strtolower(preg_replace('/\s+/', '-', $name));
                $uploadPath = 'public/uploads/product/';
                $image->move($uploadPath, $name);
                $imageUrl = $uploadPath.$name;

                $pimage = new Productimage();
                $pimage->product_id = $save_data->id;
                $pimage->image = $imageUrl;
                $pimage->save();
            }
        }

        Toastr::success('Success', 'Data insert successfully');
        return redirect()->route('products.index');
    }



    public function edit($id)
    {
        $edit_data = Product::with('images')->find($id);
        $categories = Category::where('parent_id','=','0')->where('status',1)->select('id','name','status')->get();
        
        $categoryId = Product::find($id)->category_id;
        $subcategoryId = Product::find($id)->subcategory_id;
        
        $subcategory = Subcategory::where('category_id', '=', $categoryId)->select('id','subcategoryName','status')->get();
        $childcategory = Childcategory::where('subcategory_id', '=', $subcategoryId)->select('id', 'childcategoryName', 'status')->get();
        
        $brands = Brand::where('status','1')->select('id','name','status')->get();
        $totalsizes = Size::where('status',1)->get();
        $totalcolors = Color::where('status',1)->get();
        
        $selectcolors = Productcolor::where('product_id',$id)->get();
        $selectsizes = Productsize::where('product_id',$id)->get();
        
        return view('backEnd.product.edit',compact('edit_data','categories', 'subcategory', 'childcategory', 'brands', 'selectcolors', 'selectsizes','totalsizes', 'totalcolors'));
    }

    // ✅ আপনার মিসিং Price Edit মেথডগুলো
    public function price_edit()
    {
        $products = DB::table('products')->select('id','name','status','old_price','new_price','stock')->where('status',1)->get();;
        return view('backEnd.product.price_edit',compact('products'));
    }

    public function price_update(Request $request)
    {
        $ids = $request->ids;
        $oldPrices = $request->old_price;
        $newPrices = $request->new_price;
        $stocks = $request->stock;
        foreach ($ids as $key => $id) {
            $product = Product::select('id','name','status','old_price','new_price','stock')->find($id);
            if ($product) {
                $product->update([
                    'old_price' => $oldPrices[$key],
                    'new_price' => $newPrices[$key],
                    'stock' => $stocks[$key],
                ]);
            }
        }
        Toastr::success('Success','Price update successfully');
        return redirect()->back();
    }


public function update(Request $request)
{
    $this->validate($request, [
        'name' => 'required',
        'category_id' => 'required',
        'new_price' => 'required',
        'purchase_price' => 'required',
        'stock' => 'required',
        'description' => 'required',
    ]);
        
    $update_data = Product::find($request->id);

    // ১. শুরুতে ইনপুট থেকে ডাটা ফিল্টার করা
    $input = $request->except([
        'banner', 'gallery', 'proSize', 'proColor', 
        'shipping_area', 'charge_in', 'charge_out', 
        'charge_inside', 'charge_outside',
        'removed_images', 'charge', 'gallery_images', 'image',
        'testimonials', 'ঢাকার_ভিতরে', 'ঢাকার_বাহিরে'
    ]);

    // ২. টেস্টিমোনিয়াল লজিক (সঠিকভাবে সাজানো)
    if ($request->has('testimonials')) {
        $input['testimonials'] = json_encode(array_values($request->testimonials));
    } else {
        $input['testimonials'] = null;
    }

    // ৩. স্লাগ এবং ট্যাগ প্রসেসিং
    $input['slug'] = Str::slug($request->name);
    $input['short_dec'] = $request->short_dec;

    if ($request->filled('tag')) {
        $input['tag'] = substr($request->tag, 0, 100);
    }
    
    // ৪. টগল বাটন ও স্ট্যাটাস ফিক্স (on/off এর বদলে 1/0)
    $input['status'] = $request->has('status') ? 1 : 0;
    $input['topsale'] = $request->has('topsale') ? 1 : 0;
    $input['feature_product'] = $request->has('feature_product') ? 1 : 0;
    $input['reselling_sell'] = $request->has('reselling_sell') ? 1 : 0;
    $input['continue_sell'] = $request->has('continue_sell') ? 1 : 0;
    $input['hot_deal'] = $request->has('hot_deal') ? 1 : 0;
    $input['free_shipping'] = $request->has('free_shipping') ? 1 : 0;

    // ৫. শিপিং চার্জ ক্যালকুলেশন
    if ($input['free_shipping'] == 1) {
        $input['shipping_charge'] = 0;
    } else {
        if ($request->shipping_area == 'outside') {
            $input['shipping_charge'] = $request->charge_out ?? $request->charge_outside ?? 150;
        } else {
            $input['shipping_charge'] = $request->charge_in ?? $request->charge_inside ?? 80;
        }
    }

    // ৬. ব্যানার ইমেজ হ্যান্ডলিং
    if ($request->filled('banner')) {
         $input['banner'] = $request->banner;
    }
    
    $thumbnail = $request->file('banner'); 
    if($thumbnail && $thumbnail->isValid()) {
        if($update_data->banner && file_exists(public_path($update_data->banner))) {
            unlink(public_path($update_data->banner));
        }
        $name = time().'-banner-'.$thumbnail->getClientOriginalName();
        $name = strtolower(preg_replace('/\s+/', '-', $name));
        $uploadPath = 'public/frontEnd/banner/';
        if (!file_exists(public_path($uploadPath))) mkdir(public_path($uploadPath), 0777, true);
        $thumbnail->move($uploadPath, $name);
        $input['banner'] = $uploadPath.$name;
    }
    
    // ৭. সব প্রসেসিং শেষ করে এখন একবার আপডেট কল হবে
    $update_data->update($input);
    
    // ৮. গ্যালারি ইমেজ প্রসেসিং (কোড ঠিক আছে)
 
    
  $submittedImages = $request->input('gallery_images', []);

    // A. ডিলিট লজিক: ডাটাবেসে আছে কিন্তু ফর্মে নেই -> ডিলিট করুন
    $existingDbImages = Productimage::where('product_id', $update_data->id)->get();
    
    foreach ($existingDbImages as $dbImg) {
        if (!in_array($dbImg->image, $submittedImages)) {
            $dbImg->delete(); // লিস্টে না থাকলে ডিলিট
        }
    }

    // B. ইনসার্ট লজিক: ফর্মে আছে কিন্তু ডাটাবেসে নেই -> সেভ করুন
    if (!empty($submittedImages)) {
        foreach ($submittedImages as $imageUrl) {
            if ($imageUrl) {
                 // চেক করুন এই ইমেজটি অলরেডি ডাটাবেসে আছে কিনা
                 $exists = Productimage::where('product_id', $update_data->id)
                                       ->where('image', $imageUrl)
                                       ->exists();
                 
                 // যদি ডাটাবেসে না থাকে (মানে নতুন অ্যাড করেছেন), তাহলে সেভ করুন
                 if(!$exists) {
                    $pimage = new Productimage();
                    $pimage->product_id = $update_data->id;
                    $pimage->image = $imageUrl;
                    $pimage->save();
                 }
            }
        }
    }
    
    $galleryFiles = $request->file('gallery_images'); 
    if($galleryFiles) {
        foreach($galleryFiles as $index => $image) {
            if($image && $image->isValid()) {
                $name = time().'-gallery-'.$index.'-'.$image->getClientOriginalName();
                $name = strtolower(preg_replace('/\s+/', '-', $name));
                $uploadPath = 'public/uploads/product/';
                
                if (!file_exists(public_path($uploadPath))) {
                    mkdir(public_path($uploadPath), 0777, true);
                }
                
                $image->move($uploadPath, $name);
                $imageUrl = $uploadPath.$name;
                
                $pimage = new Productimage();
                $pimage->product_id = $update_data->id;
                $pimage->image = $imageUrl;
                $pimage->save();
            }
        }
    }













    // ৯. রিলেশন আপডেট
    $update_data->sizes()->sync($request->proSize ?? []);
    $update_data->colors()->sync($request->proColor ?? []);
    
    Toastr::success('Success','Product updated successfully');
    return redirect()->route('products.index');
}
 public function duplicate(Request $request)
    {
      try {
        // Find the original product
        $originalProduct = Product::with(['sizes', 'colors', 'images'])->find($request->order_id);
        
        if (!$originalProduct) {
            Toastr::error('Error', 'Product not found');
            return redirect()->route('products.index');
        }
        
        // Get the last product ID for slug generation
        $lastProduct = Product::orderBy('id', 'desc')->select('id')->first();
        $nextId = $lastProduct ? $lastProduct->id + 1 : 1;
        
        // Create new product instance
        $newProduct = new Product();
        
        // Copy all product attributes
        $newProduct->name = $originalProduct->name;
        $newProduct->category_id = $originalProduct->category_id;
        $newProduct->subcategory_id = $originalProduct->subcategory_id;
        $newProduct->childcategory_id = $originalProduct->childcategory_id;
        $newProduct->brand_id = $originalProduct->brand_id;
        $newProduct->purchase_price = $originalProduct->purchase_price;
        $newProduct->old_price = $originalProduct->old_price;
        $newProduct->reseller_price = $originalProduct->reseller_price;
        $newProduct->new_price = $originalProduct->new_price;
        $newProduct->offer_price = $originalProduct->offer_price;
        $newProduct->stock = $originalProduct->stock;
        $newProduct->pro_unit = $originalProduct->pro_unit;
        $newProduct->description = $originalProduct->description;
        $newProduct->pro_video = $originalProduct->pro_video;
        $newProduct->meta_title = $originalProduct->meta_title;
        $newProduct->meta_description = $originalProduct->meta_description;
        $newProduct->tag = $originalProduct->tag;
        $newProduct->short_dec = $originalProduct->short_dec;
        $newProduct->banner = $originalProduct->banner;
        $newProduct->product_code = $originalProduct->product_code . '_COPY_' . time(); // Make unique
        
        // Handle boolean fields
        $newProduct->status = $originalProduct->status ? 1 : 0;
        $newProduct->topsale = $originalProduct->topsale ? 1 : 0;
        $newProduct->feature_product = $originalProduct->feature_product ? 1 : 0;
        $newProduct->reselling_sell = $originalProduct->reselling_sell ? 1 : 0;
        $newProduct->continue_sell = $originalProduct->continue_sell ? 1 : 0;
        $newProduct->free_shipping = $originalProduct->free_shipping ? 1 : 0;
        $newProduct->hot_deal = $originalProduct->hot_deal ? 1 : 0;
        
        $newProduct->shipping_charge = $originalProduct->shipping_charge;
        
        // Generate unique slug
        $newProduct->slug = strtolower(preg_replace('/[\/\s]+/', '-', $originalProduct->name . '-copy-' . $nextId));
        
        // Save the new product
        $newProduct->save();
        
        // Duplicate sizes relationship
        if ($originalProduct->sizes->count() > 0) {
            $sizeIds = $originalProduct->sizes->pluck('id')->toArray();
            $newProduct->sizes()->sync($sizeIds);
        }
        
        // Duplicate colors relationship
        if ($originalProduct->colors->count() > 0) {
            $colorIds = $originalProduct->colors->pluck('id')->toArray();
            $newProduct->colors()->sync($colorIds);
        }
        
        // Duplicate product images
        if ($originalProduct->images->count() > 0) {
            foreach ($originalProduct->images as $imagecopy) {
                $newImage = new Productimage();
                $newImage->product_id = $newProduct->id;
                $newImage->image = $imagecopy->image;
                $newImage->save();
            }
        }
        
        Toastr::success('Success', 'Product duplicated successfully');
        return redirect()->route('products.index');
        
    } catch (\Exception $e) {
        Toastr::error('Error', 'Failed to duplicate product: ' . $e->getMessage());
        return redirect()->route('products.index');
    }
    }
    public function inactive(Request $request)
    {
        $inactive = Product::find($request->hidden_id);
        $inactive->status = 0;
        $inactive->save();
        Toastr::success('Success','Data inactive successfully');
        return redirect()->back();
    }
    public function active(Request $request)
    {
        $active = Product::find($request->hidden_id);
        $active->status = 1;
        $active->save();
        Toastr::success('Success','Data active successfully');
        return redirect()->back();
    }
    public function destroy(Request $request)
    {
        $delete_data = Product::find($request->hidden_id);
        $delete_data->delete();
        Toastr::success('Success','Data delete successfully');
        return redirect()->back();
    }
    public function imgdestroy(Request $request)
    { 
        $delete_data = Productimage::find($request->id);
        File::delete($delete_data->image);
        $delete_data->delete();
        Toastr::success('Success','Data delete successfully');
        return redirect()->back();
    } 
    public function pricedestroy(Request $request)
    { 
        $delete_data = Productprice::find($request->id);
        $delete_data->delete();
        Toastr::success('Success','Product price delete successfully');
        return redirect()->back();
    }
    public function update_deals(Request $request){
        $products = Product::whereIn('id', $request->input('product_ids'))->update(['topsale' => $request->status]);
        return response()->json(['status'=>'success','message'=>'Hot deals product status change']);
    }
    public function update_feature(Request $request){
        $products = Product::whereIn('id', $request->input('product_ids'))->update(['feature_product' => $request->status]);
        return response()->json(['status'=>'success','message'=>'Feature product status change']);
    }
    public function update_status(Request $request){
        $products = Product::whereIn('id', $request->input('product_ids'))->update(['status' => $request->status]);
        return response()->json(['status'=>'success','message'=>'Product status change successfully']);
    }
public function generateSeo(Request $request)
    {
        // ১. ভ্যালিডেশন
        if (!$request->name) {
            return response()->json(['status' => 'error', 'message' => 'Product Name is required']);
        }

        $productName = $request->name;
        $description = strip_tags($request->description);
        
        $apiKey = env('GEMINI_API_KEY');
        if (empty($apiKey)) {
            return response()->json(['status' => 'error', 'message' => 'API Key not found in .env file!']);
        }

        // ২. প্রম্পট
        $prompt = "Generate SEO data for product: '{$productName}'. Description: '{$description}'. 
        Return valid JSON with keys: meta_title, meta_description, tags. 
        No markdown.";

        try {
            // ৩. API কল (মডেল পরিবর্তন করা হয়েছে: gemini-flash-latest)
            // এটি ফ্রি টিয়ারে বেশি লিমিট দেয় এবং স্ট্যাবল
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key={$apiKey}", [
                'contents' => [
                    ['parts' => [['text' => $prompt]]]
                ],
                'safetySettings' => [
                    ['category' => 'HARM_CATEGORY_HARASSMENT', 'threshold' => 'BLOCK_NONE'],
                    ['category' => 'HARM_CATEGORY_HATE_SPEECH', 'threshold' => 'BLOCK_NONE'],
                    ['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT', 'threshold' => 'BLOCK_NONE'],
                    ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT', 'threshold' => 'BLOCK_NONE']
                ]
            ]);

            $result = $response->json();

            // ৪. এরর চেকিং
            if ($response->failed()) {
                $errorMessage = $result['error']['message'] ?? 'Unknown API Error';
                $errorCode = $result['error']['code'] ?? $response->status();
                
                // কোটা বা লিমিট এরর হলে ইউজারকে জানানো
                if ($errorCode == 429) {
                    return response()->json([
                        'status' => 'error', 
                        'message' => "Daily Quota Exceeded. Please try again later or verify billing."
                    ]);
                }

                Log::error("Gemini API Error ($errorCode): " . $errorMessage);
                
                return response()->json([
                    'status' => 'error', 
                    'message' => "Google API Error ($errorCode): " . $errorMessage
                ]);
            }

            // ৫. সফল রেসপন্স হ্যান্ডলিং
            if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                $generatedText = $result['candidates'][0]['content']['parts'][0]['text'];
                
                // JSON ক্লিন করা
                if (preg_match('/\{.*\}/s', $generatedText, $matches)) {
                    $jsonString = $matches[0];
                } else {
                    $jsonString = $generatedText;
                }

                $seoData = json_decode($jsonString, true);

                if (json_last_error() === JSON_ERROR_NONE && $seoData) {
                    return response()->json(['status' => 'success', 'data' => $seoData]);
                } else {
                    return response()->json([
                        'status' => 'error', 
                        'message' => 'AI sent text but not JSON. Try again.'
                    ]);
                }
            } else {
                $blockReason = $result['promptFeedback']['blockReason'] ?? 'UNKNOWN_BLOCK';
                return response()->json([
                    'status' => 'error', 
                    'message' => 'Content Blocked by Google. Reason: ' . $blockReason
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Gemini Exception: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'System Error: ' . $e->getMessage()]);
        }
    }
}