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
             {{ trans('messages.insuranceQuoteList') }}
        </h1>
        <ol class="breadcrumb ms-auto mb-0">
            <li><a href="{{ Route('admin.dashboard') }}"><em class="fa fa-dashboard"></em> {{ trans('messages.dashboard') }}
                </a></li>
            <li class="active"> {{ trans('messages.insuranceQuoteList') }}</li>
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
                            {{ html()->text('name', isset($searchVariable['name']) ? $searchVariable['name'] : '')->attributes(['class' => 'form-control', 'placeholder' => trans('messages.user_reference_number')]) }}
                        </div>

                        <div class="form-group mb-0 w-20p">
                            {{ html()->select('status',['1' => trans('messages.Premium Quoted'), '2' => trans('messages.Awaiting Reply'), '3' => trans('messages.Declined')],
                            ((isset($searchVariable['status'])) ? $searchVariable['status'] : ''))
                            ->attributes(['class' => 'form-control form-control-dropdown status_any_item'])->placeholder(trans("messages.status")) }}
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
                                    trans('messages.nameOfUser'),
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
                                    trans('messages.vehicleValueToBeInsured'),
                                    [
                                        'sortBy' => 'vehicle_value_to_be_insured',
                                        'order' => $sortBy == 'vehicle_value_to_be_insured' && $order == 'desc' ? 'asc' : 'desc',
                                    ],
                                    $query_string,
                                ) }}
                                @php getSortIcon($sortBy,$order,'vehicle_value_to_be_insured') @endphp
                            </th>

                            <th scope="col">
                                {{ link_to_route(
                                    $listRoute,
                                    trans('messages.premiumProposed'),
                                    [
                                        'sortBy' => 'premium_proposed',
                                        'order' => $sortBy == 'premium_proposed' && $order == 'desc' ? 'asc' : 'desc',
                                    ],
                                    $query_string,
                                ) }}
                                @php getSortIcon($sortBy,$order,'premium_proposed') @endphp
                            </th>

                            <th scope="col">
                                {{ link_to_route(
                                    $listRoute,
                                    trans('messages.comment'),
                                    [
                                        'sortBy' => 'comment',
                                        'order' => $sortBy == 'comment' && $order == 'desc' ? 'asc' : 'desc',
                                    ],
                                    $query_string,
                                ) }}
                                @php getSortIcon($sortBy,$order,'comment') @endphp
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

                                    <td>{{ $record->request_reference_number }}</td>
                                    <td>{{ $record->user_name }}</td>

                                    <td>{{ $record->vehicle_value_to_be_insured }}</td>
                                    <td>{{ $record->premium_proposed }}</td>
                                    <td>{{ Str::limit($record->comment, 20, '...') }}</td>

                                    <td>
                                        @switch($record->status)
                                            @case(1)
                                                {{ trans('messages.Premium Quoted') }}
                                                @break
                                            @case(2)
                                                {{ trans('messages.Awaiting Reply') }}
                                                @break
                                            @case(3)
                                                {{ trans('messages.Declined') }}
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
                                    <td>
                                        @can('INSURANCE_QUOTE_VIEW')
                                        <a title="{{ trans('View') }}" href="{{ route('admin.insuranceQuote.view', $record->id) }}"
                                            class="btn btn-primary">
                                            <em class="fa fa-eye"></em>
                                        </a>
                                    @endcan
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="10" class="text-center noRecord"><strong>
                                        {{ trans('messages.noRecordFound') }}</strong></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </x-slot:table>
        </x-admin.table-list-structure>

    </div>
@stop
