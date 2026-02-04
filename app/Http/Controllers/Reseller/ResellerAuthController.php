<?php

namespace App\Http\Controllers\Reseller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\District;
use App\Models\Reseller;
use Illuminate\Support\Facades\Hash;
use Brian2694\Toastr\Facades\Toastr;

class ResellerAuthController extends Controller
{
    public function login()
    {
        return view('backEnd.reseller.auth.login');
    }

    public function loginCheck(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('reseller')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('reseller.dashboard');
        }

        return back()->with('error', 'Invalid credentials');
    }

    public function register(Request $request)
    {
        if ($request->has('ref')) {
            session(['dealer_ref_id' => $request->query('ref')]);
        }

        // ১. সব ডাটা নিয়ে আসছি (JSON হিসেবে জাভাস্ক্রিপ্টে ব্যবহারের জন্য)
        $all_locations = District::all(); 

        // ২. ড্রপডাউনের জন্য শুধু ইউনিক জেলার নামগুলো নিচ্ছি
        $districts = $all_locations->unique('district');
        
        // ৩. ভিউতে দুটি ভ্যারিয়েবলই পাঠাচ্ছি
        return view('backEnd.reseller.auth.register', compact('districts', 'all_locations'));
    }

    public function registerStore(Request $request)
    {
        $this->validate($request, [
            'name'          => 'required',
            'store_name'    => 'required',
            'email'         => 'required|email|unique:resellers',
            'phone'         => 'required|unique:resellers',
            'password'      => 'required|min:6',
            'district_id'   => 'required', 
            'thana_id'      => 'required', 
            'user_role'     => 'required|in:reseller,librarian', 
        ]);

        $reseller = new Reseller();
        $reseller->name        = $request->name;
        $reseller->email       = $request->email;
        $reseller->phone       = $request->phone;
        $reseller->store_name  = $request->store_name;
        $reseller->district_id = $request->district_id; 
        $reseller->thana_id    = $request->thana_id;    
        $reseller->user_role   = $request->user_role;   
        $reseller->password    = bcrypt($request->password);
        
        $reseller->dealer_id   = $request->dealer_id; 

        if (session()->has('referrer_reseller_id')) {
            $reseller->referrer_id = session()->get('referrer_reseller_id');
        }

        $reseller->status = '0'; 
        $reseller->save();

        Auth::guard('reseller')->login($reseller);

        Toastr::success('রেজিস্ট্রেশন সফল। আইডি সচল করতে আপনার প্রথম অর্ডারটি সম্পন্ন করুন।');
        return redirect()->route('reseller.products'); 
    }

    public function logout()
    {
        Auth::guard('reseller')->logout();
        return redirect()->route('reseller.login');
    }
}