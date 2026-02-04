<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminQuickTab;
use Illuminate\Validation\Rule;
use Auth;
use Toastr; 

class QuickTabController extends Controller
{
    /**
     * সকল কুইক ট্যাবের তালিকা প্রদর্শন করুন।
     */
    public function index()
    {
        $quickTabs = AdminQuickTab::orderBy('order', 'asc')->get(); 
        return view('backEnd.admin.quick_tabs.index', compact('quickTabs'));
    }

    /**
     * নতুন কুইক ট্যাব তৈরির ফর্ম দেখান।
     */
    public function create()
    {
        return view('backEnd.admin.quick_tabs.create');
    }

    /**
     * নতুন কুইক ট্যাব ডেটা সেভ করুন।
     */
    public function store(Request $request)
    {
        // ✅ icon_svg এবং route_params ভ্যালিডেশন থেকে বাদ দেওয়া হলো
        $request->validate([
            'tab_name'   => 'required|string|max:255',
            'tab_link'   => 'required|string|max:255',
        ]);

        // ✅ icon_svg এবং route_params $data অ্যারে থেকে বাদ দেওয়া হলো
        $data = $request->only(['tab_name', 'tab_link']);
        
        // মাইগ্রেশন অনুযায়ী যে ফিল্ডগুলি Required, সেগুলিকে Auto-সেট করা হলো
        $data['user_id'] = Auth::id(); 
        $data['is_active'] = 1; 
        $data['order'] = $request->input('order', 99); 

        AdminQuickTab::create($data); 
        
        // ⭐ Toastr এখন সঠিকভাবে কাজ করবে
        Toastr::success('কুইক ট্যাব সফলভাবে যোগ করা হয়েছে!', 'সফল');
        return redirect()->route('admin.quick_tabs.index');
    }
    
    /**
     * কুইক ট্যাব এডিট করার ফর্ম দেখান।
     * ❌ (এই মেথডটি পূর্বে অনুপস্থিত ছিল, যার কারণে BadMethodCallException এসেছিল)
     */
    public function edit(Request $request)
    {
        $quickTab = AdminQuickTab::findOrFail($request->id);
        return view('backEnd.admin.quick_tabs.edit', compact('quickTab'));
    }

    /**
     * কুইক ট্যাব ডেটা আপডেট করুন।
     */
    public function update(Request $request)
    {
        $quickTab = AdminQuickTab::findOrFail($request->id);
        
        // ✅ icon_svg এবং route_params ভ্যালিডেশন থেকে বাদ দেওয়া হলো
        $request->validate([
            'tab_name'   => 'required|string|max:255',
            'tab_link'   => 'required|string|max:255',
        ]);

        // ✅ icon_svg এবং route_params $data অ্যারে থেকে বাদ দেওয়া হলো
        $data = $request->only(['tab_name', 'tab_link']);
        
        $data['order'] = $request->input('order', $quickTab->order);
        
        $quickTab->update($data);
        
        Toastr::success('কুইক ট্যাব সফলভাবে আপডেট করা হয়েছে!', 'সফল');
        return redirect()->route('admin.quick_tabs.index');
    }

    /**
     * কুইক ট্যাব ডিলিট করুন।
     */
    public function destroy(Request $request)
    {
        $quickTab = AdminQuickTab::findOrFail($request->id);
        $quickTab->delete();
        
        Toastr::success('কুইক ট্যাব সফলভাবে মুছে ফেলা হয়েছে!', 'সফল');
        return redirect()->route('admin.quick_tabs.index');
    }

    /**
     * কুইক ট্যাব নিষ্ক্রিয় করুন।
     */
    public function inactive(Request $request)
    {
        $quickTab = AdminQuickTab::findOrFail($request->id);
        $quickTab->update(['is_active' => 0]);
        
        Toastr::warning('কুইক ট্যাব নিষ্ক্রিয় করা হয়েছে!', 'স্ট্যাটাস আপডেট');
        return redirect()->route('admin.quick_tabs.index');
    }

    /**
     * কুইক ট্যাব সক্রিয় করুন।
     */
    public function active(Request $request)
    {
        $quickTab = AdminQuickTab::findOrFail($request->id);
        $quickTab->update(['is_active' => 1]);
        
        Toastr::success('কুইক ট্যাব সক্রিয় করা হয়েছে!', 'স্ট্যাটাস আপডেট');
        return redirect()->route('admin.quick_tabs.index');
    }
}