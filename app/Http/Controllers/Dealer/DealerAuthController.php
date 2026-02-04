<?php

namespace App\Http\Controllers\Dealer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Dealer;
use Toastr;

class DealerAuthController extends Controller
{
    public function login()
    {
        return view('backEnd.dealer.auth.login');
    }

    public function loginCheck(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
            'status' => 'active',
        ];

        if (Auth::guard('dealer')->attempt($credentials)) {
            Toastr::success('Success', 'Login successfully');
            return redirect()->route('dealer.dashboard');
        } else {
            Toastr::error('Error', 'Invalid credentials or inactive account');
            return redirect()->back();
        }
    }

    public function logout()
    {
        Auth::guard('dealer')->logout();
        Toastr::success('Success', 'Logout successfully');
        return redirect()->route('dealer.login');
    }
}
