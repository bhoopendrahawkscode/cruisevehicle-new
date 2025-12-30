
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
                        <div class="form-group mb-0 ">
                        <select class="form-control formValidate" name="model_id" id="model_id">
                            <option value="" <?= isset($_GET['model_id']) && $_GET['model_id'] == '' ? 'selected' : '' ?>>All other Model</option>
                            <option value="same_model" <?= isset($_GET['model_id']) && $_GET['model_id'] == 'same_model' ? 'selected' : '' ?>>Same Model</option>
                        </select>

                        </div>
                         
                            <div class="form-group mb-0 ">
                              
                            <?php echo e(html()->select('vehicle_id', ['' => 'Please select vehicle'] + $vehicle_data)->attributes(['class' => 'form-control formValidate'])); ?>


            
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
                        <?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->hasPermission('USER_LIST'))) : ?>
                            <a href="<?php echo e(route('admin.listUsers')); ?>?<?php echo appendDate(); ?>">
                        <?php endif; ?>
                                <div class="number">
                                    <h3>
                                        <h3><?php echo e(isset($totalUser) ? $totalUser : ''); ?></h3>
                                        <small><?php echo e(trans('messages.totalUsers')); ?></small>
                                    </h3>
                                </div>
                                <div class="icon">
                                    <em class="fa fa-user fa-5x blue"></em>
                                </div>
                        <?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->hasPermission('USER_LIST'))) : ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
                <div class="col-xl-4 col-sm-12 col-xs-12 col-md-6">
                    <div class="board">
                        <div class="panel panel-primary">
                            <?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->hasPermission('VEHICLE_LIST'))) : ?>
                            <a href="<?php echo e(route('admin.vehicle.list')); ?>?<?php echo appendDate(); ?>">
                                <?php endif; ?>
                                <div class="number">
                                    <h3>
                                        <h3><?php echo e(isset($totalRegisteredVehicles) ? $totalRegisteredVehicles : ''); ?></h3>
                                        <small><?php echo e(trans('messages.registered_vehicle')); ?></small>
                                    </h3>
                                </div>
                                <div class="icon">
                                    <em class="fa fa-car fa-5x fa-5x green"></em>
                                </div>
                                <?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->hasPermission('VEHICLE_LIST'))) : ?>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
           
        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <canvas id="fuelGraph"></canvas>
    
<script>
    // Function to get query parameter from URL
    function getQueryParam(name) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
    }
    const vehicleId = getQueryParam('vehicle_id');
    if (vehicleId) {
        document.getElementById('vehicle_id').value = vehicleId;
    }

   var ctx = document.getElementById('fuelGraph').getContext('2d');
var chart = new Chart(ctx, {
    type: 'line', // or 'bar', 'pie', etc.
    data: {
        labels: <?php echo json_encode($graphData['months'] ?? []); ?>, // X-axis months
        datasets: [
            {
                label: 'Selected Vehicle Mileage',
                data: <?php echo json_encode($graphData['selectedVehicleMileage'] ?? []); ?>, // Y-axis mileage for selected vehicle
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.6,
               
            },
            {
                label: 'Other Vehicles Average Mileage',
                data: <?php echo json_encode($graphData['otherVehiclesAverageMileage'] ?? []); ?>, // Y-axis average mileage for other vehicles
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                tension: 0.6,
               
            }
        ]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

</script>

        


        </div>


    </section>
  
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.default_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/dashboard.blade.php ENDPATH**/ ?>