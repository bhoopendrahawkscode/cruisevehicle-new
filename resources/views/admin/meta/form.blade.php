<div class="mb-3 translation-tabs">
    <ul class="nav nav-tabs border-0" role="tabList">
        @php $i = 0; @endphp
        @foreach ($languages as $laguageRowTop)
            <li class="nav-item">
                <a class="nav-link language_tab {{ $laguageRowTop->locale }}
                    @php if($i == 0){echo " active"; } @endphp"
                    id="tabId{{ $laguageRowTop->id }}" data-bs-toggle="pill" href="#custom-tabs-{{ $laguageRowTop->id }}"
                    role="tab" aria-selected="true">{{ $laguageRowTop->name }}
                </a>
            </li>
            @php $i++; @endphp
        @endforeach
    </ul>
    <div class="tab-content" id="tab-parent">
        @php $j = 0; @endphp
        @foreach ($languages as $key=> $languageRow)
            <div language="{{ $languageRow->id }}"
                class="tab-pane   fade @php if($j == 0){echo " active show"; } @endphp"
                id="custom-tabs-{{ $languageRow->id }}" role="tabContent">
                <div class="row">
                    <div class="col-6 col-sm-6">
                        <div
                            class="clearValidation form-group
                            {{ $errors->has('meta_title[' . $languageRow->id . ']') ? 'has-error' : '' }}">
                            <label for="{{ trans('messages.meta_title') }}_{{ $languageRow->id }}">
                                @lang('messages.meta') @lang('messages.title') {{ '(' . $languageRow->name . ')' }}
                            </label>

                            {{ html()->text('meta_title[' . $languageRow->id . ']')->attributes([
                                    'class' => 'form-control languages_title formValidate',
                                    'placeholder' => trans('messages.meta_title'),
                                    'id' => trans('messages.meta_title') . '_' . $languageRow->id,
                                ]) }}

                            @if ($errors->has('meta_title.' . $languageRow->id))
                                <span class="invalid-feedback " role="alert">
                                    <strong>{{ $errors->first('meta_title.' . $languageRow->id) }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-6 col-sm-6">
                        <div
                            class="clearValidation form-group
                            {{ $errors->has('meta_key[' . $languageRow->id . ']') ? 'has-error' : '' }}">
                            <label for="{{ trans('messages.meta_key') }}_{{ $languageRow->id }}">
                                @lang('messages.meta_key') {{ '(' . $languageRow->name . ')' }}
                            </label>

                            {{ html()->text('meta_key[' . $languageRow->id . ']')->attributes([
                                    'class' => 'form-control languages_title formValidate',
                            
                                    'placeholder' => trans('messages.meta_key'),
                                    'id' => trans('messages.meta_key') . '_' . $languageRow->id,
                                ]) }}

                            @if ($errors->has('meta_key.' . $languageRow->id))
                                <span class="invalid-feedback " role="alert">
                                    <strong>{{ $errors->first('meta_key.' . $languageRow->id) }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                @if(isset($meta))
                <input type="hidden" name="meta_translations_id[{{$languageRow->id}}]" value="{{$data['id'][$languageRow->id]}}" />
                @endif

                <div class="row">
                    <div class="col-6">
                        <div class="col-md-4` upload_img mb-5">

                            <label for="{{ $languageRow->id }}">@lang('messages.image') ({{ $languageRow->name }})
                                @if (!isset($data['recordId']))
                                @endif
                            </label>
                            <input name="image[{{ $languageRow->id }}]" type="file"
                                id="imageInput_{{ $languageRow->id }}" class="Inputimg" accept="image/*">
                            <span id="file-size-error" class="text-danger"></span>
                  
                            @if ($errors->has('image.'.$languageRow->id))

                                <span class="invalid-feedback error" role="alert">
                                    <strong> {{ $errors->first('image.'.$languageRow->id)}}</strong>
                                </span>
                            @endif
                            <span class="imageHint" style="display: block">{{ trans('messages.imageHint') }}</span>
                            @if (isset($data['image'][$languageRow->id]))
                                <label class="exist_image">{{ trans('messages.existingImage') }}
                                </label>
                                <div class="old_img">

                                    <img alt="Image" class="border border-1"
                                        src="{{$meta->meta_translations[$key]->thumbImage}}" width="100">
                                </div>
                            @endif
                        </div>
                        <div class="col-lg-2  col-md-6">
                            <label class='image-preview'>{{ trans('messages.imagePreview') }}</label>
                            <img id="image-preview" class="image-preview" alt="{{ trans('messages.imagePreview') }}"
                                class="img-fluid rounded-circle" width="150px">
                        </div>
                    </div>

                    <div class="col-6 col-sm-6">
                        <div
                            class="clearValidation form-group
                            {{ $errors->has('meta_description[' . $languageRow->id . ']') ? 'has-error' : '' }}">
                            <label for="{{ trans('messages.meta_description') }}_{{ $languageRow->id }}">
                                @lang('messages.meta') @lang('messages.description') {{ '(' . $languageRow->name . ')' }}
                            </label>

                            {{ html()->textarea('meta_description[' . $languageRow->id . ']')->attributes([
                                    'class' => 'form-control languages_description formValidate',
                                    'placeholder' => trans('messages.meta_description'),
                                    'id' => trans('messages.meta_description') . '_' . $languageRow->id,
                                    'style' => 'height:220px',
                                ]) }}

                            @if ($errors->has('meta_description.' . $languageRow->id))
                                <span class="invalid-feedback " role="alert">
                                    <strong>{{ $errors->first('meta_description.' . $languageRow->id) }}</strong>
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
    <div class="col-6 col-sm-6">
        <div class="clearValidation form-group
            {{ $errors->has('name') ? 'has-error' : '' }}">
            <label for="name">
                @lang('messages.name')
                <span class="red_lab">
                    *</span></label>
            </label>

            {{ html()->text('name')->attributes([
                    'class' => 'form-control languages_title formValidate',
                    'placeholder' => trans('messages.name'),
                    'id' => 'name',
                    'value' => $meta->name ?? null,
                ]) }}

            @if ($errors->has('name'))
                <span class="invalid-feedback " role="alert">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="col-6 col-sm-6">
        <div class="clearValidation form-group
            {{ $errors->has('url') ? 'has-error' : '' }}">
            <label for="url">
                @lang('messages.route')
                <span class="red_lab">
                    *</span></label>
            </label>
            @php
                $disabled = isset($meta->url) ? 'disabled' : '';
            @endphp
            {{ html()->text('url')->attributes([
                    'class' => 'form-control languages_title formValidate',
                    'placeholder' => trans('messages.route'),
                    'id' => 'url',
                    'value' => $meta->url ?? null,
                    $disabled,
                ]) }}

            @if ($errors->has('url'))
                <span class="invalid-feedback " role="alert">
                    <strong>{{ $errors->first('url') }}</strong>
                </span>
            @endif
        </div>
    </div>
</div>

@if (env('ENABLE_CLIENT_VALIDATION'))
    <script>
        const validateName = "{{route('meta.tag.validateName')}}";
        const meta_id =  "{{ isset($meta) ? $meta->id : null }}";
        window.formReference = $("#metaForm").validate({
            rules: {
                name: {
                    required: true,
                    minlength: 2,
                    maxlength: 30,
                    notNumber: true,
                    remote: {
                            url: validateName,
                            type: "POST",
                            data: {
                                id: function() {
                                    return meta_id;
                                }
                            }
                        }
                },

                url: {
                    required: true,
                    minlength: 2,
                    notNumber: true,
                },
             

            },
            messages: {
                name: {
                    minlength: "{{ trans('messages.min2Max30') }}",
                    maxlength: "{{ trans('messages.min2Max30') }}",
                    remote: "{{ trans('messages.name_is_already_exists') }}",
                },

                url: {
                    minlength: "{{ trans('messages.min2Max30') }}",
                    maxlength: "{{ trans('messages.min2Max30') }}",
                    remote: "{{ trans('messages.name_is_already_exists') }}",
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
        
        @if (isset($meta))
            $("#url").rules('remove', 'required');
        @endif


        $('#btnSubmit').on('click', function(e) {
            $("#metaForm").valid();
            removeTabError();


        });

        $('.Inputimg').on('change', function (e) {
        const file = e.target.files[0];
        const url = window.URL.createObjectURL(file);
        const image_preview = $(this).parent().parent().find('.image-preview');
        image_preview.attr('src', url).show();
      });

    </script>
@endif
