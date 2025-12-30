@extends('admin.layouts.default_layout')
@section('content')
<?php
use App\Constants\Constant;
use App\Services\GeneralService;
use App\Services\ImageService;
?>
<script type="text/javascript" integrity="" src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.0.1/ckeditor.js"></script>
<div class="header d-flex align-items-center">
    <h1 class="page-header">
        {{ trans('messages.settings') }}
    </h1>
    <ol class="breadcrumb ms-auto mb-0">
        <li><a href="{{ Route('admin.dashboard') }}"><em class="fa fa-dashboard"></em> {{ trans('messages.dashboard') }}</a></li>
        <li class="active">{{ trans('messages.settings') }}</li>
    </ol>
</div>
<div id="page-inner">
    {{-- <div class="panel panel-default">
        <div class="panel-body">
            <div class="table-responsive">
                <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <a href="{{url('admin/export/Setting/xls')}}" class="btn btn-sm border_btn" title="{{ trans('messages.exportXls') }}">{{ trans('messages.exportXls') }}</a>
                        <a href="{{url('admin/export/Setting/csv')}}" class="btn btn-sm border_btn" title="{{ trans('messages.exportCsv') }}">{{ trans('messages.exportCsv') }}</a>
                    </div>
                </div>
                </div>
        </div>
        </div>
        </div> --}}



    <div class="panel panel-default">
        <div class="panel-body">
            <div class="table-responsive">
                {{ html()->modelForm($settings, 'POST')->route('admin.settings.save')->attributes(['id'=>"settingForm", 'autocomplete' => 'off','enctype'=>'multipart/form-data'])->open() }}
                @csrf

                <div class="container-fluid">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                 @endif

                      <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="header" class="font-medium fs-6 mb-3">{{trans('messages.websiteTitle')}}<span class="red_lab"> *</span></label>
                                {{ html()->text('websiteTitle')->attributes(['class' => 'form-control  formValidate', 'maxlength'=>50, 'placeholder' => trans("messages.websiteTitle")]) }}
                                @if ($errors->has('websiteTitle'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('websiteTitle') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="header" class="font-medium fs-6 mb-3">{{trans('messages.companyPhone')}}</label>
                                {{ html()->text('companyPhone')->attributes(['class' => 'form-control', 'maxlength'=>12, 'placeholder' => trans("messages.companyPhone")]) }}
                               
                                @if ($errors->has('companyPhone'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('companyPhone') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                      </div>

                    <div class="row">
                    <div class="col-md-6">
                            <div class="form-group">
                                <label for="header" class="font-medium fs-6 mb-3">{{trans('messages.companyEmail')}}</label>
                                {{ html()->text('companyEmail')->attributes(['class' => 'form-control ', 'maxlength'=>50, 'placeholder' => trans("messages.companyEmail")]) }}
                                @if ($errors->has('companyEmail'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('companyEmail') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        {{-- <div class="col-md-6">
                            <div class="form-group">
                                <label for="header" class="font-medium fs-6 mb-3">{{trans('messages.dateFormat')}}<span class="red_lab"> *</span></label>
                                {{ html()->select('dateFormat',$settingDateFormatList)->attributes(['class' => 'form-control  formValidate', 'maxlength'=>10, 'placeholder' => trans("messages.dateFormatPlace")]) }}
                                @if ($errors->has('dateFormat'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('dateFormat') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div> --}}
                        <div class="col-md-3 d-none">
                            <div class="form-group">
                                <label for="header" class="font-medium fs-6 mb-3">{{trans('messages.UploadServerType')}}<span class="red_lab"> *</span></label>
                                {{ html()->select('UploadServerType',$UploadServerType)->attributes(['class' => 'form-control  formValidate']) }}
                                @if ($errors->has('UploadServerType'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('UploadServerType') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-lg-4 col-md-6 upload_img mb-5">
                            <label>{{ trans('messages.logoImage') }}</label>
                            <input name="logo_image" type="file" id="imageInput" accept="image/*">
                            <span id="file-size-error" class="text-danger"></span>
                            @if ($errors->has('logo_image'))
                            <span class="invalid-feedback error" role="alert">
                                <strong>{{ $errors->first('logo_image') }}</strong>
                            </span>
                            @endif

                           @if(isset($settings->logo))
                                <label class="exist_image">{{ trans('messages.existingImage') }}</label>
                                <div class="old_img">
                                    <img alt="Image" class="border border-1" src="{{ ImageService::getImageUrl(Config::get('constants.LOGO_FOLDER') . GeneralService::getSettings('logo')) }}" width="100">
                                </div>
                                @endif
                        </div>
                            <div class="col-lg-2  col-md-6">
                                <label class='image-preview'>{{ trans('messages.imagePreview') }}</label>
                                <img id="image-preview" class="image-preview" alt="{{ trans('messages.imagePreview') }}" class="img-fluid rounded-circle" width="150px">
                            </div>


                      <div class="col-lg-4 col-md-6 upload_img mb-5">
                            <label>{{ trans('messages.faviconIcon') }}</label>
                            <input name="favicon_image" type="file" id="imageInputFav" accept="image/*">
                            <span id="file-size-error" class="text-danger"></span>
                            @if ($errors->has('favicon_image'))
                            <span class="invalid-feedback error" role="alert">
                                <strong>{{ $errors->first('favicon_image') }}</strong>
                            </span>
                            @endif
                            @if(isset($settings->favicon))
                                <label class="exist_image">{{ trans('messages.existingImage') }}
                                </label>
                                <div class="old_img">
                                    <img alt="Image" class="border border-1" src="{{ ImageService::getImageUrl(Config::get('constants.LOGO_FOLDER') . GeneralService::getSettings('favicon')) }}" width="100">
                                </div>
                                @endif
                        </div>
                            <div class="col-lg-2  col-md-6">
                                <label class='image-preview-fav'>{{ trans('messages.imagePreview') }}</label>
                                <img id="image-preview-fav" class="image-preview-fav" alt="{{ trans('messages.imagePreview') }}" class="img-fluid rounded-circle" width="150px">
                            </div>
                        </div>

                   <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="header" class="font-medium fs-6 mb-3">{{trans('messages.companyAddress')}}<span class="red_lab"> *</span></label>
                                {{ html()->textarea('companyAddress')->attributes(['class' => 'form-control  formValidate', 'rows'=>5, 'placeholder' => trans("messages.companyAddress")]) }}
                                @if ($errors->has('companyAddress'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('companyAddress') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                      </div>
                        {{-- <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="header" class="font-medium fs-6 mb-3">{{trans('messages.isMaintenanceMode')}}<span class="red_lab"> *</span></label>
                                {{ html()->select('isMaintenanceMode',$isMaintenanceModeType)->attributes(['class' => 'form-control  formValidate']) }}
                                @if($errors->has('isMaintenanceMode'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('isMaintenanceMode') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="header" class="font-medium fs-6 mb-3">{{trans('messages.password_generation')}}<span class="red_lab"> *</span></label>
                                {{ html()->select('password_generation',Constant::PASSWORD_GENERATION)->attributes(['class' => 'form-control  formValidate']) }}
                                @if($errors->has('password_generation'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('password_generation') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="header" class="font-medium fs-6 mb-3">{{trans('messages.otp_verification_mode')}}<span class="red_lab"> *</span></label>
                                {{ html()->select('otp_verification_mode',Constant::OTP_VERIFICATION_MODE)->attributes(['class' => 'form-control  formValidate']) }}
                                @if($errors->has('otp_verification_mode'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('otp_verification_mode') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="header" class="font-medium fs-6 mb-3">{{trans('messages.two_factor_authentication')}}<span class="red_lab"> *</span></label>
                                {{ html()->select('two_factor_authentication',Constant::TWO_FACTOR_AUTHENTICATION)->attributes(['class' => 'form-control  formValidate']) }}
                                @if($errors->has('two_factor_authentication'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('two_factor_authentication') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div> --}}

                    <div class="row">
                    {{-- <div class="col-md-6">
                            <div class="form-group">
                                <label for="footer" class="font-medium fs-6 mb-3">{{trans('messages.googleAnalytics')}}</label>
                                {{ html()->textarea('googleAnalytics')->attributes(['class' => 'form-control  formValidate', 'placeholder' => trans("messages.googleAnalytics"), 'rows'=>'10']) }}
                                @if ($errors->has('googleAnalytics'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('googleAnalytics') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="header" class="font-medium fs-6 mb-3">{{trans('messages.customCss')}}</label>
                                {{ html()->textarea('customCss')->attributes(['class' => 'form-control  formValidate', 'placeholder' => trans("messages.customCss"), 'rows'=>'10']) }}
                                @if ($errors->has('customCss'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('customCss') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                         <div class="col-md-12">
                            <div class="form-group">
                                <label for="footer" class="font-medium fs-6 mb-3">{{trans('messages.thirdPartyJs')}}</label>
                                {{ html()->text('thirdPartyJs')->attributes(['class' => 'form-control  formValidate', 'placeholder' => trans("messages.thirdPartyJs")]) }}
                                @if ($errors->has('thirdPartyJs'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('thirdPartyJs') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div> --}}

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="header" class="font-medium fs-6 mb-3">{{trans('messages.emailHeaderContent')}}</label>
                                {{ html()->textarea('header')->attributes(['class' => 'form-control  formValidate',
                                            'placeholder' => trans("messages.emailHeaderContent")]) }}
                                @if ($errors->has('header'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('header') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="footer" class="font-medium fs-6 mb-3">{{trans('messages.emailFooterContent')}}</label>
                                {{ html()->textarea('footer')->attributes(['class' => 'form-control  formValidate',
                                            'placeholder' => trans("messages.emailFooterContent")]) }}
                                @if ($errors->has('footer'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('footer') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>


                    @can("SETTING_MANAGEMENT_EDIT")
                    <button type="submit" class="btn theme_btn bg_theme font-semibold border-0 fs-6 px-sm-5">{{ trans('messages.submit') }}</button>
                    {{ html()->closeModelForm() }}
                    @endcan

                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('assets/js/custom-user-define-fun.js') }}"></script>
<script>
    $(document).ready(function() {
        CKEDITOR.replace('header', {
            allowedContent: true,
            height: 320
        });

        CKEDITOR.replace('footer', {
            allowedContent: true,
            height: 320
        });

    });
</script>
<script>
	$(function() {
		window.formReference = $("#settingForm").validate({
			rules: {
                websiteTitle: {
					required: true,
                    minlength: 2,
                    maxlength: 30,
                    notNumber: true,
                    validName: true,
				},
              
                companyEmail:{
                  
                    email: true,
                    maxlength: 100,
                    emailPattern: true
                },
                companyAddress: {
					
					minlength: 2,
					maxlength: 100,
				},
             
                UploadServerType: {
					required: true,
				},
             
                password_generation: {
					required: true,
				},
                two_factor_authentication: {
					required: true,
				},
              
                thirdPartyJs: {
					url: true,
				},
                'logo_image':{
				     		extension: "jpeg|png|jpg|gif"
				          },
                'favicon_image':{
				     		extension: "jpeg|png|jpg|gif|ico"
				          },
			},
			messages: {
				websiteTitle: {
                    minlength: "{{ trans('messages.min2Max30') }}",
                    maxlength: "{{ trans('messages.min2Max30') }}",
                    notNumber: "{{ trans('messages.notNumberMessage') }}"
				},
                companyPhone: {
                    minlength: "{{ trans('messages.phoneNumRangeValidationMessage') }}",
                    maxlength: "{{ trans('messages.phoneNumRangeValidationMessage') }}"
                },
                companyAddress: {
                    required:'Enter company address',
				},
                UploadServerType: {
                    required:'Select Upload Sever Type',
				},
                'logo_image': "Select a image file as jpg, jpeg, png",
                'favicon_image': "Select a image file as jpg, jpeg, png",
			},
			errorClass: "help-inline",
			errorElement: "span",
			highlight: function(element, errorClass, validClass) {
				$(element).parents('.form-group').addClass('error');
				//$('.invalid-feedback').hide();
			},
			unhighlight: function(element, errorClass, validClass) {
				$(element).parents('.form-group').removeClass('error');
				$(element).parents('.form-group').addClass('success');
			},
		});

        checkImage(false);
        $("#imageInput").change(function(e) {
            checkImage(true);
        });

        checkImageFav(false);
        $("#imageInputFav").change(function(e) {
            checkImageFav(true);
        });
	});
</script>
@endsection
