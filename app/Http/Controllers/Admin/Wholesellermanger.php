<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Arr;
use App\Models\User;
use App\Models\Wholesaler;
use App\Models\Order;
use App\Models\PaymentHistory;
use Toastr;
use Image;
use File;
use DB;
use Hash;
use Illuminate\Support\Str;

class Wholesellermanger extends Controller
{
    public function index(Request $request)
    {
        $data = Wholesaler::orderBy('id','DESC')->get();
        return view('backEnd.wholeseller.index',compact('data'));
    }
    
    public function create()
    {
        $roles = Role::select('name')->get();
        return view('backEnd.wholeseller.create',compact('roles'));
    }
    
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:wholesalers,email',
            'password' => 'required|same:confirm-password'
        ]);
        // image with intervention 
        $image = $request->file('image');
        $name =  time().'-'.$image->getClientOriginalName();
        $name = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp',$name);
        $name = strtolower(preg_replace('/\s+/', '-', $name));
        $uploadpath = 'public/uploads/users/';
        $imageUrl = $uploadpath.$name; 
        $img=Image::make($image->getRealPath());
        $img->encode('webp', 90);
        $width = 100;
        $height = 100;
        $img->height() > $img->width() ? $width=null : $height=null;
        $img->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->save($imageUrl);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $input['image'] = $imageUrl;
        
        $user = Wholesaler::create($input);
        Toastr::success('Success','Data insert successfully');
        return redirect()->route('wholeseller.index');
    }
    
    public function edit($id)
    {
        $edit_data = Wholesaler::find($id);
        return view('backEnd.wholeseller.edit',compact('edit_data'));
    }
    
public function historywhosales($id)
{
    // ১. হোলসেলার ডাটা খুঁজে বের করা
    $edit_data = Wholesaler::findOrFail($id);

    // ২. রিলেটেড সব ডাটা একবারে নিয়ে আসা (Performance Optimization)
    // সব অর্ডার যারা এই হোলসেলারের এবং টাইপ 'wholesale'
    $allOrders = Order::where('customer_type', 'wholesale')
                      ->where('customer_id', $id)
                      ->get();

    // ৩. ইনভয়েস এবং রিটার্ন আলাদা করা
    // স্ট্যাটাস ১১ মানে রিটার্ন, ১১ ছাড়া বাকি সব ইনভয়েস
    $invoiceOrders = $allOrders->where('order_status', '!=', 11);
    $returnOrders  = $allOrders->where('order_status', 11);

    // ৪. ইনভয়েস এবং রিটার্নের টোটাল অ্যামাউন্ট বের করা
    $totalInvoiceAmount = $invoiceOrders->sum('amount');
    $totalReturnAmount  = $returnOrders->sum('amount');

    // ৫. পেমেন্ট হিস্ট্রি ডাটা আনা
    $paymenthistory = PaymentHistory::where('whosales_id', $id)->latest()->get();

    // ৬. পেমেন্ট ক্যালকুলেশন
    $totalPaid    = $paymenthistory->sum('pay_amount');
    $totalAdvance = $paymenthistory->where('payment_method', 'Advance')->sum('pay_amount');

    // ৭. নেট পেয়াবল (আসল কেনাকাটা) এবং ডিউ ক্যালকুলেশন
    // লজিক: (মোট ইনভয়েস - মোট রিটার্ন) = নিট কেনাকাটা। সেখান থেকে পেইড অ্যামাউন্ট বাদ।
    $netInvoiceAmount = $totalInvoiceAmount - $totalReturnAmount;
    $dueAmount        = $netInvoiceAmount - $totalPaid;

    // ৮. সামারি ডাটা অ্যারে তৈরি
    $summaryData = [
        'total_invoices'       => $invoiceOrders->count(),
        'total_invoice_amount' => $totalInvoiceAmount,
        'total_returns'        => $returnOrders->count(),
        'total_return_amount'  => $totalReturnAmount,
        'total_paid'           => $totalPaid,
        'total_advance'        => $totalAdvance,
        'net_invoice_amount'   => $netInvoiceAmount,
        'due_amount'           => $dueAmount,
        'payment_history'      => $paymenthistory
    ];

    // ৯. ভিউ রিটার্ন করা
    return view('backEnd.wholeseller.historywhosales', compact('edit_data', 'id', 'summaryData'));
}
    
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:wholesalers,email,'.$request->hidden_id,
            'password' => 'same:confirm-password'
        ]);
        
        $update_data = Wholesaler::find($request->hidden_id);

        // new password
        $input = $request->all();
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));    
        }

        // new image
        $image = $request->file('image');
        if($image){
            // image with intervention 
            $name =  time().'-'.$image->getClientOriginalName();
            $name = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp',$name);
            $name = strtolower(preg_replace('/\s+/', '-', $name));
            $uploadpath = 'public/uploads/users/';
            $imageUrl = $uploadpath.$name; 
            $img=Image::make($image->getRealPath());
            $img->encode('webp', 90);
            $width = 100;
            $height = 100;
            $img->height() > $img->width() ? $width=null : $height=null;
            $img->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save($imageUrl);
            $input['image'] = $imageUrl;
            File::delete($update_data->image);
        }else{
            $input['image'] = $update_data->image;
        }
        $input['status'] = $request->status?1:0;
        $update_data->update($input);

        // role asign
        
        Toastr::success('Success','Data update successfully');
        return redirect()->route('wholeseller.index');
    }

    public function inactive(Request $request)
    {
        $inactive = Wholesaler::find($request->hidden_id);
        $inactive->status = 'inactive';
        $inactive->save();
        Toastr::success('Success','Data inactive successfully');
        return redirect()->back();
    }

    public function active(Request $request)
    {
        $active = Wholesaler::find($request->hidden_id);
        $active->status = 'active';
        $active->save();
        Toastr::success('Success','Data active successfully');
        return redirect()->back();
    }

  public function destroy(Request $request)
    {

        $delete_data = Wholesaler::find($request->hidden_id);
        File::delete($delete_data->image);
        $delete_data->delete();
        Toastr::success('Success','Data delete successfully');
        return redirect()->back();
    }

    public function paymentlist(Request $request){

     $query = PaymentHistory::with('wholesaler');
    
    // Date filtering
    if ($request->filled('date_filter')) {
        $dateFilter = $request->date_filter;
        switch ($dateFilter) {
            case 'today':
                $query->whereDate('date', today());
                break;
            case 'this_week':
                $query->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'this_month':
                $query->whereMonth('date', now()->month)
                      ->whereYear('date', now()->year);
                break;
        }
    }
    
    // Wholesaler filtering
    if ($request->filled('whosaler_id')) {
        $query->where('whosales_id', $request->whosaler_id);
    }
    
    // Keyword search
    if ($request->filled('keyword')) {
        $keyword = $request->keyword;
        $query->where(function($q) use ($keyword) {
            $q->where('pay_code', 'like', "%{$keyword}%")
              ->orWhere('payment_method', 'like', "%{$keyword}%")
              ->orWhere('pay_amount', 'like', "%{$keyword}%")
              ->orWhere('paynode', 'like', "%{$keyword}%")
              ->orWhereHas('wholesaler', function($subQuery) use ($keyword) {
                  $subQuery->where('business_name', 'like', "%{$keyword}%");
              });
        });
    }
    
    $paymentlist = $query->paginate(15);
    $wholesaler = Wholesaler::get();
    
    // If AJAX request, return only the table content
    if ($request->ajax()) {
        return response()->json([
            'html' => view('backEnd.wholeseller.payment_table_content', compact('paymentlist'))->render(),
            'pagination' => $paymentlist->appends($request->all())->links('pagination::bootstrap-4')->render()
        ]);
    }
    
    return view('backEnd.wholeseller.paymentlist', compact('paymentlist', 'wholesaler'));
    }

    public function paymentwhosaleer(Request $request){
        

        $validated = $request->validate([
        'whosaler_id'     => 'required|exists:wholesalers,id', // make sure table name is correct
        'payment_method'  => 'required',
        'advance'         => 'required',
        'paynote'         => 'nullable|string|max:1000',
    ]);
   
$pay_code = 'PAY-' . strtoupper(Str::random(8));
    // Step 2: Save to database
    $payment = new PaymentHistory();
    $payment->whosales_id    = $validated['whosaler_id'];
    $payment->pay_code    = $pay_code;
    $payment->payment_method = $validated['payment_method'];
    $payment->pay_amount         = $validated['advance']; // assuming the column is named 'amount'
    $payment->paynode           = $validated['paynote']; // assuming the column is named 'note'
   $payment->date             = $request->date;
    $payment->save();

    $wholesale_payment = Wholesaler::find($request->whosaler_id);
    $pay =  $wholesale_payment->advance + $request->advance;
    $wholesale_payment->advance = $pay;
    $wholesale_payment->save();

    return redirect()->route('wholeseller.payment')->with('success', 'Payment recorded successfully.');



    }

    public function whosalespayment($id){

        $edit_pay = PaymentHistory::find($id);
         $wholesaler = Wholesaler::get();
        return view('backEnd.wholeseller.editpayment',compact('edit_pay','wholesaler'));

    }

    public function whosalesupdate(Request $request){
        $validated = $request->validate([
        'whosaler_id'     => 'required|exists:wholesalers,id',
        'payment_method'  => 'required|in:Cash On Delivery,In Courier,Bkash',
        'advance'         => 'required|numeric|min:0.01',
        'date'            => 'required|date',
        'paynote'         => 'nullable|string|max:1000',
    ]);

    try {
        $paymentlist = PaymentHistory::findOrFail($request->hidden_id);
        
        $paymentlist->whosales_id    = $validated['whosaler_id'];
        $paymentlist->payment_method = $validated['payment_method'];
        $paymentlist->pay_amount     = $validated['advance'];
        $paymentlist->paynode        = $validated['paynote'];
        $paymentlist->date           = $validated['date'];
        
        $paymentlist->save(); // Use save() instead of update()
        return redirect()->route('wholeseller.payment')->with('success', 'Payment updated successfully.');        
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Failed to update payment. Please try again.');
    }


    }

    public function paymentdelete(Request $request){
        $delete_data = PaymentHistory::find($request->id);
        $wholesale_payment = Wholesaler::find($delete_data->whosales_id);
       $pay =  $wholesale_payment->advance - $delete_data->pay_amount;
      $wholesale_payment->advance = $pay;
      $wholesale_payment->save();
        $delete_data->delete();
        Toastr::success('Success','Payment delete successfully');
        return redirect()->back();

    }

}