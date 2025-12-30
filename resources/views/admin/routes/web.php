<?php

use App\Constants\Constant;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\EmailTemplateController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Middleware\ApiDataLogger;
use App\Http\Middleware\Localization;
use App\Http\Middleware\AuthAdmin;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\MediaManagerController;
use App\Http\Controllers\Admin\MetaManagementController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ZipModuleController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SubAdminActivityLogController;
use App\Http\Controllers\Admin\SubAdminController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\ServiceProviderController;
use App\Http\Controllers\Admin\TransmissionTypeController;
use App\Http\Controllers\Admin\ModelController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\FuelTypeController;
use App\Http\Controllers\Admin\EngineCapacityController;
use App\Http\Controllers\Admin\InsuranceCompanyController;
use App\Http\Controllers\Admin\InsuranceCoverPeriodController;
use App\Http\Controllers\Admin\InsuranceCoverTypeController;
use App\Http\Controllers\Admin\InsuranceQuoteConfirmationController;
use App\Http\Controllers\Admin\InsuranceQuoteController;
use App\Http\Controllers\Admin\InsuranceRenewalController;
use App\Jobs\CreateMigrationJob;
use App\Services\UploadHandler;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\View;

Route::match(['get', 'post', 'put', 'patch', 'delete', 'options', 'head'], '/fileUploader', function () {
	new UploadHandler();
	die;
});



Route::get('/cache-clear', function () {
	Artisan::call('route:clear');
	Artisan::call('view:clear');
	Artisan::call('config:cache');
	Artisan::call('cache:clear');
	Artisan::call('optimize:clear');

	return '<h1>Cleared!</h1>';
});

Route::get('/createMigration', function () {
	CreateMigrationJob::dispatch();
});


Route::get('/linkstorage', function () {
	Artisan::call('storage:link');
});

Route::get('/site-up', function () {
	Artisan::call('up');
});


    Route::post('/tags/suggest', 'App\Http\Controllers\HomeController@suggestTags')->name('tags.suggest');
    Route::get('/get-models/{brand_id}', 'App\Http\Controllers\HomeController@getModels')->name('get.models');
    Route::post('/paginationLimitChange', 'App\Http\Controllers\HomeController@paginationLimitChange')->name('paginationLimitChange');
    Route::get('/', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::prefix('admin')->middleware([ApiDataLogger::class, Localization::class])->group(function () {

	Route::get('/zip-modules', [ZipModuleController::class, 'zipModuleList'])->name('admin.zipModuleList');
	Route::post('/create-zip-module', [ZipModuleController::class, 'createZipModule'])->name('admin.zip.create');
	Route::get('/zipmodule/status/{id}/{value}', [ZipModuleController::class, 'status']);


	## Admin Routes Before Authentication
	Route::group(array('namespace' => 'Admin'), function () {
		Route::get('/', [AdminLoginController::class, 'showLoginForm'])->name('login');
		Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
		Route::post('/admin-login', [AdminLoginController::class, 'adminLogin'])->name('admin-login');
		Route::get('/forgot-password', [AdminLoginController::class, 'forgotPassword'])->name('forgotPassword');
		Route::post('/send-password', [AdminLoginController::class, 'sendPassword'])->name('sendPassword');
		Route::get('/reset-new-password/{validate_string}', [AdminLoginController::class, 'resetPassword'])->name('resetPassword');
		Route::post('/reset-new-password', [AdminLoginController::class, 'saveResetPassword'])->name('saveResetPassword');
		Route::get('/mobile-otp', [AdminLoginController::class, 'mobileOtp'])->name('mobileOtp');
		Route::post('/send-otp', [AdminLoginController::class, 'sendMobileOtp'])->name('sendMobileOtp');
		Route::get('/check-mobile-otp', [AdminLoginController::class, 'checkMobileOtp'])->name('checkMobileOtp');
		Route::get('/resend-otp/{country_code}/{mobile_no}', [AdminLoginController::class, 'resendMobileOtp'])->name('resendMobileOtp');
		Route::post('/verify-otp', [AdminLoginController::class, 'verifyMobileOtp'])->name('verifyMobileOtp');
	});

	Route::get('/error403', function () {
		return View::make('admin.403');
	})->name('error403');

	Route::middleware([AuthAdmin::class])->group(function () {
		## Admin Routes After Authentication
		Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

		Route::controller(SubAdminController::class)->group(function () {
			Route::get('/logout-admin',  'logout')->name('admin.logout');
			Route::get('/edit-profile',  'showEditProfile')->name('edit-profile');
			Route::post('/update-profile',  'UpdateProfile')->name('update-profile');
			Route::get('/change-password',  'changePassword')->name('change-password');
			Route::post('/update-password',  'updatePassword')->name('update-password');
			Route::get('/subadmin-login/{uid}',  'subadminLogin')->name('admin.subadmin-login');
		});

		##Role Routes
		Route::controller(RoleController::class)->middleware(Constant::GET_SECTION . Constant::ROLE)->prefix('role')->group(function () {
			Route::get('list', 'roleList')->name('admin.role.list')->middleware('App\Http\Middleware\CheckPermission:ROLE_LIST');
			Route::get('add', 'addRole')->name('admin.role.add');
			Route::get('edit/{role}', 'editRole')->name('admin.role.edit')->middleware('App\Http\Middleware\CheckPermission:ROLE_EDIT');
			Route::post('save', 'saveRole')->name('admin.role.save');
			Route::post('validate_role_name', 'validateRoleName')->name('admin.role.validate_name');
			Route::post('update/{role}', 'updateRole')->name('admin.role.update');
		});

		## Sub-Admin Routes
		Route::middleware(Constant::GET_SECTION . Constant::SUB_ADMIN_MANAGEMENT)->group(function () {
			Route::any('/sub-admin-list', [SubAdminController::class, 'listSubadmin'])->name('admin.listSubadmin')->middleware('App\Http\Middleware\CheckPermission:SUB_ADMIN_LIST');
			Route::get('/sub-admin/delete/{id}', [SubAdminController::class, 'delete'])->name('admin.Subadmin_delete')->middleware('App\Http\Middleware\CheckPermission:SUB_ADMIN_DELETE');
			Route::get('/sub-admin-list/add', [SubAdminController::class, 'addSubadmin'])->name('admin.Subadmin_add')->middleware('App\Http\Middleware\CheckPermission:SUB_ADMIN_ADD');
			Route::post('/sub-admin/saveUser', [SubAdminController::class, 'saveSubadmin'])->name('admin.saveSubadmin')->middleware('App\Http\Middleware\CheckPermission:SUB_ADMIN_ADD');
			Route::get('/sub-admin/status/{id}/{value}', [SubAdminController::class, 'status'])->middleware('App\Http\Middleware\CheckPermission:USER_CHANGE_STATUS');
			Route::get('/sub-admin-list/edit/{id}', [SubAdminController::class, 'editSubadmin'])->middleware('App\Http\Middleware\CheckPermission:SUB_ADMIN_EDIT');
			Route::post('/sub-admin/updateUser/{id}', [SubAdminController::class, 'updateSubadmin'])->name('admin.updateSubadmin')->middleware('App\Http\Middleware\CheckPermission:SUB_ADMIN_EDIT');
			Route::get('/sub-admin/view-user/{id}', [SubAdminController::class, 'viewSubadmin'])->middleware('App\Http\Middleware\CheckPermission:SUB_ADMIN_CHANGE_STATUS');
		});

		## User Routes
		Route::middleware(Constant::GET_SECTION . Constant::USER_MANAGEMENT)->group(function () {
			Route::any('/user-list', [UserController::class, 'listUsers'])->name('admin.listUsers')->middleware('App\Http\Middleware\CheckPermission:USER_LIST');
			Route::post('/users/keyword_search', [UserController::class, 'keyword_search'])->name('admin.keyword_search')->middleware('App\Http\Middleware\CheckPermission:USER_LIST');
			Route::get('/users/status/{id}/{value}', [UserController::class, 'status'])->middleware('App\Http\Middleware\CheckPermission:USER_CHANGE_STATUS');
			Route::get('/users/delete/{id}', [UserController::class, 'delete'])->name('admin.user.sub_admin.delete')->middleware('App\Http\Middleware\CheckPermission:SUB_ADMIN_DELETE');
			Route::get('/users/add', [UserController::class, 'addUser'])->name('admin.user_add')->middleware('App\Http\Middleware\CheckPermission:USER_ADD');
			Route::get('/users/edit/{user}', [UserController::class, 'editUser'])->name('admin.user_edit')->middleware('App\Http\Middleware\CheckPermission:USER_EDIT');
			Route::post('/users/saveUser', [UserController::class, 'saveUser'])->name('admin.saveUser')->middleware('App\Http\Middleware\CheckPermission:USER_ADD');
			Route::post('/users/updateUser/{user}', [UserController::class, 'updateUser'])->name('admin.updateUser')->middleware('App\Http\Middleware\CheckPermission:USER_EDIT');
			Route::get('/user-list/view/{id}', [UserController::class, 'viewUser'])->middleware('App\Http\Middleware\CheckPermission:USER_VIEW');
			Route::post('/users/updateCss', [UserController::class, 'updateCss'])->name('admin.updateCss');
			Route::post('validate-username', [UserController::class, 'validateUserName'])->name('admin.validateUsername');
			Route::get('/users-delete/{id}', [UserController::class, 'deleteUser'])->name('admin.delete.user')->middleware('App\Http\Middleware\CheckPermission:USER_DELETE');
		});



		## Notification Routes
		Route::controller(UserController::class)->group(function () {
			Route::get('/push-notification', 'pushNotification')->name('admin.PushNotification')->middleware('App\Http\Middleware\CheckPermission:NOTIFICATION_SEND');
			Route::get('/list-notification', 'ListNotificationHistory')->name('admin.ListNotification')->middleware('App\Http\Middleware\CheckPermission:NOTIFICATION_LIST');
			Route::get('/list-notification-user', 'ListNotificationUser')->name('admin.ListNotificationUser')->middleware('App\Http\Middleware\CheckPermission:NOTIFICATION_LIST_USER');
			Route::get('/list-notification/delete/{id}', 'NotificationDelete')->middleware('App\Http\Middleware\CheckPermission:NOTIFICATION_DELETE');
			Route::post('/send-notification', 'SendNotification')->name('admin.SendNotification')->middleware('App\Http\Middleware\CheckPermission:NOTIFICATION_SEND');
		});



		##Admin Activity Logs Routes
		Route::controller(SubAdminActivityLogController::class)->prefix('sub-admin-activity-logs')->group(function () {
			Route::get('list', 'logList')->name('admin.sub_admin.activity.logs.list');
			Route::get('delete/{activity}', 'delete')->name('admin.sub_admin.activity.delete');
			Route::get('view/{activity}', 'view')->name('admin.sub_admin.activity.view');
		});

		#Email Templates Routes
		Route::middleware(Constant::GET_SECTION . Constant::EMAIL_MANAGEMENTS)->group(function () {

			Route::any('/email_template-list', [EmailTemplateController::class, 'index'])->name('admin.email_template.index')->middleware('App\Http\Middleware\CheckPermission:EMAIL_TEMPLATE_MANGER_LIST');
			Route::get('/email_template-list/edit/{id}', [EmailTemplateController::class, 'edit'])->name('admin.email_template.edit')->middleware('App\Http\Middleware\CheckPermission:EMAIL_TEMPLATE_MANGER_EDIT');
			Route::post('/email_template-list/update/{id}', [EmailTemplateController::class, 'update'])->name('admin.email_template.update')->middleware('App\Http\Middleware\CheckPermission:EMAIL_TEMPLATE_MANGER_EDIT');
		});

		## Global Settings
		Route::controller(SettingController::class)->middleware(Constant::GET_SECTION . Constant::SETTING_MANAGEMENT)->group(function () {
			Route::get('/settings', 'index')->name('Settings.index')->middleware('App\Http\Middleware\CheckPermission:SETTING_MANAGEMENT_LIST');
			Route::post('/settings/save', 'save')->name('admin.settings.save')->middleware('App\Http\Middleware\CheckPermission:SETTING_MANAGEMENT_EDIT');
		});


		##Routes Management
		## Export Module
		Route::get('/export/{modelName}/{format}', [ExportController::class, 'export']);
		##MetaTag Management Routes



        ## Faq Manager Routes
        Route::controller(FaqController::class)->group(function () {
            Route::any('/faq-list',  'index')->name('admin.faq.index')->middleware('App\Http\Middleware\CheckPermission:FAQ_LIST');
            Route::get('/faq-list/status/{id}/{value}',  'status')->middleware('App\Http\Middleware\CheckPermission:FAQ_CHANGE_STATUS');
            Route::get('/faq-list/delete/{id}',  'delete')->middleware('App\Http\Middleware\CheckPermission:FAQ_DELETE');
            Route::get('/faq-list/add',  'add')->name('admin.faq.add')->middleware('App\Http\Middleware\CheckPermission:FAQ_ADD');
            Route::post('/faq-list/save',  'save')->name('admin.faq.save')->middleware('App\Http\Middleware\CheckPermission:FAQ_ADD');
            Route::get('/faq-list/edit/{id}',  'edit')->name('admin.faq.edit')->middleware('App\Http\Middleware\CheckPermission:FAQ_EDIT');
            Route::post('/faq-list/update/{id}',  'update')->name('admin.faq.update')->middleware('App\Http\Middleware\CheckPermission:FAQ_EDIT');
        });


        Route::prefix('vehicle-management')->group(function () {
			Route::controller(BrandController::class)->prefix('brand')->group(function () {
				Route::get('/', 'list')->name('admin.brand.list')->middleware('App\Http\Middleware\CheckPermission:BRAND_LIST');
				Route::post('save', 'save')->name('admin.brand.save')->middleware('App\Http\Middleware\CheckPermission:BRAND_ADD');
				Route::get('add', 'add')->name('admin.brand.add')->middleware('App\Http\Middleware\CheckPermission:BRAND_ADD');
				Route::get('edit/{brand}', 'edit')->name('admin.brand.edit')->middleware('App\Http\Middleware\CheckPermission:BRAND_EDIT');
				Route::post('update/{brand}', 'update')->name('admin.brand.update')->middleware('App\Http\Middleware\CheckPermission:BRAND_EDIT');
				Route::get('delete/{brand}', 'delete')->name('admin.brand.delete')->middleware('App\Http\Middleware\CheckPermission:BRAND_DELETE');
				Route::get('status/{brand}/{status}', 'status')->name('admin.brand.status.change')->middleware('App\Http\Middleware\CheckPermission:BRAND_CHANGE_STATUS');
			});

			Route::controller(ModelController::class)->prefix('model')->group(function () {
				Route::get('/', 'list')->name('admin.model.list')->middleware('App\Http\Middleware\CheckPermission:MODEL_LIST');
				Route::post('save', 'save')->name('admin.model.save')->middleware('App\Http\Middleware\CheckPermission:MODEL_ADD');
				Route::get('add', 'add')->name('admin.model.add')->middleware('App\Http\Middleware\CheckPermission:MODEL_ADD');
				Route::get('edit/{model}', 'edit')->name('admin.model.edit')->middleware('App\Http\Middleware\CheckPermission:MODEL_EDIT');
				Route::post('update/{model}', 'update')->name('admin.model.update')->middleware('App\Http\Middleware\CheckPermission:MODEL_EDIT');
				Route::get('delete/{model}', 'delete')->name('admin.model.delete')->middleware('App\Http\Middleware\CheckPermission:MODEL_DELETE');
				Route::get('status/{model}/{status}', 'status')->name('admin.model.status')->middleware('App\Http\Middleware\CheckPermission:MODEL_CHANGE_STATUS');

			});

			Route::controller(FuelTypeController::class)->prefix('fuel-type')->group(function () {
				Route::get('/', 'list')->name('admin.fueltype.list')->middleware('App\Http\Middleware\CheckPermission:FUEL_USED_LIST');
				Route::post('save', 'save')->name('admin.fueltype.save')->middleware('App\Http\Middleware\CheckPermission:FUEL_USED_ADD');
				Route::get('add', 'add')->name('admin.fueltype.add')->middleware('App\Http\Middleware\CheckPermission:FUEL_USED_ADD');
				Route::get('edit/{fueltype}', 'edit')->name('admin.fueltype.edit')->middleware('App\Http\Middleware\CheckPermission:FUEL_USED_EDIT');
				Route::post('update/{fueltype}', 'update')->name('admin.fueltype.update')->middleware('App\Http\Middleware\CheckPermission:FUEL_USED_EDIT');
				Route::get('delete/{fueltype}', 'delete')->name('admin.fueltype.delete')->middleware('App\Http\Middleware\CheckPermission:FUEL_USED_DELETE');
				Route::get('status/{fuelType}/{status}', 'status')->name('admin.fueltype.status')->middleware('App\Http\Middleware\CheckPermission:FUEL_USED_CHANGE_STATUS');

			});

			Route::controller(TransmissionTypeController::class)->prefix('transmission-type')->group(function () {
				Route::get('/', 'list')->name('admin.transmissiontype.list')->middleware('App\Http\Middleware\CheckPermission:TRANSMISSION_TYPE_LIST');
				Route::post('save', 'save')->name('admin.transmissiontype.save')->middleware('App\Http\Middleware\CheckPermission:TRANSMISSION_TYPE_ADD');
				Route::get('add', 'add')->name('admin.transmissiontype.add')->middleware('App\Http\Middleware\CheckPermission:TRANSMISSION_TYPE_ADD');
				Route::get('edit/{transmissiontype}', 'edit')->name('admin.transmissiontype.edit')->middleware('App\Http\Middleware\CheckPermission:TRANSMISSION_TYPE_EDIT');
				Route::post('update/{transmissiontype}', 'update')->name('admin.transmissiontype.update')->middleware('App\Http\Middleware\CheckPermission:TRANSMISSION_TYPE_EDIT');
				Route::get('delete/{transmissiontype}', 'delete')->name('admin.transmissiontype.delete')->middleware('App\Http\Middleware\CheckPermission:TRANSMISSION_TYPE_DELETE');
				Route::get('status/{transmissionType}/{status}', 'status')->name('admin.transmissiontype.status')->middleware('App\Http\Middleware\CheckPermission:TRANSMISSION_TYPE_CHANGE_STATUS');
                Route::get('view/{transmissiontype}', 'view')->name('admin.transmissiontype.view')->middleware('App\Http\Middleware\CheckPermission:TRANSMISSION_TYPE_VIEW');

			});

			Route::controller(EngineCapacityController::class)->prefix('engine-capacity')->group(function () {
				Route::get('/', 'list')->name('admin.enginecapacity.list')->middleware('App\Http\Middleware\CheckPermission:ENGINE_CAPACITY_LIST');
				Route::post('save', 'save')->name('admin.enginecapacity.save')->middleware('App\Http\Middleware\CheckPermission:ENGINE_CAPACITY_ADD');
				Route::get('add', 'add')->name('admin.enginecapacity.add')->middleware('App\Http\Middleware\CheckPermission:ENGINE_CAPACITY_ADD');
				Route::get('edit/{enginecapacity}', 'edit')->name('admin.enginecapacity.edit')->middleware('App\Http\Middleware\CheckPermission:ENGINE_CAPACITY_EDIT');
				Route::post('update/{enginecapacity}', 'update')->name('admin.enginecapacity.update')->middleware('App\Http\Middleware\CheckPermission:ENGINE_CAPACITY_EDIT');
				Route::get('delete/{enginecapacity}', 'delete')->name('admin.enginecapacity.delete')->middleware('App\Http\Middleware\CheckPermission:ENGINE_CAPACITY_DELETE');
				Route::get('status/{enginecapacity}/{status}', 'status')->name('admin.enginecapacity.status')->middleware('App\Http\Middleware\CheckPermission:ENGINE_CAPACITY_CHANGE_STATUS');

			});


			Route::controller(VehicleController::class)->prefix('vehicle')->group(function(){
				Route::get('/','list')->name('admin.vehicle.list')->middleware('App\Http\Middleware\CheckPermission:VEHICLE_LIST');
				Route::post('save','save')->name('admin.vehicle.save')->middleware('App\Http\Middleware\CheckPermission:VEHICLE_ADD');
				Route::get('add','add')->name('admin.vehicle.add')->middleware('App\Http\Middleware\CheckPermission:VEHICLE_ADD');
				Route::get('edit/{vehicle}','edit')->name('admin.vehicle.edit')->middleware('App\Http\Middleware\CheckPermission:VEHICLE_EDIT');
				Route::post('update/{vehicle}','update')->name('admin.vehicle.update')->middleware('App\Http\Middleware\CheckPermission:VEHICLE_EDIT');
                Route::get('view/{vehicle}','view')->name('admin.vehicle.view')->middleware('App\Http\Middleware\CheckPermission:VEHICLE_VIEW');
				Route::get('delete/{vehicle}','delete')->name('admin.vehicle.delete')->middleware('App\Http\Middleware\CheckPermission:VEHICLE_DELETE');
			});

		});


        Route::controller(ServiceProviderController::class)->prefix('service-provider')->group(function () {
            Route::get('/', 'list')->name('admin.serviceprovider.list')->middleware('App\Http\Middleware\CheckPermission:SERVICE_PROVIDER_LIST');
            Route::post('save', 'save')->name('admin.serviceprovider.save')->middleware('App\Http\Middleware\CheckPermission:SERVICE_PROVIDER_ADD');
            Route::get('add', 'add')->name('admin.serviceprovider.add')->middleware('App\Http\Middleware\CheckPermission:SERVICE_PROVIDER_ADD');
            Route::get('edit/{serviceprovider}', 'edit')->name('admin.serviceprovider.edit')->middleware('App\Http\Middleware\CheckPermission:SERVICE_PROVIDER_EDIT');
            Route::post('update/{serviceprovider}', 'update')->name('admin.serviceprovider.update')->middleware('App\Http\Middleware\CheckPermission:SERVICE_PROVIDER_EDIT');
            Route::get('delete/{serviceprovider}', 'delete')->name('admin.serviceprovider.delete');
            Route::get('status/{serviceprovider}/{status}', 'status')->name('admin.serviceprovider.status')->middleware('App\Http\Middleware\CheckPermission:SERVICE_PROVIDER_CHANGE_STATUS');

        });

        Route::prefix('insurance-management')->group(function () {
            ## InsuranceCompany Routes
            Route::controller(InsuranceCompanyController::class)->prefix('insurance-company')->group(function () {
				Route::get('/', 'list')->name('admin.insuranceCompany.list')->middleware('App\Http\Middleware\CheckPermission:INSURANCE_LIST');
				Route::post('save', 'save')->name('admin.insuranceCompany.save')->middleware('App\Http\Middleware\CheckPermission:INSURANCE_ADD');
				Route::get('add', 'add')->name('admin.insuranceCompany.add')->middleware('App\Http\Middleware\CheckPermission:INSURANCE_ADD');
				Route::get('edit/{insuranceCompany}', 'edit')->name('admin.insuranceCompany.edit')->middleware('App\Http\Middleware\CheckPermission:INSURANCE_EDIT');
				Route::post('update/{insuranceCompany}', 'update')->name('admin.insuranceCompany.update')->middleware('App\Http\Middleware\CheckPermission:INSURANCE_EDIT');
				Route::get('delete/{insuranceCompany}', 'delete')->name('admin.insuranceCompany.delete')->middleware('App\Http\Middleware\CheckPermission:INSURANCE_EDIT');
				Route::get('status/{insuranceCompany}/{status}', 'status')->name('admin.insuranceCompany.status.change')->middleware('App\Http\Middleware\CheckPermission:INSURANCE_CHANGE_STATUS');
			});

            ## InsuranceQuoteRequest Routes
             Route::controller(InsuranceQuoteController::class)->prefix('insurance-quote')->group(function () {
				Route::get('/', 'list')->name('admin.insuranceQuote.list')->middleware('App\Http\Middleware\CheckPermission:INSURANCE_QUOTE_LIST');
				Route::get('view/{insuranceQuote}', 'view')->name('admin.insuranceQuote.view')->middleware('App\Http\Middleware\CheckPermission:INSURANCE_QUOTE_VIEW');
			});

            ## InsuranceQuoteConfirmation Routes
             Route::controller(InsuranceQuoteConfirmationController::class)->prefix('insurance-quote-confirmation')->group(function () {
				Route::get('/', 'list')->name('admin.insuranceQuoteConfirmation.list')->middleware('App\Http\Middleware\CheckPermission:INSURANCE_QUOTE_CONFIRMATION_LIST');
			});
            ## InsuranceCoverType Routes
            Route::controller(InsuranceCoverTypeController::class)->prefix('insurance-cover-type')->group(function () {
				Route::get('/', 'list')->name('admin.insuranceCoverType.list')->middleware('App\Http\Middleware\CheckPermission:INSURANCE_COVER_TYPE_LIST');
                Route::get('view/{insuranceCoverType}', 'view')->name('admin.insuranceCoverType.view')->middleware('App\Http\Middleware\CheckPermission:INSURANCE_COVER_TYPE_VIEW');
                Route::get('status/{id}/{status}', 'status')->name('admin.insuranceCoverType.status.change')->middleware('App\Http\Middleware\CheckPermission:INSURANCE_COVER_TYPE_STATUS');

			});
            ## InsuranceCoverPeriod Routes
            Route::controller(InsuranceCoverPeriodController::class)->prefix('insurance-cover-period')->group(function () {
				Route::get('/', 'list')->name('admin.insuranceCoverPeriod.list')->middleware('App\Http\Middleware\CheckPermission:INSURANCE_COVER_PERIOD_LIST');
                Route::get('view/{insuranceCoverPeriod}', 'view')->name('admin.insuranceCoverPeriod.view')->middleware('App\Http\Middleware\CheckPermission:INSURANCE_COVER_PERIOD_VIEW');
                Route::get('status/{id}/{status}', 'status')->name('admin.insuranceCoverPeriod.status.change')->middleware('App\Http\Middleware\CheckPermission:INSURANCE_COVER_PERIOD_STATUS');

			});

             ## Insurance Renewal Routes
            Route::controller(InsuranceRenewalController::class)->prefix('insurance-renewal')->group(function () {
				Route::get('/', 'list')->name('admin.insuranceRenewal.list')->middleware('App\Http\Middleware\CheckPermission:INSURANCE_RENEWAL_LIST');
                Route::post('save', 'save')->name('admin.insuranceRenewal.save');
				Route::get('add', 'add')->name('admin.insuranceRenewal.add')->middleware('App\Http\Middleware\CheckPermission:INSURANCE_RENEWAL_ADD');
                Route::get('edit/{insuranceRenewal}', 'edit')->name('admin.insuranceRenewal.edit')->middleware('App\Http\Middleware\CheckPermission:INSURANCE_RENEWAL_EDIT');
				Route::post('update/{insuranceRenewal}', 'update')->name('admin.insuranceRenewal.update');
			});
        });





		Route::group([], base_path('routes/media.php'));
		Route::group([], base_path('routes/page.php'));

		loadDropDownRoutes();
	});
});
