
<?php $__env->startSection('content'); ?>
<?php
use App\Constants\Constant;
use App\Services\GeneralService;
use App\Services\ImageService;
?>
<script type="text/javascript" integrity="" src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.0.1/ckeditor.js"></script>
<div class="header d-flex align-items-center">
    <h1 class="page-header">
        <?php echo e(trans('messages.settings')); ?>

    </h1>
    <ol class="breadcrumb ms-auto mb-0">
        <li><a href="<?php echo e(Route('admin.dashboard')); ?>"><em class="fa fa-dashboard"></em> <?php echo e(trans('messages.dashboard')); ?></a></li>
        <li class="active"><?php echo e(trans('messages.settings')); ?></li>
    </ol>
</div>
<div id="page-inner">
    



    <div class="panel panel-default">
        <div class="panel-body">
            <div class="table-responsive">
                <?php echo e(html()->modelForm($settings, 'POST')->route('admin.settings.save')->attributes(['id'=>"settingForm", 'autocomplete' => 'off','enctype'=>'multipart/form-data'])->open()); ?>

                <?php echo csrf_field(); ?>

                <div class="container-fluid">
                <?php if($errors->any()): ?>
                    <div class="alert alert-danger">
                        <ul>
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                 <?php endif; ?>

                      <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="header" class="font-medium fs-6 mb-3"><?php echo e(trans('messages.websiteTitle')); ?><span class="red_lab"> *</span></label>
                                <?php echo e(html()->text('websiteTitle')->attributes(['class' => 'form-control  formValidate', 'maxlength'=>50, 'placeholder' => trans("messages.websiteTitle")])); ?>

                                <?php if($errors->has('websiteTitle')): ?>
                                <span class="invalid-feedback" role="alert">
                                    <strong><?php echo e($errors->first('websiteTitle')); ?></strong>
                                </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="header" class="font-medium fs-6 mb-3"><?php echo e(trans('messages.companyPhone')); ?></label>
                                <?php echo e(html()->text('companyPhone')->attributes(['class' => 'form-control', 'maxlength'=>12, 'placeholder' => trans("messages.companyPhone")])); ?>

                               
                                <?php if($errors->has('companyPhone')): ?>
                                <span class="invalid-feedback" role="alert">
                                    <strong><?php echo e($errors->first('companyPhone')); ?></strong>
                                </span>
                                <?php endif; ?>
                            </div>
                        </div>
                      </div>

                    <div class="row">
                    <div class="col-md-6">
                            <div class="form-group">
                                <label for="header" class="font-medium fs-6 mb-3"><?php echo e(trans('messages.companyEmail')); ?></label>
                                <?php echo e(html()->text('companyEmail')->attributes(['class' => 'form-control ', 'maxlength'=>50, 'placeholder' => trans("messages.companyEmail")])); ?>

                                <?php if($errors->has('companyEmail')): ?>
                                <span class="invalid-feedback" role="alert">
                                    <strong><?php echo e($errors->first('companyEmail')); ?></strong>
                                </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="col-md-3 d-none">
                            <div class="form-group">
                                <label for="header" class="font-medium fs-6 mb-3"><?php echo e(trans('messages.UploadServerType')); ?><span class="red_lab"> *</span></label>
                                <?php echo e(html()->select('UploadServerType',$UploadServerType)->attributes(['class' => 'form-control  formValidate'])); ?>

                                <?php if($errors->has('UploadServerType')): ?>
                                <span class="invalid-feedback" role="alert">
                                    <strong><?php echo e($errors->first('UploadServerType')); ?></strong>
                                </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-lg-4 col-md-6 upload_img mb-5">
                            <label><?php echo e(trans('messages.logoImage')); ?></label>
                            <input name="logo_image" type="file" id="imageInput" accept="image/*">
                            <span id="file-size-error" class="text-danger"></span>
                            <?php if($errors->has('logo_image')): ?>
                            <span class="invalid-feedback error" role="alert">
                                <strong><?php echo e($errors->first('logo_image')); ?></strong>
                            </span>
                            <?php endif; ?>

                           <?php if(isset($settings->logo)): ?>
                                <label class="exist_image"><?php echo e(trans('messages.existingImage')); ?></label>
                                <div class="old_img">
                                    <img alt="Image" class="border border-1" src="<?php echo e(ImageService::getImageUrl(Config::get('constants.LOGO_FOLDER') . GeneralService::getSettings('logo'))); ?>" width="100">
                                </div>
                                <?php endif; ?>
                        </div>
                            <div class="col-lg-2  col-md-6">
                                <label class='image-preview'><?php echo e(trans('messages.imagePreview')); ?></label>
                                <img id="image-preview" class="image-preview" alt="<?php echo e(trans('messages.imagePreview')); ?>" class="img-fluid rounded-circle" width="150px">
                            </div>


                      <div class="col-lg-4 col-md-6 upload_img mb-5">
                            <label><?php echo e(trans('messages.faviconIcon')); ?></label>
                            <input name="favicon_image" type="file" id="imageInputFav" accept="image/*">
                            <span id="file-size-error" class="text-danger"></span>
                            <?php if($errors->has('favicon_image')): ?>
                            <span class="invalid-feedback error" role="alert">
                                <strong><?php echo e($errors->first('favicon_image')); ?></strong>
                            </span>
                            <?php endif; ?>
                            <?php if(isset($settings->favicon)): ?>
                                <label class="exist_image"><?php echo e(trans('messages.existingImage')); ?>

                                </label>
                                <div class="old_img">
                                    <img alt="Image" class="border border-1" src="<?php echo e(ImageService::getImageUrl(Config::get('constants.LOGO_FOLDER') . GeneralService::getSettings('favicon'))); ?>" width="100">
                                </div>
                                <?php endif; ?>
                        </div>
                            <div class="col-lg-2  col-md-6">
                                <label class='image-preview-fav'><?php echo e(trans('messages.imagePreview')); ?></label>
                                <img id="image-preview-fav" class="image-preview-fav" alt="<?php echo e(trans('messages.imagePreview')); ?>" class="img-fluid rounded-circle" width="150px">
                            </div>
                        </div>

                   <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="header" class="font-medium fs-6 mb-3"><?php echo e(trans('messages.companyAddress')); ?><span class="red_lab"> *</span></label>
                                <?php echo e(html()->textarea('companyAddress')->attributes(['class' => 'form-control  formValidate', 'rows'=>5, 'placeholder' => trans("messages.companyAddress")])); ?>

                                <?php if($errors->has('companyAddress')): ?>
                                <span class="invalid-feedback" role="alert">
                                    <strong><?php echo e($errors->first('companyAddress')); ?></strong>
                                </span>
                                <?php endif; ?>
                            </div>
                        </div>
                      </div>
                        

                    <div class="row">
                    

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="header" class="font-medium fs-6 mb-3"><?php echo e(trans('messages.emailHeaderContent')); ?></label>
                                <?php echo e(html()->textarea('header')->attributes(['class' => 'form-control  formValidate',
                                            'placeholder' => trans("messages.emailHeaderContent")])); ?>

                                <?php if($errors->has('header')): ?>
                                <span class="invalid-feedback" role="alert">
                                    <strong><?php echo e($errors->first('header')); ?></strong>
                                </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="footer" class="font-medium fs-6 mb-3"><?php echo e(trans('messages.emailFooterContent')); ?></label>
                                <?php echo e(html()->textarea('footer')->attributes(['class' => 'form-control  formValidate',
                                            'placeholder' => trans("messages.emailFooterContent")])); ?>

                                <?php if($errors->has('footer')): ?>
                                <span class="invalid-feedback" role="alert">
                                    <strong><?php echo e($errors->first('footer')); ?></strong>
                                </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>


                    <?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->hasPermission("SETTING_MANAGEMENT_EDIT"))) : ?>
                    <button type="submit" class="btn theme_btn bg_theme font-semibold border-0 fs-6 px-sm-5"><?php echo e(trans('messages.submit')); ?></button>
                    <?php echo e(html()->closeModelForm()); ?>

                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo e(asset('assets/js/custom-user-define-fun.js')); ?>"></script>
<script>
    $(document).ready(function() {
        CKEDITOR.replace('header', {
            allowedContent: true,
            height: 320
        });

        CKEDITOR.replace('footer', {
            allowedContent: true,
            height: 320
        });

    });
</script>
<script>
	$(function() {
		window.formReference = $("#settingForm").validate({
			rules: {
                websiteTitle: {
					required: true,
                    minlength: 2,
                    maxlength: 30,
                    notNumber: true,
                    validName: true,
				},
              
                companyEmail:{
                  
                    email: true,
                    maxlength: 100,
                    emailPattern: true
                },
                companyAddress: {
					
					minlength: 2,
					maxlength: 100,
				},
             
                UploadServerType: {
					required: true,
				},
             
                password_generation: {
					required: true,
				},
                two_factor_authentication: {
					required: true,
				},
              
                thirdPartyJs: {
					url: true,
				},
                'logo_image':{
				     		extension: "jpeg|png|jpg|gif"
				          },
                'favicon_image':{
				     		extension: "jpeg|png|jpg|gif|ico"
				          },
			},
			messages: {
				websiteTitle: {
                    minlength: "<?php echo e(trans('messages.min2Max30')); ?>",
                    maxlength: "<?php echo e(trans('messages.min2Max30')); ?>",
                    notNumber: "<?php echo e(trans('messages.notNumberMessage')); ?>"
				},
                companyPhone: {
                    minlength: "<?php echo e(trans('messages.phoneNumRangeValidationMessage')); ?>",
                    maxlength: "<?php echo e(trans('messages.phoneNumRangeValidationMessage')); ?>"
                },
                companyAddress: {
                    required:'Enter company address',
				},
                UploadServerType: {
                    required:'Select Upload Sever Type',
				},
                'logo_image': "Select a image file as jpg, jpeg, png",
                'favicon_image': "Select a image file as jpg, jpeg, png",
			},
			errorClass: "help-inline",
			errorElement: "span",
			highlight: function(element, errorClass, validClass) {
				$(element).parents('.form-group').addClass('error');
				//$('.invalid-feedback').hide();
			},
			unhighlight: function(element, errorClass, validClass) {
				$(element).parents('.form-group').removeClass('error');
				$(element).parents('.form-group').addClass('success');
			},
		});

        checkImage(false);
        $("#imageInput").change(function(e) {
            checkImage(true);
        });

        checkImageFav(false);
        $("#imageInputFav").change(function(e) {
            checkImageFav(true);
        });
	});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.default_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/settings/list.blade.php ENDPATH**/ ?>