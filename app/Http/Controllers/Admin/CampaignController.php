<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\CampaignReview;
use App\Models\Campaign;
use App\Models\Bookimage;
use Image;
use Toastr;
use Str;
use File;

class CampaignController extends Controller
{
    public function index(Request $request)
    {
        $show_data = Campaign::orderBy('id', 'DESC')->get();
        return view('backEnd.campaign.index', compact('show_data'));
    }
    public function create(Request $request)
    {
        $products = Product::where(['status' => 1])->select('id', 'name', 'status')->get();
        if ($request->has('theme') && $request->theme == 9) {
        return view('backEnd.campaign.create_nine', compact('products'));
    }
        return view('backEnd.campaign.create', compact('products'));
    }
    public function createSeven()
    {
        $products = Product::where(['status' => 1])->select('id', 'name', 'status')->get();
        return view('backEnd.campaign.create_seven', compact('products'));
    }

    public function landingPageManage()
    {
        return view('backEnd.campaign.manage_landing_page');
    }

public function store(Request $request)
    {
        $this->validate($request, [
            'short_description' => 'required',
            'description' => 'required',
            'name' => 'required',
            'slug' => 'required|unique:campaigns,slug',
            'status' => 'required',
        ]);

        $campaign = new Campaign;

        // =========================================================
        // ১. সিঙ্গেল ইমেজ হ্যান্ডলিং (Hybrid: File Upload OR Media Manager String)
        // =========================================================

        // --- Image One ---
        if ($request->hasFile('image_one')) {
            $image1 = $request->file('image_one');
            $name1 = time() . '-' . $image1->getClientOriginalName();
            $name1 = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp', $name1);
            $name1 = strtolower(preg_replace('/\s+/', '-', $name1));
            $uploadpath1 = 'public/uploads/campaign/';
            $image1Url = $uploadpath1 . $name1;
            $img1 = Image::make($image1->getRealPath());
            $img1->encode('webp', 90);
            $img1->resize(800, null, function ($constraint) { $constraint->aspectRatio(); });
            $img1->save($image1Url);
            $campaign->image_one = $image1Url;
        } else {
            $campaign->image_one = $request->image_one;
        }

        // --- Image Two ---
        if ($request->hasFile('image_two')) {
            $image2 = $request->file('image_two');
            $name2 = time() . '-' . $image2->getClientOriginalName();
            $name2 = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp', $name2);
            $name2 = strtolower(preg_replace('/\s+/', '-', $name2));
            $uploadpath2 = 'public/uploads/campaign/';
            $image2Url = $uploadpath2 . $name2;
            $img2 = Image::make($image2->getRealPath());
            $img2->encode('webp', 90);
            $img2->resize(800, null, function ($constraint) { $constraint->aspectRatio(); });
            $img2->save($image2Url);
            $campaign->image_two = $image2Url;
        } else {
            $campaign->image_two = $request->image_two;
        }

        // --- Image Three ---
        if ($request->hasFile('image_three')) {
            $image3 = $request->file('image_three');
            $name3 = time() . '-' . $image3->getClientOriginalName();
            $name3 = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp', $name3);
            $name3 = strtolower(preg_replace('/\s+/', '-', $name3));
            $uploadpath3 = 'public/uploads/campaign/';
            $image3Url = $uploadpath3 . $name3;
            $img3 = Image::make($image3->getRealPath());
            $img3->encode('webp', 90);
            $img3->resize(800, null, function ($constraint) { $constraint->aspectRatio(); });
            $img3->save($image3Url);
            $campaign->image_three = $image3Url;
        } else {
            $campaign->image_three = $request->image_three;
        }

        // --- Image Four ---
        if ($request->hasFile('image_four')) {
            $image4 = $request->file('image_four');
            $name4 = time() . '-four-' . $image4->getClientOriginalName();
            $name4 = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp', $name4);
            $name4 = strtolower(preg_replace('/\s+/', '-', $name4));
            $uploadpath4 = 'public/uploads/campaign/';
            $image4Url = $uploadpath4 . $name4;
            $img4 = Image::make($image4->getRealPath());
            $img4->encode('webp', 90);
            $img4->resize(800, null, function ($constraint) { $constraint->aspectRatio(); });
            $img4->save($image4Url);
            $campaign->image_four = $image4Url;
        } else {
            $campaign->image_four = $request->image_four;
        }

        // --- Banner ---
        if ($request->hasFile('banner')) {
            $banner = $request->file('banner');
            $nameBanner = time() . '-' . $banner->getClientOriginalName();
            $nameBanner = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp', $nameBanner);
            $nameBanner = strtolower(preg_replace('/\s+/', '-', $nameBanner));
            $uploadpathBanner = 'public/uploads/campaign/banner/';
            $imageBannerUrl = $uploadpathBanner . $nameBanner;
            $imgBanner = Image::make($banner->getRealPath());
            $imgBanner->encode('webp', 90);
            $imgBanner->resize(800, null, function ($constraint) { $constraint->aspectRatio(); });
            $imgBanner->save($imageBannerUrl);
            $campaign->banner = $imageBannerUrl;
        } else {
            $campaign->banner = $request->banner;
        }

        // --- Image Section ---
        if ($request->hasFile('image_section')) {
            $image_section = $request->file('image_section');
            $nameSection = time() . '-' . $image_section->getClientOriginalName();
            $nameSection = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp', $nameSection);
            $nameSection = strtolower(preg_replace('/\s+/', '-', $nameSection));
            $uploadpathSection = 'public/uploads/campaign/banner/';
            $imageSectionUrl = $uploadpathSection . $nameSection;
            $imgSection = Image::make($image_section->getRealPath());
            $imgSection->encode('webp', 90);
            $imgSection->resize(800, null, function ($constraint) { $constraint->aspectRatio(); });
            $imgSection->save($imageSectionUrl);
            $campaign->image_section = $imageSectionUrl;
        } else {
            $campaign->image_section = $request->image_section;
        }

        // =========================================================
        // ২. টেস্টমোনিয়ালস (Testimonials)
        // =========================================================
       $testimonials_data = [];
        if($request->testimonials){
            foreach($request->testimonials as $key => $item){
                $tData = [
                    'type' => $item['type'],
                    'content' => $item['content'] ?? null,
                ];

                if($item['type'] == 'image'){
                    if(isset($request->file('testimonials')[$key]['image'])){
                        $tImage = $request->file('testimonials')[$key]['image'];
                        $tName = time() . '_review_' . $key . '.' . $tImage->getClientOriginalExtension();
                        $tPath = 'public/uploads/campaign/reviews/';
                        
                        if(!File::exists($tPath)) {
                            File::makeDirectory($tPath, 0777, true, true);
                        }
                        $tImage->move($tPath, $tName);
                        $tData['image'] = $tPath . $tName;
                    } 
                    elseif(isset($item['image']) && is_string($item['image'])){
                        $tData['image'] = $item['image'];
                    }
                }
                $testimonials_data[] = $tData;
            }
            $campaign->testimonials = json_encode($testimonials_data, JSON_UNESCAPED_UNICODE);
        }

        // =========================================================
        // ৩. বেসিক ডাটা সেভ
        // =========================================================
        $productId = $request->product_id;
        $encodedProductId = json_encode($productId);
        
        $campaign->name = $request->name;
        $campaign->slug = strtolower(Str::slug($request->slug));
        $campaign->banner_title = $request->banner_title;
        $campaign->video = $request->video;
        $campaign->theme_id = $request->theme_id;
        $campaign->product_id = $encodedProductId;
        $campaign->short_description = $request->short_description;
        $campaign->description = $request->description;
        $campaign->section_title = $request->section_title;
        $campaign->review = $request->review ?? 5;
        $campaign->status = $request->status;
        $campaign->primary_color = $request->primary_color;

        $campaign->save();

        // =========================================================
        // ৪. রিভিউ ইমেজ (Review Images) - FIXED
        // =========================================================
        
        // A. মিডিয়া ম্যানেজার লিংক (Media Manager String URLs)
        if ($request->image && is_array($request->image)) {
            foreach ($request->image as $imgItem) {
                // যদি স্ট্রিং হয় (মানে মিডিয়া ম্যানেজার লিংক)
                if (is_string($imgItem)) {
                    $pimage = new CampaignReview();
                    $pimage->campaign_id = $campaign->id;
                    $pimage->image = $imgItem;
                    $pimage->save();
                }
            }
        }

        // B. ফাইল আপলোড (Legacy File Upload)
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $image) {
                $name = time() . '-' . $image->getClientOriginalName();
                $name = strtolower(preg_replace('/\s+/', '-', $name));
                $uploadPath = 'public/uploads/campaign/';
                $image->move($uploadPath, $name);
                $imageUrl = $uploadPath . $name;

                $pimage = new CampaignReview();
                $pimage->campaign_id = $campaign->id;
                $pimage->image = $imageUrl;
                $pimage->save();
            }
        }

        // =========================================================
        // ৫. ল্যান্ডিং পেজ বুক ইমেজ (Landing Images) - FIXED
        // =========================================================
        
        // A. মিডিয়া ম্যানেজার লিংক (Media Manager String URLs)
        if ($request->land_image && is_array($request->land_image)) {
            foreach ($request->land_image as $landItem) {
                // যদি স্ট্রিং হয় (মানে মিডিয়া ম্যানেজার লিংক)
                if (is_string($landItem)) {
                    $bimage = new Bookimage();
                    $bimage->campaign_id = $campaign->id;
                    $bimage->bookimage = $landItem;
                    $bimage->save();
                }
            }
        }

        // B. ফাইল আপলোড (Legacy File Upload)
        if ($request->hasFile('land_image')) {
            foreach ($request->file('land_image') as $image) {
                $name = time() . '-' . $image->getClientOriginalName();
                $name = strtolower(preg_replace('/\s+/', '-', $name));
                $uploadPath = 'public/uploads/campaign/';
                $image->move($uploadPath, $name);
                $imageUrl = $uploadPath . $name;

                $bimage = new Bookimage();
                $bimage->campaign_id = $campaign->id;
                $bimage->bookimage = $imageUrl;
                $bimage->save();
            }
        }

        Toastr::success('Success', 'Data insert successfully');
        return redirect()->route('campaign.index');
    }

    public function edit($id)
    {
        $edit_data = Campaign::with('images', 'bookimage')->find($id);
        $select_products = Product::where('campaign_id', $id)->get();
        $show_data = Campaign::orderBy('id', 'DESC')->get();
        $products = Product::where(['status' => 1])->select('id', 'name', 'status')->get();
        
        if ($edit_data->theme_id == 7) {
            return view('backEnd.campaign.edit_seven', compact('edit_data', 'products', 'select_products'));
        }
        return view('backEnd.campaign.edit', compact('edit_data', 'products', 'select_products'));

        if ($edit_data->theme_id == 9) {
        return view('backEnd.campaign.edit_nine', compact('edit_data', 'products', 'select_products'));
    }
    }

public function update(Request $request)
    {
        // ১. ভ্যালিডেশন
        $this->validate($request, [
            'name' => 'required',
            'slug' => 'required|unique:campaigns,slug,' . $request->hidden_id,
            'short_description' => 'required',
            'description' => 'required',
            'status' => 'required',
        ]);

        $update_data = Campaign::find($request->hidden_id);
        
        // বেসিক ইনপুটগুলো নেওয়া (যেগুলো ইমেজ নয়)
        $input = $request->except([
            'hidden_id', 'product_ids', 'files', 'image', 'land_image', 
            'delete_land_images', 'gallery_images', 
            'image_one', 'image_two', 'image_three', 'image_four', 
            'banner', 'image_section', 'testimonials'
        ]);
        
        $input['banner_title'] = $request->banner_title;
        $input['primary_color'] = $request->primary_color;

        // =========================================================
        // ২. সিঙ্গেল ইমেজ হ্যান্ডলিং (Hybrid Logic)
        // =========================================================
        
        // --- Image One ---
        if ($request->hasFile('image_one')) {
            $image_one = $request->file('image_one');
            $name1 = time() . '-' . $image_one->getClientOriginalName();
            $name1 = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp', $name1);
            $name1 = strtolower(preg_replace('/\s+/', '-', $name1));
            $uploadpath1 = 'public/uploads/campaign/';
            $imageUrl1 = $uploadpath1 . $name1;

            $img1 = Image::make($image_one->getRealPath());
            $img1->encode('webp', 90);
            $width1 = ''; $height1 = '';
            $img1->height() > $img1->width() ? $width1 = null : $height1 = null;
            $img1->resize($width1, $height1, function ($constraint) { $constraint->aspectRatio(); });
            $img1->save($imageUrl1);
            
            $input['image_one'] = $imageUrl1;
            File::delete($update_data->image_one);
        } 
        elseif ($request->image_one) {
            $input['image_one'] = $request->image_one;
        } 
        else {
            $input['image_one'] = $update_data->image_one;
        }

        // --- Image Two ---
        if ($request->hasFile('image_two')) {
            $image_two = $request->file('image_two');
            $name2 = time() . '-' . $image_two->getClientOriginalName();
            $name2 = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp', $name2);
            $name2 = strtolower(preg_replace('/\s+/', '-', $name2));
            $uploadpath2 = 'public/uploads/campaign/';
            $imageUrl2 = $uploadpath2 . $name2;

            $img2 = Image::make($image_two->getRealPath());
            $img2->encode('webp', 90);
            $width2 = ''; $height2 = '';
            $img2->height() > $img2->width() ? $width2 = null : $height2 = null;
            $img2->resize($width2, $height2, function ($constraint) { $constraint->aspectRatio(); });
            $img2->save($imageUrl2);

            $input['image_two'] = $imageUrl2;
            File::delete($update_data->image_two);
        } 
        elseif ($request->image_two) {
            $input['image_two'] = $request->image_two;
        } 
        else {
            $input['image_two'] = $update_data->image_two;
        }

        // --- Image Three ---
        if ($request->hasFile('image_three')) {
            $image_three = $request->file('image_three');
            $name3 = time() . '-' . $image_three->getClientOriginalName();
            $name3 = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp', $name3);
            $name3 = strtolower(preg_replace('/\s+/', '-', $name3));
            $uploadpath3 = 'public/uploads/campaign/';
            $imageUrl3 = $uploadpath3 . $name3;

            $img3 = Image::make($image_three->getRealPath());
            $img3->encode('webp', 90);
            $width3 = ''; $height3 = '';
            $img3->height() > $img3->width() ? $width3 = null : $height3 = null;
            $img3->resize($width3, $height3, function ($constraint) { $constraint->aspectRatio(); });
            $img3->save($imageUrl3);

            $input['image_three'] = $imageUrl3;
            File::delete($update_data->image_three);
        } 
        elseif ($request->image_three) {
            $input['image_three'] = $request->image_three;
        } 
        else {
            $input['image_three'] = $update_data->image_three;
        }

        // --- Image Four ---
        if ($request->hasFile('image_four')) {
            $image_four = $request->file('image_four');
            $name4 = time() . '-four-' . $image_four->getClientOriginalName();
            $name4 = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp', $name4);
            $name4 = strtolower(preg_replace('/\s+/', '-', $name4));
            $uploadpath4 = 'public/uploads/campaign/';
            $imageUrl4 = $uploadpath4 . $name4;

            $img4 = Image::make($image_four->getRealPath());
            $img4->encode('webp', 90);
            $img4->resize(800, null, function ($constraint) { $constraint->aspectRatio(); });
            $img4->save($imageUrl4);

            $input['image_four'] = $imageUrl4;
            if(isset($update_data->image_four)) {
                File::delete($update_data->image_four);
            }
        } 
        elseif ($request->image_four) {
            $input['image_four'] = $request->image_four;
        } 
        else {
            $input['image_four'] = $update_data->image_four ?? null;
        }

        // --- Banner ---
        if ($request->hasFile('banner')) {
            $banner_img = $request->file('banner');
            $nameBanner = time() . '-' . $banner_img->getClientOriginalName();
            $nameBanner = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp', $nameBanner);
            $nameBanner = strtolower(preg_replace('/\s+/', '-', $nameBanner));
            $uploadpathBanner = 'public/uploads/campaign/banner/';
            $imageUrlBanner = $uploadpathBanner . $nameBanner;

            $imgBanner = Image::make($banner_img->getRealPath());
            $imgBanner->encode('webp', 90);
            $widthBanner = ''; $heightBanner = '';
            $imgBanner->height() > $imgBanner->width() ? $widthBanner = null : $heightBanner = null;
            $imgBanner->resize($widthBanner, $heightBanner, function ($constraint) { $constraint->aspectRatio(); });
            $imgBanner->save($imageUrlBanner);

            $input['banner'] = $imageUrlBanner;
            File::delete($update_data->banner);
        } 
        elseif ($request->banner) {
            $input['banner'] = $request->banner;
        } 
        else {
            $input['banner'] = $update_data->banner;
        }

        // --- Image Section ---
        if ($request->hasFile('image_section')) {
            $image_section = $request->file('image_section');
            $nameSection = time() . '-' . $image_section->getClientOriginalName();
            $nameSection = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp', $nameSection);
            $nameSection = strtolower(preg_replace('/\s+/', '-', $nameSection));
            $uploadpathSection = 'public/uploads/campaign/';
            $imageUrlSection = $uploadpathSection . $nameSection;

            $imgSection = Image::make($image_section->getRealPath());
            $imgSection->encode('webp', 90);
            $widthSection = ''; $heightSection = '';
            $imgSection->height() > $imgSection->width() ? $widthSection = null : $heightSection = null;
            $imgSection->resize($widthSection, $heightSection, function ($constraint) { $constraint->aspectRatio(); });
            $imgSection->save($imageUrlSection);

            $input['image_section'] = $imageUrlSection;
            File::delete($update_data->image_section);
        } 
        elseif ($request->image_section) {
            $input['image_section'] = $request->image_section;
        } 
        else {
            $input['image_section'] = $update_data->image_section;
        }

        // =========================================================
        // ৩. টেস্টমোনিয়ালস (Testimonials)
        // =========================================================
        $testimonials_data = [];
        if($request->testimonials){
            foreach($request->testimonials as $key => $item){
                $tData = [
                    'type' => $item['type'],
                    'content' => $item['content'] ?? null,
                ];

                if($item['type'] == 'image'){
                    // A. New File Upload
                    if(isset($request->file('testimonials')[$key]['image'])){
                        $tImage = $request->file('testimonials')[$key]['image'];
                        $tName = time() . '_review_' . $key . '.' . $tImage->getClientOriginalExtension();
                        $tPath = 'public/uploads/campaign/reviews/';
                        if(!File::exists($tPath)) { File::makeDirectory($tPath, 0777, true, true); }
                        $tImage->move($tPath, $tName);
                        $tData['image'] = $tPath . $tName;
                    } 
                    // B. Media Manager String URL
                    elseif(isset($item['image']) && is_string($item['image']) && !empty($item['image'])) {
                        $tData['image'] = $item['image'];
                    }
                    // C. Old Image
                    elseif(isset($item['old_image'])) {
                        $tData['image'] = $item['old_image'];
                    }
                }
                $testimonials_data[] = $tData;
            }
            $input['testimonials'] = json_encode($testimonials_data, JSON_UNESCAPED_UNICODE);
        } else {
            $input['testimonials'] = null;
        }

        // =========================================================
        // ৪. বাকি ডাটা প্রসেসিং ও আপডেট
        // =========================================================
        $productId = $request->product_id;
        $encodedProductId = json_encode($productId);

        $input['video'] = $request->video;
        $input['slug'] = strtolower(Str::slug($request->slug));
        $input['section_title'] = $request->section_title;
        
        if (!empty($productId)) {
            $input['product_id'] = $encodedProductId;
        }
        $input['theme_id'] = $request->theme_id;

        // মেইন আপডেট কোয়েরি
        $update_data->update($input);


        // =========================================================
        // ৫. রিভিউ ইমেজ (Review Images) - FIXED
        // =========================================================
        
        // A. Media Manager Images (String URLs) - এটি আগে মিসিং ছিল
        if ($request->image && is_array($request->image)) {
            foreach ($request->image as $imgUrl) {
                // মিডিয়া ম্যানেজার থেকে আসলে এটি স্ট্রিং হবে
                if(is_string($imgUrl)) {
                    $pimage = new CampaignReview();
                    $pimage->campaign_id = $update_data->id;
                    $pimage->image = $imgUrl;
                    $pimage->save();
                }
            }
        }

        // B. Legacy File Upload (Old system)
        $images = $request->file('image');
        if ($images) {
            foreach ($images as $key => $image) {
                $name = time() . '-' . $image->getClientOriginalName();
                $name = strtolower(preg_replace('/\s+/', '-', $name));
                $uploadPath = 'public/uploads/campaign/';
                $image->move($uploadPath, $name);
                $imageUrl = $uploadPath . $name;

                $pimage = new CampaignReview();
                $pimage->campaign_id = $update_data->id;
                $pimage->image = $imageUrl;
                $pimage->save();
            }
        }

        // =========================================================
        // ৬. ল্যান্ডিং পেজ বুক ইমেজ (Land Images) - FIXED
        // =========================================================
        
        // ডিলেট লজিক
        if ($request->has('delete_land_images') && !empty($request->delete_land_images)) {
            $deleteImageIds = json_decode($request->delete_land_images, true);
            if (is_array($deleteImageIds)) {
                foreach ($deleteImageIds as $imageId) {
                    $bookImage = Bookimage::find($imageId);
                    if ($bookImage) {
                        File::delete($bookImage->bookimage);
                        $bookImage->delete();
                    }
                }
            }
        }

        // A. Media Manager Images (String URLs) - এটি আগে মিসিং ছিল
        if ($request->land_image && is_array($request->land_image)) {
            foreach ($request->land_image as $landItem) {
                // মিডিয়া ম্যানেজার থেকে আসলে এটি স্ট্রিং হবে
                if(is_string($landItem)) {
                    $bimage = new Bookimage();
                    $bimage->campaign_id = $update_data->id;
                    $bimage->bookimage = $landItem;
                    $bimage->save();
                }
            }
        }

        // B. Legacy File Upload (Old system)
        $books = $request->file('land_image');
        if ($books) {
            foreach ($books as $image) {
                $name = time() . '-' . $image->getClientOriginalName();
                $name = strtolower(preg_replace('/\s+/', '-', $name));
                $uploadPath = 'public/uploads/campaign/';
                $image->move($uploadPath, $name);
                $imageUrl = $uploadPath . $name;

                $bimage = new Bookimage();
                $bimage->campaign_id = $update_data->id;
                $bimage->bookimage = $imageUrl;
                $bimage->save();
            }
        }

        Toastr::success('Success', 'Data update successfully');
        return redirect()->route('campaign.index');
    }

    public function inactive(Request $request)
    {
        $inactive = Campaign::find($request->hidden_id);
        $inactive->status = 0;
        $inactive->save();
        Toastr::success('Success', 'Data inactive successfully');
        return redirect()->back();
    }
    public function active(Request $request)
    {
        $active = Campaign::find($request->hidden_id);
        $active->status = 1;
        $active->save();
        Toastr::success('Success', 'Data active successfully');
        return redirect()->back();
    }
    public function destroy(Request $request)
    {
        $delete_data = Campaign::find($request->hidden_id);
        $delete_data->delete();

        $campaign = Product::whereNotNull('campaign_id')->get();
        foreach ($campaign as $key => $value) {
            $product = Product::find($value->id);
            $product->campaign_id = null;
            $product->save();
        }
        Toastr::success('Success', 'Data delete successfully');
        return redirect()->back();
    }
    public function imgdestroy(Request $request)
    {
        $delete_data = CampaignReview::find($request->id);
        File::delete($delete_data->image);
        $delete_data->delete();
        Toastr::success('Success', 'Data delete successfully');
        return redirect()->back();
    }

    public function createNine()
{
    $products = Product::where(['status' => 1])->select('id', 'name', 'status')->get();
    return view('backEnd.campaign.create_nine', compact('products'));
}
}