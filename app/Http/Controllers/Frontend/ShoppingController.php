<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use DB;
use Cart;
use Toastr; // Toastr is kept for non-AJAX routes but removed from cart_single

class ShoppingController extends Controller
{

    // ====================== ADD TO CART (GET) ======================
    public function addTocartGet($id, Request $request)
    {
        $product = DB::table('products')->where('id', $id)->first();
        $productImage = DB::table('productimages')->where('product_id', $id)->first();
        $qty = 1;

        if (!$product) {
            Toastr::error('Product not found');
            return redirect()->back();
        }

        $price = $product->new_price ?? $product->old_price;

        Cart::instance('shopping')->add([
            'id'    => $product->id,
            'name'  => $product->name,
            'qty'   => $qty,
            'price' => $price,
            'options' => [
                'image'       => $productImage->image ?? null,
                'old_price'   => $product->old_price,
                'slug'        => $product->slug,
                'purchase_price' => $product->purchase_price,
            ]
        ]);

        Toastr::success('Product added to cart');

        return redirect()->route('customer.checkout');
    }


    // ====================== ADD TO CART (POST with size/color - Likely for non-AJAX forms) ======================
    public function cart_store(Request $request)
    {
        $product = Product::with('image')->find($request->id);

        if (!$product) {
            Toastr::error('Product not found');
            return redirect()->back();
        }

        $price = $product->new_price ?? $product->old_price;

        Cart::instance('shopping')->add([
            'id'    => $product->id,
            'name'  => $product->name,
            'qty'   => $request->qty ?? 1,
            'price' => $price,
            'options' => [
                'slug'            => $product->slug,
                'image'           => optional($product->image)->image,
                'old_price'       => $product->old_price,
                'purchase_price'  => $product->purchase_price,
                'product_size'    => $request->product_size,
                'product_color'   => $request->product_color,
                'pro_unit'        => $request->pro_unit,
            ]
        ]);

        Toastr::success('Product added to cart');

        return redirect()->route('customer.checkout');
    }


    // ====================== SINGLE PRODUCT ADD (Updated for AJAX JSON Response) ======================
   public function cart_single(Request $request)
{
    try {
        $product = Product::with('image')->find($request->id);

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found.'], 404);
        }

        Cart::instance('shopping')->add([
            'id'    => $product->id,
            'name'  => $product->name,
            'qty'   => $request->qty ?? 1,
            'price' => $product->new_price,
            'options' => [
                'slug'            => $product->slug,
                'image'           => optional($product->image)->image,
                'old_price'       => $product->new_price,
                'purchase_price'  => $product->purchase_price,
                'product_size'    => $request->product_size,
                'product_color'   => $request->product_color,
                'pro_unit'        => $request->pro_unit,
            ]
        ]);

        // 🔥 FIX: সেশন ম্যানুয়ালি সেভ করা হচ্ছে যাতে রেসপন্স যাওয়ার আগেই ডাটা স্টোর হয়
        session()->save(); 

        // Debug Log (সমস্যা চেক করার জন্য)
        \Log::info('Cart Added & Saved. Current Count: ' . Cart::instance('shopping')->count());

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully!'
        ], 200);

    } catch (\Exception $e) {
        \Log::error("Cart Single Error: " . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Error.'], 500);
    }
}


    // ====================== REMOVE ======================
  public function cart_remove(Request $request)
    {
        try {
            // চেষ্টা করবে রিমুভ করতে
            Cart::instance('shopping')->update($request->id, 0);
        } catch (\Exception $e) {
            // যদি আইটেম খুঁজে না পায় (মানে আগেই রিমুভ হয়ে গেছে),
            // তাহলে চুপচাপ থাকবে, কোনো এরর দিবে না।
        }

        $data = Cart::instance('shopping')->content();
        return view('frontEnd.layouts.ajax.cart', compact('data'));
    }


    // ====================== CART INCREMENT ======================
    public function cart_increment(Request $request)
    {
        $item = Cart::instance('shopping')->get($request->id);
        $qty = $item->qty + 1;

        Cart::instance('shopping')->update($request->id, $qty);
        $data = Cart::instance('shopping')->content();

        return $request->mycart == 2
            ? view('frontEnd.layouts.ajax.myscart', compact('data'))
            : view('frontEnd.layouts.ajax.cart', compact('data'));
    }


    // ====================== CART DECREMENT ======================
    public function cart_decrement(Request $request)
    {
        $item = Cart::instance('shopping')->get($request->id);
        $qty = $item->qty - 1;

        Cart::instance('shopping')->update($request->id, $qty);
        $data = Cart::instance('shopping')->content();

        return $request->mycart == 2
            ? view('frontEnd.layouts.ajax.myscart', compact('data'))
            : view('frontEnd.layouts.ajax.cart', compact('data'));
    }


    // ====================== COUNT ======================
    public function cart_count()
    {
        $data = Cart::instance('shopping')->count();
        return view('frontEnd.layouts.ajax.cart_count', compact('data'));
    }


    public function mobilecart_qty()
    {
        $data = Cart::instance('shopping')->count();
        return view('frontEnd.layouts.ajax.mobilecart_qty', compact('data'));
    }

}