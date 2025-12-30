
<div class="row">
    <div class="col-12 col-sm-6">
        <div class="clearValidation form-group
            {{ $errors->has('day') ? 'has-error': '' }}">
            <label for=" {{ trans('messages.day') }} ">
                {{ trans('messages.day') }}
                <span class="red_lab"> *</span>
            </label>
            @if (isset($data['recordId']))
                {{ html()->text('day')
                ->attributes(['readonly'=>'readonly','class' => 'form-control languages_day formValidate',
                'placeholder' => trans("messages.day")]) }}
            @else
                {{ html()->select('day')->options($allDays)
                ->attributes(['class' => 'form-control languages_day formValidate',
                'placeholder' => trans("messages.day")]) }}
            @endif
            @if ($errors->has('day'))
            <span class="invalid-feedback " role="alert">
                <strong>{{ $errors->first('day') }}</strong>
            </span>
            @endif
        </div>
    </div>
</div>

<div class="mb-3 translation-tabs">
    <ul class="nav nav-tabs border-0" role="tabList">
        @php $i = 0; @endphp
        @foreach ($languages as $laguageRowTop)
        <li class="nav-item d-none">
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
                        <label for=" {{ trans('messages.quote') }} {{ '(' . $languageRow->name . ')' }}">
                            {{ trans('messages.quote') }} <span class="d-none">{{ '(' . $languageRow->name . ')' }}</span>
                            <span class="red_lab"> *</span>
                        </label>
                        {{ html()->textarea('name[' . $languageRow->id . ']')
                            ->attributes(['class' => 'form-control languages_name formValidate',
                            'placeholder' => trans("messages.quote")]) }}
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
                        {{ $errors->has('written_by[' . $languageRow->id . ']') ? 'has-error': '' }}">
                        <label for=" {{ trans('messages.written_by') }} {{ '(' . $languageRow->name . ')' }}">
                            {{ trans('messages.written_by') }} <span class="d-none">{{ '(' . $languageRow->name . ')' }}</span>
                            <span class="red_lab"> *</span>
                        </label>
                        {{ html()->text('written_by[' . $languageRow->id . ']')
                            ->attributes(['class' => 'form-control languages_written_by formValidate',
                            'placeholder' => trans("messages.written_by")]) }}
                        @if ($errors->has('written_by.' . $languageRow->id))
                        <span class="invalid-feedback " role="alert">
                            <strong>{{ $errors->first('written_by.' . $languageRow->id) }}</strong>
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
            rules: {
				day: {
					required: true,
				}
			},
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
                minlength: 20,
                maxlength: 150,
                notNumber: true,
                messages: {
                    minlength: "{{ trans('messages.min20') }}",
                    maxlength: "{{ trans('messages.max150') }}",
                    notNumber: "{{ trans('messages.notNumberMessage') }}"
                }
            })
        });

        $('.languages_written_by').each(function(e) {
            $(this).rules('add', {
                required: true,
                minlength: 20,
                maxlength: 50,
                notNumber: true,
                messages: {
                    minlength: "{{ trans('messages.min20') }}",
                    maxlength: "{{ trans('messages.max50') }}",
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
