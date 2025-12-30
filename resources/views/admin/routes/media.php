<?php

use App\Constants\Constant;
use App\Http\Controllers\Admin\AudioController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\PortfolioController;
use App\Http\Controllers\Admin\VideoController;
use Illuminate\Support\Facades\Route;
## Media Management Dropdown Related Routes
Route::middleware('App\Http\Middleware\CheckPermission:PAGE_MANAGEMENT_VIEW')->group(function () {
    ## Banner Manager Routes
    Route::controller(BannerController::class)->middleware(Constant::GET_SECTION . Constant::BANNER_MANAGEMENT)->group(function () {
        Route::any('/banner-list', 'index')->name('admin.banner.index')->middleware('App\Http\Middleware\CheckPermission:BANNER_MANGER_LIST');
        Route::get('/banner-list/status/{id}/{value}', 'status')->middleware('App\Http\Middleware\CheckPermission:BANNER_MANAGER_CHANGE_STATUS');
        Route::get('/banner-list/delete/{id}', 'delete')->middleware('App\Http\Middleware\CheckPermission:BANNER_MANAGER_DELETE');
        Route::get('/banner-list/add', 'add')->name('admin.banner.add')->middleware('App\Http\Middleware\CheckPermission:BANNER_MANAGER_ADD');
        Route::post('/banner-list/save', 'save')->name('admin.banner.save')->middleware('App\Http\Middleware\CheckPermission:BANNER_MANAGER_ADD');
        Route::get('/banner-list/edit/{id}', 'edit')->name('admin.banner.edit')->middleware('App\Http\Middleware\CheckPermission:BANNER_MANAGER_EDIT');
        Route::post('/banner-list/update/{id}', 'update')->name('admin.banner.update')->middleware('App\Http\Middleware\CheckPermission:BANNER_MANAGER_EDIT');
    });

    ##Portfolio Routes
    Route::controller(PortfolioController::class)->middleware(Constant::GET_SECTION . Constant::PORTFOLIO_MANAGEMENT)->group(function () {
        Route::any('/portfolio-list',  'index')->name('admin.portfolio.index')->middleware('App\Http\Middleware\CheckPermission:PORTFOLIO_MANGER_LIST');
        Route::get('/portfolio-list/status/{id}/{value}',  'status')->middleware('App\Http\Middleware\CheckPermission:PORTFOLIO_MANAGER_CHANGE_STATUS');
        Route::get('/portfolio-list/delete/{id}',  'delete')->middleware('App\Http\Middleware\CheckPermission:PORTFOLIO_MANAGER_DELETE');
        Route::get('/portfolio-list/add',  'add')->name('admin.portfolio.add')->middleware('App\Http\Middleware\CheckPermission:PORTFOLIO_MANAGER_ADD');
        Route::post('/portfolio-list/save',  'save')->name('admin.portfolio.save')->middleware('App\Http\Middleware\CheckPermission:PORTFOLIO_MANAGER_ADD');
        Route::get('/portfolio-list/edit/{id}',  'edit')->name('admin.portfolio.edit')->middleware('App\Http\Middleware\CheckPermission:PORTFOLIO_MANAGER_EDIT');
        Route::post('/portfolio-list/update/{id}',  'update')->name('admin.portfolio.update')->middleware('App\Http\Middleware\CheckPermission:PORTFOLIO_MANAGER_EDIT');
    });

    ## Videos Routes
    Route::controller(VideoController::class)->middleware(Constant::GET_SECTION . Constant::VIDEO_MANAGEMENT)->group(function () {
        Route::any('/video-list', 'index')->name('admin.video.index')->middleware('App\Http\Middleware\CheckPermission:VIDEOS_MANAGEMENT_LIST');
        Route::get('/video-list/status/{id}/{value}', 'status')->middleware('App\Http\Middleware\CheckPermission:VIDEOS_MANAGEMENT_CHANGE_STATUS');
        Route::get('/video-list/add', 'add')->name('admin.video.add')->middleware('App\Http\Middleware\CheckPermission:VIDEOS_MANAGEMENT_ADD');
        Route::post('/video-list/save', 'save')->name('admin.video.save')->middleware('App\Http\Middleware\CheckPermission:VIDEOS_MANAGEMENT_ADD');
        Route::get('/video-list/edit/{id}', 'edit')->name('admin.video.edit')->middleware('App\Http\Middleware\CheckPermission:VIDEOS_MANAGEMENT_EDIT');
        Route::post('/video-list/update/{id}', 'update')->name('admin.video.update')->middleware('App\Http\Middleware\CheckPermission:VIDEOS_MANAGEMENT_EDIT');
        Route::post('/video/updateToken', 'updateToken')->name('admin.updateTokenVideo');
    });

    ## Audio Routes
    Route::controller(AudioController::class)->middleware(Constant::GET_SECTION . Constant::AUDIO_MANAGEMENT)->group(function () {
        Route::any('/audio-list', 'index')->name('admin.audio.index')->middleware('App\Http\Middleware\CheckPermission:AUDIOS_MANAGEMENT_LIST');
        Route::get('/audio-list/status/{id}/{value}', 'status')->middleware('App\Http\Middleware\CheckPermission:AUDIOS_MANAGEMENT_CHANGE_STATUS');
        Route::get('/audio-list/add', 'add')->name('admin.audio.add')->middleware('App\Http\Middleware\CheckPermission:AUDIOS_MANAGEMENT_ADD');
        Route::post('/audio-list/save', 'save')->name('admin.audio.save')->middleware('App\Http\Middleware\CheckPermission:AUDIOS_MANAGEMENT_ADD');
        Route::get('/audio-list/edit/{id}', 'edit')->name('admin.audio.edit')->middleware('App\Http\Middleware\CheckPermission:AUDIOS_MANAGEMENT_EDIT');
        Route::post('/audio-list/update/{id}', 'update')->name('admin.audio.update')->middleware('App\Http\Middleware\CheckPermission:AUDIOS_MANAGEMENT_EDIT');
        Route::post('/audio/updateToken', 'updateToken')->name('admin.updateTokenAudio');
    });

    ## Gallery Manager Routes
    Route::controller(GalleryController::class)->middleware(Constant::GET_SECTION . Constant::GALLERY_MANAGEMENT)->group(function () {
        Route::any('/gallery-list', 'index')->name('admin.gallery.index')->middleware('App\Http\Middleware\CheckPermission:GALLERY_MANGER_LIST');
        Route::get('/gallery-list/status/{id}/{value}', 'status')->middleware('App\Http\Middleware\CheckPermission:GALLERY_MANAGER_CHANGE_STATUS');
        Route::get('/gallery-list/delete/{id}', 'delete')->middleware('App\Http\Middleware\CheckPermission:GALLERY_MANAGER_DELETE');
        Route::get('/gallery-list/add', 'add')->name('admin.gallery.add')->middleware('App\Http\Middleware\CheckPermission:GALLERY_MANAGER_ADD');
        Route::post('/gallery-list/save', 'save')->name('admin.gallery.save')->middleware('App\Http\Middleware\CheckPermission:GALLERY_MANAGER_ADD');
        Route::get('/gallery-list/edit/{id}', 'edit')->name('admin.gallery.edit')->middleware('App\Http\Middleware\CheckPermission:GALLERY_MANAGER_EDIT');
        Route::post('/gallery-list/update/{id}', 'update')->name('admin.gallery.update')->middleware('App\Http\Middleware\CheckPermission:GALLERY__MANAGER_EDIT');
        Route::get('/gallery-list/mediaImage', 'galleryImage')->name('admin.gallery.media.images');

    });
});
