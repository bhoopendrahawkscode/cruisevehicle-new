@extends('admin.layouts.admin-login')
@section('content')
<div class="panel-heading">
      <div class="card-title">
          <div class="title">{{ trans('messages.forgotPassword') }} </div>
      </div>
  </div>
<div class="panel-body">
    {{ html()->modelForm(null, 'POST')->route('sendPassword')
    ->attributes(['id'=>"forgotPassword",'class'=>'pwdForm','role'=>'form','autocomplete' => 'off'])->open() }}
		<div class="form-group has-feedback">
            {{ html()->email('email')->value(old('email'))->attributes(['class' => 'form-control', 'required' => 'required', 'placeholder' => trans('messages.email')]) }}

			@if ($errors->has('email'))
				<span class="invalid-feedback login-error-font-size" role="alert">
					<strong>{{ $errors->first('email') }}</strong>
				</span>
			@endif
		<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
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
    <script>
		$(function() {
				$("#forgotPassword").validate(
					{
						rules: {
							'email': {
                                required: true,
                                email: true,
                                maxlength:100,
                                emailPattern:true
							}

						},
						highlight:function(element, errorClass, validClass) {
							//$('.invalid-feedback').hide();
						}
					}
				)
			});
    </script>
@endif


 @endsection
