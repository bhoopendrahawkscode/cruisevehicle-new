
<?php $__env->startSection('content'); ?>
<script type="text/javascript" integrity="" src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.0.1/ckeditor.js"></script>
<?php
use App\Services\CommonService;

?>
<div class="header d-flex align-items-center">
    <h1 class="page-header"><?php echo e(trans("messages.edit")); ?> <?php echo e($title); ?></h1>
    <ol class="breadcrumb ms-auto mb-0">
        <li><a href="<?php echo e(Route('admin.dashboard')); ?>"><em class="fa fa-dashboard"></em>
                <?php echo e(trans("messages.dashboard")); ?></a></li>
        <li><a href="<?php echo e(route($listRoute)); ?>"><?php echo e($title); ?></a></li>
        <li class="active"><?php echo e(trans("messages.edit")); ?></li>
    </ol>
</div>
<div id="page-inner">
    <div class=" panel panel-default">
        <div class="panel-body">
            <?php echo e(html()->modelForm($data, 'POST', URL::to('/').'/admin/'.$listUrl.'/update/'.$data['recordId'])
            ->attributes(['id'=>$formId,'autocomplete' => 'off','enctype'=>'multipart/form-data'])->open()); ?>

            <?php echo $__env->make('admin.cms.form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <div class="form-group d-flex gap-3">
                <button type="submit" id="btnSubmit" class="btn theme_btn bg_theme font-semibold border-0 fs-6 px-sm-5">
                    <?php echo e(trans("messages.submit")); ?></button>
                <a href="<?php echo e(route($listRoute)); ?>" class="btn px-sm-5 font-semibold border_btn">
                    <em class="icon-refresh"></em> <?php echo e(trans("messages.cancel")); ?></a>
            </div>

            <?php echo e(html()->closeModelForm()); ?>

        </div>
    </div>
</div>
<?php echo $__env->make('admin.tabSelected', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.default_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/cms/edit.blade.php ENDPATH**/ ?>