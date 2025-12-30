<div class="mb-3 translation-tabs">

    {{--<div class="row">
        <div class="col-12 col-sm-6">
            <div class="clearValidation form-group">
                <label for="{{ trans('messages.faq') }}">
                    {{ trans('messages.faqCategory') }}
                    <span class="red_lab"> *</span>
                </label>

                @if(isset($data))
                {{ html()->select('faqcategories_id', $faqCategoriesData, $data['faq_category_id'])
                    ->attributes(['class' => 'form-control formValidate', 'placeholder' => trans("messages.name"), 'readOnly' => 'readOnly']) }}
                @else
                {{ html()->select('faqcategories_id', $faqCategoriesData)
                    ->attributes(['class' => 'form-control formValidate', 'placeholder' => trans("messages.name")]) }}
                @endif

                @if ($errors->has('faqcategories_id'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('faqcategories_id') }}</strong>
                </span>
                @endif
            </div>
        </div>
    </div>--}}

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
        <div language="{{ $languageRow->id }}" class="tab-pane   fade @php if($j == 0){echo " active show"; } @endphp" id="custom-tabs-{{ $languageRow->id }}" role="tabContent">
            <div class="row">
                <div class="col-12 col-sm-6">
                    <div class="clearValidation form-group
                            {{ $errors->has('question[' . $languageRow->id . ']') ? 'has-error': '' }}">
                        {{ html()->hidden('id[' . $languageRow->id . ']') }}
                        <label for=" {{ trans('messages.question') }} {{ '(' . $languageRow->name . ')' }}">
                            {{ trans('messages.question') }} {{ '(' . $languageRow->name . ')' }}
                            <span class="red_lab"> *</span>
                        </label>
                        {{ html()->text('question[' . $languageRow->id . ']')
                            ->attributes(['class' => 'form-control languages_question formValidate',
                            'placeholder' => trans("messages.question")]) }}
                        @if ($errors->has('question.' . $languageRow->id))
                        <span class="invalid-feedback " role="alert">
                            <strong>{{ $errors->first('question.' . $languageRow->id) }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-6">
                    <div class="clearValidation form-group
                            {{ $errors->has('answer[' . $languageRow->id . ']') ? 'has-error': '' }}">
                        {{ html()->hidden('id[' . $languageRow->id . ']') }}
                        <label for=" {{ trans('messages.answer') }} {{ '(' . $languageRow->answer . ')' }}">
                            {{ trans('messages.answer') }} {{ '(' . $languageRow->name . ')' }}
                            <span class="red_lab"> *</span>
                        </label>
                        {{ html()->text('answer[' . $languageRow->id . ']')
                            ->attributes(['class' => 'form-control languages_answer formValidate',
                            'placeholder' => trans("messages.answer")]) }}
                        @if ($errors->has('answer.' . $languageRow->id))
                        <span class="invalid-feedback " role="alert">
                            <strong>{{ $errors->first('answer.' . $languageRow->id) }}</strong>
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
            rules: {
                faqcategories_id: {
                    required: true,
                }
            },
            ignore: [],
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
        $('.languages_question').each(function(e) {
            $(this).rules('add', {
                required: true,
                minlength: 2,
                notNumber: true,
                messages: {
                    minlength: "{{ trans('messages.minGeneral') }}",
                    notNumber: "{{ trans('messages.notNumberMessage') }}"
                }
            })
        });
        $('.languages_answer').each(function(e) {
            $(this).rules('add', {
                required: true,
                minlength: 2,
                notNumber: true,
                messages: {
                    minlength: "{{ trans('messages.minGeneral') }}",
                    notNumber: "{{ trans('messages.notNumberMessage') }}"
                }
            })
        });
        $('#btnSubmit').on('click', function(e) {
            $("#<?php echo $formId; ?>").valid();
            removeTabError();
        });

    });
</script>
<?php if (isset($data['recordId'])) { ?>
    <script>
        $(function() {
            $("#imageInput").rules('add', {
                extension: "{{ Config::get('constants.validImageExtensions') }}",
                filesize: "{{ Config::get('constants.maxImageSizeJs') }}",
            });
        });
    </script>
<?php } else { ?>
    <script>
        $(function() {
            $("#imageInput").rules('add', {
                required: true,
                extension: "{{ Config::get('constants.validImageExtensions') }}",
                filesize: "{{ Config::get('constants.maxImageSizeJs') }}",
            });

        });
    </script>
<?php } ?>
@endif
