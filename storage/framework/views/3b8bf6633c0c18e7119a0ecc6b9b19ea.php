<div class="row">
    
    <div class="col-md-6">
        <div class="form-group">
            <label for="capacity"><?php echo e(trans('messages.capacity')); ?> <span class="red_lab">
                    *</span></label>
            <?php echo e(html()->text('capacity', null)->attributes(['class' => 'form-control', 'placeholder' => trans('messages.capacity'), 'enctype' => 'multipart/form-data'])->open()); ?>

            <?php if($errors->has('capacity')): ?>
                <span class="invalid-feedback" role="alert">
                    <strong><?php echo e($errors->first('capacity')); ?></strong>
                </span>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="brand_id"><?php echo e(trans('messages.brand_name_of_vehicle')); ?> <span class="red_lab">
                    *</span></label>

            <div class="errorPlacement2">
                <?php echo e(html()->select('brand_id', $brands, $model ?? '')->attributes([
                        'class' => 'form-control select2',
                        'placeholder' => trans('message.brand'),
                        'required' => 'required',
                        'id' => 'brand_id',
                    ])); ?>

            </div>

            <?php if($errors->has('brand_id')): ?>
                <span class="invalid-feedback" role="alert">
                    <strong><?php echo e($errors->first('brand_id')); ?></strong>
                </span>
            <?php endif; ?>
        </div>
    </div>


    <div class="col-md-6">
        <div class="form-group">
            <label for="model_id"><?php echo e(trans('messages.vehicle_model')); ?> <span class="red_lab">
                    *</span></label>

            <div class="errorPlacement2">
                <?php echo e(html()->select('model_id', $models, $model ?? '')->attributes([
                        'class' => 'form-control select2',
                        'required' => 'required',
                        'id' => 'model_id',
                    ])); ?>

            </div>

            <?php if($errors->has('model_id')): ?>
                <span class="invalid-feedback" role="alert">
                    <strong><?php echo e($errors->first('model_id')); ?></strong>
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
    <a href="<?php echo e(route($listRoute)); ?>" class="btn px-sm-5 font-semibold border_btn"><em class="icon-refresh"></em>
        <?php echo e(trans('messages.cancel')); ?></a>
</div>

<script src="<?php echo e(asset('assets/js/custom-user-define-fun.js')); ?>"></script>
<script>
    $(document).ready(function() {
        $('#brand_id').change(function() {
            var brand_id = $(this).val();
            if (brand_id) {
                $.ajax({
                    url: '<?php echo e(route("get.models", ":brand_id")); ?>'.replace(':brand_id', brand_id),
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#model_id').empty();
                        $.each(data, function(key, value) {
                            $('#model_id').append('<option value="'+ key +'">'+ value +'</option>');
                        });
                        $('#model_id').trigger('change');
                    }
                });
            } else {
                $('#model_id').empty();
            }
        });

        // Trigger change event if a brand is already selected
        if ($('#brand_id').val()) {
            $('#brand_id').trigger('change');
        }

        $('.select2').select2();

        $('#renewal_period,#due_renewal_date,#service_date').datepicker({
            format: "yyyy-mm-dd",
            clearBtn: true,
            autoclose:true,
            todayHighlight: true,
        })


        $('#expiry_date').datepicker({
            format: "yyyy-mm-dd",
            clearBtn: true,
            autoclose:true,
            todayHighlight: true,
            startDate: new Date()
        })
        OnlyAllowFloatNumber('#cost,#sum_assured_value')

    })
</script>
<?php if(env('ENABLE_CLIENT_VALIDATION')): ?>
    <script>
        window.range_ = [''];
        const validateName = "#";
        const model_id = "<?php echo e(isset($model) ? $model->id : null); ?>";

        $(function() {

            window.formReference = $("#enginecapacityForm").validate({
                rules: {

                    capacity: {
                        required: true,
                        minlength: 2,
                        maxlength: 30,
                        notNumber: true,
                    }
                },
                messages: {
                  
                    capacity: {
                        minlength: "<?php echo e(trans('messages.min2Max10')); ?>",
                        maxlength: "<?php echo e(trans('messages.min2Max10')); ?>",

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


        });
    </script>

<?php endif; ?>
<?php /**PATH /var/www/html/resources/views/admin/enginecapacity/form.blade.php ENDPATH**/ ?>