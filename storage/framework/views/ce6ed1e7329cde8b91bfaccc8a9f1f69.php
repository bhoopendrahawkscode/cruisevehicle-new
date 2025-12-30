
<?php $__env->startSection('content'); ?>

<?php use App\Constants\Constant;
 use App\Services\GeneralService;
?>
<div class="header d-flex align-items-center">
    <h1 class="page-header">
        <?php echo e(trans('messages.help_support')); ?>

    </h1>
    <ol class="breadcrumb ms-auto mb-0">
        <li><a href="<?php echo e(Route('admin.dashboard')); ?>"><em class="fa fa-dashboard"></em> <?php echo e(trans('messages.dashboard')); ?></a></li>
        <li class="active"><?php echo e(trans('messages.help_support')); ?></li>
    </ol>
</div>
<div id="page-inner">

    <div class="panel panel-default">
        <div class="panel-body">
            <?php echo e(html()->modelForm(null, 'GET')->route('admin.contactList')
            ->attributes(['class'=>'form-inline','role'=>'form','autocomplete' => 'off','onSubmit'=>'return checkDate();'])->open()); ?>


                <?php echo csrf_field(); ?>
                <?php echo e(html()->hidden('display')); ?>


            <div class="form_row">
                <div class="form_fields d-flex gap-2">
                    <div class="form-group mb-0 w-50p">
                        <?php echo e(html()->text('name',((isset($searchVariable['name'])) ?
						$searchVariable['name'] : ''))
                        ->attributes(['class' => 'form-control mw-100','autocomplete' => 'off',
                        'placeholder' => trans("messages.searchContactPlaceHolder")])); ?>

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
                <div class="form-action">
                    <button type="submit" class="btn theme_btn bg_theme border_btn btn-sm btnIcon" title="<?php echo e(trans('messages.search')); ?>"><em class="fa-solid fa-magnifying-glass"></em> </button>
                    <a href="<?php echo e(Route('admin.contactList')); ?>" class="btn btn-sm border_btn btnIcon" title="<?php echo e(trans('messages.reset')); ?>"><em class='fa fa-refresh '></em> </a>
                    
                </div>
            </div>
            <?php echo e(html()->closeModelForm()); ?>

        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="table-responsive">
                <table class="<?php if(!$result->isEmpty()): ?> table table-striped table-hover <?php endif; ?>">
                    <caption>Contact List</caption>
                    <?php if(!$result->isEmpty()): ?>
                    <thead>
                        <tr>
                            <th scope="col">
                                S.No.
                            </th>
                            <th scope="col">
                                <?php echo e(link_to_route(
                                                'admin.contactList',
                                                trans('User Id'),
                                                [
                                                    'sortBy' => 'user_id',
                                                    'order' => $sortBy == 'user_id' && $order == 'desc' ? 'asc' : 'desc'
                                                ],
                                                $query_string,
                                            )); ?>

                                <?php getSortIcon($sortBy,$order,'user_id') ?>
                            </th>
                            <th scope="col">
                                <?php echo e(link_to_route(
                                                'admin.contactList',
                                                trans('messages.contactName'),
                                                [
                                                    'sortBy' => 'name',
                                                    'order' => $sortBy == 'name' && $order == 'desc' ? 'asc' : 'desc'
                                                ],
                                                $query_string,
                                            )); ?>

                                <?php getSortIcon($sortBy,$order,'name') ?>
                            </th>
                            <th scope="col">
                                <?php echo e(link_to_route(
                                                'admin.contactList',
                                                trans('messages.contactPhone'),
                                                [
                                                    'sortBy' => 'mobile_no',
                                                    'order' => $sortBy == 'mobile_no' && $order == 'desc' ? 'asc' : 'desc'
                                                ],
                                                $query_string,
                                            )); ?>

                                <?php getSortIcon($sortBy,$order,'mobile_no') ?>
                            </th>

                            <th scope="col">
                                <?php echo e(trans('messages.email')); ?>

                            </th>
                            <th scope="col">
                                <?php echo e(trans('messages.subject')); ?>

                            </th>
                            <th scope="col">
                                <?php echo e(trans('messages.ticketStatus')); ?>

                            </th>
                            <th scope="col">
                                <?php echo e(trans('messages.status')); ?>

                            </th>
                            <th scope="col">
                                <?php echo e(link_to_route(
                                                'admin.listUsers',
                                                trans('messages.created_at'),
                                                [
                                                    'sortBy' => 'created_at',
                                                    'order' => $sortBy == 'created_at' && $order == 'desc' ? 'asc' : 'desc'
                                                ],
                                                $query_string,
                                            )); ?>


                                <?php getSortIcon($sortBy,$order,'created_at') ?>
                            </th>
                            <th scope="col">
                                <?php echo e(trans('messages.action')); ?>

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
                                <?php echo e(($record->user_id)); ?>

                            </td>
                            <td>
                                <?php echo e(($record->name)); ?>

                            </td>
                            <td>
                               <?php echo e(!empty($record->mobile_no) ? $record->mobile_no : '-'); ?>

                            </td>
                            <td>
                                <?php echo e($record->email); ?>

                            </td>
                            <td>
                                <?php echo e(($record->subject)); ?>

                            </td>
                            <td>
                                <?php if($record->ticket_status == 0): ?>
                                  <button class="btn btn-info"><?php echo e(trans('messages.ticketNew')); ?></button>
                                <?php elseif($record->ticket_status == 1): ?>
                                  <button class="btn btn-warning"><?php echo e(trans('messages.ticketProgress')); ?></button>
                                <?php else: ?>
                                <button class="btn btn-success"><?php echo e(trans('messages.ticketClose')); ?></button>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($record->status == 0): ?>
                                 <button class="btn btn-warning"><?php echo e(trans('messages.notReplied')); ?></button>
                                <?php else: ?>
                                <button class="btn btn-success"><?php echo e(trans('messages.replied')); ?></button>
                                <?php endif; ?>
                            </td>
                           <td>
                            <?php echo e(date(Config::get('constants.ADMIN_LISTING_DATE_FORMAT'), strtotime($record->created_at))); ?>

                            </td>
                            <?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->hasPermission('CONTACT_VIEW'))) : ?>
                            <td>
                                <?php if($record->ticket_status == 0 || $record->ticket_status == 1): ?>
                                <a  title="<?php echo e(trans('messages.view')); ?>" href="<?php echo e(URL::to('admin/contact-view/'.$record->id)); ?>" class="btn btn-warning">
                                    <em class="fa fa-reply"></em>
                                </a>
                                <?php else: ?>
                                <button class="btn btn-success"><?php echo e(trans('messages.ticketClose')); ?></button>
                                <?php endif; ?>
                            </td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center noRecord"><strong> <?php echo e(trans('messages.noRecordFound')); ?></strong></td>
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



<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.default_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/contact/contactList.blade.php ENDPATH**/ ?>