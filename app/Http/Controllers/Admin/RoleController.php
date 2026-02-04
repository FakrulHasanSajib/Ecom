<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Toastr;
use DB;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
         $this->middleware('permission:role-create', ['only' => ['create','store']]);
         $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }
    
    // --- [Helper Function] পারমিশন অটোমেটিক গ্রুপ করার লজিক ---
    private function getDynamicGroups($permissions)
    {
        $grouped = [];

        foreach ($permissions as $perm) {
            // ১. হাইফেন (-) বা ডট (.) দিয়ে নাম আলাদা করা
            if (str_contains($perm->name, '-')) {
                $parts = explode('-', $perm->name);
                // প্রথম অংশটি হবে গ্রুপের নাম (যেমন: product-create থেকে 'Product')
                $groupName = ucfirst($parts[0]); 
            } elseif (str_contains($perm->name, '.')) {
                $parts = explode('.', $perm->name);
                $groupName = ucfirst($parts[0]);
            } else {
                // কোনো সেপারেটর না থাকলে 'Others' গ্রুপে যাবে
                $groupName = 'Others';
            }

            // ২. গ্রুপ অনুযায়ী অ্যারেতে সাজানো
            $grouped[$groupName][] = $perm;
        }

        return $grouped;
    }

    public function index(Request $request)
    {
        $show_data = Role::orderBy('id','DESC')->get();
        return view('backEnd.roles.index',compact('show_data'));
    }
    
    public function create()
    {
        // ১. সব পারমিশন আনা
        $permission = Permission::get();
        
        // ২. অটোমেটিক গ্রুপ তৈরি করা (Helper Function কল করা)
        $permission_structure = $this->getDynamicGroups($permission);

        // ৩. ভিউতে পাঠানো
        return view('backEnd.roles.create', compact('permission', 'permission_structure'));
    }
    
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);
     
        $role = Role::create(['name' => $request->input('name')]);
        // ভিউ থেকে আসা array আইডিগুলো সিঙ্ক করা
        $role->syncPermissions($request->input('permission'));
        
        Toastr::success('Success','Data store successfully');
        return redirect()->route('roles.index');
    }
    
    public function show($id)
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
            ->where("role_has_permissions.role_id",$id)
            ->get();
    
        return view('backEnd.roles.show',compact('role','rolePermissions'));
    }

    public function edit($id)
    {
        $edit_data = Role::find($id);
        $permission = Permission::get();
        
        // এডিট পেজের জন্যও অটোমেটিক গ্রুপ তৈরি করা
        $permission_structure = $this->getDynamicGroups($permission);

        return view('backEnd.roles.edit',compact('edit_data','permission', 'permission_structure'));
    }
    
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);

        $input = $request->all();
        $update_data = Role::find($request->hidden_id);
        $update_data->update($input);
    
        $update_data->syncPermissions($request->input('permission'));
        
        Toastr::success('Success','Data update successfully');
        return redirect()->route('roles.index');
    }

    public function destroy(Request $request)
    {
        $delete_data = Role::find($request->hidden_id)->delete();
        Toastr::success('Success','Data delete successfully');
        return redirect()->back();
    }
}