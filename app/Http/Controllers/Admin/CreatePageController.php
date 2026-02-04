<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CreatePage;
use App\Models\Flashdeal;
use App\Models\Flashdealpro;
use App\Models\Product;
use App\Models\Authorloylity;
use App\Models\User;
use App\Models\PaymentHistory;
use App\Models\SmsTeamplate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Toastr;
use Str;
use File;
class CreatePageController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:page-list|page-create|page-edit|page-delete', ['only' => ['index','store']]);
         $this->middleware('permission:page-create', ['only' => ['create','store']]);
         $this->middleware('permission:page-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:page-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $show_data = CreatePage::orderBy('id','DESC')->get();
        return view('backEnd.createpage.index',compact('show_data'));
    }
    public function create()
    {
        return view('backEnd.createpage.create');
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'title' => 'required',
            'description' => 'required',
            'status' => 'required',
        ]);

        $input = $request->all();
        $input['slug'] = strtolower(preg_replace('/\s+/', '-', $request->name));
        CreatePage::create($input);
        Toastr::success('Success','Data insert successfully');
        return redirect()->route('pages.index');
    }
    
    public function edit($id)
    {
        $edit_data = CreatePage::find($id);
        return view('backEnd.createpage.edit',compact('edit_data'));
    }
    
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'title' => 'required',
            'description' => 'required',
        ]);
        $input = $request->except('hidden_id');
        $input['slug'] = strtolower(preg_replace('/\s+/', '-', $request->name));
        $update_data = CreatePage::find($request->hidden_id);
        $update_data->update($input);

        Toastr::success('Success','Data update successfully');
        return redirect()->route('pages.index');
    }
 
    public function inactive(Request $request)
    {
        $inactive = CreatePage::find($request->hidden_id);
        $inactive->status = 0;
        $inactive->save();
        Toastr::success('Success','Data inactive successfully');
        return redirect()->back();
    }
    public function active(Request $request)
    {
        $active = CreatePage::find($request->hidden_id);
        $active->status = 1;
        $active->save();
        Toastr::success('Success','Data active successfully');
        return redirect()->back();
    }
    public function destroy(Request $request)
    {
        $delete_data = CreatePage::find($request->hidden_id);
        $delete_data->delete();
        Toastr::success('Success','Data delete successfully');
        return redirect()->back();
    }
    
    public function hotdeal(){
        $flash_deal = FlashDeal::get();

        return view('backEnd.hotdeal.index',compact('flash_deal'));
    }

    public function hotdealcreate(){
        return view('backEnd.hotdeal.create');
    }

    public function hotdealStore(Request $request){

        $flash_deal = new FlashDeal;
        $flash_deal->title = $request->title;


        $flash_deal->start_date = strtotime($request->start_date);
        $flash_deal->end_date   = strtotime($request->end_date);

        $flash_deal->slug = Str::slug($request->title).'-'.Str::random(5);
         // image with intervention
         $file = $request->file('banner');
         $name = time().$file->getClientOriginalName();
         $uploadPath = 'public/uploads/banner/';
         $file->move($uploadPath,$name);
         $fileUrl =$uploadPath.$name;
        $flash_deal->banner = $fileUrl;
        

        if($flash_deal->save()){

            foreach ($request->product_id as $key => $product) {
                $flash_deal_product = new Flashdealpro;
                $flash_deal_product->flash_deal_id = $flash_deal->id;
                $flash_deal_product->product_id = $product;
                $flash_deal_product->save();

                $root_product = Product::findOrFail($product);
                $root_product->new_price = $request['discount_'.$product];

                $root_product->save();
            }

        }

        Toastr::success('Success','Data Save successfully');
        return redirect()->back();

    }

    public function flashedit($id)
    {
        $edit_data = FlashDeal::find($id);
        return view('backEnd.hotdeal.edit',compact('edit_data'));
    }

    public function hotdealproduct(Request $request){
        $product_ids = $request->product_id;

        return view('backEnd.hotdeal.discount_flash', compact('product_ids'));

    }
    public function hotdealUpdate(Request $request)
    {
        $id = $request->id;
        $flash_deal = FlashDeal::findOrFail($id);
    $flash_deal->title = $request->title;
    $flash_deal->start_date = strtotime($request->start_date);
    $flash_deal->end_date = strtotime($request->end_date);
   // $flash_deal->slug = $request->title;

    // Only update the banner if a new image is uploaded
    if ($request->hasFile('banner')) {
        $file = $request->file('banner');
        $name = time() . $file->getClientOriginalName();
        $uploadPath = 'public/uploads/banner/';
        $file->move($uploadPath, $name);
        $fileUrl = $uploadPath . $name;
        $flash_deal->banner = $fileUrl;
    }

    $flash_deal->save();

    // Update or create the flash deal products
    foreach ($request->product_id as $key => $product) {
        // Check if the product is already associated with the flash deal
        $flash_deal_product = Flashdealpro::firstOrNew([
            'flash_deal_id' => $flash_deal->id,
            'product_id' => $product
        ]);

        // Update the price (if needed)
        $flash_deal_product->flash_deal_id = $flash_deal->id;
        $flash_deal_product->product_id = $product;
        $flash_deal_product->save();

        // Update the product's new price
        $root_product = Product::findOrFail($product);
        $root_product->new_price = $request['discount_' . $product];
        $root_product->save();
    }

        Toastr::success('Success','Data Update successfully');
        return redirect()->back();
    }

    public function hotdealedit(Request $request){
        $product_ids = $request->product_id;
        $flash_deal_id = $request->flash_deal_id;

        return view('backEnd.hotdeal.discount_edit_flash', compact('product_ids', 'flash_deal_id'));
    }

    public function flashdelete(Request $request)
    {

        $delete_data = FlashDeal::find($request->hidden_id);
        $banner_path = public_path($delete_data->banner);

                if (File::exists($banner_path)) {
                    File::delete($banner_path);
                }
        $product = Flashdealpro::where('flash_deal_id',$delete_data->id)->get();

        foreach($product as $key=>$value){
            $product_delete = Flashdealpro::find($value->id);
            $product_delete->delete();
        }
        $delete_data->delete();
        Toastr::success('Success','Data delete successfully');
        return redirect()->back();
    }
    
    public function flashinactive(Request $request)
    {
        $inactive = FlashDeal::find($request->hidden_id);
        $inactive->status = 0;
        $inactive->save();
        Toastr::success('Success','Data inactive successfully');
        return redirect()->back();
    }
    public function flashactive(Request $request)
    {
        $active = FlashDeal::find($request->hidden_id);
        $active->status = 1;
        $active->save();
        Toastr::success('Success','Data active successfully');
        return redirect()->back();
    }
    public function loylatymanage(){
        
        $authorloylity = Authorloylity::get();
        return view('backEnd.loylaty.indexmanage', compact('authorloylity'));
    }
    public function loylatycreate(){
        $user = User::where('user_type','=','author')->get();
        return view('backEnd.loylaty.create', compact('user'));
    }
    
    public function loyalityproduct(Request $request){
        $product_ids = $request->product_id;

        return view('backEnd.loylaty.loyality_flash', compact('product_ids'));

    }
    
    public function loyalityStore(Request $request){
        $request->validate([
            'title' => 'required|string|max:255',
            'author_id' => 'required|integer|exists:users,id',
            'status' => 'nullable|boolean',
            'product_id' => 'nullable|array',
            'banner' => 'nullable|image|max:2048', // 2MB max
        ]);
        $loyalityAuthor = new Authorloylity;
    $loyalityAuthor->titile = $request->title;
    $loyalityAuthor->author_id = $request->author_id;
    $loyalityAuthor->status = $request->status ? 1 : 0;
    
    // Encode product IDs as JSON
    $loyalityAuthor->product_id = json_encode($request->product_id);
    $loyaltyAuthor->page_link = Str::slug($request->title) . '-' . Str::random(5);
    // Handle banner upload
    if ($request->hasFile('banner')) {
        $file = $request->file('banner');
        $name = time() . '_' . $file->getClientOriginalName();
        $uploadPath = 'public/uploads/banner/';
        $file->move($uploadPath, $name);
        $fileUrl = $uploadPath . $name;
        $loyalityAuthor->banner = $fileUrl;
    }
    
    // Save the loyalty record first
    $loyalityAuthor->save();
    
    // Update products with loyalty values
    foreach ($request->product_id as $key => $product) {
        $root_product = Product::findOrFail($product);
        $root_product->author_id = $request->author_id;
        $root_product->loyalty = $request['loyality_'.$product] ?? null;
        $root_product->save();
    }
        Toastr::success('Success','Data Save successfully');
        return redirect()->back();
    }
    public function getAuthorProducts(Request $request)
  {
    $products = Product::orderBy('created_at', 'desc')
                       ->get();
    
    $options = '';
    foreach($products as $product){
        $options .= '<option value="'.$product->id.'">'.$product->name.'</option>';
    }
    
    return response()->json($options);
 }
    
     public function loylatyedit($id)
    {
        $user = User::where('user_type','=','author')->get();
        $edit_data = Authorloylity::find($id);
        
        $productedit = Product::where('author_id',$edit_data->author_id)->get();
        
        $allproduct = Product::get();
        return view('backEnd.loylaty.loyalityedit',compact('edit_data','user','productedit','allproduct'));
    }
    
    
    public function loyalityUpdate(Request $request){
         $request->validate([
            'title' => 'required|string|max:255',
            'author_id' => 'required|integer|exists:users,id',
            'status' => 'nullable|boolean',
            'product_id' => 'nullable|array',
            'banner' => 'nullable|image|max:2048', // 2MB max
        ]);
        
        $loyalityAuthor = Authorloylity::find($request->id);
    $loyalityAuthor->titile = $request->title;
    $loyalityAuthor->author_id = $request->author_id;
    $loyalityAuthor->status = $request->status ? 1 : 0;
    
    // Encode product IDs as JSON
    $loyalityAuthor->product_id = json_encode($request->product_id);
    // Handle banner upload
    if ($request->hasFile('banner')) {
        $file = $request->file('banner');
        $name = time() . '_' . $file->getClientOriginalName();
        $uploadPath = 'public/uploads/banner/';
        $file->move($uploadPath, $name);
        $fileUrl = $uploadPath . $name;
        $loyalityAuthor->banner = $fileUrl;
    }
    
    // Save the loyalty record first
    $loyalityAuthor->save();
    
    // Update products with loyalty values
    foreach ($request->product_id as $key => $product) {
        $root_product = Product::findOrFail($product);
        $root_product->author_id = $request->author_id;
        $root_product->loyalty = $request['loyality_'.$product] ?? null;
        $root_product->save();
    }
        Toastr::success('Success','Data Update successfully');
        return redirect()->back();
    }
    
    
    public function loyalitydelete(Request $request)
    {

        $delete_data = Authorloylity::find($request->hidden_id);
        $banner_path = public_path($delete_data->banner);

                if (File::exists($banner_path)) {
                    File::delete($banner_path);
                }
        
        $delete_data->delete();
        Toastr::success('Success','Data delete successfully');
        return redirect()->back();
    }
    
    public function loyalityinactive(Request $request)
    {
        $inactive = Authorloylity::find($request->hidden_id);
        $inactive->status = 0;
        $inactive->save();
        Toastr::success('Success','Data inactive successfully');
        return redirect()->back();
    }
    public function loyalityactive(Request $request)
    {
        $active = Authorloylity::find($request->hidden_id);
        $active->status = 1;
        $active->save();
        Toastr::success('Success','Data active successfully');
        return redirect()->back();
    }
    
    public function authorpaymentUpdate(Request $request){
        

        $validated = $request->validate([
        'author_id'     => 'required|exists:users,id', // make sure table name is correct
        'payment_method'  => 'required',
        'pay_amount'         => 'required',
        'paynote'         => 'nullable|string|max:1000',
    ]);
   
$pay_code = 'PAY-' . strtoupper(Str::random(8));
    // Step 2: Save to database
    $payment = new PaymentHistory();
    $payment->author_id    = $validated['author_id'];
    $payment->pay_code    = $pay_code;
    $payment->payment_method = $validated['payment_method'];
    $payment->pay_amount         = $validated['pay_amount']; // assuming the column is named 'amount'
    $payment->paynode           = $validated['paynote']; // assuming the column is named 'note'
   $payment->date             = $request->date;
    $payment->save();

    $wholesale_payment = User::find($request->author_id);
    $pay =  $wholesale_payment->advance + $request->pay_amount;
    $wholesale_payment->advance = $pay;
    $wholesale_payment->save();
    Toastr::success('Success','Payment recorded successfully');
    return redirect()->back();



    }
    
     public function teamplate(){
         $Smsteamplete = SmsTeamplate::get();
        return view('backEnd.marketing.index',compact('Smsteamplete'));
    }

    public function createteamplate(){

        return view('backEnd.marketing.smsContent');
    }

    public function storeTeamplate(Request $request){
        
        
      $smsteamplate = new SmsTeamplate;
      $smsteamplate->type = $request->type;
      $smsteamplate->smsteamplate = $request->templateContent;
      $smsteamplate->status = 1;
      $smsteamplate->dynamic = $request->dynamicsms;
       $smsteamplate->save();
        Toastr::success('Success','Data teamplate successfully');
        return redirect()->back();
    }

    public function smsedit($id)
    {
        $edit_data = SmsTeamplate::find($id);
        return view('backEnd.marketing.editTeamplate',compact('edit_data'));
    }

    public function smsUpdate(Request $request){
        
       $id = $request->id;
        $Smsteamplate = SmsTeamplate::findOrFail($id);
        $Smsteamplate->type = $request->type;
       $Smsteamplate->smsteamplate = $request->templateContent;
       $Smsteamplate->dynamic = $request->dynamicsms;
       $Smsteamplate->save();
       Toastr::success('Success','Data Update successfully');
        return redirect()->back();

    }

    public function smsdelete(Request $request)
    {

        $delete_data = SmsTeamplate::find($request->hidden_id);
        
        $delete_data->delete();
        Toastr::success('Success','Data delete successfully');
        return redirect()->back();
    }

    public function smsinactive(Request $request)
    {
        $inactive = SmsTeamplate::find($request->hidden_id);
        $inactive->status = 0;
        $inactive->save();
        Toastr::success('Success','Data inactive successfully');
        return redirect()->back();
    }
    public function smsactive(Request $request)
    {
        $active = SmsTeamplate::find($request->hidden_id);
        $active->status = 1;
        $active->save();
        Toastr::success('Success','Data active successfully');
        return redirect()->back();
    }
    
    
    public function paymentlist(Request $request){
        

    $query = PaymentHistory::with('author')->whereNotNull('author_id');

// Date filtering
if ($request->filled('date_filter')) {
    switch ($request->date_filter) {
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
if ($request->filled('author_id')) {
    $query->where('author_id', $request->author_id);
}

// Keyword search
if ($request->filled('keyword')) {
    $keyword = $request->keyword;
    $query->where(function($q) use ($keyword) {
        $q->where('pay_code', 'like', "%{$keyword}%")
          ->orWhere('payment_method', 'like', "%{$keyword}%")
          ->orWhere('pay_amount', 'like', "%{$keyword}%")
          ->orWhere('paynode', 'like', "%{$keyword}%")
          ->orWhereHas('author', function($subQuery) use ($keyword) {
              $subQuery->where('name', 'like', "%{$keyword}%");
          });
    });
}

$paymentlist = $query->paginate(15);
$author = User::where('user_type', 'author')->get();

if ($request->ajax()) {
    return response()->json([
        'html' => view('author.paytabel_content', compact('paymentlist'))->render(),
        'pagination' => $paymentlist->appends($request->all())->links('pagination::bootstrap-4')->render()
    ]);
}

return view('author.payment_history', compact('paymentlist', 'author'));

    }
    
    public function paymentauthor(Request $request){
        

        $validated = $request->validate([
        'author_id'     => 'required|exists:users,id', // make sure table name is correct
        'payment_method'  => 'required',
        'advance'         => 'required',
        'paynote'         => 'nullable|string|max:1000',
    ]);
   
$pay_code = 'PAY-' . strtoupper(Str::random(8));
    // Step 2: Save to database
    $payment = new PaymentHistory();
    $payment->author_id    = $validated['author_id'];
    $payment->pay_code    = $pay_code;
    $payment->payment_method = $validated['payment_method'];
    $payment->pay_amount         = $validated['advance']; // assuming the column is named 'amount'
    $payment->paynode           = $validated['paynote']; // assuming the column is named 'note'
   $payment->date             = $request->date;
    $payment->save();

    $author_payment = User::find($request->author_id);
    $pay =  $author_payment->advance + $request->advance;
    $author_payment->advance = $pay;
    $author_payment->save();

    return redirect()->route('author.payment')->with('success', 'Payment recorded successfully.');



    }
    
    public function authorpay()
    {
        // Get statistics
        $stats = [
            'pending_count' => PaymentHistory::where('status', 0)->count(),
            'pending_amount' => PaymentHistory::where('status', 0)->sum('pay_amount'),
            'approved_count' => PaymentHistory::where('status', 2)->count(),
            'approved_amount' => PaymentHistory::where('status', 2)->sum('pay_amount'),
            'processing_count' => PaymentHistory::where('status', 1)->count(),
            'processing_amount' => PaymentHistory::where('status', 1)->sum('pay_amount'),
            'completed_count' => PaymentHistory::where('status', 3)->count(),
            'completed_amount' => PaymentHistory::where('status', 3)->sum('pay_amount'),
        ];

        // Get withdrawal requests by status with author relationship
        $pendingRequests = PaymentHistory::with('author')
            ->where('status', 0)
            ->orderBy('created_at', 'desc')
            ->get();

        $approvedRequests = PaymentHistory::with('author')
            ->where('status', 2)
            ->orderBy('created_at', 'desc')
            ->get();

        $processingRequests = PaymentHistory::with('author')
            ->where('status', 1)
            ->orderBy('created_at', 'desc')
            ->get();

        $completedRequests = PaymentHistory::with('author')
            ->where('status', 3)
            ->orderBy('created_at', 'desc')
            ->get();

        $rejectedRequests = PaymentHistory::with('author')
            ->where('status', 4)
            ->orderBy('created_at', 'desc')
            ->get();

        $allRequests = PaymentHistory::with('author')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('backEnd.loylaty.approvePayment', compact(
            'stats',
            'pendingRequests',
            'approvedRequests',
            'processingRequests',
            'completedRequests',
            'rejectedRequests',
            'allRequests'
        ));
    }

    /**
     * Update withdrawal request status
     */
    public function payupdate(Request $request, $id)
    {
        
        
        
        // $validator = Validator::make($request->all(), [
        //     'status' => 'required|in:1,2,3,4',
        //     'admin_note' => 'required|string|min:10|max:1000',
        // ], [
        //     'status.required' => 'Status is required',
        //     'status.in' => 'Invalid status value',
        //     'admin_note.required' => 'Admin note is required',
        //     'admin_note.min' => 'Admin note must be at least 10 characters',
        //     'admin_note.max' => 'Admin note cannot exceed 1000 characters',
        // ]);

        // if ($validator->fails()) {
        //     return redirect()->back()
        //         ->withErrors($validator)
        //         ->withInput()
        //         ->with('error', 'Validation failed. Please check your input.');
        // }

        // try {
        //     DB::beginTransaction();

        //     $withdrawal = Withdrawal::with('author')->findOrFail($id);
        //     $oldStatus = $withdrawal->status;
        //     $newStatus = $request->status;

        //     // Status flow validation
        //     $validTransitions = [
        //         0 => [1, 2, 4], // Pending can go to Processing, Approved, or Rejected
        //         1 => [2, 4],    // Processing can go to Approved or Rejected
        //         2 => [3],       // Approved can go to Completed
        //     ];

        //     if (!isset($validTransitions[$oldStatus]) || !in_array($newStatus, $validTransitions[$oldStatus])) {
        //         return redirect()->back()->with('error', 'Invalid status transition');
        //     }

        //     // Update withdrawal
        //     $withdrawal->status = $newStatus;
        //     $withdrawal->admin_note = $request->admin_note;
        //     $withdrawal->processed_at = now();
        //     $withdrawal->processed_by = auth()->id();
        //     $withdrawal->save();

        //     // Handle balance updates based on status
        //     if ($newStatus == 2) { // Approved
        //         // Deduct from author's balance if not already done
        //         if ($oldStatus == 0 || $oldStatus == 1) {
        //             $author = $withdrawal->author;
        //             if ($author->balance >= $withdrawal->pay_amount) {
        //                 $author->balance -= $withdrawal->pay_amount;
        //                 $author->save();

        //                 // Log transaction
        //                 DB::table('balance_transactions')->insert([
        //                     'user_id' => $author->id,
        //                     'type' => 'withdrawal',
        //                     'amount' => -$withdrawal->pay_amount,
        //                     'balance_after' => $author->balance,
        //                     'description' => 'Withdrawal approved - Request #' . $withdrawal->id,
        //                     'reference_id' => $withdrawal->id,
        //                     'created_at' => now(),
        //                     'updated_at' => now(),
        //                 ]);
        //             } else {
        //                 DB::rollBack();
        //                 return redirect()->back()->with('error', 'Insufficient balance for withdrawal');
        //             }
        //         }
        //     } elseif ($newStatus == 4 && in_array($oldStatus, [0, 1])) { // Rejected from Pending or Processing
        //         // If balance was already deducted (shouldn't happen in normal flow), refund it
        //         // This is a safety check
        //     }

        //     // Send notification to author
        //     $this->sendWithdrawalNotification($withdrawal, $newStatus);

        //     DB::commit();

        //     $statusMessages = [
        //         1 => 'Withdrawal request marked as processing',
        //         2 => 'Withdrawal request approved successfully',
        //         3 => 'Withdrawal request marked as completed',
        //         4 => 'Withdrawal request rejected',
        //     ];

        //     return redirect()->back()->with('success', $statusMessages[$newStatus]);

        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     return redirect()->back()->with('error', 'Failed to update withdrawal: ' . $e->getMessage());
        // }
        // Validate request
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:1,2,3,4',
            'admin_note' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Find withdrawal request
            $withdrawal = PaymentHistory::findOrFail($id);

            // Check if already processed (not pending)
            if ($withdrawal->status != 0 && $withdrawal->status != 1 && $withdrawal->status != 2) {
                return redirect()->back()->with('error', 'This withdrawal request has already been processed.');
            }

            $oldStatus = $withdrawal->status;
            $newStatus = $request->status;

            // Update withdrawal request
            $withdrawal->status = $newStatus;
            $withdrawal->paynode = $request->admin_note;
            $withdrawal->date = now();
            $withdrawal->processed_by = auth()->id(); // Assuming you have admin user logged in
            $withdrawal->save();

            // Handle status changes
            $author = User::find($withdrawal->author_id);
            
            if ($author) {
                switch ($newStatus) {
                    case '2': // Approved
                        // Status 2 = Approved (waiting for processing)
                        $message = 'Withdrawal request approved successfully.';
                        break;

                    case '1': // Processing
                        // Status 1 = Processing (payment being sent)
                        $message = 'Withdrawal marked as processing.';
                        break;

                    case '3': // Completed
                        // Status 3 = Completed (payment sent)
                        // Deduct from author's loyalty points if not already deducted
                        if ($oldStatus != 3) {
                            $author->loyalty_point -= $withdrawal->pay_amount;
                            $author->save();
                        }
                        $message = 'Withdrawal completed successfully.';
                        break;

                    case '4': // Rejected
                        // Status 4 = Rejected
                        // If it was previously approved/processing, return the amount
                        if (in_array($oldStatus, [1, 2])) {
                            $author->loyalty_point += $withdrawal->pay_amount;
                            $author->save();
                        }
                        $message = 'Withdrawal request rejected.';
                        break;

                    default:
                        $message = 'Withdrawal status updated.';
                }

                // Send notification to author (you can implement this)
                // $this->sendWithdrawalNotification($author, $withdrawal);
            }

            DB::commit();

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Bulk approve withdrawals
     */
    public function bulkApprove(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'withdrawal_ids' => 'required|array',
            'withdrawal_ids.*' => 'exists:payment_histories,id',
            'admin_note' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $withdrawalIds = $request->withdrawal_ids;
            $adminNote = $request->admin_note;

            $updated = PaymentHistory::whereIn('id', $withdrawalIds)
                ->where('status', 0)
                ->update([
                    'status' => 2,
                    'admin_note' => $adminNote,
                    'processed_at' => now(),
                    'processed_by' => auth()->id(),
                ]);

            DB::commit();

            return redirect()->back()->with('success', "$updated withdrawal requests approved successfully.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Bulk reject withdrawals
     */
    public function bulkReject(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'withdrawal_ids' => 'required|array',
            'withdrawal_ids.*' => 'exists:payment_histories,id',
            'admin_note' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $withdrawalIds = $request->withdrawal_ids;
            $adminNote = $request->admin_note;

            $withdrawals = PaymentHistory::whereIn('id', $withdrawalIds)
                ->where('status', 0)
                ->get();

            foreach ($withdrawals as $withdrawal) {
                // Return amount to author
                $author = User::find($withdrawal->author_id);
                if ($author) {
                    $author->loyalty_point += $withdrawal->pay_amount;
                    $author->save();
                }

                $withdrawal->update([
                    'status' => 4,
                    'admin_note' => $adminNote,
                    'processed_at' => now(),
                    'processed_by' => auth()->id(),
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', count($withdrawals) . " withdrawal requests rejected successfully.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Get withdrawal statistics
     */
    public function statistics()
    {
        $stats = [
            'total_requests' => PaymentHistory::count(),
            'total_amount' => PaymentHistory::sum('pay_amount'),
            'pending_requests' => PaymentHistory::where('status', 0)->count(),
            'pending_amount' => PaymentHistory::where('status', 0)->sum('pay_amount'),
            'approved_requests' => PaymentHistory::where('status', 2)->count(),
            'approved_amount' => PaymentHistory::where('status', 2)->sum('pay_amount'),
            'completed_requests' => PaymentHistory::where('status', 3)->count(),
            'completed_amount' => PaymentHistory::where('status', 3)->sum('pay_amount'),
            'rejected_requests' => PaymentHistory::where('status', 4)->count(),
            'rejected_amount' => PaymentHistory::where('status', 4)->sum('pay_amount'),
        ];

        return response()->json($stats);
    }

    /**
     * Export withdrawal reports
     */
    public function export(Request $request)
    {
        $status = $request->get('status', 'all');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $query = PaymentHistory::with('author');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $withdrawals = $query->orderBy('created_at', 'desc')->get();

        // You can implement CSV/Excel export here
        // For now, returning JSON
        return response()->json($withdrawals);
    }

}