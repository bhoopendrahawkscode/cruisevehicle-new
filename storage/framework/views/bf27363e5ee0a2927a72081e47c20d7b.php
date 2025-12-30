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
                            <?php echo e($errors->has('question[' . $languageRow->id . ']') ? 'has-error': ''); ?>">
                        <?php echo e(html()->hidden('id[' . $languageRow->id . ']')); ?>

                        <label for=" <?php echo e(trans('messages.question')); ?> <?php echo e('(' . $languageRow->name . ')'); ?>">
                            <?php echo e(trans('messages.question')); ?> <?php echo e('(' . $languageRow->name . ')'); ?>

                            <span class="red_lab"> *</span>
                        </label>
                        <?php echo e(html()->text('question[' . $languageRow->id . ']')
                            ->attributes(['class' => 'form-control languages_question formValidate',
                            'placeholder' => trans("messages.question")])); ?>

                        <?php if($errors->has('question.' . $languageRow->id)): ?>
                        <span class="invalid-feedback " role="alert">
                            <strong><?php echo e($errors->first('question.' . $languageRow->id)); ?></strong>
                        </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-6">
                    <div class="clearValidation form-group
                            <?php echo e($errors->has('answer[' . $languageRow->id . ']') ? 'has-error': ''); ?>">
                        <?php echo e(html()->hidden('id[' . $languageRow->id . ']')); ?>

                        <label for=" <?php echo e(trans('messages.answer')); ?> <?php echo e('(' . $languageRow->answer . ')'); ?>">
                            <?php echo e(trans('messages.answer')); ?> <?php echo e('(' . $languageRow->name . ')'); ?>

                            <span class="red_lab"> *</span>
                        </label>
                        <?php echo e(html()->text('answer[' . $languageRow->id . ']')
                            ->attributes(['class' => 'form-control languages_answer formValidate',
                            'placeholder' => trans("messages.answer")])); ?>

                        <?php if($errors->has('answer.' . $languageRow->id)): ?>
                        <span class="invalid-feedback " role="alert">
                            <strong><?php echo e($errors->first('answer.' . $languageRow->id)); ?></strong>
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
            rules: {
                faqcategories_id: {
                    required: true,
                }
            },
            ignore: [],
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
        $('.languages_question').each(function(e) {
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
        $('.languages_answer').each(function(e) {
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
<?php /**PATH /var/www/html/resources/views/admin/faq/form.blade.php ENDPATH**/ ?>