@extends('admin.layouts.default_layout')
@section('content')
    <?php use App\Services\GeneralService; ?>
    <div class="header d-flex align-items-center">
        <h1 class="page-header">
            {{ $title }} {{ trans('messages.list') }}
        </h1>
        <ol class="breadcrumb ms-auto mb-0">
            <li><a href="{{ Route('admin.dashboard') }}">
                    <em class="fa fa-dashboard"></em>
                    {{ trans('messages.dashboard') }}</a></li>
            <li class="active">{{ $title }} {{ trans('messages.list') }}</li>
        </ol>
    </div>
    <div id="page-inner">
        <div class="panel panel-default">
            <div class="panel-body form_mobile">
                {{ html()->modelForm(null, 'GET')->route($listRoute)->attributes([
                        'id' => $formId,
                        'class' => 'form-inline',
                        'role' => 'form',
                        'autocomplete' => 'off',
                        'onSubmit' => 'return checkDate();',
                    ])->open() }}
                {{ html()->hidden('display') }}
                <div class="form_row">
                    <div class="form_fields d-flex mb-0 gap-2">
                        <div class="form-group mb-0">
                            {{ html()->text('name', isset($searchVariable['name']) ? $searchVariable['name'] : '')->attributes([
                                    'class' => 'form-control',
                                    'placeholder' => trans('messages.searchBy') . ' ' . trans('messages.name'),
                                ]) }}
                        </div>
                        <div class="form-group mb-0 w-20p">
                            {{ html()->select(
                                    'status',
                                    ['1' => trans('messages.active'), '0' => trans('messages.inActive')],
                                    isset($searchVariable['status']) ? $searchVariable['status'] : '',
                                )->attributes(['class' => 'form-control form-control-dropdown'])->placeholder(trans('messages.status')) }}
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
                    <div class="form-action">
                        <button title="{{ trans('messages.search') }}" type="submit"
                            class="btn theme_btn bg_theme btn-sm btnIcon"><em
                                class="fa-solid fa-magnifying-glass"></em></button>
                        <a title="{{ trans('messages.reset') }}" href="{{ Route($listRoute) }}"
                            class="btn btn-sm border_btn btnIcon"><em class='fa fa-refresh '></em></a>
                        <a href="{{ url('admin/export/Video/xls') }}" class="btn btn-sm border_btn"
                            title="Export Xls">Export Xls</a>
                        <a href="{{ url('admin/export/Video/csv') }}" class="btn btn-sm border_btn"
                            title="Export Csv">Export Csv</a>
                        <div class="form-action_status">
                            @can($addPermission)
                                <a title="{{ trans('messages.addNew') }} {{ $title }} "
                                    href="{{ Route($addRoute) }}?token={{ time() }}"
                                    class="btn theme_btn bg_theme btn-sm py-2 btnIcon">
                                    <em class='fa fa-add '></em>
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
                {{ html()->closeModelForm() }}
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="@if (!$result->isEmpty()) table table-striped table-hover @endif" id="sortable">
                        <caption>{{ $title }} {{ trans('messages.list') }}</caption>

                        @if (!$result->isEmpty())
                            <thead>
                                <th scope="col" class="w-5p">
                                    {{ trans('messages.sNo') }}
                                </th>
                                <th scope="col" class="w-20p">
                                    {{ link_to_route(
                                        $listRoute,
                                        trans('messages.name'),
                                        [
                                            'sortBy' => 'name',
                                            'order' => $sortBy == 'name' && $order == 'desc' ? 'asc' : 'desc',
                                        ],
                                        $query_string,
                                    ) }}
                                    @php getSortIcon($sortBy,$order,'name') @endphp
                                </th>
                                <th scope="col" class="w-20p">
                                    {{ link_to_route(
                                        $listRoute,
                                        trans('messages.artist'),
                                        [
                                            'sortBy' => 'artist',
                                            'order' => $sortBy == 'artist' && $order == 'desc' ? 'asc' : 'desc',
                                        ],
                                        $query_string,
                                    ) }}
                                    @php getSortIcon($sortBy,$order,'artist') @endphp
                                </th>
                                <th scope="col" class="w-5p">{{ trans('messages.video') }}</th>
                                <th scope="col" class="w-10p">
                                    {{ trans('messages.duration') }}
                                </th>
                                <th scope="col" class="w-10p">
                                    {{ link_to_route(
                                        $listRoute,
                                        trans('messages.category'),
                                        [
                                            'sortBy' => 'category',
                                            'order' => $sortBy == 'category' && $order == 'desc' ? 'asc' : 'desc',
                                        ],
                                        $query_string,
                                    ) }}
                                    @php getSortIcon($sortBy,$order,'category') @endphp
                                </th>
                                <th scope="col" class="w-15p">
                                    {{ link_to_route(
                                        $listRoute,
                                        trans('messages.createdOn'),
                                        [
                                            'sortBy' => $mainTable . '.created_at',
                                            'order' => $sortBy == $mainTable . '.created_at' && $order == 'desc' ? 'asc' : 'desc',
                                        ],
                                        $query_string,
                                    ) }}
                                    @php getSortIcon($sortBy,$order,$mainTable.'.created_at') @endphp
                                </th>
                                <th scope="col" class="w-10p">
                                    {{ link_to_route(
                                        $listRoute,
                                        trans('messages.status'),
                                        [
                                            'sortBy' => 'status',
                                            'order' => $sortBy == 'status' && $order == 'desc' ? 'asc' : 'desc',
                                        ],
                                        $query_string,
                                    ) }}
                                    @php getSortIcon($sortBy,$order,'status') @endphp
                                </th>
                                <th scope="col" class="w-10p">
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
                                        <td> {{ $i }}
                                            @php
                                                $i++;
                                            @endphp
                                        </td>
                                        <td class="breakAll">
                                            {{ $record->name }}
                                        </td>
                                        <td class="breakAll">
                                            {{ $record->artist }}
                                        </td>
                                        <td>
                                            <a data-src="{{ $record->video }}" onclick="showVideo(this);"
                                                href="javascript:void(0);">{{ __('messages.view') }}</a>
                                        </td>
                                        <td>
                                            {{ $record->duration }}s
                                        </td>
                                        <td>
                                            {{ getVideoCategoryName($record->category) }}
                                        </td>
                                        <td>
                                            {{ $record->created }}
                                        </td>
                                        <td>
                                            @can($statusPermission)
                                                @if ($record->getRawOriginal('status') == 1)
                                                    <div class="form-check form-switch pl-0">
                                                        <input class="form-check-input status_any_item" data-onlabel="On"
                                                            checked type="checkbox" role="switch"
                                                            data-id="{{ $record->id }}" status='0'>
                                                        <span class="on">{{ trans('messages.active') }}</span><span
                                                            class="off">{{ trans('messages.inActive') }}</span>
                                                    </div>
                                                @else
                                                    <div class="form-check form-switch pl-0">
                                                        <input class="form-check-input status_any_item" type="checkbox"
                                                            role="switch" data-id="{{ $record->id }}" status='1'>
                                                        <span class="on">{{ trans('messages.active') }}</span><span
                                                            class="off">{{ trans('messages.inActive') }}</span>
                                                    </div>
                                                @endif
                                            @else
                                                @if ($record->getRawOriginal('status') == 1)
                                                    <div class="form-check form-switch pl-0">
                                                        <input disabled class="form-check-input " data-onlabel="On" checked
                                                            type="checkbox" role="switch" data-id="{{ $record->id }}"
                                                            status='0'>
                                                        <span class="on">{{ trans('messages.active') }}</span><span
                                                            class="off">{{ trans('messages.inActive') }}</span>
                                                    </div>
                                                @else
                                                    <div class="form-check form-switch pl-0">
                                                        <input disabled class="form-check-input " type="checkbox" role="switch"
                                                            data-id="{{ $record->id }}" status='1'>
                                                        <span class="on">{{ trans('messages.active') }}</span><span
                                                            class="off">{{ trans('messages.inActive') }}</span>
                                                    </div>
                                                @endif
                                            @endcan
                                        </td>
                                        <td>
                                            @can($editPermission)
                                                <a title="{{ trans('messages.edit') }}"
                                                    href="{{ URL::to('admin/' . $listUrl . '/edit/' . $record->id) }}?token=e_{{ $record->id }}"
                                                    class="btn btn-primary">
                                                    <em class="fa fa-pencil"></em>
                                                </a>
                                            @endcan
                                            @can($deletePermission)
                                                <a style='display:none' title="{{ trans('messages.delete') }}"
                                                    href="{{ URL::to('admin/' . $listUrl . '/delete/' . $record->id) }}"
                                                    class="btn btn-danger">
                                                    <em class="fa fa-trash"></em>
                                                </a>
                                            @endcan



                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-center noRecord"><strong>
                                            {{ trans('messages.noRecordFound') }} </strong></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="box-footer clearfix">
                        @include('pagination.default', ['paginator' => $result])
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showVideo(j) {
            let videoUrl = $(j).attr('data-src');
            videoUrl = S3_URL + videoUrl;
            const videoElement = document.createElement('video');
            videoElement.controls = true;
            videoElement.autoplay = true;
            const sourceMP4 = document.createElement('source');
            videoElement.width = 500;
            sourceMP4.src = videoUrl;
            videoElement.appendChild(sourceMP4);
            videoElement.onerror = function() {
                console.error('Error occurred while loading the video:', videoElement.error);
            };
            $('#video-container').html("");
            document.getElementById('video-container').appendChild(videoElement);
            $("#videoPreview").modal('show');
        }
        $(document).ready(function() {
            var myModal = document.getElementById('videoPreview');
            myModal.addEventListener('hidden.bs.modal', function() {
                $("#video-container").html("");
            });
        });
    </script>
    <div class="modal fade" id="videoPreview" tabindex="-1" role="dialog" aria-labelledby="videoPreviewTitle"
        aria-hidden="true">
        <div class="modal-dialog" role="document" style="max-width: 535px;width: 100%;">
            <div class="modal-content">
                <div class="modal-body" id="video-container">
                </div>
            </div>
        </div>
    </div>


@stop
