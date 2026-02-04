<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use App\Models\InventoryLog;
use App\Models\CourierLedger;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Maatwebsite\Excel\Facades\Excel;

class CourierManageController extends Controller
{
    public function index()
    {
        // লেজার ডাটা আনা (নতুন থেকে পুরাতন)
        $ledgers = CourierLedger::orderBy('id', 'desc')->paginate(10);
        
        return view('backEnd.courier.import', compact('ledgers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'courier_name' => 'required',
            'file' => 'required|mimes:csv,txt,xlsx,xls',
        ]);

        try {
            // ১. ফাইল রিড করা
            $data = Excel::toArray([], $request->file('file'));
            
            if (empty($data) || count($data[0]) < 1) {
                Toastr::error('ফাইলটি খালি!', 'Error');
                return redirect()->back();
            }

            $sheet = $data[0];
            $header = $sheet[0];
            
            // হেডার ক্লিন করা
            $cleanHeader = array_map(function($h) {
                return strtolower(trim($h));
            }, $header);

            // ২. কলাম ইনডেক্স খোঁজা
            // ডিফল্ট ইনডেক্স আপনার ফাইল অনুযায়ী সেট করা হলো
            $trackingIndex = 3; 
            $invoiceIndex = 4;
            $phoneIndex = 6;
            $statusIndex = 8;
            $amountIndex = 12;

            // ডাইনামিক সার্চ (যদি কলাম সরে যায়)
            foreach ($cleanHeader as $key => $col) {
                if (in_array($col, ['tracking code', 'tracking_id'])) $trackingIndex = $key;
                elseif (in_array($col, ['invoice', 'invoice id'])) $invoiceIndex = $key;
                elseif (in_array($col, ['recipient phone', 'phone', 'customer phone'])) $phoneIndex = $key;
                elseif (in_array($col, ['delivery status', 'status'])) $statusIndex = $key;
                elseif (in_array($col, ['cod amount', 'amount'])) $amountIndex = $key;
            }

            $ledger = new CourierLedger();
            $ledger->courier_name = $request->courier_name;
            $ledger->sheet_name = $request->file('file')->getClientOriginalName();
            $ledger->save();

            $total_credit = 0;
            $delivered_count = 0;
            $returned_count = 0;

            DB::beginTransaction();

            for ($i = 1; $i < count($sheet); $i++) {
                $row = $sheet[$i];

                // ডাটা নেওয়া
                $csv_tracking = isset($row[$trackingIndex]) ? trim($row[$trackingIndex]) : null;
                $csv_invoice  = isset($row[$invoiceIndex]) ? trim($row[$invoiceIndex]) : null;
                $csv_phone    = isset($row[$phoneIndex]) ? preg_replace('/[^0-9]/', '', $row[$phoneIndex]) : null; // শুধু নাম্বার
                $csv_amount   = isset($row[$amountIndex]) ? floatval(str_replace(',', '', $row[$amountIndex])) : 0;
                $csv_status   = isset($row[$statusIndex]) ? strtolower(trim($row[$statusIndex])) : '';

                // অর্ডার খোঁজা শুরু
                $order = null;

                // ১. ট্র্যাকিং আইডি দিয়ে চেষ্টা
                if (!empty($csv_tracking)) {
                    $order = Order::where('tracking_id', $csv_tracking)->first();
                }

                // ২. ইনভয়েস দিয়ে চেষ্টা (যদি ট্র্যাকিং দিয়ে না পায়)
                if (!$order && !empty($csv_invoice)) {
                    $order = Order::where('invoice_id', $csv_invoice)->orWhere('id', $csv_invoice)->first();
                }

                // ৩. [ব্যাকআপ] ফোন নাম্বার এবং এমাউন্ট দিয়ে চেষ্টা (Last Resort)
                if (!$order && !empty($csv_phone) && $csv_amount > 0) {
                    $shortPhone = substr($csv_phone, -10); 
                    
                    $order = Order::whereHas('shipping', function($q) use ($shortPhone) {
                                    $q->where('phone', 'like', "%$shortPhone%");
                                })
                                ->where('amount', $csv_amount) // টাকার পরিমাণ মিলতে হবে
                                ->where('order_status', '!=', 10) // যেটি অলরেডি ডেলিভারড না
                                ->where('order_status', '!=', 11) // যেটি অলরেডি রিটার্ন না
                                ->orderBy('id', 'desc') // ⭐ সবচেয়ে নতুন অর্ডারটি আগে ধরবে
                                ->first();
                }

                // অর্ডার পাওয়া গেলে আপডেট
                if ($order) {
                    // === Delivered ===
                    if (str_contains($csv_status, 'eliver') && !str_contains($csv_status, 'cancell')) {
                        if ($order->order_status != 10) {
                            $order->order_status = 10;
                            // $order->payment_status = 'paid'; // orders টেবিলে এই কলাম নেই তাই কমেন্ট করা হলো
                            $order->save();

                            $payment = Payment::where('order_id', $order->id)->first();
                            if ($payment) {
                                $payment->payment_status = 'paid';
                                $payment->amount = $csv_amount;
                                $payment->save();
                            }
                            $total_credit += $csv_amount;
                            $delivered_count++;
                        }
                    }
                    // === Returned / Cancelled ===
                    elseif (str_contains($csv_status, 'return') || str_contains($csv_status, 'cancell')) {
                        if ($order->order_status != 11) {
                            $order->order_status = 11;
                            $order->save();

                            // Stock Return Logic
                            $orderDetails = OrderDetails::where('order_id', $order->id)->get();
                            foreach ($orderDetails as $detail) {
                                $product = Product::find($detail->product_id);
                                if ($product) {
                                    $product->increment('stock', $detail->qty);
                                    
                                    InventoryLog::create([
                                        'product_id' => $product->id,
                                        'type' => 'courier_return',
                                        'quantity' => $detail->qty,
                                        'ref_id' => $order->invoice_id,
                                        'note' => 'Auto Return (Phone Match)',
                                        'current_stock' => $product->stock
                                    ]);
                                }
                            }
                            $returned_count++;
                        }
                    }
                }
            }

            // লেজার আপডেট
            $ledger->total_credit = $total_credit;
            $ledger->delivered_orders = $delivered_count;
            $ledger->returned_orders = $returned_count;
            $ledger->save();

            DB::commit();

            // === বিস্তারিত মেসেজ লজিক ===
            $total_rows = count($sheet) - 1; // হেডার বাদে মোট রো সংখ্যা
            $updated_count = $delivered_count + $returned_count; // মোট কতগুলো কাজ হয়েছে
            
            // মেসেজ তৈরি
            if ($updated_count > 0) {
                Toastr::success("সফল! মোট রো: $total_rows | ডেলিভারড: $delivered_count | রিটার্ন: $returned_count", 'Success');
            } else {
                Toastr::warning("ফাইল আপলোড হয়েছে কিন্তু কোনো অর্ডার ম্যাচ করেনি। (মোট রো: $total_rows)", 'No Match Found');
            }
            
            return redirect()->back();

        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Error: ' . $e->getMessage(), 'Error');
            return redirect()->back();
        }
    }
}