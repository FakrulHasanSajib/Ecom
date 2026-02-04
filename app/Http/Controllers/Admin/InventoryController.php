<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\InventoryLog;
use App\Models\Purchase;
use DB;
use Toastr;

class InventoryController extends Controller
{
    // Dashboard: Overview of Stock
    public function dashboard()
    {
        $total_products = Product::count();
        $total_stock_qty = Product::sum('stock');
        // Calculate Total Stock Value (Purchase Price * Stock)
        $total_stock_value = Product::select(DB::raw('SUM(stock * purchase_price) as total_value'))->value('total_value');

        $low_stock_products = Product::where('stock', '<', 10)->paginate(20);

        return view('backEnd.inventory.dashboard', compact('total_products', 'total_stock_qty', 'total_stock_value', 'low_stock_products'));
    }

    // Shipping Manager: The Scanner Page
    public function shipping()
    {
        return view('backEnd.inventory.shipping');
    }

    // AJAX: Fetch Order Details for Scanning
  public function shippingFetch(Request $request)
    {
        $order_id = $request->invoice_id;

       $order = Order::where('invoice_id', $order_id)
    ->orWhere('id', $order_id)
    // নিচে 'wholesaler' যোগ করা হয়েছে
    ->with('orderdetails', 'customer', 'status', 'shipping', 'wholesaler') 
    ->first();

        if (!$order) {
            return response()->json(['status' => 'error', 'message' => 'Order not found!']);
        }

        $status_id = $order->order_status;

        // --- VALIDATION LOGIC BASED ON YOUR DASHBOARD CONTROLLER ---

        // 1. Check if Cancelled (ID 7) or Failed (ID 9) or Return (ID 6)
       if (!$order) {
            return response()->json(['status' => 'error', 'message' => 'Order not found!']);
        }

        $status_id = $order->order_status;

        // --- EXCLUSION LOGIC (যেইগুলো শিপ করা যাবে না) ---

        // ১. যদি Order Cancelled হয় (ID 7)
        if ($status_id == 7) {
            return response()->json(['status' => 'error', 'message' => 'Order is Cancelled (ID: 7)! Cannot ship.']);
        }

        // ২. যদি Order অলরেডি Courier এ থাকে (ID 4)
        if ($status_id == 4) {
            return response()->json(['status' => 'error', 'message' => 'Order is Already in Courier (ID: 4)!']);
        }

        // ৩. যদি Order Delivered/Completed হয়ে যায় (ID 5)
        if ($status_id == 5) {
            return response()->json(['status' => 'error', 'message' => 'Order is Already Delivered (ID: 5)!']);
        }

        // --- ALLOW OTHERS ---
        // উপরে যেই ৩টি বাদ দেওয়া হয়েছে (4, 5, 7), সেগুলো ছাড়া বাকি সব (1, 2, 3, 6, 8, 9) শিপ করা যাবে।
        
        return response()->json(['status' => 'success', 'data' => $order]);
    }

        // Note: We allow Pending (1), Processing (2), Shipped (3), On Hold (8) to be processed.

       
    

    // Process Shipping: Deduct Stock
    // Process Shipping: Deduct Stock
   public function shippingConfirm(Request $request)
    {
        $order_id = $request->order_id;
        $courier_service = $request->courier_service; // Frontend থেকে আসা courier info (steadfast/pathao/manual)

        $order = Order::with('orderdetails')->find($order_id);

        if (!$order) {
            return response()->json(['status' => 'error', 'message' => 'Order not found']);
        }

        DB::beginTransaction();
        try {
            foreach ($order->orderdetails as $item) {
                $product = Product::find($item->product_id);

                if (!$product)
                    continue;

                if ($product->stock < $item->qty) {
                    throw new \Exception("Insufficient stock for product: " . $product->name);
                }

                // 1. Deduct Stock
                $product->decrement('stock', $item->qty);

                // 2. Log Inventory
                InventoryLog::create([
                    'product_id' => $product->id,
                    'type' => 'sale',
                    'quantity' => -$item->qty,
                    'ref_id' => $order->invoice_id,
                    'note' => 'Order Shipped via ' . ucfirst($courier_service),
                    'current_stock' => $product->stock
                ]);
            }

            // 3. Update Order Status and Courier Info
            // Assuming status 'On Delivery' (4)
            $order->order_status = 5;
            
            // [FIXED] ডাটাবেসের কলাম নাম 'courier' অনুযায়ী আপডেট করা হলো
            $order->courier = $courier_service; 
            
            $order->save();

            DB::commit();

            return response()->json(['status' => 'success', 'message' => 'Order Processed & Shipped via ' . ucfirst($courier_service)]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    // Return Manager Page
    public function return()
    {
        return view('backEnd.inventory.return');
    }

    // Return Fetch
    public function returnFetch(Request $request)
    {
        $order_id = $request->invoice_id;
        $order = Order::where('invoice_id', $order_id)
            ->orWhere('id', $order_id)
            ->with('orderdetails', 'customer', 'status', 'shipping', 'wholesaler') 
            ->first();

        if (!$order) {
            return response()->json(['status' => 'error', 'message' => 'Order not found!']);
        }

        return response()->json([
            'status' => 'success',
            'data' => $order,
            'status_text' => $order->status ? $order->status->name : 'Unknown'
        ]);
    }

    // Process Return
    public function returnProcess(Request $request)
    {
        try {
            DB::beginTransaction();

            $order = Order::findOrFail($request->order_id);
            $type = $request->type;
            $items = $request->items; // Array of items from form

            $returned_count = 0;

            foreach ($items as $item_id => $data) {
                // If partial mode, skip unchecked items
                if ($type == 'partial' && !isset($data['checked'])) {
                    continue;
                }

                $detail = OrderDetails::find($item_id);
                if (!$detail)
                    continue;

                $qty_to_return = ($type == 'full') ? $detail->qty : intval($data['qty']);

                if ($qty_to_return <= 0)
                    continue;
                if ($qty_to_return > $detail->qty)
                    $qty_to_return = $detail->qty; // Safety cap

                // Increment Stock
                $product = Product::find($detail->product_id);
                if ($product) {
                    $product->increment('stock', $qty_to_return);

                    // Log Inventory
                    InventoryLog::create([
                        'product_id' => $product->id,
                        'type' => 'return',
                        'quantity' => $qty_to_return,
                        'ref_id' => $order->invoice_id,
                        'note' => 'Order Return (' . ucfirst($type) . ')',
                        'current_stock' => $product->stock
                    ]);

                    $returned_count++;
                }
            }

            if ($returned_count == 0) {
                throw new \Exception("No items selected for return");
            }

            // Optionally update order status
            if ($type == 'full') {
                // 6 = Cancelled / Returned. Adjust based on system.
                // $order->order_status = 6; 
                // $order->save();
            }

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Stock Returned Successfully!']);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    // Inventory Logs Report
    public function logs()
    {
        $logs = InventoryLog::with('product')->orderBy('created_at', 'desc')->paginate(50);
        return view('backEnd.inventory.logs', compact('logs'));
    }

    // Stock List
    public function stockList()
    {
        $products = Product::with('image')
            ->select('id', 'name', 'product_code', 'purchase_price', 'stock')
            ->selectRaw('(stock * purchase_price) as total_value')
            ->orderBy('stock', 'desc')
            ->get();

        $grand_total = $products->sum('total_value');

        return view('backEnd.inventory.stock_list', compact('products', 'grand_total'));
    }
    
}
