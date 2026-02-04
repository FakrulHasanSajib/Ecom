<?php


use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cart;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Str;

use App\Http\Controllers\Frontend\FrontendController;
// use App\Http\Controllers\Frontend\ShoppingController;
use App\Http\Controllers\Frontend\CustomerController;
use App\Http\Controllers\Frontend\BkashController;
use App\Http\Controllers\Frontend\ShurjopayController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SubcategoryController;
use App\Http\Controllers\Admin\ChildcategoryController;
use App\Http\Controllers\Admin\OrderStatusController;
use App\Http\Controllers\Admin\PixelsController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ApiIntegrationController;
use App\Http\Controllers\Admin\GeneralSettingController;
use App\Http\Controllers\Admin\SocialMediaController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\BannerCategoryController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CreatePageController;
use App\Http\Controllers\Admin\CampaignController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\CustomerManageController;
use App\Http\Controllers\Admin\ShippingChargeController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Admin\TagManagerController;
use App\Http\Controllers\Admin\Wholesellermanger;
use App\Http\Controllers\author\WithdrawalController;
use App\Http\Controllers\author\authorLoginController;
use App\Http\Controllers\Wholesales\WholesalersAuthController;
use App\Http\Controllers\Wholesales\WholesaleDashboardController;
use App\Http\Controllers\Wholesales\WholesalesorderController;
 use App\Http\Controllers\Wholesales\WholesalesOrderDetailsController;
 use App\Http\Controllers\Wholesales\WholesalesOrderStatusController;
 use App\Http\Controllers\Wholesales\WholesalesProductController;
 use App\Http\Controllers\Wholesales\WholesalesProductDetailsController;
use App\Http\Controllers\Admin\QuickTabController;
use App\Http\Controllers\Admin\TiktokPixelsController;
use App\Http\Controllers\Admin\MediaController;
use Illuminate\Support\Facades\Http;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Log;




Auth::routes();
Route::get('/login', function() {
    abort(404); // কেউ /login এ গেলে 404 এরর দেখাবে
});
//
Route::group(['prefix' => 'admin'], function() {
    Route::get('login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('admin.login.submit');
});

// ====================================================
// ✅ ADMIN PANEL ROUTES (অ্যাডমিন প্যানেল)
// ====================================================
Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => ['auth']], function () {

    // Reseller Management
    Route::group(['prefix' => 'reseller', 'as' => 'reseller.'], function () {
        Route::get('/list', [App\Http\Controllers\Admin\ResellerManageController::class, 'index'])->name('index');
        Route::get('/profile/{id}', [App\Http\Controllers\Admin\ResellerManageController::class, 'profile'])->name('profile');
        Route::post('/active', [App\Http\Controllers\Admin\ResellerManageController::class, 'active'])->name('active');
        Route::post('/inactive', [App\Http\Controllers\Admin\ResellerManageController::class, 'inactive'])->name('inactive');
        Route::post('/destroy', [App\Http\Controllers\Admin\ResellerManageController::class, 'destroy'])->name('destroy');
    });

    // Recruitment Campaign (Admin side)
    Route::group(['prefix' => 'recruitment', 'as' => 'recruitment.'], function() {
        Route::get('manage', [App\Http\Controllers\Admin\RecruitmentCampaignController::class, 'index'])->name('index');
        Route::get('create', [App\Http\Controllers\Admin\RecruitmentCampaignController::class, 'create'])->name('create');
        Route::post('save', [App\Http\Controllers\Admin\RecruitmentCampaignController::class, 'store'])->name('store');
        Route::get('edit/{id}', [App\Http\Controllers\Admin\RecruitmentCampaignController::class, 'edit'])->name('edit');
        Route::post('update', [App\Http\Controllers\Admin\RecruitmentCampaignController::class, 'update'])->name('update');
        Route::post('destroy', [App\Http\Controllers\Admin\RecruitmentCampaignController::class, 'destroy'])->name('destroy');
    });

    // Dealer Management (Admin controls Dealers)
    Route::group(['prefix' => 'dealer', 'as' => 'dealer.'], function () {
        Route::get('/list', [App\Http\Controllers\Admin\DealerController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\DealerController::class, 'create'])->name('create');
        Route::post('/store', [App\Http\Controllers\Admin\DealerController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [App\Http\Controllers\Admin\DealerController::class, 'edit'])->name('edit');
        Route::post('/update', [App\Http\Controllers\Admin\DealerController::class, 'update'])->name('update');
        Route::get('/assign-products/{id}', [App\Http\Controllers\Admin\DealerController::class, 'assignProducts'])->name('assign_products');
        Route::get('/orders', [App\Http\Controllers\Admin\DealerController::class, 'orderList'])->name('orders');
        Route::get('/payment-requests', [App\Http\Controllers\Admin\DealerController::class, 'paymentRequests'])->name('payment_requests');
        Route::post('/payment/status', [App\Http\Controllers\Admin\DealerController::class, 'paymentStatusUpdate'])->name('payment_status');
    });

}); // অ্যাডমিন গ্রুপের শেষ


// ====================================================
// ✅ DEALER PANEL ROUTES (ডিলার নিজস্ব প্যানেল)
// ====================================================
Route::group(['prefix' => 'dealer', 'as' => 'dealer.', 'middleware' => ['dealer']], function () {

    // Recruitment Campaign (Dealer side)
    Route::group(['prefix' => 'my-recruitment', 'as' => 'recruitment.'], function() {
        Route::get('manage', [App\Http\Controllers\Dealer\DealerRecruitmentController::class, 'index'])->name('index');
        Route::get('create', [App\Http\Controllers\Dealer\DealerRecruitmentController::class, 'create'])->name('create');
        Route::post('save', [App\Http\Controllers\Dealer\DealerRecruitmentController::class, 'store'])->name('store');
        Route::get('edit/{id}', [App\Http\Controllers\Dealer\DealerRecruitmentController::class, 'edit'])->name('edit');
        Route::post('update', [App\Http\Controllers\Dealer\DealerRecruitmentController::class, 'update'])->name('update');
        Route::post('destroy', [App\Http\Controllers\Dealer\DealerRecruitmentController::class, 'destroy'])->name('destroy');
    });
    
});

// Clear cache routes
Route::get('/cc', function () {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    return "Cleared!";
});

// Database Migration Route
Route::get('/migration', function () {
    Artisan::call('migrate');
    return "Migration Done!";
});

Route::get('/controller', function () {
    Artisan::call('make:controller author/authorLoginController');
    return "Controller Done!";
});

// Quick Actions Group
// Route::group(['prefix' => 'quick', 'namespace' => 'Frontend', 'middleware' => ['ipcheck', 'check_refer']], function () {
//     Route::post('/view', [FrontendController::class, 'quickview'])->name('quick.view');
//     Route::get('/cart-add/{id}/{qty?}', [App\Http\Controllers\Frontend\ShoppingController::class, 'quickAddToCart'])->name('quick.cart.add');
// });

// Frontend Routes
Route::group(['namespace' => 'Frontend', 'middleware' => ['ipcheck', 'check_refer']], function () {
    Route::get('/', [FrontendController::class, 'index'])->name('home');
    Route::get('/loadshowmore', [FrontendController::class, 'getMoreProducts'])->name('products.loadMore');
    Route::get('category/{category}', [FrontendController::class, 'category'])->name('category');
    Route::get('subcategory/{subcategory}', [FrontendController::class, 'subcategory'])->name('subcategory');
    Route::get('products/{slug}', [FrontendController::class, 'products'])->name('products');
    Route::get('hot-deals', [FrontendController::class, 'hotdeals'])->name('hotdeals');
    Route::get('flash-delas/{slug}', [FrontendController::class, 'flashdealsdetails'])->name('flashdeal');
    Route::get('author-details/{slug}', [FrontendController::class, 'authorprodetails'])->name('author.product');
    Route::get('free-shipping', [FrontendController::class, 'freeshippingdetails'])->name('freeshipping');
    Route::get('livesearch', [FrontendController::class, 'livesearch'])->name('livesearch');
    Route::get('search', [FrontendController::class, 'search'])->name('search');
    Route::get('product/{id}', [FrontendController::class, 'details'])->name('product');
    Route::post('quick-view', [FrontendController::class, 'quickview'])->name('quickview');
    Route::get('/shipping-charge', [FrontendController::class, 'shipping_charge'])->name('shipping.charge');
    // Route::get('/landshipping-charge', [FrontendController::class, 'landshipping_charge'])->name('landshipping.charge');
    Route::get('site/contact-us', [FrontendController::class, 'contact'])->name('contact');
    Route::get('/page/{slug}', [FrontendController::class, 'page'])->name('page');
    Route::get('districts', [FrontendController::class, 'districts'])->name('districts');
    Route::get('/campaign/{slug}', [FrontendController::class, 'campaign'])->name('campaign');
    Route::get('/offer', [FrontendController::class, 'offers'])->name('offers');
    Route::get('/payment-success', [FrontendController::class, 'payment_success'])->name('payment_success');
    Route::get('/payment-cancel', [FrontendController::class, 'payment_cancel'])->name('payment_cancel');
    Route::post('order/issue', [FrontendController::class, 'orderissue'])->name('report.issue');
    Route::post('/payment-invoice', [FrontendController::class, 'paymentinvoice'])->name('payment_invoice');
    Route::get('my/{id}/{day}', [FrontendController::class, 'invoice'])->name('front.invoice');
    

    // Cart routes group
    Route::group(['prefix' => 'cart'], function () {
        Route::post('/store', [App\Http\Controllers\Frontend\ShoppingController::class, 'cart_store'])->name('cart.store');
        Route::post('/single', [App\Http\Controllers\Frontend\ShoppingController::class, 'cart_single'])->name('cart.single');
        Route::get('/add-to-cart/{id}/{qty}', [App\Http\Controllers\Frontend\ShoppingController::class, 'addTocartGet']);
        Route::get('/show', [App\Http\Controllers\Frontend\ShoppingController::class, 'cart_show'])->name('cart.show');
        Route::get('/remove', [App\Http\Controllers\Frontend\ShoppingController::class, 'cart_remove'])->name('cart.remove');
        Route::get('/count', [App\Http\Controllers\Frontend\ShoppingController::class, 'cart_count'])->name('cart.count');
        Route::get('/mobile-count', [App\Http\Controllers\Frontend\ShoppingController::class, 'mobilecart_qty'])->name('mobile.cart.count');
        Route::get('/decrement', [App\Http\Controllers\Frontend\ShoppingController::class, 'cart_decrement'])->name('cart.decrement');
        Route::get('/increment', [App\Http\Controllers\Frontend\ShoppingController::class, 'cart_increment'])->name('cart.increment');
        Route::post('/remove-item', [App\Http\Controllers\Frontend\ShoppingController::class, 'lancart_remove'])->name('lancart.remove');
        Route::post('/new-product', [App\Http\Controllers\Frontend\ShoppingController::class, 'cart_newpro'])->name('cart.newpro');
        Route::get('/my-increment', [App\Http\Controllers\Frontend\ShoppingController::class, 'cart_myincrement'])->name('cart.myincrement');
        Route::get('/my-decrement', [App\Http\Controllers\Frontend\ShoppingController::class, 'cart_mydecrement'])->name('cart.mydecrement');
    });
});

// Dealer Panel Routes
Route::group(['prefix' => 'dealer'], function () {
    Route::get('/login', [App\Http\Controllers\Dealer\DealerAuthController::class, 'login'])->name('dealer.login');
    Route::post('/login/submit', [App\Http\Controllers\Dealer\DealerAuthController::class, 'loginCheck'])->name('dealer.login.submit');
    Route::post('/logout', [App\Http\Controllers\Dealer\DealerAuthController::class, 'logout'])->name('dealer.logout');

    Route::group(['middleware' => ['dealer']], function () {
        Route::get('/dashboard', [App\Http\Controllers\Dealer\DealerDashboardController::class, 'dashboard'])->name('dealer.dashboard');
        Route::get('/products', [App\Http\Controllers\Dealer\DealerDashboardController::class, 'productList'])->name('dealer.products');
        Route::post('/commission/update', [App\Http\Controllers\Dealer\DealerDashboardController::class, 'updateCommission'])->name('dealer.commission.update');

        Route::get('/orders', [App\Http\Controllers\Dealer\DealerDashboardController::class, 'orderHistory'])->name('dealer.order_history');
        Route::get('/withdrawals', [App\Http\Controllers\Dealer\DealerDashboardController::class, 'withdrawals'])->name('dealer.withdrawals');
        Route::post('/withdrawals/store', [App\Http\Controllers\Dealer\DealerDashboardController::class, 'storeWithdrawal'])->name('dealer.withdrawals.store');

        Route::get('/referrals', [App\Http\Controllers\Dealer\DealerDashboardController::class, 'referralList'])->name('dealer.referrals');
        Route::get('/reseller/profile/{id}', [App\Http\Controllers\Dealer\DealerDashboardController::class, 'resellerProfile'])->name('dealer.reseller.profile');

        Route::get('/profile', [App\Http\Controllers\Dealer\DealerDashboardController::class, 'profile'])->name('dealer.profile');
        Route::post('/profile/update', [App\Http\Controllers\Dealer\DealerDashboardController::class, 'updateProfile'])->name('dealer.profile.update');
    });
});

// Reseller Auth Routes
Route::group(['prefix' => 'reseller'], function () {
    Route::get('/login', [App\Http\Controllers\Reseller\ResellerAuthController::class, 'login'])->name('reseller.login');
    Route::post('/login', [App\Http\Controllers\Reseller\ResellerAuthController::class, 'loginCheck'])->name('reseller.login.submit');
    Route::get('/register', [App\Http\Controllers\Reseller\ResellerAuthController::class, 'register'])->name('reseller.register');
    Route::post('/register', [App\Http\Controllers\Reseller\ResellerAuthController::class, 'registerStore'])->name('reseller.register.submit');
    Route::post('/logout', [App\Http\Controllers\Reseller\ResellerAuthController::class, 'logout'])->name('reseller.logout');
});

// Reseller Panel Routes (Protected)
Route::group(['prefix' => 'reseller', 'middleware' => ['auth:reseller']], function () {
    Route::get('/dashboard', [App\Http\Controllers\Reseller\ResellerController::class, 'dashboard'])->name('reseller.dashboard');
    Route::get('/products', [App\Http\Controllers\Reseller\ResellerController::class, 'productList'])->name('reseller.products');
    Route::get('/order/create/{id}', [App\Http\Controllers\Reseller\ResellerController::class, 'orderCreate'])->name('reseller.order.create');
    Route::post('/order/store', [App\Http\Controllers\Reseller\ResellerController::class, 'orderStore'])->name('reseller.order.store');

    Route::get('/orders', [App\Http\Controllers\Reseller\ResellerController::class, 'orderList'])->name('reseller.orders');
    Route::get('/wallet', [App\Http\Controllers\Reseller\ResellerController::class, 'wallet'])->name('reseller.wallet');
    Route::post('/withdrawal/request', [App\Http\Controllers\Reseller\ResellerController::class, 'withdrawalRequest'])->name('reseller.withdrawal.request');
    Route::get('/my-referrals', [App\Http\Controllers\Reseller\ResellerController::class, 'referralList'])->name('reseller.referrals');
    Route::get('/referral/profile/{id}', [App\Http\Controllers\Reseller\ResellerController::class, 'referralProfile'])->name('reseller.referral.profile');

    Route::get('/profile', [App\Http\Controllers\Reseller\ResellerController::class, 'profile'])->name('reseller.profile');
    Route::post('/profile/update', [App\Http\Controllers\Reseller\ResellerController::class, 'updateProfile'])->name('reseller.profile.update');
});

// Customer Authentication Routes
Route::group(['prefix' => 'customer', 'namespace' => 'Frontend', 'middleware' => ['ipcheck', 'check_refer']], function () {
    Route::get('/login', [CustomerController::class, 'login'])->name('customer.login');
    Route::post('/signin', [CustomerController::class, 'signin'])->name('customer.signin');
    Route::get('/register', [CustomerController::class, 'register'])->name('customer.register');
    Route::post('/store', [CustomerController::class, 'store'])->name('customer.store');
    Route::get('/verify', [CustomerController::class, 'verify'])->name('customer.verify');
    Route::post('/verify-account', [CustomerController::class, 'account_verify'])->name('customer.account.verify');
    Route::post('/resend-otp', [CustomerController::class, 'resendotp'])->name('customer.resendotp');
    Route::post('/logout', [CustomerController::class, 'logout'])->name('customer.logout');
    Route::post('/review', [CustomerController::class, 'review'])->name('customer.review');
    Route::get('/forgot-password', [CustomerController::class, 'forgot_password'])->name('customer.forgot.password');
    Route::post('/forgot-verify', [CustomerController::class, 'forgot_verify'])->name('customer.forgot.verify');
    Route::get('/forgot-password/reset', [CustomerController::class, 'forgot_reset'])->name('customer.forgot.reset');
    Route::post('/forgot-password/store', [CustomerController::class, 'forgot_store'])->name('customer.forgot.store');
    Route::post('/forgot-password/resendotp', [CustomerController::class, 'forgot_resend'])->name('customer.forgot.resendotp');
    Route::get('/checkout', [CustomerController::class, 'checkout'])->name('customer.checkout');
    Route::post('/order-save', [CustomerController::class, 'order_save'])->name('customer.ordersave');
    Route::post('/order-newsave', [CustomerController::class, 'order_newsave'])->name('order_newsave');
    Route::get('/order-success/{id}', [CustomerController::class, 'order_success'])->name('customer.order_success');
    Route::get('/order-track', [CustomerController::class, 'order_track'])->name('customer.order_track');
    Route::get('/order-track/result', [CustomerController::class, 'order_track_result'])->name('customer.order_track_result');
});

Route::get('/ref/{id}', [CustomerController::class, 'setReferral'])->name('customer.referral');

// Authenticated Customer Routes
Route::group(['prefix' => 'customer', 'namespace' => 'Frontend', 'middleware' => ['customer', 'ipcheck', 'check_refer']], function () {
    Route::get('/account', [CustomerController::class, 'account'])->name('customer.account');
    Route::get('/orders', [CustomerController::class, 'orders'])->name('customer.orders');
    Route::get('/invoice', [CustomerController::class, 'invoice'])->name('customer.invoice');
    Route::get('/invoice/order-note', [CustomerController::class, 'order_note'])->name('customer.order_note');
    Route::get('/profile-edit', [CustomerController::class, 'profile_edit'])->name('customer.profile_edit');
    Route::post('/profile-update', [CustomerController::class, 'profile_update'])->name('customer.profile_update');
    Route::get('/change-password', [CustomerController::class, 'change_pass'])->name('customer.change_pass');
    Route::post('/password-update', [CustomerController::class, 'password_update'])->name('customer.password_update');
});

// Cart Management Routes
Route::get('/cart/clear', function () {
    Cart::instance('shopping')->destroy();
    return response()->json(['success' => true]);
})->name('cart.clear');

Route::get('/cart/add', function (Request $request) {
    $product = Product::find($request->id);
    if ($product) {
        Cart::instance('shopping')->add([
            'id' => $product->id,
            'name' => $product->name,
            'qty' => $request->qty ?? 1,
            'price' => $product->new_price,
            'options' => [
                'slug' => $product->slug,
                'image' => $product->image->image,
                'old_price' => $product->old_price,
                'purchase_price' => $product->purchase_price,
            ],
        ]);
    }
    return response()->json(['success' => true]);
})->name('cart.add');

Route::get('/cart/content', function () {
    $subtotal = Cart::instance('shopping')->subtotal();
    $subtotal = str_replace(',', '', $subtotal);
    $subtotal = str_replace('.00', '', $subtotal);
    $shipping = Session::get('shipping') ? Session::get('shipping') : 0;

    $html = '<table class="table">
                <thead class="table-light">
                    <tr>
                        <th>প্রোডাক্ট</th>
                        <th>পরিমাণ</th>
                        <th>মূল্য</th>
                    </tr>
                </thead>
                <tbody>';

    foreach (Cart::instance('shopping')->content() as $value) {
        $html .= '<tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="' . asset($value->options->image) . '" height="40" width="40" class="me-2 rounded">
                            ' . Str::limit($value->name, 30) . '
                        </div>
                    </td>
                    <td>
                        <div class="qty-cart">
                            <div class="quantity-controls">
                                <button type="button" class="qty-btn cart_decrement" data-id="' . $value->rowId . '">-</button>
                                <input type="text" class="qty-input" value="' . $value->qty . '" readonly />
                                <button type="button" class="qty-btn cart_increment" data-id="' . $value->rowId . '">+</button>
                            </div>
                        </div>
                    </td>
                    <td>৳' . number_format($value->price * $value->qty) . '</td>
                </tr>';
    }

    $html .= '<tr>
                <td colspan="2"><strong>সাবটোটাল:</strong></td>
                <td><strong id="net_total">৳' . number_format($subtotal) . '</strong></td>
              </tr>
              <tr>
                <td colspan="2"><strong>ডেলিভারি চার্জ:</strong></td>
                <td><strong id="cart_shipping_cost">৳' . number_format($shipping) . '</strong></td>
              </tr>
              <tr class="total-row">
                <td colspan="2"><strong>মোট:</strong></td>
                <td><strong id="grand_total">৳' . number_format($subtotal + $shipping) . '</strong></td>
              </tr>
            </tbody>
        </table>';

    return response($html);
})->name('cart.content');

// Payment Gateway Routes
Route::group(['namespace' => 'Frontend', 'middleware' => ['ipcheck', 'check_refer']], function () {
    Route::get('bkash/checkout-url/pay', [BkashController::class, 'pay'])->name('url-pay');
    Route::any('bkash/checkout-url/create', [BkashController::class, 'create'])->name('url-create');
    Route::get('bkash/checkout-url/callback', [BkashController::class, 'callback'])->name('url-callback');

    // Shurjopay Routes
    // Route::get('/shurjopay/payment-success', [ShurjopayController::class, 'payment_success'])->name('shurjopay.payment_success');
    // Route::get('/shurjopay/payment-cancel', [ShurjopayController::class, 'payment_cancel'])->name('shurjopay.payment_cancel');
});

// Admin Lock Screen
Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => ['customer', 'ipcheck', 'check_refer']], function () {
    Route::get('locked', [DashboardController::class, 'locked'])->name('locked');
    Route::post('unlocked', [DashboardController::class, 'unlocked'])->name('unlocked');
});

// Author Login
Route::get('/author/login', [authorLoginController::class, 'authorlogin']);

// AJAX Routes
Route::get('/ajax-product-subcategory', [ProductController::class, 'getSubcategory']);
Route::get('/ajax-product-childcategory', [ProductController::class, 'getChildcategory']);

// Wholesaler Routes
Route::prefix('wholesaler')->group(function () {

    // Guest routes (not authenticated)
    Route::middleware('guest:wholesaler')->group(function () {
        Route::get('/login', [WholesalersAuthController::class, 'showLoginForm'])->name('wholesaler.login');
        Route::post('/login', [WholesalersAuthController::class, 'login'])->name('wholesale.login');
        Route::get('/register', [WholesalersAuthController::class, 'showRegistrationForm'])->name('wholesaler.register');
        Route::post('/register', [WholesalersAuthController::class, 'register']);
    });

    // Authenticated routes
    Route::middleware('wholesaler.auth')->group(function () {
        Route::get('/dashboard', [WholesaleDashboardController::class, 'index'])->name('wholesaler.dashboard');
        Route::get('/profile', [WholesaleDashboardController::class, 'profile'])->name('wholesaler.profile');
        Route::post('/profile/update', [WholesaleDashboardController::class, 'updateProfile'])->name('wholesaler.profile.update');
        Route::post('/logout', [WholesaleDashboardController::class, 'logout'])->name('wholesaler.logout');
        Route::post('/password/update', [WholesaleDashboardController::class, 'updatePassword'])->name('wholesaler.password.update');
        Route::post('/contact/update', [WholesaleDashboardController::class, 'updateContact'])->name('wholesaler.contact.update');

        Route::get('/product/show', [WholesaleDashboardController::class, 'productshow'])->name('wholesaler.product');
        Route::get('order_list', [WholesalesorderController::class, 'index'])->name('wholesaler.orderlist');
        Route::get('return_list', [WholesalesorderController::class, 'returnlist'])->name('wholesaler.returnlist');
        Route::get('order/invoice/{invoice_id}', [WholesalesorderController::class, 'invoice'])->name('wholesaler.order.invoice');
        Route::get('pay_list', [WholesalesorderController::class, 'historypay'])->name('wholesaler.paylist');
    });
});

// Admin Routes (routes/web.php)

Route::group(['namespace' => 'Admin', 'middleware' => ['auth', 'lock', 'check_refer'], 'prefix' => 'admin'], function () {

    // Dashboard: 'admin.dashboard' পরিবর্তন করে শুধু 'dashboard' করা হলো।
    Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    // Reports: নাম ঠিক আছে, তাই পরিবর্তন করা হলো না।
    Route::get('topSellReport', [DashboardController::class, 'topSellReport'])->name('topSellReport');
    Route::get('sellReport', [DashboardController::class, 'sellReport'])->name('sellReport');

    // Password Management: নাম ঠিক আছে, তাই পরিবর্তন করা হলো না।
    Route::get('change-password', [DashboardController::class, 'changepassword'])->name('change_password');
    Route::post('new-password', [DashboardController::class, 'newpassword'])->name('new_password');
    Route::get('admin/my-report', [App\Http\Controllers\Admin\DashboardController::class, 'moderator_report'])->name('admin.moderator.report');


    // --- Photo Gallery Routes ---

    Route::group(['middleware' => ['can:gallery-manage']], function () {
        Route::get('gallery', [MediaController::class, 'index'])->name('media.index');
        Route::post('gallery/store', [MediaController::class, 'store'])->name('media.store');
        Route::delete('gallery/{id}', [MediaController::class, 'destroy'])->name('media.delete');
        Route::get('media/get-list', [MediaController::class, 'getMediaList'])->name('media.get_list');
        Route::get('gallery/sync', [MediaController::class, 'syncFiles'])->name('media.sync');

        Route::get('media/get-list', [MediaController::class, 'getMediaList'])->name('media.get_list');
    });


    // Quick Tabs Routes

    // index (তালিকা)
    Route::get('quick-tabs/manage', [QuickTabController::class, 'index'])->name('admin.quick_tabs.index');

    // Create (ফর্ম)
    Route::get('quick-tabs/create', [QuickTabController::class, 'create'])->name('admin.quick_tabs.create');

    // Store (সেভ)
    Route::post('quick-tabs/save', [QuickTabController::class, 'store'])->name('admin.quick_tabs.store');

    // Edit (ফর্ম)
    Route::get('quick-tabs/{id}/edit', [QuickTabController::class, 'edit'])->name('admin.quick_tabs.edit');

    // Update (আপডেট) 
    Route::post('quick-tabs/update', [QuickTabController::class, 'update'])->name('admin.quick_tabs.update');

    // Destroy (ডিলিট)
    Route::post('quick-tabs/destroy', [QuickTabController::class, 'destroy'])->name('admin.quick_tabs.destroy');

    // Inactive 
    Route::post('quick-tabs/inactive', [QuickTabController::class, 'inactive'])->name('admin.quick_tabs.inactive');

    // Active
    Route::post('quick-tabs/active', [QuickTabController::class, 'active'])->name('admin.quick_tabs.active');



    // ড্যাশবোর্ড এবং অন্যান্য প্রাথমিক রুট

    Route::get('topSellReport', [DashboardController::class, 'topSellReport'])->name('topSellReport');

    // ⭐ এই লাইনটি নিশ্চিত করুন যে আপনার web.php এ আছে ⭐
    Route::get('sellReport', [DashboardController::class, 'sellReport'])->name('sellReport');

    // ⭐ এবং এই রুটটিও নিশ্চিত করুন। এই রুটটির নামই আপনার কোড খুঁজছে। ⭐
    Route::get('/sell-report-ajax', [DashboardController::class, 'sellReport'])->name('sell-report-ajax');



    // রুট গ্রুপ শেষ

    // Users routes
    Route::get('users/manage', [UserController::class, 'index'])->name('users.index');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('users/save', [UserController::class, 'store'])->name('users.store');
    Route::get('users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::post('users/update', [UserController::class, 'update'])->name('users.update');
    Route::post('users/inactive', [UserController::class, 'inactive'])->name('users.inactive');
    Route::post('users/active', [UserController::class, 'active'])->name('users.active');
    Route::post('users/destroy', [UserController::class, 'destroy'])->name('users.destroy');

    // Author
    Route::get('author/manage', [UserController::class, 'authorindex'])->name('author.index');
    Route::get('author/create', [UserController::class, 'authorcreate'])->name('author.create');
    Route::get('author/{id}/payment', [UserController::class, 'authorpayment'])->name('authorinfo.payment');




    // Roles
    Route::get('roles/manage', [RoleController::class, 'index'])->name('roles.index');
    Route::get('roles/{id}/show', [RoleController::class, 'show'])->name('roles.show');
    Route::get('roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('roles/save', [RoleController::class, 'store'])->name('roles.store');
    Route::get('roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::post('roles/update', [RoleController::class, 'update'])->name('roles.update');
    Route::post('roles/destroy', [RoleController::class, 'destroy'])->name('roles.destroy');

    // Permissions
    Route::get('permissions/manage', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('permissions/{id}/show', [PermissionController::class, 'show'])->name('permissions.show');
    Route::get('permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
    Route::post('permissions/save', [PermissionController::class, 'store'])->name('permissions.store');
    Route::get('permissions/{id}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
    Route::post('permissions/update', [PermissionController::class, 'update'])->name('permissions.update');
    Route::post('permissions/destroy', [PermissionController::class, 'destroy'])->name('permissions.destroy');

    // Categories
    Route::get('categories/manage', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('categories/{id}/show', [CategoryController::class, 'show'])->name('categories.show');
    Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('categories/save', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::post('categories/update', [CategoryController::class, 'update'])->name('categories.update');
    Route::post('categories/inactive', [CategoryController::class, 'inactive'])->name('categories.inactive');
    Route::post('categories/active', [CategoryController::class, 'active'])->name('categories.active');
    Route::post('categories/destroy', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Subcategories
    Route::get('subcategories/manage', [SubcategoryController::class, 'index'])->name('subcategories.index');
    Route::get('subcategories/{id}/show', [SubcategoryController::class, 'show'])->name('subcategories.show');
    Route::get('subcategories/create', [SubcategoryController::class, 'create'])->name('subcategories.create');
    Route::post('subcategories/save', [SubcategoryController::class, 'store'])->name('subcategories.store');
    Route::get('subcategories/{id}/edit', [SubcategoryController::class, 'edit'])->name('subcategories.edit');
    Route::post('subcategories/update', [SubcategoryController::class, 'update'])->name('subcategories.update');
    Route::post('subcategories/inactive', [SubcategoryController::class, 'inactive'])->name('subcategories.inactive');
    Route::post('subcategories/active', [SubcategoryController::class, 'active'])->name('subcategories.active');
    Route::post('subcategories/destroy', [SubcategoryController::class, 'destroy'])->name('subcategories.destroy');

    // Childcategories
    Route::get('childcategories/manage', [ChildcategoryController::class, 'index'])->name('childcategories.index');
    Route::get('childcategories/{id}/show', [ChildcategoryController::class, 'show'])->name('childcategories.show');
    Route::get('childcategories/create', [ChildcategoryController::class, 'create'])->name('childcategories.create');
    Route::post('childcategories/save', [ChildcategoryController::class, 'store'])->name('childcategories.store');
    Route::get('childcategories/{id}/edit', [ChildcategoryController::class, 'edit'])->name('childcategories.edit');
    Route::post('childcategories/update', [ChildcategoryController::class, 'update'])->name('childcategories.update');
    Route::post('childcategories/inactive', [ChildcategoryController::class, 'inactive'])->name('childcategories.inactive');
    Route::post('childcategories/active', [ChildcategoryController::class, 'active'])->name('childcategories.active');
    Route::post('childcategories/destroy', [ChildcategoryController::class, 'destroy'])->name('childcategories.destroy');

    // Payment Gateway
    Route::get('paymentgeteway/manage', [ApiIntegrationController::class, 'pay_manage'])->name('paymentgeteway.manage');
    Route::post('paymentgeteway/save', [ApiIntegrationController::class, 'pay_update'])->name('paymentgeteway.update');

    // SMS Gateway
    Route::get('smsgeteway/manage', [ApiIntegrationController::class, 'sms_manage'])->name('smsgeteway.manage');
    Route::post('smsgeteway/save', [ApiIntegrationController::class, 'sms_update'])->name('smsgeteway.update');

    // Courier API
    Route::get('courierapi/manage', [ApiIntegrationController::class, 'courier_manage'])->name('courierapi.manage');
    Route::post('courierapi/save', [ApiIntegrationController::class, 'courier_update'])->name('courierapi.update');
    Route::get('pathao/token', [ApiIntegrationController::class, 'pathao_token'])->name('pathao.token');

    // Order Status
    Route::get('orderstatus/manage', [OrderStatusController::class, 'index'])->name('orderstatus.index');
    Route::get('orderstatus/{id}/show', [OrderStatusController::class, 'show'])->name('orderstatus.show');
    Route::get('orderstatus/create', [OrderStatusController::class, 'create'])->name('orderstatus.create');
    Route::post('orderstatus/save', [OrderStatusController::class, 'store'])->name('orderstatus.store');
    Route::get('orderstatus/{id}/edit', [OrderStatusController::class, 'edit'])->name('orderstatus.edit');
    Route::post('orderstatus/update', [OrderStatusController::class, 'update'])->name('orderstatus.update');
    Route::post('orderstatus/inactive', [OrderStatusController::class, 'inactive'])->name('orderstatus.inactive');
    Route::post('orderstatus/active', [OrderStatusController::class, 'active'])->name('orderstatus.active');
    Route::post('orderstatus/destroy', [OrderStatusController::class, 'destroy'])->name('orderstatus.destroy');
    Route::get('order/fraud', [OrderStatusController::class, 'fraudorder'])->name('order.fraud');

    // Fraud Check Routes
    Route::post('/fraud-check/combined', [OrderStatusController::class, 'combinedFraudCheck'])->name('fraud.check');
    Route::post('/fraud-check/single', [OrderStatusController::class, 'checkSingleProvider']);
    Route::get('/fraud-check/combined/{number}', function ($number) {
        $request = request();
        $request->merge(['number' => $number]);
        return app(OrderStatusController::class)->combinedFraudCheck($request);
    });
    Route::get('/fraud-check/{provider}/{number}', function ($provider, $number) {
        $request = request();
        $request->merge(['number' => $number, 'provider' => $provider]);
        return app(OrderStatusController::class)->checkSingleProvider($request);
    });
    Route::post('order/checkdelivery', [OrderStatusController::class, 'checkDeliveryStatus'])->name('order.check');

    // Pixels
    Route::get('pixels/manage', [PixelsController::class, 'index'])->name('pixels.index');
    Route::get('pixels/{id}/show', [PixelsController::class, 'show'])->name('pixels.show');
    Route::get('pixels/create', [PixelsController::class, 'create'])->name('pixels.create');
    Route::post('pixels/save', [PixelsController::class, 'store'])->name('pixels.store');
    Route::get('pixels/{id}/edit', [PixelsController::class, 'edit'])->name('pixels.edit');
    Route::post('pixels/update', [PixelsController::class, 'update'])->name('pixels.update');
    Route::post('pixels/inactive', [PixelsController::class, 'inactive'])->name('pixels.inactive');
    Route::post('pixels/active', [PixelsController::class, 'active'])->name('pixels.active');
    Route::post('pixels/destroy', [PixelsController::class, 'destroy'])->name('pixels.destroy');

    // এখন পরিবর্তন করে এই লাইনগুলো ব্যবহার করুন (আলাদাভাবে সংজ্ঞায়িত):
// --- TikTok Pixels Custom Routes ---

    // index (manage)
    Route::get('tiktok_pixels', [TiktokPixelsController::class, 'index'])->name('tiktok_pixels.index');

    // create
    Route::get('tiktok_pixels/create', [TiktokPixelsController::class, 'create'])->name('tiktok_pixels.create');

    // store
    Route::post('tiktok_pixels/save', [TiktokPixelsController::class, 'store'])->name('tiktok_pixels.store');

    // edit
    Route::get('tiktok_pixels/{tiktok_pixel}/edit', [TiktokPixelsController::class, 'edit'])->name('tiktok_pixels.edit');
    // রিসোর্স রুট থেকে আলাদা করতে প্যারামিটার নাম পরিবর্তন করা হলো

    // update
     Route::post('tiktok_pixels/update', [TiktokPixelsController::class, 'update'])->name('tiktok_pixels.update');

    // destroy (আপনার কাস্টম destroy রুট)
     Route::post('tiktok_pixels/destroy', [TiktokPixelsController::class, 'destroy'])->name('tiktok_pixels.destroy');

    // inactive (আপনার কাস্টম রুট)
     Route::post('tiktok_pixels/inactive', [TiktokPixelsController::class, 'inactive'])->name('tiktok_pixels.inactive');

    // active (আপনার কাস্টম রুট)
     Route::post('tiktok_pixels/active', [TiktokPixelsController::class, 'active'])->name('tiktok_pixels.active');

    // Tag Manager
    Route::get('tag-manager/manage', [TagManagerController::class, 'index'])->name('tagmanagers.index');
    Route::get('tag-manager/{id}/show', [TagManagerController::class, 'show'])->name('tagmanagers.show');
    Route::get('tag-manager/create', [TagManagerController::class, 'create'])->name('tagmanagers.create');
    Route::post('tag-manager/save', [TagManagerController::class, 'store'])->name('tagmanagers.store');
    Route::get('tag-manager/{id}/edit', [TagManagerController::class, 'edit'])->name('tagmanagers.edit');
    Route::post('tag-manager/update', [TagManagerController::class, 'update'])->name('tagmanagers.update');
    Route::post('tag-manager/inactive', [TagManagerController::class, 'inactive'])->name('tagmanagers.inactive');
    Route::post('tag-manager/active', [TagManagerController::class, 'active'])->name('tagmanagers.active');
    Route::post('tag-manager/destroy', [TagManagerController::class, 'destroy'])->name('tagmanagers.destroy');

    // Brands
    Route::get('brands/manage', [BrandController::class, 'index'])->name('brands.index');
    Route::get('brands/{id}/show', [BrandController::class, 'show'])->name('brands.show');
    Route::get('brands/create', [BrandController::class, 'create'])->name('brands.create');
    Route::post('brands/save', [BrandController::class, 'store'])->name('brands.store');
    Route::get('brands/{id}/edit', [BrandController::class, 'edit'])->name('brands.edit');
    Route::post('brands/update', [BrandController::class, 'update'])->name('brands.update');
    Route::post('brands/inactive', [BrandController::class, 'inactive'])->name('brands.inactive');
    Route::post('brands/active', [BrandController::class, 'active'])->name('brands.active');
    Route::post('brands/destroy', [BrandController::class, 'destroy'])->name('brands.destroy');

    // Colors
    Route::get('color/manage', [ColorController::class, 'index'])->name('colors.index');
    Route::get('color/{id}/show', [ColorController::class, 'show'])->name('colors.show');
    Route::get('color/create', [ColorController::class, 'create'])->name('colors.create');
    Route::post('color/save', [ColorController::class, 'store'])->name('colors.store');
    Route::get('color/{id}/edit', [ColorController::class, 'edit'])->name('colors.edit');
    Route::post('color/update', [ColorController::class, 'update'])->name('colors.update');
    Route::post('color/inactive', [ColorController::class, 'inactive'])->name('colors.inactive');
    Route::post('color/active', [ColorController::class, 'active'])->name('colors.active');
    Route::post('color/destroy', [ColorController::class, 'destroy'])->name('colors.destroy');

    // Sizes
    Route::get('size/manage', [SizeController::class, 'index'])->name('sizes.index');
    Route::get('size/{id}/show', [SizeController::class, 'show'])->name('sizes.show');
    Route::get('size/create', [SizeController::class, 'create'])->name('sizes.create');
    Route::post('size/save', [SizeController::class, 'store'])->name('sizes.store');
    Route::get('size/{id}/edit', [SizeController::class, 'edit'])->name('sizes.edit');
    Route::post('size/update', [SizeController::class, 'update'])->name('sizes.update');
    Route::post('size/inactive', [SizeController::class, 'inactive'])->name('sizes.inactive');
    Route::post('size/active', [SizeController::class, 'active'])->name('sizes.active');
    Route::post('size/destroy', [SizeController::class, 'destroy'])->name('sizes.destroy');

    // Products
    Route::get('products/manage', [ProductController::class, 'index'])->name('products.index');
    Route::get('products/author', [ProductController::class, 'authorpro'])->name('author.product');
    Route::get('products/{id}/show', [ProductController::class, 'show'])->name('products.show');
    Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('products/save', [ProductController::class, 'store'])->name('products.store');
    Route::get('products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::post('products/update', [ProductController::class, 'update'])->name('products.update');
    Route::post('products/inactive', [ProductController::class, 'inactive'])->name('products.inactive');
    Route::post('products/active', [ProductController::class, 'active'])->name('products.active');
    Route::post('products/destroy', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::post('products/duplicate', [ProductController::class, 'duplicate'])->name('products.duplicate');
    Route::get('products/image/destroy', [ProductController::class, 'imgdestroy'])->name('products.image.destroy');
    Route::get('products/price/destroy', [ProductController::class, 'pricedestroy'])->name('products.price.destroy');
    Route::get('products/update-deals', [ProductController::class, 'update_deals'])->name('products.update_deals');
    Route::get('products/update-feature', [ProductController::class, 'update_feature'])->name('products.update_feature');
    Route::get('products/update-status', [ProductController::class, 'update_status'])->name('products.update_status');
    Route::get('products/price-edit', [ProductController::class, 'price_edit'])->name('products.price_edit');
    Route::post('products/price-update', [ProductController::class, 'price_update'])->name('products.price_update');
    // AI SEO Generate Route
    Route::post('products/ai-seo', [ProductController::class, 'generateSeo'])->name('products.ai_seo');

    // Product Report
    Route::get('/product-report', [OrderController::class, 'productReport'])->name('reports.product_report');

    // Campaign
    Route::get('landing-page/manage', [CampaignController::class, 'landingPageManage'])->name('landing_page.manage');
    Route::get('campaign/manage', [CampaignController::class, 'index'])->name('campaign.index');
    Route::get('campaign/{id}/show', [CampaignController::class, 'show'])->name('campaign.show');
    Route::get('campaign/create', [CampaignController::class, 'create'])->name('campaign.create');
    Route::get('campaign/create/7', [CampaignController::class, 'createSeven'])->name('campaign.create.seven');
    Route::post('campaign/save', [CampaignController::class, 'store'])->name('campaign.store');
    Route::get('campaign/{id}/edit', [CampaignController::class, 'edit'])->name('campaign.edit');
    Route::post('campaign/update', [CampaignController::class, 'update'])->name('campaign.update');
    Route::post('campaign/inactive', [CampaignController::class, 'inactive'])->name('campaign.inactive');
    Route::post('campaign/active', [CampaignController::class, 'active'])->name('campaign.active');
    Route::post('campaign/destroy', [CampaignController::class, 'destroy'])->name('campaign.destroy');
    Route::get('campaign/image/destroy', [CampaignController::class, 'imgdestroy'])->name('campaign.image.destroy');
    Route::get('/campaign/cart/add', [App\Http\Controllers\Frontend\FrontendController::class, 'campaign_cart_add'])->name('campaign.cart.add');
    Route::get('/campaign/cart/remove-item', [App\Http\Controllers\Frontend\FrontendController::class, 'campaign_cart_remove_item'])->name('campaign.cart.remove_item');

    // Theme 9 Create Route
Route::get('campaign/create/theme-9', [App\Http\Controllers\Admin\CampaignController::class, 'createNine'])->name('campaign.create_nine');

    // Settings
    Route::get('settings/manage', [GeneralSettingController::class, 'index'])->name('settings.index');
    Route::get('settings/create', [GeneralSettingController::class, 'create'])->name('settings.create');
    Route::post('settings/save', [GeneralSettingController::class, 'store'])->name('settings.store');
    Route::get('settings/{id}/edit', [GeneralSettingController::class, 'edit'])->name('settings.edit');
    Route::post('settings/update', [GeneralSettingController::class, 'update'])->name('settings.update');
    Route::post('settings/inactive', [GeneralSettingController::class, 'inactive'])->name('settings.inactive');
    Route::post('settings/active', [GeneralSettingController::class, 'active'])->name('settings.active');
    Route::post('settings/destroy', [GeneralSettingController::class, 'destroy'])->name('settings.destroy');
    // Settings Group এর ভেতরে বা নিচে কোথাও দিন
// Pixel Trigger Setup Routes
Route::get('pixel/setup', [App\Http\Controllers\Admin\GeneralSettingController::class, 'pixelSetup'])->name('admin.pixel_setup');
Route::post('pixel/setup/update', [App\Http\Controllers\Admin\GeneralSettingController::class, 'pixelSetupUpdate'])->name('admin.pixel_setup.update');

    // Social Media
Route::get('social-media/manage', [SocialMediaController::class, 'index'])->name('socialmedias.index');
Route::get('social-media/create', [SocialMediaController::class, 'create'])->name('socialmedias.create');
Route::post('social-media/save', [SocialMediaController::class, 'store'])->name('socialmedias.store');
Route::get('social-media/{id}/edit', [SocialMediaController::class, 'edit'])->name('socialmedias.edit');
Route::post('social-media/update', [SocialMediaController::class, 'update'])->name('socialmedias.update');
Route::post('social-media/active', [SocialMediaController::class, 'active'])->name('socialmedias.active');
Route::post('social-media/inactive', [SocialMediaController::class, 'inactive'])->name('socialmedias.inactive'); 
Route::post('social-media/destroy', [SocialMediaController::class, 'destroy'])->name('socialmedias.destroy');

    // System Update Routes
    Route::group(['prefix' => 'system-update', 'as' => 'admin.system.update.'], function () {
        Route::get('/', [\App\Http\Controllers\Admin\SystemUpdateController::class, 'index'])->name('index');
        Route::post('/update', [\App\Http\Controllers\Admin\SystemUpdateController::class, 'update'])->name('post');
    });

    // Contact
    Route::get('contact/manage', [ContactController::class, 'index'])->name('contact.index');
    Route::get('contact/create', [ContactController::class, 'create'])->name('contact.create');
    Route::post('contact/save', [ContactController::class, 'store'])->name('contact.store');
    Route::get('contact/{id}/edit', [ContactController::class, 'edit'])->name('contact.edit');
    Route::post('contact/update', [ContactController::class, 'update'])->name('contact.update');
    Route::post('contact/inactive', [ContactController::class, 'inactive'])->name('contact.inactive');
    Route::post('contact/active', [ContactController::class, 'active'])->name('contact.active');
    Route::post('contact/destroy', [ContactController::class, 'destroy'])->name('contact.destroy');

    // Banner Category
    Route::get('banner-category/manage', [BannerCategoryController::class, 'index'])->name('banner_category.index');
    Route::get('banner-category/create', [BannerCategoryController::class, 'create'])->name('banner_category.create');
    Route::post('banner-category/save', [BannerCategoryController::class, 'store'])->name('banner_category.store');
    Route::get('banner-category/{id}/edit', [BannerCategoryController::class, 'edit'])->name('banner_category.edit');
    Route::post('banner-category/update', [BannerCategoryController::class, 'update'])->name('banner_category.update');
    Route::post('banner-category/inactive', [BannerCategoryController::class, 'inactive'])->name('banner_category.inactive');
    Route::post('banner-category/active', [BannerCategoryController::class, 'active'])->name('banner_category.active');
    Route::post('banner-category/destroy', [BannerCategoryController::class, 'destroy'])->name('banner_category.destroy');

    // Banner
    Route::get('banner/manage', [BannerController::class, 'index'])->name('banners.index');
    Route::get('banner/create', [BannerController::class, 'create'])->name('banners.create');
    Route::post('banner/save', [BannerController::class, 'store'])->name('banners.store');
    Route::get('banner/{id}/edit', [BannerController::class, 'edit'])->name('banners.edit');
    Route::post('banner/update', [BannerController::class, 'update'])->name('banners.update');
    Route::post('banner/inactive', [BannerController::class, 'inactive'])->name('banners.inactive');
    Route::post('banner/active', [BannerController::class, 'active'])->name('banners.active');
    Route::post('banner/destroy', [BannerController::class, 'destroy'])->name('banners.destroy');

    // Pages
    Route::get('page/manage', [CreatePageController::class, 'index'])->name('pages.index');
    Route::get('page/create', [CreatePageController::class, 'create'])->name('pages.create');
    Route::post('page/save', [CreatePageController::class, 'store'])->name('pages.store');
    Route::get('page/{id}/edit', [CreatePageController::class, 'edit'])->name('pages.edit');
    Route::post('page/update', [CreatePageController::class, 'update'])->name('pages.update');
    Route::post('page/inactive', [CreatePageController::class, 'inactive'])->name('pages.inactive');
    Route::post('page/active', [CreatePageController::class, 'active'])->name('pages.active');
    Route::post('page/destroy', [CreatePageController::class, 'destroy'])->name('pages.destroy');

    // Hot Deal
    Route::get('hotdeal/manage', [CreatePageController::class, 'hotdeal'])->name('hotdeal.index');
    Route::get('hotdeal/create', [CreatePageController::class, 'hotdealcreate'])->name('hotdel.create');
    Route::post('hotdeal/store', [CreatePageController::class, 'hotdealStore'])->name('hotdel.store');
    Route::post('hotdeal/product', [CreatePageController::class, 'hotdealproduct'])->name('hotdel.product');
    Route::post('flash/delete', [CreatePageController::class, 'flashdelete'])->name('flash.delete');
    Route::get('flash/{id}/edit', [CreatePageController::class, 'flashedit'])->name('flash.edit');
    Route::post('hotdeal/update', [CreatePageController::class, 'hotdealUpdate'])->name('hotdel.update');
    Route::post('hotdeal/proedit', [CreatePageController::class, 'hotdealedit'])->name('hotdeledit.product');
    Route::post('flash/inactive', [CreatePageController::class, 'flashinactive'])->name('flash.inactive');
    Route::post('flash/active', [CreatePageController::class, 'flashactive'])->name('flash.active');

    // Loyality
    Route::get('loyality/create', [CreatePageController::class, 'loylatycreate'])->name('loylaty.create');
    Route::post('loyality/product', [CreatePageController::class, 'loyalityproduct'])->name('loyality.product');
    Route::post('loyality/store', [CreatePageController::class, 'loyalityStore'])->name('loyality.store');
    Route::get('loyality/manage', [CreatePageController::class, 'loylatymanage'])->name('loylaty.manage');
    Route::get('loyality/edit/{id}', [CreatePageController::class, 'loylatyedit'])->name('loylaty.edit');
    Route::post('loyality/update', [CreatePageController::class, 'loyalityUpdate'])->name('loyality.update');
    Route::post('loyality/delete', [CreatePageController::class, 'loyalitydelete'])->name('loyality.delete');
    Route::post('loyality/inactive', [CreatePageController::class, 'loyalityinactive'])->name('loyality.inactive');
    Route::post('loyality/active', [CreatePageController::class, 'loyalityactive'])->name('loyality.active');
    Route::post('loyality/author/products', [CreatePageController::class, 'getAuthorProducts'])->name('loyality.author.products');
    Route::get('author/royalty/{slug}', [CreatePageController::class, 'authorflash'])->name('loylaty.author');
    Route::post('author/paymentupdate', [CreatePageController::class, 'authorpaymentUpdate'])->name('author.paymenthistory');
    Route::get('author/payment', [CreatePageController::class, 'paymentlist'])->name('author.payment');
    Route::post('author/payupdate', [CreatePageController::class, 'paymentauthor'])->name('author.payupdate');


    Route::get('author/withdrawal/list', [CreatePageController::class, 'authorpay'])->name('author.payment.pay');
    Route::put('author/withdrawal/update/{id}', [CreatePageController::class, 'payupdate'])->name('admin.withdrawal.update');
    Route::post('author/withdrawal/bulkApprove', [CreatePageController::class, 'bulkApprove'])->name('admin.withdrawal.bulkApprove');
    Route::post('author/withdrawal/bulkReject', [CreatePageController::class, 'bulkReject'])->name('admin.withdrawal.bulkReject');
    Route::post('author/withdrawal/export', [CreatePageController::class, 'export'])->name('admin.withdrawal.export');



    // author witdrowl

    Route::get('author/withdrawal', [WithdrawalController::class, 'index'])->name('author.withdrawal');
    Route::post('author/savewithrawal', [WithdrawalController::class, 'store'])->name('author.withdrawal.store');
    Route::get('author/cancel/{id}', [WithdrawalController::class, 'cancel'])->name('author.cancel');
    //end
    // SMS Template
    Route::get('sms/teamplate', [CreatePageController::class, 'teamplate'])->name('sms.index');
    Route::get('sms/create', [CreatePageController::class, 'createteamplate'])->name('sms.create');
    Route::post('sms/store', [CreatePageController::class, 'storeTeamplate'])->name('sms.store');
    Route::post('sms/delete', [CreatePageController::class, 'smsdelete'])->name('sms.delete');
    Route::get('sms/{id}/edit', [CreatePageController::class, 'smsedit'])->name('sms.edit');
    Route::post('sms/update', [CreatePageController::class, 'smsUpdate'])->name('sms.update');
    Route::post('sms/inactive', [CreatePageController::class, 'smsinactive'])->name('sms.inactive');
    Route::post('sms/active', [CreatePageController::class, 'smsactive'])->name('sms.active');

    // POS Routes
    Route::get('order/create', [OrderController::class, 'order_create'])->name('admin.order.create');
    Route::post('order/store', [OrderController::class, 'order_store'])->name('admin.order.store');
    Route::get('order/cart-add', [OrderController::class, 'cart_add'])->name('admin.order.cart_add');
    Route::get('order/whosalescart-add', [OrderController::class, 'whosalescart_add'])->name('admin.orderwhosales.cart_add');
    Route::get('order/cart-content', [OrderController::class, 'cart_content'])->name('admin.order.cart_content');
    Route::get('order/cart-increment', [OrderController::class, 'cart_increment'])->name('admin.order.cart_increment');
    Route::get('order/cart-decrement', [OrderController::class, 'cart_decrement'])->name('admin.order.cart_decrement');
    Route::get('order/cart-remove', [OrderController::class, 'cart_remove'])->name('admin.order.cart_remove');
    Route::get('order/cart-product-discount', [OrderController::class, 'product_discount'])->name('admin.order.product_discount');
    Route::get('order/cart-product-price', [OrderController::class, 'product_price'])->name('admin.order.product_price');
    Route::get('order/cart-details', [OrderController::class, 'cart_details'])->name('admin.order.cart_details');
    Route::get('order/cart-shipping', [OrderController::class, 'cart_shipping'])->name('admin.order.cart_shipping');
    Route::post('order/cart-clear', [OrderController::class, 'cart_clear'])->name('admin.order.cart_clear');
    Route::post('order/cart-update',[OrderController::class, 'cart_update'])->name('admin.order.cart_update');
    Route::get('order/cart-product-size', [OrderController::class, 'product_size'])->name('admin.order.product_size');


    // Wholesales POS
    Route::get('order/whosales/create', [OrderController::class, 'order_whosalescreate'])->name('admin.order.whosalescreate');
    Route::get('order/whosales/return', [OrderController::class, 'order_whosalesreturn'])->name('admin.order.whosalesreturn');
    Route::post('order/whosalesstore', [OrderController::class, 'order_whosalesstore'])->name('admin.whosalesorder.store');
    Route::get('order/mycart-add', [OrderController::class, 'cart_whosalesadd'])->name('admin.whosalesorder.cart_add');
    Route::get('order/whosalescart-content', [OrderController::class, 'whosalescart_content'])->name('admin.orderwhosales.cart_content');
    Route::get('orderwhosales/cart-details', [OrderController::class, 'cart_detailswhosales'])->name('admin.orderwhosales.cart_details');
    Route::get('order/wholeseller', [OrderController::class, 'Allorder'])->name('admin.order.all_order');
    Route::get('/order/whosales-customers', [OrderController::class, 'whosalesCustomers'])->name('admin.order.whosales_customers');
    Route::get('/order/whosales-customer-info', [OrderController::class, 'whosalesCustomerInfo'])->name('admin.order.whosales_customer_info');

    // Order Management
    Route::get('order/{slug}', [OrderController::class, 'index'])->name('admin.orders');
    Route::get('sound/{slug}', [OrderController::class, 'sound'])->name('sound.order');
    Route::get('order/edit/{invoice_id}', [OrderController::class, 'order_edit'])->name('admin.order.edit');
    Route::get('order/whosalesedit/{invoice_id}', [OrderController::class, 'whosalesorder_edit'])->name('admin.whosalesorder.edit');
    Route::post('order/update', [OrderController::class, 'order_update'])->name('admin.order.update');
    Route::get('order/invoice/{invoice_id}', [OrderController::class, 'invoice'])->name('admin.order.invoice');
    Route::get('order/process/{invoice_id}', [OrderController::class, 'process'])->name('admin.order.process');
    Route::post('order/change', [OrderController::class, 'order_process'])->name('admin.order_change');
    Route::post('order/destroy', [OrderController::class, 'destroy'])->name('admin.order.destroy');
    Route::get('order-assign', [OrderController::class, 'order_assign'])->name('admin.order.assign');
    Route::get('order-status', [OrderController::class, 'order_status'])->name('admin.order.status');
    Route::get('order-bulk-destroy', [OrderController::class, 'bulk_destroy'])->name('admin.order.bulk_destroy');
    Route::post('order-print', [OrderController::class, 'order_print'])->name('admin.order.order_print');
    Route::get('order-csv', [OrderController::class, 'order_csv'])->name('admin.order.order_csv');
    Route::get('bulk-courier/{slug}', [OrderController::class, 'bulk_courier'])->name('admin.bulk_courier');
    Route::get('stock-report', [OrderController::class, 'stock_report'])->name('admin.stock_report');
    Route::get('order-report', [OrderController::class, 'order_report'])->name('admin.order_report');
    Route::get('order-pathao', [OrderController::class, 'order_pathao'])->name('admin.order.pathao');
    Route::get('/pathao-city', [OrderController::class, 'pathaocity'])->name('pathaocity');
    Route::get('/pathao-zone', [OrderController::class, 'pathaozone'])->name('pathaozone');
    Route::post('pay/invoice', [OrderController::class, 'payinvoice'])->name('pay.invoice');
    Route::post('sms/invoice', [OrderController::class, 'smsinvoice'])->name('sms.invoice');
    Route::get('order/testview', [OrderController::class, 'order_textview'])->name('order.testvivew');
    Route::post('testsave/order', [OrderController::class, 'testSaveorder'])->name('testsave-order');
    Route::post('remove-product-from-order', [OrderController::class, 'removePro'])->name('remove-product-from');
    Route::get('orderwhosales/invoice/{invoice_id}', [OrderController::class, 'whosalesinvoice'])->name('admin.whosales.invoice');
    Route::post('order/retuenupdate', [OrderController::class, 'returnupdate'])->name('admin.order.returnupdate');
    Route::get('get-thana-by-district', [OrderController::class, 'getThanas'])->name('get-thana-by-district');
    Route::post('order/singlesms', [OrderController::class, 'singlesms'])->name('admin.order.singlesms');
    Route::post('order/multisms', [OrderController::class, 'multisms'])->name('admin.order.multisms');

    // Wholeseller Management
    Route::get('wholeseller', [Wholesellermanger::class, 'index'])->name('wholeseller.index');
    Route::get('wholeseller/{id}/history', [Wholesellermanger::class, 'historywhosales'])->name('wholesellers.history');
    Route::get('wholeseller/create', [Wholesellermanger::class, 'create'])->name('wholeseller.create');
    Route::post('wholeseller/store', [Wholesellermanger::class, 'store'])->name('wholeseller.store');
    Route::get('wholeseller/{id}/edit', [Wholesellermanger::class, 'edit'])->name('wholeseller.edit');
    Route::get('wholeseller/payment', [Wholesellermanger::class, 'paymentlist'])->name('wholeseller.payment');
    Route::post('wholeseller/payupdate', [Wholesellermanger::class, 'paymentwhosaleer'])->name('wholeseller.payupdate');
    Route::get('wholpay/{id}/edit', [Wholesellermanger::class, 'whosalespayment'])->name('wholeselleredit.payment');
    Route::post('wholpay/update', [Wholesellermanger::class, 'whosalesupdate'])->name('wholesellerpay.updates');
    Route::post('wholpay/delete', [Wholesellermanger::class, 'paymentdelete'])->name('wholeseldelete.payment');
    Route::post('wholeseller/update', [Wholesellermanger::class, 'update'])->name('wholeseller.update');
    Route::post('wholeseller/inactive', [Wholesellermanger::class, 'inactive'])->name('wholeseller.inactive');
    Route::post('wholeseller/active', [Wholesellermanger::class, 'active'])->name('wholeseller.active');
    Route::post('wholeseller/destroy', [Wholesellermanger::class, 'destroy'])->name('wholeseller.destroy');

    // Dealer Management
    Route::get('dealer/manage', [App\Http\Controllers\Admin\DealerController::class, 'index'])->name('admin.dealer.index');
    Route::get('dealer/create', [App\Http\Controllers\Admin\DealerController::class, 'create'])->name('admin.dealer.create');
    Route::post('dealer/store', [App\Http\Controllers\Admin\DealerController::class, 'store'])->name('admin.dealer.store');
    Route::get('dealer/{id}/edit', [App\Http\Controllers\Admin\DealerController::class, 'edit'])->name('admin.dealer.edit');
    Route::post('dealer/update', [App\Http\Controllers\Admin\DealerController::class, 'update'])->name('admin.dealer.update');

    Route::get('dealer/orders', [App\Http\Controllers\Admin\DealerController::class, 'orderList'])->name('admin.dealer.orders');


    // Admin Dealer Helper Routes
    Route::post('dealer/inactive', [App\Http\Controllers\Admin\DealerController::class, 'inactive'])->name('admin.dealer.inactive');
    Route::post('dealer/active', [App\Http\Controllers\Admin\DealerController::class, 'active'])->name('admin.dealer.active');
    Route::post('dealer/destroy', [App\Http\Controllers\Admin\DealerController::class, 'destroy'])->name('admin.dealer.destroy');

    // Dealer Product Assign
    Route::get('dealer/{id}/products', [App\Http\Controllers\Admin\DealerController::class, 'products'])->name('admin.dealer.products');
    Route::get('dealer/{id}/profile', [App\Http\Controllers\Admin\DealerController::class, 'profile'])->name('admin.dealer.profile');
    Route::post('dealer/product/store', [App\Http\Controllers\Admin\DealerController::class, 'productStore'])->name('admin.dealer.product.store');
    Route::post('dealer/product/destroy', [App\Http\Controllers\Admin\DealerController::class, 'productDestroy'])->name('admin.dealer.product.destroy');

    // Reviews
    Route::get('reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::get('review/pending', [ReviewController::class, 'pending'])->name('reviews.pending');
    Route::post('review/inactive', [ReviewController::class, 'inactive'])->name('reviews.inactive');
    Route::post('review/active', [ReviewController::class, 'active'])->name('reviews.active');
    Route::get('review/create', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('review/save', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('review/{id}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
    Route::post('review/update', [ReviewController::class, 'update'])->name('reviews.update');
    Route::post('review/destroy', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::get('issueshow', [ReviewController::class, 'issueshow'])->name('issue.index');

    // Shipping Charge
    Route::get('shipping-charge/manage', [ShippingChargeController::class, 'index'])->name('shippingcharges.index');
    Route::get('shipping-charge/create', [ShippingChargeController::class, 'create'])->name('shippingcharges.create');
    Route::post('shipping-charge/save', [ShippingChargeController::class, 'store'])->name('shippingcharges.store');
    Route::get('shipping-charge/{id}/edit', [ShippingChargeController::class, 'edit'])->name('shippingcharges.edit');
    Route::post('shipping-charge/update', [ShippingChargeController::class, 'update'])->name('shippingcharges.update');
    Route::post('shipping-charge/inactive', [ShippingChargeController::class, 'inactive'])->name('shippingcharges.inactive');
    Route::post('shipping-charge/active', [ShippingChargeController::class, 'active'])->name('shippingcharges.active');
    Route::post('shipping-charge/destroy', [ShippingChargeController::class, 'destroy'])->name('shippingcharges.destroy');

    // Customer Management
    Route::get('customer', [CustomerManageController::class, 'index'])->name('customers.index');
    Route::get('customer/manage', [CustomerManageController::class, 'index'])->name('customers.index');
    Route::get('customer/{id}/edit', [CustomerManageController::class, 'edit'])->name('customers.edit');
    Route::post('customer/update', [CustomerManageController::class, 'update'])->name('customers.update');
    Route::post('customer/inactive', [CustomerManageController::class, 'inactive'])->name('customers.inactive');
    Route::post('customer/active', [CustomerManageController::class, 'active'])->name('customers.active');
    Route::get('customer/profile', [CustomerManageController::class, 'profile'])->name('customers.profile');
    Route::post('customer/adminlog', [CustomerManageController::class, 'adminlog'])->name('customers.adminlog');
    Route::get('customer/ip-block', [CustomerManageController::class, 'ip_block'])->name('customers.ip_block');
    Route::post('customer/ip-store', [CustomerManageController::class, 'ipblock_store'])->name('customers.ipblock.store');
    Route::post('customer/ip-update', [CustomerManageController::class, 'ipblock_update'])->name('customers.ipblock.update');
    Route::post('customer/ip-destroy', [CustomerManageController::class, 'ipblock_destroy'])->name('customers.ipblock.destroy');




    // ================== Inventory Management Routes ==================
    Route::group(['as' => 'admin.inventory.', 'prefix' => 'inventory'], function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\InventoryController::class, 'dashboard'])->name('dashboard');
        Route::get('/logs', [App\Http\Controllers\Admin\InventoryController::class, 'logs'])->name('logs');

        // Shipping Manager
        Route::get('/shipping', [App\Http\Controllers\Admin\InventoryController::class, 'shipping'])->name('shipping');
        Route::post('/shipping/fetch', [App\Http\Controllers\Admin\InventoryController::class, 'shippingFetch'])->name('shipping_fetch');
        Route::post('/shipping/confirm', [App\Http\Controllers\Admin\InventoryController::class, 'shippingConfirm'])->name('shipping_confirm');

        // Stock List
        Route::get('/stock-list', [App\Http\Controllers\Admin\InventoryController::class, 'stockList'])->name('stock_list');

        // Return Manager
        Route::get('/return', [App\Http\Controllers\Admin\InventoryController::class, 'return'])->name('return');
        Route::post('/return/fetch', [App\Http\Controllers\Admin\InventoryController::class, 'returnFetch'])->name('return_fetch');
        Route::post('/return/process', [App\Http\Controllers\Admin\InventoryController::class, 'returnProcess'])->name('return_process');
    });

    Route::group(['as' => 'admin.purchase.', 'prefix' => 'purchase'], function () {
        Route::get('/', [App\Http\Controllers\Admin\PurchaseController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\PurchaseController::class, 'create'])->name('create');
        Route::post('/store', [App\Http\Controllers\Admin\PurchaseController::class, 'store'])->name('store');
        Route::get('/search', [App\Http\Controllers\Admin\PurchaseController::class, 'searchProduct'])->name('search_product');
        Route::get('/show/{id}', [App\Http\Controllers\Admin\PurchaseController::class, 'show'])->name('show');
        Route::get('/edit/{id}', [App\Http\Controllers\Admin\PurchaseController::class, 'edit'])->name('edit');
        Route::post('/update', [App\Http\Controllers\Admin\PurchaseController::class, 'update'])->name('update');
    });

    // ================== Supplier Management Routes ==================
    Route::group(['as' => 'admin.supplier.', 'prefix' => 'supplier'], function () {
        Route::get('/', [App\Http\Controllers\Admin\SupplierController::class, 'index'])->name('index');
        Route::post('/store', [App\Http\Controllers\Admin\SupplierController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [App\Http\Controllers\Admin\SupplierController::class, 'edit'])->name('edit');
        Route::post('/update', [App\Http\Controllers\Admin\SupplierController::class, 'update'])->name('update');
        Route::post('/delete/{id}', [App\Http\Controllers\Admin\SupplierController::class, 'destroy'])->name('destroy');
        Route::get('/history/{id}', [App\Http\Controllers\Admin\SupplierController::class, 'history'])->name('history');
    });

});

Route::get('/join/{slug}', [App\Http\Controllers\Frontend\FrontendController::class, 'recruitmentPage'])->name('frontend.recruitment.page');
Route::post('/join/submit', [App\Http\Controllers\Frontend\FrontendController::class, 'recruitmentRegister'])->name('frontend.recruitment.submit');

// Courier Reconciliation Routes
Route::get('admin/courier/import', [App\Http\Controllers\Admin\CourierManageController::class, 'index'])->name('admin.courier.import');
Route::post('admin/courier/store', [App\Http\Controllers\Admin\CourierManageController::class, 'store'])->name('admin.courier.store');

Route::post('/ajax/track/event', [App\Http\Controllers\Frontend\CustomerController::class, 'ajaxTrackEvent'])->name('ajax.track.event');
Route::post('/ajax/track/activity', [App\Http\Controllers\Frontend\FrontendController::class, 'trackActivity'])->name('ajax.track.activity');

















