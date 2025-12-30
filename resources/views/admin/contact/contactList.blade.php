@extends('admin.layouts.default_layout')
@section('content')

<?php use App\Constants\Constant;
 use App\Services\GeneralService;
?>
<div class="header d-flex align-items-center">
    <h1 class="page-header">
        {{ trans('messages.help_support') }}
    </h1>
    <ol class="breadcrumb ms-auto mb-0">
        <li><a href="{{ Route('admin.dashboard') }}"><em class="fa fa-dashboard"></em> {{ trans('messages.dashboard') }}</a></li>
        <li class="active">{{ trans('messages.help_support') }}</li>
    </ol>
</div>
<div id="page-inner">

    <div class="panel panel-default">
        <div class="panel-body">
            {{ html()->modelForm(null, 'GET')->route('admin.contactList')
            ->attributes(['class'=>'form-inline','role'=>'form','autocomplete' => 'off','onSubmit'=>'return checkDate();'])->open() }}

                @csrf
                {{ html()->hidden('display') }}

            <div class="form_row">
                <div class="form_fields d-flex gap-2">
                    <div class="form-group mb-0 w-50p">
                        {{ html()->text('name',((isset($searchVariable['name'])) ?
						$searchVariable['name'] : ''))
                        ->attributes(['class' => 'form-control mw-100','autocomplete' => 'off',
                        'placeholder' => trans("messages.searchContactPlaceHolder")]) }}
                    </div>
                    <div class="form-group mb-0 calendarIcon">
                        {{ html()->text('from',((isset($searchVariable['from'])) ?
                        $searchVariable['from'] : ''))
                        ->attributes(['class' => 'form-control datepicker','onkeydown'=>'return false;',
                        'placeholder' => trans('messages.fromDate')]) }}
                    </div>
                    <div class="form-group mb-0 calendarIcon">
                        {{ html()->text('to',((isset($searchVariable['to'])) ?
                        $searchVariable['to'] : ''))
                        ->attributes(['class' => 'form-control datepicker','onkeydown'=>'return false;',
                        'placeholder' => trans('messages.toDate')]) }}
                    </div>
                </div>
                <div class="form-action">
                    <button type="submit" class="btn theme_btn bg_theme border_btn btn-sm btnIcon" title="{{ trans('messages.search') }}"><em class="fa-solid fa-magnifying-glass"></em> </button>
                    <a href="{{ Route('admin.contactList') }}" class="btn btn-sm border_btn btnIcon" title="{{ trans('messages.reset') }}"><em class='fa fa-refresh '></em> </a>
                    {{-- <a href="{{url('admin/export/Setting/xls')}}" class="btn btn-sm border_btn" title="{{ trans('messages.exportXls') }}">{{ trans('messages.exportXls') }}</a>
                    <a href="{{url('admin/export/Setting/csv')}}" class="btn btn-sm border_btn" title="{{ trans('messages.exportCsv') }}">{{ trans('messages.exportCsv') }}</a> --}}
                </div>
            </div>
            {{ html()->closeModelForm() }}
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="table-responsive">
                <table class="@if (!$result->isEmpty()) table table-striped table-hover @endif">
                    <caption>Contact List</caption>
                    @if (!$result->isEmpty())
                    <thead>
                        <tr>
                            <th scope="col">
                                S.No.
                            </th>
                            <th scope="col">
                                {{ link_to_route(
                                                'admin.contactList',
                                                trans('User Id'),
                                                [
                                                    'sortBy' => 'user_id',
                                                    'order' => $sortBy == 'user_id' && $order == 'desc' ? 'asc' : 'desc'
                                                ],
                                                $query_string,
                                            ) }}
                                @php getSortIcon($sortBy,$order,'user_id') @endphp
                            </th>
                            <th scope="col">
                                {{ link_to_route(
                                                'admin.contactList',
                                                trans('messages.contactName'),
                                                [
                                                    'sortBy' => 'name',
                                                    'order' => $sortBy == 'name' && $order == 'desc' ? 'asc' : 'desc'
                                                ],
                                                $query_string,
                                            ) }}
                                @php getSortIcon($sortBy,$order,'name') @endphp
                            </th>
                            <th scope="col">
                                {{ link_to_route(
                                                'admin.contactList',
                                                trans('messages.contactPhone'),
                                                [
                                                    'sortBy' => 'mobile_no',
                                                    'order' => $sortBy == 'mobile_no' && $order == 'desc' ? 'asc' : 'desc'
                                                ],
                                                $query_string,
                                            ) }}
                                @php getSortIcon($sortBy,$order,'mobile_no') @endphp
                            </th>

                            <th scope="col">
                                {{trans('messages.email')}}
                            </th>
                            <th scope="col">
                                {{trans('messages.subject')}}
                            </th>
                            <th scope="col">
                                {{trans('messages.ticketStatus')}}
                            </th>
                            <th scope="col">
                                {{trans('messages.status')}}
                            </th>
                            <th scope="col">
                                {{ link_to_route(
                                                'admin.listUsers',
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
                                {{trans('messages.action')}}
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
                                {{ ($record->user_id) }}
                            </td>
                            <td>
                                {{ ($record->name) }}
                            </td>
                            <td>
                               {{ !empty($record->mobile_no) ? $record->mobile_no : '-' }}
                            </td>
                            <td>
                                {{$record->email}}
                            </td>
                            <td>
                                {{ ($record->subject) }}
                            </td>
                            <td>
                                @if ($record->ticket_status == 0)
                                  <button class="btn btn-info">{{trans('messages.ticketNew')}}</button>
                                @elseif ($record->ticket_status == 1)
                                  <button class="btn btn-warning">{{trans('messages.ticketProgress')}}</button>
                                @else
                                <button class="btn btn-success">{{trans('messages.ticketClose')}}</button>
                                @endif
                            </td>
                            <td>
                                @if ($record->status == 0)
                                 <button class="btn btn-warning">{{trans('messages.notReplied')}}</button>
                                @else
                                <button class="btn btn-success">{{trans('messages.replied')}}</button>
                                @endif
                            </td>
                           <td>
                            {{ date(Config::get('constants.ADMIN_LISTING_DATE_FORMAT'), strtotime($record->created_at)) }}
                            </td>
                            @can('CONTACT_VIEW')
                            <td>
                                @if($record->ticket_status == 0 || $record->ticket_status == 1)
                                <a  title="{{ trans('messages.view') }}" href="{{URL::to('admin/contact-view/'.$record->id)}}" class="btn btn-warning">
                                    <em class="fa fa-reply"></em>
                                </a>
                                @else
                                <button class="btn btn-success">{{trans('messages.ticketClose')}}</button>
                                @endif
                            </td>
                            @endcan
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



@stop
