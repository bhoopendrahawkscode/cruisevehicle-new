@csrf
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="name">{{ trans('messages.name') }} <span class="red_lab">
                    *</span></label>
            {{ html()->text('name', null)->attributes(['class' => 'form-control', 'placeholder' => trans('messages.name'), 'enctype' => 'multipart/form-data'])->open() }}
            @if ($errors->has('name'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="offer_type"> {{ trans('messages.offer_type') }}<span class="red_lab">
                    *</span></label>
            <div class="row">
                <div class="col-12 errorPlacement2">
                    {{ html()->select('offer_type', $offer_type, $selected_offer_type)->attributes([
                            'class' => 'form-control',
                            'placeholder' => 'offer_type',
                            'required' => 'required',
                            'id' => 'offer_type',
                        ]) }}
                    <span class="invalid-feedback " role="alert">
                        <strong>{{ $errors->first('offer_type') }}</strong>
                    </span>
                </div>
            </div>
            @if ($errors->has('offer_type'))
                <span class="invalid-feedback " role="alert">
                    <strong>{{ $errors->first('offer_type') }}</strong>
                </span>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="discount">{{ trans('messages.discount') }} <span class="red_lab"> *</span></label>
            {{ html()->text('discount', null)->attributes(['class' => 'form-control', 'placeholder' => trans('messages.discount'), 'id' => 'discount']) }}
            @if ($errors->has('discount'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('discount') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group ">
            <label for="min_order_value">{{ trans('messages.min_order_value') }} <span class="red_lab">
                    *</span></label>
            {{ html()->text('min_order_value', null)->attributes(['class' => 'form-control', 'placeholder' => trans('messages.min_order_value')]) }}
            @if ($errors->has('min_order_value'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('min_order_value') }}</strong>
                </span>
            @endif
        </div>
    </div>


</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group clearValidation">
            <label for="discount_up_to">{{ trans('messages.discount_up_to') }} <span class="red_lab">
                    *</span></label>
            {{ html()->text('discount_up_to', null)->attributes(['class' => 'form-control', 'placeholder' => trans('messages.discount_up_to')]) }}
            @if ($errors->has('discount_up_to'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('discount_up_to') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group ">
            <label for="min_order_value">{{ trans('messages.code') }} <span class="red_lab">
                    *</span></label>
            {{ html()->text('code', null)->attributes(['class' => 'form-control','oninput'=>"this.value = this.value.replace(/\s+/g, '')" ,'placeholder' => trans('messages.code')]) }}
            @if ($errors->has('code'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('code') }}</strong>
                </span>
            @endif
        </div>
    </div>


</div>
<div class="row">

    <div class="col-md-3">
        <div class="form-group ">
            <label for="start_date">{{ trans('messages.start_date') }} <span class="red_lab">
                    *</span></label>
            <div class="form-group mb-0 calendarIcon">
                {{ html()->text('start_date')->attributes([
                        'class' => 'form-control datepicker py-2',
                        'onkeydown' => 'return false;',
                        'id' => 'from',
                        'placeholder' => trans('messages.start_date'),
                    ]) }}
            </div>

            @if ($errors->has('start_date'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('start_date') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group ">
            <label for="end_date">{{ trans('messages.end_date') }} <span class="red_lab"> *</span></label>
            <div class="form-group mb-0 calendarIcon">
                {{ html()->text('expiry_date')->attributes([
                        'class' => 'form-control datepicker py-2',
                        'onkeydown' => 'return false;',
                        'id' => 'to',
                        'placeholder' => trans('messages.end_date'),
                    ]) }}
            </div>

            @if ($errors->has('expiry_date'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('expiry_date') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group clearValidation">
            <label for="maximum_uses">{{ trans('messages.maximum_uses') }} <span class="red_lab">
                    *</span></label>
            {{ html()->text('maximum_uses', null)->attributes(['class' => 'form-control', 'placeholder' => trans('messages.maximum_uses'),'oninput'=>'AllowNumericOnly(this)']) }}
            @if ($errors->has('maximum_uses'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('maximum_uses') }}</strong>
                </span>
            @endif
        </div>
    </div>

</div>

<div class="row">

    <div class="col-lg-4 col-md-6 upload_img mb-5">
        <label>{{ trans('messages.image') }}
            @if (!isset($coupon))
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
        <?php if (isset($coupon)) { ?>
        <label class="exist_image">{{ trans('messages.existingImage') }}
        </label>
        <div class="old_img">
            <img alt="Image" class="border border-1" src="{{ $coupon->thumbImage }}" width="100">
        </div>
        <?php } ?>
    </div>
    <div class="col-lg-2  col-md-6">
        <label class='image-preview'>{{ trans('messages.imagePreview') }}</label>
        <img id="image-preview" class="image-preview" alt="{{ trans('messages.imagePreview') }}"
            class="img-fluid rounded-circle" width="150px">
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="description" class="font-medium fs-6 mb-3">{{ trans('messages.description') }}<span
                    class="red_lab">
                    *</span></label>
            {{ html()->textarea('description')->attributes(['class' => 'form-control  formValidate', 'rows' => 5, 'placeholder' => trans('messages.description'), 'enctype' => 'multipart/form-data']) }}
            @if ($errors->has('description'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('description') }}</strong>
                </span>
            @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group ">
            <label for="single_user_use_limit">{{ trans('messages.single_user_use_limit') }} <span class="red_lab">
                    *</span></label>
            {{ html()->text('single_user_use_limit', null)->attributes(['class' => 'form-control', 'placeholder' => trans('messages.single_user_use_limit'),
            'oninput'=>'AllowNumericOnly(this)'
            ]) }}
            @if ($errors->has('single_user_use_limit'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('single_user_use_limit') }}</strong>
                </span>
            @endif
        </div>
    </div>

</div>

<div class="form-group d-flex gap-3">
    <button type="submit"
        class="btn theme_btn bg_theme font-semibold border-0 fs-6 px-sm-5">{{ trans('messages.submit') }}</button>
    <a href="{{ route('admin.coupons.list') }}" class="btn px-sm-5 font-semibold border_btn"><em
            class="icon-refresh"></em> {{ trans('messages.cancel') }}</a>
</div>
<script src="{{ asset('assets/js/custom-user-define-fun.js') }}"></script>

@if (env('ENABLE_CLIENT_VALIDATION'))
    <script>
        window.range_ = [''];
        const validateCouponCodeUrl = "{{ route('admin.coupons.validateCouponCode') }}";
        const validateCouponNameUrl = "{{ route('admin.coupons.validateCouponName') }}";
        const coupon_id = "{{ isset($coupon) ? $coupon->id : null }}";

        $(function() {
            $.validator.addMethod("maxDiscountUpto", function(value, element) {
                var discount = parseFloat($("#discount").val());
                var discountUpto = parseFloat($('#discount_up_to').val());
                return (parseFloat(discountUpto) <= parseFloat(discount))
            }, "{{ trans('messages.max_up_to_discount') }}");

            $.validator.addMethod("validPercentage", function(value, element) {
                
                var discount = parseInt($("#discount").val());
                return discount >= 1 && discount <= 100 && /^\d+(\.\d+)?$/.test(discount)
            }, "{{ trans('messages.valid_percentage') }}");

            window.formReference = $("#couponForm").validate({
                rules: {

                    name: {
                        required: true,
                        minlength: 2,
                        maxlength: 30,
                        notNumber: true,
                        remote: {
                            url: validateCouponNameUrl,
                            type: "POST",
                            data: {
                                id: function() {
                                    return coupon_id;
                                }
                            }
                        }
                    },
                    discount: {
                        required: true,
                        maxlength: 100,
                        positive_float: true,

                    },
                    discount_up_to: {
                        required: true,
                        maxlength: 8,
                        positive_float: true,

                    },
                    offer_type: {
                        required: true,
                        notNumber: true,
                        validName: true
                    },

                    min_order_value: {
                        required: true,
                        maxlength: 8,
                        positive_float: true,
                    },


                    code: {
                        required: true,
                        maxlength: 30,
                        minlength: 3,
                        notNumber:true,
                        remote: {
                            url: validateCouponCodeUrl,
                            type: "POST",
                            data: {
                                id: function() {
                                    return coupon_id;
                                }
                            }
                        }
                    },
                    start_date: {
                        required: true,
                        maxlength: 255,
                    },
                    expiry_date: {
                        required: true,
                        maxlength: 255,
                    },
                    maximum_uses: {
                        required: true,
                        integer: true,
                        minlength: 1,
                        range: ['1', 100],
                    },
                    single_user_use_limit: {
                        required: true,
                        integer: true,
                        minlength: 1,
                        range: ['1', 100],
                    },
                    description: {
                        required: true,
                        minlength: 3,
                    },



                },
                messages: {
                    name: {
                        minlength: "{{ trans('messages.min2Max30') }}",
                        maxlength: "{{ trans('messages.min2Max30') }}",
                        remote: "{{ trans('messages.name_is_already_exists') }}",
                    },

                    discount: {
                        required: "{{ trans('messages.discount_required') }}",
                    },
                    code: {
                        minlength: "{{ trans('messages.min3') }}",
                        notNumber: "{{ trans('messages.notNumberMessage') }}",
                        remote: "{{ trans('messages.code_is_already_exists') }}",
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
    <script type="text/javascript">
        $(document).ready(function() {
            $('#offer_type').change(function() {
                validateFields();
            });
            $("#min_order_value").on('input',function(){
         
                OnlyAllowFloatNumber($(this));
            });
    
            $('#discount, #discount_up_to').on('input', function() {
                validateFields();
                OnlyAllowFloatNumber($(this));
            });
    
            function validateFields() {
                const offerType = $('#offer_type').val();
                if (offerType === 'percentage') {
                    $("#discount_up_to").rules('remove','maxDiscountUpto');
                    $("#discount").rules('add',{
                        validPercentage:true
                    });
    
                } else if (offerType === 'flat') {
                    $("#discount").rules('remove','percentage');
                    $("#discount_up_to").rules('add', {
                    maxDiscountUpto:true,
                });
                }
                $("#discount_up_to,#discount").valid();
    
            }
    
    
        });
    </script>
    
    @if (isset($coupon))
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
   

    @else
    <script type="text/javascript">
        $(document).ready(function() {
            $('#discount, #discount_up_to,#min_order_value').on('input', function() {
                OnlyAllowFloatNumber($(this));
            });
    
        });
    </script>

@endif







