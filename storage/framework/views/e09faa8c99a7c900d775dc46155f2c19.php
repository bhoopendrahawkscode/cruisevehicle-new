

<?php $__env->startSection('content'); ?>
<div class="panel-heading">
      <div class="card-title">
          <div class="title"><?php echo e(trans('messages.resetPassword')); ?></div>
      </div>
  </div>
<div class="panel-body">
    <?php echo e(html()->modelForm(null, 'POST')->route('saveResetPassword')
    ->attributes(['id'=>"resetPassword",'class'=>'pwdForm','role'=>'form','autocomplete' => 'off'])->open()); ?>

     <?php echo e(html()->hidden('validate_string',$validate_string)); ?>

		<div class="form-group has-feedback clearValidation">
            <?php echo e(html()->text('new_password')
            ->attributes(['id'=>'new_password','class' => 'form-control ',
            'placeholder' => trans("messages.newPassword"),'required'=>'required','type'=>'password','autocomplete'=>'off'])); ?>

			<?php if($errors->has('new_password')): ?>
				<span class="invalid-feedback login-error-font-size" role="alert">
					<strong><?php echo e($errors->first('new_password')); ?></strong>
				</span>
			<?php endif; ?>

        <span id="togglePassword2"><em class="fa fa-eye-slash form-control-feedback eye-icon-fa password-eye1"  ></em></span>
		</div>
		<div class="form-group has-feedback clearValidation">
           <?php echo e(html()->text('new_password_confirmation')
           ->attributes(['id'=>'new_password_confirmation','class' => 'form-control ',
           'placeholder' => trans("messages.confirmNewPassword"),'required'=>'required','type'=>'password','autocomplete'=>'off'])); ?>

			<?php if($errors->has('new_password_confirmation')): ?>
				<span class="invalid-feedback login-error-font-size" role="alert">
					<strong><?php echo e($errors->first('new_password_confirmation')); ?></strong>
				</span>
			<?php endif; ?>
            <span id="togglePassword"><em class="fa fa-eye-slash form-control-feedback eye-icon-fa password-eye"  ></em></span>
		</div>
		<div class="row">
        <div class="col-xs-4">
           <input type="submit" class="btn btn-primary btn-block btn-flat" value="<?php echo e(trans('messages.submit')); ?>">
        </div>
      </div>


      <?php if(env('ENABLE_CLIENT_VALIDATION')): ?>
		<script>
			$(function() {
				$("#resetPassword").validate(
					{
						rules: {
							'new_password': {
								required: true,
								rule_password: true,
								maxlength: 30
							},
							'new_password_confirmation': {
								required: true,
								rule_password: true,
								maxlength: 30,
								equalTo : "#new_password",
							},

						},
						messages: {
							'new_password_confirmation':  {
								equalTo: "<?php echo e(trans('messages.newPassConfirmPassNotMatched')); ?> ",
							}
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
                const password = $('#new_password_confirmation');
                const type = password.prop('type') === 'password' ? 'text' : 'password';
                const cls = password.prop('type') === 'password' ? 'fa-eye' : 'fa-eye-slash';
                password.prop('type', type);
                $('.password-eye').removeClass('fa-eye fa-eye-slash').addClass(cls);
              });

              $('#togglePassword2').click(function() {
                const password = $('#new_password');
                const type = password.prop('type') === 'password' ? 'text' : 'password';
                const cls = password.prop('type') === 'password' ? 'fa-eye' : 'fa-eye-slash';
                password.prop('type', type);
                $('.password-eye1').removeClass('fa-eye fa-eye-slash').addClass(cls);
              });


            });
          </script>

<?php echo e(html()->closeModelForm()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.admin-login', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/login/reset_password.blade.php ENDPATH**/ ?>