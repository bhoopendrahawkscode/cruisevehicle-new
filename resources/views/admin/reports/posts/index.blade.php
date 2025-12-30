<?php use \App\Constants\Constant; ?>
@extends('admin.layouts.default_layout')
@section('content')
<?php use App\Services\GeneralService; ?>
<div class="header d-flex align-items-center">
	<h1 class="page-header">
		{{ trans("messages.reportedPost") }} {{ trans("messages.list") }}
	</h1>
	<ol class="breadcrumb ms-auto mb-0">
		<li><a href="{{ Route('admin.dashboard') }}">
				<em class="fa fa-dashboard"></em>
				{{ trans("messages.dashboard") }}</a></li>
		<li class="active">{{ trans("messages.reportedPost") }} {{ trans("messages.list") }}</li>
	</ol>
</div>
<div id="page-inner">
    <div class="panel panel-default">
        <div class="panel-body form_mobile">
            {{ html()->modelForm(null, 'GET')->route('admin.reportPostlist')
         ->attributes(['id'=>'reports','class'=>'form-inline','role'=>'form','autocomplete' => 'off','onSubmit'=>'return checkDate();'])->open() }}
            {{ html()->hidden('display') }}
            <div class="form_row">
                <div class="form_fields d-flex mb-0 gap-2">
                    <div class="form-group mb-0">
                        {{ html()->text('name',((isset($searchVariable['name'])) ?
						$searchVariable['name'] : ''))
                        ->attributes(['class' => 'form-control',
                        'placeholder' => trans("messages.searchBy")." ".trans('messages.username')]) }}
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
                    <button title="{{ trans('messages.search') }}" type="submit" class="btn theme_btn bg_theme btn-sm btnIcon"><em class="fa-solid fa-magnifying-glass"></em></button>
                    <a title="{{ trans('messages.reset') }}" href="{{Route('admin.reportPostlist')}}" class="btn btn-sm border_btn btnIcon">
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
					<caption>{{trans("messages.reportedPost")}} {{ trans("messages.list") }}</caption>

					@if (!$result->isEmpty())
					<thead>
						<th scope="col" class="w-5p">
							{{ trans("messages.sNo") }}
						</th>
                        <th scope="col" class="w-5p">
							{{
                                    link_to_route(
                                        'admin.reportPostlist',
                                        trans("messages.postOwner")." ".trans("messages.username"),
                                        array(
                                            'sortBy' => 'users.username',
                                            'order' => ($sortBy == 'users.username' && $order == 'desc') ? 'asc' : 'desc'
                                        ),
                                        $query_string
                                    )
								}}
							@php getSortIcon($sortBy,$order,'users.username') @endphp
						</th>
                        <th scope="col" class="w-5p">
							{{
                                    link_to_route(
                                        'admin.reportPostlist',
                                        trans("messages.postOwner")." ".trans("messages.userId"),
                                        array(
                                            'sortBy' => 'users.id',
                                            'order' => ($sortBy == 'users.id' && $order == 'desc') ? 'asc' : 'desc'
                                        ),
                                        $query_string
                                    )
								}}
							@php getSortIcon($sortBy,$order,'users.id') @endphp
						</th>
                        <th scope="col" class="w-25p">
							{{
                                    link_to_route(
                                        'admin.reportPostlist',
                                        trans("messages.post"),
                                        array(
                                            'sortBy' => 'blogs.content',
                                            'order' => ($sortBy == 'blogs.content' && $order == 'desc') ? 'asc' : 'desc'
                                        ),
                                        $query_string
                                    )
								}}
							@php getSortIcon($sortBy,$order,'blogs.content') @endphp
						</th>
                        <th scope="col" class="w-5p">
							{{
                                    link_to_route(
                                        'admin.reportPostlist',
                                        trans("messages.noOfReports"),
                                        array(
                                            'sortBy' => 'count',
                                            'order' => ($sortBy == 'count' && $order == 'desc') ? 'asc' : 'desc'
                                        ),
                                        $query_string
                                    )
								}}
							@php getSortIcon($sortBy,$order,'count') @endphp
						</th>
                        <th scope="col" class="w-10p">
							{{
                                    link_to_route(
                                        'admin.reportPostlist',
                                        trans("messages.lastReportedOn"),
                                        array(
                                            'sortBy' => 'blogs.last_reported_at',
                                            'order' => ($sortBy == 'blogs.last_reported_at' && $order == 'desc') ? 'asc' : 'desc'
                                        ),
                                        $query_string
                                    )
								}}
							@php getSortIcon($sortBy,$order,'blogs.last_reported_at') @endphp
						</th>
                        <th scope="col" class="w-5p">
							{{
                                    link_to_route(
                                        'admin.reportPostlist',
                                        trans("messages.postStatus"),
                                        array(
                                            'sortBy' => 'blogs.status',
                                            'order' => ($sortBy == 'blogs.status' && $order == 'desc') ? 'asc' : 'desc'
                                        ),
                                        $query_string
                                    )
								}}
							@php getSortIcon($sortBy,$order,'blogs.status') @endphp
						</th>
						<th scope="col" class="w-10p">
							{{trans("messages.action")}}
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
								{{$record->username}}
							</td>
                            <td>
								{{$record->userId}}
							</td>

                            <td>@can("POSTS_MANAGEMENT_VIEW")
                                <a title="{{ trans('messages.view') }}" href="{{URL::to('admin/post-list/view/'.$record->blogId.'?refer=blog_reports')}}" >
                                    {{excerpt($record->blogName,100)}}
                                </a>
                                @else
                                {{excerpt($record->blogName,100)}}
                                @endcan
                            </td>

                            <td>
								{{$record->count}}
							</td>
                            <td>
								{{ date(Config::get('constants.ADMIN_LISTING_DATE_FORMAT'),strtotime($record->last_reported_at)) }}
							</td>
							<td>
                                @can("REPORTS_POSTS_MANAGEMENT_CHANGE_STATUS")
                                    @if ($record->blogStatus == 1)
                                        <div class="form-check form-switch pl-0">
                                            <input class="form-check-input status_any_item" data-onlabel="On" checked type="checkbox" role="switch"  data-id="{{$record->blogId}}"  status='0' >
                                            <span class="on">{{ trans('messages.active') }}</span><span class="off">{{ trans('messages.inActive') }}</span>
                                        </div>
                                        @else
                                        <div class="form-check form-switch pl-0">
                                            <input class="form-check-input status_any_item"  type="checkbox" role="switch"  data-id="{{$record->blogId}}"  status='1' >
                                            <span class="on">{{ trans('messages.active') }}</span><span class="off">{{ trans('messages.inActive') }}</span>
                                        </div>
                                    @endif
                                @else
                                    @if ($record->blogStatus == 1)
                                        <div class="form-check form-switch pl-0">
                                            <input disabled class="form-check-input " data-onlabel="On" checked type="checkbox" role="switch"  data-id="{{$record->blogId}}"  status='0' >
                                            <span class="on">{{ trans('messages.active') }}</span><span class="off">{{ trans('messages.inActive') }}</span>
                                        </div>
                                        @else
                                        <div class="form-check form-switch pl-0">
                                            <input disabled class="form-check-input "  type="checkbox" role="switch"  data-id="{{$record->blogId}}"  status='1' >
                                            <span class="on">{{ trans('messages.active') }}</span><span class="off">{{ trans('messages.inActive') }}</span>
                                        </div>
                                    @endif
                                @endcan
                            </td>
							<td>
                                @can("REPORTS_POSTS_MANAGEMENT_VIEW")
                                <a  title="{{ trans('messages.whoReported') }}" href="{{URL::to('admin/report-post-view/'.$record->blogId)}}" class="btn btn-warning">
                                    <em class="fa fa-flag"></em>
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
