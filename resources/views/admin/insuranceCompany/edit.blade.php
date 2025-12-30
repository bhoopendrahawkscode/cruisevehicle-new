@extends('admin.layouts.default_layout')
@section('content')
<?php  use \App\Constants\Constant; ?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<div class="header d-flex align-items-center">
    <h1 class="page-header">
        {{ trans('messages.editinsurance_company') }}
    </h1>
    <ol class="breadcrumb ms-auto mb-0">
        <li><a href="{{ Route('admin.dashboard') }}"> <em class="fa fa-dashboard"></em>{{ trans('messages.dashboard') }}</a></li>
        <li><a href="{{route('admin.insuranceCompany.list')}}">{{ trans('messages.insurance_company') }} {{ trans("messages.list") }}</a></li>
        <li class="active">{{ trans("messages.edit") }}</li>
    </ol>
</div>
<div id="page-inner">
    <div class="panel panel-default">
        <div class="panel-body">
            {{ html()->modelForm($data, 'POST', URL::to('/').'/admin/insurance-management/insurance-company/update/'.$data->id)
            ->attributes(['role' =>'form','id'=>"insurance_companyForm",'autocomplete' => 'off'])->open() }}
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
                    <div class="form-group">
                        <label for="insurance_company_employee_full_name">{{ trans('messages.employeeFullName') }} <span class="red_lab"> *</span></label>
                        {{ html()->text('insurance_company_employee_full_name',null)->attributes(['class' => 'form-control',
                         'placeholder' => trans("messages.employeeFullName")]) }}
                        @if ($errors->has('insurance_company_employee_full_name'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('insurance_company_employee_full_name') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group clearValidation">
                        <label for="email">{{ trans('messages.email') }} <span class="red_lab"> *</span></label>
                        {{ html()->text('email',null)->attributes(['class' => 'form-control',
                        'placeholder' => trans("messages.email")]) }}
                        @if ($errors->has('email'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                {{-- @if(auth()->user()->role_id == 2)
                <div class="col-md-6">
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
                </div>
                @endif --}}
                <input type="hidden" name="role" value='6'>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group d-flex gap-3">
                        <button type="submit" class="btn theme_btn bg_theme font-semibold border-0 fs-6 px-sm-5">{{ trans('messages.submit') }}</button>
                        <a href="{{route('admin.insuranceCompany.list')}}" class="btn px-sm-5 font-semibold border_btn"><em class="icon-refresh"></em> {{ trans("messages.cancel") }}</a>
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
        $("#insurance_companyForm").validate({
            rules: {

                full_name: {
                    required: true,
                    minlength: 2,
                    maxlength: 60,
                    notNumber: true,
                    validName: true
                },
                insurance_company_employee_full_name: {
                    required: true,
                    minlength: 2,
                    maxlength: 60,
                    notNumber: true,
                    validName: true,
                },
                email: {
                    required: true,
                    email: true,
                    maxlength: 100,
                    emailPattern: true
                },
            },
            messages: {
                full_name: {
                    minlength: "{{ trans('messages.min2Max60') }}",
                    maxlength: "{{ trans('messages.min2Max60') }}",
                    notNumber: "{{ trans('messages.notNumberMessage') }}"
                },
                insurance_company_employee_full_name: {
                    minlength: "{{ trans('messages.min2Max60') }}",
                    maxlength: "{{ trans('messages.min2Max60') }}",
                    notNumber: "{{ trans('messages.notNumberMessage') }}"
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
