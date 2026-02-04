<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Jobs\SendServerSideEventJob;
use Illuminate\Support\Facades\Log;

class TrackingController extends Controller
{
    public function trackEvent(Request $request)
    {
        // ১. সেশন লক রিলিজ (যাতে কার্ট বা চেকআউট পেজ স্লো না হয়)
        session_write_close(); 

        try {
            $data = $request->all();

            $trackingData = [
                // বেসিক ইভেন্ট ইনফো
                'event_name'      => $data['event_name'] ?? 'Unknown',
                'event_id'        => $data['event_id'] ?? null,
                'source_url'      => $data['source_url'] ?? null,
                
                // 👇 ইউজার ডাটা (সব প্যারামিটার রিসিভ করা হচ্ছে)
                'email'           => $data['user_data']['email'] ?? null, 
                'phone'           => $data['user_data']['phone'] ?? null,
                'name'            => $data['user_data']['name'] ?? null,
                'city'            => $data['user_data']['city'] ?? null,
                'zip'             => $data['user_data']['zip'] ?? null,
                'country'         => $data['user_data']['country'] ?? 'bd',
                'dob'             => $data['user_data']['dob'] ?? null, // জন্মতারিখ
                'external_id'     => $data['user_data']['external_id'] ?? null,
                
                // 👇 ব্রাউজার ও ক্লিক আইডি (API রাউটের জন্য গুরত্বপূর্ণ)
                // লজিক: প্রথমে JS থেকে আসা ডাটা দেখবে, না পেলে কুকি চেক করবে
                'fbp'             => $data['user_data']['fbp'] ?? $request->cookie('_fbp'),
                'fbc'             => $data['user_data']['fbc'] ?? $request->cookie('_fbc'),
                'ttp'             => $data['user_data']['ttp'] ?? $request->cookie('_ttp'),

                // ভ্যালু এবং টেকনিক্যাল ডাটা
                'amount'          => $data['value'] ?? 0,
                'ip'              => $request->ip(),
                'user_agent'      => $request->userAgent(),
                
                // কনটেন্ট ডাটা (প্রোডাক্ট আইডি এবং ডিটেইলস)
                'content_ids'     => $data['content_ids'] ?? [],
                // TikTok এর জন্য contents ডাটা decode করা হচ্ছে (যদি স্ট্রিং হিসেবে আসে)
                'contents_tiktok' => isset($data['contents']) 
                                        ? (is_array($data['contents']) ? $data['contents'] : json_decode($data['contents'], true)) 
                                        : [],
            ];

            // জব ফাইলে ডাটা পাঠানো
            SendServerSideEventJob::dispatch($trackingData);

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            // এরর লগ ফাইলে সেভ রাখা ভালো ডিবাগিংয়ের জন্য
            Log::error('SST Controller Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}