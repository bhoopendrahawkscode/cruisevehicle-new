<div class="mb-3 translation-tabs">
    <ul class="nav nav-tabs border-0" role="tabList">
        <?php $i = 0; ?>
        <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $laguageRowTop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li class="nav-item">
            <a class="nav-link language_tab <?php echo e($laguageRowTop->locale); ?>

                    <?php if($i == 0){echo " active"; } ?>" id="tabId<?php echo e($laguageRowTop->id); ?>" data-bs-toggle="pill" href="#custom-tabs-<?php echo e($laguageRowTop->id); ?>" role="tab" aria-selected="true"><?php echo e($laguageRowTop->name); ?>

            </a>
        </li>
        <?php $i++; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
    <div class="tab-content" id="tab-parent">
        <?php $j = 0; ?>
        <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $languageRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div language="<?php echo e($languageRow->id); ?>" class="tab-pane   fade <?php if($j == 0){echo " active show"; } ?>" id="custom-tabs-<?php echo e($languageRow->id); ?>" role="tabContent">
            <div class="row">
                <div class="col-12 col-sm-6">
                    <div class="clearValidation form-group
                            <?php echo e($errors->has('title[' . $languageRow->id . ']') ? 'has-error': ''); ?>">
                        <label for=" <?php echo e(trans('messages.title')); ?> <?php echo e('(' . $languageRow->title . ')'); ?>">
                            <?php echo e(trans('messages.title')); ?> <?php echo e('(' . $languageRow->name . ')'); ?>

                            <span class="red_lab"> *</span>
                        </label>
                        <?php echo e(html()->text('title[' . $languageRow->id . ']')->attributes(['class' => 'form-control languages_title formValidate',
                            'placeholder' => trans("messages.title")])); ?>

                        <?php if($errors->has('title.' . $languageRow->id)): ?>
                        <span class="invalid-feedback " role="alert">
                            <strong><?php echo e($errors->first('title.' . $languageRow->id)); ?></strong>
                        </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-6">
                    <div class="clearValidation form-group
                            <?php echo e($errors->has('banner_link[' . $languageRow->id . ']') ? 'has-error': ''); ?>">
                        <label for=" <?php echo e(trans('Link')); ?> <?php echo e('(' . $languageRow->banner_link . ')'); ?>">
                            <?php echo e(trans('Banner Link')); ?> <?php echo e('(' . $languageRow->name . ')'); ?>

                            <span class="red_lab"> *</span>
                        </label>
                        <?php echo e(html()->text('banner_link[' . $languageRow->id . ']')->attributes(['class' => 'form-control languages_banner_link formValidate',
                            'placeholder' => trans("Banner Link")])); ?>

                        <?php if($errors->has('banner_link.' . $languageRow->id)): ?>
                        <span class="invalid-feedback " role="alert">
                            <strong><?php echo e($errors->first('banner_link.' . $languageRow->id)); ?></strong>
                        </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-6">
                    <div class="clearValidation form-group
                            <?php echo e($errors->has('description[' . $languageRow->id . ']') ? 'has-error': ''); ?>">
                        <?php echo e(html()->hidden('id[' . $languageRow->id . ']')); ?>

                        <label for=" <?php echo e(trans('messages.content')); ?> <?php echo e('(' . $languageRow->name . ')'); ?>">
                            <?php echo e(trans('messages.content')); ?> <?php echo e('(' . $languageRow->name . ')'); ?>

                            <span class="red_lab"> *</span>
                        </label>
                        <?php echo e(html()->text('description[' . $languageRow->id . ']')
                            ->attributes(['class' => 'form-control languages_description formValidate',
                            'placeholder' => trans("messages.content")])); ?>

                        <?php if($errors->has('description.' . $languageRow->id)): ?>
                        <span class="invalid-feedback " role="alert">
                            <strong><?php echo e($errors->first('description.' . $languageRow->id)); ?></strong>
                        </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>


        </div>
        <?php $j++; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>

<div class="row">
    <div class="col-lg-4 col-md-6 upload_img mb-5">
        <label><?php echo e(trans('messages.image')); ?>

            <?php if(!isset($data['recordId'])): ?>
            <span class="red_lab"> *</span>
            <?php endif; ?>
        </label>
        <input name="image" type="file" id="imageInput" accept="image/*">
        <span id="file-size-error" class="text-danger"></span>
        <?php if($errors->has('image')): ?>
        <span class="invalid-feedback error" role="alert">
            <strong><?php echo e($errors->first('image')); ?></strong>
        </span>
        <?php endif; ?>
        <span class="imageHint" style="display: block"><?php echo e(trans("messages.$title.ImageHint")); ?></span>
        <?php if (isset($data['recordId'])) { ?>
            <label class="exist_image"><?php echo e(trans('messages.existingImage')); ?>

            </label>
            <div class="old_img">
                <img alt="Image" class="border border-1" src="<?php echo e($data['thumbImage']); ?>" width="100">
            </div>
        <?php } ?>
    </div>
    <div class="col-lg-2  col-md-6">
        <label class='image-preview'><?php echo e(trans('messages.imagePreview')); ?></label>
        <img id="image-preview" class="image-preview" alt="<?php echo e(trans('messages.imagePreview')); ?>" class="img-fluid rounded-circle" width="150px">
    </div>
</div>

<?php if(env('ENABLE_CLIENT_VALIDATION')): ?>
<script>
    $(function() {
        window.formReference = $("#<?php echo $formId; ?>").validate({
            ignore: [],
            rules: {},
            errorElement: "span",
            highlight: function(element) {
                $(element).parents('.form-group').addClass('error');
            },
            unhighlight: function(element) {
                let lang = $(element).parent().parent().parent().parent().attr('language');
                $(element).parents('.form-group').removeClass('error');
                $(element).parents('.form-group').addClass('success');
                removeTabError2(lang);
            },
        });

        $('.languages_title').each(function(e) {
            $(this).rules('add', {
                required: true,
                notNumber: true,
               
            })
        });
        $('.languages_banner_link').each(function(e) {
            $(this).rules('add', {
                required: true,
                minlength: 2,
                maxlength: 30,
                notNumber: true,
                messages: {
                    minlength: "<?php echo e(trans('messages.minGeneral')); ?>",
                    maxlength: "<?php echo e(trans('messages.maxGeneral')); ?>",
                    notNumber: "<?php echo e(trans('messages.notNumberMessage')); ?>"
                }
            })
        });

        $('.languages_description').each(function(e) {
            $(this).rules('add', {
                required: true,
                minlength: 2,
                notNumber: true,
                messages: {
                    minlength: "<?php echo e(trans('messages.minGeneral')); ?>",
                    notNumber: "<?php echo e(trans('messages.notNumberMessage')); ?>"
                }
            })
        });



        $('#btnSubmit').on('click', function(e) {
            $("#<?php echo $formId; ?>").valid();
            removeTabError();
        });
        checkImage(false);
        $("#imageInput").change(function(e) {
            checkImage(true);
        });
    });
</script>
<?php if (isset($data['recordId'])) { ?>
    <script>
        $(function() {
            $("#imageInput").rules('add', {
                extension: "<?php echo e(Config::get('constants.validImageExtensions')); ?>",
                filesize: "<?php echo e(Config::get('constants.maxImageSizeJs')); ?>",
            });
        });
    </script>
<?php } else { ?>
    <script>
        $(function() {
            $("#imageInput").rules('add', {
                required: true,
                extension: "<?php echo e(Config::get('constants.validImageExtensions')); ?>",
                filesize: "<?php echo e(Config::get('constants.maxImageSizeJs')); ?>",
            });

        });
    </script>
<?php } ?>
<?php endif; ?>
<?php /**PATH /var/www/html/resources/views/admin/banner/form.blade.php ENDPATH**/ ?>