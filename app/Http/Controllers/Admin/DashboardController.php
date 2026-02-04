<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\Customer;
use App\Models\AdminQuickTab;
use App\Models\OrderStatus; 
use Carbon\Carbon;
use Session;
use Toastr;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http; 
use App\Services\TiktokService;
use App\Models\TiktokPixel;
use App\Models\VisitorActivity;
use App\Models\EcomPixel; // ধরে নিলাম এই মডেল বা এর কাছাকাছি মডেল আপনি ব্যবহার করছেন
use Exception;

// যদি আপনি একটি ডেডিকেটেড কনফিগারেশন মডেল ব্যবহার করেন
// use App\Models\FacebookConfig; // যদি এই নামে মডেল থাকে

class DashboardController extends Controller
{
    private $adAccountId;
    private $accessToken;
    private $apiBaseUrl;
    // TikTok Credentials
    private $ttAccessToken;
    private $ttAdvertiserId;
    
    public function __construct()
    {
        // 1. ⭐ ডেটাবেস থেকে ক্রেডেনশিয়ালস লোড করা ⭐
        // আমরা ধরে নিচ্ছি আপনার EcomPixel মডেলে অ্যাড অ্যাকাউন্টের কনফিগারেশন আছে,
        // অথবা আপনি একটি নির্দিষ্ট সেটিং আইডি লোড করছেন (যেমন আইডি 1)
        
        $config = EcomPixel::latest()->first();
 // উদাহরণস্বরূপ, প্রথম বা একমাত্র কনফিগারেশন লোড করা হচ্ছে

        if ($config) {
            $this->adAccountId = $config->ad_account_id ?? null;
            $this->accessToken = $config->meta_access_token ?? null;
            $this->code = $config->code ?? null;
            // এই ক্ষেত্রে API ভার্সন .env থেকে নেওয়াই নিরাপদ:
            $this->apiBaseUrl = 'https://graph.facebook.com/' . env('FACEBOOK_API_VERSION', 'v19.0') . '/';
        } else {
            // যদি ডেটাবেসে কোনো কনফিগারেশন না থাকে
            $this->adAccountId = null;
            $this->accessToken = null;
            $this->apiBaseUrl = 'https://graph.facebook.com/' . env('FACEBOOK_API_VERSION', 'v19.0') . '/';
        }
        // 2. TikTok Credentials (From TiktokPixel Model)
        $ttConfig = TiktokPixel::where('status', 1)->latest()->first();
        if ($ttConfig) {
            $this->ttAccessToken = $ttConfig->access_token;
            $this->ttAdvertiserId = $ttConfig->ad_account_id;
        }
    }
    

    public function dashboard(Request $request ,TiktokService $tiktokService)
    {
        // ... (বাকি কোড অপরিবর্তিত)
        $myidntitiy = Auth::user()->user_type;
        $DELIVERED_ID = 10; 
        $CANCELLED_ID = 7;
        $RETURNED_ID = 11;

        // --- সকল Undefined variable এরর দূর করতে Global Initialization ---
        $dashboardData = []; $total_product = 0; 
        $latest_order = collect([]); $latest_customer = collect([]); $today_delivery = 0;
        $total_delivery = 0; $last_week = 0; $last_month = 0; $monthly_sale = collect([]);
        $report_data = collect([]); $topSell = collect([]); $assigin = [];
        
        $total_order = 0; $total_order_amount = 0;
        $today_total_orders = 0; $today_total_amount = 0; 
        $yesterday_total_orders = 0; $yesterday_total_amount = 0; 
        $cancelled_total_orders = 0; $cancelled_total_amount = 0;
        $return_total_orders = 0; $return_total_amount = 0;
        $total_customer = 0;

        // ⭐ Wholesale Variables Initialization ⭐
        $total_wholesale_order = 0; $total_wholesale_amount = 0;
        $today_wholesale_orders = 0; $today_wholesale_amount = 0;
        $yesterday_wholesale_orders = 0; $yesterday_wholesale_amount = 0;
        $cancelled_wholesale_orders = 0; $cancelled_wholesale_amount = 0;
        $return_wholesale_orders = 0; $return_wholesale_amount = 0;
        
        $quickTabs = collect([]); 
        
        $facebookActivityData = ['error' => null, 'labels' => '[]', 'data' => '[]'];
        $adMetrics = [];
        
        // --- 🎵 TikTok Initialization 🎵 ---
        $tiktokReports = []; 

        if($myidntitiy == 'admin'){
            
            // ⭐ [ফেসবুক অ্যাড মেট্রিক্সের জন্য ডেট ফিল্টার] 
            $fb_startDate = $request->input('fb_start_date');
            $fb_endDate = $request->input('fb_end_date');
            $fb_dateFilter = $request->input('fb_date_filter', 'last_7_days'); 
            
            $adMetrics = $this->getFacebookAdMetrics($fb_startDate, $fb_endDate, $fb_dateFilter);

            // --- TikTok Ad Metrics ---
            $tt_startDate = $request->input('tt_start_date');
            $tt_endDate = $request->input('tt_end_date');
            $tt_dateFilter = $request->input('tt_date_filter', 'last_7_days');
            $tiktokReports = $this->getTiktokAdMetrics($tiktokService, $tt_startDate, $tt_endDate, $tt_dateFilter);

            // ... (বাকি কোড অপরিবর্তিত)
            $startDate = $request->input('status_start_date');
            $endDate = $request->input('status_end_date');
            $dateFilter = $request->input('status_date_filter');

            $hasDateFilter = (!empty($startDate) && !empty($endDate)) || (!empty($dateFilter) && $dateFilter !== 'lifetime');
            
            $applyDateFilter = function ($query) use ($startDate, $endDate, $dateFilter) {
                if (!empty($startDate) && !empty($endDate)) { 
                    try {
                        $start = Carbon::parse($startDate)->startOfDay();
                        $end = Carbon::parse($endDate)->endOfDay();
                    } catch (\Exception $e) {
                         $start = Carbon::now()->subMonths(3); 
                         $end = Carbon::now();
                    }
                    $query->where(function ($q) use ($start, $end) {
                        $q->whereBetween('o.created_at', [$start, $end])
                          ->orWhereBetween('o.updated_at', [$start, $end]);
                    });
                } 
                elseif (!empty($dateFilter) && $dateFilter !== 'lifetime') { 
                    $col = 'o.updated_at'; 
                    if ($dateFilter === 'today') {
                        $query->whereDate($col, Carbon::today());
                    } elseif ($dateFilter === 'this_week') {
                        $query->whereBetween($col, [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    } elseif ($dateFilter === 'this_month') {
                        $query->whereBetween($col, [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
                    }
                }
            };
            
            
            // ⭐ ১. রেগুলার অর্ডারের লজিক (Wholesale বাদে)
            $regularOrder = function($q) {
                $q->where('customer_type', '!=', 'wholesale')
                  ->orWhereNull('customer_type');
            };

            // --- ২. রেগুলার অর্ডারের মেইন কাউন্টার ---
            $total_order = Order::where($regularOrder)->count();
            $total_order_amount = Order::where($regularOrder)->sum('amount'); 
            
            $total_customer = Customer::count();
            $total_product = Product::count();

            $today_total_orders = Order::where($regularOrder)->whereDate('created_at', Carbon::today())->count();
            $today_total_amount = Order::where($regularOrder)->whereDate('created_at', Carbon::today())->sum('amount');
            
            $yesterday_total_orders = Order::where($regularOrder)->whereDate('created_at', Carbon::yesterday())->count();
            $yesterday_total_amount = Order::where($regularOrder)->whereDate('created_at', Carbon::yesterday())->sum('amount');
            
            $cancelled_total_orders = Order::where($regularOrder)->where('order_status', $CANCELLED_ID)->count();
            $cancelled_total_amount = Order::where($regularOrder)->where('order_status', $CANCELLED_ID)->sum('amount');
            
            $return_total_orders = Order::where($regularOrder)->where('order_status', $RETURNED_ID)->count();
            $return_total_amount = Order::where($regularOrder)->where('order_status', $RETURNED_ID)->sum('amount');


            // ⭐ ৩. Wholesale অর্ডারের লজিক (শুধুমাত্র Wholesale) ⭐
            $wholesaleFilter = function($q) {
                $q->where('customer_type', 'wholesale');
            };

            // --- ৪. Wholesale অর্ডারের মেইন কাউন্টার ---
            $total_wholesale_order = Order::where($wholesaleFilter)->count();
            $total_wholesale_amount = Order::where($wholesaleFilter)->sum('amount');

            $today_wholesale_orders = Order::where($wholesaleFilter)->whereDate('created_at', Carbon::today())->count();
            $today_wholesale_amount = Order::where($wholesaleFilter)->whereDate('created_at', Carbon::today())->sum('amount');
            
            $yesterday_wholesale_orders = Order::where($wholesaleFilter)->whereDate('created_at', Carbon::yesterday())->count();
            $yesterday_wholesale_amount = Order::where($wholesaleFilter)->whereDate('created_at', Carbon::yesterday())->sum('amount');
            
            $cancelled_wholesale_orders = Order::where($wholesaleFilter)->where('order_status', $CANCELLED_ID)->count();
            $cancelled_wholesale_amount = Order::where($wholesaleFilter)->where('order_status', $CANCELLED_ID)->sum('amount');
            
            $return_wholesale_orders = Order::where($wholesaleFilter)->where('order_status', $RETURNED_ID)->count();
            $return_wholesale_amount = Order::where($wholesaleFilter)->where('order_status', $RETURNED_ID)->sum('amount');


            // ⭐ ৫. নিচের স্ট্যাটাস বক্স এবং ফিল্টার লজিক (রেগুলার অর্ডারের জন্য)
            $baseQuery = Order::query()->from('orders as o')
                ->where(function($q) {
                    $q->where('o.customer_type', '!=', 'wholesale')
                      ->orWhereNull('o.customer_type');
                });

            if ($hasDateFilter) {
                $baseQuery->when(true, $applyDateFilter);
            }

            $total_order_filtered = $baseQuery->count();
            $total_order_amount_filtered = $baseQuery->sum('o.amount'); 
            
            $statusMap = OrderStatus::pluck('id', 'name')->toArray(); 

            if (isset($statusMap['Returned']) && !isset($statusMap['Return'])) {
                $statusMap['Return'] = $statusMap['Returned'];
                unset($statusMap['Returned']); 
            }
            
            $statusIds = array_values($statusMap);
            
            $statusSummary = DB::table('orders as o')
                ->when($hasDateFilter, $applyDateFilter) 
                ->whereIn('o.order_status', $statusIds) 
                // এই ফিল্টারটি এখানেও যোগ করা হলো (রেগুলার অর্ডারের জন্য)
                ->where(function($q) {
                    $q->where('o.customer_type', '!=', 'wholesale')
                      ->orWhereNull('o.customer_type');
                })
                ->select(
                    'o.order_status',
                    DB::raw('COUNT(*) as count'),
                    DB::raw('SUM(o.amount) as total_value')
                )
                ->groupBy('o.order_status')
                ->get()
                ->keyBy('order_status');

            $dynamicStatusLabels = array_keys($statusMap);
            array_unshift($dynamicStatusLabels, 'All Order'); 
            
            $dashboardData = [
                'statusMap' => $statusMap,
                'statusSummary' => $statusSummary,
                'allOrder' => ['count' => $total_order_filtered, 'value' => $total_order_amount_filtered],
                'allStatusLabels' => $dynamicStatusLabels, 
            ];

            $latest_order = Order::latest()->limit(5)->with('customer', 'product.image')->get(); 
            $latest_customer = Customer::latest()->limit(5)->get();
            
            $total_delivery = (clone $baseQuery)->where('o.order_status', $DELIVERED_ID)->count(); 

            $colForMonthlySale = ($dateFilter && $dateFilter !== 'lifetime' && !$startDate && !$endDate) ? 'o.updated_at' : 'o.created_at';

            $monthly_sale = DB::table('orders as o')
                ->when($hasDateFilter, $applyDateFilter) 
                ->select(DB::raw("DATE($colForMonthlySale) as date"))
                ->selectRaw("SUM(o.amount) as amount")
                ->where('o.order_status', $DELIVERED_ID) 
                ->groupBy('date')
                ->orderBy('date', 'ASC')
                ->get();


            $reportFilterValue = $request->input('report_duration', 'daily'); 
            
            $hasReportDateFilter = (
                !empty($request->input('start_date')) && !empty($request->input('end_date'))
            ) || (
                !empty($request->input('date_filter')) && $request->input('date_filter') !== 'lifetime'
            );

            $applyReportFilter = function ($query) use ($request) {
                $startDate = $request->input('start_date');
                $endDate = $request->input('end_date');
                $dateFilter = $request->input('date_filter');
                
                 if (!empty($startDate) && !empty($endDate)) { 
                     try {
                         $start = Carbon::parse($startDate)->startOfDay();
                         $end = Carbon::parse($endDate)->endOfDay();
                     } catch (\Exception $e) {
                          $start = Carbon::now()->subMonths(3); 
                          $end = Carbon::now();
                     }
                     
                     $query->where(function ($q) use ($start, $end) {
                          $q->whereBetween('o.created_at', [$start, $end])
                            ->orWhereBetween('o.updated_at', [$start, $end]);
                     });
                 } 
                 elseif (!empty($dateFilter) && $dateFilter !== 'lifetime') { 
                     $col = 'o.updated_at';
                     if ($dateFilter === 'today') {
                         $query->whereDate($col, Carbon::today());
                     } elseif ($dateFilter === 'this_week') {
                         $query->whereBetween($col, [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                     } elseif ($dateFilter === 'this_month') {
                         $query->whereBetween($col, [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
                     }
                 }
            };
            
            $report_data = $this->getSellReportData($request, $reportFilterValue, $applyReportFilter, $hasReportDateFilter, true, $statusMap); 
            
            $topSellFilterValue = $request->input('topsell', 'daily'); 
            $topSell = $this->getTopSellReportData($request, $topSellFilterValue, $applyReportFilter, $hasReportDateFilter);

            $users = User::where('status', 1)->get();
            $assigin = $users;
            
            $quickTabs = AdminQuickTab::where('user_id', Auth::id()) 
                ->where('is_active', 1) 
                ->orderBy('order', 'asc') 
                ->get();

                // পুরনো $reseller_stats কোড মুছে এটি বসান
// [UPDATE] থানা ভিত্তিক Active এবং Pending আলাদা করে বের করার কুয়েরি
$reseller_stats = \App\Models\Reseller::select(
        'thana_id', 
        'district_id', 
        DB::raw('count(*) as total'), // মোট কতজন
        // কতজন অ্যাক্টিভ (Active বা 1)
        DB::raw("SUM(CASE WHEN status = 'active' OR status = 1 THEN 1 ELSE 0 END) as active_count"),
        // কতজন পেন্ডিং (Pending বা 0)
        DB::raw("SUM(CASE WHEN status = 'pending' OR status = 0 OR status IS NULL THEN 1 ELSE 0 END) as pending_count")
    )
    ->with(['thana', 'district'])
    ->groupBy('thana_id', 'district_id')
    ->orderBy('total', 'desc')
    ->get();


            // Visitor Tracking Data (Time Spent & Scroll Depth)
            $recent_activities = VisitorActivity::orderBy('updated_at', 'desc')->take(20)->get();
            $avg_time = VisitorActivity::where('date', date('Y-m-d'))->avg('time_spent') ?? 0;

            // ⭐ ভিউ রিটার্ন করার সময় Wholesale ভেরিয়েবলগুলো যুক্ত করা হলো
            return view('backEnd.admin.dashboard', compact(
                'facebookActivityData', 'adMetrics', 'tiktokReports', 'dashboardData', 
                'total_order', 'total_product', 'total_customer', 'latest_order', 'latest_customer', 
                'today_delivery', 'total_delivery', 'last_week', 'last_month', 'monthly_sale', 
                'today_total_orders', 'today_total_amount', 'yesterday_total_orders', 'yesterday_total_amount', 
                'cancelled_total_orders', 'cancelled_total_amount', 'return_total_orders', 'return_total_amount', 
                'total_order_amount', 'report_data', 'topSell', 'assigin', 'quickTabs',

                // New Wholesale Variables
                'total_wholesale_order', 'total_wholesale_amount',
                'today_wholesale_orders', 'today_wholesale_amount',
                'yesterday_wholesale_orders', 'yesterday_wholesale_amount',
                'cancelled_wholesale_orders', 'cancelled_wholesale_amount',
                'return_wholesale_orders', 'return_wholesale_amount',
                // New Reseller Stats (থানা ভিত্তিক পরিসংখ্যান)
            'reseller_stats','recent_activities', 
                'avg_time'
            ));
        
        } else {
            $assigin = [];
            return view('author.dashboard', compact('assigin'));
        }
    }

// --- TikTok Reporting Logic (Updated for Dynamic Database Credentials) ---
   private function getTiktokAdMetrics($tiktokService, ?string $startDate = null, ?string $endDate = null, ?string $dateFilter = 'last_7_days'): array
    {
        // ১. ক্রেডেনশিয়াল চেক
        if (empty($this->ttAccessToken) || empty($this->ttAdvertiserId)) {
            return [['campaign_name' => 'Config Error', 'ad_name' => 'TikTok Credentials Missing in Database', 'cpm' => 'N/A', 'frequency' => '0', 'ctr' => '0%', 'link_clicks' => '0', 'cpc' => '0', 'purchase_roas' => '0']];
        }

        // ২. তারিখ সেট করা
        $currentEndDate = Carbon::now()->format('Y-m-d');
        $currentStartDate = Carbon::now()->subDays(7)->format('Y-m-d');

        if (!empty($startDate) && !empty($endDate)) {
            $currentStartDate = Carbon::parse($startDate)->format('Y-m-d');
            $currentEndDate = Carbon::parse($endDate)->format('Y-m-d');
        } else {
            switch ($dateFilter) {
                case 'today': $currentStartDate = $currentEndDate = Carbon::today()->format('Y-m-d'); break;
                case 'yesterday': $currentStartDate = $currentEndDate = Carbon::yesterday()->format('Y-m-d'); break;
                case 'this_month': $currentStartDate = Carbon::now()->startOfMonth()->format('Y-m-d'); break;
            }
        }

        try {
            // ৩. API কল করা (ডাইনামিক ক্রেডেনশিয়াল সহ)
            $rawReports = $tiktokService->getAdReport($currentStartDate, $currentEndDate, $this->ttAccessToken, $this->ttAdvertiserId);
            
            // ⭐ [গুরুত্বপূর্ণ পরিবর্তন] API থেকে এরর আসলে সেটা টেবিল-এ দেখানো ⭐
            // যদি 'code' থাকে এবং সেটা ০ না হয় (যেমন: 40001), তাহলে এরর মেসেজ রিটার্ন করবে
            if (isset($rawReports['code']) && $rawReports['code'] != 0) {
                return [[
                    'campaign_name' => 'API Error (' . $rawReports['code'] . ')',
                    'ad_name'       => $rawReports['message'] ?? 'Unknown API Error', // আসল এরর মেসেজ এখানে দেখাবে
                    'cpm'           => 'N/A',
                    'frequency'     => 'N/A',
                    'ctr'           => 'N/A',
                    'link_clicks'   => 'N/A',
                    'cpc'           => 'N/A',
                    'purchase_roas' => 'N/A',
                ]];
            }

            // ৪. ডাটা না থাকলে ফাঁকা অ্যারে রিটার্ন (মানে কোনো এরর নেই, কিন্তু কোনো অ্যাডও চলেনি)
            if (!isset($rawReports['data']['list'])) return [];

            // ৫. ডাটা থাকলে ফরম্যাট করা
            $formatted = [];
            foreach ($rawReports['data']['list'] as $report) {
                $m = $report['metrics'];
                $formatted[] = [
                    'campaign_name' => $report['dimensions']['campaign_name'] ?? 'N/A',
                    'ad_name'       => $report['dimensions']['ad_name'] ?? 'N/A',
                    'cpm'           => '$' . number_format($m['cpm'] ?? 0, 2),
                    'frequency'     => number_format($m['frequency'] ?? 1.0, 2),
                    'ctr'           => number_format($m['ctr'] ?? 0, 2) . '%',
                    'link_clicks'   => number_format($m['clicks'] ?? 0),
                    'cpc'           => '$' . number_format($m['cpc'] ?? 0, 2),
                    'purchase_roas' => number_format($m['purchase_roas'] ?? 0, 2) . 'X',
                ];
            }
            return $formatted;

        } catch (Exception $e) {
            // সিস্টেম বা কোড লেভেলের কোনো এরর হলে সেটা দেখানো
            return [['campaign_name' => 'Exception Error', 'ad_name' => $e->getMessage(), 'cpm' => 'N/A', 'frequency' => 'N/A', 'ctr' => 'N/A', 'link_clicks' => 'N/A', 'cpc' => 'N/A', 'purchase_roas' => 'N/A']];
        }
    }

    // ⭐ আপডেট করা মেথড: Facebook Graph API থেকে অ্যাড-লেভেলের ডেটা ফেচ করার জন্য
    private function getFacebookAdMetrics(?string $startDate = null, ?string $endDate = null, ?string $dateFilter = 'last_7_days'): array
    {
        // ১. ক্রেডেনশিয়ালস চেক
        if (empty($this->adAccountId) || empty($this->accessToken)) {
             Log::warning('Facebook credentials missing in database');
             return $this->getErrorMetrics('Facebook ক্রেডেনশিয়ালস ডেটাবেসে অনুপস্থিত');
        }

        // ২. ⭐ সময়ের রেঞ্জ সেট করা (Filtering Logic Added) ⭐
        
        // স্থিতিশীল ডেটার জন্য ডিফল্ট শেষ তারিখ গতকাল
        $currentEndDate = Carbon::now()->subDay()->format('Y-m-d');
        $currentStartDate = Carbon::now()->subDays(7)->format('Y-m-d'); // ডিফল্ট শুরু তারিখ

        if (!empty($startDate) && !empty($endDate)) {
            // কাস্টম ডেট রেঞ্জ
            $currentStartDate = Carbon::parse($startDate)->format('Y-m-d');
            $currentEndDate = Carbon::parse($endDate)->format('Y-m-d');
            
        } else {
            // প্রি-ডিফাইন্ড ফিল্টার বা ডিফল্ট (Last 7 Days)
            switch ($dateFilter) {
                case 'today':
                    $currentStartDate = Carbon::today()->format('Y-m-d');
                    $currentEndDate = Carbon::today()->format('Y-m-d');
                    break;
                case 'yesterday':
                    $currentStartDate = Carbon::yesterday()->format('Y-m-d');
                    $currentEndDate = Carbon::yesterday()->format('Y-m-d');
                    break;
                case 'last_3_days':
                    $currentStartDate = Carbon::now()->subDays(3)->format('Y-m-d');
                    $currentEndDate = Carbon::now()->subDay()->format('Y-m-d');
                    break;
                case 'last_14_days':
                    $currentStartDate = Carbon::now()->subDays(14)->format('Y-m-d');
                    $currentEndDate = Carbon::now()->subDay()->format('Y-m-d');
                    break;
                case 'last_30_days':
                    $currentStartDate = Carbon::now()->subDays(30)->format('Y-m-d');
                    $currentEndDate = Carbon::now()->subDay()->format('Y-m-d');
                    break;
                case 'this_month':
                    $currentStartDate = Carbon::now()->startOfMonth()->format('Y-m-d');
                    $currentEndDate = Carbon::now()->subDay()->format('Y-m-d'); // এই মাসের শুরু থেকে গতকাল পর্যন্ত
                    break;
                case 'last_month':
                    $currentStartDate = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
                    $currentEndDate = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
                    break;
                case 'last_7_days':
                default:
                    $currentStartDate = Carbon::now()->subDays(7)->format('Y-m-d');
                    $currentEndDate = Carbon::now()->subDay()->format('Y-m-d');
                    break;
            }
        }
        
        // নিরাপত্তা যাচাই: শুরু তারিখ শেষ তারিখের পরে কি না
        if (Carbon::parse($currentStartDate)->greaterThan(Carbon::parse($currentEndDate))) {
             // যদি একক দিন না হয় (today/yesterday), তবে ত্রুটি দেখান
             if ($dateFilter !== 'today' && $dateFilter !== 'yesterday') {
                  return $this->getErrorMetrics('তারিখ নির্বাচনের ত্রুটি: শুরুর তারিখ শেষের তারিখের আগে হতে হবে।');
             }
        }


        // ৩. অ্যাড-লেভেলের ডেটা ফেচ করা
        $currentInsights = $this->fetchInsights($currentStartDate, $currentEndDate);

        if (empty($currentInsights) || isset($currentInsights['error_message'])) {
            return $this->getErrorMetrics($currentInsights['error_message'] ?? 'ফেসবুক থেকে অ্যাড ডেটা পাওয়া যায়নি বা API রিকোয়েস্ট ব্যর্থ হয়েছে');
        }
        
        // ৪. ডেটা ফরম্যাটিং 
        return $this->formatMetricsForTable($currentInsights);
    }
    
    // ⭐ সহায়ক ফাংশন: Facebook API তে রিকোয়েস্ট পাঠানোর জন্য (লেভেল: ad এবং campaign_name সহ)
    private function fetchInsights(string $sinceDate, string $untilDate): array
    {
        $params = [
            'access_token' => $this->accessToken,
            'time_range' => json_encode([
                'since' => $sinceDate,
                'until' => $untilDate
            ]),
            'level' => 'ad', // ⭐ লেভেল অ্যাড করা হয়েছে
            // প্রয়োজনীয় মেট্রিকগুলো
            'fields' => 'campaign_name,ad_name,cpm,frequency,ctr,inline_link_clicks,spend,purchase_roas', // ⭐ campaign_name এবং ad_name যোগ করা হয়েছে
            'limit' => 50, // সর্বাধিক ৫০টি অ্যাড দেখানোর জন্য
            // 'time_increment' => 'all_days', // অ্যাড লেভেলের জন্য এটি অপ্রয়োজনীয়
        ];

        try {
            // Ad Account ID এর আগে act_ যোগ করা হচ্ছে
            $url = $this->apiBaseUrl . 'act_' . $this->adAccountId . '/insights';
            $response = Http::timeout(30)->get($url, $params);
            
            if ($response->successful()) {
                $data = $response->json();
                return $data['data'] ?? []; // ⭐ এখন এটি একটি ডেটা অ্যারে রিটার্ন করবে (all ads)
            }
            
            // API ত্রুটি লগ করা
            Log::error("Facebook API Failed ({$sinceDate} to {$untilDate}): " . $response->body());
            
            // যদি Facebook API থেকে কোনো error message আসে, তা Exception এ ধরা
            if ($response->json() && isset($response->json()['error']['message'])) {
                throw new Exception("FB API Error: " . $response->json()['error']['message']);
            }
            
            return [];

        } catch (Exception $e) {
            $errorMessage = $e->getMessage();
            Log::error("Facebook API Exception: " . $errorMessage);
            // যদি API রিকোয়েস্ট লেভেলে কোনো ত্রুটি হয়, সেটি ধরে return করা
            return ['error_message' => $errorMessage]; 
        }
    }
    
    // ⭐ সহায়ক ফাংশন: অ্যাড-লেভেলের ডেটা প্রসেস করে টেবিল উপযোগী অ্যারে তৈরি করা
    private function formatMetricsForTable(array $currentInsights): array
    {
        // যদি currentInsights অ্যারেতে fetchInsights ফাংশন থেকে কোনো error_message ফিরে আসে
        if (isset($currentInsights['error_message'])) {
             return $this->getErrorMetrics($currentInsights['error_message']);
        }
        
        $adMetrics = [];
        
        // Insights এখন একটি অ্যারে, যেখানে প্রতিটি এলিমেন্ট একটি অ্যাড-এর ডেটা
        foreach ($currentInsights as $ad) {
            
            $ad_name = $ad['ad_name'] ?? 'N/A';
            $campaign_name = $ad['campaign_name'] ?? 'N/A';
            $clicks = $ad['inline_link_clicks'] ?? 0;
            $spend = $ad['spend'] ?? 0;

            // CPC (Cost Per Link Click) ক্যালকুলেশন
            $cpc = $clicks > 0 ? $spend / $clicks : 0;
            
            $adMetrics[] = [
                'campaign_name' => $campaign_name,
                'ad_name' => $ad_name,
                'cpm' => '$' . number_format($ad['cpm'] ?? 0, 2),
                'frequency' => number_format($ad['frequency'] ?? 0, 2),
                'ctr' => number_format($ad['ctr'] ?? 0, 2) . '%',
                'link_clicks' => number_format($clicks),
                'cpc' => '$' . number_format($cpc, 2),
                'purchase_roas' => number_format($ad['purchase_roas'] ?? 0, 2) . 'X',
            ];
        }
        
        return $adMetrics;
    }
    
    // ⭐ সহায়ক ফাংশন: API ব্যর্থ হলে ত্রুটি বার্তা দেখানোর জন্য (Ad-Level Structure)
    private function getErrorMetrics(string $error): array
    {
        return [
            [
                'campaign_name' => 'API Error',
                'ad_name' => 'Status',
                'cpm' => $error,
                'frequency' => 'N/A',
                'ctr' => 'N/A',
                'link_clicks' => 'N/A',
                'cpc' => 'N/A',
                'purchase_roas' => 'N/A',
            ],
        ];
    }
    
    // ... (বাকি সকল মেথড অপরিবর্তিত)
    protected function getSellReportData(Request $request, $defaultFilter = 'daily', $applyDateFilter = null, $hasDateFilter = false, $paginate = false, $statusMap = []) {
        
        if (empty($statusMap)) {
            $statusMap = OrderStatus::pluck('id', 'name')->toArray(); 
        }

        $DELIVERED_ID = $statusMap['Delivered'] ?? 10; 
        $CANCELLED_ID = $statusMap['Cancelled'] ?? 7;
        $RETURNED_ID = $statusMap['Returned'] ?? ($statusMap['Return'] ?? 11);
        $SHIPPED_ID = $statusMap['Shipped'] ?? 12;
        $HOLD_ID = $statusMap['On Hold'] ?? 4;
        $PENDING_ID = $statusMap['Pending'] ?? 1;

        $filter = $request->input('report_duration', $defaultFilter); 

        $query = DB::table('orders as o')
            ->join('order_details as od', 'o.id', '=', 'od.order_id')
            
            ->select(
                'od.product_id as SKU', 
                DB::raw('COUNT(o.id) as OrderCount'),
                DB::raw('SUM(CASE WHEN o.order_status = ' . $SHIPPED_ID . ' THEN 1 ELSE 0 END) as Shipped'),
                DB::raw('SUM(CASE WHEN o.order_status = ' . $CANCELLED_ID . ' THEN 1 ELSE 0 END) as Cancelled'),
                DB::raw('SUM(CASE WHEN o.order_status = ' . $RETURNED_ID . ' THEN 1 ELSE 0 END) as Returned'),
                DB::raw('SUM(CASE WHEN o.order_status = ' . $HOLD_ID . ' THEN 1 ELSE 0 END) as Hold'),
                DB::raw('SUM(CASE WHEN o.order_status = ' . $PENDING_ID . ' THEN 1 ELSE 0 END) as Pending'),
                DB::raw('SUM(CASE WHEN o.order_status = ' . $DELIVERED_ID . ' THEN 1 ELSE 0 END) as Delivered')
            )
            ->groupBy('od.product_id');
        
        foreach ($statusMap as $name => $id) {
            if (!in_array($id, [$DELIVERED_ID, $CANCELLED_ID, $RETURNED_ID, $SHIPPED_ID, $HOLD_ID, $PENDING_ID])) {
                 $safeName = str_replace([' ', '-'], '', $name); 
                 $query->selectRaw("SUM(CASE WHEN o.order_status = {$id} THEN 1 ELSE 0 END) as {$safeName}");
            }
        }
        
        if ($hasDateFilter && $applyDateFilter) {
            $query->when(true, $applyDateFilter); 
        } else {
            $col = 'o.created_at';
            if ($filter === 'daily') {
                $query->whereDate($col, Carbon::today());
            } elseif ($filter === 'weekly') {
                $query->whereBetween($col, [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            } elseif ($filter === 'monthly') {
                $query->whereMonth($col, Carbon::now()->month)->whereYear($col, Carbon::now()->year);
            }
        }
        
        if ($paginate) {
            return $query->paginate(10)->withQueryString();
        }

        return $query->get();
    }
    
    protected function getTopSellReportData(Request $request, $defaultFilter = 'daily', $applyDateFilter = null, $hasDateFilter = false) { 
        
        $filter = $request->input('report_duration', $request->input('topsell', $defaultFilter));

        $query = DB::table('orders as o')
             ->join('order_details as od', 'o.id', '=', 'od.order_id')
             ->join('products as p', 'p.id', '=', 'od.product_id')
             ->join(DB::raw('(SELECT product_id, MIN(image) as image FROM productimages GROUP BY product_id) as pi'), 'p.id', '=', 'pi.product_id')
             ->select(
                 'p.name as product_name', 'pi.image', 'od.product_id as SKU',
                 DB::raw('SUM(od.qty) as PurchaseQty'),
                 DB::raw('SUM(od.qty * od.sale_price) as Revenue')
             )
             ->groupBy('od.product_id', 'p.name', 'pi.image')
             ->orderByDesc('Revenue');

          if ($hasDateFilter && $applyDateFilter) {
             $query->when(true, $applyDateFilter); 
          } else {
             $col = 'o.created_at';
             if ($filter === 'daily') {
                 $query->whereDate($col, Carbon::today());
             } elseif ($filter === 'weekly') {
                 $query->whereBetween($col, [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
             } elseif ($filter === 'monthly') {
                 $query->whereMonth($col, Carbon::now()->month)->whereYear($col, Carbon::now()->year);
             }
          }
          
        return $query->get();
    }

    public function topSellReport(Request $request){
        
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $dateFilter = $request->input('date_filter');

        $hasDateFilter = (!empty($startDate) && !empty($endDate)) || (!empty($dateFilter) && $dateFilter !== 'lifetime');
        
        $applyDateFilter = function ($query) use ($startDate, $endDate, $dateFilter) {
             
             if (!empty($startDate) && !empty($endDate)) { 
                 try {
                     $start = Carbon::parse($startDate)->startOfDay();
                     $end = Carbon::parse($endDate)->endOfDay();
                 } catch (\Exception $e) {
                      $start = Carbon::now()->subMonths(3);
                      $end = Carbon::now();
                 }
                 
                 $query->where(function ($q) use ($start, $end) {
                      $q->whereBetween('o.created_at', [$start, $end])
                       ->orWhereBetween('o.updated_at', [$start, $end]);
                 });
             } 
             elseif (!empty($dateFilter) && $dateFilter !== 'lifetime') { 
                 $col = 'o.updated_at';
                 if ($dateFilter === 'today') {
                      $query->whereDate($col, Carbon::today());
                 } elseif ($dateFilter === 'this_week') {
                      $query->whereBetween($col, [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                 } elseif ($dateFilter === 'this_month') {
                      $query->whereBetween($col, [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
                 }
             }
        };
        
        $topSell = $this->getTopSellReportData($request, $request->input('topsell'), $applyDateFilter, $hasDateFilter);
        return response()->json(compact('topSell'));
    }

    public function sellReport(Request $request){
        
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $dateFilter = $request->input('date_filter');
        
        $hasDateFilter = (!empty($startDate) && !empty($endDate)) || (!empty($dateFilter) && $dateFilter !== 'lifetime'); 
        
        $applyDateFilter = function ($query) use ($startDate, $endDate, $dateFilter) {
             
             if (!empty($startDate) && !empty($endDate)) { 
                 try {
                     $start = Carbon::parse($startDate)->startOfDay();
                     $end = Carbon::parse($endDate)->endOfDay();
                 } catch (\Exception $e) {
                      $start = Carbon::now()->subMonths(3);
                      $end = Carbon::now();
                 }
                 
                 $query->where(function ($q) use ($start, $end) {
                      $q->whereBetween('o.created_at', [$start, $end])
                       ->orWhereBetween('o.updated_at', [$start, $end]);
                 });
             } 
             elseif (!empty($dateFilter) && $dateFilter !== 'lifetime') { 
                 $col = 'o.updated_at';
                 if ($dateFilter === 'today') {
                      $query->whereDate($col, Carbon::today());
                 } elseif ($dateFilter === 'this_week') {
                      $query->whereBetween($col, [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                 } elseif ($dateFilter === 'this_month') {
                      $query->whereBetween($col, [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
                 }
             }
        };
        
        $sellReport = $this->getSellReportData($request, $request->input('report_duration'), $applyDateFilter, $hasDateFilter, false);
        return response()->json(compact('sellReport'));
    }


    public function locked()
    {
        Session::put('locked', true);
        return view('backEnd.auth.locked');
    }
    // DashboardController.php

public function moderator_report(Request $request)
{
    // ১. ইউজার নির্ধারণ (লজিক আগের মতোই)
    if ($request->user_id && auth()->user()->user_type == 'admin') {
        $user = \App\Models\User::find($request->user_id);
        if (!$user) return response()->json(['error' => 'User not found'], 404);
        $target_user_id = $user->id;
    } else {
        $user = auth()->user();
        $target_user_id = auth()->id();
    }

    // ২. কুয়েরি বিল্ডার (Query Builder)
    $query = \App\Models\Order::where('assign_user_id', $target_user_id);

    // ডেট ফিল্টার
    if ($request->start_date && $request->end_date) {
        $query->whereBetween('updated_at', [
            $request->start_date . ' 00:00:00',
            $request->end_date . ' 23:59:59'
        ]);
    }

    // ৩. ডাটা ক্যালকুলেশন
    $total_orders = (clone $query)->count();
    $success_orders = (clone $query)->where('order_status', 10)->count(); // Delivered ID 10
    $return_orders = (clone $query)->where('order_status', 11)->count();  // Returned ID 11
    $cancel_orders = (clone $query)->where('order_status', 7)->count();   // Cancelled ID 7

    $success_rate = $total_orders > 0 ? ($success_orders / $total_orders) * 100 : 0;

    // ৪. AJAX চেক
    if ($request->ajax()) {
        return response()->json([
            'status' => 'success',
            'user_name' => $user->name,
            'balance' => $user->balance,
            'total_orders' => $total_orders,
            'success_orders' => $success_orders,
            'return_orders' => $return_orders,
            'cancel_orders' => $cancel_orders,
            'success_rate' => number_format($success_rate, 2)
        ]);
    }

    // ৫. সাধারণ ভিউ রিটার্ন (প্রথমবার লোড হওয়ার জন্য)
    $moderators = [];
    if (auth()->user()->user_type == 'admin') {
        $moderators = \App\Models\User::where('status', 1)->get();
    }

    return view('backEnd.users.my_report', compact(
        'user', 'total_orders', 'success_orders', 'return_orders', 
        'cancel_orders', 'success_rate', 'moderators'
    ));
}
}