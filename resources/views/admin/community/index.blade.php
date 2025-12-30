<?php use \App\Constants\Constant; ?>
@extends('admin.layouts.default_layout')
@section('content')
<?php use App\Services\GeneralService; ?>
<div class="header d-flex align-items-center">
	<h1 class="page-header">
		{{$title}} {{ trans("messages.list") }}
	</h1>
	<ol class="breadcrumb ms-auto mb-0">
		<li><a href="{{ Route('admin.dashboard') }}">
				<em class="fa fa-dashboard"></em>
				{{ trans("messages.dashboard") }}</a></li>
		<li class="active">{{$title}} {{ trans("messages.list") }}</li>
	</ol>
</div>
<div id="page-inner">
	<div class="panel panel-default">
		<div class="panel-body form_mobile">
             {{ html()->modelForm(null, 'GET')->route($listRoute)
             ->attributes(['id'=>$formId,'class'=>'form-inline','role'=>'form','autocomplete' => 'off','onSubmit'=>'return checkDate();'])->open() }}
            {{ html()->hidden('display') }}
			<div class="form_row">
				<div class="form_fields d-flex mb-0 gap-2">
					<div class="form-group mb-0">
                        {{ html()->text('name',((isset($searchVariableCommunity['name'])) ?
						$searchVariableCommunity['name'] : ''))
                        ->attributes(['class' => 'form-control',
                        'placeholder' => trans("messages.searchBy")." ".trans('messages.communityName')]) }}
					</div>
                    <?php $filterArr  = Constant::COMMUNITIES;
                        unset($filterArr[0]);
                    ?>
                    <div class="form-group mb-0 w-20p">
                        {{ html()->select('type',$filterArr,
						((isset($searchVariableCommunity['type'])) ? $searchVariableCommunity['type'] : ''))
                        ->attributes(['class' => 'form-control form-control-dropdown'])->placeholder(trans("messages.communityType")) }}
					</div>
					<div class="form-group mb-0 calendarIcon">
                        {{ html()->text('from',((isset($searchVariableCommunity['from'])) ?
                        $searchVariableCommunity['from'] : ''))
                        ->attributes(['class' => 'form-control datepicker','onkeydown'=>'return false;',
                        'placeholder' => trans('messages.fromDate')]) }}
					</div>
					<div class="form-group mb-0 calendarIcon">
						{{ html()->text('to',((isset($searchVariableCommunity['to'])) ?
                        $searchVariableCommunity['to'] : ''))
                        ->attributes(['class' => 'form-control datepicker','onkeydown'=>'return false;',
                        'placeholder' => trans('messages.toDate')]) }}
					</div>
				</div>
				<div class="form-action ">
					<button title="{{ trans('messages.search') }}" type="submit" class="btn theme_btn bg_theme btn-sm btnIcon"><em class="fa-solid fa-magnifying-glass"></em></button>
					<a title="{{ trans('messages.reset') }}" href="{{Route($listRoute)}}" class="btn btn-sm border_btn btnIcon">
						<em class='fa fa-refresh '></em>
                    </a>
				</div>
			</div>
            {{ html()->closeModelForm() }}
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="table-responsive">
				<table class="@if (!$result->isEmpty()) table table-striped table-hover @endif" id="sortable">
					<caption>{{$title}} {{ trans("messages.list") }}</caption>

					@if (!$result->isEmpty())
					<thead>
						<th scope="col" class="w-5p">
							{{ trans("messages.sNo") }}
						</th>

                        <th scope="col" class="w-5p">
							{{
                                    link_to_route(
                                        $listRoute,
                                        trans("messages.userId"),
                                        array(
                                            'sortBy' => 'users.id',
                                            'order' => ($sortBy == 'users.id' && $order == 'desc') ? 'asc' : 'desc'
                                        ),
                                        $query_string
                                    )
								}}
							@php getSortIcon($sortBy,$order,'users.id') @endphp
						</th>

                        <th scope="col" class="w-10p">
							{{
                                    link_to_route(
                                        $listRoute,
                                        trans("messages.username"),
                                        array(
                                            'sortBy' => 'users.username',
                                            'order' => ($sortBy == 'users.username' && $order == 'desc') ? 'asc' : 'desc'
                                        ),
                                        $query_string
                                    )
								}}
							@php getSortIcon($sortBy,$order,'username') @endphp
						</th>

                        <th scope="col" class="w-20p">
							{{
                                    link_to_route(
                                        $listRoute,
                                        trans("messages.communityName"),
                                        array(
                                            'sortBy' => 'communities.name',
                                            'order' => ($sortBy == 'communities.name' && $order == 'desc') ? 'asc' : 'desc'
                                        ),
                                        $query_string
                                    )
								}}
							@php getSortIcon($sortBy,$order,'communities.name') @endphp
						</th>

                        <th scope="col" class="w-5p">
							{{
                                    link_to_route(
                                        $listRoute,
                                        trans("messages.communityId"),
                                        array(
                                            'sortBy' => 'communities.id',
                                            'order' => ($sortBy == 'communities.id' && $order == 'desc') ? 'asc' : 'desc'
                                        ),
                                        $query_string
                                    )
								}}
							@php getSortIcon($sortBy,$order,'communities.id') @endphp
						</th>

                        <th scope="col" class="w-10p">
							{{
                                    link_to_route(
                                        $listRoute,
                                        trans("messages.communityType"),
                                        array(
                                            'sortBy' => 'communities.type',
                                            'order' => ($sortBy == 'communities.type' && $order == 'desc') ? 'asc' : 'desc'
                                        ),
                                        $query_string
                                    )
								}}
							@php getSortIcon($sortBy,$order,'communities.type') @endphp
						</th>

                        <th scope="col" class="w-10p">
							{{
                                    link_to_route(
                                        $listRoute,
                                        trans("messages.addedPeople"),
                                        array(
                                            'sortBy' =>  'totalPeople',
                                            'order' => ($sortBy ==  'totalPeople' && $order == 'desc') ? 'asc' : 'desc'
                                        ),
                                        $query_string
                                    )
								}}
							@php getSortIcon($sortBy,$order,'totalPeople') @endphp
						</th>

                        <th scope="col" class="w-10p">
							{{
                                    link_to_route(
                                        $listRoute,
                                        trans("messages.totalPosts"),
                                        array(
                                            'sortBy' =>  'totalPosts',
                                            'order' => ($sortBy ==  'totalPosts' && $order == 'desc') ? 'asc' : 'desc'
                                        ),
                                        $query_string
                                    )
								}}
							@php getSortIcon($sortBy,$order,'totalPosts') @endphp
						</th>
                        <th scope="col" class="w-20p">
							{{
                                    link_to_route(
                                        $listRoute,
                                        trans("messages.date"),
                                        array(
                                            'sortBy' =>  $mainTable.'.created_at',
                                            'order' => ($sortBy ==  $mainTable.'.created_at' && $order == 'desc') ? 'asc' : 'desc'
                                        ),
                                        $query_string
                                    )
								}}
							@php getSortIcon($sortBy,$order,$mainTable.'.created_at') @endphp
						</th>
                        <th scope="col" class="w-10p">
							{{
                                    link_to_route(
                                        $listRoute,
                                        trans("messages.status"),
                                        array(
                                            'sortBy' => 'communities.status',
                                            'order' => ($sortBy == 'communities.status' && $order == 'desc') ? 'asc' : 'desc'
                                        ),
                                        $query_string
                                    )
								}}
							@php getSortIcon($sortBy,$order,'communities.status') @endphp
						</th>
                        <th scope="col">
                            {{trans('messages.action')}}
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
						@if(!$result->isEmpty())
						@foreach($result as $key => $record)
						<tr>
							<td> {{ $i }}
								@php
								$i++;
								@endphp
							</td>
                            <td>
								{{$record->userId}}
							</td>
                            <td>
								{{$record->userName}}
							</td>
                            <td>
								@if(empty($record->communityName))
                                    -
                                @else
                                    {{$record->communityName}}
                                @endif
							</td>
                            <td>
                                {{$record->communityId}}
							</td>
                            <td>
								@if(empty($record->communityType))
                                    -
                                @else
                                    {{getCommunityName($record->communityType)}}
                                @endif
							</td>
                            <td>
                                {{$record->totalPeople}}
							</td>
                            <td>
                                {{$record->totalPosts}}
							</td>
                            <td>
								{{ date(Config::get('constants.ADMIN_LISTING_DATE_FORMAT'),strtotime($record->created_at)) }}
							</td>
                            <td>
                                @can("COMMUNITIES_MANAGEMENT_CHANGE_STATUS")
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
                                @can("COMMUNITIES_MANAGEMENT_VIEW")
                                <a  title="{{ trans('messages.view') }}" href="{{URL::to('admin/'.$listUrl.'/view/'.$record->id)}}" class="btn btn-warning">
                                    <em class="fa fa-eye"></em>
                                </a>
                                @endcan
                            </td>
						</tr>
						@endforeach
						@else
						<tr>
							<td colspan="6" class="text-center noRecord"><strong>
									{{ trans("messages.noRecordFound") }} </strong></td>
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
