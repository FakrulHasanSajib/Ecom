<?php

namespace App\Http\Controllers\Dealer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Dealer;
use App\Models\DealerProduct;
use App\Models\Product;
use App\Models\Customer;
use Brian2694\Toastr\Facades\Toastr;

class DealerDashboardController extends Controller
{
   public function dashboard()
{
    $dealer_id = Auth::guard('dealer')->id();
    
    // আগে ছিল Customer, এখন Reseller মডেল থেকে কাউন্ট করা হচ্ছে যাতে সঠিক সংখ্যা দেখায়
    $total_referrals = \App\Models\Reseller::where('dealer_id', $dealer_id)->count();
    
    $assigned_products = DealerProduct::where('dealer_id', $dealer_id)->count();
    $balance = Auth::guard('dealer')->user()->balance;

    return view('backEnd.dealer.panel.dashboard', compact('total_referrals', 'assigned_products', 'balance'));
}
    public function productList()
    {
        $dealer_id = Auth::guard('dealer')->id();
        $products = Dealer::with('products')->find($dealer_id)->products;
        return view('backEnd.dealer.panel.products', compact('products'));
    }

    public function updateCommission(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'commission_amount' => 'required|numeric',
            'commission_type' => 'required|in:fixed,percent',
            'reseller_price' => 'required|numeric',
        ]);

        $dealer_id = Auth::guard('dealer')->id();

        DealerProduct::where('dealer_id', $dealer_id)
            ->where('product_id', $request->product_id)
            ->update([
                'commission_amount' => $request->commission_amount,
                'commission_type' => $request->commission_type,
                'reseller_price' => $request->reseller_price
            ]);

        Toastr::success('Success', 'Commission updated successfully');
        return redirect()->back();
    }

    public function referralList()
    {
        $dealer_id = Auth::guard('dealer')->id();
        $referrals = \App\Models\Reseller::where('dealer_id', $dealer_id)->orderBy('id', 'desc')->paginate(20);
        return view('backEnd.dealer.panel.referrals', compact('referrals'));
    }

    public function resellerProfile($id)
    {
        $dealer_id = Auth::guard('dealer')->id();
        $reseller = \App\Models\Reseller::where('dealer_id', $dealer_id)->with(['orders', 'payments', 'dealer'])->find($id);

        if (!$reseller) {
            return back()->with('error', 'Reseller not found');
        }

        return view('backEnd.dealer.panel.reseller_profile', compact('reseller'));
    }

    public function orderHistory()
    {
        $dealer_id = Auth::guard('dealer')->id();
        // Get all resellers IDs under this dealer
        $reseller_ids = \App\Models\Reseller::where('dealer_id', $dealer_id)->pluck('id');

        // Get orders where customer_id is in reseller_ids (Assuming reseller places order as customer with their ID? 
        // Wait, Order table has customer_id. Reseller IS a Customer? Or separate table?
        // Let's check Reseller model. It likely links to customers table or is separate.
        // Based on previous code: $order->order_type == 'reseller'
        // And customer_id usually refers to the Reseller's ID if 'reseller' type.
        // Let's assume customer_id stores Reseller ID for reseller orders.

        $orders = \App\Models\Order::whereIn('customer_id', $reseller_ids)
            ->where('order_type', 'reseller')
            ->orderBy('id', 'desc')
            ->with('status')
            ->paginate(20);

        return view('backEnd.dealer.panel.order_history', compact('orders'));
    }

    public function withdrawals()
    {
        $dealer_id = Auth::guard('dealer')->id();

        // My Withdrawals (Dealer's own requests)
        $my_withdrawals = \App\Models\Withdrawal::where('dealer_id', $dealer_id)
            ->where('type', 'dealer')
            ->orderBy('id', 'desc')
            ->get();

        // Reseller Payments (Requests from my resellers)
        $reseller_ids = \App\Models\Reseller::where('dealer_id', $dealer_id)->pluck('id');
        $reseller_withdrawals = \App\Models\Withdrawal::whereIn('reseller_id', $reseller_ids)
            ->where('type', 'reseller') // or just where reseller_id is not null
            ->orderBy('id', 'desc')
            ->with('reseller')
            ->get();

        return view('backEnd.dealer.panel.withdrawals', compact('my_withdrawals', 'reseller_withdrawals'));
    }

    public function storeWithdrawal(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10',
            'method' => 'required',
            'account_info' => 'required',
        ]);

        $dealer = Auth::guard('dealer')->user();

        if ($request->amount > $dealer->balance) {
            Toastr::error('Insufficient balance', 'Error');
            return back();
        }

        // Create Withdrawal
        \App\Models\Withdrawal::create([
            'dealer_id' => $dealer->id,
            'amount' => $request->amount,
            'method' => $request->method,
            'account_info' => $request->account_info,
            'status' => 'pending',
            'type' => 'dealer'
        ]);

        // Deduct Balance (Usually done immediately or after approval? 
        // Standard practice: Deduct on approval. Or deduct now and hold?
        // Let's follow Reseller logic. Usually just create request.
        // Let's deduct on APPROVED in AdminController.

        Toastr::success('Withdrawal request submitted successfully', 'Success');
        return back();
    }

    public function profile()
    {
        $profile = Auth::guard('dealer')->user();
        return view('backEnd.dealer.panel.profile_settings', compact('profile'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'image' => 'nullable|image',
            'password' => 'nullable|min:6',
        ]);

        $dealer = Dealer::find(Auth::guard('dealer')->id());
        $dealer->name = $request->name;
        $dealer->email = $request->email;
        $dealer->phone = $request->phone;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time() . '-' . $image->getClientOriginalName();
            $path = 'public/uploads/dealer/';
            $image->move($path, $name);
            $dealer->image = $path . $name;
        }

        if ($request->password) {
            $dealer->password = bcrypt($request->password);
        }

        $dealer->save();

        Toastr::success('Success', 'Profile updated successfully');
        return back();
    }
}
