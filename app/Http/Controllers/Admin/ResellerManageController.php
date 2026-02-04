<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reseller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\File;

class ResellerManageController extends Controller
{
    public function index(Request $request)
    {
        $data = Reseller::with('dealer')->orderBy('id', 'DESC');
        if ($request->dealer_id) {
            $data->where('dealer_id', $request->dealer_id);
        }
        if ($request->reseller_id) {
            $data->where('id', $request->reseller_id);
        }
        $data = $data->get();
        return view('backEnd.reseller.manage.index', compact('data'));
    }

    public function profile($id)
    {
        $reseller = Reseller::with(['dealer', 'orders', 'payments'])->find($id);
        return view('backEnd.reseller.manage.profile', compact('reseller'));
    }

    public function active(Request $request)
    {
        $reseller = Reseller::find($request->hidden_id);
        $reseller->status = 'active';
        $reseller->save();
        Toastr::success('Success', 'Reseller activated successfully');
        return redirect()->back();
    }

    public function inactive(Request $request)
    {
        $reseller = Reseller::find($request->hidden_id);
        $reseller->status = 'inactive';
        $reseller->save();
        Toastr::success('Success', 'Reseller inactivated successfully');
        return redirect()->back();
    }
    public function destroy(Request $request)
    {
        $reseller = Reseller::find($request->hidden_id);
        $reseller->delete();
        Toastr::success('Success', 'Reseller deleted successfully');
        return redirect()->back();
    }
    public function resellerStats() {
    $stats = Reseller::select('thana_id', \DB::raw('count(*) as total'))
             ->groupBy('thana_id')
             ->with('thana')
             ->get();
    return view('backEnd.reseller.manage.stats', compact('stats'));
}
}
