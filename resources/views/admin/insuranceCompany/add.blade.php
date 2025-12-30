@extends('admin.layouts.default_layout')
@section('content')

<?php  use \App\Constants\Constant; ?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<div class="header d-flex align-items-center">
    <h1 class="page-header">
        {{ trans('messages.add') }} {{ trans('messages.insurance_company') }}
    </h1>
    <ol class="breadcrumb ms-auto mb-0">
        <li><a href="{{ Route('admin.dashboard') }}"><em class="fa fa-dashboard"></em> {{ trans('messages.dashboard') }}</a></li>
        <li><a href="{{route('admin.insuranceCompany.list')}}">{{ trans('messages.insurance_company') }} {{ trans("messages.list") }}</a></li>
        <li class="active">{{ trans("messages.add") }}</li>
    </ol>
</div>
<div id="page-inner">
    <div class="panel panel-default">
        <div class="panel-body">
           {{ html()->modelForm(null, 'POST')->route('admin.insuranceCompany.save')
            ->attributes(['id'=>'insurance_companyForm','class'=>'form-horizontal','role'=>'form','autocomplete' => 'off'])->open() }}
           <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="full_name">{{trans('messages.insuranceCompanyName')}} <span class="red_lab"> *</span></label>
                        {{ html()->text('full_name',null)->attributes(['class' => 'form-control',
                        'placeholder' => trans("messages.insuranceCompanyName")]) }}
                        @if ($errors->has('full_name'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('full_name') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="insurance_company_employee_full_name">{{trans('messages.employeeFullName')}} <span class="red_lab"> *</span></label>
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
                        <label for="email">{{trans('messages.email')}} <span class="red_lab"> *</span></label>
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
                        <div class="form-group clearValidation">
                            <label for="role">{{ trans('messages.role') }} <span class="red_lab">*</span></label>
                            {{ html()->select('role', $sub_admin_roles, old('role'))->attributes([
                                'class' => 'form-control select',
                                'placeholder' => trans('messages.role'),
                                'required' => 'required',
                            ]) }}

                            @if ($errors->has('role'))
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('role') }}</strong>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif --}}
                <input type="hidden" name="role" value='6'>


            </div>
            @if(Constant::ALLOW_SUB_ADMIN_PASSWORD_CREATE)
            @php
            $isPasswordVisible = false;
            $isConfirmPasswordVisible = false;
            @endphp
            <div class="row" <?php if($password_generation==Constant::SYSTEM_FACTOR) { ?> style="display:none" <?php } ?>>
                <div class="col-sm-6">
                    <div class="form-group clearValidation">
                        <label for="password">{{ trans('messages.password') }}<span class="red_lab"> *</span></label>
                        <input class="form-control" id="password" placeholder="{{trans('messages.enterPassword')}}" reqired="required" name="password" type="password" value="{{ old('password')}}">
                        @if ($errors->has('password'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                        @endif
                        <em class="fa fa-eye form-control-feedback cp eye-icon-fa" id="togglePassword"></em>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group clearValidation">
                        <label for="confirm_password">{{ trans('messages.confirmPassword') }}<span class="red_lab"> *</span></label>
                        <input class="form-control" id="confirm_password" placeholder="{{trans('messages.enterConfirmPassword')}}" reqired="required" name="confirm_password" type="password" value="{{ old('confirm_password')}}">
                        @if ($errors->has('confirm_password'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('confirm_password') }} </strong>
                        </span>
                        @endif
                        <em class="fa fa-eye form-control-feedback cp eye-icon-fa" id="togglePassword2"></em>
                    </div>
                </div>
            </div>
            @endif
            <br />

            <div class="form-group d-flex gap-3">
                <button type="submit" class="btn theme_btn bg_theme font-semibold border-0 fs-6 px-sm-5">{{ trans('messages.submit') }}</button>
                <a href="{{route('admin.insuranceCompany.list')}}" class="btn px-sm-5 font-semibold border_btn"><em class="icon-refresh"></em> {{ trans("messages.cancel") }}</a>
            </div>
            {{ html()->closeModelForm() }}
        </div>
    </div>
</div>
@if(env('ENABLE_CLIENT_VALIDATION'))
<script integrity="" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
      $(document).ready(function() {
            $('.select2').select2();
        });
    $(function() {
        // Form Validation
        $("#insurance_companyForm").validate({
            rules: {

                full_name: {
                    required: true,
                    minlength: 2,
                    maxlength: 60,
                    notNumber: true,
                    validName: true,
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

            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).parents('.form-group').removeClass('error');
                $(element).parents('.form-group').addClass('success');
            },


        });
    });
</script>
@endif

<script type="text/javascript">
    $(document).ready(function() {

        var isPasswordVisible = false;
        var isConfirmPasswordVisible = false;

        document.getElementById("togglePassword").addEventListener("click", function() {
            var passwordInput = document.getElementById("password");
            isPasswordVisible = !isPasswordVisible; // Toggle the visibility state
            if (isPasswordVisible) {
                passwordInput.setAttribute("type", "text");
                document.getElementById("togglePassword").classList.remove("fa-eye");
                document.getElementById("togglePassword").classList.add("fa-eye-slash");
            } else {
                passwordInput.setAttribute("type", "password");
                document.getElementById("togglePassword").classList.remove("fa-eye-slash");
                document.getElementById("togglePassword").classList.add("fa-eye");
            }
        });

        document.getElementById("togglePassword2").addEventListener("click", function() {
            var confirm_passwordInput = document.getElementById("confirm_password");
            isConfirmPasswordVisible = !isConfirmPasswordVisible; // Toggle the visibility state
            if (isConfirmPasswordVisible) {
                confirm_passwordInput.setAttribute("type", "text");
                document.getElementById("togglePassword2").classList.remove("fa-eye");
                document.getElementById("togglePassword2").classList.add("fa-eye-slash");
            } else {
                confirm_passwordInput.setAttribute("type", "password");
                document.getElementById("togglePassword2").classList.remove("fa-eye-slash");
                document.getElementById("togglePassword2").classList.add("fa-eye");
            }
        });
    });
</script>

@stop
