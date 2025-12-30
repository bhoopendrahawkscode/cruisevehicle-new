@extends('admin.layouts.default_layout')
@section('content')
    <?php use App\Constants\Constant; ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />



    <div class="header d-flex align-items-center">
        <h1 class="page-header">
            {{ trans('messages.role') }} {{ trans('messages.list') }}


        </h1>
        <ol class="breadcrumb ms-auto mb-0">
            <li><a href="{{ Route('admin.dashboard') }}"><em class="fa fa-dashboard"></em> {{ trans('messages.dashboard') }}
                </a></li>
                <li><a href={{ route('admin.role.list') }}><em class="fa fa-users"></em> {{ trans('messages.dashboard') }}
                </a></li>
            <li class="active">{{ trans('messages.edit') }} {{ trans('messages.role') }}</li>
        </ol>
    </div>
    <div id="page-inner">
        <div class="panel panel-default">
            <div class="panel-body">
                {{ html()->modelForm($role, 'POST')->route('admin.role.update', $role)->attributes(['id' => 'roleForm', 'class' => 'form-horizontal', 'role' => 'form', 'autocomplete' => 'off'])->open() }}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">{{ trans('messages.name') }} <span class="red_lab">
                                    *</span></label>
                            {{ html()->text('name', null)->attributes(['class' => 'form-control', 'placeholder' => trans('messages.name')]) }}
                            @if ($errors->has('name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group third_party_select">
                            <label for="phone">Permissions list <span style="color:#5f8bc2;font-weight:bold;">(Click on
                                    module name to select entire module) </span> </label>
                            @php $permissions_list = permissions_list();
                            @endphp
                            {{ html()->multiselect('permissions[]', $permissions_list, old('permissions') ? old('permissions') : $role->permissions()->pluck('permission_id')->toArray())->attributes(['class' => 'form-control', 'id' => 'mySelect']) }}
                        </div>
                    </div>


                    <div class="form-group d-flex gap-3">
                        <button type="submit"
                            class="btn theme_btn bg_theme font-semibold border-0 fs-6 px-sm-5">{{ trans('messages.submit') }}</button>
                        <a href="{{ route('admin.role.list') }}" class="btn px-sm-5 font-semibold border_btn"><em
                                class="icon-refresh"></em> {{ trans('messages.cancel') }}</a>
                    </div>
                    {{ html()->closeModelForm() }}
                </div>
            </div>
        </div>
        @if (env('ENABLE_CLIENT_VALIDATION'))
            <script integrity="" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
            <script>
                const validateNameUrl = "{{ route('admin.role.validate_name') }}";
                const role_id = "{{ $role->id }}";
                $(document).ready(function() {
                    $('.select2').select2();
                });

                $(function() {
                    // Form Validation
                    $("#roleForm").validate({
                        rules: {
                            name: {
                                required: true,
                                minlength: 2,
                                maxlength: 30,
                                notNumber: true,
                                remote: {
                                    url: validateNameUrl,
                                    type: "POST",
                                    data: {
                                        id: function() {
                                            return role_id;
                                        }

                                    }
                                }
                            },
                        },
                        messages: {
                            name: {
                                minlength: "{{ trans('messages.min2Max30') }}",
                                maxlength: "{{ trans('messages.min2Max30') }}",
                                notNumber: "{{ trans('messages.notNumberMessage') }}",
                                remote: "{{ trans('messages.uniqueRole') }}",
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

        <link href="{{ asset('/assets/css/bootstrap-multiselect.min.css') }}" rel="stylesheet">
        <script src="{{ asset('/assets/js/bootstrap-multiselect.min.js') }}"></script>
        <script type="text/javascript">
            $(document).ready(function() {

                $("#mySelect").multiselect({
                    onChange: function(option, checked, select) {

                        if (!option.parent) {
                            return false;
                        }
                        parentGroup = (option).parent();
                        var parentGroupFirstOption = $(parentGroup).children(':first-child');
                        if ($(option).is(":checked")) {
                            $('#mySelect').multiselect('select', [$(parentGroupFirstOption).val()]);
                        }
                        if (!$(parentGroupFirstOption).is(":checked")) {
                            $(parentGroup).children().each(function() {
                                let childValue = $(this).val();
                                $('#mySelect').multiselect('deselect', [childValue]);
                            });
                        }
                        if ($('#mySelect option:checked').length == 1) {
                            // $('.multiselect-selected-text').html("1");
                            setTimeout(function() {
                                $('.multiselect-selected-text').html("1 Selected")
                            }, 1);
                        }

                    },
                    includeSelectAllOption: true,
                    enableCaseInsensitiveFiltering: true,
                    enableClickableOptGroups: true,
                    enableCollapsibelOptGroups: true,
                    numberDisplayed: 1,
                    templates: {
                        button: '<button type="button" class="multiselect dropdown-toggle btn btn-primary" data-bs-toggle="dropdown" aria-expanded="false"><span class="multiselect-selected-text"></span></button>',
                    },

                });
            });
        </script>
    @endsection
