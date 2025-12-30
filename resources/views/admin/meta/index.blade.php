@extends('admin.layouts.default_layout')
@section('content')
    <?php use App\Services\GeneralService; ?>
    <div class="header d-flex align-items-center">
        <h1 class="page-header">@lang('messages.meta') @lang('messages.list')</h1>
        <ol class="breadcrumb ms-auto mb-0">
            <li><a href="{{ route('admin.dashboard') }}">
                    <em class="fa fa-dashboard"></em>
                    {{ trans('messages.dashboard') }}</a></li>
            <li class="active">@lang('messages.meta') @lang('messages.list')</li>
        </ol>
    </div>
    <div id="page-inner">
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
                            {{ html()->text('name', isset($searchVariable['name']) ? $searchVariable['name'] : '')->attributes([
                                    'class' => 'form-control',
                                    'placeholder' => trans('messages.searchBy') . ' ' . trans('messages.name'),
                                ]) }}
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
                        <button title="{{ trans('messages.search') }}" type="submit"
                            class="btn theme_btn bg_theme btn-sm btnIcon"><em
                                class="fa-solid fa-magnifying-glass"></em></button>
                        <a title="{{ trans('messages.reset') }}" href="{{ Route($listRoute) }}"
                            class="btn btn-sm border_btn btnIcon">
                            <em class='fa fa-refresh '></em>
                        </a>
                        <a href="{{ route('meta.tag.add') }}" class="btn theme_btn bg_theme btn-sm py-2  btnIcon"
                            style="margin:0;" title="@lang('messages.add')"> <em class='fa fa-add '></em></a>
                    </div>
                </div>
                {{ html()->closeModelForm() }}
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="@if (!$result->isEmpty()) table table-striped table-hover @endif" id="sortable">
                        <caption>@lang('messages.meta') @lang('messages.list') </caption>
                        @if (!$result->isEmpty())
                            <thead>
                                <tr>
                                    <th scope="col" class="w-5p">
                                        {{ trans('messages.sNo') }}
                                    </th>
                                    <th scope="col" class="w-45p">
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
                                            trans('messages.route'),
                                            [
                                                'sortBy' => 'url',
                                                'order' => $sortBy == 'url' && $order == 'desc' ? 'asc' : 'desc',
                                            ],
                                            $query_string,
                                        ) }}
                                        @php getSortIcon($sortBy,$order,'url') @endphp
                                    </th>

                                    <th scope="col" class="w-20p">
                                        {{
                                            link_to_route(
                                                $listRoute,
                                                trans("messages.updatedOn"),
                                                array(
                                                    'sortBy' =>  $mainTable.'.updated_at',
                                                    'order' => ($sortBy ==  $mainTable.'.updated_at' && $order == 'desc') ? 'asc' : 'desc'
                                                ),
                                                $query_string
                                            )
                                        }}
                                        @php getSortIcon($sortBy,$order,$mainTable.'.updated_at') @endphp
                                    </th>

                                    <th scope="col" class="w-10p">
                                        {{ trans('messages.action') }}
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
                                        <td> {{ $i }}
                                            @php
                                                $i++;
                                            @endphp
                                        </td>
                                        <td class="breakAll">
                                            {{ $record->name }}
                                        </td>

                                        <td>
                                            {{ $record->url }}

                                        </td>
                                        <td>
                                            {{ date(Config::get('constants.ADMIN_LISTING_DATE_FORMAT'),strtotime($record->updated_at)) }}
            
                                        </td>
                                        <td>
                                            @can($editPermission)
                                                <a title="{{ trans('messages.edit') }}" href="{{route('meta.tag.edit',$record->meta_id)}}" class="btn btn-primary">
                                                    <em class="fa fa-pencil"></em>
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
@stop
