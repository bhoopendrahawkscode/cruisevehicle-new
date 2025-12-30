
<?php $__env->startSection('content'); ?>
<div class="panel-heading">
      <div class="card-title">
          <div class="title"><?php echo e(trans('messages.forgotPassword')); ?> </div>
      </div>
  </div>
<div class="panel-body">
    <?php echo e(html()->modelForm(null, 'POST')->route('sendPassword')
    ->attributes(['id'=>"forgotPassword",'class'=>'pwdForm','role'=>'form','autocomplete' => 'off'])->open()); ?>

		<div class="form-group has-feedback">
            <?php echo e(html()->email('email')->value(old('email'))->attributes(['class' => 'form-control', 'required' => 'required', 'placeholder' => trans('messages.email')])); ?>


			<?php if($errors->has('email')): ?>
				<span class="invalid-feedback login-error-font-size" role="alert">
					<strong><?php echo e($errors->first('email')); ?></strong>
				</span>
			<?php endif; ?>
		<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
		</div>
		<div class="row">
        <div class="col-xs-4">
           <input type="submit" class="btn theme_btn bg_theme w-100 font-semibold border-0" value="<?php echo e(trans('messages.submit')); ?>">
        </div>
      </div>
      <br/>
      <div class="text-center mt-4">
      <a href="<?php echo e(route('login')); ?>" class="text-center m-0">Back to login</a><br>
      </div>
      <?php echo e(html()->closeModelForm()); ?>

</div>
<?php if(env('ENABLE_CLIENT_VALIDATION')): ?>
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
<?php endif; ?>


 <?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.admin-login', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/login/forget_password.blade.php ENDPATH**/ ?>