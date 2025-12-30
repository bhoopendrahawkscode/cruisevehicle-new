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
            {{ trans('messages.insurance_quote_confirmation') }}
        </h1>
        <ol class="breadcrumb ms-auto mb-0">
            <li><a href="{{ Route('admin.dashboard') }}"><em class="fa fa-dashboard"></em> {{ trans('messages.dashboard') }}
                </a></li>
            <li class="active"> {{ trans('messages.insurance_quote_confirmation') }}</li>
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
                            {{ html()->text('request_reference_number', isset($searchVariable['request_reference_number']) ? $searchVariable['request_reference_number'] : '')->attributes(['class' => 'form-control', 'placeholder' => trans('messages.requestReferenceNumber')]) }}
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
                                    $listRoute,
                                    trans('messages.requestReferenceNumber'),
                                    [
                                        'sortBy' => 'request_reference_number',
                                        'order' => $sortBy == 'request_reference_number' && $order == 'desc' ? 'asc' : 'desc',
                                    ],
                                    $query_string,
                                ) }}
                                @php getSortIcon($sortBy,$order,'request_reference_number') @endphp
                            </th>
                            <th scope="col">
                                {{ link_to_route(
                                    $listRoute,
                                    trans('messages.userName'),
                                    [
                                        'sortBy' => 'user_id',
                                        'order' => $sortBy == 'user_id' && $order == 'desc' ? 'asc' : 'desc',
                                    ],
                                    $query_string,
                                ) }}
                                @php getSortIcon($sortBy,$order,'user_id') @endphp
                            </th>

                            <th scope="col">
                                {{ link_to_route(
                                    $listRoute,
                                    trans('messages.premiumPayable'),
                                    [
                                        'sortBy' => 'premium_payable',
                                        'order' => $sortBy == 'premium_payable' && $order == 'desc' ? 'asc' : 'desc',
                                    ],
                                    $query_string,
                                ) }}
                                @php getSortIcon($sortBy,$order,'premium_payable') @endphp
                            </th>

                            <th scope="col">
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
                            <th scope="col">
                                {{ link_to_route(
                                    $listRoute,
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
                                {{ link_to_route(
                                    $listRoute,
                                    trans('messages.updatedOn'),
                                    [
                                        'sortBy' => 'updated_at',
                                        'order' => $sortBy == 'updated_at' && $order == 'desc' ? 'asc' : 'desc',
                                    ],
                                    $query_string,
                                ) }}
                                @php getSortIcon($sortBy,$order,'updated_at') @endphp
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

                                    <td>{{ $record->request_reference_number }}</td>
                                    <td>{{ $record->user_name }}</td>
                                    <td>{{ $record->premium_payable }}</td>

                                    <td>
                                        @switch($record->status)
                                            @case(0)
                                                {{ trans('messages.notAvailable') }}
                                                @break
                                            @case(1)
                                                {{ trans('messages.confirmed') }}
                                                @break
                                            @default
                                                {{ trans('messages.notAvailable') }}
                                        @endswitch
                                    </td>

                                    <td>
                                        {{ date(Config::get('constants.ADMIN_LISTING_DATE_FORMAT'), strtotime($record->created_at)) }}
                                    </td>
                                    <td>
                                        {{ date(Config::get('constants.ADMIN_LISTING_DATE_FORMAT'), strtotime($record->updated_at)) }}
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

    </div>
@stop
