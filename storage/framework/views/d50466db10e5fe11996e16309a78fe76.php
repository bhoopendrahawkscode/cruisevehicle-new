
<?php $__env->startSection('content'); ?>
<style type="text/css">
.show_more_css{
    color: white !important;
}
.show_more_css {
    display: inline-block;
    margin-top: 5px;
    word-wrap: normal; /* Prevents text breaking inside the button */
}

.show-more .excerpt {
    white-space: nowrap; /* Prevents breaking inside the excerpt */
    overflow: hidden;
    text-overflow: ellipsis;
    display: inline-block;
    max-width: 100%; /* Adjust to fit within the available table space */
}

.show-more .more-content {
    white-space: normal; /* Allows the full content to wrap properly */
    word-wrap: break-word; /* Breaks long words if necessary */
}

.show-more-text, .show-less-text {
    cursor: pointer;
}

</style>
<?php use App\Services\GeneralService; ?>
<div class="header d-flex align-items-center">
    <h1 class="page-header">
    <?php echo e(trans('messages.notificationHistory')); ?>

    </h1>
    <ol class="breadcrumb ms-auto mb-0">
        <li><a href="<?php echo e(Route('admin.dashboard')); ?>"><em class="fa fa-dashboard"></em> <?php echo e(trans('messages.dashboard')); ?></a></li>
        <li class="active"><?php echo e(trans('messages.notificationHistory')); ?></li>
    </ol>
</div>


<div id="page-inner">
    <div class="panel panel-default">
		<div class="panel-body form_mobile">
             <?php echo e(html()->modelForm(null, 'GET')->route('admin.ListNotification')
             ->attributes(['class'=>'form-inline','role'=>'form','autocomplete' => 'off','onSubmit'=>'return checkDate();'])->open()); ?>

            <?php echo e(html()->hidden('display')); ?>

			<div class="form_row">
				<div class="form_fields d-flex mb-0 gap-2">
					<div class="form-group mb-0">
                        <?php echo e(html()->text('title',((isset($searchVariable['title'])) ?
						$searchVariable['title'] : ''))
                        ->attributes(['class' => 'form-control',
                        'placeholder' => trans('Search by title')])); ?>

					</div>
					
					<div class="form-group mb-0 calendarIcon">
                        <?php echo e(html()->text('from',((isset($searchVariable['from'])) ?
                        $searchVariable['from'] : ''))
                        ->attributes(['class' => 'form-control datepicker','onkeydown'=>'return false;',
                        'placeholder' => trans('messages.fromDate')])); ?>

					</div>
					<div class="form-group mb-0 calendarIcon">
						<?php echo e(html()->text('to',((isset($searchVariable['to'])) ?
                        $searchVariable['to'] : ''))
                        ->attributes(['class' => 'form-control datepicker','onkeydown'=>'return false;',
                        'placeholder' => trans('messages.toDate')])); ?>

					</div>
				</div>
				 <div class="form-action ">
                    <button type="submit" class="btn theme_btn bg_theme btn-sm btnIcon" title="<?php echo e(trans('messages.search')); ?>"><em class="fa-solid fa-magnifying-glass"></em> </button>
                    <a href="<?php echo e(Route('admin.ListNotification')); ?>" class="btn btn-sm border_btn btnIcon" title="<?php echo e(trans('messages.reset')); ?>"><em class='fa fa-refresh '></em> </a>
               
                    <div class="form-action_status">

                    </div>
                </div>
			</div>
            <?php echo e(html()->closeModelForm()); ?>

		</div>
	</div>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="table-responsive">
                <table class="<?php if(!$result->isEmpty()): ?> table table-striped table-hover <?php endif; ?>">
                    <caption><?php echo e(trans('messages.notificationList')); ?></caption>
                    <?php if(!$result->isEmpty()): ?>
                    <thead>
                        <tr>
                            <th scope="col" class="w-5p">
                                <?php echo e(trans('messages.sNo')); ?>

                            </th>
                            <th scope="col">
                                <?php echo e(link_to_route(
                                    'admin.ListNotification',
                                    trans('messages.title'),
                                    [
                                        'sortBy' => 'title',
                                        'order' => $sortBy == 'title' && $order == 'desc' ? 'asc' : 'desc',
                                    ],
                                    $query_string,
                                )); ?>

                                <?php getSortIcon($sortBy,$order,'title') ?>
                            </th>
                            <th scope="col">
                                <?php echo e(link_to_route(
                                    'admin.ListNotification',
                                    trans('messages.message'),
                                    [
                                        'sortBy' => 'description',
                                        'order' => $sortBy == 'description' && $order == 'desc' ? 'asc' : 'desc',
                                    ],
                                    $query_string,
                                )); ?>

                                <?php getSortIcon($sortBy,$order,'description') ?>
                            </th>
                            <th scope="col">
                                <?php echo e(link_to_route(
                                    'admin.ListNotification',
                                    trans('messages.sendTo'),
                                    [
                                        'sortBy' => 'send_to',
                                        'order' => $sortBy == 'send_to' && $order == 'desc' ? 'asc' : 'desc',
                                    ],
                                    $query_string,
                                )); ?>

                                <?php getSortIcon($sortBy,$order,'send_to') ?>
                            </th>
                            <th scope="col" class="w-15p">
                                <?php echo e(link_to_route(
                                        'admin.ListNotification',
                                        "Created Date",
                                        [
                                            'sortBy' => 'created_at',
                                            'order' => $sortBy == 'created_at' && $order == 'desc' ? 'asc' : 'desc'
                                        ],
                                        $query_string
                                    )); ?>

                                    <?php getSortIcon($sortBy,$order,'created_at') ?>
                            </th>
                        </tr>
                    </thead>
                    <?php endif; ?>
                        <tbody>
                        <?php
                        $i = 1;
                        if (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) {
                        $page = $_REQUEST['page'];
                        $limit = GeneralService::getSettings('pageLimit');
                        $i = ($page - 1) * $limit + 1;
                        }
                        ?>
                        <?php if(!$result->isEmpty()): ?>
                        <?php $__currentLoopData = $result; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($i); ?>

                                <?php
                                $i++;
                                ?>
                            </td>
                            <td>
                                <?php  echo   $record->title;
                                ?>
                            </td>
                            <td>
                                <span class="show-more" title="<?php echo e(trans('messages.view')); ?>">
                                    <span class="excerpt"><?php echo e(excerpt($record->description, 50)); ?></span>
                                    <span class="more-content" style="display: none;"><?php echo e($record->description); ?></span>
                                    <span class="show-more-text show_more_css btn btn-primary" style="display: <?php echo e(strlen($record->description) > 50 ? 'inline-block' : 'none'); ?>">Show more</span>
                                    <span class="show-less-text show_more_css btn btn-primary" style="display: none;">Show less</span>
                                </span>
                            </td>
                            
                            <td>
                                <?php echo e($record->send_to); ?>

                            </td>
                            <td>
                                <?php echo e(date(Config::get('constants.ADMIN_LISTING_DATE_FORMAT'), strtotime($record->created_at))); ?>

                            </td>

                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center noRecord"><strong><?php echo e("No record found!"); ?></strong></td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <!-- pagination start -->
                <div class="box-footer clearfix">

                    <?php echo $__env->make('pagination.default', ['paginator' => $result], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
                <!-- pagination end -->
            </div>
        </div>
    </div>
</div>
<script>
    
    $(document).ready(function () {
        $('.show-more').click(function(e){
            e.preventDefault();
            var $moreContent = $(this).find('.more-content');
            var $excerpt = $(this).find('.excerpt');
            var $showMoreText = $(this).find('.show-more-text');
            var $showLessText = $(this).find('.show-less-text');
            $excerpt.toggle();
            $moreContent.toggle();
            $showMoreText.toggle();
            $showLessText.toggle();
        });
    });


</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.default_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/user/notification_history.blade.php ENDPATH**/ ?>