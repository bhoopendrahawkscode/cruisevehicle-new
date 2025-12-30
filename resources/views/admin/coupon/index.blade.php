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
            {{ trans('messages.coupon') }} {{ trans('messages.list') }}


        </h1>
        <ol class="breadcrumb ms-auto mb-0">
            <li><a href="{{ Route('admin.dashboard') }}"><em class="fa fa-dashboard"></em> {{ trans('messages.dashboard') }}
                </a></li>
            <li class="active">{{ trans('messages.coupon') }} {{ trans('messages.list') }}</li>
        </ol>
    </div>
    <div id="page-inner">
        <div class="panel panel-default">
            <div class="panel-body form_mobile">
                {{ html()->modelForm(null, 'GET')->route('admin.coupons.list')->attributes([
                        'class' => 'form-inline',
                        'role' => 'form',
                        'autocomplete' => 'off',
                        'onSubmit' => 'return checkDate();',
                    ])->open() }}
                {{ html()->hidden('display') }}
                <div class="form_row">
                    <div class="form_fields d-flex mb-0 gap-2">
                        <div class="form-group mb-0">
                            {{ html()->text('name', isset($searchVariable['name']) ? $searchVariable['name'] : '')->attributes(['class' => 'form-control', 
                            'placeholder' => trans('messages.coupon_list_search')]) }}
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
                        <a href="{{ route('admin.coupons.list') }}" class="btn btn-sm border_btn btnIcon"
                            title="{{ trans('messages.reset') }}"><em class='fa fa-refresh '></em> </a>
                            @can('SUB_ADMIN_ADD')
                            <a href="{{ route('admin.coupons.add') }}" class="btn theme_btn bg_theme btn-sm py-2  btnIcon"
                                style="margin:0;" title="{{ trans('Add New User') }}"> <em class='fa fa-add '></em></a>
                        @endcan
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
                                    'admin.coupons.list',
                                    trans('messages.name'),
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
                                    'admin.coupons.list',
                                    trans('messages.discount'),
                                    [
                                        'sortBy' => 'discount',
                                        'order' => $sortBy == 'discount' && $order == 'desc' ? 'asc' : 'desc',
                                    ],
                                    $query_string,
                                ) }}
                                @php getSortIcon($sortBy,$order,'discount') @endphp
                            </th>
                            <th scope="col">
                                {{ link_to_route(
                                    'admin.coupons.list',
                                    trans('messages.code'),
                                    [
                                        'sortBy' => 'code',
                                        'order' => $sortBy == 'code' && $order == 'desc' ? 'asc' : 'desc',
                                    ],
                                    $query_string,
                                ) }}
                                @php getSortIcon($sortBy,$order,'code') @endphp
                            </th>
                            <th scope="col">
                                {{ link_to_route(
                                    'admin.coupons.list',
                                    trans('messages.offer_type'),
                                    [
                                        'sortBy' => 'offer_type',
                                        'order' => $sortBy == 'offer_type' && $order == 'desc' ? 'asc' : 'desc',
                                    ],
                                    $query_string,
                                ) }}
                                @php getSortIcon($sortBy,$order,'offer_type') @endphp
                            </th>
                            <th scope="col">
                                {{ link_to_route(
                                    'admin.coupons.list',
                                    trans('messages.maximum_uses'),
                                    [
                                        'sortBy' => 'maximum_uses',
                                        'order' => $sortBy == 'maximum_uses' && $order == 'desc' ? 'asc' : 'desc',
                                    ],
                                    $query_string,
                                ) }}
                                @php getSortIcon($sortBy,$order,'maximum_uses') @endphp
                            </th>

                            <th scope="col">
                                {{ link_to_route(
                                    'admin.coupons.list',
                                    trans('messages.single_user_use_limit'),
                                    [
                                        'sortBy' => 'single_user_use_limit',
                                        'order' => $sortBy == 'single_user_use_limit' && $order == 'desc' ? 'asc' : 'desc',
                                    ],
                                    $query_string,
                                ) }}
                                @php getSortIcon($sortBy,$order,'single_user_use_limit') @endphp
                            </th>
                            <th scope="col">
                                {{ link_to_route(
                                    'admin.coupons.list',
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
                                    'admin.coupons.list',
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
                                        {{ $record->name }}
                                    </td>

                                    <td>
                                        {{ $record->discount }}
                                    </td>
                                    <td>
                                        {{ $record->code }}
                                    </td>
                                    <td>
                                        {{ $record->offer_type }}
                                    </td>
                                    <td>
                                        {{ $record->maximum_uses }}
                                    </td>
                                    <td>
                                        {{ $record->single_user_use_limit }}
                                    </td>
                                    <td>
                                        @can('SUB_ADMIN_CHANGE_STATUS')
                                            @if ($record->getRawOriginal('status'))
                                                <div class="form-check form-switch pl-0">
                                                    <input class="form-check-input status_any_item" data-onlabel="On" checked
                                                        type="checkbox" role="switch" data-id="{{ $record->id }}"
                                                        status='0'>
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
                                            @if ($record->getRawOriginal('status'))
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
                                        {{ date(Config::get('constants.ADMIN_LISTING_DATE_FORMAT'), strtotime($record->created_at)) }}

                                    </td>

                                    <td>
                                        @can('SUB_ADMIN_EDIT')
                                            <a title="{{ trans('Edit') }}" href="{{route('admin.coupons.edit',$record->id)}}" class="btn btn-primary">
                                                <em class="fa fa-pencil"></em>
                                            </a>
                                        @endcan
                                        @can('SUB_ADMIN_DELETE')
                                            <a class="btn btn-primary delete_any_item" data-id="{{route('admin.coupon.delete',$record->id)}}"
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
        </x-admin.table-list-structure>

        <script src="{{ asset('assets/js/custom-user-define-fun.js') }}"></script>
        <script>
            $(document).ready(function() {
                DeleteConfirmation();
            });
        </script>
    </div>
@stop
