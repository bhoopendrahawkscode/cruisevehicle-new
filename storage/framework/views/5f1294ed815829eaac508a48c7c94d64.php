
<?php $__env->startSection('content'); ?>

<?php  use \App\Constants\Constant; ?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<div class="header d-flex align-items-center">
    <h1 class="page-header">
        <?php echo e(trans('messages.add')); ?> <?php echo e(trans('messages.insurance_company')); ?>

    </h1>
    <ol class="breadcrumb ms-auto mb-0">
        <li><a href="<?php echo e(Route('admin.dashboard')); ?>"><em class="fa fa-dashboard"></em> <?php echo e(trans('messages.dashboard')); ?></a></li>
        <li><a href="<?php echo e(route('admin.insuranceCompany.list')); ?>"><?php echo e(trans('messages.insurance_company')); ?> <?php echo e(trans("messages.list")); ?></a></li>
        <li class="active"><?php echo e(trans("messages.add")); ?></li>
    </ol>
</div>
<div id="page-inner">
    <div class="panel panel-default">
        <div class="panel-body">
           <?php echo e(html()->modelForm(null, 'POST')->route('admin.insuranceCompany.save')
            ->attributes(['id'=>'insurance_companyForm','class'=>'form-horizontal','role'=>'form','autocomplete' => 'off'])->open()); ?>

           <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="full_name"><?php echo e(trans('messages.insuranceCompanyName')); ?> <span class="red_lab"> *</span></label>
                        <?php echo e(html()->text('full_name',null)->attributes(['class' => 'form-control',
                        'placeholder' => trans("messages.insuranceCompanyName")])); ?>

                        <?php if($errors->has('full_name')): ?>
                        <span class="invalid-feedback" role="alert">
                            <strong><?php echo e($errors->first('full_name')); ?></strong>
                        </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="insurance_company_employee_full_name"><?php echo e(trans('messages.employeeFullName')); ?> <span class="red_lab"> *</span></label>
                        <?php echo e(html()->text('insurance_company_employee_full_name',null)->attributes(['class' => 'form-control',
                        'placeholder' => trans("messages.employeeFullName")])); ?>

                        <?php if($errors->has('insurance_company_employee_full_name')): ?>
                        <span class="invalid-feedback" role="alert">
                            <strong><?php echo e($errors->first('insurance_company_employee_full_name')); ?></strong>
                        </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group clearValidation">
                        <label for="email"><?php echo e(trans('messages.email')); ?> <span class="red_lab"> *</span></label>
                        <?php echo e(html()->text('email',null)->attributes(['class' => 'form-control',
                        'placeholder' => trans("messages.email")])); ?>

                        <?php if($errors->has('email')): ?>
                        <span class="invalid-feedback" role="alert">
                            <strong><?php echo e($errors->first('email')); ?></strong>
                        </span>
                        <?php endif; ?>
                    </div>
                </div>

                
                <input type="hidden" name="role" value='6'>


            </div>
            <?php if(Constant::ALLOW_SUB_ADMIN_PASSWORD_CREATE): ?>
            <?php
            $isPasswordVisible = false;
            $isConfirmPasswordVisible = false;
            ?>
            <div class="row" <?php if($password_generation==Constant::SYSTEM_FACTOR) { ?> style="display:none" <?php } ?>>
                <div class="col-sm-6">
                    <div class="form-group clearValidation">
                        <label for="password"><?php echo e(trans('messages.password')); ?><span class="red_lab"> *</span></label>
                        <input class="form-control" id="password" placeholder="<?php echo e(trans('messages.enterPassword')); ?>" reqired="required" name="password" type="password" value="<?php echo e(old('password')); ?>">
                        <?php if($errors->has('password')): ?>
                        <span class="invalid-feedback" role="alert">
                            <strong><?php echo e($errors->first('password')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <em class="fa fa-eye form-control-feedback cp eye-icon-fa" id="togglePassword"></em>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group clearValidation">
                        <label for="confirm_password"><?php echo e(trans('messages.confirmPassword')); ?><span class="red_lab"> *</span></label>
                        <input class="form-control" id="confirm_password" placeholder="<?php echo e(trans('messages.enterConfirmPassword')); ?>" reqired="required" name="confirm_password" type="password" value="<?php echo e(old('confirm_password')); ?>">
                        <?php if($errors->has('confirm_password')): ?>
                        <span class="invalid-feedback" role="alert">
                            <strong><?php echo e($errors->first('confirm_password')); ?> </strong>
                        </span>
                        <?php endif; ?>
                        <em class="fa fa-eye form-control-feedback cp eye-icon-fa" id="togglePassword2"></em>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <br />

            <div class="form-group d-flex gap-3">
                <button type="submit" class="btn theme_btn bg_theme font-semibold border-0 fs-6 px-sm-5"><?php echo e(trans('messages.submit')); ?></button>
                <a href="<?php echo e(route('admin.insuranceCompany.list')); ?>" class="btn px-sm-5 font-semibold border_btn"><em class="icon-refresh"></em> <?php echo e(trans("messages.cancel")); ?></a>
            </div>
            <?php echo e(html()->closeModelForm()); ?>

        </div>
    </div>
</div>
<?php if(env('ENABLE_CLIENT_VALIDATION')): ?>
<script integrity="" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
      $(document).ready(function() {
            $('.select2').select2();
        });
    $(function() {
        // Form Validation
        $("#insurance_companyForm").validate({
            rules: {

                full_name: {
                    required: true,
                    minlength: 2,
                    maxlength: 60,
                    notNumber: true,
                    validName: true,
                },
                insurance_company_employee_full_name: {
                    required: true,
                    minlength: 2,
                    maxlength: 60,
                    notNumber: true,
                    validName: true,
                },
                email: {
                    required: true,
                    email: true,
                    maxlength: 100,
                    emailPattern: true
                },
            },
            messages: {
                full_name: {
                    minlength: "<?php echo e(trans('messages.min2Max60')); ?>",
                    maxlength: "<?php echo e(trans('messages.min2Max60')); ?>",
                    notNumber: "<?php echo e(trans('messages.notNumberMessage')); ?>"
                },
                insurance_company_employee_full_name: {
                    minlength: "<?php echo e(trans('messages.min2Max60')); ?>",
                    maxlength: "<?php echo e(trans('messages.min2Max60')); ?>",
                    notNumber: "<?php echo e(trans('messages.notNumberMessage')); ?>"
                },

            },
            errorClass: "help-inline",
            errorElement: "span",
            highlight: function(element, errorClass, validClass) {
                $(element).parents('.form-group').addClass('error');

            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).parents('.form-group').removeClass('error');
                $(element).parents('.form-group').addClass('success');
            },


        });
    });
</script>
<?php endif; ?>

<script type="text/javascript">
    $(document).ready(function() {

        var isPasswordVisible = false;
        var isConfirmPasswordVisible = false;

        document.getElementById("togglePassword").addEventListener("click", function() {
            var passwordInput = document.getElementById("password");
            isPasswordVisible = !isPasswordVisible; // Toggle the visibility state
            if (isPasswordVisible) {
                passwordInput.setAttribute("type", "text");
                document.getElementById("togglePassword").classList.remove("fa-eye");
                document.getElementById("togglePassword").classList.add("fa-eye-slash");
            } else {
                passwordInput.setAttribute("type", "password");
                document.getElementById("togglePassword").classList.remove("fa-eye-slash");
                document.getElementById("togglePassword").classList.add("fa-eye");
            }
        });

        document.getElementById("togglePassword2").addEventListener("click", function() {
            var confirm_passwordInput = document.getElementById("confirm_password");
            isConfirmPasswordVisible = !isConfirmPasswordVisible; // Toggle the visibility state
            if (isConfirmPasswordVisible) {
                confirm_passwordInput.setAttribute("type", "text");
                document.getElementById("togglePassword2").classList.remove("fa-eye");
                document.getElementById("togglePassword2").classList.add("fa-eye-slash");
            } else {
                confirm_passwordInput.setAttribute("type", "password");
                document.getElementById("togglePassword2").classList.remove("fa-eye-slash");
                document.getElementById("togglePassword2").classList.add("fa-eye");
            }
        });
    });
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.default_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/insuranceCompany/add.blade.php ENDPATH**/ ?>