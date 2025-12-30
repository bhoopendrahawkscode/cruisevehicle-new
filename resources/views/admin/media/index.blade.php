@extends('admin.layouts.default_layout')
@section('content')

<?php use App\Constants\Constant;
 use App\Services\GeneralService;
?>
<div class="header d-flex align-items-center">
    <h1 class="page-header">
        {{ trans('messages.user') }} {{ trans('messages.list') }}
    </h1>
    <ol class="breadcrumb ms-auto mb-0">
        <li><a href="{{ Route('admin.dashboard') }}"><em class="fa fa-dashboard"></em> {{ trans('messages.dashboard') }}</a></li>
        <li class="active">{{ trans('messages.user') }} {{ trans("messages.list") }}</li>
    </ol>
</div>
<div id="page-inner">

    <div class="panel panel-default">
        <div class="panel-body">
            {{ html()->modelForm(null, 'GET')->route('admin.listUsers')
            ->attributes(['class'=>'form-inline','role'=>'form','autocomplete' => 'off','onSubmit'=>'return checkDate();'])->open() }}

                @csrf
                {{ html()->hidden('display') }}

            <div class="form_row">
                <div class="form_fields d-flex gap-2">
                    <div class="form-group mb-0 w-25p">


                        {{ html()->text('name',((isset($searchVariable['name'])) ?
						$searchVariable['name'] : ''))
                        ->attributes(['class' => 'form-control mw-100','autocomplete' => 'off',
                        'placeholder' => trans("messages.searchUserPlaceHolder")]) }}

                    </div>

                    <div class="form-group mb-0 w-20p">
                        {{ html()->select('type',Constant::SUBSCRIPTION_CATEGORY,
						((isset($searchVariable['type'])) ? $searchVariable['type'] : ''))
                        ->attributes(['class' => 'form-control form-control-dropdown']) }}
                    </div>
                    <div class="form-group mb-0 w-20p">
                        {{ html()->select('status',['1' => trans('messages.active'), '0' => trans('messages.inActive')],
						((isset($searchVariable['status'])) ? $searchVariable['status'] : ''))
                        ->attributes(['class' => 'form-control form-control-dropdown'])->placeholder(trans("messages.status")) }}
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
                <div class="form-action ">
                    <button type="submit" class="btn theme_btn bg_theme btn-sm btnIcon" title="{{ trans('messages.search') }}"><em class="fa-solid fa-magnifying-glass"></em> </button>
                    <a href="{{ Route('admin.listUsers') }}" class="btn btn-sm border_btn btnIcon" title="{{ trans('messages.reset') }}"><em class='fa fa-refresh '></em> </a>
                </div>
            </div>
            {{ html()->closeModelForm() }}
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="table-responsive">
                <table class="@if (!$result->isEmpty()) table table-striped table-hover @endif">
                    <caption>User List</caption>
                    @if (!$result->isEmpty())
                    <thead>
                        <tr>
                            <th scope="col">
                                S.No.
                            </th>
                            <th scope="col">
                                {{ link_to_route(
                                                'admin.listUsers',
                                                trans('UserId'),
                                                [
                                                    'sortBy' => 'users.id',
                                                    'order' => $sortBy == 'users.id' && $order == 'desc' ? 'asc' : 'desc'
                                                ],
                                                $query_string,
                                            ) }}
                                @php getSortIcon($sortBy,$order,'users.id') @endphp
                            </th>
                            <th scope="col">
                                {{ link_to_route(
                                                'admin.listUsers',
                                                trans('messages.username'),
                                                [
                                                    'sortBy' => 'username',
                                                    'order' => $sortBy == 'username' && $order == 'desc' ? 'asc' : 'desc'
                                                ],
                                                $query_string,
                                            ) }}
                                @php getSortIcon($sortBy,$order,'username') @endphp
                            </th>
                            <th scope="col">
                                {{ link_to_route(
                                                'admin.listUsers',
                                                trans('messages.fullName'),
                                                [
                                                    'sortBy' => 'full_name',
                                                    'order' => $sortBy == 'full_name' && $order == 'desc' ? 'asc' : 'desc'
                                                ],
                                                $query_string,
                                            ) }}
                                @php getSortIcon($sortBy,$order,'full_name') @endphp
                            </th>

                            <th scope="col">
                                {{trans('messages.email')}}
                            </th>
                            <th scope="col">
                                {{trans('messages.countryCode')}}
                            </th>
                            <th scope="col">
                                {{trans('messages.phoneNo')}}
                            </th>
                            <th scope="col">
                                    {{ link_to_route(
                                                    'admin.listUsers',
                                                    trans('messages.subscriptionGeneral'),
                                                    [
                                                        'sortBy' => 'subscription_general',
                                                        'order' => $sortBy == 'subscription_general' && $order == 'desc' ? 'asc' : 'desc'
                                                    ],
                                                    $query_string,
                                                ) }}
                                    @php getSortIcon($sortBy,$order,'subscription_general') @endphp
                            </th>
                            <th scope="col">
                                    {{ link_to_route(
                                                    'admin.listUsers',
                                                    trans('messages.subscriptionMeditation'),
                                                    [
                                                        'sortBy' => 'subscription_meditation',
                                                        'order' => $sortBy == 'subscription_meditation' && $order == 'desc' ? 'asc' : 'desc'
                                                    ],
                                                    $query_string,
                                                ) }}
                                    @php getSortIcon($sortBy,$order,'subscription_meditation') @endphp
                            </th>
                            <th scope="col">
                                {{ link_to_route(
                                                'admin.listUsers',
                                                trans('messages.registeredOn'),
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
                                                'admin.listUsers',
                                                trans('Status'),
                                                [
                                                    'sortBy' => 'status',
                                                    'order' => $sortBy == 'status' && $order == 'desc' ? 'asc' : 'desc'
                                                ],
                                                $query_string,
                                            ) }}
                                @php getSortIcon($sortBy,$order,'status') @endphp
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
                                {{ ($record->id) }}
                            </td>
                            <td>
                                {{ ($record->username) }}
                            </td>
                            <td>
                                {{ ($record->full_name) }}
                            </td>
                            <td>
                                {{$record->email}}
                            </td>
                            <td>
                                +{{ !empty($record->country_code) ? $record->country_code : '-' }}
                            </td>
                            <td>
                               {{ !empty($record->mobile_no) ? $record->mobile_no : '-' }}
                            </td>
                             <td>
                                <?php if(!empty($record->subscription_general) && $record->subscription_general != null){
                                    echo "Yes";
                               }else{
                                   echo "No";
                               } ?>
                            </td>
                            <td>
                                <?php if(!empty($record->subscription_meditation) && $record->subscription_meditation != null){
                                    echo "Yes";
                               }else{
                                   echo "No";
                               } ?>
                            </td>
                           <td>
                                {{ date(Config::get('constants.ADMIN_LISTING_DATE_FORMAT'), strtotime($record->created_at)) }}
                            </td>
                            <td>
                                @can('USER_CHANGE_STATUS')
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
                                @else
                                     @if ($record->status == 1)
                                        <div class="form-check form-switch pl-0">
                                            <input disabled class="form-check-input " data-onlabel="On" checked type="checkbox" role="switch"  data-id="{{$record->id}}"  status='0' >
                                            <span class="on">{{ trans('messages.active') }}</span><span class="off">{{ trans('messages.inActive') }}</span>
                                        </div>
                                        @else
                                        <div class="form-check form-switch pl-0">
                                            <input disabled class="form-check-input "  type="checkbox" role="switch"  data-id="{{$record->id}}"  status='1' >
                                            <span class="on">{{ trans('messages.active') }}</span><span class="off">{{ trans('messages.inActive') }}</span>
                                        </div>
                                    @endif
                                @endcan
                            </td>
                            <td>
                                @can('USER_VIEW')
                                <a  title="{{ trans('messages.view') }}" href="{{URL::to('admin/user-list/view/'.$record->id)}}" class="btn btn-warning">
                                    <em class="fa fa-eye"></em>
                                </a>
                                @endcan
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
                   <!-- @include('pagination.default', ['paginator' => $result]) -->
                </div>
                <!-- pagination end -->
            </div>
        </div>
    </div>
</div>



@stop
