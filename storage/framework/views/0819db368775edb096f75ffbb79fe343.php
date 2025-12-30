
<div class="mb-3 translation-tabs">
    <div class="row">
        <div class="col-12 col-sm-6">
            <div class="clearValidation form-group <?php echo e($errors->has('nameMain') ? 'has-error': ''); ?>">
                <label for="<?php echo e(trans('messages.name')); ?>">
                    <?php echo e(trans('messages.name')); ?>

                    <span class="red_lab"> *</span>
                </label>
                <?php echo e(html()->text('nameMain')->attributes(['class' => 'form-control nameMain formValidate',
                'placeholder' => trans("messages.name"),'readOnly'=>'readOnly'])); ?>

                <?php if($errors->has('nameMain')): ?>
                <span class="invalid-feedback " role="alert">
                    <strong><?php echo e($errors->first('nameMain')); ?></strong>
                </span>
                <?php endif; ?>
            </div>
        </div>
    </div>

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
        <div language="<?php echo e($languageRow->id); ?>" class="tab-pane fade <?php if($j == 0){echo " active show"; } ?>" id="custom-tabs-<?php echo e($languageRow->id); ?>" role="tabContent">
            <div class="row">
                <div class="col-12 col-sm-6">
                    <div class="clearValidation form-group
                                     <?php echo e($errors->has('title[' . $languageRow->id . ']') ? 'has-error': ''); ?>">
                        <?php echo e(html()->hidden('id[' . $languageRow->id . ']')); ?>

                        <label for=" <?php echo e(trans('messages.title')); ?> <?php echo e('(' . $languageRow->name . ')'); ?>">
                            <?php echo e(trans('messages.title')); ?> <span class="d-none"><?php echo e('(' . $languageRow->name . ')'); ?></span>
                            <span class="red_lab"> *</span>
                        </label>
                        <?php echo e(html()->text('title[' . $languageRow->id . ']')
                                    ->attributes(['class' => 'form-control languages_title formValidate',
                                    'placeholder' => trans("messages.title")])); ?>

                        <?php if($errors->has('title.' . $languageRow->id)): ?>
                        <span class="invalid-feedback " role="alert">
                            <strong><?php echo e($errors->first('title.' . $languageRow->id)); ?></strong>
                        </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="clearValidation form-group
                                     <?php echo e($errors->has('meta_title[' . $languageRow->id . ']') ? 'has-error': ''); ?>">
                        <label for=" <?php echo e(trans('messages.metaTitle')); ?> <?php echo e('(' . $languageRow->name . ')'); ?>">
                            <?php echo e(trans('messages.metaTitle')); ?> <span class="d-none"><?php echo e('(' . $languageRow->name . ')'); ?></span>
                            <span class="red_lab"> *</span>
                        </label>
                        <?php echo e(html()->text('meta_title[' . $languageRow->id . ']')
                                    ->attributes(['class' => 'form-control languages_meta_title formValidate',
                                    'placeholder' => trans("messages.metaTitle")])); ?>

                        <?php if($errors->has('meta_title.' . $languageRow->id)): ?>
                        <span class="invalid-feedback " role="alert">
                            <strong><?php echo e($errors->first('meta_title.' . $languageRow->id)); ?></strong>
                        </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-sm-6">
                    <div class="clearValidation form-group
                                     <?php echo e($errors->has('meta_keywords[' . $languageRow->id . ']') ? 'has-error': ''); ?>">
                        <label for=" <?php echo e(trans('messages.metaKeywords')); ?> <?php echo e('(' . $languageRow->name . ')'); ?>">
                            <?php echo e(trans('messages.metaKeywords')); ?> <span class="d-none"><?php echo e('(' . $languageRow->name . ')'); ?></span>
                            <span class="red_lab"> *</span>
                        </label>
                        <?php echo e(html()->text('meta_keywords[' . $languageRow->id . ']')
                                    ->attributes(['class' => 'form-control languages_meta_keywords formValidate',
                                    'placeholder' => trans("messages.metaKeywords")])); ?>

                        <?php if($errors->has('meta_keywords.' . $languageRow->id)): ?>
                        <span class="invalid-feedback " role="alert">
                            <strong><?php echo e($errors->first('meta_keywords.' . $languageRow->id)); ?></strong>
                        </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="clearValidation form-group
                                     <?php echo e($errors->has('meta_description[' . $languageRow->id . ']') ? 'has-error': ''); ?>">
                        <label for=" <?php echo e(trans('messages.metaDescription')); ?> <?php echo e('(' . $languageRow->name . ')'); ?>">
                            <?php echo e(trans('messages.metaDescription')); ?> <span class="d-none"><?php echo e('(' . $languageRow->name . ')'); ?></span>
                            <span class="red_lab"> *</span>
                        </label>
                        <?php echo e(html()->text('meta_description[' . $languageRow->id . ']')
                                    ->attributes(['class' => 'form-control languages_meta_description formValidate',
                                    'placeholder' => trans("messages.metaDescription")])); ?>

                        <?php if($errors->has('meta_description.' . $languageRow->id)): ?>
                        <span class="invalid-feedback " role="alert">
                            <strong><?php echo e($errors->first('meta_description.' . $languageRow->id)); ?></strong>
                        </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-6">
                    <div class="clearValidation form-group
                                     <?php echo e($errors->has('body[' . $languageRow->id . ']') ? 'has-error': ''); ?>">
                        <label for=" <?php echo e(trans('messages.cmsBody')); ?> <?php echo e('(' . $languageRow->name . ')'); ?>">
                            <?php echo e(trans('messages.cmsBody')); ?> <span class="d-none"><?php echo e('(' . $languageRow->name . ')'); ?></span>
                            <span class="red_lab"> *</span>
                        </label>
                        <?php echo e(html()->textarea('body[' . $languageRow->id . ']')
                                    ->attributes(['class' => 'form-control languages_body formValidate',
                                    'placeholder' => trans("messages.cmsBody")])); ?>

                        <?php if($errors->has('body.' . $languageRow->id)): ?>
                        <span class="invalid-feedback " role="alert">
                            <strong><?php echo e($errors->first('body.' . $languageRow->id)); ?></strong>
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
                minlength: 2,
                maxlength: 55,
                notNumber: true,
                messages: {
                    minlength: "<?php echo e(trans('messages.minGeneral')); ?>",
                    notNumber: "<?php echo e(trans('messages.notNumberMessage')); ?>"
                }
            })
        });

        $('.languages_meta_title').each(function(e) {
            $(this).rules('add', {
                required: true,
                minlength: 2,
                maxlength: 500,
                notNumber: true,
                messages: {
                    minlength: "<?php echo e(trans('messages.minGeneral')); ?>",
                    maxlength: "<?php echo e(trans('messages.max500')); ?>",
                    notNumber: "<?php echo e(trans('messages.notNumberMessage')); ?>"
                }
            })
        });

        $('.languages_meta_keywords').each(function(e) {
            $(this).rules('add', {
                required: true,
                minlength: 2,
                maxlength: 2000,
                notNumber: true,
                messages: {
                    minlength: "<?php echo e(trans('messages.minGeneral')); ?>",
                    maxlength: "<?php echo e(trans('messages.max2000')); ?>",
                    notNumber: "<?php echo e(trans('messages.notNumberMessage')); ?>"
                }
            })
        });

        $('.languages_meta_description').each(function(e) {
            $(this).rules('add', {
                required: true,
                minlength: 2,
                maxlength: 2000,
                notNumber: true,
                messages: {
                    minlength: "<?php echo e(trans('messages.minGeneral')); ?>",
                    maxlength: "<?php echo e(trans('messages.max2000')); ?>",
                    notNumber: "<?php echo e(trans('messages.notNumberMessage')); ?>"
                }
            })
        });


        $('.languages_body').each(function(e) {
            $(this).rules('add', {
                required: true,
                minlength: 2,
                maxlength: 50000,
                notNumber: true,
                messages: {
                    minlength: "<?php echo e(trans('messages.minGeneral')); ?>",
                    maxlength: "<?php echo e(trans('messages.max50000')); ?>"
                }
            })
        });

        $('#btnSubmit').on('click', function(e) {
            $("#<?php echo $formId; ?>").valid();
            removeTabError();
        });

    });
</script>

<?php endif; ?>
<script>
    $(document).ready(function() {
        $('.languages_body').each(function() {
            var Id = $(this).attr('id');
            var ckeditorInstance = CKEDITOR.replace(Id, {
                allowedContent: true,
                height: 320
            });
        });
        CKEDITOR.on('instanceReady', function() {
            setTimeout(function() {
                $.each(CKEDITOR.instances, function(instance) {
                    CKEDITOR.instances[instance].document.on("keyup", CK_jQ);
                    CKEDITOR.instances[instance].document.on("paste", CK_jQ);
                    CKEDITOR.instances[instance].document.on("keypress", CK_jQ);
                    CKEDITOR.instances[instance].document.on("blur", CK_jQ);
                    CKEDITOR.instances[instance].document.on("change", CK_jQ);
                });
            }, 2000);
        });

        function CK_jQ() {
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
        }
    });
</script>
<?php /**PATH /var/www/html/resources/views/admin/cms/form.blade.php ENDPATH**/ ?>