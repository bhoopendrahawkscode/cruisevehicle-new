
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
            if (isset($_GET['status']) && $_GET['status'] != '') {
                $string .= '&status=' . $_GET['status'];
            }
            if (isset($_GET['company']) && $_GET['company'] != '') {
                $string .= '&company=' . $_GET['company'];
            }
            return $string;
        }
    }
    ?>
    <div class="header d-flex align-items-center">
        <h1 class="page-header">
            <?php echo e(trans('messages.ReportManager')); ?>

        </h1>
        <ol class="breadcrumb ms-auto mb-0">
            <li><a href="javascript:void(0);"><em class="fa fa-dashboard"></em> <?php echo e(trans('messages.ReportManager')); ?></a></li>
        </ol>
    </div>
    <?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->hasPermission('REPORT_LIST'))) : ?>
    <section id="page-inner" class="admin-dashboard">
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-body form_fields">
                        <?php echo e(html()->modelForm(null, 'GET')->route('admin.report')->attributes([
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
                        <div class="form-group mb-0 w-10p">
                            <?php echo e(html()->select('status',['1' => trans('messages.active'), '0' => trans('messages.inActive')],
                            ((isset($searchData['status'])) ? $searchData['status'] : ''))
                            ->attributes(['class' => 'form-control form-control-dropdown'])->placeholder(trans("messages.status"))); ?>

                        </div>
                        <button type="submit" class="btn theme_btn bg_theme btn-sm"
                            title="<?php echo e(trans('messages.search')); ?>"><em class="fa-solid fa-magnifying-glass"></em></button>
                        <a href="<?php echo e(Route('admin.report')); ?>" title="<?php echo e(trans('messages.reset')); ?>"
                            class="btn btn-sm border_btn"><em class='fa fa-refresh '></em> </a>
                        <?php echo e(html()->closeModelForm()); ?>

                    </div>
                </div>
            </div>
        </div>
        <div class="row gx-3">
            
            <?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->hasPermission('USER_LIST'))) : ?>
                <div class="col-xl-4 col-sm-12 col-xs-12 col-md-6">
                    <div class="board">
                        <div class="panel panel-primary">
                            <a href="<?php echo e(route('admin.listUsers')); ?>?<?php echo appendDate(); ?>">
                                <div class="number">
                                    <h3>
                                        <h3><?php echo e(isset($totalUser) ? $totalUser : ''); ?></h3>
                                        <small><?php echo e(trans('messages.totalUsers')); ?></small>
                                    </h3>
                                </div>
                                <div class="icon">
                                    <em class="fa fa-users fa-5x blue"></em>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->hasPermission('VEHICLE_LIST'))) : ?>
                <div class="col-xl-4 col-sm-12 col-xs-12 col-md-6">
                    <div class="board">
                        <div class="panel panel-primary">
                            <a href="<?php echo e(route('admin.vehicle.list')); ?>?<?php echo appendDate(); ?>">
                                <div class="number">
                                    <h3>
                                        <h3><?php echo e(isset($totalRegisteredVehicles) ? $totalRegisteredVehicles : ''); ?></h3>
                                        <small><?php echo e(trans('messages.registered_vehicle')); ?></small>
                                    </h3>
                                </div>
                                <div class="icon">
                                    <em class="fa fa-car fa-5x fa-5x green"></em>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->hasPermission('SERVICE_PROVIDER_LIST'))) : ?>
        <div class="row gx-3">
            <div class="col-xl-4 col-sm-12 col-xs-12 col-md-6">
                <div class="board">
                    <div class="panel panel-primary">
                        <a href="<?php echo e(route('admin.serviceprovider.list')); ?>?<?php echo appendDate(); ?>">
                            <div class="number">
                                <h3>
                                    <h3><?php echo e(isset($totalServiceProvider) ? $totalServiceProvider : ''); ?></h3>
                                    <small><?php echo e(trans('messages.no_service_provider')); ?></small>
                                </h3>
                            </div>
                            <div class="icon">
                                <em class="fa fa-ship fa-5x fa-5x yellow"></em>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->hasPermission('INSURANCE_RENEWAL_LIST'))) : ?>
            <div class="col-xl-4 col-sm-12 col-xs-12 col-md-6">
                <div class="board">
                    <div class="panel panel-primary">
                        <a href="<?php echo e(route('admin.insuranceRenewal.list')); ?>?<?php echo appendDate(); ?>">
                            <div class="number">
                                <h3>
                                    <h3><?php echo e(isset($totalInsuranceRenewal) ? $totalInsuranceRenewal : ''); ?></h3>
                                    <small><?php echo e(trans('messages.no_Insurance_renewel_request')); ?></small>
                                </h3>
                            </div>
                            <div class="icon">
                                <em class="fa fa-stethoscope fa-5x fa-5x red"></em>
                            </div>
                        </a>
                    </div>
                </div>
        </div>
            <?php endif; ?>
        </div>
    
 
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-body form_fields">
                        <?php echo e(html()->modelForm(null, 'GET')->route('admin.report')->attributes([
                                'class' => 'd-flex gap-2 form-inline',
                                'role' => 'form',
                                'autocomplete' => 'off',
                                'onSubmit' => 'return checkDate();',
                            ])->open()); ?>

                        <?php echo e(html()->hidden('display')); ?>

                        <div class="form-group mb-0 calendarIcon">
                            <div class="form-group mb-0 calendarIcon">
                                <?php echo e(html()->text('from', isset($searchData['from']) ? $searchData['from'] : '')->attributes([
                                    'class' => 'form-control datepicker',
                                    'id' => 'fromdate',
                                    'onkeydown' => 'return false;',
                                    'placeholder' => trans('messages.fromDate'),
                                ])); ?>

                            </div>
                            
                        </div>
                        <div class="form-group mb-0 calendarIcon">
                            <?php echo e(html()->text('to', isset($searchData['to']) ? $searchData['to'] : '')->attributes([
                                    'class' => 'form-control datepicker',
                                    'id' => 'todate',
                                    'onkeydown' => 'return false;',
                                    'placeholder' => trans('messages.toDate'),
                                ])); ?>

                        </div>
                        
                       
                     
                        
                        <button type="submit" class="btn theme_btn bg_theme btn-sm"
                            title="<?php echo e(trans('messages.search')); ?>"><em class="fa-solid fa-magnifying-glass"></em></button>
                        <a href="<?php echo e(Route('admin.report')); ?>" title="<?php echo e(trans('messages.reset')); ?>"
                            class="btn btn-sm border_btn"><em class='fa fa-refresh '></em> </a>
                            <a href="<?php echo e(route('admin.download-report')); ?>?<?php echo appendDate(); ?>" class="btn btn-primary">Download Insurance Renewals Report</a>

                        <?php echo e(html()->closeModelForm()); ?>

                    </div>
                </div>
            </div>
        </div>
        
       
            <div class="col-xl-4 col-sm-12 col-xs-12 col-md-6">
                <div class="board">
                    <div class="panel panel-primary">
                        
                            <div class="number">
                                <h3>
                                    <h3><?php echo e(isset($insuranceRenewed) ? $insuranceRenewed : ''); ?></h3>
                                    <small><?php echo e(trans('messages.no_Insurance_renewel_request')); ?></small>
                                </h3>
                            </div>
                            <div class="icon">
                                <em class="fa fa-stethoscope fa-5x fa-5x red"></em>
                            </div>
                       
                    </div>
                </div>
        </div>
           
    </section>
    <script type="text/javascript">
$(function() {
var elem = document.getElementsByClassName("active-menu");
if(elem.length)
elem[0].scrollIntoView();

$('#fromdate').datepicker({
    format: "yyyy-mm-dd",
    clearBtn: true
}).on('changeDate', function(selected){
    $('#todate').datepicker('setStartDate', selected.date);
});
$('#todate').datepicker({
    format: "yyyy-mm-dd",
    clearBtn: true
}).on('changeDate', function(selected){
    $('#fromdate').datepicker('setEndDate', selected.date);
});
                        checkDate();
});

function checkDate(){
			 //Select the date fields by ID
			var fromDateField = $('#fromdate');
			var toDateField = $('#todate');

			// Attach an event listener to the "to" date field
			toDateField.on('change', function() {
				checkDate2();
			});
			checkDate2();
			function checkDate2(){
				var fromDateValue = new Date(fromDateField.val());
				var toDateValue = new Date(toDateField.val());

				if (fromDateValue > toDateValue) {
					alert('To date cannot be less than From date.');
					toDateField.val('');
					return false;
				}
				return true;
			}
		}
    </script>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.default_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/report.blade.php ENDPATH**/ ?>