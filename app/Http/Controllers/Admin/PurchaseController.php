<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Product;
use App\Models\InventoryLog;
use App\Models\Supplier;
use DB;
use Brian2694\Toastr\Facades\Toastr;


class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::orderBy('id', 'desc')->paginate(20);
        return view('backEnd.inventory.purchase.index', compact('purchases'));
    }

    public function create()
    {
        // We'll use AJAX to search products, so no need to load all products here
        return view('backEnd.inventory.purchase.create');
    }

    public function update(Request $request)
    {
        $request->validate([
            'purchase_id' => 'required|exists:purchases,id',
            'supplier_id' => 'required',
            'purchase_date' => 'required|date',
            'product_id' => 'required|array',
            'qty' => 'required|array',
            'purchase_price' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            $purchase = Purchase::with('details')->findOrFail($request->purchase_id);
            $supplier = Supplier::find($request->supplier_id);

            // Revert Stock (Add removed items back / adjust logic)
            // Simpler approach: Revert ALL old stock, then process NEW stock.
            // 1. Revert Old Stock
            foreach ($purchase->details as $detail) {
                $product = Product::find($detail->product_id);
                if ($product) {
                    $product->decrement('stock', $detail->quantity);
                    // Log the stock reversion
                    InventoryLog::create([
                        'product_id' => $product->id,
                        'type' => 'purchase_edit_revert',
                        'quantity' => -$detail->quantity, // Negative quantity for reversion
                        'ref_id' => 'PUR-EDIT-' . $purchase->id,
                        'note' => 'Stock reverted due to purchase edit (old item)',
                        'current_stock' => $product->stock
                    ]);
                }
                $detail->delete(); // Delete old details
            }

            // Update Purchase Header
            $purchase->update([
                'supplier_id' => $supplier->id,
                'supplier_name' => $supplier->name,
                'invoice_no' => $request->invoice_no,
                'purchase_date' => $request->purchase_date,
                'total_amount' => $request->total_amount, // Will be recalculated if not provided
                'note' => $request->note,
            ]);

            $total_bill = 0;

            // 2. Process New/Updated Items
            foreach ($request->product_id as $key => $productId) {
                $qty = $request->qty[$key];
                $unit_price = $request->purchase_price[$key] ?? 0;
                $line_total = $qty * $unit_price;
                $total_bill += $line_total;

                // Save New Detail
                PurchaseDetail::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $productId,
                    'quantity' => $qty,
                    'unit_price' => $unit_price,
                    'total_price' => $line_total
                ]);

                // 3. Update Product Stock & Purchase Price
                $product = Product::find($productId);
                if ($product) {
                    $product->increment('stock', $qty);
                    if ($unit_price > 0) {
                        $product->purchase_price = $unit_price;
                    }
                    $product->save();

                    // 4. Log Inventory
                    InventoryLog::create([
                        'product_id' => $product->id,
                        'type' => 'purchase_edit',
                        'quantity' => $qty,
                        'ref_id' => 'PUR-EDIT-' . $purchase->id,
                        'note' => 'Purchase edited from ' . $supplier->name,
                        'current_stock' => $product->stock
                    ]);
                }
            }

            // Update Total if not manually set or if it needs recalculation
            if (!$request->total_amount || $request->total_amount != $total_bill) {
                $purchase->total_amount = $total_bill;
                $purchase->save();
            }

            DB::commit();
            Toastr::success('Success', 'Purchase Updated Successfully');
            return redirect()->route('admin.purchase.index');

        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Error', $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required',
            'purchase_date' => 'required|date',
            'product_id' => 'required|array',
            'qty' => 'required|array',
            'purchase_price' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            $supplier = Supplier::find($request->supplier_id);
            $purchase = Purchase::create([
                'supplier_id' => $supplier->id,
                'supplier_name' => $supplier->name,
                'invoice_no' => $request->invoice_no,
                'purchase_date' => $request->purchase_date,
                'total_amount' => $request->total_amount,
                'note' => $request->note,
            ]);

            $total_bill = 0;

            // 2. Process Items
            // 2. Process Items
            foreach ($request->product_id as $key => $productId) {
                $qty = $request->qty[$key];
                $unit_price = $request->purchase_price[$key] ?? 0;
                $line_total = $qty * $unit_price;
                $total_bill += $line_total;

                // Save Detail
                PurchaseDetail::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $productId,
                    'quantity' => $qty,
                    'unit_price' => $unit_price,
                    'total_price' => $line_total
                ]);

                // 3. Update Product Stock & Purchase Price (Optional: update purchase price?)
                $product = Product::find($productId);
                if ($product) {
                    $product->increment('stock', $qty);
                    // Update purchase price if provided and different? Let's keep it simple for now or optional.
                    if ($unit_price > 0) {
                        $product->purchase_price = $unit_price;
                    }
                    $product->save();

                    // 4. Log Inventory
                    InventoryLog::create([
                        'product_id' => $product->id,
                        'type' => 'purchase',
                        'quantity' => $qty,
                        'ref_id' => $purchase->invoice_no ?? 'PUR-' . $purchase->id,
                        'note' => 'Purchase from ' . $supplier->name,
                        'current_stock' => $product->stock
                    ]);
                }
            }

            // Update Total if not manually set
            if (!$request->total_amount) {
                $purchase->total_amount = $total_bill;
                $purchase->save();
            }

            DB::commit();
            Toastr::success('Success', 'Stock Added Successfully');
            return redirect()->route('admin.purchase.index');

        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Error', $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function searchProduct(Request $request)
    {
        $products = Product::query();
        if ($request->has('q') && !empty($request->q)) {
            $products->where('name', 'like', '%' . $request->q . '%')
                ->orWhere('product_code', 'like', '%' . $request->q . '%')
                ->orWhere('id', $request->q);
        }

        $products = $products->select('id', 'name', 'purchase_price', 'stock', 'product_code')
            ->orderBy('id', 'desc')
            ->limit(20)
            ->get();

        return response()->json($products);
    }

    public function show($id)
    {
        $purchase = Purchase::with('details.product')->findOrFail($id);
        return view('backEnd.inventory.purchase.show', compact('purchase'));
    }

    public function edit($id)
    {
        $purchase = Purchase::with('details.product')->findOrFail($id);
        return view('backEnd.inventory.purchase.edit', compact('purchase'));
    }
}
