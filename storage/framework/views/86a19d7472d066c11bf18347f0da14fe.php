
<?php $__env->startSection('content'); ?>
<div class="panel-heading">
      <div class="card-title">
          <div class="title"><?php echo e(trans('messages.login')); ?></div>
      </div>
  </div>
<div class="panel-body">
  <?php echo e(html()->modelForm(null, 'POST')->route('admin-login')
  ->attributes(['id'=>"loginForm",'class'=>'pwdForm','role'=>'form','autocomplete' => 'off'])->open()); ?>


    <div class="form-group has-feedback">
      <input id="username" type="email"  class="form-control <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="username" autocomplete="username" placeholder="<?php echo e(trans('messages.email')); ?>" value="<?php echo e(old('username', isset($_COOKIE['admin_email']) ? $_COOKIE['admin_email'] : '')); ?>" required>
      <?php if($errors->has('username')): ?>
        <span class="invalid-feedback login-error-font-size" role="alert">
          <strong><?php echo e($errors->first('username')); ?></strong>
        </span>
      <?php endif; ?>
      <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
    </div>
    <div class="form-group has-feedback clearValidation">
      <input id="password" type="password" required class=" form-control " name="password" autocomplete="current-password" placeholder="<?php echo e(trans('messages.password')); ?>" value="<?php echo e(old('admin_password') ?? (isset($_COOKIE['admin_password']) ? $_COOKIE['admin_password'] : '')); ?>">

	  <?php if($errors->has('password')): ?>
        <span class="invalid-feedback login-error-font-size" role="alert">
          <strong><?php echo e($errors->first('password')); ?></strong>
        </span>
      <?php endif; ?>

	  <span id="togglePassword">  <em class="fa fa-eye-slash form-control-feedback eye-icon-fa password-eye"  ></em></span>

         <?php if(env('ENABLE_CLIENT_VALIDATION')): ?>
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
        <?php endif; ?>
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
              <span class="custom_check font-semibold" for="remember"><?php echo e(trans('messages.rememberMe')); ?> &nbsp; <input type="checkbox" name="remember" value="" <?php echo e(isset($_COOKIE['remember_me']) ? 'checked' : ''); ?>><span class="check_indicator">&nbsp;</span></span>
          </div>
        </div>
      </div>
      <!-- /.col -->
      <div class="col-xs-4 form-group">
      <a href="<?php echo e(route('forgotPassword')); ?>" class="mt-0"><?php echo e(trans('messages.iForgotMyPassword')); ?> </a><br>

      </div>
      <!-- /.col -->
    </div>
    <div class="text-center mt-4">
      <input type="submit" class="btn theme_btn bg_theme w-100 font-semibold border-0" value="<?php echo e(trans('messages.login')); ?>">
    </div>
    <?php echo e(html()->closeModelForm()); ?>


</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.admin-login', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/login/admin-login.blade.php ENDPATH**/ ?>