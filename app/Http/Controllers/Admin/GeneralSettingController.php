<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Toastr;
use Image;
use File;
use DB;
class GeneralSettingController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:setting-list|setting-create|setting-edit|setting-delete', ['only' => ['index','store']]);
        $this->middleware('permission:setting-create', ['only' => ['create','store']]);
        $this->middleware('permission:setting-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:setting-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $show_data = GeneralSetting::orderBy('id','DESC')->get();
        return view('backEnd.settings.index',compact('show_data'));
    }
    public function create()
    {
        return view('backEnd.settings.create');
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'white_logo' => 'required',
            'favicon' => 'required',
            'status' => 'required',
        ]);

        // image with intervention 
        $image = $request->file('white_logo');
        $name =  time().'-'.$image->getClientOriginalName();
        $name = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp',$name);
        $name = strtolower(preg_replace('/\s+/', '-', $name));
        $uploadpath = 'public/uploads/settings/';
        $imageUrl = $uploadpath.$name; 
        $img=Image::make($image->getRealPath());
        $img->encode('webp', 90);
        $width = '';
        $height = '';
        $img->height() > $img->width() ? $width=null : $height=null;
        $img->resize($width, $height);
        $img->save($imageUrl);

        // dark logo
        $image2 = $request->file('dark_logo');
        $name2 =  time().'-'.$image2->getClientOriginalName();
        $name2 = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp',$name2);
        $name2 = strtolower(preg_replace('/\s+/', '-', $name2));
        $uploadpath2 = 'public/uploads/settings/';
        $image2Url = $uploadpath2.$name2; 
        $img2=Image::make($image2->getRealPath());
        $img2->encode('webp', 90);
        $width2 = '';
        $height2 = '';
        $img2->height() > $img2->width() ? $width2=null : $height2=null;
        $img2->resize($width2, $height2);
        $img2->save($image2Url);

        // image with intervention 
        $image3 = $request->file('favicon');
        $name3 =  time().'-'.$image3->getClientOriginalName();
        $name3 = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp',$name3);
        $name3 = strtolower(preg_replace('/\s+/', '-', $name3));
        $uploadpath3 = 'public/uploads/settings/';
        $image3Url = $uploadpath3.$name3; 
        $img3=Image::make($image3->getRealPath());
        $img3->encode('webp', 90);
        $width3 = 32;
        $height3 = 32;
        $img3->height() > $img3->width() ? $width3=null : $height3=null;
        $img3->resize($width3, $height3);
        $img3->save($image3Url);

        $input = $request->all();
        $input['white_logo'] = $imageUrl;
        $input['dark_logo'] = $image2Url;
        $input['favicon'] = $image3Url;
        GeneralSetting::create($input);
        Toastr::success('Success','Data insert successfully');
        return redirect()->route('settings.index');
    }
    
    public function edit($id)
    {
        $edit_data = GeneralSetting::find($id);

        // ১. সেফটি চেক: যদি ডাটা না পাওয়া যায়, তাহলে আগের পেজে ফিরিয়ে নিবে
        if (!$edit_data) {
            Toastr::error('Error', 'Setting data not found!');
            return redirect()->route('settings.index');
        }

        // ২. Header Menu Decode
        $labels = json_decode($edit_data->header_menu_labels, true); 
        $urls = json_decode($edit_data->header_menu_links, true);
        
        $headerMenuData = [];
        // চেক করা হচ্ছে labels এবং urls সত্যিই অ্যারে কিনা
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
        
        // ৩. Footer Menu Decode
        $foolabels = json_decode($edit_data->footer_menu_labels, true); 
        $foourls = json_decode($edit_data->footer_menu_links, true);
        
        $footerMenuData = [];
        // চেক করা হচ্ছে foolabels এবং foourls সত্যিই অ্যারে কিনা
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

        return view('backEnd.settings.edit', compact('edit_data', 'headerMenuData', 'footerMenuData'));
    }
    
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);
        $update_data = GeneralSetting::find($request->id);
        $input = $request->all();
        // new white logo
        $image = $request->file('white_logo');
        if($image){
            // image with intervention 
            $image = $request->file('white_logo');
            $name =  time().'-'.$image->getClientOriginalName();
            $name = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp',$name);
            $name = strtolower(preg_replace('/\s+/', '-', $name));
            $uploadpath = 'public/uploads/settings/';
            $imageUrl = $uploadpath.$name; 
            $img=Image::make($image->getRealPath());
            $img->encode('webp', 90);
            $width = '';
            $height = '';
            $img->height() > $img->width() ? $width=null : $height=null;
            $img->resize($width, $height);
            $img->save($imageUrl);
            $input['white_logo'] = $imageUrl;
        }else{
            $input['white_logo'] = $update_data->white_logo;
        }
        // new dark logo
        $image2 = $request->file('dark_logo');
        if($image2){
            // image with intervention 
            $image2 = $request->file('dark_logo');
            $name2 =  time().'-'.$image2->getClientOriginalName();
            $name2 = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp',$name2);
            $name2 = strtolower(preg_replace('/\s+/', '-', $name2));
            $uploadpath2 = 'public/uploads/settings/';
            $image2Url = $uploadpath2.$name2; 
            $img2=Image::make($image2->getRealPath());
            $img2->encode('webp', 90);
            $width2 = '';
            $height2 = '';
            $img2->height() > $img2->width() ? $width2=null : $height2=null;
            $img2->resize($width2, $height2);
            $img2->save($image2Url);
            $input['dark_logo'] = $image2Url;
        }else{
            $input['dark_logo'] = $update_data->dark_logo;
        }

        // new favicon image
        $image3 = $request->file('favicon');
        if($image3){
            $image3 = $request->file('favicon');
            $name3 =  time().'-'.$image3->getClientOriginalName();
            $name3 = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp',$name3);
            $name3 = strtolower(preg_replace('/\s+/', '-', $name3));
            $uploadpath3 = 'public/uploads/settings/';
            $image3Url = $uploadpath3.$name3; 
            $img3=Image::make($image3->getRealPath());
            $img3->encode('webp', 90);
            $width3 = 32;
            $height3 = 32;
            $img3->height() > $img3->width() ? $width3=null : $height3=null;
            $img3->resize($width3, $height3);
            $img3->save($image3Url);
            $input['favicon'] = $image3Url;
        }else{
            $input['favicon'] = $update_data->favicon;
        }
        $input['status'] = $request->status?1:0;
        $input['header_menu_labels'] = json_encode($request->header_menu_labels ?? []);
$input['header_menu_links'] = json_encode($request->header_menu_links ?? []);
        
        $input['footer_menu_labels'] = json_encode($request->footer_menu_labels ?? []);
$input['footer_menu_links'] = json_encode($request->footer_menu_links ?? []);

        $update_data->update($input);

        Toastr::success('Success','Data update successfully');
        return redirect()->route('settings.index');
    }
 
    public function inactive(Request $request)
    {
        $inactive = GeneralSetting::find($request->hidden_id);
        $inactive->status = 0;
        $inactive->save();
        Toastr::success('Success','Data inactive successfully');
        return redirect()->back();
    }
    public function active(Request $request)
    {
        $active = GeneralSetting::find($request->hidden_id);
        $active->status = 1;
        $active->save();
        Toastr::success('Success','Data active successfully');
        return redirect()->back();
    }
    public function destroy(Request $request)
    {
        $delete_data = GeneralSetting::find($request->hidden_id);
        File::delete($delete_data->image);
        $delete_data->delete();
        Toastr::success('Success','Data delete successfully');
        return redirect()->back();
    }

    public function pixelSetup()
{
    // ডাটা নিয়ে আসা
    $setting = \App\Models\GeneralSetting::first();
    return view('backEnd.settings.pixel_setup', compact('setting'));
}

public function pixelSetupUpdate(Request $request)
{
    $setting = \App\Models\GeneralSetting::first();
    
    // ডাটা আপডেট করা (admin অথবা customer)
    $setting->pixel_trigger_type = $request->pixel_trigger_type;
    $setting->save();

    Toastr::success('Success', 'Pixel Trigger Setting Updated');
    return redirect()->back();
}
}