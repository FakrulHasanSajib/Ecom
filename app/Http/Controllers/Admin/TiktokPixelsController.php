<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TiktokPixel; 
use App\Services\TiktokService; 
use Toastr;
use Carbon\Carbon;

class TiktokPixelsController extends Controller 
{
    /**
     * পিক্সেল লিস্ট এবং টিকটক রিপোর্ট ড্যাশবোর্ড দেখানোর জন্য।
     */
   public function index(Request $request, TiktokService $tiktokService)
{
    // ১. ডাটাবেস থেকে পিক্সেল লিস্ট নিন
    $data = TiktokPixel::orderBy('id', 'DESC')->get();
    
    // ২. তারিখ নির্ধারণ (ডিফল্ট গত ৩০ দিন)
    $startDate = Carbon::now()->subDays(30)->format('Y-m-d');
    $endDate = Carbon::now()->format('Y-m-d');

    // ৩. শুরুতে reports ভেরিয়েবলটি ফাঁকা অ্যারে হিসেবে ডিক্লেয়ার করুন
    // এটি না করলে pixel না থাকলে বা API ফেল করলে 'Undefined variable' এরর দিবে
    $reports = []; 

    // ৪. অ্যাক্টিভ পিক্সেল খুঁজে বের করা
    $pixel = \App\Models\TiktokPixel::where('status', 1)->first();

    if ($pixel) {
        // ৫. সার্ভিস ব্যবহার করে রিপোর্ট আনা (৪টি প্যারামিটার পাঠানো হচ্ছে)
        $apiData = $tiktokService->getAdReport(
            $startDate, 
            $endDate, 
            $pixel->access_token, 
            $pixel->ad_account_id 
        );

        // ৬. এপিআই রেসপন্স থেকে মূল ডাটা লিস্ট বের করা
        // টিকটক সাধারণত ['data']['list'] এর মধ্যে মেইন ডাটা দেয়
        if (isset($apiData['data']['list'])) {
            $reports = $apiData['data']['list'];
        }
    }

    // ৭. ভিউতে ডাটা পাঠানো
    return view('backEnd.tiktok_pixels.index', compact('data', 'reports', 'startDate', 'endDate'));
}

    public function create()
    {
        return view('backEnd.tiktok_pixels.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'pixel_id' => 'required', 
            'ad_account_id' => 'required|string',
            'access_token' => 'required|string', 
            'status' => 'required',
        ]);

        // যদি নতুন পিক্সেল Active (1) হিসেবে সেভ করা হয়, তবে আগের সব পিক্সেল ইনএকটিভ করে দিন
        if($request->status == 1){
            TiktokPixel::where('status', 1)->update(['status' => 0]);
        }

        $input = $request->all();
        TiktokPixel::create($input);

        Toastr::success('Success', 'TikTok Pixel inserted successfully');
        return redirect()->route('tiktok_pixels.index');
    }
    
    public function edit($id)
    {
        $edit_data = TiktokPixel::find($id);
        return view('backEnd.tiktok_pixels.edit', compact('edit_data'));
    }
    
    public function update(Request $request)
    {
        $this->validate($request, [
            'pixel_id' => 'required', 
            'ad_account_id' => 'required|string',
            'access_token' => 'required|string', 
        ]);

        $update_data = TiktokPixel::find($request->id);
        
        // যদি এই পিক্সেলটি একটিভ করা হয়, তবে অন্যগুলো ইনএকটিভ করুন
        if($request->status == 1){
            TiktokPixel::where('id', '!=', $request->id)->update(['status' => 0]);
        }

        $input = $request->all();
        $input['status'] = $request->status ? 1 : 0;
        $update_data->update($input);

        Toastr::success('Success', 'TikTok Pixel updated successfully');
        return redirect()->route('tiktok_pixels.index');
    }
 
    public function inactive(Request $request)
    {
        $inactive = TiktokPixel::find($request->hidden_id);
        $inactive->status = 0;
        $inactive->save();
        Toastr::success('Success', 'TikTok Pixel inactivated successfully');
        return redirect()->back();
    }

    public function active(Request $request)
    {
        // একটি একটিভ করার আগে বাকি সব ইনএকটিভ করে দিন
        TiktokPixel::where('status', 1)->update(['status' => 0]);

        $active = TiktokPixel::find($request->hidden_id);
        $active->status = 1;
        $active->save();
        Toastr::success('Success', 'TikTok Pixel activated successfully');
        return redirect()->back();
    }

    public function destroy(Request $request)
    {
        $delete_data = TiktokPixel::find($request->hidden_id);
        $delete_data->delete();
        Toastr::success('Success', 'TikTok Pixel deleted successfully');
        return redirect()->back();
    }
}