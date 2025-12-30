
<?php $__env->startSection('content'); ?>

<script>
    $(function() {
        window.emojiPicker = new EmojiPicker({
            emojiable_selector: '[data-emojiable=true]',
            assetsPath: "<?php echo e(asset('/assets/images/')); ?>",
            popupButtonClasses: 'fa fa-smile-o'
        });
        window.emojiPicker.discover();
    });
</script>
<style type="text/css">
.fa-smile-o:before {
    content: "\f118";
}
</style>
<script src="<?php echo e(asset('/assets/js/js_emoji/config.min.js')); ?>"></script>
<script src="<?php echo e(asset('/assets/js/js_emoji/util.min.js')); ?>"></script>
<script src="<?php echo e(asset('/assets/js/js_emoji/jquery.emojiarea.min.js')); ?>"></script>
<script src="<?php echo e(asset('/assets/js/js_emoji/emoji-picker.min.js')); ?>"></script>
<link href="<?php echo e(asset('/assets/css/emoji.css')); ?>" rel="stylesheet">

<div class="header d-flex align-items-center">
    <h1 class="page-header">
        <?php echo e(trans("messages.sendNotification")); ?>

    </h1>
    <ol class="breadcrumb ms-auto mb-0">
        <li><a href="<?php echo e(Route('admin.dashboard')); ?>"><em class="fa fa-dashboard"></em> <?php echo e(trans("messages.dashboard")); ?></a></li>
        <li class="active"><?php echo e(trans("messages.sendNotification")); ?></li>
    </ol>
</div>
<div id="page-inner">
    <div class="panel panel-default">
        <div class="panel-body">
            <?php echo e(html()->modelForm(null, 'POST')->route("admin.SendNotification")
             ->attributes(['id'=>"pushNotifyForm",'class'=>'form-inline','role'=>'form','autocomplete' => 'off'])->open()); ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group  <?php echo ($errors->first('title')) ? 'has-error' : ''; ?>">
                        <label for="title"><?php echo e(trans('messages.notificationTitle')); ?><span class="red_lab">*</span></label>
                        <?php echo e(html()->text('title',null)
                        ->attributes(['class' => 'form-control',
                        'placeholder' => trans("messages.notificationTitle")])); ?>

                        <div class="error-message help-inline">
                            <?php echo $errors->first('title'); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6"></div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group  <?php echo ($errors->first('user_type')) ? 'has-error' : ''; ?>">
                        <label for="user_type"><?php echo e(trans('messages.selectUserType')); ?><span class="red_lab">*</span></label>
                        <?php echo e(html()->select('user_type',[null=>trans('messages.selectUserType') ] + $userType)
                        ->attributes(['id'=>'user_type','class' => 'form-control'])); ?>

                        <div class="error-message help-inline">
                            <?php echo $errors->first('user_type'); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6"></div>
            </div>
            <div class="row" id="Particular_User">
                <div class="col-md-6">
                    <div class="form-group   <?php echo ($errors->first('user_id')) ? 'has-error' : ''; ?>">
                        <label for="user_id"><?php echo e(trans('messages.selectParticularUser')); ?><span class="red_lab">*</span></label>
                        <?php $userList = user_list();
                        ?>
                        <?php echo e(html()->select('user_id[]',$userList)
                        ->attributes(['id'=>'mySelect','multiple'=>'multiple','class' => 'form-control','style'=>'width:300px;'])); ?>

                        <div class="error-message help-inline">
                            <?php echo $errors->first('user_id'); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6"></div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group  <?php echo ($errors->first('description')) ? 'has-error' : ''; ?>">
                        <label for="description" class='description_data'><?php echo e(trans('messages.description')); ?> <span class="red_lab">*</span></label>
                        <?php echo e(html()->textarea('description',null)
                        ->attributes(['class'=>'form-control text_area','data-emojiable'=>'true', 'rows' => 4, 'cols' => 54,'maxlength'=>'350'])); ?>

                        <div class="error-message desc_error help-inline">
                            <?php echo $errors->first('description'); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6"></div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <button type="submit" class="btn theme_btn bg_theme font-semibold border-0 fs-6 px-sm-5"><?php echo e(trans('messages.submit')); ?></button>

                    </div>
                </div>
            </div>
            <?php echo e(html()->closeModelForm()); ?>

        </div>
    </div>
</div>
<?php if(env('ENABLE_CLIENT_VALIDATION')): ?>
<script>
   $(document).ready(function() {
    function validateTextArea() {
        var textLength = $('.text_area').text().trim().length;
        var minLength = 2;
        var maxLength = 350;

        if (textLength < 1) {
            $('.desc_error').show();
            $('.description_data').addClass('error');
            $('.desc_error').text('This field is required.');
        } else if (textLength < minLength) {
            $('.desc_error').show();
            $('.description_data').addClass('error');
            $('.desc_error').text('Please enter at least ' + minLength + ' characters.');
        } else if (textLength > maxLength) {
            $('.desc_error').show();
            $('.description_data').addClass('error');
            $('.desc_error').text('Cannot exceed ' + maxLength + ' characters.');
        } else {
            $('.desc_error').hide();
            $('.description_data').removeClass('error');
            $('.desc_error').text('');
        }
    }

    $('.text_area').on('input keyup keydown', function() {

        validateTextArea();
    });

    $('#pushNotifyForm').submit(function(event) {
        validateTextArea();

        if ($('.desc_error').is(':visible')) {
            event.preventDefault();
        }
    });
});




    $(function() {
        window.formReference = $("#pushNotifyForm").validate({
            rules: {
                title: {
                    required: true,
                    minlength: 2,
                    maxlength: 200,
                    notNumber: true,

                },
                user_id: {
                    required: true,
                },
                user_type: {
                    required: true,
                },

            },
            messages: {},
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
    });
</script>
<?php endif; ?>
<link href="<?php echo e(asset('/assets/css/select2.min.css')); ?>" rel="stylesheet">
<script src="<?php echo e(asset ('/assets/js/select2.min.js')); ?>"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#mySelect').select2({
    search: true,
    placeholder: '<?php echo e(trans('messages.selectParticularUser')); ?>',
    multiple: true, // Enable multi-select
    closeOnSelect: false // Keep the dropdown open when selecting
});


    });
</script>
<?php if (isset($selected_user_type) && $selected_user_type > 0) { ?>
    <script>
        var selected_user_type = '<?php echo $selected_user_type; ?>';
        var commaSeparatedString = '<?php echo $selected_user_id; ?>';

        $(document).ready(function() {
            var selected_user_id = commaSeparatedString.split(',');
            $('#user_type').val(selected_user_type);
            $('#mySelect').val(selected_user_id).trigger("change");
            selectUserType();
        });
    </script>
<?php } ?>
<script>
    $(document).ready(function() {
        $('#user_type').change(function() {
            selectUserType();
        });
    });

    function selectUserType() {
        if ($('#user_type').val() == '2') {
            $('#Particular_User').show();
        } else {
            $('#Particular_User').hide();
        }
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.default_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/user/push_notification.blade.php ENDPATH**/ ?>