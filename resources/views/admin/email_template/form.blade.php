<ul class="nav nav-tabs border-0" role="tabList">
    @php $i = 0; @endphp
    @foreach ($languages as $laguageRowTop)
    <li class="nav-item">
        <a class="nav-link language_tab {{ $laguageRowTop->locale }}
                            @php if($i == 0){echo " active"; } @endphp" id="tabId{{ $laguageRowTop->id }}" data-bs-toggle="pill" href="#custom-tabs-{{ $laguageRowTop->id }}" role="tab" aria-selected="true">{{ $laguageRowTop->name }}
        </a>
    </li>
    @php $i++; @endphp
    @endforeach
</ul>


<div class="tab-content" id="tab-parent">
    @php $j = 0; @endphp
    @foreach ($languages as $languageRow)
    <div language="{{ $languageRow->id }}" class="tab-pane fade @php if($j == 0){echo " active show"; } @endphp" id="custom-tabs-{{ $languageRow->id }}" role="tabContent">
        <div class="row">
            <div class="col-12 col-sm-6">
                <div class="clearValidation form-group
                                     {{ $errors->has('name[' . $languageRow->id . ']') ? 'has-error': '' }}">
                    {{ html()->hidden('id[' . $languageRow->id . ']') }}
                    <label for=" {{ trans('Template title') }} {{ '(' . $languageRow->name . ')' }}">
                        {{ trans('Template title') }} <span class="d-none">{{ '(' . $languageRow->name . ')' }}</span>
                        <span class="red_lab"> *</span>
                    </label>
                    {{ html()->text('name[' . $languageRow->id . ']')
                                    ->attributes(['class' => 'form-control languages_name formValidate',
                                    'placeholder' => trans("Template title"),'readOnly'=>'readOnly']) }}
                    @if ($errors->has('name.' . $languageRow->id))
                    <span class="invalid-feedback " role="alert">
                        <strong>{{ $errors->first('name.' . $languageRow->id) }}</strong>
                    </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-6">
                <div class="clearValidation form-group
                                     {{ $errors->has('subject[' . $languageRow->id . ']') ? 'has-error': '' }}">
                    <label for=" {{ trans('messages.subject') }} {{ '(' . $languageRow->name . ')' }}">
                        {{ trans('messages.subject') }} <span class="d-none">{{ '(' . $languageRow->name . ')' }}</span>
                        <span class="red_lab"> *</span>
                    </label>
                    {{ html()->text('subject[' . $languageRow->id . ']')
                                    ->attributes(['class' => 'form-control languages_subject formValidate',
                                    'placeholder' => trans("messages.subject")]) }}
                    @if ($errors->has('subject.' . $languageRow->id))
                    <span class="invalid-feedback " role="alert">
                        <strong>{{ $errors->first('subject.' . $languageRow->id) }}</strong>
                    </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-6">
                <div class="clearValidation form-group
                                     {{ $errors->has('email_body[' . $languageRow->id . ']') ? 'has-error': '' }}">
                    <label for=" {{ trans('messages.email_body') }} {{ '(' . $languageRow->name . ')' }}">
                        {{ trans('messages.email_body') }} <span class="d-none">{{ '(' . $languageRow->name . ')' }}</span>
                        <span class="red_lab"> *</span>
                    </label>
                    {{ html()->textarea('email_body[' . $languageRow->id . ']')
                                    ->attributes(['class' => 'form-control languages_email_body  formValidate','id'=>'ckEditor'.$languageRow->id,
                                    'placeholder' => trans("messages.email_body")]) }}
                    @if ($errors->has('email_body.' . $languageRow->id))
                    <span class="invalid-feedback " role="alert">
                        <strong>{{ $errors->first('email_body.' . $languageRow->id) }}</strong>
                    </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @php $j++; @endphp
    @endforeach
</div>


<div class="clearValidation form-group mt-4">


    <!-- New Checkbox -->
    <span class="custom_check" for="{{ trans('messages.global_header_footer') }}">{{ trans('messages.global_header_footer') }}
        <span class="red_lab"> </span> &nbsp; {{ html()->checkbox('global_header_footer') }}<span class="check_indicator">&nbsp;</span></span>
</div>



@if (env('ENABLE_CLIENT_VALIDATION'))

<script>
    $(function() {

        window.formReference = $("#<?php echo $formId; ?>").validate({
            ignore: [],
            rules: {},
            errorElement: "span",
            highlight: function(element) {
                $(element).parents('.form-group').addClass('error');
            },
            unhighlight: function(element) {
                let lang = $(element).parent().parent().parent().parent().attr('language');
                $(element).parents('.form-group').removeClass('error');
                $(element).parents('.form-group').addClass('success');
                removeTabError2(lang);
            },
        });
        $('.languages_name').each(function(e) {
            $(this).rules('add', {
                required: true,
                minlength: 2,
                maxlength: 30,
                notNumber: true,
                messages: {
                    minlength: "{{ trans('messages.minGeneral') }}",
                    maxlength: "{{ trans('messages.maxGeneral') }}",
                    notNumber: "{{ trans('messages.notNumberMessage') }}"
                }
            })
        });

        $('.languages_subject').each(function(e) {
            $(this).rules('add', {
                required: true,
                minlength: 2,
                maxlength: 30,
                notNumber: true,
                messages: {
                    minlength: "{{ trans('messages.minGeneral') }}",
                    maxlength: "{{ trans('messages.maxGeneral') }}",
                    notNumber: "{{ trans('messages.notNumberMessage') }}"
                }
            })
        });


        $('.languages_email_body').each(function(e) {
            $(this).rules('add', {
                required: true,
                minlength: 2,
                maxlength: 50000,
                notNumber: true,
                messages: {
                    minlength: "{{ trans('messages.minGeneral') }}",
                    maxlength: "{{ trans('messages.max50000') }}"
                }
            })
        });

        $('#btnSubmit').on('click', function(e) {
            $("#<?php echo $formId; ?>").valid();
            removeTabError();
        });

    });
</script>

@endif
<script>
    $(document).ready(function() {
        $('.languages_email_body').each(function() {
            var Id = $(this).attr('id');
            var ckeditorInstance = CKEDITOR.replace(Id, {
                allowedContent: true,
                height: 320
            });
        });
        CKEDITOR.on('instanceReady', function() {
            setTimeout(function() {
                $.each(CKEDITOR.instances, function(instance) {
                    CKEDITOR.instances[instance].document.on("keyup", CK_jQ);
                    CKEDITOR.instances[instance].document.on("paste", CK_jQ);
                    CKEDITOR.instances[instance].document.on("keypress", CK_jQ);
                    CKEDITOR.instances[instance].document.on("blur", CK_jQ);
                    CKEDITOR.instances[instance].document.on("change", CK_jQ);
                });
            }, 2000);
        });

        function CK_jQ() {
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
        }
    });
</script>
