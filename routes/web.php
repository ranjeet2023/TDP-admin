<?php

// use App\Models\User;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\customer\CustomerController;
use App\Http\Controllers\customer\DiamondController;
use App\Http\Controllers\customer\DiamondcusController;
use App\Http\Controllers\customer\HoldController;
use App\Http\Controllers\customer\OrdersController;
use App\Http\Controllers\customer\WishlistController;
use App\Http\Controllers\customer\CartController;

use App\Http\Controllers\supplier\SupplierController;
use App\Http\Controllers\supplier\SupplierDiamondController;
use App\Http\Controllers\supplier\SupplierOrderController;
use App\Http\Controllers\supplier\InvoiceController;

use App\Http\Controllers\admin\ADashboardController;
use App\Http\Controllers\admin\ACustomerController;
use App\Http\Controllers\admin\ASupplierController;
use App\Http\Controllers\admin\ADiamondController;
use App\Http\Controllers\admin\AFTPController;
use App\Http\Controllers\admin\AOrderController;
use App\Http\Controllers\admin\AStaffController;
use App\Http\Controllers\admin\MatchPairController;
use App\Http\Controllers\admin\AssociateController;
use App\Http\Controllers\admin\PricingController;
use App\Http\Controllers\admin\LogController;
use App\Http\Controllers\admin\AExcelController;
use App\Http\Controllers\admin\DebugController;
use App\Http\Controllers\admin\AccountController;
use App\Http\Controllers\admin\ReturnDiamondController;
use App\Http\Controllers\admin\EventController;
use App\Http\Controllers\admin\ExtendedController;
use App\Http\Controllers\admin\PermissionController;
use App\Http\Controllers\admin\LogisticsController;
use App\Http\Controllers\admin\AParcelGoodsController;
use App\Http\Controllers\admin\TrackingController;
use App\Http\Controllers\admin\AcareerController;
use App\Http\Controllers\admin\LeadsController;
use App\Http\Controllers\admin\TestController;
use App\Http\Controllers\admin\AOrderNewController;


use App\Http\Controllers\GetInTouch;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//----------------------------------COMMON------------------------------------------------------------------------------//
Route::get('/', function () { return view('frontend.index'); });
Route::get('/about', function () { return view('frontend.aboutus'); });
Route::get('/solution', function () { return view('frontend.solution'); });
Route::get('/buyer', function () { return view('frontend.buyers'); });
Route::get('/suppliers', function () { return view('frontend.suppliers'); });
Route::get('/contact', function () { return view('frontend.contactus'); });
// Route::get('/career', function () { return view('frontend.career'); });

Route::get('/career',[AcareerController::class,'Career'])->name('admin.career');
Route::get('apply-now/{job_id}',[AcareerController::class,'Applied'])->name('applicant.form');

Route::get('/privacy-policy', function () {    return view('frontend.privacy-policy'); });
Route::get('/terms-and-conditions', function () {    return view('frontend.term-condition'); });

Route::get('/naturaldiamond', function () {    return view('frontend.natural-diamond'); });
Route::get('/labgrowndiamond', function () {    return view('frontend.labgrown-diamond'); });
Route::get('/melee-diamond', function () {    return view('frontend.melee-diamond'); });

Route::get('/connect-with-us', function () { return view('frontend.connect-with-us'); });

Route::get('/jck-las-vegas-2022', function () { return view('frontend.jcklasvegas'); });
Route::post('show-post', [EventController::class, 'postEvent']);

Route::get('/australian-jewellery-fair', function () { return view('frontend.australian-jewellery'); });

Route::post('subscribe-post', [EventController::class, 'postEvent']);
Route::get('/thankyou', function () { return view('frontend.thankyou'); });

// Route::get('logout',[AuthController::class, 'logout'])->name('logouts');

Route::post('get-in-touch', [GetInTouch::class,'SendMessage'])->name('getintouch');

// Route::get('email/verify/{token}', function (Illuminate\Http\Request $request) {
//     $email = Crypt::decrypt($request->route('token'));

//     $user = User::where('email', $email)->firstOrfail();
//     $user->update(['email_verified_at' => Carbon::now()]);

//     return response()->json(['success' => 'Email verified successfully']);
// });

//----------------------------------Login-Registration------------------------------------------------------------------------------//


Route::controller(AuthController::class)->group(function () {
    Route::get('logout', 'logout')->name('logouts');
    Route::get('login', 'index')->name('login');
    Route::post('post-login','postLogin');
    Route::get('register','register')->name('registers');
    Route::post('post-registration','postRegistration');
    Route::get('verification/{id}','verifyEmail')->name('verifyemail');
    Route::get('forgot-password','forgotPassword')->name('forgot-password');
    Route::post('post-forgot-password','postForgotPassword')->name('post-forgot-password');
    Route::get('reset-password/{token}','resetPassword')->name('reset-password');
    Route::post('post-reset-password','postResetPassword')->name('reset-password-post');
});


//----------------------------------CUSTOMER------------------------------------------------------------------------------//
Route::group(['middleware' => 'App\Http\Middleware\CustomerMiddleware'], function()
{
    Route::controller(CustomerController::class)->group(function () {
        Route::get('dashboard', 'index')->name('dashboard');
        Route::get('profile', 'profile')->name('profile');
        Route::get('company-details', 'CompanyDetails')->name('comapny-details');
        Route::get('edit-company-details', 'EditCompanyDetails')->name('edit-comapny-details');
        Route::post('update-company-details', 'UpdateCompanyDetails')->name('update-comapny-details');
        Route::get('edit-profile', 'editprofile')->name('edit-profile');
        Route::post('update-profile',  'updateprofile');
        Route::get('account-setting',  'accountsetting')->name('account-setting');
        Route::post('customer-update-password', 'customerupdatepassword')->name('updatepassword');
        Route::get('white-lable', 'WhiteLable')->name('white.lable');
        Route::post('post-white-label', 'PostWhiteLabel')->name('white.post.data');
    });

    Route::controller(DiamondcusController::class)->group(function () {
        Route::get('diamond-search', 'diamondsearch')->name('diamond-search');
        Route::get('diamond-list', 'diamondlist')->name('diamond-list');
        Route::post('diamond-grid-overview', 'diamondlistOverview')->name('diamond-list-overview');
        Route::post('diamond-listview-overview', 'diamondlistviewOverview')->name('diamond-listview-overview');
        Route::get('diamond-detail-natural/{sku}', 'DiamondDetailnatural')->name('diamond-detail-natural');
        Route::get('diamond-detail-lab/{sku}', 'DiamondDetaillab')->name('diamond-detail-lab');
    });

    Route::controller(DiamondController::class)->group(function () {
        Route::get('natural-diamond', 'diamondnatural')->name('natural-diamond');
        Route::post('natural-diamond-list', 'naturaldiamondlist')->name('natural-diamond-list');
        Route::get('natural-diamond-detail/{sku}', 'naturalDiamondDetail')->name('diamond-detail.natural');
        Route::post('natural-excel-download', 'NaturalExcelDownload')->name('natural-excel.download');
        Route::get('labgrown-diamond', 'diamondlabgown')->name('labgrown-diamond');
        Route::post('labgrown-diamond-list', 'labgrowndiamondlist')->name('labgrown-diamond-list');
        Route::get('lab-diamond-detail/{sku}', 'labDiamondDetail')->name('diamond-detail.labgrown');
        Route::post('lab-excel-download', 'LabExcelDownload')->name('lab-excel.download');
        Route::post('confirmPopup', 'confirmPopup')->name('confirmPopup');
        Route::post('confirmOrder', 'confirmOrder')->name('confirmOrder');
        Route::get('lab-video/{sku}',  'Labvideo')->name('customer.lab.video');
        Route::get('natural-video/{sku}','NaturalVideo')->name('customer.natural.video');
    });

    Route::controller(HoldController::class)->group(function () {
        Route::post('hold-list-modal', 'holdPopup')->name('holdPopup');
        Route::post('holdOrder', 'holdOrder')->name('holdOrder');
        Route::get('hold', 'holdList')->name('holdList');
        Route::post('holddata', 'holdData')->name('holddata');
        Route::post('hold-confirm-modal', 'holdConfirmPopup')->name('holdConfirmPopup');
        Route::post('HoldConfirm', 'HoldConfirm')->name('HoldConfirm');
    });

    Route::controller(OrdersController::class)->group(function () {
        Route::get('orders', 'index')->name('orders');
        Route::post('orderlist', 'orderList')->name('orderlist');
        Route::get('customer-invoice/{invoice_id}',  'CustomerInvoice')->name('customer.invoice');
        Route::get('invoice', 'invoice')->name('invoice');
    });

    Route::controller(WishlistController::class)->group(function () {
        Route::get('wishlist','WishList')->name('wish-list');
        Route::post('add-wish-list', 'addWishList')->name('add-wish-list');
        Route::post('remove-wish-list',  'removeWishList')->name('remove-wish-list');
    });

    Route::controller(CartController::class)->group(function () {
        Route::post('add-to-cart-modal', 'addtocartPopup')->name('addtocartPopup');
        Route::post('add-to-cart', 'addToCart')->name('addtocart');
        Route::get('cart', 'cartList')->name('cartlist');
        Route::post('remove-diamond-to-cart', 'RemoveCart')->name('removecart');
    });

});

//----------------------------------SUPPLIER------------------------------------------------------------------------------//
Route::group(['middleware' => 'App\Http\Middleware\SupplierMiddleware'], function()
{
    Route::controller(SupplierController::class)->group(function () {
        Route::get('supplier', 'index')->name('supplier');
        Route::get('upload-diamond', 'UploadDiamond')->name('upload-diamond');
        Route::post('/upload-file', 'StockUpload')->name('fileUpload');
        Route::get('supplier-profile', 'supplierProfile')->name('supplier.profile');
        Route::get('supplier-profile-edit', 'supplierProfileEdit')->name('supplier.profile.edit');
        Route::post('supplier-profile-update', 'supplierProfileUpdate')->name('supplier.profile.update');
        Route::get('supplier-account-setting', 'supplierAccountSetting')->name('supplier.account.setting');
        Route::post('supplier-password-update', 'supplierPasswordUpdate')->name('supplier.account.passwordupdate');
        Route::get('invalid-diamond', 'invalidDiamond')->name('invalid.diamond');
        Route::post('invalid-diamond-details', 'invalidDiamondDetail')->name('invalid.diamond.details');
    });

    Route::get('supplier-stock',[SupplierDiamondController::class, 'supplierStock'])->name('supplier-diamond');
        Route::post('supplier-diamond-list',[SupplierDiamondController::class, 'supplierDiamondList'])->name('supplier.diamond.list');
        Route::get('supplier-search',[SupplierDiamondController::class, 'supplierSearch'])->name('supplier-search-all');
        Route::get('hold-diamond',[SupplierOrderController::class, 'holdDiamond'])->name('supplier.hold-diamond');
        Route::get('order-diamond',[SupplierOrderController::class, 'orderDiamond'])->name('supplier.order-diamond');
        Route::post('supplier-order-status',[SupplierOrderController::class, 'OrderStatus'])->name('order-status');
        Route::get('supplier-invoice',[InvoiceController::class, 'SupplierInvoice'])->name('supplier-invoice');
        Route::post('invoiceFileUpload',[InvoiceController::class, 'InvoiceFileUpload'])->name('invoice-file-upload');
        Route::get('supplier_invoice_download/{id}',[InvoiceController::class, 'InvoiceFileDownload'])->name('invoice-file-Download');
});

//----------------------------------ADMIN------------------------------------------------------------------------------//
Route::middleware(['auth', 'admin_mid'])->group(function()
{
    Route::controller(ADashboardController::class)->group(function () {
        Route::get('admin', 'index')->name('admin');
        Route::get('admin-profile', 'adminProfile')->name('admin.profile');
        Route::get('admin-profile-edit', 'adminProfileEdit')->name('admin.profile.edit');
        Route::get('invoices-to-pickups', 'InoivesToPickups')->name('Invoices-To-Pickups');
        Route::post('customer-upload', 'importCustomer')->name('customer.upload');
        Route::post('supplier-upload', 'supplierCustomer')->name('supplier.upload');
        Route::get('update-raprate', 'updateraprate')->name('raprate.update');
        Route::get('add-diamond', 'AddDiamond')->name('add.diamond');
        Route::post('post-diamond', 'AddDiamondPost')->name('add.diamond.post');
        Route::get('delete-diamond/{id}/{check}', 'DeleteDiamond')->name('delete.diamond');
    });

    Route::controller(ACustomerController::class)->group(function () {
        Route::get('customer-list', 'customerList')->name('customer');
        Route::get('pcustomer', 'pendingCustomerList')->name('customer.pending');
        Route::get('gcustomer', 'goldCustomerList')->name('customer.gold');
        Route::get('scustomer', 'silverCustomerList')->name('customer.silver');
        Route::get('deletedcustomer', 'deletedCustomerList')->name('customer.deleted.list');
        Route::get('customer-delete/{id}', 'deletedCustomer')->name('customer.deleted');
        Route::get('customer-supplier-request/{id}', 'SupplierRequestCustomer')->name('customer.supplier-request');
        Route::post('customer-supplier-request-trun-all', 'supplierRequestTrunon')->name('update.customer.turn-all');
        Route::post('customer-supplier-request-trun-off', 'supplierRequestTrunOff')->name('update.customer.turn-off');
        Route::post('customer-supplier-request-trun', 'supplierRequestTrun')->name('update.customer.turn');
        Route::post('admin-customer-approve', 'approveCustomer')->name('customer.approve');
        Route::post('admin-customer-move-pending', 'CustomerMoveToPending')->name('move.customer.pending');
        Route::post('resend-email-customer', 'customerResendEmailVerify')->name('customer-resend-email');
        Route::get('customer-move/{id}', 'MoveCustomer')->name('customer.move');
        Route::get('customer-edit/{id}', 'customerEdit')->name('customer-edit');
        Route::post('update-customer-profile', 'updateCustomerProfile')->name('update.customer.profile');
        Route::get('add-customer', 'addCustomer')->name('add.customer');
        Route::post('add-new-customer', 'addNewCustomer')->name('add.customer.post');
        Route::get('customer-password/{id}', 'CustomerPassword')->name('customer.password');
        Route::post('update-customer-password', 'updateCustomerPassword')->name('update.customer.password');
        Route::get('place-order', 'AdminOrderPlace')->name('admin-Order-Place');
        Route::post('place-order-save', 'AdminOrderPlaceSave')->name('admin.Place-Order-Save');
        Route::post('search-diamond-order', 'searchDaimondOrder')->name('admin.Search-Diamond-Order');

        Route::post('show-shipping-details','ShowShippingDetails')->name('admin.show-shipping-details');

        Route::post('edit-shipping-details','getShippingDetails')->name('admin.get-shipping-details');

        Route::post('add-edit-shipping-details','addEditShippingDetails')->name('admin.add-edit-shipping-details');
        Route::post('delete-shipping-details','DeleteShippingDetails')->name('admin.delete-shipping-details');
        Route::post('shipping-address-default','ShippingAddressDefault')->name('admin.defaault-shipping-addrss');
    });

    Route::controller(LeadsController::class)->group(function () {
        Route::get('leads-list', 'LeadsList')->name('leads-list');
        Route::post('leads-list', 'LeadsList')->name('leads-list-post');
        Route::post('leads-comment-add', 'LeadsCommentAdd')->name('leads-comment-add');
        Route::post('leads-comment-show', 'LeadsCommentsShow')->name('leads-comments-show');
        Route::get('leads-edit/{id}', 'LeadsEdit')->name('leads-edit');
        Route::post('leads-convert ', 'LeadsConvert')->name('leads-convert');
        Route::post('leads-edit-post', 'LeadsEditPost')->name('leads-edit-post');
        Route::get('leads-report', 'LeadsReport')->name('leads-report');
        Route::post('leads-report', 'LeadsReport');
        Route::get('leads-report-detail', 'LeadsReportDetail')->name('leads-report-detail');
        Route::get('leads-report-user-detail/{id}', 'LeadsUserDetail')->name('leads-user-detail');
        Route::get('add-new-leads', 'AddNewLeads')->name('add-new-leads');
        Route::post('add-new-leads-post', 'AddNewLeadsPost')->name('add-new-leads-post');
        Route::post('leads-send-email', 'LeadsSendEmail')->name('leadsendemail');
        Route::get('create-email-template', 'EmailTemplate');
        Route::post('create-email-template', 'EmailTemplate');
        Route::post('leads-email-template', 'LeadsEmailTemplate');
        Route::post('leads-template-show', 'LeadsTemplateShow');
        Route::post('leads-template-delete', 'LeadsTemplateDelete');
    });

    Route::controller(ASupplierController::class)->group(function () {
        Route::get('pending-suppliers-list', 'suppliersPendingList')->name('supplier.pending.list');
        Route::post('pending-supplier-popup', 'supplierPendingPopup')->name('supplier.pending.popup');
        Route::post('post-popup-pending-Suppliers', 'postPopupPendingSupplier')->name('post.Popup.pending.supplier');
        Route::get('pending-supplier-delete/{id}', 'pendingSupplierDelete')->name('supplier.pending.delete');
        Route::get('add-suppliers', 'addSuppliers')->name('add.supplier');
        Route::post('add-new-supplier', 'addNewSupplier')->name('add.new.supplier');
        Route::post('activate-suppliers', 'activateSuppliers')->name('activate.supplier');
        Route::post('pending-supplier-followup', 'pendingSupplierFollowup')->name('pending-Supplier-Followup');
        Route::post('followup-details', 'followupDetails')->name('followup-Details');
        Route::get('suppliers-list', 'suppliersList')->name('supplier.list');
        Route::post('suppliers-list', 'getSuppliersList')->name('supplier.list-post');
        Route::get('suppliers-all-diamond/{id}', 'suppliersalldiamond')->name('supplier.suppliers-all-diamond');
        Route::get('downloadSuppliersFile/{id}', 'downloadSuppliersFile')->name('supplier.downloadSuppliersFile');
        Route::post('valid-diamond-count', 'validDiamondCount')->name('supplier.valid-diamond-port');
        Route::get('suppliers-edit/{id}', 'supplierEdit')->name('supplier.edit');
        Route::get('suppliers-password/{id}', 'supplierPassword')->name('supplier.password');
        Route::post('update-supplier-password', 'updateSupplierPassword')->name('update.supplier.password');
        Route::post('post-supplier-edit', 'supplierEditSave')->name('supplier.edit.save');
        Route::get('deleted-supplier-list', 'supplierDeleteList')->name('supplier.deleted.list');
        Route::get('expired-report', 'expiredreport')->name('expired.report');
        Route::post('supplier-upload-report', 'GetSupplierUploadReport')->name('supplier.upload.report');
        Route::get('suppliers-delete/{id}', 'DeleteSupplier')->name('supplier./delete');
        Route::get('supplier-markup/{id}', 'supplierMarkup')->name('supplier-markup');
        Route::post('post-supplier-markup', 'supplierMarkupPost')->name('supplier-markup-post');
        Route::post('resend-email-supplier', 'supplierResendEmailVerify')->name('supplier-resend-email');
        Route::get('supplier-move/{id}', 'Movesupplier')->name('supplier.move');
        Route::get('move-supplier-pending-list/{id}', 'SupplierMovePending')->name('supplier.move.pending');
        Route::get('activate-supplier-account/{id}', 'ActivateSupplierAccount')->name('activate.supplier.account');
        Route::get('download-images/{id}', 'downloadImages')->name('download-images');
        Route::get('invoice-list/{id}', 'supplierInvoice')->name('invoice.list');
        Route::get('invoice-download/{id}', 'InvoiceDownload')->name('invoice-download');
        Route::get('my-invoice', 'SupInvoice');
        Route::get('suppliers-invalid-diamond/{id}', 'suppliersInvalidDiamond')->name('suppliers-invalid');
        Route::post('invalid-diamond', 'inDiamondDetail')->name('diamond-invalid');
        Route::get('admin-upload-diamond', 'adminuploadDiamond')->name('admin-upload-diamond');
        Route::post('admin-upload-diamond-post', 'adminuploadDiamondPost')->name('admin-upload-diamond-post');
        Route::get('last-deleted-stones', 'LastDeletedStones')->name('Last-Deleted-Stones');
    });

    Route::get('suppliers-stock-refresh/{id}',[AFTPController::class, 'supplierStockRefresh'])->name('supplier.stock-refresh');

    Route::controller(ADiamondController::class)->group(function () {
        Route::get('image-video-request','ImageVideoRequest')->name('Image-Video-Request');
        Route::post('image-video-request-post', 'ImageVideoRequestPost')->name('Image-Video-Request-Post');
        Route::get('replacement-diamond', 'ReplacementDiamond')->name('Replacement-Diamond');
        Route::post('replacement-diamond-post', 'ReplacementDiamondPost')->name('Replacement-Diamond-Post');
        Route::get('unloaded-natural-diamond', 'UnloadedNaturalList')->name('unloaded-natural-diamond');
        Route::post('unloaded-natural-diamond', 'UnloadedNaturalListSearch')->name('unloaded-natural-diamond-search');
        Route::post('movetosearch-natural', 'movetoSearchNatural')->name('movetosearch-natural');
        Route::get('unloaded-lab-diamond', 'UnloadedLabList')->name('unloaded-lab-diamond');
        Route::post('unloaded-lab-diamond', 'UnloadedLabListSearch')->name('unloaded-lab-diamond-search');
        Route::post('movetosearch-labgrown', 'movetoSearchLabgrown')->name('movetosearch-labgrown');
        Route::get('diamond_natural', 'diamondNatural')->name('diamond_natural');
        Route::post('diamondcountnatural', 'diamondcountnatural')->name('diamondcountnatural');
        Route::get('diamond_natural_list', 'diamondNaturalList')->name('diamond_natural_list');
        Route::post('diamond_natural_list', 'diamondNaturalSearch')->name('diamondNaturalSearch');
        Route::post('allStockDownload-natural', 'NaturalDiamondDownload')->name('allStockDownload-natural');
        Route::get('diamond_labgrown', 'diamondLabgrown')->name('diamond_labgrown');
        Route::post('diamondcountlabgrown', 'diamondcountlabgrown')->name('diamondcountlabgrown');
        Route::get('diamond_labgrown_list', 'diamondLabgrownList')->name('diamond_labgrown_list');
        Route::post('diamond_labgrown_list', 'diamondLabgrownSearch')->name('diamondLabgrownSearch');
        Route::post('diamond-view-detail', 'diamondViewDetail')->name('diamond-view-detail');
        Route::post('allStockDownload-labgrown', 'allStockDownloadLabgrown')->name('allStockDownload-labgrown');
        Route::get('diamond-status', 'DiamondStatus')->name('diamond-status');
        Route::post('diamond-status', 'DiamondStatusPost')->name('diamond-status-post');
    });

    Route::controller(AOrderController::class)->group(function () {
        Route::get('order-list', 'orderList')->name('order-list');
        Route::post('all-order-excel-download', 'AllOrderExcelDownload')->name('admin.All-Order-Excel-Download');
        Route::get('order-list-sales', 'orderListSales')->name('order-list-Sales');
        Route::get('cart-list', 'cartList')->name('Cart-list');
        Route::post('cart-list-details', 'cartListDetails')->name('Cart-List-Details');
        Route::get('enquiry-list', 'enquiryList')->name('enquiry-list');
        Route::get('enquiry-list-detail/{id}', 'enquiryListDetail')->name('enquiry-list-detail');
        Route::post('admin-update-priority-status', 'AdminUpdatePriorityStatus')->name('Admin-Update-Priority-Status');
        Route::post('update-enquiry-list', 'updateEnquiryStatus')->name('admin.update-enquiry-list');
        Route::post('port-enquiry-status', 'portEnquiryStatus')->name('admin.Port-Enquiry-Status');
        Route::post('admin-internal-confirmation', 'adminInternalConfirmation')->name('admin.internal-confirmation');
        Route::post('admin-update-order-status', 'updateOrderStatus')->name('admin.order-status.update');
        Route::post('admin-confirm-to-supplier', 'confirmToSupplierPopup')->name('admin.confirm-to-supplier');
        Route::post('admin-confirmToSupplier', 'confirmToSupplier')->name('admin-confirmToSupplier');
        Route::post('admin-invoice-popup', 'invoicePopupPrepare')->name('admin-invoice-popup');
        Route::post('admin-create-invoice', 'invoiceCreate')->name('admin-create-invoice');
        Route::post('admin-cancel-invoice', 'invoiceCancel')->name('admin-cancel-invoice');
        Route::post('admin-sales-invoice-popup', 'salesinvoicePopupPrepare')->name('admin-Sales-invoice-popup');
        Route::post('admin-sales-invoice', 'salesinvoiceCreate')->name('admin-Create-Sales-invoice');
        Route::post('admin-perfoma-popup', 'PerfomaInvoicePrepare')->name('admin-perfoma-popup');
        Route::post('admin-perfoma-invoice', 'PerfomaCreate')->name('admin-perfoma-invoice');
        Route::get('admin-perfoma-invoice-list', 'PerfomaList')->name('admin-perfoma-invoice-list');
        Route::get('delete-invoice/{id}', 'DeleteInvoice')->name('delete-invoice');
        Route::post('admin-invoice_generated_status', 'AdminInvoiceGeneratedStatus')->name('admin.invoice_generated_status');
        Route::post('admin-view-order-detail', 'ViewOrderDetail')->name('admin.view-order-detail');
        Route::post('admin-order-reverse', 'OrderReverseDiamond')->name('admin.order-reverse');
        Route::post('admin-order-release', 'OrderReleaseDiamond')->name('admin.order-release');
        Route::get('admin-release-list/{id}', 'ReleaseListDetail')->name('admin.release-list');
        Route::post('admin-update-order-price', 'UpdateOrderPrice')->name('admin.update-order-price');
        Route::get('hold-diamond-list/{id}', 'holdList')->name('hold-diamond-list');
        Route::post('admin-update-hold-status', 'updateHoldStatus')->name('admin-update-hold-status');
        Route::get('invoice-list', 'invoiceList')->name('invoice-list');
        Route::post('invoice-list', 'invoiceList')->name('invoice-list-post');
        Route::post('send-mail-to-customer', 'SendMailToCustomer')->name('send-mail-to-customer');
        Route::post('invoice-list-diamonds', 'invoiceListDiamonds')->name('invoice-list-diamonds');
        Route::post('track-order', 'TrackOrder')->name('track-order');
        Route::post('payment-status', 'PaymentStatus')->name('payment-status');
        Route::post('logistics-saveqrcode', 'logisticsSaveQrCode')->name('logistics-saveqrcode');
        Route::get('hold-reminder-message', 'holdReminderMessage')->name('Hold-Reminder-Message');
        Route::post('admin-update-exchange-rate', 'AdminUpdateExchangeRate')->name('admin-update-exchange-rate');
    });
    Route::controller(AOrderNewController::class)->group(function () {
        Route::get('order-list-new','orderListNew')->name('order-List-New');
        Route::post('order-list-comment-add','orderListComment');
        Route::post('customer_order_approve', 'customerorderapprove');
        Route::post('update-enquiry-list-new', 'updateEnquiryStatusNew');
        Route::post('order_comment', 'ordercomment');
        Route::post('admin-update-exchange-rate-new', 'AdminUpdateExchangeRateNew')->name('admin-update-exchange-rate-new');
    });

    Route::controller(LogisticsController::class)->group(function () {
        Route::post('admin-update-qc-status', 'updateQcStatus')->name('admin.qc-status.update');
        Route::post('admin-check-pickup', 'checkPickup')->name('admin.check-pickup');
        Route::get('pickup-list', 'pickupList')->name('admin-pickup-list');
        Route::post('generate-export', 'GenerateMemo')->name('generate-memo');
        Route::post('directshippment-confirmation', 'directshippmentConfirmation')->name('directshippment-Confirmation');
        Route::post('admin-qc-review-inout', 'QcReviewInoutUpdate')->name('admin-qc-review-inout');
        Route::post('edit-pickups', 'EditPickups')->name('admin-Edit-Pickups');
        Route::get('pickup-done-list', 'pickupDoneList')->name('pickup-done-list');
        Route::post('pickup-done-list', 'pickupDoneList')->name('QR-pickup-done-list');
        Route::post('logistics-stonedetails', 'logisticsStonedetails')->name('logistics-Stonedetails');
        Route::post('logistics-pickup-done', 'logisticsPickupDone')->name('logistics-pickup-done');
        Route::get('return-list', 'ReturnList')->name('return-list');
        Route::get('export-list', 'ExportList')->name('export-list');
        Route::post('cancel-export', 'CancelExport')->name('cancel-export');
        Route::get('downloadexport/{id}', 'DownloadExport')->name('download-export');
        Route::post('export-list-diamonds', 'ExportListdiamond')->name('admin.export-list-diamonds');
        Route::post('reff-order-export', 'reffOrderExport')->name('Reff-order-export');
        Route::post('update-export-status', 'UpdateExportStatus')->name('Update-Export-status');
    });

    Route::controller(MatchPairController::class)->group(function () {
        Route::get('match-pair', 'matchPair')->name('Admin-Match-Pair');
        Route::post('match-pair-search', 'matchPairSearch')->name('Admin-Match-Pair-Post');

    });

    Route::controller(AParcelGoodsController::class)->group(function () {
        Route::get('parcel-goods-list', 'ParcelGoodsList')->name('Admin.Parcel-Goods-List');
        Route::post('update-parcel-goods', 'UpdateParcelGoods')->name('Admin.Update-Parcel-Goods');
        Route::post('parcel_comment', 'parcelcomment')->name('Admin.Parcel-comment');
        Route::post('admin-send-mail-parcel', 'AdminSendMailParcel')->name('Admin.Send-Mail-Parcel');
    });

    Route::controller(TrackingController::class)->group(function () {
        Route::get('inout-list', 'inOutList')->name('in-Out-List');
        Route::post('inout-list', 'inOutList')->name('in-Out-List-Post');
        Route::post('tracking-status-update', 'trackingStatusUpdate')->name('admin-Tracking-Status-Update');
        Route::post('export-tracking', 'trackingExport')->name('admin-Tracking-Export');
        Route::post('admin-export-invoice-popup', 'ExportInvoicePopup')->name('Admin-Export-Invoice-Popup');
        Route::post('admin-export-invoice', 'ExportInvoice')->name('Admin-Export-Invoice');
        Route::post('admin-cancel-export-invoice', 'CancelExportInvoice')->name('Admin-Cancel-Export-Invoice');
    });


    Route::get('filecustom',[AFTPController::class, 'index'])->name('filecustom');

    Route::controller(AStaffController::class)->group(function () {
        Route::get('add-staff', 'AddStaff')->name('add.staff');
        Route::post('post-add-staff', 'PostAddStaff')->name('post.add.staff');
        Route::get('manage-staff', 'manageStaff')->name('manage.staff');
        Route::post('staff-delete/{id}', 'deleteStaff')->name('delete.staff');
        Route::get('staff-edit/{id}', 'staffEdit')->name('staff.edit');
        Route::post('post-staff-edit', 'PostStaffEdit')->name('post.staff.edit');
        Route::post('change-staff-password', 'ChangeStaffPassword')->name('chnage.staff.password');
        Route::get('staff-permission/{id}', 'StaffPermission')->name('staff.permission');
    });

    Route::controller(AssociateController::class)->group(function () {
        Route::get('add-associate','addAssociate')->name('add.associate');
        Route::post('post-add-associate','postAssociate')->name('post.add.associate');
        Route::get('manage-associate','ManageAssociate')->name('manage.associate');
        Route::get('associate-edit/{id}','EditAssociate')->name('edit.associate');
        Route::post('update-associate-detail','UpdateAssociateDetail')->name('update.associate.detail');
    });

    Route::controller(PricingController::class)->group(function () {
        Route::get('pricesetting','pricesetting')->name('pricesetting');
        Route::post('post-pricesetting','postPricesetting')->name('admin.post.pricesetting');
        Route::get('shippingpricesetting','shippingpricesetting')->name('shippingpricesetting');
        Route::post('shippingpricelist','shippingpricelist')->name('shippingpricelist');
        Route::post('save-shippingpricelist','saveShippingPriceList')->name('save-shippingpricelist');
        Route::get('price-markup-setting','priceMarkupsetting')->name('price-markup-setting');
        Route::post('post-price-markup-setting','postPriceMarkupsetting')->name('admin.post.price-markup-setting');
    });

    Route::controller(LogController::class)->group(function () {
        Route::get('login-history-customer','loginHistoryCustomer')->name('admin.log.loginhistorycustomer');
        Route::get('login-history-supplier','loginHistorySupplier')->name('admin.log.loginhistorysupplier');
        Route::get('login-history-staff','loginHistoryStaff')->name('admin.log.loginhistorystaff');
        Route::get('login-history-total','loginHistorytotal')->name('admin.log.loginhistorytotal');
        Route::post('login-history-total','loginHistorytotal')->name('admin.log.loginhistorytota');
        Route::get('api-history','ApiLog')->name('admin.log.apihistory');
        Route::get('api-history-detail/{id}','ApiLogDetail')->name('admin.log.apihistorydetail');
        Route::get('page-visits','PageVisits')->name('admin.Page-Visits');
        Route::get('search-history','Searchhistory')->name('admin.log.searchhistory');
        Route::post('search-history','Searchhistory');
    });

    Route::controller(AccountController::class)->group(function () {
        Route::get('purchase-list','purchaseList')->name('admin.purchase-list');
        Route::post('purchase-list','purchaseList')->name('admin.post-purchase-list');
        Route::get('purchase-bill','purchaseBill')->name('admin.purchase-bill');
        Route::post('purchase-bill','purchaseBill')->name('admin.post-purchase-bill');
        Route::post('delete-purchase-bill','deletePurchaseBill')->name('admin.delete-purchase-bill');
        Route::post('update-purchase-bill', 'UpdatePurchaseBill')->name('admin.update-purchase-bill');
        Route::get('purchase-bill-form','purchaseBillForm')->name('admin.purchase-bill-form');
        Route::post('post-purchase-bill-form', 'purchaseBillSave')->name('admin.purchase-bill-save');
        Route::get('sales-report','salesReport')->name('admin.sales-report');
        Route::post('sales-report','salesReport')->name('admin.Post-sales-report');
    });

    Route::controller(ReturnDiamondController::class)->group(function () {
        Route::get('return-diamond-list','returnDiamondList')->name('admin.Return-Diamond-List');
        Route::get('add-return-diamond','AddreturnDiamond')->name('admin.add-return-diamond');
        Route::post('search-return-diamond','SearchReturnDiamond')->name('admin.search-return-diamond');
        Route::post('add-Return-Diamond-post','AddReturnDiamondSave')->name('admin.add-return-diamond-post');
    });

    Route::controller(DebugController::class)->group(function () {
        Route::get('red-alert','redAlert')->name('admin.red-alert');
        Route::post('red-alert-post','redAlertPost')->name('admin.red-alert-post');
        Route::get('invalid-discount','invalidDiscount')->name('admin.invalid-discount');
        Route::post('invalid-discount-post','invalidDiscountPost')->name('admin.invalid-discount-post');
        Route::get('image-download-labgrown','ImageDownloadLabgrown')->name('admin.image-download-labgrown');
        Route::get('image-download-natural','ImageDownloadNatural')->name('admin.image-download-natural');
        Route::get('image-upload-s3-labgrown','ImageUploads3Labgrown')->name('admin.image-upload-s3-labgrown');
        Route::get('image-upload-s3-natural','ImageUploads3Natural')->name('admin.image-upload-s3-natural');
        Route::get('delete-expired-file', 'deleteExpiredFile')->name('delete-expired-file');
    });

    Route::get('permission',[PermissionController::class,'Permission'])->name('admin.permission');
    Route::post('permission-url',[PermissionController::class,'SetPermission'])->name('admin.url-permission');
    Route::post('assign-permission/{id}',[AStaffController::class,'AssignPermission'])->name('admin.assign-permission');

    Route::controller(AcareerController::class)->group(function () {
        Route::get('add-job','AddJob')->name('admin.add-job');
        Route::post('post-add-job','PostAddJob')->name('admin.post-add-job');
        Route::get('manage-job','ManageJob')->name('admin.manage-job');
        Route::get('job-edit/{id}','JobEdit')->name('admin.edit-job');
        Route::post('jobeditpost','JobEditPost')->name('admin.edit-postjob');
        Route::get('job-delete/{id}','JobDelete')->name('admin.delet-job');
    });

    Route::controller(ExtendedController::class)->group(function () {
        Route::get('currency-exchange','currencyExchange')->name('currency-exchange');
        Route::post('post-Currency-Exchange','PostCurrencyExchange')->name('Post-Currency-Exchange');
        Route::get('currency-exchange-save','currencyExchangeSave')->name('currency-exchange-Save');
        Route::get('daily-reporting','dailyReporting')->name('daily-reporting');
        Route::post('daily-reporting','dailyReporting')->name('daily-reporting-search');
        Route::post('daily-report-post','dailyReportPost')->name('daily-Report-Post');
        Route::post('update-daily-reporting','updateDailyReporting')->name('update-Daily-Reporting');
        Route::get('daily-check-list','dailyCheckList')->name('Daily-Check-List');
        Route::get('get-notification','GetNotification')->name('Admin.Get-Notification');

        Route::get('cart-mail','CartMail')->name('Cart-Mail');
        Route::get('send-messages','SendMessages')->name('Send-Message');
    });

});

// Route::get('cron',[TestController::class, 'index'])->name('cron');

Route::controller(AExcelController::class)->group(function () {
    Route::get('rare', 'index')->name('rare');
    Route::get('ritani', 'ritani')->name('ritani');
    Route::get('mejewel', 'mejewel')->name('mejewel');
    Route::get('mejewelNatural', 'mejewelNatural')->name('mejewelNatural');
});
Route::controller(AFTPController::class)->group(function () {
    Route::get('cron','index')->name('cron');
    Route::get('arush','arush')->name('arush');
    Route::get('brahma_diamonds','brahma_diamonds')->name('brahma_diamonds');
    Route::get('rajlaxmidiamond','rajlaxmidiamond')->name('rajlaxmidiamond');
    Route::get('belgiumdia','belgiumdia')->name('belgiumdia');
    Route::get('classicgrown','classicgrown')->name('classicgrown');
    Route::get('cultrdGrowersLlp','cultrdGrowersLlp')->name('cultrdGrowersLlp');
    Route::get('elementsintlinc','elementsintlinc')->name('elementsintlinc');
    Route::get('JasDiamondsInc','JasDiamondsInc')->name('JasDiamondsInc');
    Route::get('hrimpex','hrimpex')->name('hrimpex');
    Route::get('paldiam','paldiam')->name('paldiam');
    Route::get('merayadiamond','merayadiamond')->name('merayadiamond');
    Route::get('neolabdiamonds','neolabdiamonds')->name('neolabdiamonds');
    Route::get('paladiya_diamond','paladiya_diamond')->name('paladiya_diamond');
    Route::get('paralleldiamonds','paralleldiamonds')->name('paralleldiamonds');
    Route::get('purelabdiamond','purelabdiamond')->name('purelabdiamond');
    Route::get('supremeexports','supremeexports')->name('supremeexports');
    Route::get('smilingRock','smilingRock')->name('smilingRock');
    Route::get('snjdiam','snjdiam')->name('snjdiam');
    Route::get('narolabrothers','narolabrothers')->name('narolabrothers');
    Route::get('shivamjewels','shivamjewels')->name('shivamjewels');
    Route::get('blueearth','BlueEarth')->name('blueearth');
    Route::get('ecobrilliance','EcoBrilliance')->name('ecobrilliance');
    Route::get('jodhani','Jodhani')->name('jodhani');
    Route::get('akarshexports','AkarshExports')->name('akarshexports');
    Route::get('diamspark','Diamspark')->name('diamspark');
    Route::get('ankitgems','AnkitGems')->name('ankitgems');
    Route::get('parishidiamond','ParishiDiamond')->name('parishidiamond');
    Route::get('shyamcorporation','ShyamCorporation')->name('shyamcorporation');
    Route::get('excelsuccess','ExcelSuccess')->name('excelsuccess');
    Route::get('cdinesh','CDinesh')->name('cdinesh');
    Route::get('diamantirebv','DiamantireBv')->name('diamantirebv');
    Route::get('dmdiamond','DMDiamond')->name('dmdiamond');
    Route::get('shinestone','ShineStone')->name('shinestone');
    Route::get('glowstar','GlowStar')->name('glowstar');
    Route::get('blumoon','Blumoon')->name('blumoon');
    Route::get('africanstar','AfricanStar')->name('africanstar');
    Route::get('easyimpex','EasyImpex')->name('easyimpex');
    Route::get('osam','Osam')->name('osam');
    Route::get('naturalbelgiumdia','NaturalBelgiumdia')->name('naturalbelgiumdia');
    Route::get('vidhansh','Vidhansh')->name('vidhansh');
});

Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('config:cache');
    $exitCode = Artisan::call('route:cache');
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('view:clear');
    $exitCode = Artisan::call('optimize');
    return 'DONE'; //Return anything
});
