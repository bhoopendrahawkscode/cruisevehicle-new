
<?php $__env->startSection('content'); ?>
    <?php
    if (!function_exists('appendDate')) {
        function appendDate()
        {
            $string = '';
            if (isset($_GET['from']) && $_GET['from'] != '') {
                $string = '&from=' . $_GET['from'];
            }
            if (isset($_GET['to']) && $_GET['to'] != '') {
                $string .= '&to=' . $_GET['to'];
            }
            return $string;
        }
    }
    ?>
    <div class="header d-flex align-items-center">
        <h1 class="page-header">
            Dashboard
        </h1>
        <ol class="breadcrumb ms-auto mb-0">
            <li><a href="javascript:void(0);"><em class="fa fa-dashboard"></em> <?php echo e(trans('messages.dashboard')); ?></a></li>
        </ol>
    </div>
    <section id="page-inner" class="admin-dashboard">
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-body form_fields">
                        <?php echo e(html()->modelForm(null, 'GET')->route('admin.dashboard')->attributes([
                                'class' => 'd-flex gap-2 form-inline',
                                'role' => 'form',
                                'autocomplete' => 'off',
                                'onSubmit' => 'return checkDate();',
                            ])->open()); ?>

                        <?php echo e(html()->hidden('display')); ?>

                        <div class="form-group mb-0 calendarIcon">
                            <?php echo e(html()->text('from', isset($searchData['from']) ? $searchData['from'] : '')->attributes([
                                    'class' => 'form-control datepicker',
                                    'onkeydown' => 'return false;',
                                    'placeholder' => trans('messages.fromDate'),
                                ])); ?>

                        </div>
                        <div class="form-group mb-0 calendarIcon">
                            <?php echo e(html()->text('to', isset($searchData['to']) ? $searchData['to'] : '')->attributes([
                                    'class' => 'form-control datepicker',
                                    'onkeydown' => 'return false;',
                                    'placeholder' => trans('messages.toDate'),
                                ])); ?>

                        </div>
                        <button type="submit" class="btn theme_btn bg_theme btn-sm"
                            title="<?php echo e(trans('messages.search')); ?>"><em class="fa-solid fa-magnifying-glass"></em></button>
                        <a href="<?php echo e(Route('admin.dashboard')); ?>" title="<?php echo e(trans('messages.reset')); ?>"
                            class="btn btn-sm border_btn"><em class='fa fa-refresh '></em> </a>
                        <?php echo e(html()->closeModelForm()); ?>

                    </div>
                </div>
            </div>
        </div>
        <div class="row gx-3">
            
           
                <div class="col-xl-4 col-sm-12 col-xs-12 col-md-6">
                    <div class="board">
                        <div class="panel panel-primary">
                            <a href="<?php echo e(route('admin.insuranceRenewal.list')); ?>?<?php echo appendDate(); ?>">
                                <div class="number">
                                    <h3>
                                        <h3><?php echo e(isset($totalRequest) ? $totalRequest : ''); ?></h3>
                                        <small><?php echo e(trans('Number of total Request')); ?></small>
                                    </h3>
                                </div>
                                <div class="icon">
                                    <em class="fa fa-user fa-5x blue"></em>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
          
                <div class="col-xl-4 col-sm-12 col-xs-12 col-md-6">
                    <div class="board">
                        <div class="panel panel-primary">
                            <a href="<?php echo e(route('admin.insuranceRenewal.list')); ?>?<?php echo appendDate(); ?>&insurance_status=2">
                                <div class="number">
                                    <h3>
                                        <h3><?php echo e(isset($totalDeclinedRequest) ? $totalDeclinedRequest : ''); ?></h3>
                                        <small><?php echo e(trans('Number of Quote Decline')); ?></small>
                                    </h3>
                                </div>
                                <div class="icon">
                                    <em class="fa fa-car fa-5x fa-5x green"></em>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
           
        </div>
        <div class="row gx-3">
            <div class="col-xl-4 col-sm-12 col-xs-12 col-md-6">
                <div class="board">
                    <div class="panel panel-primary">
                        <a href="<?php echo e(route('admin.insuranceRenewal.list')); ?>?<?php echo appendDate(); ?>&insurance_status=4">
                            <div class="number">
                                <h3>
                                    <h3><?php echo e(isset($totalAcceptedRequest) ? $totalAcceptedRequest : ''); ?></h3>
                                    <small><?php echo e(trans('Number of Confirmed quote')); ?></small>
                                </h3>
                            </div>
                            <div class="icon">
                                <em class="fa fa-gas-pump fa-5x fa-5x yellow"></em>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>


    </section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.default_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/insurance_dashboard.blade.php ENDPATH**/ ?>