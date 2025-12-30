<div id="page-inner">
    <style>
        .uploadeFileWrap {
            position: relative;
            width: 100%;
            height: 164px;
            padding: 20px;
            background: transparent !important;
            border: 2px dashed #acacac;
            text-align: center;
            font-size: 12px;
        }

        .uploadeFileWrap input {
            position: relative;
            z-index: 2;
        }

        .uploadeFileCaption {
            position: absolute;
            left: 0;
            right: 0;
            z-index: 1;
        }

        .uploadeFileCaption>* {
            display: block;
        }

        .asSelectBtn {
            border: 1px solid #ddd;
            padding: 8px 15px;
            display: inline-block;
            margin: 10px 0;
            font-size: 13px;
            font-weight: normal;
        }
    </style>


    <link rel="stylesheet" href="https://blueimp.github.io/Gallery/css/blueimp-gallery.min.css" />
    <!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
    <link rel="stylesheet" href="@php echo url('/assets/css/fileUpload'); @endphp/jquery.fileupload.css" />
    <link rel="stylesheet" href="@php echo url('/assets/css/fileUpload'); @endphp/jquery.fileupload-ui.css" />
    <!-- CSS adjustments for browsers with JavaScript disabled -->
    <noscript>
        <link rel="stylesheet" href="@php echo url('/assets/css/fileUpload'); @endphp/jquery.fileupload-noscript.css" />
    </noscript>
    <noscript>
        <link rel="stylesheet"
            href="@php echo url('/assets/css/fileUpload'); @endphp/jquery.fileupload-ui-noscript.css" />
    </noscript>


    <!-- The file upload form used as target for the file upload widget -->
    <form id="fileupload" action="" method="POST" enctype="multipart/form-data">

        <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
        <div class="row fileupload-buttonbar">
            <div class="col-lg-12 mb-3">
                <!-- The fileinput-button span is used to style the file input field as button -->
                <div class="btn btn-success fileinput-button uploadeFileWrap">
                    <input type="file" name="files[]" multiple />

                    <div class="uploadeFileCaption">
                        <strong> Drop files to upload </strong>
                        <span style="margin-top: 10px;">Or</span>
                        <span class="asSelectBtn">Select File</span>
                        <span style="font-weight:normal;"> Maximum upload file size: 150 MB.</span>
                    </div>

                </div>
                <button type="submit" class="btn btn-primary start d-none">
                    <em class="glyphicon glyphicon-upload"></em>
                    <span>Start upload</span>
                </button>
                <button type="reset" class="btn btn-warning cancel d-none">
                    <em class="glyphicon glyphicon-ban-circle"></em>
                    <span>Cancel upload</span>
                </button>
                <button type="button" class="btn btn-danger delete d-none">
                    <em class="glyphicon glyphicon-trash"></em>
                    <span>Delete selected</span>
                </button>
                <input type="checkbox" class="toggle d-none" />
                <!-- The global file processing state -->
                <span class="fileupload-process"></span>
            </div>
            <!-- The global progress state -->
            <div class="col-lg-5 fileupload-progress d-none">
                <!-- The global progress bar -->
                <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar progress-bar-success" style="width: 0%;"></div>
                </div>
                <!-- The extended global progress state -->
                <div class="progress-extended">&nbsp;</div>
            </div>
        </div>
        <!-- The table listing the files available for upload/download -->
        <table class="table table-striped">
            <tbody class="files"></tbody>
        </table>
    </form>

    <!-- The blueimp Gallery widget -->
    <div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" aria-label="image gallery"
        aria-modal="true" role="dialog" data-filter=":even">
        <div class="slides" aria-live="polite"></div>
        <h3 class="title"></h3>
        <a class="prev" aria-controls="blueimp-gallery" aria-label="previous slide" aria-keyshortcuts="ArrowLeft"></a>
        <a class="next" aria-controls="blueimp-gallery" aria-label="next slide" aria-keyshortcuts="ArrowRight"></a>
        <a class="close" aria-controls="blueimp-gallery" aria-label="close" aria-keyshortcuts="Escape"></a>
        <a class="play-pause" aria-controls="blueimp-gallery" aria-label="play slideshow" aria-keyshortcuts="Space"
            aria-pressed="false" role="button"></a>
        <ol class="indicator"></ol>
    </div>
    <!-- The template to display files available for upload -->
    <script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fadex{%=o.options.loadImageFileTypes.test(file.type)?' image':''%}">
        <td>
            <span class="preview"></span>
        </td>
        <td>
            <p class="name">{%=file.name%}</p>
            <strong class="error text-danger"></strong>
        </td>
        <td>
            <p class="size">Processing...</p>
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
        </td>
        <td>
            {% if (!o.options.autoUpload && o.options.edit && o.options.loadImageFileTypes.test(file.type)) { %}
              <button class="btn btn-success edit" data-index="{%=i%}" disabled>
                  <i class="glyphicon glyphicon-edit"></i>
                  <span>Edit</span>
              </button>
            {% } %}
            {% if (!i && !o.options.autoUpload) { %}
                <button class="btn btn-primary start" disabled>
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Start</span>
                </button>
            {% } %}
            {% if (!i) { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>
    <!-- The template to display files available for download -->
    <script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fadex{%=file.thumbnailUrl?' image':''%}">
        <td>
            <span class="preview">
                {% if (file.thumbnailUrl) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
                {% } %}
            </span>
        </td>
        <td>
            <p class="name">
                {% if (file.url) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                {% } else { %}
                    <span>{%=file.name%}</span>
                {% } %}
            </p>
            {% if (file.error) { %}
                <div><span class="label label-danger">Error</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td>
            <span class="size">{%=o.formatFileSize(file.size)%}</span>
        </td>

    </tr>
{% } %}
</script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"
        integrity="sha384-nvAa0+6Qg9clwYCGGPpDQLVpLNn0fRaROjHqs13t4Ggj3Ez50XnGQqc/r8MhnRDZ" crossorigin="anonymous">
    </script>
    <!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
    <script src="@php echo url('/assets/js/fileUpload'); @endphp/vendor/jquery.ui.widget.js"></script>
    <!-- The Templates plugin is included to render the upload/download listings -->
    <script src="https://blueimp.github.io/JavaScript-Templates/js/tmpl.min.js"></script>
    <!-- The Load Image plugin is included for the preview images and image resizing functionality -->
    <script src="https://blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js"></script>
    <!-- The Canvas to Blob plugin is included for image resizing functionality -->
    <script src="https://blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
    <!-- blueimp Gallery script -->
    <script src="https://blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js"></script>
    <!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
    <script src="@php echo url('/assets/js/fileUpload'); @endphp/jquery.iframe-transport.js"></script>
    <!-- The basic File Upload plugin -->
    <script src="@php echo url('/assets/js/fileUpload'); @endphp/jquery.fileupload.js"></script>
    <!-- The File Upload processing plugin -->
    <script src="@php echo url('/assets/js/fileUpload'); @endphp/jquery.fileupload-process.js"></script>
    <!-- The File Upload image preview & resize plugin -->
    <script src="@php echo url('/assets/js/fileUpload'); @endphp/jquery.fileupload-image.js"></script>
    <!-- The File Upload audio preview plugin -->
    <script src="@php echo url('/assets/js/fileUpload'); @endphp/jquery.fileupload-audio.js"></script>
    <!-- The File Upload video preview plugin -->
    <script src="@php echo url('/assets/js/fileUpload'); @endphp/jquery.fileupload-video.js"></script>
    <!-- The File Upload validation plugin -->
    <script src="@php echo url('/assets/js/fileUpload'); @endphp/jquery.fileupload-validate.js"></script>
    <!-- The File Upload user interface plugin -->
    <script src="@php echo url('/assets/js/fileUpload'); @endphp/jquery.fileupload-ui.js"></script>
    <!-- The main application script -->
    <script src="@php echo url('/assets/js/fileUpload'); @endphp/demo.js"></script>
    <!-- The XDomainRequest Transport is included for cross-domain file deletion for IE 8 and IE 9 -->
    <!--[if (gte IE 8)&(lt IE 10)]>
                            <script src="js/cors/jquery.xdr-transport.js"></script>
                          <![endif]-->

    <!--  -->
    {{-- end --}}

    <div class="panel panel-default">
        <div class="panel-body form_mobile">
            {{ html()->modelForm(null, 'GET')->route($listRoute)->attributes([
                    'class' => 'form-inline',
                    'role' => 'form',
                    'autocomplete' => 'off',
                    'onSubmit' => 'return checkDate();',
                ])->open() }}
            {{ html()->hidden('display') }}
            <div class="form_row">
                <div class="form_fields d-flex mb-0 gap-2">
                    <div class="form-group mb-0">
                        {{ html()->text('name', isset($searchVariable['name']) ? $searchVariable['name'] : '')->attributes(['class' => 'form-control', 'placeholder' => trans('messages.name')]) }}
                    </div>

                    <div class="form-group mb-0 calendarIcon">
                        {{ html()->text('from', isset($searchVariable['from']) ? $searchVariable['from'] : '')->attributes([
                                'class' => 'form-control datepicker',
                                'onkeydown' => 'return false;',
                                'placeholder' => trans('messages.fromDate'),
                            ]) }}
                    </div>
                    <div class="form-group mb-0 calendarIcon">
                        {{ html()->text('to', isset($searchVariable['to']) ? $searchVariable['to'] : '')->attributes([
                                'class' => 'form-control datepicker',
                                'onkeydown' => 'return false;',
                                'placeholder' => trans('messages.toDate'),
                            ]) }}
                    </div>
                </div>

                <div class="form-action ">
                    <button type="submit" class="btn theme_btn bg_theme btn-sm btnIcon"
                        title="{{ trans('messages.search') }}"><em class="fa-solid fa-magnifying-glass"></em> </button>
                    <a href="{{ route($listRoute) }}" class="btn btn-sm border_btn btnIcon"
                        title="{{ trans('messages.reset') }}"><em class='fa fa-refresh '></em> </a>



                </div>
            </div>
            {{ html()->closeModelForm() }}
        </div>
    </div>
    <div class="row">
        <div class="container">
            <div class="row" id="media-data">
                <!-- Media content will be loaded here -->
            </div>
            <div class="ajax-load text-center" style="display:none">
                <p><img src="https://via.placeholder.com/50" alt="Loading..."></p>
            </div>
        </div>
    </div>


    {{-- <x-admin.table-list-structure :result="$result">
        <x-slot:table>
            <table class="@if (!$result->isEmpty()) table table-striped table-hover @endif">
                <caption>{{ trans('messages.subAdminActivityLogs') }} {{ trans('messages.list') }}</caption>
                @if (!$result->isEmpty())
                    <thead>
                        <th scope="col">
                            {{ trans('messages.sNo') }}
                        </th>
                        <th scope="col" class="w-10p">{{ trans('messages.image') }}</th>
                        <th scope="col">
                            {{ link_to_route(
                                $listRoute,
                                trans('messages.name'),
                                [
                                    'sortBy' => 'name',
                                    'order' => $sortBy == 'name' && $order == 'desc' ? 'asc' : 'desc',
                                ],
                                $queryString,
                            ) }}
                            @php getSortIcon($sortBy,$order,'name') @endphp
                        </th>


                        <th scope="col">
                            {{ link_to_route(
                                $listRoute,
                                trans('messages.createdOn'),
                                [
                                    'sortBy' => 'created_at',
                                    'order' => $sortBy == 'created_at' && $order == 'desc' ? 'asc' : 'desc',
                                ],
                                $queryString,
                            ) }}

                            @php getSortIcon($sortBy,$order,'created_at') @endphp
                        </th>

                        <th scope="col">
                            {{ trans('messages.action') }}
                        </th>
                    </thead>
                @endif
                <tbody>
                    @php
                        $i = 1;
                        if (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) {
                            $page = $_REQUEST['page'];
                            $limit = GeneralService::getSettings('pageLimit');
                            $i = ($page - 1) * $limit + 1;
                        }
                    @endphp

                    @if (!$result->isEmpty())
                        @foreach ($result as $key => $record)
                            <tr>
                                <td>{{ $i }}
                                    @php
                                        $i++;
                                    @endphp
                                </td>
                                <td>
                                    <img class="border border-1 mediaImage" alt=""
                                        src="{{ $record->thumbImage }}" width="50">
                                </td>
                                <td>
                                    <span class="ImageName">
                                        {{ $record->name }}</span><button class="btn btn-primary d-none EditMediaBtn ml-3"  data-id='{{ $record->id }}'
                                            data-url='{{ route('admin.mediamanager.update.media.name', $record->id) }}' data-img='{{Storage::url(config('constants.MEDIA_FOLDER').'/'.$record->image)}}'><em class="fa fa-edit"></em></button>
                                </td>
                                <td>
                                    {{ date(Config::get('constants.ADMIN_LISTING_DATE_FORMAT'), strtotime($record->created_at)) }}

                                </td>
                                <td>
                                    @can('SUB_ADMIN_DELETE')
                                        <a class="btn btn-primary delete_any_item"
                                            data-id="{{ route('admin.mediamanager.delete', $record->id) }}"
                                            title="{{ trans('Delete') }}" href="javascript::void(0);">
                                            <em class="fa fa-trash"></em>
                                        </a>
                                    @endcan


                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="text-center noRecord"><strong>
                                    {{ trans('messages.noRecordFound') }}</strong></td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </x-slot:table>
    </x-admin.table-list-structure> --}}

    @include('admin.mediamanager.modal')
    <script>
        const getImageUrl = "{{ route('admin.gallery.media.images') }}";
        const validateMediaNameUrl = "{{ route('admin.mediamanager.validateName') }}";
        const minlength_ = "{{ trans('messages.min2Max30') }}";
        const maxlength = "{{ trans('messages.min2Max30') }}";
        const remote_ = "{{ trans('messages.name_is_already_exists') }}";
        const getImageBaseUrl = "{{route('admin.mediamanager.getImages')}}";
        let selected_media_image_id = '';
    </script>
    <script src="{{ asset('assets/js/custom-user-define-fun.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.validate.min.js?id=8') }}"></script>
    <script src="{{ asset('assets/js/jquery.validate_custom.min.js?id=8') }}"></script>
    <script src="{{ asset('assets/js/media_manager/media-manager.js') }}"></script>
    @if (env('ENABLE_CLIENT_VALIDATION'))
        <script src="{{ asset('assets/js/media_manager/media-form-validator.js') }}"></script>
    @endif
    <script src="{{ asset('assets/js/media_manager/form-action.js') }}"></script>

</div>
