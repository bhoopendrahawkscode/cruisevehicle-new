<div class="mb-3 translation-tabs">
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
                            {{ $errors->has('description[' . $languageRow->id . ']') ? 'has-error': '' }}">
                        {{ html()->hidden('id[' . $languageRow->id . ']') }}
                        <label for=" {{ trans('messages.content') }} {{ '(' . $languageRow->name . ')' }}">
                            {{ trans('messages.content') }} {{ '(' . $languageRow->name . ')' }}
                            <span class="red_lab"> *</span>
                        </label>
                        {{ html()->text('description[' . $languageRow->id . ']')
                            ->attributes(['class' => 'form-control languages_description formValidate',
                            'placeholder' => trans("messages.content")]) }}
                        @if ($errors->has('description.' . $languageRow->id))
                        <span class="invalid-feedback " role="alert">
                            <strong>{{ $errors->first('description.' . $languageRow->id) }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-6">
                    <div class="clearValidation form-group
                            {{ $errors->has('designation[' . $languageRow->id . ']') ? 'has-error': '' }}">
                        <label for=" {{ trans('messages.designation') }} {{ '(' . $languageRow->designation . ')' }}">
                            {{ trans('messages.designation') }} {{ '(' . $languageRow->name . ')' }}
                            <span class="red_lab"> *</span>
                        </label>
                        {{ html()->text('designation[' . $languageRow->id . ']')
                            ->attributes(['class' => 'form-control languages_designation formValidate',
                            'placeholder' => trans("messages.designation")]) }}
                        @if ($errors->has('designation.' . $languageRow->id))
                        <span class="invalid-feedback " role="alert">
                            <strong>{{ $errors->first('designation.' . $languageRow->id) }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-sm-6">
                    <div class="clearValidation form-group
                            {{ $errors->has('giver[' . $languageRow->id . ']') ? 'has-error': '' }}">
                        <label for=" {{ trans('messages.giver') }} {{ '(' . $languageRow->giver . ')' }}">
                            {{ trans('messages.giver') }} {{ '(' . $languageRow->name . ')' }}
                            <span class="red_lab"> *</span>
                        </label>
                        {{ html()->text('giver[' . $languageRow->id . ']')
                            ->attributes(['class' => 'form-control languages_giver formValidate',
                            'placeholder' => trans("messages.giver")]) }}
                        @if ($errors->has('giver.' . $languageRow->id))
                        <span class="invalid-feedback " role="alert">
                            <strong>{{ $errors->first('giver.' . $languageRow->id) }}</strong>
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

<div class="row">
    <div class="col-lg-4 col-md-6 upload_img mb-5">
        <label>{{ trans('messages.image') }}
            @if(!isset($data['recordId']))
            <span class="red_lab"> *</span>
            @endif
        </label>
        <input name="image" type="file" id="imageInput" accept="image/*">
        <span id="file-size-error" class="text-danger"></span>
        @if ($errors->has('image'))
        <span class="invalid-feedback error" role="alert">
            <strong>{{ $errors->first('image') }}</strong>
        </span>
        @endif
        <span class="imageHint" style="display: block">{{ trans("messages.$title.ImageHint") }}</span>
        <?php if (isset($data['recordId'])) { ?>
            <label class="exist_image">{{ trans('messages.existingImage') }}
            </label>
            <div class="old_img">
                <img alt="Image" class="border border-1" src="{{ $data['thumbImage'] }}" width="100">
            </div>
        <?php } ?>
    </div>
    <div class="col-lg-2  col-md-6">
        <label class='image-preview'>{{ trans('messages.imagePreview') }}</label>
        <img id="image-preview" class="image-preview" alt="{{ trans('messages.imagePreview') }}" class="img-fluid rounded-circle" width="150px">
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

        $('.languages_description').each(function(e) {
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

        $('.languages_designation').each(function(e) {
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

        $('.languages_giver').each(function(e) {
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
        checkImage(false);
        $("#imageInput").change(function(e) {
            checkImage(true);
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
