<div class="mb-3 translation-tabs">
    <div class="row">
        <div class="col-12 col-sm-6">
            <div class="clearValidation form-group">
                <label for="{{ trans('messages.parent') }}">
                    {{ trans('messages.parentCategory') }}
                </label>
                {{ html()->select('parent_id',$tree)->attributes(['class' => 'form-control  formValidate',
                'placeholder' => trans("messages.name"),'readOnly'=>'readOnly']) }}
                @if ($errors->has('parent_id'))
                <span class="invalid-feedback " role="alert">
                    <strong>{{ $errors->first('parent_id') }}</strong>
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
        <div language="{{ $languageRow->id }}" class="tab-pane   fade @php if($j == 0){echo " active show"; } @endphp" id="custom-tabs-{{ $languageRow->id }}" role="tabContent">
            <div class="row">
                <div class="col-12 col-sm-6">
                    <div class="clearValidation form-group
                            {{ $errors->has('name[' . $languageRow->id . ']') ? 'has-error': '' }}">
                        {{ html()->hidden('id[' . $languageRow->id . ']') }}
                        <label for=" {{ trans('messages.name') }} {{ '(' . $languageRow->name . ')' }}">
                            {{ trans('messages.name') }} {{ '(' . $languageRow->name . ')' }}
                            <span class="red_lab"> *</span>
                        </label>
                        {{ html()->text('name[' . $languageRow->id . ']')
                            ->attributes(['class' => 'form-control languages_name formValidate',
                            'placeholder' => trans("messages.name")]) }}
                        @if ($errors->has('name.' . $languageRow->id))
                        <span class="invalid-feedback " role="alert">
                            <strong>{{ $errors->first('name.' . $languageRow->id) }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @php $j++; @endphp
        @endforeach
    </div>
    <div class="row">
        <div class="col-12 col-sm-6">
            <div class="clearValidation form-group mt-3">
                <label for="{{ trans('messages.parent') }}">
                    {{ trans('messages.status') }}
                </label>
                {{ html()->select('status',['1' => trans('messages.active'), '0' => trans('messages.inActive')])
                ->attributes(['class' => 'form-control  formValidate',
                'placeholder' => trans("messages.name"),'readOnly'=>'readOnly']) }}
            </div>
        </div>
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

        $('#btnSubmit').on('click', function(e) {
            $("#<?php echo $formId; ?>").valid();
            removeTabError();
        });
    });
</script>
@endif
