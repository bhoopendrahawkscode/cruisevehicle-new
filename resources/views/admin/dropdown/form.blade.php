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
</div>
@if($image_exist['image_show']==1)
<div class="row">
    <div class="col-sm-4 upload_img mb-5">
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
        <span class="imageHint" style="display: block">{{ trans('dropdowns.'.str_replace("_"," ",$type).'ImageHint') }}</span>
        <?php if (isset($data['recordId'])) { ?>
            <label class="exist_image">{{ trans('messages.existingImage') }}
            </label>
            <div class="old_img">
                <img class="border border-1" src="{{ $data['thumbImage'] }}" width="100" alt="image">
            </div>
        <?php } ?>
    </div>
    <div class="col-sm-2">
        <label class='image-preview'>{{ trans('messages.imagePreview') }}</label>
        <img id="image-preview" class="image-preview" alt="{{ trans('messages.imagePreview') }}" class="img-fluid rounded-circle" width="150px">
    </div>
</div>

@endif

@if (env('ENABLE_CLIENT_VALIDATION'))
<script>
    $(function() {
        window.formReference = $("#dropDownForm").validate({
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
        $('.languages_quantity').each(function(e) {
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
            $("#dropDownForm").valid();
            removeTabError();
        });



    });
</script>
@if($image_exist['image_show']==1)
<script>
    $(function() {
        checkImage(false);
        $("#imageInput").change(function(e) {
            checkImage(true);
        });
    });
</script>
@endif
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
