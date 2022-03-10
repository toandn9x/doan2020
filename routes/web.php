<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'App\Http\Controllers\Controller@index')->name('index');
Route::get('dang-nhap', 'App\Http\Controllers\Controller@getLogin')->name('g_login');
Route::post('dang-nhap','App\Http\Controllers\Controller@postLogin')->name('p_login');
Route::post('dang-ky','App\Http\Controllers\Controller@postRegister')->name('p_register');
Route::get('dang-xuat','App\Http\Controllers\Controller@logOut')->name('logout');
Route::get('quen-mat-khau', 'App\Http\Controllers\Controller@getResetPass')->name('g_reset_pass');
Route::post('quen-mat-khau','App\Http\Controllers\Controller@postResetPass')->name('p_reset_pass');
Route::get('doi-mat-khau', 'App\Http\Controllers\Controller@getRecoverPass')->name('g_recover_pass');
Route::post('doi-mat-khau','App\Http\Controllers\Controller@postRecoverPass')->name('p_recover_pass');
// Chi tiết sản phẩm
Route::get('san-pham/{id}','App\Http\Controllers\Controller@product')->name('v_product');
Route::get('san-pham/chi-tiet-san-pham/{id}','App\Http\Controllers\Controller@productDetail')->name('product_detail');

// Giỏ hàng //
Route::get('gio-hang', 'App\Http\Controllers\CartController@viewCart')->name('v_cart');
Route::post('gio-hang', 'App\Http\Controllers\CartController@addCart')->name('add_cart');
Route::get('gio-hang/xoa/{id}', 'App\Http\Controllers\CartController@deleteCart')->name('delete_cart');
Route::get('gio-hang/xoa', 'App\Http\Controllers\CartController@deleteAll')->name('delete_all');
Route::post('order', 'App\Http\Controllers\CartController@order');
//ajax update cart
Route::post('gio-hang/update/{rowid}', 'App\Http\Controllers\CartController@updateCart')->name('update_cart');
Route::get('return', 'App\Http\Controllers\CartController@return');
//end
//send email
Route::get('emal-to-owner', 'EmailController@sendEMail');
//
Route::get('tim-kiem-sp', 'App\Http\Controllers\Controller@search')->name('search');
// lich su mua hang
Route::get('lich-su-mua-hang', 'App\Http\Controllers\Controller@orderHistory')->name('order_history');
Route::get('lich-su-mua-hang/chi-tiet/{id}', 'App\Http\Controllers\Controller@orderHistoryDetail')->name('order_history_detail');
// đk nhận tt khuyến mãi
Route::post('dk-nhan-tin', 'App\Http\Controllers\Controller@postGetDiscount')->name('get_discount');
// hiển thị các sp giảm giá
Route::get('khuyen-mai', 'App\Http\Controllers\Controller@getSale')->name('get_sale');
// hiển thị thông tin liên hệ
Route::get('lien-he', 'App\Http\Controllers\Controller@getContact')->name('get_contact');
//////////////////////////////////////////////////////////
// router xử lý phần admin //
/////////////////////////////////////////////////////////
Route::group(['prefix' => 'admin', 'middleware' => 'checklogin'], function() {
    Route::get('index', 'App\Http\Controllers\AdminController@adminIndex')->name('admin_index');
    Route::get('ql-tai-khoan', 'App\Http\Controllers\AdminController@getUser')->name('g_user');
    Route::get('ql-tai-khoan/them', 'App\Http\Controllers\AdminController@getAddUser')->name('g_add_user');
    Route::post('ql-tai-khoan/them', 'App\Http\Controllers\AdminController@postAddUser')->name('p_add_user');
    Route::get('ql-tai-khoan/timkiem', 'App\Http\Controllers\AdminController@searchUser')->name('g_search_user');
    Route::get('ql-tai-khoan/sua/{id}', 'App\Http\Controllers\AdminController@getEditUser')->name('g_edit_user');
    Route::post('ql-tai-khoan/sua', 'App\Http\Controllers\AdminController@postEditUser')->name('p_edit_user');
    Route::post('ql-tai-khoan/xoa', 'App\Http\Controllers\AdminController@postDeleteUser')->name('p_delete_user');
    Route::get('ql-tai-khoan/export', 'App\Http\Controllers\AdminController@exportUser')->name('g_export_user');
    // End
    // QL danh mục sản phẩm
    Route::get('ql-danh-muc', 'App\Http\Controllers\AdminController@getCategory')->name('g_category');
    Route::get('ql-danh-muc/them', 'App\Http\Controllers\AdminController@getAddCategory')->name('g_add_category');
    Route::post('ql-danh-muc/them', 'App\Http\Controllers\AdminController@postAddCategory')->name('p_add_category');
    Route::get('ql-danh-muc/sua/{id}', 'App\Http\Controllers\AdminController@getEditCategory')->name('g_edit_category');
    Route::post('ql-danh-muc/sua', 'App\Http\Controllers\AdminController@postEditCategory')->name('p_edit_category');
    Route::get('ql-danh-muc/timkiem', 'App\Http\Controllers\AdminController@searchCategory')->name('g_search_category');
    Route::post('ql-danh-muc/xoa', 'App\Http\Controllers\AdminController@postDeleteCategory')->name('p_delete_category');
    Route::get('ql-danh-muc/export', 'App\Http\Controllers\AdminController@exportCategory')->name('g_export_category');
    // End
    // QL sản phẩm
    Route::get('ql-san-pham', 'App\Http\Controllers\AdminController@getProduct')->name('g_product');
    Route::get('ql-san-pham/them', 'App\Http\Controllers\AdminController@getAddProduct')->name('g_add_product');
    Route::post('ql-san-pham/them', 'App\Http\Controllers\AdminController@postAddProduct')->name('p_add_product');
    Route::get('ql-san-pham/sua/{id}', 'App\Http\Controllers\AdminController@getEditProduct')->name('g_edit_product');
    Route::post('ql-san-pham/sua', 'App\Http\Controllers\AdminController@postEditProduct')->name('p_edit_product');
    Route::get('ql-san-pham/timkiem', 'App\Http\Controllers\AdminController@searchProduct')->name('g_search_product');
    Route::post('ql-san-pham/xoa', 'App\Http\Controllers\AdminController@postDeleteProduct')->name('p_delete_product');
    Route::get('ql-san-pham/export', 'App\Http\Controllers\AdminController@exportProduct')->name('g_export_product');
    // End
    // QL đơn hàng
    Route::get('ql-don-hang', 'App\Http\Controllers\AdminController@getBill')->name('g_bill');
    Route::get('ql-don-hang/timkiem', 'App\Http\Controllers\AdminController@searchBill')->name('g_search_bill');
    Route::get('ql-don-hang/xoa', 'App\Http\Controllers\AdminController@postDeleteBill')->name('p_delete_bill');
    Route::get('ql-don-hang/export', 'App\Http\Controllers\AdminController@exportBill')->name('g_export_bill');
    // Show chi tiết đơn hàng
    Route::post('ql-don-hang/chtiet', 'App\Http\Controllers\AdminController@showBill')->name('p_show_bill');
    // Update trạng thái
    Route::post('ql-don-hang/update', 'App\Http\Controllers\AdminController@ajaxUpdateStatus')->name('p_update');
    //End
    ////////////////
    // QL khách hàng
    Route::get('ql-khach-hang', 'App\Http\Controllers\AdminController@getCustomer')->name('g_customer');
    // Route::get('ql-khach-hang/them', 'App\Http\Controllers\AdminController@getAddCustomer')->name('g_add_customer');
    // Route::post('ql-khach-hang/them', 'App\Http\Controllers\AdminController@postAddCustomer')->name('p_add_customer');
    // Route::get('ql-khach-hang/sua/{id}', 'App\Http\Controllers\AdminController@getEditCustomer')->name('g_edit_customer');
    // Route::post('ql-khach-hang/sua', 'App\Http\Controllers\AdminController@postEditCustomer')->name('p_edit_customer');
    Route::get('ql-khach-hang/timkiem', 'App\Http\Controllers\AdminController@searchCustomer')->name('g_search_customer');
    Route::get('ql-khach-hang/xoa', 'App\Http\Controllers\AdminController@postDeleteCustomer')->name('p_delete_customer');
    Route::get('ql-khach-hang/export', 'App\Http\Controllers\AdminController@exportCustomer')->name('g_export_customer');

    // Thống kê
    Route::get('thong-ke', 'App\Http\Controllers\AdminController@statistical')->name('g_statistical');

    // QL thông tin liên hệ
    Route::get('lien-he', 'App\Http\Controllers\AdminController@contact')->name('g_contact');
    Route::post('lien-he', 'App\Http\Controllers\AdminController@postContact')->name('p_contact');
    // QL khách hàng nhận km
    Route::get('ds-khuyen-mai', 'App\Http\Controllers\AdminController@discount')->name('g_discount');
    Route::get('ds-khuyen-mai/timkiem', 'App\Http\Controllers\AdminController@searchDiscount')->name('g_search_discount');
    Route::post('ds-khuyen-mai/xoa', 'App\Http\Controllers\AdminController@deleteDiscount')->name('p_delete_discount');
    //QL slider
    Route::get('ql-slider', 'App\Http\Controllers\AdminController@slider')->name('g_slider');
    Route::get('ql-slider/them', 'App\Http\Controllers\AdminController@getAddSlide')->name('g_add_slide');
    Route::post('ql-slider/them', 'App\Http\Controllers\AdminController@postAddSlide')->name('p_add_slide');
    Route::get('ql-slider/sua/{id}', 'App\Http\Controllers\AdminController@getEditSlide')->name('g_edit_slide');
    Route::post('ql-slider/sua', 'App\Http\Controllers\AdminController@postEditSlide')->name('p_edit_slide');
    Route::post('ql-slider/xoa', 'App\Http\Controllers\AdminController@postDeleteSlide')->name('p_delete_slide');
});
