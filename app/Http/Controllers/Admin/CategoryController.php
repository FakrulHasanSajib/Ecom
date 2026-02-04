<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Toastr;
use Image;
use File;
use Str;
class CategoryController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:category-list|category-create|category-edit|category-delete', ['only' => ['index','store']]);
         $this->middleware('permission:category-create', ['only' => ['create','store']]);
         $this->middleware('permission:category-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:category-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $data = Category::orderBy('id','DESC')->with('category')->get();
        // return $data;
        return view('backEnd.category.index',compact('data'));
    }
    public function create()
    {
        $categories = Category::orderBy('id','DESC')->select('id','name')->get();
        return view('backEnd.category.create',compact('categories'));
    }
 
    public function store(Request $request)
{
    $this->validate($request, [
        'name' => 'required',
        'status' => 'required',
        'image' => 'required', // মিডিয়া ম্যানেজার থেকে আসা পাথটি রিকোয়ার্ড করা হলো
    ]);

    $input = $request->all();
    
    // স্লাগ তৈরি করা
    $input['slug'] = strtolower(preg_replace('/\s+/', '-', $request->name));
    $input['slug'] = str_replace('/', '', $input['slug']);

    // প্যারেন্ট আইডি এবং ভিউ হ্যান্ডেল করা
    $input['parent_id'] = $request->parent_id ? $request->parent_id : 0;
    $input['front_view'] = $request->front_view ? 1 : 0;
    
    // মিডিয়া ম্যানেজার থেকে আসা ইমেজ পাথ সেট করা
    $input['image'] = $request->image; 

    Category::create($input);
    
    Toastr::success('Success', 'Data inserted successfully');
    return redirect()->route('categories.index');
}
    public function edit($id)
    {
        $edit_data = Category::find($id);
        $categories = Category::select('id','name')->get();
        return view('backEnd.category.edit',compact('edit_data','categories'));
    }
    
  public function update(Request $request)
{
    $this->validate($request, [
        'name' => 'required',
    ]);
    
    $update_data = Category::find($request->id);
    if (!$update_data) {
        Toastr::error('Error', 'Category not found');
        return redirect()->back();
    }

    $input = $request->all();
    
    // স্লাগ আপডেট
    $input['slug'] = strtolower(preg_replace('/\s+/', '-', $request->name));
    $input['slug'] = str_replace('/', '', $input['slug']);

    // চেকবক্স হ্যান্ডেলিং (যদি আনচেক থাকে তবে ০ হবে)
    $input['parent_id'] = $request->parent_id ? $request->parent_id : 0;
    $input['front_view'] = $request->front_view ? 1 : 0;
    $input['status'] = $request->status ? 1 : 0;
    
    // ইমেজ আপডেট: যদি নতুন ইমেজ সিলেক্ট করা হয় তবে সেটি নিবে, নাহলে আগেরটি থাকবে
    $input['image'] = $request->image ? $request->image : $update_data->image;

    $update_data->update($input);

    Toastr::success('Success', 'Data updated successfully');
    return redirect()->route('categories.index');
}
 
    public function inactive(Request $request)
    {
        $inactive = Category::find($request->hidden_id);
        $inactive->status = 0;
        $inactive->save();
        Toastr::success('Success','Data inactive successfully');
        return redirect()->back();
    }
    public function active(Request $request)
    {
        $active = Category::find($request->hidden_id);
        $active->status = 1;
        $active->save();
        Toastr::success('Success','Data active successfully');
        return redirect()->back();
    }
public function destroy(Request $request)
{
    $delete_data = Category::find($request->hidden_id);

    if ($delete_data) {
        // 'subcategories' এর বদলে 'allSubcategories' ব্যবহার করুন যা আপনি মডেলে পরে যোগ করেছেন
        $delete_data->allSubcategories()->delete(); 

        $delete_data->delete();
        Toastr::success('Success', 'Category and ALL related subcategories deleted successfully');
    } else {
        Toastr::error('Error', 'Data not found');
    }
    return redirect()->back();
}
}
