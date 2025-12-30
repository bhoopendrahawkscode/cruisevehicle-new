@extends('admin.layouts.default_layout')
<link integrity=""
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/css/bootstrap3/bootstrap-switch.min.css"
    rel="stylesheet">
@section('content')
    <?php use App\Services\GeneralService;
    use App\Constants\Constant;
    ?>




    <div class="header d-flex align-items-center">
        <h1 class="page-header">
            {{ trans('messages.subAdminActivityLogs') }} {{ trans('messages.list') }}


        </h1>
        <ol class="breadcrumb ms-auto mb-0">
            <li><a href="{{ Route('admin.dashboard') }}"><em class="fa fa-dashboard"></em> {{ trans('messages.dashboard') }}
                </a></li>
            <li class="active">{{ trans('messages.subAdminActivityLogs') }} {{ trans('messages.list') }}</li>
        </ol>
    </div>
    <div id="page-inner">
        <div class="panel panel-default">
            <div class="panel-body form_mobile">
                {{ html()->modelForm(null, 'GET')->route('admin.sub_admin.activity.logs.list')->attributes([
                        'class' => 'form-inline',
                        'role' => 'form',
                        'autocomplete' => 'off',
                        'onSubmit' => 'return checkDate();',
                    ])->open() }}
                {{ html()->hidden('display') }}
                <div class="form_row">
                    <div class="form_fields d-flex mb-0 gap-2">
                        <div class="form-group mb-0">
                            {{ html()->text('name', isset($searchVariable['name']) ? $searchVariable['name'] : '')->attributes(['class' => 'form-control', 'placeholder' => trans('messages.username')]) }}
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
                        <a href="{{ route('admin.sub_admin.activity.logs.list') }}" class="btn btn-sm border_btn btnIcon"
                            title="{{ trans('messages.reset') }}"><em class='fa fa-refresh '></em> </a>
                        <div class="form-action_status">
                        </div>
                    </div>
                </div>
                {{ html()->closeModelForm() }}
            </div>
        </div>

        <x-admin.table-list-structure :result="$result">
            <x-slot:table>
                <table class="@if (!$result->isEmpty()) table table-striped table-hover @endif">
                    <caption>{{ trans('messages.subAdminActivityLogs') }} {{ trans('messages.list') }}</caption>
                    @if (!$result->isEmpty())
                        <thead>
                            <th scope="col">
                                {{ trans('messages.sNo') }}
                            </th>
                            <th scope="col">
                                {{ link_to_route(
                                    'admin.sub_admin.activity.logs.list',
                                    trans('messages.username'),
                                    [
                                        'sortBy' => 'log_name',
                                        'order' => $sortBy == 'log_name' && $order == 'desc' ? 'asc' : 'desc',
                                    ],
                                    $query_string,
                                ) }}
                                @php getSortIcon($sortBy,$order,'log_name') @endphp
                            </th>
                            <th scope="col">
                                {{ link_to_route(
                                    'admin.sub_admin.activity.logs.list',
                                    trans('messages.section'),
                                    [
                                        'sortBy' => 'section_name',
                                        'order' => $sortBy == 'section_name' && $order == 'desc' ? 'asc' : 'desc',
                                    ],
                                    $query_string,
                                ) }}
                                @php getSortIcon($sortBy,$order,'section_name') @endphp
                            </th>

                            <th scope="col">
                                {{ link_to_route(
                                    'admin.sub_admin.activity.logs.list',
                                    trans('messages.event'),
                                    [
                                        'sortBy' => 'event',
                                        'order' => $sortBy == 'event' && $order == 'desc' ? 'asc' : 'desc',
                                    ],
                                    $query_string,
                                ) }}
                                @php getSortIcon($sortBy,$order,'id') @endphp
                            </th>

                            <th scope="col">
                                {{ link_to_route(
                                    'admin.sub_admin.activity.logs.list',
                                    trans('messages.createdOn'),
                                    [
                                        'sortBy' => 'created_at',
                                        'order' => $sortBy == 'created_at' && $order == 'desc' ? 'asc' : 'desc',
                                    ],
                                    $query_string,
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
                                        {{ $record->log_name }} ({{$record->causer->roles->first()->name}})
                                    </td>
                                    <td>
                                        {{ $record->section_name }}
                                    </td>
                                    <td>
                                        {{ $record->event }}
                                    </td>
                                    <td>
                                        {{ date(Config::get('constants.ADMIN_LISTING_DATE_FORMAT'), strtotime($record->created_at)) }}

                                    </td>

                                    <td>

                                        <a class="btn btn-primary delete_any_item"
                                            data-id="{{ route('admin.sub_admin.activity.delete', $record) }}"
                                            title="{{ trans('Delete') }}" href="javascript::void(0);">
                                            <em class="fa fa-trash"></em>
                                        </a>
                                        <a class="btn btn-warning"
                                            href="{{ route('admin.sub_admin.activity.view', $record) }}"><em
                                                class="fa fa-eye"></em></a>
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
        </x-admin.table-list-structure>

        <script src="{{ asset('assets/js/custom-user-define-fun.js') }}"></script>
        <script>
            $(document).ready(function() {
                DeleteConfirmation();
            });
        </script>
    </div>
@stop
