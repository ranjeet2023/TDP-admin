<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserApiController;
use App\Http\Controllers\API\SupplierApiController;
use App\Http\Controllers\API\WhitelabelApiController;
use App\Http\Controllers\API\ShopiApiController;
use App\Http\Controllers\API\JewelleryController;
use App\Http\Controllers\API\RingBuilderController;
use App\Http\Controllers\API\RingImportController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('api_login',[UserApiController::class,'Login']);
Route::post('api_registration',[UserApiController::class,'Registration']);
Route::post('api_contact_us',[UserApiController::class,'ContactUs']);
Route::post('api_ask_question',[UserApiController::class,'AskQuestion']);
Route::post('api_getin_touch',[UserApiController::class,'GetInTouch']);
Route::post('api_forget_password',[UserApiController::class,'forgetPassword']);
Route::post('api_version_code',[UserApiController::class,'Version']);
Route::post('api_guest_login_status',[UserApiController::class,'GuestLogin']);
Route::post('api_get_diamond', [UserApiController::class,'GetDiamond']);
Route::post('api_user_forget_password', [UserApiController::class, 'ForgotPassword']);
//Customer Route
Route::middleware('auth:api')->group(function(){
    Route::post('api_lab_search_parameters', [UserApiController::class,'LabParameters']);
    Route::post('api_search_natural_parameters', [UserApiController::class,'NaturalParameters']);
    Route::post('api_lab_search_diamond', [UserApiController::class,'LabDiamondSearch']);
    Route::post('api_search_natural_diamond', [UserApiController::class,'NaturalDiamondSearch']);
    Route::group(['middleware' => 'App\Http\Middleware\CustomerApiController'], function()
    {
        Route::post('api_user_profile', [UserApiController::class,'UserProfile']);
        Route::post('api_user_profile_update', [UserApiController::class,'UserProfileUpdate']);
        Route::post('api_user_company-details',[UserApiController::class, 'CompanyDetails'])->name('user-comapny-details');
        Route::post('api_user_update-company-details',[UserApiController::class, 'UpdateCompanyDetails'])->name('user-update-comapny-details');
        Route::post('api_diamond_detail', [UserApiController::class,'DiamondDetail']);
        Route::post('api_diamond_order', [UserApiController::class,'DiamondOrder']);
        Route::post('api_hold_diamond', [UserApiController::class,'HoldDiamond']);
        Route::post('api_holdlist', [UserApiController::class,'HoldList']);
        Route::post('api_wishlist_diamond', [UserApiController::class,'WishlistDiamond']);
        Route::post('api_wishlist', [UserApiController::class,'MyWishlist']);
        Route::post('api_confirm_diamond',[UserApiController::class,'ConfirmDiamond']);
        Route::post('api_confirm_diamond_list',[UserApiController::class,'ConfirmDiamondList']);
        Route::post('api_remove_favorite', [UserApiController::class,'RemoveDaimondWishlist']);
        Route::post('api_user_logout',[UserApiController::class,'Userlogout']);
        Route::post('api_user_change_password',[UserApiController::class,'UserChangePassword']);
        Route::post('api_recent_view',[UserApiController::class,'RecentView']);
        Route::post('api_clear_recent_view',[UserApiController::class,'ClearRecentView']);
        Route::post('api_customer_invoice_list',[UserApiController::class,'InvoiceList']);
        Route::post('api_customer_add_to_cart',[UserApiController::class,'AddToCart']);
        Route::post('api_customer_cart_list',[UserApiController::class,'CartList']);
        Route::post('api_customer_remove_cart',[UserApiController::class,'RemoveCart']);
        Route::post('api_customer_notification',[UserApiController::class,'Customernotification']);
    });
});
Route::post('api_send_notification',[UserApiController::class,'sendWebNotification']);


//Supplier Route
Route::middleware('auth:api')->group(function(){
    Route::group(['middleware' => 'App\Http\Middleware\SupplierApiController'], function()
    {
        Route::post('api_supplier_dashboard', [SupplierApiController::class,'SupplierDashboard']);
        Route::post('api_supplier_upload_history',[SupplierApiController::class,'SupplieUploadHistory']);
        Route::post('api_invalid_daimond',[SupplierApiController::class,'InvalidDaimond']);
        Route::post('api_search_dimond',[SupplierApiController::class,'SearchDiamondList']);
        Route::post('api_orders',[SupplierApiController::class,'Orders']);
        Route::post('api_hold_diamond_list',[SupplierApiController::class,'HoldDiamond']);
        Route::post('api_reject_aprove',[SupplierApiController::class,'AproveReject']);
        Route::post('api_invoice_list',[SupplierApiController::class,'InvoiceList']);
        Route::post('api_supplier_profile', [SupplierApiController::class,'SupplierProfile']);
        Route::post('api_supplier_profile_update', [SupplierApiController::class,'SupplierProfileUpdate']);
        Route::post('api_supplier_change_password',[SupplierApiController::class,'SupplerChangePassword']);
        Route::post('api_supplier_logout',[SupplierApiController::class,'Supplierlogout']);
        Route::post('api_supplier_notification',[SupplierApiController::class,'Suppliernotification']);
    });
});


// whitelab

//Shopify
Route::post('sh_get_config', [ShopiApiController::class,'GetConfig']);
Route::post('sh_search_diamond', [ShopiApiController::class,'SearchDiamond']);
Route::middleware('auth:api')->group(function(){
    Route::group(['middleware' => 'App\Http\Middleware\WhitelabelApiMiddleware'], function()
    {
        // Route::post('get_parameter', [ShopiApiController::class,'GetParameter']);
    });
});

// jewellery produt
Route::post('token-generate',[JewelleryController::class, 'tokenGenerate'])->name('token-generate');
Route::get('product',[JewelleryController::class, 'Product'])->name('product');
Route::get('semi-product',[JewelleryController::class, 'SemiProduct'])->name('semi-product');
Route::middleware('auth:api')->group(function(){
    Route::group(['middleware' => 'App\Http\Middleware\JewelleryApiMiddleware'], function()
    {
        Route::post('getproduct',[JewelleryController::class, 'GetSemiproductData'])->name('get-semi-product');
        Route::post('get-fine-product',[JewelleryController::class, 'GetFineproductData'])->name('getproduct');
    });
});


// Ring builder
Route::post('ringtoken-generate',[RingBuilderController::class, 'tokenGenerate']);
Route::middleware('auth:api')->group(function(){
    Route::post('get_config_ring', [RingBuilderController::class,'GetConfigRing']);
    Route::post('getring',[RingBuilderController::class, 'GetAllRing']);
    Route::post('add_ring',[RingBuilderController::class, 'AddRing'])->name('addring');
});

// Ring Import

Route::post('ringimport-token',[RingImportController::class, 'Genratetoken']);
Route::middleware('auth:api')->group(function(){
    Route::post('get-ring-data',[RingImportController::class, 'GetImportRing']);
});

