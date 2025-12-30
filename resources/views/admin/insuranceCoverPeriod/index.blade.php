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
            {{ trans('messages.insurance_cover_period') }}
        </h1>
        <ol class="breadcrumb ms-auto mb-0">
            <li><a href="{{ Route('admin.dashboard') }}"><em class="fa fa-dashboard"></em> {{ trans('messages.dashboard') }}</a></li>
            <li class="active"> {{ trans('messages.insurance_cover_period') }}</li>
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
                            {{ html()->text('name', isset($searchVariable['name']) ? $searchVariable['name'] : '')->attributes(['class' => 'form-control', 'placeholder' => trans('messages.name')]) }}
                        </div>

                        <div class="form-group mb-0 w-20p">
                            {{ html()->select('status',['1' => trans('messages.active'), '0' => trans('messages.inActive')],
                            ((isset($searchVariable['status'])) ? $searchVariable['status'] : ''))
                            ->attributes(['class' => 'form-control form-control-dropdown'])->placeholder(trans("messages.status")) }}
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

        <x-admin.table-list-structure :result="$result">
            <x-slot:table>
                <table class="@if (!$result->isEmpty()) table table-striped table-hover @endif">
                    <caption>{{ trans('messages.insuranceCoverTypes') }} {{ trans('messages.list') }}</caption>
                    @if (!$result->isEmpty())
                        <thead>
                            <th scope="col">
                                {{ trans('messages.sNo') }}
                            </th>
                            <th scope="col">
                                {{ link_to_route(
                                    $listRoute,
                                    trans('messages.coverPeriod'),
                                    [
                                        'sortBy' => 'name',
                                        'order' => $sortBy == 'name' && $order == 'desc' ? 'asc' : 'desc',
                                    ],
                                    $query_string,
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
                            <th scope="col" class="d-none">
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
                                        {{  $record->name }}
                                    </td>


                                    <td>
                                        {{ date(Config::get('constants.ADMIN_LISTING_DATE_FORMAT'), strtotime($record->created_at)) }}
                                    </td>
                                    <td>
                                        {{ date(Config::get('constants.ADMIN_LISTING_DATE_FORMAT'), strtotime($record->updated_at)) }}
                                    </td>
                                    <td>
                                        @can('INSURANCE_COVER_PERIOD_STATUS')
                                            @if ($record->status == 1)
                                            <div class="form-check form-switch pl-0">
                                                <input class="form-check-input status_any_item" data-onlabel="On" checked type="checkbox" role="switch"  data-id="{{$record->id}}"  status='0' >
                                                <span class="on">{{ trans('messages.active') }}</span><span class="off">{{ trans('messages.inActive') }}</span>
                                            </div>
                                            @else
                                            <div class="form-check form-switch pl-0">
                                                <input class="form-check-input status_any_item"  type="checkbox" role="switch"  data-id="{{$record->id}}"  status='1' >
                                                <span class="on">{{ trans('messages.active') }}</span><span class="off">{{ trans('messages.inActive') }}</span>
                                            </div>
                                            @endif
                                        @endcan
                                    </td>
                                    <td>
                                        @can('INSURANCE_COVER_PERIOD_VIEW')
                                        <a title="{{ trans('View') }}" href="{{ route('admin.insuranceCoverPeriod.view', $record->id) }}"
                                            class="btn btn-primary d-none">
                                            <em class="fa fa-eye"></em>
                                        </a>
                                    @endcan
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center noRecord"><strong>
                                        {{ trans('messages.noRecordFound') }}</strong></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </x-slot:table>
        </x-admin.table-list-structure>

    </div>
@stop
