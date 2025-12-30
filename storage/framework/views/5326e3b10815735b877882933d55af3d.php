<div class="panel panel-default">
    <div class="panel-body">
        <div class="table-responsive">
          <?php echo e($table); ?>

            <!-- pagination start -->
            <div class="box-footer clearfix">

                <?php echo $__env->make('pagination.default', ['paginator' => $result], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
            <!-- pagination end -->
        </div>
    </div>
</div><?php /**PATH /var/www/html/resources/views/components/admin/table-list-structure.blade.php ENDPATH**/ ?>