<?php

namespace App\Http\Controllers\Wholesales;

use App\Http\Controllers\Controller;
use App\Models\Wholesaler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class WholesalersAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('wholesaler.auth.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::guard('wholesaler')->attempt($credentials, $request->remember)) {
            $wholesaler = Auth::guard('wholesaler')->user();
            
            if ($wholesaler->status !== 'active') {
                Auth::guard('wholesaler')->logout();
                return redirect()->back()->with('error', 'Your account is not active.');
            }

            return redirect()->intended(route('wholesaler.dashboard'));
        }

        return redirect()->back()->with('error', 'Invalid credentials.');
    }

    public function showRegistrationForm()
    {
        return view('wholesaler.auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:wholesalers',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'business_name' => 'required|string|max:255',
            'address' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $wholesaler = Wholesaler::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'business_name' => $request->business_name,
            'address' => $request->address,
            'status' => 'pending',
        ]);

        return redirect()->route('wholesaler.login')
            ->with('success', 'Registration successful! Please wait for admin approval.');
    }

    public function logout(Request $request)
    {
        Auth::guard('wholesaler')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('wholesaler.login');
    }
}
