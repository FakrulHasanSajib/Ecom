<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RecruitmentCampaign;
use App\Models\Dealer;
use Illuminate\Support\Str;
use Brian2694\Toastr\Facades\Toastr;
use Auth;
use File; // ফাইল ডিলিট করার জন্য এটি প্রয়োজন

class RecruitmentCampaignController extends Controller
{
    public function index() {
        $data = RecruitmentCampaign::latest()->get();
        return view('backEnd.admin.recruitment.index', compact('data'));
    }

    public function create() {
        // ডিলারদের লিস্ট পাঠানো হচ্ছে যাতে অ্যাডমিন সিলেক্ট করতে পারে
        $dealers = Dealer::where('status', 'active')->select('id', 'name', 'store_name')->get();
        return view('backEnd.admin.recruitment.create', compact('dealers'));
    }

    public function store(Request $request) {
        $request->validate([
            'title' => 'required', 
            'slug' => 'required|unique:recruitment_campaigns,slug'
        ]);

        $data = new RecruitmentCampaign();
        $data->title = $request->title;
        $data->slug = Str::slug($request->slug);
        $data->description = $request->description;
        $data->video_url = $request->video_url;
        $data->creator_type = 'admin';
        $data->creator_id = Auth::id();

        // যদি অ্যাডমিন কোনো ডিলার সিলেক্ট করে দেয়
        if ($request->assign_dealer_id) {
            $data->referral_code = $request->assign_dealer_id;
        }

        // মিডিয়া ম্যানেজার/ফটো গ্যালারি থেকে আসা ব্যানার URL সেভ করা
        $data->banner = $request->banner;

        // যদি ফাইল আপলোড সাপোর্ট রাখতে চান (অপশনাল)
        if ($request->hasFile('banner_file')) {
            $image = $request->file('banner_file');
            $name = time().'-'.$image->getClientOriginalName();
            $path = 'public/uploads/campaign/';
            $image->move($path, $name);
            $data->banner = $path.$name;
        }

        $data->save();
        Toastr::success('Campaign Created Successfully');
        return redirect()->route('admin.recruitment.index');
    }

    // Edit Page: Undefined variable $dealers এরর সমাধানের জন্য আপডেট করা হয়েছে
    public function edit($id)
    {
        $edit_data = RecruitmentCampaign::findOrFail($id);
        // ডিলার লিস্ট তুলে আনা হয়েছে
        $dealers = Dealer::where('status', 'active')->select('id', 'name', 'store_name')->get();
        
        return view('backEnd.admin.recruitment.edit', compact('edit_data', 'dealers'));
    }

    // Update Logic: ফটো গ্যালারি সাপোর্ট সহ
    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'slug' => 'required|unique:recruitment_campaigns,slug,'.$request->hidden_id,
        ]);

        $update_data = RecruitmentCampaign::findOrFail($request->hidden_id);
        $update_data->title = $request->title;
        $update_data->slug = Str::slug($request->slug);
        $update_data->description = $request->description;
        $update_data->video_url = $request->video_url;
        $update_data->status = $request->status;

        // ডিলার এসাইন আপডেট
        if ($request->assign_dealer_id) {
            $update_data->referral_code = $request->assign_dealer_id;
        }

        // মিডিয়া ম্যানেজার থেকে আসা ব্যানার URL আপডেট করা
        $update_data->banner = $request->banner;

        $update_data->save();
        Toastr::success('Campaign Updated Successfully');
        return redirect()->route('admin.recruitment.index');
    }

    // Delete Logic
    public function destroy(Request $request)
    {
        $delete_data = RecruitmentCampaign::findOrFail($request->hidden_id);
        
        // যদি এটি লোকাল ফাইল হয় তবে ডিলিট করবে
        if ($delete_data->banner && File::exists($delete_data->banner)) {
            File::delete($delete_data->banner);
        }
        
        $delete_data->delete();
        Toastr::success('Campaign Deleted Successfully');
        return redirect()->back();
    }
}