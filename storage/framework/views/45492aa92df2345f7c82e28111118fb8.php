
<?php $__env->startSection('content'); ?>
<?php use App\Services\GeneralService; ?>
<div class="header d-flex align-items-center">
	<h1 class="page-header"><?php echo e($title); ?> <?php echo e(trans("messages.list")); ?></h1>
	<ol class="breadcrumb ms-auto mb-0">
		<li><a href="<?php echo e(Route('admin.dashboard')); ?>">
				<em class="fa fa-dashboard"></em>
				<?php echo e(trans("messages.dashboard")); ?></a></li>
		<li class="active"><?php echo e($title); ?> <?php echo e(trans("messages.list")); ?></li>
	</ol>
</div>
<div id="page-inner">
	<div class="panel panel-default">
		<div class="panel-body form_mobile">
            <?php echo e(html()->modelForm(null, 'GET')->route($listRoute)
             ->attributes(['id'=>$formId,'class'=>'form-inline','role'=>'form','autocomplete' => 'off','onSubmit'=>'return checkDate();'])->open()); ?>

            <?php echo e(html()->hidden('display')); ?>

			<div class="form_row">
                <div class="form_fields d-flex mb-0 gap-2">
					<div class="form-group mb-0">
                        <?php echo e(html()->text('name',((isset($searchVariable['name'])) ?
						$searchVariable['name'] : ''))
                        ->attributes(['class' => 'form-control',
                        'placeholder' => trans("messages.searchBy")." ".trans('messages.name')])); ?>

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
					<button title="<?php echo e(trans('messages.search')); ?>" type="submit" class="btn theme_btn bg_theme btn-sm btnIcon"><em class="fa-solid fa-magnifying-glass"></em></button>
					<a title="<?php echo e(trans('messages.reset')); ?>" href="<?php echo e(Route($listRoute)); ?>" class="btn btn-sm border_btn btnIcon">
						<em class='fa fa-refresh '></em>
                    </a>
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
				<table class="<?php if(!$result->isEmpty()): ?> table table-striped table-hover <?php endif; ?>" id="sortable">
					<caption><?php echo e($title); ?> <?php echo e(trans("messages.list")); ?></caption>
					<?php if(!$result->isEmpty()): ?>
					<thead>
						<tr>
							<th scope="col" class="w-5p">
								<?php echo e(trans("messages.sNo")); ?>

							</th>
							<th scope="col" class="w-45p">
								<?php echo e(link_to_route(
                                                $listRoute,
                                                trans("messages.name"),
                                                array(
                                                    'sortBy' => 'name',
                                                    'order' => ($sortBy == 'name' && $order == 'desc') ? 'asc' : 'desc'
                                                ),
                                                $query_string
                                            )); ?>

								<?php getSortIcon($sortBy,$order,'name') ?>
							</th>
							<th scope="col" class="w-20p">
								<?php echo e(link_to_route(
                                                $listRoute,
                                                trans("messages.updatedOn"),
                                                array(
                                                    'sortBy' =>  $mainTable.'.updated_at',
                                                    'order' => ($sortBy ==  $mainTable.'.updated_at' && $order == 'desc') ? 'asc' : 'desc'
                                                ),
                                                $query_string
                                            )); ?>

								<?php getSortIcon($sortBy,$order,$mainTable.'.updated_at') ?>
							</th>
							<th scope="col" class="w-10p">
								<?php echo e(trans("messages.action")); ?>

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
							<td> <?php echo e($i); ?>

								<?php
								$i++;
								?>
							</td>
							<td class="breakAll">
								<?php echo e($record->name); ?>

							</td>

							<td>
							<?php echo e(date(Config::get('constants.ADMIN_LISTING_DATE_FORMAT'), strtotime($record->updated_at))); ?>


							</td>
							<td>
								<?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->hasPermission($editPermission))) : ?>
								<a title="<?php echo e(trans('messages.edit')); ?>" href="<?php echo e(URL::to('admin/'.$listUrl.'/edit/'.$record->id)); ?>" class="btn btn-primary">
									<em class="fa fa-pencil"></em>
								</a>
								<?php endif; ?>


							</td>
						</tr>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						<?php else: ?>
						<tr>
							<td colspan="6" class="text-center noRecord"><strong>
									<?php echo e(trans("messages.noRecordFound")); ?> </strong></td>
						</tr>
						<?php endif; ?>
					</tbody>
				</table>
				<div class="box-footer clearfix">
					<?php echo $__env->make('pagination.default', ['paginator' => $result], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
				</div>
			</div>
		</div>
	</div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.default_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/cms/index.blade.php ENDPATH**/ ?>