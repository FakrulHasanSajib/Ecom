<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dealer;
use App\Models\DealerProduct;
use App\Models\Product;
use Spatie\Permission\Models\Role; // Kept for consistency if needed later
use Brian2694\Toastr\Facades\Toastr;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;

class DealerController extends Controller
{
    public function index(Request $request)
    {
        $data = Dealer::orderBy('id', 'DESC')->get();
        return view('backEnd.dealer.index', compact('data'));
    }

    public function create()
    {
        // $roles = Role::select('name')->get(); // If roles are needed
        return view('backEnd.dealer.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:dealers,email',
            'password' => 'required|same:confirm-password',
            'phone' => 'required',
            'store_name' => 'required',
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        // Image upload
        $image = $request->file('image');
        if ($image) {
            $name = time() . '-' . $image->getClientOriginalName();
            $name = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp', $name);
            $name = strtolower(preg_replace('/\s+/', '-', $name));
            $uploadpath = 'public/uploads/users/';
            $imageUrl = $uploadpath . $name;
            $img = Image::make($image->getRealPath());
            $img->encode('webp', 90);
            $img->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save($imageUrl);
            $input['image'] = $imageUrl;
        }

        Dealer::create($input);
        Toastr::success('Success', 'Dealer created successfully');
        return redirect()->route('admin.dealer.index');
    }

    public function edit($id)
    {
        $edit_data = Dealer::find($id);
        return view('backEnd.dealer.edit', compact('edit_data'));
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:dealers,email,' . $request->hidden_id,
        ]);

        $dealer = Dealer::find($request->hidden_id);
        $input = $request->all();

        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }

        // Image upload
        $image = $request->file('image');
        if ($image) {
            $name = time() . '-' . $image->getClientOriginalName();
            $name = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp', $name);
            $uploadpath = 'public/uploads/users/';
            $imageUrl = $uploadpath . $name;
            Image::make($image->getRealPath())->encode('webp', 90)->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save($imageUrl);

            $input['image'] = $imageUrl;
            if ($dealer->image != 'default.png') {
                File::delete($dealer->image);
            }
        } else {
            $input['image'] = $dealer->image;
        }

        $input['status'] = $request->status ? 'active' : 'inactive';
        $dealer->update($input);

        Toastr::success('Success', 'Dealer updated successfully');
        return redirect()->route('admin.dealer.index');
    }

    public function destroy(Request $request)
    {
        $dealer = Dealer::find($request->hidden_id);
        if ($dealer->image != 'default.png') {
            File::delete($dealer->image);
        }
        $dealer->delete();
        Toastr::success('Success', 'Dealer deleted successfully');
        return redirect()->back();
    }

    public function inactive(Request $request)
    {
        $dealer = Dealer::find($request->hidden_id);
        $dealer->status = 'inactive';
        $dealer->save();
        Toastr::success('Success', 'Dealer inactivated');
        return redirect()->back();
    }

    public function active(Request $request)
    {
        $dealer = Dealer::find($request->hidden_id);
        $dealer->status = 'active';
        $dealer->save();
        Toastr::success('Success', 'Dealer activated');
        return redirect()->back();
    }

    public function products($id)
    {
        $dealer = Dealer::with('products')->find($id);
        // Fetch all products for now to debug, or check if status is 1
        $products = Product::select('id', 'name', 'new_price', 'status')->where('status', 1)->orWhere('status', 'active')->get();
        return view('backEnd.dealer.products', compact('dealer', 'products'));
    }

    public function profile($id)
    {
        $dealer = Dealer::with(['products', 'resellers'])->find($id);
        return view('backEnd.dealer.profile', compact('dealer'));
    }

    public function productStore(Request $request)
    {
        $this->validate($request, [
            'dealer_id' => 'required',
            'product_id' => 'required',
            'dealer_price' => 'required|numeric',
        ]);

        $dealer = Dealer::find($request->dealer_id);

        // Check if already exists
        if ($dealer->products()->where('product_id', $request->product_id)->exists()) {
            $dealer->products()->updateExistingPivot($request->product_id, ['dealer_price' => $request->dealer_price]);
            Toastr::success('Success', 'Product price updated');
        } else {
            $dealer->products()->attach($request->product_id, ['dealer_price' => $request->dealer_price]);
            Toastr::success('Success', 'Product assigned successfully');
        }

        return redirect()->back();
    }

    public function productDestroy(Request $request)
    {
        $dealer = Dealer::find($request->dealer_id);
        $dealer->products()->detach($request->product_id);
        Toastr::success('Success', 'Product removed successfully');
        return redirect()->back();
    }


    public function orderList()
    {
        $orders = \App\Models\Order::where('order_type', 'dealer')->orWhere('order_type', 'reseller')->orderBy('id', 'desc')->paginate(20);
        return view('backEnd.dealer.order_list', compact('orders'));
    }

    public function paymentRequests()
    {
        $withdrawals = \App\Models\Withdrawal::orderBy('id', 'desc')->with(['dealer', 'reseller'])->paginate(20);
        return view('backEnd.dealer.payment_request', compact('withdrawals'));
    }

    public function paymentStatusUpdate(Request $request)
    {
        $withdrawal = \App\Models\Withdrawal::find($request->id);
        if ($withdrawal) {
            $withdrawal->status = $request->status;
            $withdrawal->note = $request->note;
            $withdrawal->save();

            // If Approved, Deduct Balance?
            // Currently Balance is deducted conceptually. But we should update the Dealer/Reseller balance in DB?
            // User didn't specify balance logic, just "Status change".
            // Typically: Pending -> Approved (Money Sent) -> Balance Deducted.
            // OR Balance Deducted on Request -> Refund on Reject.

            // Let's assume manual balance adjustment or logic is handled elsewhere for now 
            // OR simply implement: If status becomes 'approved' and wasn't before, deduct balance?
            // I'll stick to just Status Update for now as requested.

            Toastr::success('Status updated successfully', 'Success');
        }
        return redirect()->back();
    }
}
