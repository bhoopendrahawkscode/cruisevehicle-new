
@php
use App\Services\CommonService;
@endphp
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
                            {{ $errors->has('title[' . $languageRow->id . ']') ? 'has-error': '' }}">
                        {{ html()->hidden('id[' . $languageRow->id . ']') }}
                        <label for=" {{ trans('messages.title') }} {{ '(' . $languageRow->name . ')' }}">
                            {{ trans('messages.title') }} {{ '(' . $languageRow->name . ')' }}
                            <span class="red_lab"> *</span>
                        </label>
                        {{ html()->text('title[' . $languageRow->id . ']')
                            ->attributes(['class' => 'form-control languages_name formValidate',
                            'placeholder' => trans("messages.title")]) }}
                        @if ($errors->has('title.' . $languageRow->id))
                        <span class="invalid-feedback " role="alert">
                            <strong>{{ $errors->first('title.' . $languageRow->id) }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-6">
                    <div class="clearValidation form-group
                             {{ $errors->has('content[' . $languageRow->id . ']') ? 'has-error': '' }}">
                        <label for=" {{ trans('messages.content') }} {{ '(' . $languageRow->name . ')' }}">
                            {{ trans('messages.content') }} {{ '(' . $languageRow->name . ')' }}
                            <span class="red_lab"> *</span>
                        </label>
                        {{ html()->textarea('content[' . $languageRow->id . ']')
                            ->attributes(['class' => 'form-control languages_content formValidate','id'=>'ckEditor'.$languageRow->id,
                            'placeholder' => trans("messages.content")]) }}
                        @if ($errors->has('content.' . $languageRow->id))
                        <span class="invalid-feedback " role="alert">
                            <strong>{{ $errors->first('content.' . $languageRow->id) }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-sm-6">
                    <div class="clearValidation tag_inner form-group
                        {{ $errors->has('tags') ? 'has-error': '' }}">

                        <label for=" {{ trans('messages.name') }} ">
                            {{ trans('messages.tags') }}
                            <span class="red_lab"> *</span>
                        </label>
                        {{ html()->text('tags[' . $languageRow->id . ']')
                        ->attributes(['class' => 'form-control languages_tag formValidate',
                        'placeholder' => trans("messages.tags_enter")]) }}
                        <ul class="suggestions"></ul>

                        @if ($errors->has('tags'))
                        <span class="invalid-feedback " role="alert">
                            <strong>{{ $errors->first('tags') }}</strong>
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
    <label for=" {{ trans('messages.categories') }} ">
        {{ trans('messages.categories') }}
        <span class="red_lab"> *</span>
    </label>
    <div class="col-12 col-sm-6 htmlSearch" style="height:500px;overflow:auto;border:1px solid #5f5757; " id="htmlSearch">
       <div class="panel panel-default ">
            <div class="panel-body form_mobile">
                <div class="form_row">
                    <div class="form_fields d-flex mb-0 gap-2">
                        <div class="form-group mb-0">
                            {{ html()->text('name',((isset($searchVariable['name'])) ?
                            $searchVariable['name'] : ''))
                            ->attributes(['class' => 'form-control',
                            'placeholder' => trans("messages.searchBy")." ".trans('messages.name')]) }}
                        </div>
                    </div>
                    <div class="form-action ">
                        <button type="button" title="{{ trans('messages.search') }}" onclick="highlight(document.getElementById('name').value);" class="btn theme_btn bg_theme btn-sm">
                            <em class="fa-solid fa-magnifying-glass"></em>
                        </button>
                        <a href="javascript:void(0);" title="{{ trans("messages.reset") }}" onClick="highlightAndReload()" class="btn btn-sm border_btn">
                            <em class='fa fa-refresh '></em>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php $oldSelected = old('categories', []);
            if(empty($oldSelected) && !empty( $data['categories'])){
                $oldSelected = $data['categories'];
            }
        ?>
        @if(!$blogCategories->isEmpty())
            @foreach($blogCategories as $key => $record)
                @php
                $checked = '';
                if(in_array($record->id, $oldSelected)){
                    $checked = 'checked';
                }
                if(isset($treeMain[$record->id]['children']) &&
                !empty($treeMain[$record->id]['children'])){
                    echo '<ul class="myUL">
                        <li style="padding-left:0px;">
                            <input type="checkbox" name="categories[]" '.$checked.' value="'.$record['id'].'" /> <span style="padding-left:0px;" class="caret">'.$record['name'].'</span>';
                            echo CommonService::categoryMenuPosts($treeMain[$record->id]['children'],$oldSelected);
                            echo '</li>
                    </ul>';
                }else{
                    echo '<p class="mb-0 pt-1">'.$record->name.' <input type="checkbox" name="categories[]" '.$checked.' value="'.$record['id'].'" /> </p>';
                }
                @endphp
            @endforeach
        @endif
    </div>
</div>
<div class="row mt-5">
    <div class="col-12 col-sm-6">
        <div class="clearValidation form-group
            {{ $errors->has('tags') ? 'has-error': '' }}">

            <label for=" {{ trans('messages.author') }} ">
                {{ trans('messages.author') }}
                <span class="red_lab"> *</span>
            </label>
            {{ html()->select('author')->options($authors)
            ->attributes(['class' => 'form-control languages_author formValidate',
            'placeholder' => trans("messages.author")]) }}
            @if ($errors->has('author'))
            <span class="invalid-feedback " role="alert">
                <strong>{{ $errors->first('author') }}</strong>
            </span>
            @endif
        </div>
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
        <span class="imageHint" style="display: block">{{ trans("messages.$title.imageHint") }}</span>
        <?php if (isset($data['recordId'])) { ?>
            <label class="exist_image">{{ trans('messages.existingImage') }}
            </label>
            <div class="old_img">
                <img class="border border-1" alt="Image" class="border border-1" src="{{ $data['thumbImage'] }}" width="100">
            </div>
        <?php } ?>
    </div>
    <div class="col-lg-2  col-md-6">
        <label class='image-preview'>{{ trans('messages.imagePreview') }}</label>
        <img id="image-preview" class="image-preview" alt="{{ trans('messages.imagePreview') }}" class="img-fluid rounded-circle" width="150px">
    </div>
</div>
<script integrity="" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.js"></script>
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
                maxlength: 500,
                notNumber: true,
                messages: {
                    minlength: "{{ trans('messages.minGeneral') }}",
                    maxlength: "{{ trans('messages.max500') }}",
                    notNumber: "{{ trans('messages.notNumberMessage') }}"
                }
            })
        });
        $('.languages_tag ').each(function(e) {
            $(this).rules('add', {
                required: true,
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

        $('.languages_content').each(function(e) {
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
<script>
    $(document).ready(function() {


        $(document).click(function() {
             $('.notfound').hide();
        });

        $('.languages_tag').tagsinput();
        $('.bootstrap-tagsinput > input').on('input', function() {
            var keyword = $(this).val();
           // $('.languages_tag').tagsinput('remove', keyword);
            localStorage.setItem('keyword', keyword);


            var routeUrl = "{{ route('tags.suggest') }}";

            // Perform an AJAX request to fetch suggestions
            $.ajax({
                url: routeUrl,
                method: 'POST',
                dataType: "json",
                data: {
                    "keyword": keyword,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {

                    // Clear previous suggestions
                    $('.suggestions').show().empty();

                    // Check if data is an array
                    if (data.length > 0) {
                        // Iterate through the data and append each suggestion to the 'suggestions' element
                        data.forEach(function(suggestions) {
                            $('.suggestions').append('<li>' + suggestions + '</li>');
                        });
                    } else {
                        // Handle the case when data is not an array or is empty
                        $('.suggestions').append('<div class="notfound p-2">No suggestions found</div>');
                        //$('#suggestions').show().empty();
                    }

                }
            });
        });
        // Add an event listener to the suggestions list to handle click events
        $('.suggestions').on('click', 'li', function() {
            // Get the clicked suggestion's text
            var suggestionText = $(this).text();
            if (suggestionText !== "No suggestions found") {
                var searchTerm = localStorage.getItem('keyword');
                $('div.tab-content div.active > .languages_tag').tagsinput('remove', searchTerm);
                // Populate the search input field with the clicked suggestion
                $('div.tab-content div.active > .languages_tag').tagsinput('add', suggestionText);

            }

            // Clear the suggestions list
            $('.suggestions').empty();
        });
    });
</script>
<script>
    $(document).ready(function() {


        $('.languages_content').each(function() {
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

<style>
    ul.myUL {
        list-style-type: none;
    }

    .myUL {
        margin: 0;
        padding: 0;
    }

    ul.myUL li span,
    ul.myUL li {
        list-style: none;
        padding: 10px;
    }

    ul.myUL li a {
        color: #000
    }

    .caret {
        cursor: pointer;
        user-select: none;
    }

    .caret::before {
        content: "\25B6";
        color: black;
        display: inline-block;
        margin-right: 6px;
    }

    .caret-down::before {
        transform: rotate(90deg);
    }

    .nested {
        display: none;
    }

    .active {
        display: block;
    }

    .badge:empty {
        display: inline;
    }

    mark.highlight {
        color: black;
        padding: 5px;
        background: cyan;
    }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mark.js/8.11.1/mark.min.js" integrity="sha512-5CYOlHXGh6QpOFA/TeTylKLWfB3ftPsde7AnmhuitiTX4K5SqCLBeKro6sPS8ilsz1Q4NRx3v8Ko2IBiszzdww==" crossorigin="anonymous">
</script>


<script>
    function highlightAndReload() {
        highlight('');

        location.reload();
    }

    var toggler = document.getElementsByClassName("caret");
    var i;
    for (i = 0; i < toggler.length; i++) {
        toggler[i].parentElement.querySelector(".nested").classList.toggle("active");
        toggler[i].classList.add("caret-down");

        toggler[i].addEventListener("click", function() {
            this.parentElement.querySelector(".nested").classList.toggle("active");
            this.classList.toggle("caret-down");
        });
    }

    function highlight(searchTerm) {
        var ob = new Mark(document.querySelector(".htmlSearch"));
        ob.unmark();
        ob.mark(
            searchTerm, {
                className: 'highlight',
                separateWordSearch: true
            }
        );
        const highlightedElement = document.querySelector('.highlight');
        if (highlightedElement) {
            $('#htmlSearch').animate({
                scrollTop: ($('.highlight:visible:first').offset().top - 150)
            }, 1000);
        }
    }
</script>
