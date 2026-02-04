<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\PaymentGateway;
use App\Models\SmsGateway;
use App\Models\Courierapi;
use Toastr;
use File;
use Str;
use Image;
use DB;

class ApiIntegrationController extends Controller
{
    
     
 public function pay_manage()
{
    // 'firstOrNew' চেক করে ডাটা আছে কিনা। না থাকলে নতুন ইন্সট্যান্স তৈরি করে, তাই null এরর আসবে না।
    $bkash = PaymentGateway::firstOrNew(['type' => 'bkash']);
    $shurjopay = PaymentGateway::firstOrNew(['type' => 'shurjopay']);
    $paystation = PaymentGateway::firstOrNew(['type' => 'paystation']);

    return view('backEnd.apiintegration.pay_manage', compact('bkash', 'shurjopay', 'paystation'));
}
    
    public function pay_update(Request $request)
    {
      
        $update_data = PaymentGateway::find($request->id);
        $input = $request->all();
        $input['status'] = $request->status?1:0;
        $update_data->update($input);
        
        Toastr::success('Success','Data update successfully');
        return redirect()->back();
    }
    
    public function sms_manage ()
    {  
        $sms = SmsGateway::first();
        $smsBalancse = $this->balanceCheck();
        return view('backEnd.apiintegration.sms_manage',compact('sms','smsBalancse'));
    }
    
    public function sms_update(Request $request)
    {
      
        $update_data = SmsGateway::find($request->id);
        $input = $request->all();
        $input['status'] = $request->status?1:0;
        $input['order'] = $request->order?1:0;
        $input['forget_pass'] = $request->forget_pass?1:0;
        $input['password_g'] = $request->password_g?1:0;
        $update_data->update($input);
        
        Toastr::success('Success','Data update successfully');
        return redirect()->back();
    }
    
  public function courier_manage()
{
    // firstOrNew ব্যবহার করলে ডাটা না থাকলে নতুন অবজেক্ট তৈরি হবে, তাই আর error দিবে না
    $steadfast = Courierapi::firstOrNew(['type' => 'steadfast']);
    $pathao = Courierapi::firstOrNew(['type' => 'pathao']);

    return view('backEnd.apiintegration.courier_manage', compact('steadfast', 'pathao'));
}
    
    public function pathao_token(Request $request){
        
        $pathao = Courierapi::where('type','=','pathao')->first();
    $response = Http::post('https://api-hermes.pathao.com/aladdin/api/v1/issue-token', [
     'client_id' => $pathao->api_key,
     'client_secret' => $pathao->secret_key,
     'grant_type' => 'password',
     'username' => $request->username,
     'password' => $request->password,
     ]);
     
     dd($response);
      if ($response->successful()) {
        $token = $response->json()['access_token'];
     dd($token);
        // Save token to DB
        $pathao->token = $token;
        $pathao->save();

        return redirect()->back()->with('success', 'Token generated and saved successfully.');
    } else {
        return redirect()->back()->with('error', 'Token generation failed. Please check credentials.');
    }  
        
    }
    
    public function courier_update (Request $request)
    {
      
        $update_data = Courierapi::find($request->id);
        $input = $request->all();
        $input['status'] = $request->status?1:0;
        $update_data->update($input);
        
        Toastr::success('Success','Data update successfully');
        return redirect()->back();
    }
    
     private function balanceCheck()
  {
   $smsgateway = SmsGateway::latest()->first();
   $username = urlencode($smsgateway->username);
    $apiKey   = urlencode($smsgateway->api_key);

    $url = "https://api.mimsms.com/api/SmsSending/balanceCheck?userName={$username}&Apikey={$apiKey}";

    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => ['Accept: application/json'],
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
}