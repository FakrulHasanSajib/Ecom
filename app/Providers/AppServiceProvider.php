<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Models\GeneralSetting;
use App\Models\Category;
use App\Models\Brand;
use App\Models\SocialMedia;
use App\Models\Contact;
use App\Models\CreatePage;
use App\Models\OrderStatus;
use App\Models\EcomPixel;
use App\Models\GoogleTagManager;
use App\Models\Order;
use App\Models\PaymentGateway;
use App\Models\TiktokPixel;
use Config;
use Session;

class AppServiceProvider extends ServiceProvider
{
  public function register()
{
    if (class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
        
        $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
        
        // ভেন্ডরে থাকলেই কেবল আমাদের অ্যাপের প্রোভাইডার লোড হবে
        $this->app->register(\App\Providers\TelescopeServiceProvider::class);
    }
}

    public function boot()
    {
        Paginator::useBootstrap();
        // 1. Payment Gateway Configuration (Safe Wrap)
        try {
            $shurjopay = PaymentGateway::where(['status' => 1, 'type' => 'shurjopay'])->first();
            if ($shurjopay) {
                Config::set(['shurjopay.apiCredentials.username' => $shurjopay->username]);
                Config::set(['shurjopay.apiCredentials.password' => $shurjopay->password]);
                Config::set(['shurjopay.apiCredentials.prefix' => $shurjopay->prefix]);
                Config::set(['shurjopay.apiCredentials.return_url' => $shurjopay->success_url]);
                Config::set(['shurjopay.apiCredentials.cancel_url' => $shurjopay->return_url]);
                Config::set(['shurjopay.apiCredentials.base_url' => $shurjopay->base_url]);
            }
        } catch (\Exception $e) {
            // Log error
        }

        // 2. General Settings (CRASH FIX HERE)
        $generalsetting = GeneralSetting::where('status', 1)->first();

        // যদি ডাটাবেসে সেটিংস না থাকে, তবে আমরা একটি ডামি অবজেক্ট তৈরি করব
        // যাতে ভিউ ফাইলে ->name বা ->favicon কল করলে এরর না দেয়
        if (!$generalsetting) {
            $generalsetting = new GeneralSetting();
            $generalsetting->name = 'My Ecommerce'; // ডিফল্ট নাম
            $generalsetting->white_logo = 'public/uploads/logo.png';
            $generalsetting->favicon = 'public/uploads/favicon.png';
            $generalsetting->header_menu_labels = '[]';
            $generalsetting->header_menu_links = '[]';
            $generalsetting->footer_menu_labels = '[]';
            $generalsetting->footer_menu_links = '[]';
        }
        
        // এখন আর $generalsetting নাল হওয়ার সুযোগ নেই
        view()->share('generalsetting', $generalsetting);


        // 3. Header & Footer Menu Logic
        $headerMenuData = [];
        $footerMenuData = [];

        // labels এবং links ডিকোড করার আগে চেক করে নেওয়া হচ্ছে
        $labels = json_decode($generalsetting->header_menu_labels, true);
        $urls = json_decode($generalsetting->header_menu_links, true);

        if (is_array($labels) && is_array($urls)) {
            foreach ($labels as $key => $label) {
                if (isset($urls[$key])) {
                    $headerMenuData[] = [
                        'label' => $label,
                        'url' => $urls[$key],
                    ];
                }
            }
        }

        $foolabels = json_decode($generalsetting->footer_menu_labels, true);
        $foourls = json_decode($generalsetting->footer_menu_links, true);

        if (is_array($foolabels) && is_array($foourls)) {
            foreach ($foolabels as $key => $labelf) {
                if (isset($foourls[$key])) {
                    $footerMenuData[] = [
                        'labels' => $labelf,
                        'urls' => $foourls[$key],
                    ];
                }
            }
        }

        view()->share('headerMenu', $headerMenuData);
        view()->share('headerFooter', $footerMenuData);


        // 4. Other Shared Data
        // এই কুয়েরিগুলো যেন ফেইল না করে তাই optional() বা চেক রাখা ভালো, তবে সাধারণত এগুলো ক্র্যাশ করে না
        $sidecategories = Category::where('parent_id', '0')->where('status', 1)->select('id', 'name', 'slug', 'status', 'image')->get();
        view()->share('sidecategories', $sidecategories);

        $menucategories = Category::where('status', 1)
    ->with(['subcategories.childcategories']) // এটি এক কোয়েরিতে সব নিয়ে আসবে
    ->select('id', 'name', 'slug', 'status', 'image')
    ->get();

view()->share('menucategories', $menucategories);

        $contact = Contact::where('status', 1)->first();
        view()->share('contact', $contact);

        $socialicons = SocialMedia::where('status', 1)->get();
        view()->share('socialicons', $socialicons);

        $pages = CreatePage::where('status', 1)->limit(3)->get();
        view()->share('pages', $pages);

        $pagesright = CreatePage::where('status', 1)->skip(3)->limit(10)->get();
        view()->share('pagesright', $pagesright);

        $cmnmenu = CreatePage::where('status', 1)->get();
        view()->share('cmnmenu', $cmnmenu);

        $brands = Brand::where('status', 1)->get();
        view()->share('brands', $brands);

        $neworder = Order::where('order_status', '1')->count();
        view()->share('neworder', $neworder);

        $pendingorder = Order::where('order_status', '1')->latest()->limit(9)->get();
        view()->share('pendingorder', $pendingorder);

        $orderstatus = OrderStatus::get();
        view()->share('orderstatus', $orderstatus);

        $pixels = EcomPixel::where('status', 1)->get();
        view()->share('pixels', $pixels);

        $gtm_code = GoogleTagManager::where('status', 1)->get();
        view()->share('gtm_code', $gtm_code);
        // TikTok Pixel Share Code
        try {
            $activeTiktokPixel = TiktokPixel::where('status', 1)->first();
            view()->share('activeTiktokPixel', $activeTiktokPixel);
        } catch (\Exception $e) {
            // টেবিল না থাকলে বা এরর হলে ইগনোর করবে
        }
    }

}