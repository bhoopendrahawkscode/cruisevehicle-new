<?php

#Page Management DropDown Related Routes

use App\Constants\Constant;
use App\Http\Controllers\Admin\CMSController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\TestimonialsController;
use App\Http\Controllers\Admin\TransactionController;
use Illuminate\Support\Facades\Route;

Route::middleware('App\Http\Middleware\CheckPermission:CMS_MANGER_LIST')->group(function () {
    ### CMS Management Routes
    Route::middleware(Constant::GET_SECTION . Constant::CMS_MANAGEMENT)->group(function () {

        Route::any('/cms-list', [CMSController::class, 'index'])->name('admin.cms.index')->middleware('App\Http\Middleware\CheckPermission:CMS_MANGER_LIST');
        Route::get('/cms-list/edit/{id}', [CMSController::class, 'edit'])->name('admin.cms.edit')->middleware('App\Http\Middleware\CheckPermission:CMS_MANGER_EDIT');
        Route::post('/cms-list/update/{id}', [CMSController::class, 'update'])->name('admin.cms.update')->middleware('App\Http\Middleware\CheckPermission:CMS_MANGER_EDIT');
    });
    ## Coupon Management Routes
    Route::controller(CouponController::class)->middleware(Constant::GET_SECTION . Constant::COUPON_MANAGEMENT)->prefix('coupons')->group(function () {
        Route::get('list', 'couponList')->name('admin.coupons.list');
        Route::get('add', 'addCoupon')->name('admin.coupons.add');
        Route::get('edit/{coupon}', 'edit')->name('admin.coupons.edit');
        Route::get('delete/{coupon}', 'delete')->name('admin.coupon.delete');
        Route::post('save', 'saveCoupon')->name('admin.coupons.save');
        Route::post('update/{coupon}', 'updateCoupon')->name('admin.coupons.update');
        Route::get('status/{id}/{value}', 'updateStatus')->name('admin.coupons.update.status');
        Route::get('details/{coupon}', 'couponDetails')->name('admin.coupons.details');
        Route::post('validate_coupon_code', 'validateCouponCode')->name('admin.coupons.validateCouponCode');
        Route::post('validate_coupon_name', 'validateCouponName')->name('admin.coupons.validateCouponName');
    });

    ## Testimonials Manager Routes
    Route::controller(TestimonialsController::class)->middleware(Constant::GET_SECTION . Constant::TESTIMONIAL_MANAGEMENT)->group(function () {
        Route::any('/testimonials-list', 'index')->name('admin.testimonials.index')->middleware('App\Http\Middleware\CheckPermission:TESTIMONIALS_MANGER_LIST');
        Route::get('/testimonial-list/status/{id}/{value}', 'status')->middleware('App\Http\Middleware\CheckPermission:TESTIMONIALS_MANAGER_CHANGE_STATUS');
        Route::get('/testimonial-list/delete/{id}', 'delete')->middleware('App\Http\Middleware\CheckPermission:TESTIMONIALS_MANAGER_DELETE');
        Route::get('/testimonials-list/add', 'add')->name('admin.testimonials.add')->middleware('App\Http\Middleware\CheckPermission:TESTIMONIALS_MANAGER_ADD');
        Route::post('/testimonials-list/save', 'save')->name('admin.testimonials.save')->middleware('App\Http\Middleware\CheckPermission:TESTIMONIALS_MANAGER_ADD');
        Route::get('/testimonial-list/edit/{id}', 'edit')->name('admin.testimonials.edit')->middleware('App\Http\Middleware\CheckPermission:TESTIMONIALS_MANAGER_EDIT');
        Route::post('/testimonial-list/update/{id}', 'update')->name('admin.testimonials.update')->middleware('App\Http\Middleware\CheckPermission:TESTIMONIALS_MANAGER_EDIT');
    });

    ## Transaction Management Routes
    Route::controller(TransactionController::class)->group(function () {
        Route::any('/transaction-list', 'index')->name('admin.transaction.index')->middleware('App\Http\Middleware\CheckPermission:TRANSACTIONS_MANAGEMENT_LIST');
        Route::get('/transaction-list/view/{id}', 'view')->name('admin.transaction.view')->middleware('App\Http\Middleware\CheckPermission:TRANSACTION_MANAGEMENT_VIEW');
    });

    ## Contact Management Routes
    Route::controller(ContactController::class)->group(function () {
        Route::any('/contact-list', 'contactList')->name('admin.contactList')->middleware('App\Http\Middleware\CheckPermission:CONTACT_LIST');
        Route::get('/contact-view/{id}', 'contactView')->middleware('App\Http\Middleware\CheckPermission:CONTACT_VIEW');
        Route::post('/support-send', 'sendReply')->name('admin.contact.sendReply')->middleware('App\Http\Middleware\CheckPermission:CONTACT_VIEW');
    });
});
