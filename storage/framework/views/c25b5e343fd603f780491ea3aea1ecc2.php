
<link integrity=""
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/css/bootstrap3/bootstrap-switch.min.css"
    rel="stylesheet">
<?php $__env->startSection('content'); ?>
    <?php use App\Services\GeneralService;
    use App\Constants\Constant;
    ?>

    <div class="header d-flex align-items-center">
        <h1 class="page-header">
            <?php echo e(trans('messages.role')); ?> <?php echo e(trans('messages.list')); ?>


        </h1>
        <ol class="breadcrumb ms-auto mb-0">
            <li><a href="<?php echo e(Route('admin.dashboard')); ?>"><em class="fa fa-dashboard"></em> <?php echo e(trans('messages.dashboard')); ?>

                </a></li>
            <li class="active"><?php echo e(trans('messages.role')); ?> <?php echo e(trans('messages.list')); ?></li>
        </ol>
    </div>



    <div id="page-inner">
        <div class="panel panel-default">
            <div class="panel-body form_mobile">
                <?php echo e(html()->modelForm(null, 'GET')->route('admin.role.list')->attributes([
                        'class' => 'form-inline',
                        'role' => 'form',
                        'autocomplete' => 'off',
                        'onSubmit' => 'return checkDate();',
                    ])->open()); ?>

                <?php echo e(html()->hidden('display')); ?>

                <div class="form_row">
                    <div class="form_fields d-flex mb-0 gap-2">
                        <div class="form-group mb-0">
                            <?php echo e(html()->text('name', isset($searchVariable['name']) ? $searchVariable['name'] : '')->attributes(['class' => 'form-control', 'placeholder' => trans('messages.role')])); ?>

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

                    <div class="form-action ">
                        <button type="submit" class="btn theme_btn bg_theme btn-sm btnIcon"
                            title="<?php echo e(trans('messages.search')); ?>"><em class="fa-solid fa-magnifying-glass"></em> </button>
                        <a href="<?php echo e(route('admin.role.list')); ?>" class="btn btn-sm border_btn btnIcon"
                            title="<?php echo e(trans('messages.reset')); ?>"><em class='fa fa-refresh '></em> </a>
                        
                        <div class="form-action_status">
                        </div>
                    </div>
                </div>
                <?php echo e(html()->closeModelForm()); ?>

            </div>
        </div>

        <?php if (isset($component)) { $__componentOriginal7a185824cf197c19d194a3e45f833186 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7a185824cf197c19d194a3e45f833186 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.table-list-structure','data' => ['result' => $result]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.table-list-structure'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['result' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($result)]); ?>
             <?php $__env->slot('table', null, []); ?> 
                <table class="<?php if(!$result->isEmpty()): ?> table table-striped table-hover <?php endif; ?>">
                    <caption><?php echo e(trans('messages.subAdmin')); ?> <?php echo e(trans('messages.list')); ?></caption>
                    <?php if(!$result->isEmpty()): ?>
                        <thead>
                            <th scope="col">
                                <?php echo e(trans('messages.sNo')); ?>

                            </th>
                            <th scope="col">
                                <?php echo e(link_to_route(
                                    'admin.role.list',
                                    trans('messages.ID'),
                                    [
                                        'sortBy' => 'id',
                                        'order' => $sortBy == 'id' && $order == 'desc' ? 'asc' : 'desc',
                                    ],
                                    $query_string,
                                )); ?>

                                <?php getSortIcon($sortBy,$order,'id') ?>
                            </th>
                            <th scope="col">
                                <?php echo e(link_to_route(
                                    'admin.role.list',
                                    trans('messages.name'),
                                    [
                                        'sortBy' => 'name',
                                        'order' => $sortBy == 'name' && $order == 'desc' ? 'asc' : 'desc',
                                    ],
                                    $query_string,
                                )); ?>

                                <?php getSortIcon($sortBy,$order,'name') ?>
                            </th>

                            <th scope="col">
                                <?php echo e(link_to_route(
                                    'admin.role.list',
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
                                <?php echo e(trans('messages.action')); ?>

                            </th>
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
                                        <?php echo e($record->id); ?>

                                    </td>
                                    <td>
                                        <?php echo e($record->name); ?>

                                    </td>
                                    <td>
                                        <?php echo e(date(Config::get('constants.ADMIN_LISTING_DATE_FORMAT'), strtotime($record->created_at))); ?>


                                    </td>

                                    <td>
                                    <?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->hasPermission('ROLE_EDIT'))) : ?>
                                        <?php if($record->id == 1 || $record->id == 2): ?>
                                            <span>--</span>
                                        <?php else: ?>
                                            <a title="<?php echo e(trans('Edit')); ?>" href="<?php echo e(route('admin.role.edit', $record)); ?>"
                                                class="btn btn-primary">
                                                <em class="fa fa-pencil"></em>
                                            </a>
                                        <?php endif; ?>
                            <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center noRecord"><strong>
                                        <?php echo e(trans('messages.noRecordFound')); ?></strong></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
             <?php $__env->endSlot(); ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7a185824cf197c19d194a3e45f833186)): ?>
<?php $attributes = $__attributesOriginal7a185824cf197c19d194a3e45f833186; ?>
<?php unset($__attributesOriginal7a185824cf197c19d194a3e45f833186); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7a185824cf197c19d194a3e45f833186)): ?>
<?php $component = $__componentOriginal7a185824cf197c19d194a3e45f833186; ?>
<?php unset($__componentOriginal7a185824cf197c19d194a3e45f833186); ?>
<?php endif; ?>

    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.default_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/role/index.blade.php ENDPATH**/ ?>