
<?php $__env->startSection('content'); ?>
<div class="header d-flex align-items-center">
    <?php
use App\Services\ImageService;
    ?>
    <h1 class="page-header"><?php echo e(trans("messages.view")); ?> <?php echo e(trans('messages.insurance_renewal')); ?></h1>
    <ol class="breadcrumb ms-auto mb-0">
        <li><a href="<?php echo e(Route('admin.dashboard')); ?>"><em class="fa fa-dashboard"></em>
                <?php echo e(trans("messages.dashboard")); ?></a></li>
        <li class="active"><?php echo e(trans("messages.view")); ?></li>
    </ol>
</div>
<?php
//pr($result);die;
?>
<div id="page-inner" class="container-fluid">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row mb-4">
                <div class="form-group d-flex gap-3">
                    <a href="<?php echo e(route('admin.insuranceRenewal.list')); ?>" class="btn btn-primary px-sm-5 font-semibold d-flex align-items-center">
                        <em class="icon-refresh me-2"></em> <?php echo e(trans("messages.back")); ?>

                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"><em class="fa fa-shield-alt me-2"></em> <?php echo e(trans(' Insurance renewal request for quote')); ?></h6>
                        </div>
                        <?php
                
                        ?>
                        <div class="card-body">
                        <div class="row mb-3">
                                <div class="col-10 col-lg-10"><strong>Is the vehicle registered in your name?</strong></div>
                                <div class="col-2 col-lg-2"><?php echo e($result->vehicle_registered == 1 ? 'Yes' : 'No'); ?>  </div>
                        </div>

                        <div class="row mb-3">
                                <div class="col-10 col-lg-10"><strong>Is there a loan on the vehicle?</strong></div>
                                <div class="col-2 col-lg-2"><?php echo e($result->vehicle_line == 1 ? 'Yes' : 'No'); ?></div>
                        </div>

                        <div class="row mb-3">
                                <div class="col-10 col-lg-10"><strong>Have youor any other driver ever been disqualified from driving or had your/his/her license been endorsed?</strong></div>
                                <div class="col-2 col-lg-2"><?php echo e($result->vehicle_disqualified == 1 ? 'Yes' : 'No'); ?></div>
                        </div>

                        <div class="row mb-3">
                                <div class="col-10 col-lg-10"><strong>Applicant driving experiance</strong></div>
                                <div class="col-2 col-lg-2"><?php echo e($result->vehicle_experience ?? ''); ?> Years</div>
                        </div>

                        <div class="row mb-3">
                                <div class="col-10 col-lg-10"><strong>Number of accidents at fault in past 3 years</strong></div>
                                <div class="col-2 col-lg-2"><?php echo e($result->vehicle_accidents ?? ''); ?></div>
                        </div>

                        <div class="row mb-3">
                                <div class="col-10 col-lg-10"><strong>Where is the vehicle kept when not in use</strong></div>
                                <div class="col-2 col-lg-2"><?php echo e($result->vehicle_not_use ?? ''); ?></div>
                        </div>

                        <div class="row mb-3">
                                <div class="col-10 col-lg-10"><strong>Do you any other driver, to your knowledge suffer from the following illness?</strong></div>
                                <div class="col-2 col-lg-2"><?php echo e($result->vehicle_drive_illness == 1 ? 'Yes' : 'No'); ?></div>
                        </div>

                        <div class="row mb-3">
                                <div class="col-10 col-lg-10"><strong>Previous insurer's name</strong></div>
                                <div class="col-2 col-lg-2"><?php echo e($result->vehicle_insurer ?? ''); ?></div>
                        </div>
                        <div class="row mb-3">
                        <div class="col-12 col-md-3"><strong><?php echo e(trans('insurance certificate')); ?></strong></div>
                        <div class="col-12 col-md-6">
                            <?php if($result->insurance_certificate): ?>
                            <a title="Download" target="_blank" href="<?php echo e(url(ImageService::getImageUrl($result->insurance_certificate))); ?>" class="btn btn-outline-info d-inline-flex align-items-center">
                                <em class="fa fa-download me-2"></em> <?php echo e(trans('Download Certificate')); ?>

                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"><em class="fa fa-shield-alt me-2"></em> <?php echo e(trans('Insurance renewal request for quote')); ?></h6>
                            
                        </div>
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"><em class="fa fa-shield-alt me-2"></em><?php echo e(trans('Information to share with the insurance company you have selected')); ?></h6>
                          
                        </div>
                        <div class="card-body">
                        <div class="row mb-3">
                                <div class="col-5 col-lg-3"><strong>User name</strong></div>
                                <div class="col-7 col-lg-9"><?php echo e($result->full_name ?? ''); ?></div>
                        </div>
                        <div class="row mb-3">
                                <div class="col-5 col-lg-3"><strong>User Email</strong></div>
                                <div class="col-7 col-lg-9"><?php echo e($result->user_email ?? ''); ?></div>
                        </div>

                        <div class="row mb-3">
                                <div class="col-5 col-lg-3"><strong>Nic Number</strong></div>
                                <div class="col-7 col-lg-9"><?php echo e($result->nic ?? ''); ?></div>
                        </div>

                        <div class="row mb-3">
                                <div class="col-5 col-lg-3"><strong>Car Model Name</strong></div>
                                <div class="col-7 col-lg-9"><?php echo e($result->car_model_name ?? ''); ?></div>
                        </div>

                        <div class="row mb-3">
                                <div class="col-5 col-lg-3"><strong>Year of manufacturer</strong></div>
                                <div class="col-7 col-lg-9"><?php echo e($result->year_of_manufacturer ?? ''); ?></div>
                        </div>
                        <div class="row mb-3">
                                <div class="col-5 col-lg-3"><strong>Registration Mark</strong></div>
                                <div class="col-7 col-lg-9"><?php echo e($result->vehicle_registration_mark ?? ''); ?></div>
                        </div>

                        <div class="row mb-3">
                                <div class="col-5 col-lg-3"><strong>Sum to be insured</strong></div>
                                <div class="col-7 col-lg-9"><?php echo e(number_format($result->sum_to_be_insured, 2) ?? ''); ?></div>
                        </div>

                        <div class="row mb-3">
                                <div class="col-5 col-lg-3"><strong>Cover type</strong></div>
                                <div class="col-7 col-lg-9"><?php echo e($result->cover_type_name ?? ''); ?></div>
                        </div>

                        <div class="row mb-3">
                                <div class="col-5 col-lg-3"><strong>Period for Insurance Cover</strong></div>
                                <div class="col-7 col-lg-9"><?php echo e($result->period_insurance_cover_name ?? ''); ?></div>
                        </div>
                        <div class="row mb-3">
                                <div class="col-5 col-lg-3"><strong>User Comment</strong></div>
                                <div class="col-7 col-lg-9"><?php echo e($result->comment ?? ''); ?></div>
                        </div>

                        <div class="row mb-3">
                        <div class="col-5 col-md-3"><strong><?php echo e(trans('User Attachment')); ?></strong></div>
                        <div class="col-7 col-md-9">
                            
                            <?php if($result->attachment): ?>
                            <a title="Download" target="_blank" href="<?php echo e(url(ImageService::getImageUrl(Config::get('constants.USER_FOLDER').$result->attachment))); ?>" class="btn btn-outline-info d-inline-flex align-items-center">
                                <em class="fa fa-download me-2"></em> <?php echo e(trans('Download Certificate')); ?>

                            </a>
                            <?php endif; ?>
                        </div>
                    </div>

                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"><em class="far fa-address-card me-2"></em> <?php echo e(trans('Company details')); ?></h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                            <div class="col-7 col-lg-3"><strong><?php echo e(trans('Company Name')); ?></strong></div>
                            <div class="col-5 col-lg-9"><?php echo e($company_name?? ''); ?></div>
                          


                            </div>
                        
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.default_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/insuranceRenewal/view.blade.php ENDPATH**/ ?>