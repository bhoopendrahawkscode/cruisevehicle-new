
<?php $__env->startSection('content'); ?>
<?php  use \App\Constants\Constant; ?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<div class="header d-flex align-items-center">
    <h1 class="page-header">
        <?php echo e(trans('messages.editinsurance_company')); ?>

    </h1>
    <ol class="breadcrumb ms-auto mb-0">
        <li><a href="<?php echo e(Route('admin.dashboard')); ?>"> <em class="fa fa-dashboard"></em><?php echo e(trans('messages.dashboard')); ?></a></li>
        <li><a href="<?php echo e(route('admin.insuranceCompany.list')); ?>"><?php echo e(trans('messages.insurance_company')); ?> <?php echo e(trans("messages.list")); ?></a></li>
        <li class="active"><?php echo e(trans("messages.edit")); ?></li>
    </ol>
</div>
<div id="page-inner">
    <div class="panel panel-default">
        <div class="panel-body">
            <?php echo e(html()->modelForm($data, 'POST', URL::to('/').'/admin/insurance-management/insurance-company/update/'.$data->id)
            ->attributes(['role' =>'form','id'=>"insurance_companyForm",'autocomplete' => 'off'])->open()); ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="full_name"><?php echo e(trans('messages.fullName')); ?> <span class="red_lab"> *</span></label>
                        <?php echo e(html()->text('full_name',null)->attributes(['class' => 'form-control',
                         'placeholder' => trans("messages.fullName")])); ?>

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
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group d-flex gap-3">
                        <button type="submit" class="btn theme_btn bg_theme font-semibold border-0 fs-6 px-sm-5"><?php echo e(trans('messages.submit')); ?></button>
                        <a href="<?php echo e(route('admin.insuranceCompany.list')); ?>" class="btn px-sm-5 font-semibold border_btn"><em class="icon-refresh"></em> <?php echo e(trans("messages.cancel")); ?></a>
                    </div>
                </div>
            </div>
            <?php echo e(html()->closeModelForm()); ?>

        </div>
    </div>
</div>
<script integrity="" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.select2').select2();

        $('.multiselect-search').on('keyup', function() {
            var searchValue = $(this).val().toLowerCase();
            var options = $('#mySelect option');

            // Hide select all if no matching record found
            var selectAllOption = $('.multiselect-all');
            if (selectAllOption.length) {
            var visibleOptions = options.filter(function() {
                return $(this).text().toLowerCase().indexOf(searchValue) > -1;
            });
            if (visibleOptions.length > 0) {
                selectAllOption.show();
            } else {
                selectAllOption.hide();
            }
            }
        });
        if($('#mySelect option:checked').length==1){
            setTimeout(function(){ $('.multiselect-selected-text').html("1 Selected") }, 10);
         }

    });
</script>
<?php if(env('ENABLE_CLIENT_VALIDATION')): ?>
<script>
    $(function() {
        // Form Validation
        $("#insurance_companyForm").validate({
            rules: {

                full_name: {
                    required: true,
                    minlength: 2,
                    maxlength: 60,
                    notNumber: true,
                    validName: true
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
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.default_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/insuranceCompany/edit.blade.php ENDPATH**/ ?>