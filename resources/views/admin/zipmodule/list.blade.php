@extends('admin.layouts.default_layout')
@section('content')

<?php use App\Constants\Constant;
 use App\Services\GeneralService;
?>
<div class="header d-flex align-items-center">
    <h1 class="page-header">
        {{ trans('messages.zip') }} {{ trans('messages.list') }}
    </h1>
    <ol class="breadcrumb ms-auto mb-0">
        <li><a href="{{ Route('admin.dashboard') }}"><em class="fa fa-dashboard"></em> {{ trans('messages.dashboard') }}</a></li>
        <li class="active">{{ trans('messages.zip') }} {{ trans("messages.list") }}</li>
    </ol>
</div>

<div id="page-inner">
    <div class="panel panel-default">
        <div class="panel-body">
            {{ html()->modelForm(null, 'GET')->route('admin.zipModuleList')
            ->attributes(['class'=>'form-inline','role'=>'form','autocomplete' => 'off'])->open() }}
                @csrf
                {{ html()->hidden('display') }}

            <div class="form_row">
                <div class="form_fields d-flex gap-2">
                    <div class="form-group mb-0 w-100p">
                        {{ html()->text('zipFolderFileName',((isset($searchVariable['zipFolderFileName'])) ?
						$searchVariable['zipFolderFileName'] : ''))
                        ->attributes(['class' => 'form-control w-100p','autocomplete' => 'off',
                        'placeholder' => 'Folder Name, File Name']) }}
                    </div>

                    <div class="form-group mb-0 w-100p">
                        {{ html()->select('zipType',[''=>'Select Type','Folder' => 'Folder', 'File' => 'File'],
						((isset($searchVariable['zipType'])) ? $searchVariable['zipType'] : ''))
                        ->attributes(['class' => 'form-control form-control-dropdown']) }}
                    </div>
                    <div class="form-group mb-0 w-100p">
                        {{ html()->select('status',['1' => trans('messages.enable'), '0' => trans('messages.disable')],
						((isset($searchVariable['status'])) ? $searchVariable['status'] : ''))
                        ->attributes(['class' => 'form-control form-control-dropdown'])->placeholder(trans("messages.status")) }}
                    </div>
                </div>
                <div class="form-action">
                    <button type="submit" class="btn theme_btn bg_theme border_btn btn-sm btnIcon" title="{{ trans('messages.search') }}"><em class="fa-solid fa-magnifying-glass"></em> </button>
                    <a href="{{ Route('admin.zipModuleList') }}" class="btn btn-sm border_btn btnIcon" title="{{ trans('messages.reset') }}"><em class='fa fa-refresh '></em> </a>
                </div>
            </div>
            {{ html()->closeModelForm() }}
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-body">
            {{ html()->modelForm(null, 'POST')->route('admin.zip.create')->attributes(['id'=>'zipForm','class'=>'form-inline','role'=>'form','autocomplete' => 'off'])->open() }}
                @csrf
            <div class="form_row">
                <div class="form_fields d-flex gap-2">
                    <div class="form-group mb-0 w-100p">
                        {{ html()->select('moduleName[]',$zipModuleList,'')->attributes(['class' => 'form-control form-control-dropdown','multiple'=>'multiple','id'=>'example-multiselect']) }}
                    </div>
                </div>
                <div class="form-action">
                    <button type="submit" class="btn theme_btn bg_theme border_btn btn-sm" title="{{ trans('messages.exportZip') }}">{{ trans('messages.exportZip') }} </button>
                </div>
            </div>
            {{ html()->closeModelForm() }}
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="table-responsive">
                <table class="@if (!$result->isEmpty()) table table-striped table-hover @endif">
                    <caption>Zip Folder and File List</caption>
                    @if (!$result->isEmpty())
                    <thead>
                        <tr>
                            <th scope="col">
                                S.No.
                            </th>

                            <th scope="col">
                                {{ link_to_route(
                                                'admin.zipModuleList',
                                                trans('messages.zipFolderFileName'),
                                                [
                                                    'sortBy' => 'zipFolderFileName',
                                                    'order' => $sortBy == 'zipFolderFileName' && $order == 'desc' ? 'asc' : 'desc'
                                                ],
                                                $query_string,
                                            ) }}
                                @php getSortIcon($sortBy,$order,'zipFolderFileName') @endphp
                            </th>

                            <th scope="col">
                                {{ link_to_route(
                                                'admin.zipModuleList',
                                                trans('messages.zipType'),
                                                [
                                                    'sortBy' => 'zipType',
                                                    'order' => $sortBy == 'zipType' && $order == 'desc' ? 'asc' : 'desc'
                                                ],
                                                $query_string,
                                            ) }}
                                @php getSortIcon($sortBy,$order,'zipType') @endphp
                            </th>

                            <th scope="col">
                                {{ link_to_route(
                                                'admin.zipModuleList',
                                                trans('messages.created_at'),
                                                [
                                                    'sortBy' => 'created_at',
                                                    'order' => $sortBy == 'created_at' && $order == 'desc' ? 'asc' : 'desc'
                                                ],
                                                $query_string,
                                            ) }}

                                @php getSortIcon($sortBy,$order,'created_at') @endphp
                            </th>
                            <th scope="col">
                                {{ link_to_route(
                                                'admin.zipModuleList',
                                                'Zip Status',
                                                [
                                                    'sortBy' => 'status',
                                                    'order' => $sortBy == 'status' && $order == 'desc' ? 'asc' : 'desc'
                                                ],
                                                $query_string,
                                            ) }}
                                @php getSortIcon($sortBy,$order,'status') @endphp
                            </th>
                        </tr>

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
                                {{ ($record->zipPath) }}/{{ ($record->zipFolderFileName) }}
                            </td>
                            <td>
                                {{ ($record->zipType) }}
                            </td>
                            <td>
                                {{ date(Config::get('constants.ADMIN_LISTING_DATE_FORMAT'), strtotime($record->created_at)) }}
                            </td>
                            <td>
                                     @if ($record->status == 1)
                                        <div class="form-check form-switch pl-0">
                                            <input class="form-check-input status_any_item" data-onlabel="On" checked type="checkbox" role="switch"  data-id="{{$record->id}}"  status='0' >
                                            <span class="on">{{ trans('messages.enable') }}</span><span class="off">{{ trans('messages.enable') }}</span>
                                        </div>
                                        @else
                                        <div class="form-check form-switch pl-0">
                                            <input class="form-check-input status_any_item"  type="checkbox" role="switch"  data-id="{{$record->id}}"  status='1' >
                                            <span class="on">{{ trans('messages.disable') }}</span><span class="off">{{ trans('messages.disable') }}</span>
                                        </div>
                                    @endif
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="8" class="text-center noRecord"><strong> {{ trans('messages.noRecordFound') }}</strong></td>
                        </tr>
                        @endif
                    </tbody>
                </table>

                <!-- pagination start -->
                <div class="box-footer clearfix">

                    @include('pagination.default', ['paginator' => $result])
                </div>
                <!-- pagination end -->
            </div>
        </div>
    </div>
</div>

<link href="{{ asset('/assets/css/bootstrap_multiple_select.min.css') }}" rel="stylesheet" />
<!-- Bootstrap JS -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity=""></script>
<!-- Bootstrap-Multiselect CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-multiselect@0.9.15/dist/css/bootstrap-multiselect.css">
<!-- Bootstrap-Multiselect JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-multiselect@0.9.15/dist/js/bootstrap-multiselect.js"  integrity=""></script>

<script>
    $(document).ready(function() {
        $('#example-multiselect').multiselect({
            includeSelectAllOption: true,  // Adds an option to select/deselect all
            enableFiltering: false          // Enables filtering inside the dropdown
        });
    });
    </script>
    @stop
