<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;
use Brian2694\Toastr\Facades\Toastr;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::latest()->get();
        return view('backEnd.supplier.index', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
        ]);

        $supplier = Supplier::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'status' => 'active',
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'supplier' => $supplier]);
        }

        Toastr::success('Success', 'Supplier Added Successfully');
        return redirect()->back();
    }

    public function edit($id)
    {
        $supplier = Supplier::find($id);
        return response()->json($supplier);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
        ]);

        $supplier = Supplier::find($request->id);
        $supplier->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'status' => $request->status,
        ]);

        Toastr::success('Success', 'Supplier Updated Successfully');
        return redirect()->back();
    }

    public function destroy($id)
    {
        $supplier = Supplier::find($id);
        $supplier->delete();
        Toastr::success('Success', 'Supplier Deleted Successfully');
        return redirect()->back();
    }

    public function history($id)
    {
        $supplier = Supplier::with('purchases')->findOrFail($id);
        return view('backEnd.supplier.history', compact('supplier'));
    }
}
