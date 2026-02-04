<?php

namespace App\Http\Controllers\Dealer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RecruitmentCampaign;
use Illuminate\Support\Str;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\File;
use Auth;

class DealerRecruitmentController extends Controller
{
    public function index() {
        // ডিলার শুধু নিজের ক্যাম্পেইন দেখবে
        $dealer_id = Auth::guard('dealer')->id();
        $data = RecruitmentCampaign::where('referral_code', $dealer_id)->latest()->get();
        return view('backEnd.dealer.panel.recruitment.index', compact('data'));
    }

    public function create() {
        return view('backEnd.dealer.panel.recruitment.create');
    }

    public function store(Request $request) {
        $request->validate([
            'title' => 'required', 
            'slug' => 'required|unique:recruitment_campaigns,slug'
        ]);

        $data = new RecruitmentCampaign();
        
        // Basic Info
        $data->title = $request->title;
        $data->slug = Str::slug($request->slug);
        $data->header_text = $request->header_text; // নতুন ফিল্ড
        $data->description = $request->description;
        
        // Videos (Updated)
        $data->agent_video_id = $request->agent_video_id; // নতুন ফিল্ড
        $data->khadem_video_id = $request->khadem_video_id; // নতুন ফিল্ড
        
        // Custom Links (Updated)
        $data->login_url = $request->login_url; // নতুন ফিল্ড
        $data->register_url = $request->register_url; // নতুন ফিল্ড

        // Images (From Media Manager Path)
        // মিডিয়া ম্যানেজার থেকে সরাসরি পাথ আসছে, তাই ফাইল আপলোডের কোড দরকার নেই
        $data->image_one = $request->image_one; 
        $data->image_two = $request->image_two; 
        $data->image_three = $request->image_three; 

        // Creator Info
        $data->creator_type = 'dealer';
        $data->creator_id = Auth::guard('dealer')->id();
        $data->referral_code = Auth::guard('dealer')->id(); 
        
        // Status
        $data->status = 1;

        $data->save();
        
        Toastr::success('Campaign Created Successfully');
        return redirect()->route('dealer.recruitment.index');
    }

    // Edit Page Show
    public function edit($id)
    {
        $dealer_id = Auth::guard('dealer')->id();
        
        // চেক করা হচ্ছে এই আইডিটি এই ডিলারের কি না
        $edit_data = RecruitmentCampaign::where('id', $id)
                    ->where('creator_type', 'dealer')
                    ->where('creator_id', $dealer_id)
                    ->first();

        if(!$edit_data){
            Toastr::error('Error', 'Invalid Request');
            return redirect()->back();
        }

        return view('backEnd.dealer.panel.recruitment.edit', compact('edit_data'));
    }

    // Update Logic
    public function update(Request $request)
    {
        $dealer_id = Auth::guard('dealer')->id();
        
        $update_data = RecruitmentCampaign::where('id', $request->hidden_id)
                    ->where('creator_type', 'dealer')
                    ->where('creator_id', $dealer_id)
                    ->first();

        if(!$update_data){
            Toastr::error('Error', 'Unauthorized Access');
            return redirect()->back();
        }

        $request->validate([
            'title' => 'required',
            'slug' => 'required|unique:recruitment_campaigns,slug,'.$request->hidden_id,
        ]);

        // Basic Info Update
        $update_data->title = $request->title;
        $update_data->slug = Str::slug($request->slug);
        $update_data->header_text = $request->header_text;
        $update_data->description = $request->description;

        // Videos Update
        $update_data->agent_video_id = $request->agent_video_id;
        $update_data->khadem_video_id = $request->khadem_video_id;

        // Links Update
        $update_data->login_url = $request->login_url;
        $update_data->register_url = $request->register_url;

        // Images Update (Direct Path Update)
        $update_data->image_one = $request->image_one;
        $update_data->image_two = $request->image_two;
        $update_data->image_three = $request->image_three;

        $update_data->status = $request->status;

        $update_data->save();
        
        Toastr::success('Success', 'Campaign Updated Successfully');
        return redirect()->route('dealer.recruitment.index');
    }

    // Delete Logic
    public function destroy(Request $request)
    {
        $dealer_id = Auth::guard('dealer')->id();
        
        $delete_data = RecruitmentCampaign::where('id', $request->hidden_id)
                    ->where('creator_type', 'dealer')
                    ->where('creator_id', $dealer_id)
                    ->first();

        if ($delete_data) {
            // যদি ম্যানুয়াল ফাইল রিমুভ করতে চান (যদি ফাইলগুলো পাবলিক ফোল্ডারে থাকে)
            // তবে মিডিয়া ম্যানেজার ব্যবহার করলে সাধারণত ফাইল ডিলিট করা হয় না, শুধু রেকর্ড মুছে ফেলা হয়
            // if (File::exists($delete_data->image_one)) { File::delete($delete_data->image_one); }
            
            $delete_data->delete();
            Toastr::success('Success', 'Campaign Deleted Successfully');
        } else {
            Toastr::error('Error', 'Unauthorized Action');
        }
        
        return redirect()->back();
    }
}