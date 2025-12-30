<div class="row">

    <div class="col-md-6">
        <div class="form-group">
            <label for="name">{{ trans('Service Provider Name') }} <span class="red_lab">
                    *</span></label>
            {{ html()->text('name', null)->attributes(['class' => 'form-control', 'placeholder' => trans('Service Provider Name')])->open() }}
            @if ($errors->has('name'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="name">{{ trans('messages.street') }} <span class="red_lab">
                    *</span></label>
            {{ html()->text('street', null)->attributes(['class' => 'form-control', 'placeholder' => trans('messages.street')])->open() }}
            @if ($errors->has('street'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('street') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="name">{{ trans('messages.town') }} <span class="red_lab">
                    *</span></label>
            {{ html()->text('town', null)->attributes(['class' => 'form-control', 'placeholder' => trans('messages.town')])->open() }}
            @if ($errors->has('town'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('town') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="name">{{ trans('Address') }} <span class="red_lab">
                    *</span></label>
            {{ html()->text('address', null)->attributes(['class' => 'form-control','required'=>'required' ,'placeholder' => trans('Address')])->open() }}
            @if ($errors->has('address'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('address') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group clearValidation3">
            <label for="phone">{{ trans('messages.phoneNo') }}
                <span class="red_lab">*</span></label>
            <div class="row gx-2 gx-md-3">
                <div class="col-sm-6 mb-4 mb-sm-0 errorPlacement2">
                    {{ html()->select('country_code', $phonecode, isset($model->country_code)?$model->country_code:'')->attributes([
                            'class' => 'form-control select2',
                            'placeholder' => trans('messages.phoneNo'),
                            'required' => 'required',
                        ]) }}

                        @if ($errors->has('country_code'))
                        <span class="invalid-feedback " role="alert">
                            <strong>{{ $errors->first('country_code') }}</strong>
                        </span>
                     @endif
                </div>
                <div class="col-sm-6">
                    {{ html()->text('mobile_no', null)->attributes(['class' => 'form-control', 'placeholder' => trans('messages.phoneNo')]) }}
                    @if ($errors->has('mobile_no'))
                        <span class="invalid-feedback " role="alert">
                            <strong>{{ $errors->first('mobile_no') }}</strong>
                        </span>
                     @endif
                </div>
           
            </div>
            
        </div>
    </div>


   <div class="col-lg-4 col-md-6 upload_img mb-5 d-none" >
        <label>{{ trans('messages.image') }}
            @if (!isset($model))
            @endif
        </label>
        <input name="image" type="file" id="imageInput" accept="image/*">
        <span id="file-size-error" class="text-danger"></span>
        @if ($errors->has('image'))
            <span class="invalid-feedback error" role="alert">
                <strong>{{ $errors->first('image') }}</strong>
            </span>
        @endif
        <span class="imageHint" style="display: block"></span>
        <?php if (isset($model->thumbImage) && empty($model->thumbImage)) { ?>
        <label class="exist_image">{{ trans('messages.existingImage') }}
        </label>
        <div class="old_img">
            <img alt="Image" class="border border-1" src="{{ $model->thumbImage }}" width="100">
        </div>
        <?php } ?>
    </div>
    <div class="col-lg-2  col-md-6">
        <label class='image-preview'>{{ trans('messages.imagePreview') }}</label>
        <img id="image-preview" class="image-preview" alt="{{ trans('messages.imagePreview') }}"
            class="img-fluid rounded-circle" width="150px">
    </div>


</div>

<div class="form-group d-flex gap-3">
    <button type="submit"
        class="btn theme_btn bg_theme font-semibold border-0 fs-6 px-sm-5">{{ trans('messages.submit') }}</button>
    <a href="{{route($listRoute)}}" class="btn px-sm-5 font-semibold border_btn"><em
            class="icon-refresh"></em> {{ trans('messages.cancel') }}</a>
</div>

<script src="{{ asset('assets/js/custom-user-define-fun.js') }}"></script>
<script integrity="" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
        window.range_ = [''];
        const validateName = "#";
        const model_id = "{{ isset($model) ? $model->id : null }}";

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
                        minlength: "{{ trans('messages.min2Max30') }}",
                        maxlength: "{{ trans('messages.min2Max30') }}",

                    },
                },
                messages: {
                    street: {
                        minlength: "{{ trans('messages.min2Max30') }}",
                        maxlength: "{{ trans('messages.min2Max30') }}",

                    },
                },
                messages: {
                    town: {
                        minlength: "{{ trans('messages.min2Max30') }}",
                        maxlength: "{{ trans('messages.min2Max30') }}",

                    },
                },
                messages: {
                    address: {
                        minlength: "{{ trans('messages.min2Max160') }}",
                        maxlength: "{{ trans('messages.min2Max160') }}",

                    },
                },
                mobile_no: {
                            minlength: "{{ trans('messages.phoneNumRangeValidationMessage') }}",
                            maxlength: "{{ trans('messages.phoneNumRangeValidationMessage') }}"
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

    @if (isset($model))
        <script>
            $(function() {

                $("#imageInput").rules('add', {
                    extension: "{{ Config::get('constants.validImageExtensions') }}",
                    filesize: "{{ Config::get('constants.maxImageSizeJs') }}",
                });
            });
        </script>
    @else
        <script>
            $(function() {

                $("#imageInput").rules('add', {
                    // required: true,
                    extension: "{{ Config::get('constants.validImageExtensions') }}",
                    filesize: "{{ Config::get('constants.maxImageSizeJs') }}",
                });

            });
        </script>
    @endif



<script>
    $(document).ready(function() {
        $('.select2').select2();

    })
</script>
