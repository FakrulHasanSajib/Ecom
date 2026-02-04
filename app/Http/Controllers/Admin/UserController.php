<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Arr;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\PaymentHistory;
use Toastr;
use Image;
use File;
use DB;
use Hash;
class UserController extends Controller
{
    public function index(Request $request)
    {
        $data = User::orderBy('id','DESC')->where('user_type','!=','author')->get();
        return view('backEnd.users.index',compact('data'));
    }
    
    public function create()
    {
        $roles = Role::select('name')->get();
        return view('backEnd.users.create',compact('roles'));
    }
    
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
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
        if (in_array('Book Author', $request->input('roles', []))) {
                $input['user_type'] = "author";
            }
        $user = User::create($input);
        $user->assignRole($request->input('roles'));
        Toastr::success('Success','Data insert successfully');
        if (in_array('Book Author', $request->input('roles', []))) {
        return redirect()->route('author.index');
        }else{
          return redirect()->route('users.index');  
        }
    }
    
    public function edit($id)
    {
        $edit_data = User::find($id);
        $roles = Role::get();
        return view('backEnd.users.edit',compact('edit_data','roles'));
    }
    
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$request->hidden_id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);
        
        $update_data = User::find($request->hidden_id);

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
        if (in_array('Book Author', $request->input('roles', []))) {
                $input['user_type'] = "author";
            }
        $update_data->update($input);

        // role asign
        DB::table('model_has_roles')->where('model_id',$request->hidden_id)->delete();
        $update_data->assignRole($request->input('roles'));
        Toastr::success('Success','Data update successfully');
        if (in_array('Book Author', $request->input('roles', []))) {
        return redirect()->route('author.index');
        }else{
          return redirect()->route('users.index');  
        }
    }
 
    public function inactive(Request $request)
    {
        $inactive = User::find($request->hidden_id);
        $inactive->status = 0;
        $inactive->save();
        Toastr::success('Success','Data inactive successfully');
        return redirect()->back();
    }
    public function active(Request $request)
    {
        $active = User::find($request->hidden_id);
        $active->status = 1;
        $active->save();
        Toastr::success('Success','Data active successfully');
        return redirect()->back();
    }
    public function destroy(Request $request)
    {

        $delete_data = User::find($request->hidden_id);
        File::delete($delete_data->image);
        $delete_data->delete();
        Toastr::success('Success','Data delete successfully');
        return redirect()->back();
    }
    
    public function authorindex(Request $request)
    {
        $data = User::orderBy('id','DESC')->where('user_type','=','author')->get();
        return view('backEnd.loylaty.index',compact('data'));
    }
   public function authorpayment($id)
    {
        
        try {
            // Find the wholesaler/author
            $edit_data = User::findOrFail($id);
            
            // Get comprehensive summary data
            $summaryData = $this->getWholesalerSummaryOptimized($id);
            
            return view('backEnd.loylaty.paymentInfo', compact('edit_data', 'summaryData'));
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Wholesaler not found.');
        }
    }
    
    /**
     * Optimized approach using Eloquent relationships with better error handling
     */
    private function getWholesalerSummaryOptimized($wholesalerId)
{
    try {
        // Get all order details where the product belongs to this wholesaler
        $orderDetails = OrderDetails::with(['product', 'order'])
            ->whereHas('product', function ($query) use ($wholesalerId) {
                $query->where('author_id', $wholesalerId);
            })
            ->get();
        
        // Get unique orders
        $uniqueOrders = $orderDetails->pluck('order_id')->unique();
        $totalInvoices = $uniqueOrders->count();
        
        // Calculate totals
        $totalInvoiceAmount = $orderDetails->sum(function ($detail) {
            return $detail->qty * $detail->price;
        });
        
        $totalQty = $orderDetails->sum('qty');
        
        // Total loyalty from order_details or product table
        $totalLoyalty = $orderDetails->sum(function ($detail) {
            // If loyalty is stored in order_details pivot
            return $detail->loyalty ?? ($detail->product->loyalty ?? 0);
        });
        
        // Get payment history with pagination
        $paymentHistory = PaymentHistory::where('author_id', $wholesalerId)
            ->orderBy('created_at', 'desc')
            ->paginate(50);
        
        // Total paid (withdrawals)
        $totalPaid = PaymentHistory::where('author_id', $wholesalerId)
            ->where('payment_method', 'withdrawal')
            ->sum('pay_amount');
        
        // Total advance payments
        $totalAdvance = PaymentHistory::where('author_id', $wholesalerId)
            ->whereIn('payment_method', ['Advance', 'advance'])
            ->sum('pay_amount');
        
        // Net invoice amount = invoice amount - advance
        $netInvoiceAmount = $totalInvoiceAmount - $totalAdvance;
        
        // Due amount = net invoice - total paid
        $dueAmount = $totalLoyalty - $totalPaid;
        
        // Additional metrics
        $lastPayment = PaymentHistory::where('author_id', $wholesalerId)
            ->orderBy('created_at', 'desc')
            ->first();
        
        $totalTransactions = PaymentHistory::where('author_id', $wholesalerId)->count();
        
        return [
            'total_invoices' => $totalInvoices,
            'total_invoice_amount' => $totalInvoiceAmount,
            'total_qty' => $totalQty,
            'total_loyalty' => $totalLoyalty,
            'total_paid' => $totalPaid,
            'total_advance' => $totalAdvance,
            'net_invoice_amount' => $netInvoiceAmount,
            'due_amount' => $dueAmount,
            'payment_history' => $paymentHistory,
            'payment_percentage' => $netInvoiceAmount > 0 ? ($totalPaid / $netInvoiceAmount) * 100 : 0,
            'average_order_value' => $totalInvoices > 0 ? $totalInvoiceAmount / $totalInvoices : 0,
            'last_payment_date' => $lastPayment ? $lastPayment->created_at : null,
            'total_transactions' => $totalTransactions
        ];
        
    } catch (\Exception $e) {
        \Log::error('Wholesaler Summary Error: ' . $e->getMessage());
        
        return [
            'total_invoices' => 0,
            'total_invoice_amount' => 0,
            'total_qty' => 0,
            'total_loyalty' => 0,
            'total_paid' => 0,
            'total_advance' => 0,
            'net_invoice_amount' => 0,
            'due_amount' => 0,
            'payment_history' => collect(),
            'payment_percentage' => 0,
            'average_order_value' => 0,
            'last_payment_date' => null,
            'total_transactions' => 0
        ];
    }
}
    
    /**
     * Alternative high-performance approach using raw queries
     */
    private function getWholesalerSummaryRaw($wholesalerId)
    {
        try {
            // Single query to get invoice summary
            $invoiceSummary = DB::table('orders')
                ->join('products', 'orders.product_id', '=', 'products.id')
                ->where('products.author_id', $wholesalerId)
                ->selectRaw('
                    COUNT(DISTINCT orders.id) as total_invoices,
                    SUM(products.loyalty) as total_invoice_amount,
                    AVG(products.loyalty) as average_order_value
                ')
                ->first();
            
            // Get return summary
            $returnSummary = DB::table('product_returns')
                ->where('wholesaler_id', $wholesalerId)
                ->selectRaw('
                    COUNT(*) as total_returns,
                    COALESCE(SUM(amount), 0) as total_return_amount
                ')
                ->first();
            
            // Get payment summary
            $paymentSummary = DB::table('payment_histories')
                ->where('author_id', $wholesalerId)
                ->selectRaw('
                    COALESCE(SUM(pay_amount), 0) as total_paid,
                    COALESCE(SUM(CASE WHEN payment_method = "Advance" THEN pay_amount ELSE 0 END), 0) as total_advance,
                    COUNT(*) as total_transactions
                ')
                ->first();
            
            // Get payment history for display with pagination
            $paymentHistory = PaymentHistory::where('author_id', $wholesalerId)
                ->orderBy('created_at', 'desc')
                ->paginate(50);
            
            // Get last payment date
            $lastPayment = PaymentHistory::where('author_id', $wholesalerId)
                ->orderBy('created_at', 'desc')
                ->first();
            
            // Calculate derived values with null safety
            $totalInvoiceAmount = $invoiceSummary->total_invoice_amount ?? 0;
            $totalReturnAmount = $returnSummary->total_return_amount ?? 0;
            $totalPaid = $paymentSummary->total_paid ?? 0;
            
            $netInvoiceAmount = $totalInvoiceAmount - $totalReturnAmount;
            $dueAmount = $netInvoiceAmount - $totalPaid;
            
            return [
                'total_invoices' => $invoiceSummary->total_invoices ?? 0,
                'total_invoice_amount' => $totalInvoiceAmount,
                'total_returns' => $returnSummary->total_returns ?? 0,
                'total_return_amount' => $totalReturnAmount,
                'total_paid' => $totalPaid,
                'total_advance' => $paymentSummary->total_advance ?? 0,
                'net_invoice_amount' => $netInvoiceAmount,
                'due_amount' => $dueAmount,
                'payment_history' => $paymentHistory,
                'payment_percentage' => $netInvoiceAmount > 0 ? ($totalPaid / $netInvoiceAmount) * 100 : 0,
                'average_order_value' => $invoiceSummary->average_order_value ?? 0,
                'last_payment_date' => $lastPayment ? $lastPayment->created_at : null,
                'total_transactions' => $paymentSummary->total_transactions ?? 0
            ];
            
        } catch (\Exception $e) {
            // Return default structure if error occurs
            return [
                'total_invoices' => 0,
                'total_invoice_amount' => 0,
                'total_returns' => 0,
                'total_return_amount' => 0,
                'total_paid' => 0,
                'total_advance' => 0,
                'net_invoice_amount' => 0,
                'due_amount' => 0,
                'payment_history' => collect(),
                'payment_percentage' => 0,
                'average_order_value' => 0,
                'last_payment_date' => null,
                'total_transactions' => 0
            ];
        }
    }
    
    /**
     * Handle payment form submission
     */
    public function paymentUpdate(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'author_id' => 'required|exists:users,id',
            'payment_method' => 'required|string|in:Cash,Bkash,Bank Transfer,Advance,Cheque,Online',
            'pay_amount' => 'required|numeric|min:0.01|max:999999.99',
            'date' => 'required|date|before_or_equal:today',
            'paynote' => 'nullable|string|max:500'
        ], [
            'author_id.required' => 'Wholesaler selection is required.',
            'author_id.exists' => 'Selected wholesaler does not exist.',
            'payment_method.required' => 'Payment method is required.',
            'payment_method.in' => 'Invalid payment method selected.',
            'pay_amount.required' => 'Payment amount is required.',
            'pay_amount.numeric' => 'Payment amount must be a valid number.',
            'pay_amount.min' => 'Payment amount must be at least 0.01.',
            'pay_amount.max' => 'Payment amount cannot exceed 999,999.99.',
            'date.required' => 'Payment date is required.',
            'date.date' => 'Invalid date format.',
            'date.before_or_equal' => 'Payment date cannot be in the future.',
            'paynote.max' => 'Payment note cannot exceed 500 characters.'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please correct the errors and try again.');
        }
        
        try {
            DB::beginTransaction();
            
            // Verify wholesaler exists and get current due amount
            $wholesaler = User::findOrFail($request->author_id);
            $currentSummary = $this->getWholesalerSummaryOptimized($request->author_id);
            
            // Optional: Check if payment exceeds due amount (for validation)
            $paymentAmount = (float) $request->pay_amount;
            $currentDueAmount = $currentSummary['due_amount'];
            
            // Create payment record
            $payment = PaymentHistory::create([
                'author_id' => $request->author_id,
                'payment_method' => $request->payment_method,
                'pay_amount' => $paymentAmount,
                'date' => $request->date,
                'paynote' => $request->paynote,
                'created_by' => auth()->id(), // Track who created the payment
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            // Optional: Update wholesaler's last payment date or balance if you maintain it
            $wholesaler->update([
                'last_payment_date' => $request->date,
                'updated_at' => now()
            ]);
            
            // Log the activity (optional)
            activity()
                ->performedOn($payment)
                ->causedBy(auth()->user())
                ->withProperties([
                    'wholesaler_name' => $wholesaler->business_name,
                    'amount' => $paymentAmount,
                    'method' => $request->payment_method
                ])
                ->log('Payment recorded');
            
            DB::commit();
            
            return redirect()->back()->with('success', 
                'Payment of ৳' . number_format($paymentAmount, 2) . ' recorded successfully!'
            );
            
        } catch (\Exception $e) {
            DB::rollback();
            
            // Log the error (optional)
            \Log::error('Payment recording failed: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'wholesaler_id' => $request->author_id,
                'amount' => $request->pay_amount
            ]);
            
            return redirect()->back()->with('error', 
                'Failed to record payment. Please try again or contact support.'
            );
        }
    }
    
    /**
     * Export payment history to CSV
     */
    public function exportPayments($wholesalerId)
    {
        try {
            $wholesaler = User::findOrFail($wholesalerId);
            $payments = PaymentHistory::where('author_id', $wholesalerId)
                ->orderBy('date', 'desc')
                ->get();
            
            $filename = 'payments_' . str_slug($wholesaler->business_name) . '_' . date('Y-m-d') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];
            
            $callback = function() use ($payments) {
                $file = fopen('php://output', 'w');
                
                // CSV Headers
                fputcsv($file, [
                    'Date',
                    'Payment Method',
                    'Amount (৳)',
                    'Reference/Note',
                    'Created At'
                ]);
                
                // CSV Data
                foreach ($payments as $payment) {
                    fputcsv($file, [
                        date('d-m-Y', strtotime($payment->date)),
                        $payment->payment_method,
                        number_format($payment->pay_amount, 2),
                        $payment->paynote ?? '',
                        $payment->created_at->format('d-m-Y H:i:s')
                    ]);
                }
                
                fclose($file);
            };
            
            return response()->stream($callback, 200, $headers);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to export payment data.');
        }
    }
    
    /**
     * Get payment details for AJAX requests
     */
    public function getPaymentDetails($paymentId)
    {
        try {
            $payment = PaymentHistory::with(['author:id,name,business_name'])
                ->findOrFail($paymentId);
            
            return response()->json([
                'success' => true,
                'data' => $payment
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Payment not found'
            ], 404);
        }
    }
    
    /**
     * Update existing payment
     */
    public function updatePayment(Request $request, $paymentId)
    {
        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|string|in:Cash,Bkash,Bank Transfer,Advance,Cheque,Online',
            'pay_amount' => 'required|numeric|min:0.01|max:999999.99',
            'date' => 'required|date|before_or_equal:today',
            'paynote' => 'nullable|string|max:500'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please correct the errors and try again.');
        }
        
        try {
            DB::beginTransaction();
            
            $payment = PaymentHistory::findOrFail($paymentId);
            $oldAmount = $payment->pay_amount;
            
            $payment->update([
                'payment_method' => $request->payment_method,
                'pay_amount' => $request->pay_amount,
                'date' => $request->date,
                'paynote' => $request->paynote,
                'updated_by' => auth()->id(),
                'updated_at' => now()
            ]);
            
            // Log the activity
            activity()
                ->performedOn($payment)
                ->causedBy(auth()->user())
                ->withProperties([
                    'old_amount' => $oldAmount,
                    'new_amount' => $request->pay_amount
                ])
                ->log('Payment updated');
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Payment updated successfully!');
            
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to update payment.');
        }
    }
    
    /**
     * Delete payment (soft delete recommended)
     */
    public function deletePayment($paymentId)
    {
        try {
            DB::beginTransaction();
            
            $payment = PaymentHistory::findOrFail($paymentId);
            
            // Soft delete or mark as cancelled
            $payment->update([
                'status' => 'cancelled',
                'cancelled_by' => auth()->id(),
                'cancelled_at' => now()
            ]);
            
            // Or use soft delete: $payment->delete();
            
            activity()
                ->performedOn($payment)
                ->causedBy(auth()->user())
                ->log('Payment cancelled');
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Payment cancelled successfully!');
            
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to cancel payment.');
        }
    }
    
    /**
     * Get wholesaler invoices
     */
    public function getWholesalerInvoices($wholesalerId)
    {
        try {
            $wholesaler = User::findOrFail($wholesalerId);
            
            $invoices = Order::with(['products' => function($query) use ($wholesalerId) {
                $query->where('author_id', $wholesalerId);
            }])
            ->whereHas('products', function($query) use ($wholesalerId) {
                $query->where('author_id', $wholesalerId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
            return view('backEnd.loylaty.invoices', compact('wholesaler', 'invoices'));
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Wholesaler not found.');
        }
    }
}