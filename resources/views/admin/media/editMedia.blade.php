@extends('admin.layouts.default_layout')
@section('content')
<?php  use \App\Constants\Constant; ?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<div class="header d-flex align-items-center">
	<h1 class="page-header">
		{{ trans('messages.myProfile') }}
	</h1>
	<ol class="breadcrumb ms-auto mb-0">
		<li><a href="{{ Route('admin.dashboard') }}"><em class="fa fa-dashboard"></em> {{ trans('messages.dashboard') }}</a></li>
		<li class="active">{{ trans('messages.myProfile') }}</li>
	</ol>
</div>
<section id="page-inner">
    <div class="panel panel-default">
        {{ html()->modelForm($authDetail, 'POST')->route('update-profile')
        ->attributes(['id'=>'editProfileForm','class'=>'form-horizontal','role'=>'form','autocomplete' => 'off','enctype'=>'multipart/form-data'])->open() }}
	<div class="panel-body">
	<div class="row">
		<div class="col-md-6 form-group">
			<label>{{ trans('messages.firstName') }}<span class="red_lab"> *</span></label>
            {{ html()->text('first_name',null)->attributes(['class' => 'form-control',
            'placeholder' => trans("messages.firstName")]) }}
			@if ($errors->has('first_name'))
			<span class="invalid-feedback" role="alert">
				<strong>{{ $errors->first('first_name') }}</strong>
			</span>
			@endif
		</div>
		<div class="col-md-6 form-group">
			<label>{{ trans('messages.lastName') }}<span class="red_lab"> *</span></label>
            {{ html()->text('last_name',null)->attributes(['class' => 'form-control',
            'placeholder' => trans("messages.lastName")]) }}
			@if ($errors->has('last_name'))
			<span class="invalid-feedback" role="alert">
				<strong>{{ $errors->first('last_name') }}</strong>
			</span>
			@endif
		</div>
		<div class="form-group col-md-6 clearValidation2">
			<label>{{ trans('messages.email') }}<span class="red_lab"> *</span></label>
            {{ html()->text('email',null)->attributes(['class' => 'form-control',
            'placeholder' => trans("messages.email")]) }}
			@if ($errors->has('email'))
			<span class="invalid-feedback error" role="alert">
				<strong>{{ $errors->first('email') }}</strong>
			</span>
			@endif
		</div>
        @if(Constant::ALLOW_SUB_ADMIN_PHONE_NO)
		<div class="form-group col-md-6 clearValidation3">
			<label for="phone">{{ trans('messages.phoneNo') }} <span class="red_lab"> *</span></label>
			<div class="row gx-2 gx-lg-3 gy-3">
				<?php $temp = explode("-", $authDetail->mobile_no);
				 $temp[0]	=	'+' . $temp[0];
				?>
				<div class="col-lg-6 errorPlacement mb-3 mb-lg-0">
                    {{ html()->select('country_code',$phonecode,$temp[0])->attributes(['class' => 'form-control select2',
                     'placeholder' => trans("messages.phoneNo"),'required'=>'required']) }}
                    @if ($errors->has('country_code'))
                    <span class="invalid-feedback error" role="alert">
                        <strong>{{ $errors->first('country_code') }}</strong>
                    </span>
		        	@endif
				</div>
				<div class="col-lg-6 w-fullInner">
                    {{ html()->text('mobile_no',@$temp[1])->attributes(['class' => 'form-control',
                     'placeholder' => trans("messages.phoneNo"),'required'=>'required']) }}
				</div>
			</div>
			@if ($errors->has('mobile_no'))
			<span class="invalid-feedback " role="alert">
				<strong>{{ $errors->first('mobile_no') }}</strong>
			</span>
			@endif
		</div>
        @endif
	</div>
    <div class="row">
        <div class="col-lg-4 col-md-6 upload_img mb-5">
            <label>{{ trans('messages.image') }}</label>
            <input name="image" type="file" id="imageInput" accept="image/*">
            <span id="file-size-error" class="text-danger"></span>
            @if ($errors->has('image'))
            <span class="invalid-feedback error" role="alert">
                <strong>{{ $errors->first('image') }}</strong>
            </span>
            @endif
            <span class="imageHint" style="display: block">{{ trans("messages.imageHint") }}</span>
                <label class="exist_image">{{ trans('messages.existingImage') }}
                </label>
                <div class="old_img">
                    <img alt="Image" class="border border-1" src="{{ $authDetail['thumbImage'] }}" width="100">
                </div>
        </div>
        <div class="col-lg-2  col-md-6">
            <label class='image-preview'>{{ trans('messages.imagePreview') }}</label>
            <img id="image-preview" class="image-preview" alt="{{ trans('messages.imagePreview') }}" class="img-fluid rounded-circle" width="150px">
        </div>
    </div>
	<div class="form-group d-flex gap-3">
		<button type="submit" class="btn theme_btn bg_theme font-semibold border-0 fs-6 px-sm-5">{{ trans('messages.submit') }}</button>
		<a href="{{ Route('admin.dashboard') }}" class="btn px-sm-5 font-semibold border_btn">{{ trans('messages.back') }}</a>
	</div>
    {{ html()->closeModelForm() }}
	</div>
	</div>
</section>
@if(env('ENABLE_CLIENT_VALIDATION'))
<script integrity="" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
	$(function() {
		window.formReference = $("#editProfileForm").validate({
			rules: {
                country_code:{
                    required: true,
                },
				first_name: {
					required: true,
					minlength: 2,
					maxlength: 30,
					validName: true
				},
				last_name: {
					required: true,
					minlength: 2,
					maxlength: 30,
					validName: true
				},
				email: {
					required: true,
					email: true,
					maxlength: 100,
					emailPattern: true
				},
				mobile_no: {
					required: true,
					number: true,
					minlength: 6,
					maxlength: 12,
					onlyInteger: true,
					nonZeroPhoneNumber: true
				},
			},
			messages: {
				first_name: {
					minlength: "{{ trans('messages.min2Max30') }}",
					maxlength: "{{ trans('messages.min2Max30') }}",
				},
				last_name: {
					minlength: "{{ trans('messages.min2Max30') }}",
					maxlength: "{{ trans('messages.min2Max30') }}",
				},
				mobile_no: {
					minlength: "{{ trans('messages.phoneNumRangeValidationMessage') }}",
					maxlength: "{{ trans('messages.phoneNumRangeValidationMessage') }}"
				},
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
	});
</script>
@endif
<script>
    $(function() {
        $("#imageInput").rules('add', {
            extension: "{{ Config::get('constants.validImageExtensions') }}",
            filesize: "{{ Config::get('constants.maxImageSizeJs') }}",
        });
    });
</script>
@endsection
