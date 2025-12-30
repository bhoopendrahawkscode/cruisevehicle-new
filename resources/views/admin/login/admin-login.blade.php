@extends('admin.layouts.admin-login')
@section('content')
<div class="panel-heading">
      <div class="card-title">
          <div class="title">{{ trans('messages.login') }}</div>
      </div>
  </div>
<div class="panel-body">
  {{ html()->modelForm(null, 'POST')->route('admin-login')
  ->attributes(['id'=>"loginForm",'class'=>'pwdForm','role'=>'form','autocomplete' => 'off'])->open() }}

    <div class="form-group has-feedback">
      <input id="username" type="email"  class="form-control @error('username') is-invalid @enderror" name="username" autocomplete="username" placeholder="{{ trans('messages.email') }}" value="{{ old('username', isset($_COOKIE['admin_email']) ? $_COOKIE['admin_email'] : '') }}" required>
      @if ($errors->has('username'))
        <span class="invalid-feedback login-error-font-size" role="alert">
          <strong>{{ $errors->first('username') }}</strong>
        </span>
      @endif
      <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
    </div>
    <div class="form-group has-feedback clearValidation">
      <input id="password" type="password" required class=" form-control " name="password" autocomplete="current-password" placeholder="{{ trans('messages.password') }}" value="{{ old('admin_password') ?? (isset($_COOKIE['admin_password']) ? $_COOKIE['admin_password'] : '') }}">

	  @if ($errors->has('password'))
        <span class="invalid-feedback login-error-font-size" role="alert">
          <strong>{{ $errors->first('password') }}</strong>
        </span>
      @endif

	  <span id="togglePassword">  <em class="fa fa-eye-slash form-control-feedback eye-icon-fa password-eye"  ></em></span>

         @if(env('ENABLE_CLIENT_VALIDATION'))
		<script>

			$(function() {
				$("#loginForm").validate(
					{
						rules: {
							'username': {
                                required: true,
                                email: true,
                                maxlength:100,
                                emailPattern:true
							},
							'password': {
								required: true,
								rule_password: true,
								maxlength: 30
							},

						},
						highlight:function(element, errorClass, validClass) {
							//$('.invalid-feedback').hide();
						}
					}
				)
			});
        </script>
        @endif
        <script>
            $(document).ready(function() {
              $('#togglePassword').click(function() {
                // Toggle the type attribute using prop() method
                const password = $('#password');
                const type = password.prop('type') === 'password' ? 'text' : 'password';
                const cls = password.prop('type') === 'password' ? 'fa-eye' : 'fa-eye-slash';

                password.prop('type', type);

                // Toggle the eye and bi-eye icon
                $('.password-eye').removeClass('fa-eye fa-eye-slash').addClass(cls);
              });
            });
          </script>

    </div>
    <div class="row align-items-center">

      <div class="col-xs-8">
        <div class="checkbox icheck">
          <div class="input checkbox">
              <span class="custom_check font-semibold" for="remember">{{ trans('messages.rememberMe') }} &nbsp; <input type="checkbox" name="remember" value="" {{ isset($_COOKIE['remember_me']) ? 'checked' : '' }}><span class="check_indicator">&nbsp;</span></span>
          </div>
        </div>
      </div>
      <!-- /.col -->
      <div class="col-xs-4 form-group">
      <a href="{{ route('forgotPassword') }}" class="mt-0">{{ trans('messages.iForgotMyPassword') }} </a><br>

      </div>
      <!-- /.col -->
    </div>
    <div class="text-center mt-4">
      <input type="submit" class="btn theme_btn bg_theme w-100 font-semibold border-0" value="{{ trans('messages.login') }}">
    </div>
    {{ html()->closeModelForm() }}

</div>
@endsection
