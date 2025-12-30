
<?php $__env->startSection('content'); ?>
    <?php use App\Constants\Constant; ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <div class="header d-flex align-items-center">
        <h1 class="page-header">
            <?php echo e(trans('messages.edit')); ?> <?php echo e($title); ?>

        </h1>
        <ol class="breadcrumb ms-auto mb-0">
            <li><a href="<?php echo e(Route('admin.dashboard')); ?>"><em class="fa fa-dashboard"></em>
                    <?php echo e(trans('messages.dashboard')); ?></a></li>
            <li class="active"><a href="<?php echo e(Route($listRoute)); ?>"><em class="fa fa-ticket"></em>
                    <?php echo e($title); ?> <?php echo e(trans('messages.list')); ?> </a></li>
            <li class="active"><?php echo e(trans('messages.add')); ?></li>
        </ol>
    </div>
    <div id="page-inner">
        <div class="panel panel-default">
            <div class="panel-body">
                <?php echo e(html()->modelForm($model, 'POST')->route('admin.serviceprovider.update',$model)
            ->attributes(['id'=>'ServiceProviderForm','autocomplete' => 'off','enctype'=>'multipart/form-data'])->open()); ?>


              <?php echo $__env->make('admin.service-provider.form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php echo e(html()->closeModelForm()); ?>

            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.default_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/service-provider/edit.blade.php ENDPATH**/ ?>