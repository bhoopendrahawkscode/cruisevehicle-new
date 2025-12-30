@extends('admin.layouts.default_layout')
@section('content')
    <?php use App\Constants\Constant; ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <div class="header d-flex align-items-center">
        <h1 class="page-header">
            {{ trans('messages.edit') }} {{ trans('messages.user') }}
        </h1>
        <ol class="breadcrumb ms-auto mb-0">
            <li><a href="{{ Route('admin.dashboard') }}"><em class="fa fa-dashboard"></em>
                    {{ trans('messages.dashboard') }}</a></li>
            <li><a href="{{ route('admin.listUsers') }}">{{ trans('messages.user') }} {{ trans('messages.list') }}</a></li>
            <li class="active">{{ trans('messages.edit') }}</li>
        </ol>
    </div>
    <div id="page-inner">
        <div class="panel panel-default">
            <div class="panel-body">
                {{ html()->modelForm($user, 'POST')->route('admin.updateUser', $user)->attributes(['id' => 'userForm', 'class' => 'form-horizontal', 'role' => 'form', 'autocomplete' => 'off'])->open() }}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="first_name">{{ trans('messages.firstName') }} <span class="red_lab">
                                    *</span></label>
                            {{ html()->text('first_name', null)->attributes(['class' => 'form-control', 'placeholder' => trans('messages.firstName')]) }}
                            @if ($errors->has('first_name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('first_name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="last_name">{{ trans('messages.lastName') }}<span class="red_lab"> *</span></label>
                            {{ html()->text('last_name', null)->attributes(['class' => 'form-control', 'placeholder' => trans('messages.lastName')]) }}
                            @if ($errors->has('last_name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('last_name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group clearValidation">
                            <label for="email">{{ trans('messages.email') }} <span class="red_lab"> *</span></label>
                            {{ html()->text('email', null)->attributes(['class' => 'form-control', 'placeholder' => trans('messages.email')]) }}
                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group ">
                            <label for="username">{{ trans('messages.username') }} <span class="red_lab"> *</span></label>
                            {{ html()->text('username', null)->attributes(['class' => 'form-control', 'placeholder' => trans('messages.username')]) }}
                            @if ($errors->has('username'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('username') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <br />


                @if (Constant::ALLOW_SUB_ADMIN_PHONE_NO)
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group clearValidation3">
                                <label for="phone">{{ trans('messages.phoneNo') }} <span class="red_lab">
                                        *</span></label>
                                <div class="row">
                                    <div class="col-sm-6 mb-4 mb-sm-0 errorPlacement2">
                                        {{ html()->select('country_code', $phonecode, $user->country_code)->attributes([
                                                'class' => 'form-control select2',
                                                'placeholder' => trans('messages.phoneNo'),
                                                'required' => 'required',
                                            ]) }}
                                        <span class="invalid-feedback " role="alert">
                                            <strong>{{ $errors->first('country_code') }}</strong>
                                        </span>
                                    </div>
                                    <div class="col-sm-6">
                                        {{ html()->text('mobile_no', @$temp[1])->attributes(['class' => 'form-control ', 'placeholder' => trans('messages.phoneNo')]) }}
                                    </div>
                                </div>
                                @if ($errors->has('mobile_no'))
                                    <span class="invalid-feedback " role="alert">
                                        <strong>{{ $errors->first('mobile_no') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                

                <div class="form-group d-flex gap-3">
                    <button type="submit"
                        class="btn theme_btn bg_theme font-semibold border-0 fs-6 px-sm-5">{{ trans('messages.submit') }}</button>
                    <a href="{{ route('admin.listUsers') }}" class="btn px-sm-5 font-semibold border_btn"><em
                            class="icon-refresh"></em> {{ trans('messages.cancel') }}</a>
                </div>
                {{ html()->closeModelForm() }}
            </div>
        </div>
    </div>
    @if (env('ENABLE_CLIENT_VALIDATION'))
        <script integrity="" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
        <script>
            const validateUserNameUrl = "{{ route('admin.validateUsername') }}";
            const user_id = "{{ $user->id }}";
            $(function() {
                // Form Validation
                $("#userForm").validate({
                    rules: {
                        country_code: {
                            required: true,
                        },
                        first_name: {
                            required: true,
                            minlength: 2,
                            maxlength: 30,
                            notNumber: true,
                            validName: true,

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

                        username: {
                            required: true,
                            minlength: 5,
                            maxlength: 30,
                            remote: {
                                url: validateUserNameUrl,
                                type: "POST",
                                data: {
                                    id: function() {
                                        return user_id;
                                    }

                                }
                            }
                        },

                    },
                    messages: {
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

                        username: {
                            remote: "{{ trans('messages.usernameAlreadyExists') }}",
                        }

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

    <script src="{{ asset('assets/js/visable-password.js') }}"></script>
    <script src="{{ asset('assets/js/custom-user-define-fun.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.select2').select2();
            AllowNumericOnly("#userForm input[type=text][name=mobile_no]");
            BlockSpaceForInput("#userForm input[type=text][name=username]");
        });
    </script>


@stop
