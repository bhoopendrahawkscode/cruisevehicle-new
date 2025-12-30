
<?php $__env->startSection('content'); ?>
<?php use App\Services\GeneralService; ?>
<div class="header d-flex align-items-center">
	<h1 class="page-header">
		<?php echo e($title); ?> <?php echo e(trans("messages.list")); ?>

	</h1>
	<ol class="breadcrumb ms-auto mb-0">
		<li><a href="<?php echo e(Route('admin.dashboard')); ?>">
				<em class="fa fa-dashboard"></em>
				<?php echo e(trans("messages.dashboard")); ?></a></li>
		<li class="active"><?php echo e($title); ?></li>
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
                        'placeholder' => trans("messages.question_answer")])); ?>

					</div>
					<div class="form-group mb-0 w-20p">
                        <?php echo e(html()->select('status',['1' => trans('messages.active'), '0' => trans('messages.inActive')],
						((isset($searchVariable['status'])) ? $searchVariable['status'] : ''))
                        ->attributes(['class' => 'form-control form-control-dropdown'])->placeholder(trans("messages.status"))); ?>

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
						<?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->hasPermission('FAQ_ADD'))) : ?>
						<a title="<?php echo e(trans('messages.addNew')); ?> <?php echo e($title); ?> " href="<?php echo e(Route($addRoute)); ?>" class="btn theme_btn bg_theme btn-sm py-2 btnIcon">
							<em class='fa fa-add '></em>
                        </a>
						<?php endif; ?>
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
							<th scope="col">
								<?php echo e(trans("messages.sNo")); ?>

							</th>
							<th scope="col" class="w-40p">
								<?php echo e(link_to_route(
										$listRoute,
										trans("messages.question"),
										array(
										'sortBy' => 'question',
										'order' => ($sortBy == 'question' && $order == 'desc') ? 'asc' : 'desc'
										),
										$query_string
										)); ?>

								<?php getSortIcon($sortBy,$order,'question') ?>
							</th>
							<th scope="col" class="w-20p">
								<?php echo e(trans("messages.answer")); ?>

							</th>
							<th scope="col">
								<?php echo e(link_to_route(
										$listRoute,
										trans("messages.updatedOn"),
										array(

										'sortBy' => $mainTable.'.updated_at',
										'order' => ($sortBy == $mainTable.'.updated_at' && $order == 'desc') ? 'asc' : 'desc'
										),
										$query_string,
										array('class' => (($sortBy == $mainTable.'.updated_at' && $order == 'desc') ?
											'sorting desc' : (($sortBy == $mainTable.'.updated_at' && $order == 'asc') ?
											'sorting asc': 'sorting')) )
										)); ?>

							</th>
                            <th scope="col">
								<?php echo e(link_to_route(
										$listRoute,
										trans("messages.status"),
										array(

										'sortBy' => 'status',
										'order' => ($sortBy == 'status' && $order == 'desc') ? 'asc' : 'desc'
										),
										$query_string
										)); ?>

								<?php getSortIcon($sortBy,$order,'status') ?>
							</th>
							<th scope="col">
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
								<?php echo e(strip_tags(Str::limit($record->question, 200))); ?>

							</td>
							<td class="breakAll">
								<?php echo e(strip_tags(Str::limit($record->answer, 200))); ?>

							</td>
							<td>
								<?php echo e(date(Config::get('constants.ADMIN_LISTING_DATE_FORMAT'),strtotime($record->updated_at))); ?>

							</td>
                            <td>
                                <?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->hasPermission('FAQ_CHANGE_STATUS'))) : ?>
                                <?php if($record->status == 1): ?>
                                <div class="form-check form-switch pl-0">
                                    <input class="form-check-input status_any_item" data-onlabel="On" checked type="checkbox" role="switch"  data-id="<?php echo e($record->id); ?>"  status='0' >
                                    <span class="on"><?php echo e(trans('messages.active')); ?></span><span class="off"><?php echo e(trans('messages.inActive')); ?></span>
                                  </div>
                                <?php else: ?>
                                <div class="form-check form-switch pl-0">
                                    <input class="form-check-input status_any_item"  type="checkbox" role="switch"  data-id="<?php echo e($record->id); ?>"  status='1' >
                                    <span class="on"><?php echo e(trans('messages.active')); ?></span><span class="off"><?php echo e(trans('messages.inActive')); ?></span>
                                  </div>
                                <?php endif; ?>
                                <?php endif; ?>
                            </td>
							<td>
								<?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->hasPermission('FAQ_EDIT'))) : ?>
								<a title="<?php echo e(trans('messages.edit')); ?>" href="<?php echo e(URL::to('admin/'.$listUrl.'/edit/'.$record->id)); ?>" class="btn btn-primary">
									<em class="fa fa-pencil"></em>
								</a>
								<?php endif; ?>
								<?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->hasPermission('FAQ_DELETE'))) : ?>
                                <a class="btn btn-primary delete_any_item" data-id="<?php echo e(URL::to('admin/'.$listUrl.'/delete/' . $record->id)); ?>" title="<?php echo e(trans('Delete')); ?>" href="javascript::void(0);">
                                    <em class="fa fa-trash"></em>
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
<script>
    $(document).ready(function () {
        $('.delete_any_item').on('click', function() {
            status=$(this).attr('status');
            var location=$(this).attr('data-id');
            Swal.fire({
                title: "Are you sure you want to delete this?",
                text: "",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes"
            }).then((result) => {
                confirmed=false;
                if(result.isConfirmed){
                    window.location.href = location;
                }
            });
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.default_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/faq/index.blade.php ENDPATH**/ ?>