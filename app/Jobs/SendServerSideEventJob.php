<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\EcomPixel;
use App\Models\TiktokPixel;
use App\Models\GoogleTagManager;

class SendServerSideEventJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $trackingData;

    public function __construct($trackingData)
    {
        $this->trackingData = $trackingData;
    }

    public function handle()
    {
        $this->sendFacebookCAPI();
        $this->sendTikTokCAPI();
       // $this->sendGoogleAdsConversion();
    }

   protected function sendFacebookCAPI()
    {
        $pixels = EcomPixel::where('status', 1)->get();

        foreach ($pixels as $pixel) {
            if (empty($pixel->meta_access_token)) continue;

            $url = "https://graph.facebook.com/v19.0/{$pixel->code}/events?access_token={$pixel->meta_access_token}";
            $eventName = $this->trackingData['event_name'] ?? 'Purchase';
            $eventId = (string) ($this->trackingData['event_id'] ?? time());

            $amountRaw = $this->trackingData['amount'] ?? 0;
            $cleanAmount = (float) filter_var($amountRaw, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

            // নাম স্প্লিট করা (First Name, Last Name)
            $fullName = $this->trackingData['name'] ?? '';
            $firstName = '';
            $lastName = '';
            if (!empty($fullName)) {
                $parts = explode(' ', trim($fullName));
                $lastName = array_pop($parts);
                $firstName = implode(' ', $parts);
                if(empty($firstName)) { $firstName = $lastName; $lastName = ''; }
            }

            // জন্মতারিখ ফরম্যাট (YYYYMMDD)
            $dob = null;
            if (!empty($this->trackingData['dob'])) {
                // ইনপুট ডেট থেকে হাইফেন সরিয়ে ফেলা (e.g. 1990-01-01 -> 19900101)
                $dob = hash('sha256', str_replace('-', '', $this->trackingData['dob']));
            }

            // 🔥 ফোন নাম্বার ফিক্স (স্কোর বাড়ানোর জন্য এই অংশটি বসান)
$phone = $this->trackingData['phone'] ?? '';
$phone = preg_replace('/[^0-9]/', '', $phone); // শুধু নাম্বার রাখা

if (!empty($phone)) {
    // যদি 0 দিয়ে শুরু হয় (যেমন: 017...) তবে 88 যোগ হবে
    if (substr($phone, 0, 1) === '0') {
        $phone = '88' . $phone;
    } 
    // যদি 1 দিয়ে শুরু হয় (যেমন: 17..., 19...) তবে 880 যোগ হবে
    elseif (substr($phone, 0, 1) === '1') {
        $phone = '880' . $phone;
    }
}

            // ✅ সব অ্যাডভান্সড প্যারামিটার (হ্যাশিং সহ)
            $userData = [
                'em'  => !empty($this->trackingData['email']) ? hash('sha256', strtolower(trim($this->trackingData['email']))) : null,
                'ph' => !empty($phone) ? hash('sha256', $phone) : null,
                'fn'  => !empty($firstName) ? hash('sha256', strtolower(trim($firstName))) : null,
                'ln'  => !empty($lastName) ? hash('sha256', strtolower(trim($lastName))) : null,
                'ct'  => !empty($this->trackingData['city']) ? hash('sha256', strtolower(trim($this->trackingData['city']))) : null,
                'zp'  => !empty($this->trackingData['zip']) ? hash('sha256', strtolower(trim($this->trackingData['zip']))) : null,
                'country' => !empty($this->trackingData['country']) ? hash('sha256', strtolower(trim($this->trackingData['country']))) : hash('sha256', 'bd'),
                'db'  => $dob, // Birthdate
                'external_id' => !empty($this->trackingData['external_id']) ? hash('sha256', $this->trackingData['external_id']) : null,
                
                // এগুলো হ্যাশ হবে না
                'client_ip_address' => $this->trackingData['ip'] ?? null,
                'client_user_agent' => $this->trackingData['user_agent'] ?? null,
                'fbp' => $this->trackingData['fbp'] ?? null, // Browser ID
                'fbc' => $this->trackingData['fbc'] ?? null, // Click ID
                // 'fb_login_id' => null, // যদি আপনার অ্যাপে ফেইসবুক লগইন থাকে তবে এটি ব্যবহার করুন
            ];

            $customData = [
                'currency' => 'BDT',
                'value' => $cleanAmount,
            ];

            if (!empty($this->trackingData['content_ids'])) {
                $customData['content_ids'] = $this->trackingData['content_ids'];
                $customData['content_type'] = 'product';
            }

            $payload = [
                'data' => [
                    [
                        'event_name' => $eventName,
                        'event_time' => time(),
                        'event_source_url' => $this->trackingData['source_url'] ?? null,
                        'event_id' => $eventId,
                        'action_source' => 'website',
                        'user_data' => array_filter($userData), // নাল ভ্যালু রিমুভ
                        'custom_data' => $customData,
                    ]
                ],
               // 'test_event_code' => 'TEST6007', // টেস্ট করার সময় আনকমেন্ট করুন
            ];

           try {
    // পোস্ট রিকোয়েস্টটি পাঠানো হচ্ছে
    $response = Http::post($url, $payload);
    
    // যদি রিকোয়েস্ট সফল হয় বা ফেসবুক কোনো রেসপন্স দেয়
    if ($response) {
      //  \Log::info('Facebook CAPI Response for Order ' . ($this->trackingData['event_id'] ?? 'N/A') . ': ' . $response->body());
    }

} catch (\Exception $e) {
    // এখানে $response ব্যবহার করবেন না, কারণ এরর হলে $response তৈরি হয় না
   // \Log::error('FB CAPI Error for Order ' . ($this->trackingData['event_id'] ?? 'N/A') . ': ' . $e->getMessage());
}
        }
    }

   protected function sendTikTokCAPI()
    {
        $pixels = TiktokPixel::where('status', 1)->get();

        foreach ($pixels as $pixel) {
            if (empty($pixel->access_token)) continue;

            $url = "https://business-api.tiktok.com/open_api/v1.3/pixel/track/";
            $fbEventName = $this->trackingData['event_name'] ?? 'Purchase';
            
            $eventMap = [
                'Purchase' => 'Purchase',
                'InitiateCheckout' => 'InitiateCheckout',
                'AddToCart' => 'AddToCart',
                'ViewContent' => 'ViewContent',
                'PageView' => 'Pageview',
            ];
            $tiktokEventName = $eventMap[$fbEventName] ?? $fbEventName;

            $eventId = (string) ($this->trackingData['event_id'] ?? time());

            // ✅ এমাউন্ট ক্লিন করা
            $amountRaw = $this->trackingData['amount'] ?? 0;
            $cleanAmount = (float) filter_var($amountRaw, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

            // ✅ ১. নাম ভাঙার লজিক (ফেসবুকের মতই)
            $fullName = $this->trackingData['name'] ?? '';
            $firstName = '';
            $lastName = '';
            
            if (!empty($fullName)) {
                $parts = explode(' ', trim($fullName));
                $lastName = array_pop($parts);
                $firstName = implode(' ', $parts);
                if(empty($firstName)) { $firstName = $lastName; $lastName = ''; }
            }

            $phone = $this->trackingData['phone'] ?? '';
$phone = preg_replace('/[^0-9]/', '', $phone);
if (!empty($phone)) {
    if (substr($phone, 0, 1) === '0') {
        $phone = '88' . $phone;
    } elseif (substr($phone, 0, 1) === '1') {
        $phone = '880' . $phone;
    }
}

            // ✅ ২. টিকটকের ইউজার ডাটা (Match Quality এর জন্য)
           $userPayload = [
    // ইমেইল এবং ফোন অবশ্যই হ্যাশ করতে হবে
    'email' => !empty($this->trackingData['email']) ? hash('sha256', strtolower(trim($this->trackingData['email']))) : null,
   'phone_number' => !empty($phone) ? hash('sha256', $phone) : null,
    // External ID খুবই পাওয়ারফুল ম্যাচিং প্যারামিটার
    'external_id' => !empty($this->trackingData['external_id']) ? hash('sha256', (string)$this->trackingData['external_id']) : null,
    
    // এই ttp টা CustomerController থেকে আসলে কাজ করবে, OrderController থেকে আসলে নাল হবে
    'ttp' => $this->trackingData['ttp'] ?? null, 
    
    'ip' => $this->trackingData['ip'] ?? null,
    'user_agent' => $this->trackingData['user_agent'] ?? null,
    
    // নাম এবং ঠিকানা
    'first_name' => !empty($firstName) ? hash('sha256', strtolower(trim($firstName))) : null,
    'last_name' => !empty($lastName) ? hash('sha256', strtolower(trim($lastName))) : null,
    'country' => hash('sha256', 'bd'),
];

            $properties = [
                'currency' => 'BDT',
                'value' => $cleanAmount,
            ];

            if (!empty($this->trackingData['contents_tiktok'])) {
                $properties['contents'] = $this->trackingData['contents_tiktok'];
            }

            $payload = [
                'pixel_code' => $pixel->pixel_id,
                'event' => $tiktokEventName,
                'event_id' => $eventId,
                'timestamp' => date('c'),
                'context' => [
                    'page' => [
                        'url' => $this->trackingData['source_url'] ?? null
                    ],
                    // array_filter নাল ভ্যালু রিমুভ করে দিবে
                    'user' => array_filter($userPayload) 
                ],
                'properties' => $properties
            ];

            // ⚠️ টেস্ট শেষ হলে এই লাইনটি মুছে দিবেন বা কমেন্ট করবেন
          //  $payload['test_event_code'] = 'TEST41949'; 

            try {
                // ✅ ফিক্স: শুরুতে $response = দিতে হবে
                $response = Http::withHeaders([
                    'Access-Token' => $pixel->access_token,
                    'Content-Type' => 'application/json'
                ])->post($url, $payload);

                // লগিং (এখন আর এরর দিবে না)
                if($tiktokEventName === 'Pageview'){
                  //  Log::info('TikTok Pageview Sent: ' . $response->status());
                   // Log::info('Response Body: ' . $response->body()); // রেসপন্স বডিও দেখতে পারবেন
                }

            } catch (\Exception $e) {
                Log::error('TikTok API Error: ' . $e->getMessage());
            }
        }
    }

    // SendServerSideEventJob.php এর একেবারে নিচে (ক্লাস শেষ হওয়ার আগে)

// protected function sendGoogleAdsConversion()
// {
//     \Log::info("🚀 Google Conversion Function STARTED");

//     // Production-এ থাকলে এটি false করে দেবেন
//     $TEST_MODE = true; 
//     $VALIDATE_ONLY = $TEST_MODE; // টেস্ট মোডে শুধু validate করবে, আসল কনভার্সন তৈরি করবে না

//     if ($TEST_MODE) {
//         $this->trackingData = [
//             'amount'   => 2500,
//             'event_id' => 'ORDER_' . rand(1000, 9999),
//             'gclid'    => 'EAIaIQobChMIrL_9w_v_hwMVp6SDBx1_Xw8XEAAYASAAEgI_YvD_BwE' // ডামি GCLID (টেস্টের জন্য)
//         ];
//     }

//     $amount  = $this->trackingData['amount'] ?? 0;
//     $orderId = (string) ($this->trackingData['event_id'] ?? uniqid());
//     $gclid   = $this->trackingData['gclid'] ?? null;

//     if (!$gclid) {
//         \Log::warning("❌ No GCLID Found – Skipping conversion upload");
//         return;
//     }

//     if ($amount <= 0) {
//         \Log::warning("⚠️ Conversion amount is 0 or negative – May not be processed");
//     }

//     // সেটিংস লোড করা
//     $setting = \App\Models\GoogleTagManager::where('status', 1)->first();

//     if (!$setting) {
//         \Log::error("❌ Google settings not found in Database");
//         return;
//     }

//     // আইডিগুলো ক্লিন করা (শুধু নাম্বার রাখা)
//     $customerId = preg_replace('/[^0-9]/', '', $setting->google_ads_customer_id);
//     $actionId   = preg_replace('/[^0-9]/', '', $setting->google_conversion_action_id);
//     $mccId      = preg_replace('/[^0-9]/', '', $setting->google_mcc_customer_id ?? '');

//     try {
//         // ১. এক্সেস টোকেন নেওয়া (refresh token দিয়ে)
//         $tokenResponse = Http::asForm()->post('https://oauth2.googleapis.com/token', [
//             'client_id'     => trim($setting->google_client_id),
//             'client_secret' => trim($setting->google_client_secret),
//             'refresh_token' => trim($setting->google_refresh_token),
//             'grant_type'    => 'refresh_token',
//         ]);

//         if ($tokenResponse->failed()) {
//             \Log::error("❌ Google OAuth Token Failed: " . $tokenResponse->body());
//             return;
//         }

//         $accessToken = $tokenResponse->json()['access_token'] ?? null;

//         if (!$accessToken) {
//             \Log::error("❌ No access token received from Google");
//             return;
//         }

//         // ২. লেটেস্ট API ভার্সন ব্যবহার করা (v23 – January 2026 latest)
//         $apiVersion = 'v23';
//         $url = "https://googleads.googleapis.com/{$apiVersion}/customers/{$customerId}:uploadClickConversions";

//         // conversionDateTime – timezone সহ ISO ফরম্যাট (উদাহরণ: 2026-01-31 15:45:00+06:00)
//         // নিরাপদে ১-২ ঘণ্টা আগে রাখা যায়, কিন্তু টেস্টে এখনকার সময়ও চলবে
//         $conversionDateTime = now()->subMinutes(90)->format('Y-m-d\TH:i:sP'); // ISO 8601 with timezone

//         $payload = [
//             "conversions" => [[
//                 "conversionAction"   => "customers/{$customerId}/conversionActions/{$actionId}",
//                 "conversionDateTime" => $conversionDateTime,
//                 "conversionValue"    => (float) $amount,
//                 "currencyCode"       => "BDT",
//                 "gclid"              => $gclid,
//                 "orderId"            => $orderId,
//                 // অপশনাল: user_identifiers যোগ করতে পারো যদি থাকে (hashed email/phone)
//                 // "userIdentifiers" => [ ... ]
//             ]],
//             "partialFailure"  => true,     // কিছু ফেল করলেও বাকিগুলো প্রসেস করবে
//             "validateOnly"    => $VALIDATE_ONLY, // টেস্ট মোডে true → আসল ডেটা সেভ হয় না
//         ];

//         $headers = [
//             "Authorization"     => "Bearer {$accessToken}",
//             "developer-token"   => trim($setting->google_developer_token),
//             "Content-Type"      => "application/json",
//             "login-customer-id" => !empty($mccId) ? $mccId : null, // MCC হলে দরকার
//         ];

//         // null হেডার রিমুভ করা
//         $headers = array_filter($headers);

//         $response = Http::withHeaders($headers)->post($url, $payload);

//         \Log::info("Google Ads API Response: Status " . $response->status());
//         \Log::info("URL Hit: " . $url);
//         \Log::info("Payload Sent: " . json_encode($payload, JSON_PRETTY_PRINT));
//         \Log::info("Response Body: " . $response->body());

//         if ($response->successful()) {
//             $result = $response->json();
//             if ($VALIDATE_ONLY) {
//                 \Log::info("✅ Validation Passed (Test Mode) – No real conversion created");
//             } else {
//                 \Log::info("✅ Conversion Uploaded Successfully");
//             }
//             // অপশনাল: partial failure চেক করতে পারো
//             if (!empty($result['partialFailureError'])) {
//                 \Log::warning("Partial Failure: " . json_encode($result['partialFailureError']));
//             }
//         } else {
//             \Log::error("❌ Conversion Upload Failed – Status: " . $response->status());
//             \Log::error("Error Body: " . $response->body());
//         }

//     } catch (\Exception $e) {
//         \Log::error("❌ System Exception in Google Conversion: " . $e->getMessage());
//         \Log::error("Stack Trace: " . $e->getTraceAsString());
//     }
// }


}