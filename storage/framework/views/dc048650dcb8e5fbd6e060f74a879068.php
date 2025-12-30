
<?php $__env->startSection('content'); ?>
<?php
use App\Services\CommonService;
?>
<div class="header d-flex align-items-center">
    <h1 class="page-header"><?php echo e(trans("messages.view")); ?> <?php echo e(trans('messages.vehicle')); ?></h1>
    <ol class="breadcrumb ms-auto mb-0">
        <li><a href="<?php echo e(Route('admin.dashboard')); ?>"><em class="fa fa-dashboard"></em>
                <?php echo e(trans("messages.dashboard")); ?></a></li>
        <li><a href="<?php echo e(route('admin.vehicle.list')); ?>"> <?php echo e(trans('messages.vehicle')); ?> <?php echo e(trans('messages.list')); ?></a></li>
        <li class="active"><?php echo e(trans("messages.view")); ?></li>
    </ol>
</div>

<div id="page-inner" class="container-fluid">
    <div class="panel panel-default">
        <div class="panel-body">
            <!-- Back Button -->
            <div class="row mb-4">
                <div class="form-group d-flex gap-3">
                    <a href="<?php echo e(route('admin.vehicle.list')); ?>" class="btn btn-primary px-sm-5 font-semibold d-flex align-items-center">
                        <em class="icon-refresh me-2"></em> <?php echo e(trans("messages.back")); ?>

                    </a>
                </div>
            </div>
    <div class=" row">
        <div class=" col-lg-6">
            <!-- Vehicle Information Section -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><em class="fa fa-car me-2"></em> <?php echo e(trans('Vehicle information')); ?></h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-7 col-lg-3"><strong><?php echo e(trans('messages.owner_name')); ?></strong></div>
                        <div class="col-5 col-lg-9"><?php echo e($result['owner_name'] ?? ''); ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-7 col-lg-3"><strong><?php echo e(trans('Owner address')); ?></strong></div>
                        <div class="col-5 col-lg-9"><?php echo e($result->owner_address ?? ''); ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-7 col-lg-3"><strong><?php echo e(trans('messages.reg_mark')); ?></strong></div>
                        <div class="col-5 col-lg-9"><?php echo e($result->reg_no ?? ''); ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-7 col-lg-3"><strong><?php echo e(trans('messages.brand')); ?></strong></div>
                        <div class="col-5 col-lg-9"><?php echo e($result['brand']->name ?? ''); ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-7 col-lg-3"><strong><?php echo e(trans('messages.model')); ?></strong></div>
                        <div class="col-5 col-lg-9"><?php echo e($result['model']->name ?? ''); ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-7 col-lg-3"><strong><?php echo e(trans('messages.fuel_used')); ?></strong></div>
                        <div class="col-5 col-lg-9"><?php echo e($result['fuelType']->name ?? ''); ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-7 col-lg-3"><strong><?php echo e(trans('messages.engine_capacity')); ?></strong></div>
                        <div class="col-5 col-lg-9"><?php echo e($result['engineCapacity']->capacity ?? ''); ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-7 col-lg-3"><strong><?php echo e(trans('messages.transmission_type')); ?></strong></div>
                        <div class="col-5 col-lg-9"><?php echo e($result['transmissionType']->name ?? ''); ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <!-- Insurance Information Section -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><em class="fa fa-shield-alt me-2"></em> <?php echo e(trans('messages.insurance_information')); ?></h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-5 col-lg-3"><strong><?php echo e(trans('messages.insurance_company')); ?></strong></div>
                        <div class="col-7 col-lg-9"><?php echo e($result['InsuranceCompany']->full_name ?? ''); ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-5 col-lg-3"><strong><?php echo e(trans('messages.sum_assured_value')); ?></strong></div>
                        <div class="col-7 col-lg-9"><?php echo e($result->sum_assured_value?'INR. '.$result->sum_assured_value:'N/A'); ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-5 col-lg-3"><strong><?php echo e(trans('messages.insurance_expiry_date')); ?></strong></div>
                        <div class="col-7 col-lg-9"><?php echo e($result->insurance_expiry_date ?? ''); ?></div>
                    </div>
                </div>
            </div>
        </div>

            <!-- Road Tax Section -->
            <div class="col-lg-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><em class="fa fa-road me-2"></em> <?php echo e(trans('messages.road_tax')); ?></h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-12 col-md-3"><strong><?php echo e(trans('messages.road_tax_certificate')); ?></strong></div>
                        <div class="col-12 col-md-4">
                            <?php if($result->RoadCertificate): ?>
                            <a title="Download" target="_blank" href="<?php echo e($result->RoadCertificate); ?>" class="btn btn-outline-info d-inline-flex align-items-center">
                                <em class="fa fa-download me-2"></em> <?php echo e(trans('Download Certificate')); ?>

                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12 col-md-3"><strong><?php echo e(trans('Road tax expiry date')); ?></strong></div>
                        <div class="col-12 col-md-9"><?php echo e($result->due_renewal_date ?? ''); ?></div>
                    </div>
                </div>
            </div>
        </div>
            <div class="col-lg-6">
            <!-- Certificate of Fitness Section -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning text-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><em class="fa fa-certificate me-2"></em> <?php echo e(trans('Certificate of Fitness')); ?></h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-12 col-md-3"><strong><?php echo e(trans('messages.fitness_certificate')); ?></strong></div>
                        <div class="col-12 col-md-9">
                            <?php if($result->FitnessAttachment): ?>
                            <a title="Download" target="_blank" href="<?php echo e($result->FitnessAttachment); ?>" class="btn btn-outline-warning d-inline-flex align-items-center">
                                <em class="fa fa-download me-2"></em> <?php echo e(trans('Download Fitness Certificate')); ?>

                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12 col-md-3"><strong><?php echo e(trans('messages.fitness_expiry_date')); ?></strong></div>
                        <div class="col-12 col-md-9"><?php echo e($result->fitness_expiry_date ?? ''); ?></div>
                    </div>
                </div>
            </div>
        </div>

        </div>
        </div>
    </div>
</div>


<?php echo $__env->make('admin.tabSelected', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.default_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/vehicle/view.blade.php ENDPATH**/ ?>