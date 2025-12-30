@extends('admin.layouts.admin-login')
@section('content')
<div class="panel-heading">
      <div class="card-title">
          <div class="title">{{ 'Verification Otp' }} </div>
      </div>
  </div>
<div class="panel-body">
    {{ html()->modelForm(null, 'POST')->route('sendMobileOtp')->attributes(['id'=>"mobileOtpForm",'class'=>'pwdForm','role'=>'form','autocomplete' => 'off'])->open() }}
		<div class="form-group col-md-12 clearValidation3">
			<label for="phone">{{ trans('messages.phoneNo') }} <span class="red_lab"> *</span></label>
			<div class="row gx-2 gx-lg-3 gy-3">
				<div class="col-lg-6 errorPlacement mb-1 mb-lg-0">
                    {{ html()->select('country_code',$phonecode,'+91')->attributes(['class' => 'form-control',
                     'placeholder' => trans("messages.phoneNo"),'required'=>'required']) }}
                    @if ($errors->has('country_code'))
                    <span class="invalid-feedback error" role="alert">
                        <strong>{{ $errors->first('country_code') }}</strong>
                    </span>
		        	@endif
				</div>
				<div class="col-lg-6 w-fullInner">
                    {{ html()->text('mobile_no')->attributes(['class' => 'form-control', 'placeholder' => trans("messages.phoneNo"),'required'=>'required','value'=>'987888888888']) }}
				</div>
			</div>
			@if ($errors->has('mobile_no'))
			<span class="invalid-feedback " role="alert">
				<strong>{{ $errors->first('mobile_no') }}</strong>
			</span>
			@endif
		</div>
		<div class="row">
        <div class="col-xs-4">
           <input type="submit" class="btn theme_btn bg_theme w-100 font-semibold border-0" value="{{ trans('messages.submit') }}">
        </div>
      </div>
      <br/>
      <div class="text-center mt-4">
      <a href="{{ route('login') }}" class="text-center m-0">Back to login</a><br>
      </div>
      {{ html()->closeModelForm() }}
</div>
@if(env('ENABLE_CLIENT_VALIDATION'))
<script integrity="" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
	$(function() {
		window.formReference = $("#mobileOtpForm").validate({
			rules: {
                country_code:{
                    required: true,
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


 @endsection
