<div class="row">

    <div class="col-md-6">
        <div class="form-group">
            <label for="name"><?php echo e(trans('Service Provider Name')); ?> <span class="red_lab">
                    *</span></label>
            <?php echo e(html()->text('name', null)->attributes(['class' => 'form-control', 'placeholder' => trans('Service Provider Name')])->open()); ?>

            <?php if($errors->has('name')): ?>
                <span class="invalid-feedback" role="alert">
                    <strong><?php echo e($errors->first('name')); ?></strong>
                </span>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="name"><?php echo e(trans('messages.street')); ?> <span class="red_lab">
                    *</span></label>
            <?php echo e(html()->text('street', null)->attributes(['class' => 'form-control', 'placeholder' => trans('messages.street')])->open()); ?>

            <?php if($errors->has('street')): ?>
                <span class="invalid-feedback" role="alert">
                    <strong><?php echo e($errors->first('street')); ?></strong>
                </span>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="name"><?php echo e(trans('messages.town')); ?> <span class="red_lab">
                    *</span></label>
            <?php echo e(html()->text('town', null)->attributes(['class' => 'form-control', 'placeholder' => trans('messages.town')])->open()); ?>

            <?php if($errors->has('town')): ?>
                <span class="invalid-feedback" role="alert">
                    <strong><?php echo e($errors->first('town')); ?></strong>
                </span>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="name"><?php echo e(trans('Address')); ?> <span class="red_lab">
                    *</span></label>
            <?php echo e(html()->text('address', null)->attributes(['class' => 'form-control','required'=>'required' ,'placeholder' => trans('Address')])->open()); ?>

            <?php if($errors->has('address')): ?>
                <span class="invalid-feedback" role="alert">
                    <strong><?php echo e($errors->first('address')); ?></strong>
                </span>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group clearValidation3">
            <label for="phone"><?php echo e(trans('messages.phoneNo')); ?>

                <span class="red_lab">*</span></label>
            <div class="row gx-2 gx-md-3">
                <div class="col-sm-6 mb-4 mb-sm-0 errorPlacement2">
                    <?php echo e(html()->select('country_code', $phonecode, isset($model->country_code)?$model->country_code:'')->attributes([
                            'class' => 'form-control select2',
                            'placeholder' => trans('messages.phoneNo'),
                            'required' => 'required',
                        ])); ?>


                        <?php if($errors->has('country_code')): ?>
                        <span class="invalid-feedback " role="alert">
                            <strong><?php echo e($errors->first('country_code')); ?></strong>
                        </span>
                     <?php endif; ?>
                </div>
                <div class="col-sm-6">
                    <?php echo e(html()->text('mobile_no', null)->attributes(['class' => 'form-control', 'placeholder' => trans('messages.phoneNo')])); ?>

                    <?php if($errors->has('mobile_no')): ?>
                        <span class="invalid-feedback " role="alert">
                            <strong><?php echo e($errors->first('mobile_no')); ?></strong>
                        </span>
                     <?php endif; ?>
                </div>
           
            </div>
            
        </div>
    </div>


   <div class="col-lg-4 col-md-6 upload_img mb-5 d-none" >
        <label><?php echo e(trans('messages.image')); ?>

            <?php if(!isset($model)): ?>
            <?php endif; ?>
        </label>
        <input name="image" type="file" id="imageInput" accept="image/*">
        <span id="file-size-error" class="text-danger"></span>
        <?php if($errors->has('image')): ?>
            <span class="invalid-feedback error" role="alert">
                <strong><?php echo e($errors->first('image')); ?></strong>
            </span>
        <?php endif; ?>
        <span class="imageHint" style="display: block"></span>
        <?php if (isset($model->thumbImage) && empty($model->thumbImage)) { ?>
        <label class="exist_image"><?php echo e(trans('messages.existingImage')); ?>

        </label>
        <div class="old_img">
            <img alt="Image" class="border border-1" src="<?php echo e($model->thumbImage); ?>" width="100">
        </div>
        <?php } ?>
    </div>
    <div class="col-lg-2  col-md-6">
        <label class='image-preview'><?php echo e(trans('messages.imagePreview')); ?></label>
        <img id="image-preview" class="image-preview" alt="<?php echo e(trans('messages.imagePreview')); ?>"
            class="img-fluid rounded-circle" width="150px">
    </div>


</div>

<div class="form-group d-flex gap-3">
    <button type="submit"
        class="btn theme_btn bg_theme font-semibold border-0 fs-6 px-sm-5"><?php echo e(trans('messages.submit')); ?></button>
    <a href="<?php echo e(route($listRoute)); ?>" class="btn px-sm-5 font-semibold border_btn"><em
            class="icon-refresh"></em> <?php echo e(trans('messages.cancel')); ?></a>
</div>

<script src="<?php echo e(asset('assets/js/custom-user-define-fun.js')); ?>"></script>
<script integrity="" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
        window.range_ = [''];
        const validateName = "#";
        const model_id = "<?php echo e(isset($model) ? $model->id : null); ?>";

        $(function() {

            window.formReference = $("#ServiceProviderForm").validate({
                rules: {

                    name: {
                        required: true,
                        minlength: 2,
                        maxlength: 30,
                        notNumber: true,

                    },
                    street: {
                        required: true,
                        minlength: 2,
                        maxlength: 30,
                        notNumber: true,

                    },
                    town: {
                        required: true,
                        minlength: 2,
                        maxlength: 30,
                        notNumber: true,

                    },
                    address: {
                        required: true,
                        minlength: 2,
                        maxlength: 160,
                        notNumber: true,

                    },
                    mobile_no: {
                            required: true,
                            number: true,
                            minlength: 6,
                            maxlength: 12,
                            onlyInteger: true,
                            nonZeroPhoneNumber: true
                        },


                },
                messages: {
                    name: {
                        minlength: "<?php echo e(trans('messages.min2Max30')); ?>",
                        maxlength: "<?php echo e(trans('messages.min2Max30')); ?>",

                    },
                },
                messages: {
                    street: {
                        minlength: "<?php echo e(trans('messages.min2Max30')); ?>",
                        maxlength: "<?php echo e(trans('messages.min2Max30')); ?>",

                    },
                },
                messages: {
                    town: {
                        minlength: "<?php echo e(trans('messages.min2Max30')); ?>",
                        maxlength: "<?php echo e(trans('messages.min2Max30')); ?>",

                    },
                },
                messages: {
                    address: {
                        minlength: "<?php echo e(trans('messages.min2Max160')); ?>",
                        maxlength: "<?php echo e(trans('messages.min2Max160')); ?>",

                    },
                },
                mobile_no: {
                            minlength: "<?php echo e(trans('messages.phoneNumRangeValidationMessage')); ?>",
                            maxlength: "<?php echo e(trans('messages.phoneNumRangeValidationMessage')); ?>"
                        },
                errorClass: "help-inline",
                errorElement: "span",
                highlight: function(element, errorClass, validClass) {
                    $(element).parents('.form-group').addClass('error');

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


        });
    </script>

    <?php if(isset($model)): ?>
        <script>
            $(function() {

                $("#imageInput").rules('add', {
                    extension: "<?php echo e(Config::get('constants.validImageExtensions')); ?>",
                    filesize: "<?php echo e(Config::get('constants.maxImageSizeJs')); ?>",
                });
            });
        </script>
    <?php else: ?>
        <script>
            $(function() {

                $("#imageInput").rules('add', {
                    // required: true,
                    extension: "<?php echo e(Config::get('constants.validImageExtensions')); ?>",
                    filesize: "<?php echo e(Config::get('constants.maxImageSizeJs')); ?>",
                });

            });
        </script>
    <?php endif; ?>



<script>
    $(document).ready(function() {
        $('.select2').select2();

    })
</script>
<?php /**PATH /var/www/html/resources/views/admin/service-provider/form.blade.php ENDPATH**/ ?>