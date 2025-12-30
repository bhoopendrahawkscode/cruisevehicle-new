<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="name"><?php echo e(trans('Fuel Used Name')); ?> <span class="red_lab">
                    *</span></label>
            <?php echo e(html()->text('name', null)->attributes(['class' => 'form-control', 'placeholder' => trans('Fuel Used Name'), 'enctype' => 'multipart/form-data'])->open()); ?>

            <?php if($errors->has('name')): ?>
                <span class="invalid-feedback" role="alert">
                    <strong><?php echo e($errors->first('name')); ?></strong>
                </span>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="cost"><?php echo e(trans('Fuel Cost')); ?> <span class="red_lab">
                    *</span></label>
            <?php echo e(html()->text('cost', null)->attributes(['class' => 'form-control', 'placeholder' => trans('Fuel cost')])->open()); ?>

            <?php if($errors->has('cost')): ?>
                <span class="invalid-feedback" role="alert">
                    <strong><?php echo e($errors->first('cost')); ?></strong>
                </span>
            <?php endif; ?>
        </div>
    </div>
   <div class="col-lg-4 col-md-6 upload_img mb-5 d-none">
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
        <?php if (isset($model)) { ?>
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
<?php if(env('ENABLE_CLIENT_VALIDATION')): ?>
    <script>
        window.range_ = [''];
        const validateName = "#";
        const model_id = "<?php echo e(isset($model) ? $model->id : null); ?>";

        $(function() {
         
            window.formReference = $("#fueltypeForm").validate({
                rules: {

                    name: {
                        required: true,
                        minlength: 2,
                        maxlength: 30,
                        notNumber: true,
                       
                    }


                },
                messages: {
                    name: {
                        minlength: "<?php echo e(trans('messages.min2Max30')); ?>",
                        maxlength: "<?php echo e(trans('messages.min2Max30')); ?>",
                    
                    },
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

<?php endif; ?><?php /**PATH /var/www/html/resources/views/admin/fueltype/form.blade.php ENDPATH**/ ?>