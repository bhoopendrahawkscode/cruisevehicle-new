<div class="row">

    <!-- Full Name -->
     <div class="col-md-6">
        <div class="form-group">
            <label>{{ trans('messages.fullName') }} <span class="red_lab">*</span></label>
            {{ html()->text('full_name', isset($model) ? $model->full_name : old('full_name'))
                ->attributes(['class' => 'form-control', 'placeholder' => trans('messages.fullName')]) }}
            @if ($errors->has('full_name'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('full_name') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <!-- NIC -->
    <div class="col-md-6">
        <div class="form-group">
            <label>{{ trans('messages.nic') }} <span class="red_lab">*</span></label>
            {{ html()->text('nic', isset($model) ? $model->nic : old('nic'))
                ->attributes(['class' => 'form-control', 'placeholder' => trans('messages.nic')]) }}
            @if ($errors->has('nic'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('nic') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <!-- Car Model -->
    <div class="col-md-6">
        <div class="form-group">
            <label>{{ trans('messages.carModel') }} <span class="red_lab">*</span></label>
            {{ html()->select('car_model', $models, isset($model) ? $model->car_model : old('car_model'))
                ->attributes(['class' => 'form-control', 'placeholder' => trans('messages.selectCarModel')]) }}
            @if ($errors->has('car_model'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('car_model') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <!-- Year of Manufacturer -->
    <div class="col-md-6">
        <div class="form-group">
            <label for="year_of_manufacturer">{{ trans('messages.yearOfManufacturer') }} <span class="red_lab">
                    *</span></label>
            <div class="calendarIcon">
                {{ html()->text('year_of_manufacturer')->attributes([
                        'class' => 'form-control datepicker p-2',
                        'onkeydown' => 'return false;',
                        'placeholder' => trans('messages.year_of_manufacturer'),
                        'id' => 'year_of_manufacturer',
                    ]) }}
            </div>

            @if ($errors->has('year_of_manufacturer'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('year_of_manufacturer') }}</strong>
                </span>
            @endif
        </div>
    </div>

     <!-- Vehicle Registration Mark -->
     <div class="col-md-6">
        <div class="form-group">
            <label>{{ trans('messages.vehicleRegistrationMark') }} <span class="red_lab">*</span></label>
            {{ html()->text('vehicle_registration_mark', isset($model) ? $model->vehicle_registration_mark : old('vehicle_registration_mark'))
                ->attributes(['class' => 'form-control', 'placeholder' => trans('messages.vehicleRegistrationMark')]) }}
            @if ($errors->has('vehicle_registration_mark'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('vehicle_registration_mark') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <!-- Value -->
    <div class="col-md-6">
        <div class="form-group">
            <label>{{ trans('messages.value') }} <span class="red_lab">*</span></label>
            {{ html()->text('value', isset($model) ? $model->value : old('value'))
                ->attributes(['class' => 'form-control', 'placeholder' => trans('messages.value')]) }}
            @if ($errors->has('value'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('value') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <!-- Sum to be Insured -->
    <div class="col-md-6">
        <div class="form-group">
            <label>{{ trans('messages.sumToBeInsured') }} <span class="red_lab">*</span></label>
            {{ html()->text('sum_to_be_insured', isset($model) ? $model->sum_to_be_insured : old('sum_to_be_insured'))
                ->attributes(['class' => 'form-control', 'placeholder' => trans('messages.sumToBeInsured')]) }}
            @if ($errors->has('sum_to_be_insured'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('sum_to_be_insured') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <!-- Cover Type -->
    <div class="col-md-6">
        <div class="form-group">
            <label>{{ trans('messages.coverType') }} <span class="red_lab">*</span></label>
            {{ html()->radio('cover_type', isset($model) && $model->cover_type, 1)
                ->attributes(['class' => 'form-check-input', 'id' => 'cover_type_yes']) }}
            <label class="form-check-label" for="cover_type_yes">
                {{ trans('messages.yes') }}
            </label>
            {{ html()->radio('cover_type', !(isset($model) && $model->cover_type), 0)
                ->attributes(['class' => 'form-check-input', 'id' => 'cover_type_no']) }}
            <label class="form-check-label" for="cover_type_no">
                {{ trans('messages.no') }}
            </label>
            @if ($errors->has('cover_type'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('cover_type') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <!-- Period of Insurance Cover -->
    <div class="col-md-6">
        <div class="form-group">
            <label>{{ trans('messages.periodOfInsuranceCover') }} <span class="red_lab">*</span></label>
            {{ html()->radio('period_of_insurance_cover', isset($model) && $model->period_of_insurance_cover, 1)
                ->attributes(['class' => 'form-check-input', 'id' => 'period_of_insurance_cover_yes']) }}
            <label class="form-check-label" for="period_of_insurance_cover_yes">
                {{ trans('messages.yes') }}
            </label>
            {{ html()->radio('period_of_insurance_cover', !(isset($model) && $model->period_of_insurance_cover), 0)
                ->attributes(['class' => 'form-check-input', 'id' => 'period_of_insurance_cover_no']) }}
            <label class="form-check-label" for="period_of_insurance_cover_no">
                {{ trans('messages.no') }}
            </label>
            @if ($errors->has('period_of_insurance_cover'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('period_of_insurance_cover') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <!-- Vehicle Registered -->
    <div class="col-md-6">
        <div class="form-group">
            <label>{{ trans('messages.vehicleRegistered') }} <span class="red_lab">*</span></label>
            <div class="form-check">
                {{ html()->radio('vehicle_registered', isset($model) && $model->vehicle_registered, 1)
                    ->attributes(['class' => 'form-check-input', 'id' => 'vehicle_registered_yes']) }}
                <label class="form-check-label" for="vehicle_registered_yes">
                    {{ trans('messages.yes') }}
                </label>
            </div>
            <div class="form-check">
                {{ html()->radio('vehicle_registered', !(isset($model) && $model->vehicle_registered), 0)
                    ->attributes(['class' => 'form-check-input', 'id' => 'vehicle_registered_no']) }}
                <label class="form-check-label" for="vehicle_registered_no">
                    {{ trans('messages.no') }}
                </label>
            </div>
            @if ($errors->has('vehicle_registered'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('vehicle_registered') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <!-- Vehicle Line -->
    <div class="col-md-6">
        <div class="form-group">
            <label>{{ trans('messages.vehicleLine') }} <span class="red_lab">*</span></label>
            <div class="form-check">
                {{ html()->radio('vehicle_line', isset($model) && $model->vehicle_line, 1)
                    ->attributes(['class' => 'form-check-input', 'id' => 'vehicle_line_yes']) }}
                <label class="form-check-label" for="vehicle_line_yes">
                    {{ trans('messages.yes') }}
                </label>
            </div>
            <div class="form-check">
                {{ html()->radio('vehicle_line', !(isset($model) && $model->vehicle_line), 0)
                    ->attributes(['class' => 'form-check-input', 'id' => 'vehicle_line_no']) }}
                <label class="form-check-label" for="vehicle_line_no">
                    {{ trans('messages.no') }}
                </label>
            </div>
            @if ($errors->has('vehicle_line'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('vehicle_line') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <!-- Vehicle Disqualified -->
    <div class="col-md-6">
        <div class="form-group">
            <label>{{ trans('messages.vehicleDisqualified') }} <span class="red_lab">*</span></label>
            <div class="form-check">
                {{ html()->radio('vehicle_disqualified', isset($model) && $model->vehicle_disqualified, 1)
                    ->attributes(['class' => 'form-check-input', 'id' => 'vehicle_disqualified_yes']) }}
                <label class="form-check-label" for="vehicle_disqualified_yes">
                    {{ trans('messages.yes') }}
                </label>
            </div>
            <div class="form-check">
                {{ html()->radio('vehicle_disqualified', !(isset($model) && $model->vehicle_disqualified), 0)
                    ->attributes(['class' => 'form-check-input', 'id' => 'vehicle_disqualified_no']) }}
                <label class="form-check-label" for="vehicle_disqualified_no">
                    {{ trans('messages.no') }}
                </label>
            </div>
            @if ($errors->has('vehicle_disqualified'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('vehicle_disqualified') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <!-- Vehicle Experience -->
    <div class="col-md-6">
        <div class="form-group">
            <label>{{ trans('messages.vehicleExperience') }} <span class="red_lab">*</span></label>
            <div class="form-check">
                {{ html()->radio('vehicle_experience', isset($model) && $model->vehicle_experience, 1)
                    ->attributes(['class' => 'form-check-input', 'id' => 'vehicle_experience_yes']) }}
                <label class="form-check-label" for="vehicle_experience_yes">
                    {{ trans('messages.yes') }}
                </label>
            </div>
            <div class="form-check">
                {{ html()->radio('vehicle_experience', !(isset($model) && $model->vehicle_experience), 0)
                    ->attributes(['class' => 'form-check-input', 'id' => 'vehicle_experience_no']) }}
                <label class="form-check-label" for="vehicle_experience_no">
                    {{ trans('messages.no') }}
                </label>
            </div>
            @if ($errors->has('vehicle_experience'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('vehicle_experience') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <!-- Vehicle Accidents -->
    <div class="col-md-6">
        <div class="form-group">
            <label>{{ trans('messages.vehicleAccidents') }} <span class="red_lab">*</span></label>
            <div class="form-check">
                {{ html()->radio('vehicle_accidents', isset($model) && $model->vehicle_accidents, 1)
                    ->attributes(['class' => 'form-check-input', 'id' => 'vehicle_accidents_yes']) }}
                <label class="form-check-label" for="vehicle_accidents_yes">
                    {{ trans('messages.yes') }}
                </label>
            </div>
            <div class="form-check">
                {{ html()->radio('vehicle_accidents', !(isset($model) && $model->vehicle_accidents), 0)
                    ->attributes(['class' => 'form-check-input', 'id' => 'vehicle_accidents_no']) }}
                <label class="form-check-label" for="vehicle_accidents_no">
                    {{ trans('messages.no') }}
                </label>
            </div>
            @if ($errors->has('vehicle_accidents'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('vehicle_accidents') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <!-- Vehicle Not Use -->
    <div class="col-md-6">
        <div class="form-group">
            <label>{{ trans('messages.vehicleNotUse') }} <span class="red_lab">*</span></label>
            <div class="form-check">
                {{ html()->radio('vehicle_not_use', isset($model) && $model->vehicle_not_use, 1)
                    ->attributes(['class' => 'form-check-input', 'id' => 'vehicle_not_use_yes']) }}
                <label class="form-check-label" for="vehicle_not_use_yes">
                    {{ trans('messages.yes') }}
                </label>
            </div>
            <div class="form-check">
                {{ html()->radio('vehicle_not_use', !(isset($model) && $model->vehicle_not_use), 0)
                    ->attributes(['class' => 'form-check-input', 'id' => 'vehicle_not_use_no']) }}
                <label class="form-check-label" for="vehicle_not_use_no">
                    {{ trans('messages.no') }}
                </label>
            </div>
            @if ($errors->has('vehicle_not_use'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('vehicle_not_use') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <!-- Vehicle Drive Illness -->
    <div class="col-md-6">
        <div class="form-group">
            <label>{{ trans('messages.vehicleDriveIllness') }} <span class="red_lab">*</span></label>
            <div class="form-check">
                {{ html()->radio('vehicle_drive_illness', isset($model) && $model->vehicle_drive_illness, 1)
                    ->attributes(['class' => 'form-check-input', 'id' => 'vehicle_drive_illness_yes']) }}
                <label class="form-check-label" for="vehicle_drive_illness_yes">
                    {{ trans('messages.yes') }}
                </label>
            </div>
            <div class="form-check">
                {{ html()->radio('vehicle_drive_illness', !(isset($model) && $model->vehicle_drive_illness), 0)
                    ->attributes(['class' => 'form-check-input', 'id' => 'vehicle_drive_illness_no']) }}
                <label class="form-check-label" for="vehicle_drive_illness_no">
                    {{ trans('messages.no') }}
                </label>
            </div>
            @if ($errors->has('vehicle_drive_illness'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('vehicle_drive_illness') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <!-- Vehicle Insurer -->
    <div class="col-md-6">
        <div class="form-group">
            <label>{{ trans('messages.vehicleInsurer') }} <span class="red_lab">*</span></label>
            <div class="form-check">
                {{ html()->radio('vehicle_insurer', isset($model) && $model->vehicle_insurer, 1)
                    ->attributes(['class' => 'form-check-input', 'id' => 'vehicle_insurer_yes']) }}
                <label class="form-check-label" for="vehicle_insurer_yes">
                    {{ trans('messages.yes') }}
                </label>
            </div>
            <div class="form-check">
                {{ html()->radio('vehicle_insurer', !(isset($model) && $model->vehicle_insurer), 0)
                    ->attributes(['class' => 'form-check-input', 'id' => 'vehicle_insurer_no']) }}
                <label class="form-check-label" for="vehicle_insurer_no">
                    {{ trans('messages.no') }}
                </label>
            </div>
            @if ($errors->has('vehicle_insurer'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('vehicle_insurer') }}</strong>
                </span>
            @endif
        </div>
    </div>

</div>


<div class="form-group d-flex gap-3">
    <button type="submit" class="btn theme_btn bg_theme font-semibold border-0 fs-6 px-sm-5">{{ trans('messages.submit') }}</button>
    <a href="{{route($listRoute)}}" class="btn px-sm-5 font-semibold border_btn"><em class="icon-refresh"></em> {{ trans('messages.cancel') }}</a>
</div>


<script src="{{ asset('assets/js/custom-user-define-fun.js') }}"></script>

@if (env('ENABLE_CLIENT_VALIDATION'))
    <script>
        $(document).ready(function() {
            // Initialize validation
            $("form").validate({
                rules: {
                    full_name: {
                        required: true,
                        minlength: 3
                    },
                    nic: {
                        required: true,
                        minlength: 5
                    },
                    car_model: {
                        required: true
                    },
                    year_of_manufacturer: {
                        required: true,
                        digits: true,
                        minlength: 4,
                        maxlength: 4
                    },
                    vehicle_registration_mark: {
                        required: true
                    },
                    value: {
                        required: true,
                        number: true
                    },
                    sum_to_be_insured: {
                        required: true,
                        number: true
                    },
                    cover_type: {
                        required: true
                    },
                    period_of_insurance_cover: {
                        required: true
                    },
                    vehicle_registered: {
                        required: true
                    },
                    vehicle_line: {
                        required: true
                    },
                    vehicle_disqualified: {
                        required: true
                    },
                    vehicle_experience: {
                        required: true
                    },
                    vehicle_accidents: {
                        required: true
                    },
                    vehicle_not_use: {
                        required: true
                    },
                    vehicle_drive_illness: {
                        required: true
                    },
                    vehicle_insurer: {
                        required: true
                    }
                },
                messages: {
                    full_name: {
                        required: "Please enter your full name",
                        minlength: "Your name must be at least 3 characters long"
                    },
                    nic: {
                        required: "Please enter your NIC",
                        minlength: "NIC must be at least 5 characters long"
                    },
                    car_model: {
                        required: "Please select a car model"
                    },
                    year_of_manufacturer: {
                        required: "Please enter the year of manufacture",
                        digits: "Year must be a number",
                        minlength: "Year must be 4 digits",
                        maxlength: "Year must be 4 digits"
                    },
                    vehicle_registration_mark: {
                        required: "Please enter the vehicle registration mark"
                    },
                    value: {
                        required: "Please enter the value",
                        number: "Value must be a number"
                    },
                    sum_to_be_insured: {
                        required: "Please enter the sum to be insured",
                        number: "Sum to be insured must be a number"
                    },
                    cover_type: {
                        required: "Please select a cover type"
                    },
                    period_of_insurance_cover: {
                        required: "Please select the period of insurance cover"
                    },
                    vehicle_registered: {
                        required: "Please select whether the vehicle is registered"
                    },
                    vehicle_line: {
                        required: "Please select whether the vehicle is in a line"
                    },
                    vehicle_disqualified: {
                        required: "Please select whether the vehicle is disqualified"
                    },
                    vehicle_experience: {
                        required: "Please select whether you have vehicle experience"
                    },
                    vehicle_accidents: {
                        required: "Please select whether the vehicle has been in accidents"
                    },
                    vehicle_not_use: {
                        required: "Please select whether the vehicle is not in use"
                    },
                    vehicle_drive_illness: {
                        required: "Please select whether there is a driving illness"
                    },
                    vehicle_insurer: {
                        required: "Please select whether you have a vehicle insurer"
                    }
                },
                errorElement: "span",
                errorClass: "invalid-feedback",
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass("is-invalid");
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass("is-invalid");
                }
            });
            $('#year_of_manufacturer').datepicker({
                format: "yyyy-mm-dd",
                clearBtn: true,
                autoclose:true,
                todayHighlight: true,
                startDate: new Date()
            })
        });
    </script>



@endif
