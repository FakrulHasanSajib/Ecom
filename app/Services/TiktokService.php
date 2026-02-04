<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TiktokService
{
    /**
     * TikTok API থেকে অ্যাড রিপোর্ট নিয়ে আসার মেথড।
     * * @param string $startDate (YYYY-MM-DD)
     * @param string $endDate (YYYY-MM-DD)
     * @param string $accessToken (From Controller)
     * @param string $advertiserId (From Controller)
     * @return array
     */
    public function getAdReport($startDate, $endDate, $accessToken, $advertiserId)
    {
        // ১. ক্রেডেনশিয়াল চেক করা (এখন কন্ট্রোলার থেকে যা আসছে তা চেক করা হচ্ছে)
        if (empty($accessToken) || empty($advertiserId)) {
            return [
                'code' => 4001,
                'message' => 'TikTok API Configuration Missing or Inactive (Credentials not passed).'
            ];
        }

        // ২. টিকটক এপিআই এন্ডপয়েন্ট (v1.3)
        $url = "https://business-api.tiktok.com/open_api/v1.3/report/integrated/get/";

        try {
            // ৩. এপিআই রিকোয়েস্ট পাঠানো
            $response = Http::withHeaders([
                'Access-Token' => $accessToken, // কন্ট্রোলার থেকে আসা টোকেন
                'Content-Type' => 'application/json',
            ])->get($url, [
                'advertiser_id' => $advertiserId, // কন্ট্রোলার থেকে আসা আইডি
                'report_type'   => 'BASIC',
                'data_level'    => 'AUCTION_AD',
                'dimensions'    => json_encode(['ad_id', 'ad_name', 'campaign_name']),
                'metrics'       => json_encode([
                    'spend', 
                    'impressions', 
                    'clicks', 
                    'ctr', 
                    'cpc', 
                    'cpm', 
                    'conversion', 
                    'cost_per_conversion',
                    'purchase_roas'
                ]),
                'start_date'    => $startDate,
                'end_date'      => $endDate,
                'page_size'     => 100
            ]);

            // ৪. রেসপন্স চেক করা
            if ($response->successful()) {
                return $response->json();
            }

            // এরর হলে লগ এ সেভ করা
            Log::error("TikTok API Error: " . $response->body());
            return $response->json();

        } catch (\Exception $e) {
            Log::error("TikTok Service Exception: " . $e->getMessage());
            return [
                'code' => 500,
                'message' => 'Internal Server Error occurred while fetching TikTok data.'
            ];
        }
    }
}