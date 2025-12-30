@extends('admin.layouts.default_layout')
@section('content')
<?php  use \App\Constants\Constant; ?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<div class="header d-flex align-items-center">
    <h1 class="page-header">
        {{ trans('messages.editSubAdmin') }}
    </h1>
    <ol class="breadcrumb ms-auto mb-0">
        <li><a href="{{ Route('admin.dashboard') }}"> <em class="fa fa-dashboard"></em>{{ trans('messages.dashboard') }}</a></li>
        <li><a href="{{route('admin.listSubadmin')}}">{{ trans('messages.subAdmin') }} {{ trans("messages.list") }}</a></li>
        <li class="active">{{ trans("messages.edit") }}</li>
    </ol>
</div>
<div id="page-inner">
    <div class="panel panel-default">
        <div class="panel-body">
            {{ html()->modelForm($data, 'POST', URL::to('/').'/admin/sub-admin/updateUser/'.$data->id)
            ->attributes(['role' =>'form','id'=>"subAdminForm",'autocomplete' => 'off'])->open() }}
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="full_name">{{ trans('messages.fullName') }} <span class="red_lab"> *</span></label>
                        {{ html()->text('full_name',null)->attributes(['class' => 'form-control',
                         'placeholder' => trans("messages.fullName")]) }}
                        @if ($errors->has('full_name'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('full_name') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group clearValidation">
                        <label for="email">{{ trans('messages.email') }} <span class="red_lab"> *</span></label>
                        {{ html()->text('email',null)->attributes(['class' => 'form-control','readonly'=>'readonly',
                         'placeholder' => trans("messages.email")]) }}
                        @if ($errors->has('email'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                {{--<div class="col-md-6">
                    <div class="form-group">
                        <label for="first_name">{{ trans('messages.firstName') }} <span class="red_lab"> *</span></label>
                        {{ html()->text('first_name',null)->attributes(['class' => 'form-control',
                         'placeholder' => trans("messages.firstName")]) }}
                        @if ($errors->has('first_name'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('first_name') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="last_name">{{ trans('messages.lastName') }} <span class="red_lab"> *</span></label>
                        {{ html()->text('last_name',null)->attributes(['class' => 'form-control',
                        'placeholder' => trans("messages.lastName")]) }}
                        @if ($errors->has('last_name'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('last_name') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>--}}
            </div>
            <div class="row">


                <input type="hidden" name="role" value='3'>
                {{-- <div class="col-md-6">
                    <div class="form-group clearValidation3">
                        <label for="role">{{ trans('messages.role') }}
                            <span class="red_lab">*</span></label>
                        <div class="row gx-2 gx-md-3">
                                {{ html()->select('role',$sub_admin_roles, $data->roles->first()->id??'')->attributes([
                                        'class' => 'form-control select2',
                                        'placeholder' => trans('messages.role'),
                                        'required' => 'required',
                                    ]) }}


                        </div>
                        @if ($errors->has('role'))
                            <span class="invalid-feedback " role="alert">
                                <strong>{{ $errors->first('role') }}</strong>
                            </span>
                        @endif
                    </div>
                </div> --}}


            </div>


            {{--@if(Constant::ALLOW_SUB_ADMIN_PHONE_NO)
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group clearValidation3">
                            <label for="phone">{{ trans('messages.phoneNo') }} <span class="red_lab"> *</span></label>
                            <div class="row">
                                <div class="col-sm-6 mb-4 mb-sm-0 errorPlacement2">
                                    {{ html()->select('country_code',$phonecode,$data->country_code)->attributes(['class' => 'form-control select2',
                                    'placeholder' => trans("messages.phoneNo"),'required'=>'required']) }}
                                    <span class="invalid-feedback " role="alert">
                                        <strong>{{ $errors->first('country_code') }}</strong>
                                    </span>
                                </div>
                                <div class="col-sm-6">
                                    {{ html()->text('mobile_no',$data->mobile_no)->attributes(['class' => 'form-control ',
                                    'placeholder' => trans("messages.phoneNo")]) }}
                                </div>
                            </div>
                            @if ($errors->has('mobile_no'))
                            <span class="invalid-feedback " role="alert">
                                <strong>{{ $errors->first('mobile_no') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group clearValidation3">
                            <label for="phone">{{ 'Timezone' }}  <span class="red_lab">
                                    *</span></label>
                            <div class="row">
                                <div class="col-sm-6 mb-4 mb-sm-0 errorPlacement2">
                                    {{ html()->select('timezone', $timeZone, $data->timezone)->attributes([
                                            'class' => 'form-control select2',
                                            'placeholder' => 'timezone',
                                            'required' => 'required',
                                        ]) }}
                                    <span class="invalid-feedback " role="alert">
                                        <strong>{{ $errors->first('timezone') }}</strong>
                                    </span>
                                </div>
                            </div>
                            @if ($errors->has('timezone'))
                                <span class="invalid-feedback " role="alert">
                                    <strong>{{ $errors->first('timezone') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif--}}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group d-flex gap-3">
                        <button type="submit" class="btn theme_btn bg_theme font-semibold border-0 fs-6 px-sm-5">{{ trans('messages.submit') }}</button>
                        <a href="{{route('admin.listSubadmin')}}" class="btn px-sm-5 font-semibold border_btn"><em class="icon-refresh"></em> {{ trans("messages.cancel") }}</a>
                    </div>
                </div>
            </div>
            {{ html()->closeModelForm() }}
        </div>
    </div>
</div>
<script integrity="" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.select2').select2();

        $('.multiselect-search').on('keyup', function() {
            var searchValue = $(this).val().toLowerCase();
            var options = $('#mySelect option');

            // Hide select all if no matching record found
            var selectAllOption = $('.multiselect-all');
            if (selectAllOption.length) {
            var visibleOptions = options.filter(function() {
                return $(this).text().toLowerCase().indexOf(searchValue) > -1;
            });
            if (visibleOptions.length > 0) {
                selectAllOption.show();
            } else {
                selectAllOption.hide();
            }
            }
        });
        if($('#mySelect option:checked').length==1){
            setTimeout(function(){ $('.multiselect-selected-text').html("1 Selected") }, 10);
         }

    });
</script>
@if(env('ENABLE_CLIENT_VALIDATION'))
<script>
    $(function() {
        // Form Validation
        $("#subAdminForm").validate({
            rules: {
                country_code:{
                    required: true,
                },
                timezone: {
                    required: true,
                },
                full_name: {
                    required: true,
                    minlength: 2,
                    maxlength: 60,
                    notNumber: true,
                    validName: true
                },
                first_name: {
                    required: true,
                    minlength: 2,
                    maxlength: 30,
                    notNumber: true,
                    validName: true
                },
                last_name: {
                    required: true,
                    minlength: 2,
                    maxlength: 30,
                    notNumber: true,
                    validName: true
                },
                email: {
                    required: true,
                    email: true,
                    maxlength: 100,
                    emailPattern: true
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
                full_name: {
                    minlength: "{{ trans('messages.min2Max60') }}",
                    maxlength: "{{ trans('messages.min2Max60') }}",
                    notNumber: "{{ trans('messages.notNumberMessage') }}"
                },
                first_name: {
                    minlength: "{{ trans('messages.min2Max30') }}",
                    maxlength: "{{ trans('messages.min2Max30') }}",
                    notNumber: "{{ trans('messages.notNumberMessage') }}"
                },
                last_name: {
                    minlength: "{{ trans('messages.min2Max30') }}",
                    maxlength: "{{ trans('messages.min2Max30') }}",
                    notNumber: "{{ trans('messages.notNumberMessage') }}"
                },
                mobile_no: {
                    minlength: "{{ trans('messages.phoneNumRangeValidationMessage') }}",
                    maxlength: "{{ trans('messages.phoneNumRangeValidationMessage') }}"
                },
            },
            errorClass: "help-inline",
            errorElement: "span",
            highlight: function(element, errorClass, validClass) {
                $(element).parents('.form-group').addClass('error');
                //$('.invalid-feedback').hide();
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).parents('.form-group').removeClass('error');
                $(element).parents('.form-group').addClass('success');
            },
            // Make sure the form is submitted to the destination defined
            // in the "action" attribute of the form when valid
            /*submitHandler: function(form) {
            }*/

        });
    });
</script>
@endif
@stop
