
<div class="mb-3 translation-tabs">
    <div class="row">
        <div class="col-12 col-sm-6">
            <div class="clearValidation form-group {{ $errors->has('nameMain') ? 'has-error': '' }}">
                <label for="{{ trans('messages.name') }}">
                    {{ trans('messages.name') }}
                    <span class="red_lab"> *</span>
                </label>
                {{ html()->text('nameMain')->attributes(['class' => 'form-control nameMain formValidate',
                'placeholder' => trans("messages.name"),'readOnly'=>'readOnly']) }}
                @if ($errors->has('nameMain'))
                <span class="invalid-feedback " role="alert">
                    <strong>{{ $errors->first('nameMain') }}</strong>
                </span>
                @endif
            </div>
        </div>
    </div>

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
                                     {{ $errors->has('title[' . $languageRow->id . ']') ? 'has-error': '' }}">
                        {{ html()->hidden('id[' . $languageRow->id . ']') }}
                        <label for=" {{ trans('messages.title') }} {{ '(' . $languageRow->name . ')' }}">
                            {{ trans('messages.title') }} <span class="d-none">{{ '(' . $languageRow->name . ')' }}</span>
                            <span class="red_lab"> *</span>
                        </label>
                        {{ html()->text('title[' . $languageRow->id . ']')
                                    ->attributes(['class' => 'form-control languages_title formValidate',
                                    'placeholder' => trans("messages.title")]) }}
                        @if ($errors->has('title.' . $languageRow->id))
                        <span class="invalid-feedback " role="alert">
                            <strong>{{ $errors->first('title.' . $languageRow->id) }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="clearValidation form-group
                                     {{ $errors->has('meta_title[' . $languageRow->id . ']') ? 'has-error': '' }}">
                        <label for=" {{ trans('messages.metaTitle') }} {{ '(' . $languageRow->name . ')' }}">
                            {{ trans('messages.metaTitle') }} <span class="d-none">{{ '(' . $languageRow->name . ')' }}</span>
                            <span class="red_lab"> *</span>
                        </label>
                        {{ html()->text('meta_title[' . $languageRow->id . ']')
                                    ->attributes(['class' => 'form-control languages_meta_title formValidate',
                                    'placeholder' => trans("messages.metaTitle")]) }}
                        @if ($errors->has('meta_title.' . $languageRow->id))
                        <span class="invalid-feedback " role="alert">
                            <strong>{{ $errors->first('meta_title.' . $languageRow->id) }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-sm-6">
                    <div class="clearValidation form-group
                                     {{ $errors->has('meta_keywords[' . $languageRow->id . ']') ? 'has-error': '' }}">
                        <label for=" {{ trans('messages.metaKeywords') }} {{ '(' . $languageRow->name . ')' }}">
                            {{ trans('messages.metaKeywords') }} <span class="d-none">{{ '(' . $languageRow->name . ')' }}</span>
                            <span class="red_lab"> *</span>
                        </label>
                        {{ html()->text('meta_keywords[' . $languageRow->id . ']')
                                    ->attributes(['class' => 'form-control languages_meta_keywords formValidate',
                                    'placeholder' => trans("messages.metaKeywords")]) }}
                        @if ($errors->has('meta_keywords.' . $languageRow->id))
                        <span class="invalid-feedback " role="alert">
                            <strong>{{ $errors->first('meta_keywords.' . $languageRow->id) }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="clearValidation form-group
                                     {{ $errors->has('meta_description[' . $languageRow->id . ']') ? 'has-error': '' }}">
                        <label for=" {{ trans('messages.metaDescription') }} {{ '(' . $languageRow->name . ')' }}">
                            {{ trans('messages.metaDescription') }} <span class="d-none">{{ '(' . $languageRow->name . ')' }}</span>
                            <span class="red_lab"> *</span>
                        </label>
                        {{ html()->text('meta_description[' . $languageRow->id . ']')
                                    ->attributes(['class' => 'form-control languages_meta_description formValidate',
                                    'placeholder' => trans("messages.metaDescription")]) }}
                        @if ($errors->has('meta_description.' . $languageRow->id))
                        <span class="invalid-feedback " role="alert">
                            <strong>{{ $errors->first('meta_description.' . $languageRow->id) }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-6">
                    <div class="clearValidation form-group
                                     {{ $errors->has('body[' . $languageRow->id . ']') ? 'has-error': '' }}">
                        <label for=" {{ trans('messages.cmsBody') }} {{ '(' . $languageRow->name . ')' }}">
                            {{ trans('messages.cmsBody') }} <span class="d-none">{{ '(' . $languageRow->name . ')' }}</span>
                            <span class="red_lab"> *</span>
                        </label>
                        {{ html()->textarea('body[' . $languageRow->id . ']')
                                    ->attributes(['class' => 'form-control languages_body formValidate',
                                    'placeholder' => trans("messages.cmsBody")]) }}
                        @if ($errors->has('body.' . $languageRow->id))
                        <span class="invalid-feedback " role="alert">
                            <strong>{{ $errors->first('body.' . $languageRow->id) }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @php $j++; @endphp
        @endforeach
    </div>


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
        $('.languages_title').each(function(e) {
            $(this).rules('add', {
                required: true,
                minlength: 2,
                maxlength: 55,
                notNumber: true,
                messages: {
                    minlength: "{{ trans('messages.minGeneral') }}",
                    notNumber: "{{ trans('messages.notNumberMessage') }}"
                }
            })
        });

        $('.languages_meta_title').each(function(e) {
            $(this).rules('add', {
                required: true,
                minlength: 2,
                maxlength: 500,
                notNumber: true,
                messages: {
                    minlength: "{{ trans('messages.minGeneral') }}",
                    maxlength: "{{ trans('messages.max500') }}",
                    notNumber: "{{ trans('messages.notNumberMessage') }}"
                }
            })
        });

        $('.languages_meta_keywords').each(function(e) {
            $(this).rules('add', {
                required: true,
                minlength: 2,
                maxlength: 2000,
                notNumber: true,
                messages: {
                    minlength: "{{ trans('messages.minGeneral') }}",
                    maxlength: "{{ trans('messages.max2000') }}",
                    notNumber: "{{ trans('messages.notNumberMessage') }}"
                }
            })
        });

        $('.languages_meta_description').each(function(e) {
            $(this).rules('add', {
                required: true,
                minlength: 2,
                maxlength: 2000,
                notNumber: true,
                messages: {
                    minlength: "{{ trans('messages.minGeneral') }}",
                    maxlength: "{{ trans('messages.max2000') }}",
                    notNumber: "{{ trans('messages.notNumberMessage') }}"
                }
            })
        });


        $('.languages_body').each(function(e) {
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
        $('.languages_body').each(function() {
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
