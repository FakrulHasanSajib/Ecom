<?php

namespace App\Http\Controllers\Wholesaler;

use App\Http\Controllers\Controller;
use App\Models\Wholesaler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        return view('wholesaler.dashboard', compact('wholesaler'));
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
            'new_password' => 'required|min:8',
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
}