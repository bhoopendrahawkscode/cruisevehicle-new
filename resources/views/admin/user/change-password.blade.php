@extends('admin.layouts.default_layout')
@section('content')
<div class="header d-flex align-items-center">
	<h1 class="page-header">
		{{ trans('messages.changePassword') }}
	</h1>
	<ol class="breadcrumb ms-auto mb-0">
		<li><a href="{{ Route('admin.dashboard') }}"><em class="fa fa-dashboard"></em> {{ trans('messages.dashboard') }}</a></li>
		<li class="active">{{ trans('messages.changePassword') }}</li>
	</ol>
</div>
<section id="page-inner">
	<div class="panel panel-default">
        {{ html()->modelForm(null, 'POST')->route('update-password')
    ->attributes(['id'=>'changePwdForm','class'=>'form-horizontal','role'=>'form','autocomplete' => 'off'])->open() }}
		<div class="panel-body">
			<div class="form-group">
				<div class="col-sm-8 col-md-6 clearValidation">
					<label>{{trans('messages.currentPassword')}}<span class="red_lab"> *</span></label>
					<input class="form-control" id="current" placeholder="{{trans('messages.enterCurrentPassword')}}" name="current_password" type="password" value="{{ old('current_password')}}">
					@if ($errors->has('current_password'))
					<span class="invalid-feedback" role="alert">
						<strong>{{ $errors->first('current_password') }}</strong>
					</span>
					@endif
					<em class="fa fa-eye-slash form-control-feedback cp eye-icon-fa" id="togglePassword"></em>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-8 col-md-6 clearValidation">
					<label>{{trans('messages.newPassword')}}<span class="red_lab"> *</span></label>
					<input class="form-control " id="newPass" placeholder="{{trans('messages.enterNewPassword')}}" name="new_password" type="password" value="{{ old('new_password')}}">
					@if ($errors->has('new_password'))
					<span class="invalid-feedback" role="alert">
						<strong>{{ $errors->first('new_password') }}</strong>
					</span>
					@endif
					<em class="fa fa-eye-slash form-control-feedback cp eye-icon-fa" id="togglePassword2"></em>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-8 col-md-6 clearValidation">
					<label>{{trans('messages.confirmNewPassword')}}<span class="red_lab"> *</span></label>
					<input class="form-control" id="confirmNewPass" placeholder="{{trans('messages.enterConfirmNewPassword')}}" name="confirm_password" type="password" value="{{ old('confirm_password')}}">
					@if($errors->has('confirm_password'))
					<span class="invalid-feedback" role="alert">
						<strong>{{ $errors->first('confirm_password') }}</strong>
					</span>
					@endif
					<em class="fa fa-eye-slash form-control-feedback cp eye-icon-fa" id="togglePassword3"></em>
				</div>
			</div>

			<div class="form-group d-flex gap-3">
				<button type="submit" class="btn theme_btn bg_theme font-semibold border-0 fs-6 px-sm-5">{{ trans('messages.submit') }}</button>
				<a href="{{ Route('admin.dashboard') }}" class="btn px-sm-5 font-semibold border_btn">{{ trans('messages.back') }}</a>
			</div>
		</div>
        {{ html()->closeModelForm() }}
	</div>
</section>
@if(env('ENABLE_CLIENT_VALIDATION'))
<script>
	$(function() {
		// Form Validation
		$("#changePwdForm").validate({
			rules: {
				current_password: {
					required: true,
					rule_password: true,
					maxlength: 30
				},
				new_password: {
					required: true,
					rule_password: true,
					maxlength: 30
				},
				confirm_password: {
					required: true,
					rule_password: true,
					maxlength: 30,
					equalTo: "#newPass",
				},
			},
			messages: {
				//current_password: "Please enter your current password.",
				'confirm_password': {
					equalTo: "{{ trans('messages.newPassConfirmPassNotMatched') }}",
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
			// Make sure the form is submitted to the destination defined
			// in the "action" attribute of the form when valid
			/*submitHandler: function(form) {
			}*/

		});
	});
</script>
@endif
<script>
	var togglePassword = document
		.querySelector('#togglePassword');
	var password = document.querySelector('#current');
	togglePassword.addEventListener('click', () => {
		// Toggle the type attribute using
		// getAttribure() method
		var type = password
			.getAttribute('type') === 'password' ?
			'text' : 'password';
		var cls = password
			.getAttribute('type') === 'password' ?
			'fa-eye' : 'fa-eye-slash';
		password.setAttribute('type', type);
		// Toggle the eye and bi-eye icon
		document.getElementById("togglePassword").classList.remove('fa-eye-slash');
		document.getElementById("togglePassword").classList.remove('fa-eye');
		document.getElementById("togglePassword").classList.add(cls);
	});

	var togglePassword2 = document
		.querySelector('#togglePassword2');
	var password2 = document.querySelector('#newPass');
	togglePassword2.addEventListener('click', () => {
		// Toggle the type attribute using
		// getAttribure() method
		var type2 = password2
			.getAttribute('type') === 'password' ?
			'text' : 'password';
		var cls2 = password2
			.getAttribute('type') === 'password' ?
			'fa-eye' : 'fa-eye-slash';
		password2.setAttribute('type', type2);
		// Toggle the eye and bi-eye icon
		document.getElementById("togglePassword2").classList.remove('fa-eye');
		document.getElementById("togglePassword2").classList.remove('fa-eye-slash');
		document.getElementById("togglePassword2").classList.add(cls2);
	});

	var togglePassword3 = document
		.querySelector('#togglePassword3');
	var password3 = document.querySelector('#confirmNewPass');
	togglePassword3.addEventListener('click', () => {
		// Toggle the type attribute using
		// getAttribure() method
		var type3 = password3
			.getAttribute('type') === 'password' ?
			'text' : 'password';
		var cls3 = password3
			.getAttribute('type') === 'password' ?
			'fa-eye' : 'fa-eye-slash';
		password3.setAttribute('type', type3);
		// Toggle the eye and bi-eye icon
		document.getElementById("togglePassword3").classList.remove('fa-eye');
		document.getElementById("togglePassword3").classList.remove('fa-eye-slash');
		document.getElementById("togglePassword3").classList.add(cls3);
	});
</script>


@endsection
