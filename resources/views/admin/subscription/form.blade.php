<?php use \App\Constants\Constant; ?>
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
                        <label for=" {{ trans('messages.name') }} {{ '(' . $languageRow->name . ')' }}">
                            {{ trans('messages.name') }} <span class="d-none">{{ '(' . $languageRow->name . ')' }}</span>
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

                <div class="col-12 col-sm-6">
                    <div class="clearValidation form-group
                        {{ $errors->has('validity[' . $languageRow->id . ']') ? 'has-error': '' }}">
                        {{ html()->hidden('id[' . $languageRow->id . ']') }}
                        <label for=" {{ trans('messages.validity_day') }} {{ '(' . $languageRow->name . ')' }}">
                            {{ trans('messages.validity_day') }} <span class="d-none">{{ '(' . $languageRow->name . ')' }}</span>
                            <span class="red_lab"> *</span>
                        </label>
                        {{ html()->number('validity[' . $languageRow->id . ']')
                            ->attributes(['class' => 'form-control languages_validity formValidate','min'=>'0',
                            'placeholder' => trans("messages.validity_day")]) }}
                        @if ($errors->has('validity.' . $languageRow->id))
                        <span class="invalid-feedback " role="alert">
                            <strong>{{ $errors->first('validity.' . $languageRow->id) }}</strong>
                        </span>
                        @endif
                    </div>
                </div>



                <div class="col-12 col-sm-6">
                    <div class="clearValidation form-group
                        {{ $errors->has('songs_service[' . $languageRow->id . ']') ? 'has-error': '' }}">
                        {{ html()->hidden('id[' . $languageRow->id . ']') }}
                        <label for=" {{ trans('messages.songs_service') }} {{ '(' . $languageRow->name . ')' }}">
                            {{ trans('messages.songs_service') }} <span class="d-none">{{ '(' . $languageRow->name . ')' }}</span>
                            <span class="red_lab"> *</span>
                        </label>
                        {{ html()->select('songs_service[' . $languageRow->id . ']',Constant::SONGS_CATEGORY)
                        ->attributes(['class' => 'form-control formValidate songs_service', 'placeholder' => trans("messages.songs_service")]) }}
                        @if ($errors->has('songs_service.' . $languageRow->id))
                        <span class="invalid-feedback " role="alert">
                            <strong>{{ $errors->first('songs_service.' . $languageRow->id) }}</strong>
                        </span>
                        @endif
                    </div>
                </div>


                <div class="col-12 col-sm-6">
                    <div class="clearValidation form-group
                        {{ $errors->has('price[' . $languageRow->id . ']') ? 'has-error': '' }}">
                        {{ html()->hidden('id[' . $languageRow->id . ']') }}
                        <label for=" {{ trans('messages.price') }} {{ '(' . $languageRow->name . ')' }}">
                            {{ trans('messages.SongsPrice') }} <span class="d-none">{{ '(' . $languageRow->name . ')' }}</span>
                            <span class="red_lab songs_price_valid"> *</span>
                        </label>
                        {{ html()->number('price[' . $languageRow->id . ']')
                            ->attributes(['class' => 'form-control languages_price formValidate','min'=>'0',
                            'placeholder' => trans("messages.SongsPrice")]) }}
                        @if ($errors->has('price.' . $languageRow->id))
                        <span class="invalid-feedback " role="alert">
                            <strong>{{ $errors->first('price.' . $languageRow->id) }}</strong>
                        </span>
                        @endif
                    </div>
                </div>


                <div class="col-12 col-sm-6">
                    <div class="clearValidation form-group
                        {{ $errors->has('video_service[' . $languageRow->id . ']') ? 'has-error': '' }}">
                        {{ html()->hidden('id[' . $languageRow->id . ']') }}
                        <label for=" {{ trans('messages.video_service') }} {{ '(' . $languageRow->name . ')' }}">
                            {{ trans('messages.video_service') }} <span class="d-none">{{ '(' . $languageRow->name . ')' }}</span>
                            <span class="red_lab"> *</span>
                        </label>
                        {{ html()->select('video_service[' . $languageRow->id . ']',Constant::VIDEO_SERVICE)
                        ->attributes(['class' => 'form-control formValidate video_service', 'placeholder' => trans("messages.video_service")]) }}
                        @if ($errors->has('video_service.' . $languageRow->id))
                        <span class="invalid-feedback " role="alert">
                            <strong>{{ $errors->first('video_service.' . $languageRow->id) }}</strong>
                        </span>
                        @endif
                    </div>
                </div>


                <div class="col-12 col-sm-6">
                    <div class="clearValidation form-group
                        {{ $errors->has('video_price[' . $languageRow->id . ']') ? 'has-error': '' }}">
                        {{ html()->hidden('id[' . $languageRow->id . ']') }}
                        <label for=" {{ trans('messages.price') }} {{ '(' . $languageRow->name . ')' }}">
                            {{ trans('messages.VideoPrice') }} <span class="d-none">{{ '(' . $languageRow->name . ')' }}</span>
                            <span class="red_lab video_price_valid"> *</span>
                        </label>
                        {{ html()->text('video_price[' . $languageRow->id . ']')
                            ->attributes(['class' => 'form-control  formValidate video_price','min'=>'0',
                            'placeholder' => trans("messages.VideoPrice")]) }}
                        @if ($errors->has('video_price.' . $languageRow->id))
                        <span class="invalid-feedback " role="alert">
                            <strong>{{ $errors->first('video_price.' . $languageRow->id) }}</strong>
                        </span>
                        @endif
                    </div>
                </div>


                <div class="col-12 col-sm-6">
                    <div class="clearValidation form-group
                        {{ $errors->has('subscription_type[' . $languageRow->id . ']') ? 'has-error': '' }}">
                        {{ html()->hidden('id[' . $languageRow->id . ']') }}
                        <label for=" {{ trans('messages.subscription_type') }} {{ '(' . $languageRow->name . ')' }}">
                            {{ trans('messages.subscription_type') }} <span class="d-none">{{ '(' . $languageRow->name . ')' }}</span>
                            <span class="red_lab"> *</span>
                        </label>
                        {{ html()->select('subscription_type[' . $languageRow->id . ']',Constant::SUBSCRIPTION_CATEGORY)
                        ->attributes(['class' => 'form-control formValidate subscription_type_validate', 'placeholder' => trans("messages.subscription_type")]) }}
                        @if ($errors->has('subscription_type.' . $languageRow->id))
                        <span class="invalid-feedback " role="alert">
                            <strong>{{ $errors->first('subscription_type.' . $languageRow->id) }}</strong>
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
        if($('.songs_service').val()=='free'){
            $('.languages_price ').prop('disabled', true);
        }else {
            $('.languages_price ').prop('disabled', false);
            }

        if($('.video_service').val()=='free'){
             $('.video_price ').prop('disabled', true);
        }else {
             $('.video_price ').prop('disabled', false);
            }
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

        $('.languages_validity').each(function(e) {
            $(this).rules('add', {
                required: true,
                integer:true,
                range: ['1', 31],

            })
        });

        $('.languages_price').each(function(e) {
            $(this).rules('add', {
                required: true,
                   number:true,
                range: ['1', 100000],

            })
        });

        $('.video_price').each(function(e) {
            $(this).rules('add', {
                required: true,
                number:true,
                range: ['1', 100000],
            })
        });

        $('.songs_service').each(function(e) {
            $(this).rules('add', {
                required: true,
            })
        });

        $('.video_service').each(function(e) {
            $(this).rules('add', {
                required: true,

            })
        });

        $('.subscription_type_validate').each(function(e) {
            $(this).rules('add', {
                required: true,

            })
        });

   });
</script>
@endif

<script>
     $(function() {
        if($('.songs_service').val()=='free'){
            $('.songs_price_valid').hide();
            $('.languages_price ').val(0).prop('disabled', true);
        }else{
            $('.languages_price').prop('disabled', false);
        }
        if($('.video_service').val()=='free'){
            $('.video_price_valid').hide();
            $('.video_price').val(0).prop('disabled', true);
        }else{
            $('.video_price').prop('disabled', false);
        }

        $('.video_service').change(function(){
            var selectedService = $(this).val();
            if (selectedService === 'free') {
                $('.video_price').val(0).prop('disabled', true);
                $('.video_price_valid').hide();

            } else {
                $('.video_price').val('').prop('disabled', false);
            }
        });

        $('.songs_service').change(function(){
            var selectedService = $(this).val();
            if (selectedService === 'free') {
                $('.languages_price ').val(0).prop('disabled', true);
                $('.songs_price_valid').hide();
            } else {
                $('.languages_price ').val('').prop('disabled', false);
            }
        });

		$('#btnSubmit').on('click', function(e) {
            $("#<?php echo $formId; ?>").valid();
            removeTabError();
        });
    });
</script>
