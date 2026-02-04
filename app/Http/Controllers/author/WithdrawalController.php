<?php

namespace App\Http\Controllers\author;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentHistory;
use App\Models\OrderDetails;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Hash;

class WithdrawalController extends Controller
{
    /**
     * Display withdrawal page
     */
    public function index()
    {
        $wholesalerId = Auth::user()->id;

        // Calculate loyalty earnings
        $orderDetails = OrderDetails::with(['product'])
            ->whereHas('product', function ($query) use ($wholesalerId) {
                $query->where('author_id', $wholesalerId);
            })
            ->get();

        // Total loyalty earned
        $totalLoyalty = $orderDetails->sum(function ($detail) {
            return $detail->loyalty ?? ($detail->product->loyalty ?? 0);
        });

        // Total withdrawn (completed withdrawals)
        $totalWithdrawn = PaymentHistory::where('author_id', $wholesalerId)
            ->where('status',3)
            ->sum('pay_amount');

        // Pending withdrawal requests
        $pendingAmount = PaymentHistory::where('author_id', $wholesalerId)
            ->whereIn('status', [0,2,1])
            ->sum('pay_amount');

        // Available balance
        $availableBalance = $totalLoyalty - $totalWithdrawn - $pendingAmount;

        // Recent withdrawals (last 5)
        $recentWithdrawals = PaymentHistory::where('author_id', $wholesalerId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // All withdrawal history with pagination
        $withdrawalHistory = PaymentHistory::where('author_id', $wholesalerId)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('author.payrequest', compact(
            'totalLoyalty',
            'totalWithdrawn',
            'pendingAmount',
            'availableBalance',
            'recentWithdrawals',
            'withdrawalHistory'
        ));
    }

    /**
     * Store withdrawal request
     */
    public function store(Request $request)
    {
        $wholesalerId = Auth::user()->id;

        // Validate request
        $validator = Validator::make($request->all(), [
            'pay_amount' => 'required|numeric|min:100',
            'payment_method' => 'required',
            'account_number' => 'required|string|max:100',
            'account_name' => 'required|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'note' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Calculate available balance
        $orderDetails = OrderDetails::with(['product'])
            ->whereHas('product', function ($query) use ($wholesalerId) {
                $query->where('author_id', $wholesalerId);
            })
            ->get();

        $totalLoyalty = $orderDetails->sum(function ($detail) {
            return $detail->loyalty ?? ($detail->product->loyalty ?? 0);
        });

        $totalWithdrawn = PaymentHistory::where('author_id', $wholesalerId)
            ->where('status', 3)
            ->sum('pay_amount');

        $pendingAmount = PaymentHistory::where('author_id', $wholesalerId)
            ->whereIn('status', [0, 2, 1])
            ->sum('pay_amount');

        $availableBalance = $totalLoyalty - $totalWithdrawn - $pendingAmount;

        // Check if sufficient balance
        if ($request->amount > $availableBalance) {
            return redirect()->back()
                ->with('error', 'Insufficient balance. Available balance: ৳' . number_format($availableBalance, 2))
                ->withInput();
        }

        // Check minimum withdrawal amount
        if ($request->pay_amount < 100) {
            return redirect()->back()
                ->with('error', 'Minimum withdrawal amount is ৳100')
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Create withdrawal request
            $withdrawal = new PaymentHistory();
            $withdrawal->author_id = $wholesalerId;
            $withdrawal->pay_amount = $request->pay_amount;
            $withdrawal->payment_method = $request->payment_method;
            $withdrawal->account_number = $request->account_number;
            $withdrawal->account_name = $request->account_name;
            $withdrawal->bank_name = $request->bank_name;
            $withdrawal->note = $request->note;
            $withdrawal->status = 0;
            $withdrawal->save();

            DB::commit();

            return redirect()->back()
                ->with('success', 'Withdrawal request submitted successfully! Request ID: #' . $withdrawal->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to submit withdrawal request. Please try again.')
                ->withInput();
        }
    }

    /**
     * Cancel withdrawal request (only if pending)
     */
    public function cancel($id)
    {
        $wholesalerId = Auth::user()->id;

        $withdrawal = PaymentHistory::where('id', $id)
            ->where('author_id', $wholesalerId)
            ->where('status', 'pending')
            ->first();

        if (!$withdrawal) {
            return redirect()->back()
                ->with('error', 'Withdrawal request not found or cannot be cancelled.');
        }

        try {
            $withdrawal->status = 'cancelled';
            $withdrawal->admin_note = 'Cancelled by author';
            $withdrawal->save();

            return redirect()->back()
                ->with('success', 'Withdrawal request cancelled successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to cancel withdrawal request.');
        }
    }
}