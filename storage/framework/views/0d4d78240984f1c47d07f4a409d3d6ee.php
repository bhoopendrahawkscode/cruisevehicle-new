<?php
use App\Services\GeneralService;
$link_limit = 6;
?>


    <div class="d-flex align-items-center">
        <div style="margin-right:15px;">
            <?php echo e(html()->modelForm(null, 'POST')->route("paginationLimitChange")
            ->attributes(['id'=>'paginationLimitChange','class'=>'form-inline','role'=>'form','autocomplete' => 'off','onSubmit'=>'return checkDate();'])->open()); ?>


             <?php echo e(html()->select('status',['5' => "Show 5 Per Page",'10' => "Show 10 Per Page",'20' => "Show 20 Per Page",'50' => "Show 50 Per Page",'100' => "Show 100 Per Page"],
             GeneralService::getSettings('pageLimit'))
             ->attributes(['class' => 'form-control form-control-dropdown','onChange'=>"document.getElementById('paginationLimitChange').submit();"])); ?>


            <?php echo e(html()->closeModelForm()); ?>

        </div>

        <div>
            <?php echo e(trans("messages.showing")); ?> <?php echo e(($paginator->currentpage()-1)*$paginator->perpage()+1); ?>

                <?php echo e('to'); ?> <?php echo e($paginator->currentpage()*$paginator->perpage()); ?>

                <?php echo e('of'); ?> <?php echo e($paginator->total()); ?> <?php echo e(trans("messages.entries")); ?>

        </div>
        <?php if($paginator->lastPage() > 1): ?>
            <ul class="pagination justify-content-end ms-auto my-0 me-0">
                <li class="page-item <?php echo e(($paginator->currentPage() == 1) ? ' disabled' : ''); ?>">
                    <a class="page-link" href="<?php echo e(($paginator->currentPage() == 1) ? 'javascript:void(0)' : $paginator->url($paginator->currentPage()-1)); ?>"><?php echo e(trans("messages.previous")); ?></a>
                </li>

                <?php if($paginator->currentPage() > $link_limit + 1): ?>
                <li class="page-item">
                    <a class="page-link" href="<?php echo e($paginator->url(1)); ?>">1</a>
                </li>
                <?php if($paginator->currentPage() > $link_limit + 2): ?>
                <li class="page-item disabled"><span class="page-link">...</span></li>
                <?php endif; ?>
                <?php endif; ?>

                <?php for($i = max(1, $paginator->currentPage() - $link_limit); $i <= min($paginator->lastPage(), $paginator->currentPage() + $link_limit); $i++): ?>
                    <?php
                    $half_total_links = floor($link_limit / 2);
                    $from = $paginator->currentPage() - $half_total_links;
                    $to = $paginator->currentPage() + $half_total_links;
                    $shouldShowEllipsis = $paginator->lastPage() > $link_limit && ($i < $from || $i > $to);
                    ?>
                    <?php if($shouldShowEllipsis && $i == 2): ?>
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                    <?php endif; ?>
                    <?php if($shouldShowEllipsis && $i == $paginator->lastPage() - 1): ?>
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                    <?php endif; ?>
                    <li class="page-item <?php echo e(($paginator->currentPage() == $i) ? ' active' : ''); ?>">
                        <a class="page-link" href='<?php echo e($paginator->url("$i")); ?>'><?php echo e($i); ?></a>
                    </li>
                    <?php endfor; ?>

                    <?php if($paginator->currentPage() < $paginator->lastPage() - $link_limit + 1): ?>
                        <?php if($paginator->currentPage() < $paginator->lastPage() - $link_limit): ?>
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                            <?php endif; ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo e($paginator->url($paginator->lastPage())); ?>"><?php echo e($paginator->lastPage()); ?></a>
                            </li>
                            <?php endif; ?>

                            <li class="page-item <?php echo e(($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : ''); ?>">
                                <a class="page-link" href="<?php echo e(($paginator->currentPage() == $paginator->lastPage()) ? 'javascript:void(0)' : $paginator->url($paginator->currentPage()+1)); ?>"><?php echo e(trans("messages.next")); ?></a>
                            </li>
            </ul>
            <?php endif; ?>
    </div>




<?php /**PATH /var/www/html/resources/views/pagination/default.blade.php ENDPATH**/ ?>