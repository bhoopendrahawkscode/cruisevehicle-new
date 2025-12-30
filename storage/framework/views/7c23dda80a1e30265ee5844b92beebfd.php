
<?php $__env->startSection('content'); ?>

    <?php use App\Constants\Constant;
    use App\Services\GeneralService;
    ?>
    <div class="header d-flex align-items-center">
        <h1 class="page-header">
            <?php echo e(trans('messages.user')); ?> <?php echo e(trans('messages.list')); ?>

        </h1>
        <ol class="breadcrumb ms-auto mb-0">
            <li><a href="<?php echo e(Route('admin.dashboard')); ?>"><em class="fa fa-dashboard"></em>
                    <?php echo e(trans('messages.dashboard')); ?></a></li>
            <li class="active"><?php echo e(trans('messages.user')); ?> <?php echo e(trans('messages.list')); ?></li>
        </ol>
    </div>
    <div id="page-inner">

        <div class="panel panel-default">
            <div class="panel-body form_mobile">
                <?php echo e(html()->modelForm(null, 'GET')->route('admin.listUsers')->attributes([
                        'class' => 'form-inline',
                        'role' => 'form',
                        'autocomplete' => 'off',
                        'onSubmit' => 'return checkDate();',
                    ])->open()); ?>


                <?php echo csrf_field(); ?>
                <?php echo e(html()->hidden('display')); ?>


                <div class="form_row">
                    <div class="form_fields d-flex mb-0 gap-2">
                        <div class="form-group mb-0 w-25p">
                            <?php echo e(html()->text('name', isset($searchVariable['name']) ? $searchVariable['name'] : '')->attributes([
                                    'class' => 'form-control mw-100',
                                    'autocomplete' => 'off',
                                    'placeholder' => trans('messages.searchUserPlaceHolder'),
                                ])); ?>

                        </div>


                        <div class="form-group mb-0 w-20p">
                            <?php echo e(html()->select(
                                    'status',
                                    ['1' => trans('messages.active'), '0' => trans('messages.inActive')],
                                    isset($searchVariable['status']) ? $searchVariable['status'] : '',
                                )->attributes(['class' => 'form-control form-control-dropdown'])->placeholder(trans('messages.status'))); ?>

                        </div>

                        <div class="form-group mb-0 calendarIcon">
                            <?php echo e(html()->text('from', isset($searchVariable['from']) ? $searchVariable['from'] : '')->attributes([
                                    'class' => 'form-control datepicker',
                                    'onkeydown' => 'return false;',
                                    'placeholder' => trans('messages.fromDate'),
                                ])); ?>

                        </div>
                        <div class="form-group mb-0 calendarIcon">
                            <?php echo e(html()->text('to', isset($searchVariable['to']) ? $searchVariable['to'] : '')->attributes([
                                    'class' => 'form-control datepicker',
                                    'onkeydown' => 'return false;',
                                    'placeholder' => trans('messages.toDate'),
                                ])); ?>

                        </div>
                    </div>

                    <div class="form-action">
                        <button title="<?php echo e(trans('messages.search')); ?>" type="submit"
                            class="btn theme_btn bg_theme btn-sm btnIcon"><em
                                class="fa-solid fa-magnifying-glass"></em></button>
                                <?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->hasPermission('SUB_ADMIN_ADD'))) : ?>
                                <a href="<?php echo e(route('admin.user_add')); ?>" class="btn theme_btn bg_theme btn-sm py-2  btnIcon d-none" style="margin:0;" title="<?php echo e(trans('Add New User')); ?>"> <em class='fa fa-add '></em></a>
                                <?php endif; ?>

                        <a href="<?php echo e(Route('admin.listUsers')); ?>" class="btn btn-sm border_btn btnIcon"
                            title="<?php echo e(trans('messages.reset')); ?>"><em class='fa fa-refresh ml-2'></em> </a>
                        <a href="<?php echo e(url('admin/export/User/xls')); ?>" class="btn btn-sm border_btn d-none"
                            title="Export Xls">Export Xls</a>
                        <a href="<?php echo e(url('admin/export/User/csv')); ?>" class="btn btn-sm border_btn d-none"
                            title="Export Csv">Export Csv</a>
                    </div>
                </div>
                <?php echo e(html()->closeModelForm()); ?>

            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="<?php if(!$result->isEmpty()): ?> table table-striped table-hover <?php endif; ?>">
                        <caption>User List</caption>
                        <?php if(!$result->isEmpty()): ?>
                            <thead>
                                <tr>
                                    <th scope="col">
                                        S.No.
                                    </th>


                                    <th scope="col">
                                        <?php echo e(link_to_route(
                                            'admin.listUsers',
                                            trans('messages.fullName'),
                                            [
                                                'sortBy' => 'full_name',
                                                'order' => $sortBy == 'full_name' && $order == 'desc' ? 'asc' : 'desc',
                                            ],
                                            $query_string,
                                        )); ?>

                                        <?php getSortIcon($sortBy,$order,'full_name') ?>
                                    </th>

                                    <th scope="col">
                                        <?php echo e(link_to_route(
                                            'admin.listUsers',
                                            trans('messages.email'),
                                            [
                                                'sortBy' => 'email',
                                                'order' => $sortBy == 'email' && $order == 'desc' ? 'asc' : 'desc',
                                            ],
                                            $query_string,
                                        )); ?>


                                        <?php getSortIcon($sortBy,$order,'email') ?>
                                    </th>
                                    <th scope="col">
                                        <?php echo e(link_to_route(
                                            'admin.listUsers',
                                            trans('Country Code'),
                                            [
                                                'sortBy' => 'country_code',
                                                'order' => $sortBy == 'country_code' && $order == 'desc' ? 'asc' : 'desc',
                                            ],
                                            $query_string,
                                        )); ?>


                                        <?php getSortIcon($sortBy,$order,'country_code') ?>
                                    </th>

                                    <th scope="col">
                                        <?php echo e(link_to_route(
                                            'admin.listUsers',
                                            trans('messages.phoneNo'),
                                            [
                                                'sortBy' => 'mobile_no',
                                                'order' => $sortBy == 'mobile_no' && $order == 'desc' ? 'asc' : 'desc',
                                            ],
                                            $query_string,
                                        )); ?>


                                        <?php getSortIcon($sortBy,$order,'mobile_no') ?>
                                    </th>

                                    <th scope="col">
                                        <?php echo e(link_to_route(
                                            'admin.listUsers',
                                            trans('messages.createdOn'),
                                            [
                                                'sortBy' => 'created_at',
                                                'order' => $sortBy == 'created_at' && $order == 'desc' ? 'asc' : 'desc',
                                            ],
                                            $query_string,
                                        )); ?>


                                        <?php getSortIcon($sortBy,$order,'created_at') ?>
                                    </th>
                                    <th scope="col">
                                        <?php echo e(link_to_route(
                                            'admin.listUsers',
                                            trans('Status'),
                                            [
                                                'sortBy' => 'status',
                                                'order' => $sortBy == 'status' && $order == 'desc' ? 'asc' : 'desc',
                                            ],
                                            $query_string,
                                        )); ?>

                                        <?php getSortIcon($sortBy,$order,'status') ?>
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
                                            <?php echo e($record->full_name); ?>

                                        </td>
                                        <td>
                                            <?php echo e($record->email); ?>

                                        </td>
                                        <td>
                                            <?php echo e(!empty($record->country_code) ? '+'.$record->country_code : ''); ?>

                                        </td>

                                        <td>
                                            <?php echo e(!empty($record->mobile_no) ? $record->mobile_no : '-'); ?>

                                        </td>
                                        <td>
                                            <?php echo e(date(Config::get('constants.ADMIN_LISTING_DATE_FORMAT'), strtotime($record->created_at))); ?>

                                        </td>
                                        <td>
                                            <?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->hasPermission('USER_CHANGE_STATUS'))) : ?>
                                            <?php if($record->status == 1 || $record->status =='Active'): ?>
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
                                            <?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->hasPermission('USER_VIEW'))) : ?>
                                                <a title="<?php echo e(trans('messages.view')); ?>"
                                                    href="<?php echo e(URL::to('admin/user-list/view/' . $record->id)); ?>"
                                                    class="btn btn-warning mb-2">
                                                    <em class="fa fa-eye"></em>
                                                </a>
                                            <?php endif; ?>
                                            <?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->hasPermission('SUB_ADMIN_EDIT'))) : ?>
                                                <a title="<?php echo e(trans('Edit')); ?>" href="<?php echo e(route('admin.user_edit',$record)); ?>" class="btn btn-primary mb-2 d-none">
                                                    <em class="fa fa-pencil"></em>
                                                </a>
                                            <?php endif; ?>
                                            <?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->hasPermission('SUB_ADMIN_DELETE'))) : ?>
                                                <a class="btn btn-primary delete_any_item mb-2 d-none" data-id="<?php echo e(route('admin.delete.user',$record->id)); ?>"
                                                    title="<?php echo e(trans('Delete')); ?>" href="javascript::void(0);">
                                                    <em class="fa fa-trash"></em>
                                                </a>
                                            <?php endif; ?>
                                        </td>

                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center noRecord"><strong>
                                            <?php echo e(trans('messages.noRecordFound')); ?></strong></td>
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

    <script src="<?php echo e(asset('assets/js/custom-user-define-fun.js')); ?>"></script>
    <script>
        $(document).ready(function() {
            DeleteConfirmation();
        });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.default_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/user/listUser.blade.php ENDPATH**/ ?>