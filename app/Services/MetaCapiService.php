<?php

namespace App\Services;

use FacebookAds\Api;
use FacebookAds\Object\ServerSide\CustomData;
use FacebookAds\Object\ServerSide\Event;
use FacebookAds\Object\ServerSide\EventRequest;
use FacebookAds\Object\ServerSide\UserData;
use FacebookAds\Object\ServerSide\Content;
use Illuminate\Support\Facades\Log;
// আপনার ড্যাশবোর্ড মডেলটি এখানে ইম্পোর্ট করা হলো
use App\Models\EcomPixel; 

class MetaCapiService
{
    protected $pixelId;

    public function __construct()
    {
        // 1. ডাটাবেজ থেকে অ্যাকটিভ Pixel সেটিংস Fetch করা
        // ধরে নিচ্ছি 'status' 1 মানে 'active' (PixelsController অনুযায়ী)
        $settings = EcomPixel::where('status', 1)->latest()->first(); 
        
        if (!$settings) {
            $this->pixelId = null;
            Log::warning('Meta CAPI configuration not found or inactive in database. CAPI disabled.');
            return;
        }

        // আপনার PixelsController অনুযায়ী Pixel ID ও Access Token এর কলাম নাম ব্যবহার করুন
        // আপনার EcomPixel মডেলে 'code' ফিল্ডটি Pixel ID ধরে রাখা উচিত।
        $this->pixelId = $settings->code ?? null;
        
        // এখানে টোকেন (Access Token) এর কলাম নাম কী তা দেখতে হবে।
        // যদি এটি 'access_token' হয়, তবে এটি ব্যবহার করুন। 
        // যদি আপনার মডেলে Access Token সেভ করার জন্য কোনো কলাম না থাকে, তবে CAPI কাজ করবে না। 
        // এই উদাহরণের জন্য 'access_token' ব্যবহার করা হলো, আপনি আপনার মডেলের কলাম অনুযায়ী পরিবর্তন করুন।
        $accessToken = $settings->access_token ?? env('META_ACCESS_TOKEN'); // যদি DB তে না থাকে, .env থেকে নিন (Backup)

        // 2. কনফিগারেশন চেক এবং SDK Initialize
        if (!$this->pixelId || !$accessToken) {
             Log::error('Meta CAPI Error: Pixel ID or Access Token is missing. Please check EcomPixel table.');
             return;
        }

        // SDK Initialize করা
        Api::init(
            env('META_APP_ID'), // App ID এবং App Secret সাধারণত .env তে থাকে
            env('META_APP_SECRET'),
            $accessToken // Access Token এখন ডাইনামিক/DB থেকে আসা
        );
    }
    
    // sendPurchaseEvent ফাংশনটি আগের মতোই থাকবে
    public function sendPurchaseEvent(array $userData, array $products, float $value, string $currency, string $orderId)
    {
        // যদি SDK ইনিশিয়ালাইজ না হয়, তবে ইভেন্ট পাঠানো হবে না
        if (!Api::instance()) {
            Log::warning('Meta CAPI SDK not initialized. Event skipped for Order: ' . $orderId);
            return false;
        }

        // 1. User Data তৈরি (SDK নিজে থেকেই PII হ্যাশ করবে)
        $userData = (new UserData())
            ->setEmail($userData['email'] ?? null) 
            ->setPhone($userData['phone'] ?? null) 
            ->setFbp($userData['fbp'] ?? null) 
            ->setFbc($userData['fbc'] ?? null);

        // 2. Products Content তৈরি
        $contents = [];
        foreach ($products as $product) {
            $contents[] = (new Content())
                ->setProductId((string)$product['id'])
                ->setQuantity($product['quantity'])
                ->setPrice($product['price']);
        }

        // 3. Custom Data (মূল্য) তৈরি
        $customData = (new CustomData())
            ->setValue($value)
            ->setCurrency($currency)
            ->setContents($contents)
            ->setContentType('product');

        // 4. Event তৈরি
        $event = (new Event())
            ->setEventName('Purchase')
            ->setEventTime(time())
            ->setUserData($userData)
            ->setCustomData($customData)
            ->setActionSource('website')
            ->setEventSourceUrl(request()->url())
            ->setEventId($orderId); 

        // 5. Request তৈরি এবং পাঠানো
        $request = (new EventRequest($this->pixelId)) 
            ->setEvents([$event]);

        try {
            $response = $request->execute();
            Log::info('Meta CAPI Success for Order ' . $orderId . ': ', $response->getDecodedBody());
            return $response->getDecodedBody();
        } catch (\Exception $e) {
            Log::error('Meta CAPI Error for Order ' . $orderId . ': ' . $e->getMessage());
            return false;
        }
    }
}