
<?php $__env->startSection('content'); ?>
<?php
use App\Services\CommonService;

?>
<div class="header d-flex align-items-center">
    <h1 class="page-header"><?php echo e(trans("messages.view")); ?> <?php echo e(trans('messages.user')); ?></h1>
    <ol class="breadcrumb ms-auto mb-0">
        <li><a href="<?php echo e(Route('admin.dashboard')); ?>"><em class="fa fa-dashboard"></em>
                <?php echo e(trans("messages.dashboard")); ?></a></li>
        <li><a href="<?php echo e(route('admin.listUsers')); ?>"> <?php echo e(trans('messages.user')); ?> <?php echo e(trans('messages.list')); ?></a></li>
        <li class="active"><?php echo e(trans("messages.view")); ?></li>
    </ol>
</div>
<div id="page-inner">
    <div class=" panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="form-group d-flex gap-3">
                    <a href="<?php echo e(route('admin.listUsers')); ?>" class="btn px-sm-5 font-semibold border_btn">
                        <em class="icon-refresh"></em> <?php echo e(trans("messages.back")); ?></a>
                </div>
            </div>


            <div class="row mt-3">
                <div class="col-2 ">
                    <strong><?php echo e(trans('messages.fullName')); ?></strong>
                </div>
                <div class="col-4 text-start">
                    <?php echo e($data['full_name']); ?>

                </div>
                <div class="col-2 ">
                    <strong><?php echo e(trans('messages.email')); ?></strong>
                </div>
                <div class="col-4 text-start">
                    <?php echo e($data['email']); ?>

                </div>
            </div>

            <div class="row mt-3">
                <div class="col-2 ">
                    <strong><?php echo e(trans('messages.phoneNo')); ?></strong>
                </div>
                <div class="col-4 text-start">
                    <?php echo e(!empty($data->country_code) ? '+'.$data->country_code : '-'); ?>

                    <?php echo e(!empty($data->mobile_no) ? $data->mobile_no : '-'); ?>

                </div>
                <div class="col-2 ">
                    <strong><?php echo e(trans('messages.createdOn')); ?></strong>
                </div>
                <div class="col-4 text-start">
                    <?php echo e(date(Config::get('constants.ADMIN_LISTING_DATE_FORMAT'), strtotime($data->created_at))); ?>

                </div>
            </div>


        </div>
    </div>
</div>
<?php echo $__env->make('admin.tabSelected', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.default_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/user/view.blade.php ENDPATH**/ ?>