<div class="row">
    <strong class="mb-2">@lang('messages.owner_information')</strong>
    <div class="col-md-6">
        <div class="form-group">
            <label for="owner_name">{{ trans('messages.vehicle_owner_name') }} <span class="red_lab">
                    *</span></label>
            {{ html()->text('owner_name', null)->attributes(['class' => 'form-control', 'placeholder' => trans('messages.vehicle_owner_name'), 'id' => 'owner_name']) }}
            @if ($errors->has('owner_name'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('owner_name') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="owner_address">{{ trans('messages.vehicle_owner_address') }} <span class="red_lab">
                    *</span></label>
            {{ html()->text('owner_address', null)->attributes(['class' => 'form-control', 'placeholder' => trans('messages.vehicle_owner_address'), 'id' => 'owner_address']) }}
            @if ($errors->has('owner_address'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('owner_address') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="reg_no">{{ trans('messages.vehicle_registration_number') }} <span class="red_lab">
                    *</span></label>
            {{ html()->text('reg_no', null)->attributes(['class' => 'form-control', 'placeholder' => trans('messages.vehicle_registration_number'), 'id' => 'reg_no']) }}
            @if ($errors->has('reg_no'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('reg_no') }}</strong>
                </span>
            @endif
        </div>
    </div>


    <div class="col-md-6">
        <div class="form-group">
            <label for="brand_id">{{ trans('messages.brand_name_of_vehicle') }} <span class="red_lab">
                    *</span></label>

            <div class="errorPlacement2">
                {{ html()->select('brand_id', $brands, $model ?? '')->attributes([
                        'class' => 'form-control select2',
                        'placeholder' => trans('message.brand'),
                        'required' => 'required',
                        'id' => 'brand_id',
                    ]) }}
            </div>

            @if ($errors->has('brand_id'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('brand_id') }}</strong>
                </span>
            @endif
        </div>
    </div>


    <div class="col-md-6">
        <div class="form-group">
            <label for="model_id">{{ trans('messages.vehicle_model') }} <span class="red_lab">
                    *</span></label>

            <div class="errorPlacement2">
                {{ html()->select('model_id', $models, $model ?? '')->attributes([
                        'class' => 'form-control select2',
                        'required' => 'required',
                        'id' => 'model_id',
                    ]) }}
            </div>

            @if ($errors->has('model_id'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('model_id') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="fuel_id">{{ trans('messages.fueltype') }} <span class="red_lab">
                    *</span></label>

            <div class="errorPlacement2">
                {{ html()->select('fuel_id', $fuel_types, $model ?? old('fuel_id'))->attributes([
                        'class' => 'form-control select2',
                        'placeholder' => trans('message.brand'),
                        'required' => 'required',
                        'id' => 'fuel_id',
                    ]) }}
            </div>

            @if ($errors->has('fuel_id '))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('fuel_id ') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="engine_capacity_id">{{ trans('messages.engine_capacity') }} <span class="red_lab">
                    *</span></label>

            <div class="errorPlacement2">
                {{ html()->select('engine_capacity_id', $engine_capacity, $model ?? old('engine_capacity_id'))->attributes([
                        'class' => 'form-control select2',
                        'placeholder' => trans('message.brand'),
                        'required' => 'required',
                        'id' => 'engine_capacity_id ',
                    ]) }}
            </div>

            @if ($errors->has('engine_capacity_id '))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('engine_capacity_id ') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="transmission_type_id">{{ trans('messages.transmission_types') }} <span class="red_lab">
                    *</span></label>

            <div class="errorPlacement2">
                {{ html()->select('transmission_type_id', $transmission_types, $model ?? old('transmission_type_id'))->attributes([
                        'class' => 'form-control select2',
                        'placeholder' => trans('message.brand'),
                        'required' => 'required',
                        'id' => 'transmission_type_id',
                    ]) }}
            </div>

            @if ($errors->has('transmission_type_id '))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('transmission_type_id ') }}</strong>
                </span>
            @endif
        </div>
    </div>

</div>
<div class="row mt-3">
    <strong class="mb-2">@lang('messages.road_tax')</strong>
    <div class="col-md-6">
        <div class="form-group">
            <label for="renewal_period">{{ trans('messages.renewal_period') }} <span class="red_lab">
                    *</span></label>
            <div class="calendarIcon">
                {{ html()->text('renewal_period')->attributes([
                        'class' => 'form-control datepicker p-2',
                        'onkeydown' => 'return false;',
                        'placeholder' => trans('messages.renewal_period'),
                        'id' => 'renewal_period',
                    ]) }}
            </div>

            @if ($errors->has('renewal_period'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('renewal_period') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="due_renewal_date">{{ trans('messages.due_renewal_date') }} <span class="red_lab">
                    *</span></label>
            <div class="calendarIcon">
                {{ html()->text('due_renewal_date')->attributes([
                        'class' => 'form-control datepicker p-2',
                        'onkeydown' => 'return false;',
                        'placeholder' => trans('messages.due_renewal_date'),
                        'id' => 'due_renewal_date',
                    ]) }}
            </div>

            @if ($errors->has('due_renewal_date'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('due_renewal_date') }}</strong>
                </span>
            @endif
        </div>
    </div>
</div>

<div class="row mt-3">
    <strong class="mb-2">@lang('messages.insurance_information')</strong>
    <div class="col-md-6">
        <div class="form-group">
            <label for="insurance_company">{{ trans('messages.insurance_company') }} <span class="red_lab">
                    *</span></label>
            {{ html()->text('insurance_company', null)->attributes(['class' => 'form-control', 'placeholder' => trans('messages.insurance_company'), 'id' => 'insurance_company']) }}
            @if ($errors->has('insurance_company'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('insurance_company') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="sum_assured_value">{{ trans('messages.sum_assured_value') }} <span class="red_lab">
                    *</span></label>
            {{ html()->text('sum_assured_value', null)->attributes(['class' => 'form-control', 'placeholder' => trans('messages.sum_assured_value'), 'id' => 'sum_assured_value']) }}
            @if ($errors->has('sum_assured_value'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('sum_assured_value') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="expiry_date">{{ trans('messages.expiry_date') }} <span class="red_lab">
                    *</span></label>
            <div class="calendarIcon">
                {{ html()->text('expiry_date')->attributes([
                        'class' => 'form-control datepicker p-2',
                        'onkeydown' => 'return false;',
                        'placeholder' => trans('messages.expiry_date'),
                        'id' => 'expiry_date',
                    ]) }}
            </div>

            @if ($errors->has('expiry_date'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('expiry_date') }}</strong>
                </span>
            @endif
        </div>
    </div>
</div>

<div class="row mt-2">
    <strong class="mb-2">@lang('messages.service_history')</strong>
    <div class="col-md-6">
        <div class="form-group">
            <label for="service_provider">{{ trans('messages.service_provider') }} <span class="red_lab">
                    *</span></label>
            {{ html()->text('service_provider', null)->attributes(['class' => 'form-control', 'placeholder' => trans('messages.service_provider'), 'id' => 'service_provider']) }}
            @if ($errors->has('service_provider'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('service_provider') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="service_date">{{ trans('messages.service_date') }} <span class="red_lab">
                    *</span></label>
            <div class="calendarIcon">
                {{ html()->text('service_date')->attributes([
                        'class' => 'form-control datepicker p-2',
                        'onkeydown' => 'return false;',
                        'placeholder' => trans('messages.service_date'),
                        'id' => 'service_date',
                    ]) }}
            </div>

            @if ($errors->has('service_date'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('service_date') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="cost">{{ trans('messages.cost') }} <span class="red_lab">
                    *</span></label>
            {{ html()->text('cost', null)->attributes(['class' => 'form-control', 'placeholder' => trans('messages.cost'), 'id' => 'cost']) }}
            @if ($errors->has('cost'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('cost') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <label for="services">{{ trans('messages.services') }} <span class="red_lab">
                    *</span></label>
                    {{ html()->textarea('services')
                    ->attributes([
                        'class' => 'form-control',
                        'style' => 'height:120px',
                        'id' => 'services',
                        'rows' => 4,
                        'cols' => 4,
                        'placeholder' => trans('messages.services')
                    ]) }}


            @if ($errors->has('services'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('services') }}</strong>
                </span>
            @endif
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-lg-4 col-md-6 upload_img mb-5">
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
        <?php if (isset($model)) { ?>
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


</div>

<div class="form-group d-flex gap-3">
    <button type="submit"
        class="btn theme_btn bg_theme font-semibold border-0 fs-6 px-sm-5">{{ trans('messages.submit') }}</button>
    <a href="{{ route($listRoute) }}" class="btn px-sm-5 font-semibold border_btn"><em class="icon-refresh"></em>
        {{ trans('messages.cancel') }}</a>
</div>
<script integrity="" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>


<script src="{{ asset('assets/js/custom-user-define-fun.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#brand_id').change(function() {
            var brand_id = $(this).val();
            if (brand_id) {
                $.ajax({
                    url: '{{ route("get.models", ":brand_id") }}'.replace(':brand_id', brand_id),
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
@if (env('ENABLE_CLIENT_VALIDATION'))
    <script>
        window.range_ = [''];
        const validateName = "#";
        const model_id = "{{ isset($model) ? $model->id : null }}";

        $(function() {

            window.formReference = $("#vehicleForm").validate({
                rules: {

                    owner_name: {
                        required: true,
                        minlength: 2,
                        maxlength: 30,
                        notNumber: true,

                    },
                    owner_address: {
                        required: true,
                        minlength: 2,
                        notNumber: true,

                    },

                    reg_no: {
                        required: true,
                        minlength: 2,
                        notNumber: true,

                    },

                    renewal_period: {
                        required: true,

                    },
                    due_renewal_date: {
                        required: true,

                    },
                    expiry_date: {
                        required: true,

                    },
                    service_provider: {
                        required: true,
                        minlength: 2,
                        maxlength: 30,

                    },
                    service_date: {
                        required: true,

                    },
                    services: {
                        required: true,

                    },


                },
                messages: {
                    owner_name: {
                        minlength: "{{ trans('messages.min2Max30') }}",
                        maxlength: "{{ trans('messages.min2Max30') }}",

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
            $('.select2').on('change',function(){
                $(this).valid();
            })
        </script>
    @endif

@endif
