<?php use \App\Constants\Constant; ?>
@extends('admin.layouts.default_layout')
@section('content')
<div class="header d-flex align-items-center">
	<h1 class="page-header">
		{{ trans("messages.reportedCommunity") }} {{ trans("messages.list") }} >> {{ trans("messages.whoReported") }} ({{$communityDetails['name']}})
	</h1>
	<ol class="breadcrumb ms-auto mb-0">
		<li><a href="{{ Route('admin.dashboard') }}">
				<em class="fa fa-dashboard"></em>
				{{ trans("messages.dashboard") }}</a></li>
        <li><a href="{{ Route('admin.reportCommunitylist') }}">
            <em class="fa fa-rss"></em>
            {{ trans("messages.reportedCommunity") }} {{ trans("messages.list") }}</a></li>
		<li class="active">{{ trans("messages.whoReported") }}</li>
	</ol>
</div>
<div id="page-inner">
    <div class="panel panel-default">
        <div class="panel-body form_mobile">
            {{ html()->modelForm(null, 'GET')->route('admin.reportCommunity.view',$id)
         ->attributes(['id'=>'communities','class'=>'form-inline','role'=>'form','autocomplete' => 'off','onSubmit'=>'return checkDate();'])->open() }}
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
                    <a title="{{ trans('messages.reset') }}" href="{{Route('admin.reportCommunity.view',$id)}}" class="btn btn-sm border_btn btnIcon">
                        <em class='fa fa-refresh '></em>
                    </a>
                </div>
            </div>
            {{ html()->closeModelForm() }}
        </div>
    </div>
    <div class="panel panel-default">
		<div class="panel-body">
            <div class="row">
                <div class="form-group d-flex gap-3">
                    <a href="{{ route("admin.reportCommunitylist")}}" class="btn px-sm-5 font-semibold border_btn">
                        <em class="icon-refresh"></em> {{ trans("messages.back") }}</a>
                </div>
            </div>
			<div class="table-responsive">
				<table class="@if (!$result->isEmpty()) table table-striped table-hover @endif" id="sortable">
					<caption>{{trans("messages.reportCommunity")}} {{ trans("messages.view") }}</caption>

					@if (!$result->isEmpty())
					<thead>
						<th scope="col" class="w-5p">
							{{ trans("messages.sNo") }}
						</th>
                        <th scope="col" class="w-20p">
							{{
                                    link_to_route(
                                        'admin.reportCommunity.view',
                                        trans("messages.username"),
                                        array(
                                            'id'=>$id,
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
                                        'admin.reportCommunity.view',
                                        trans("messages.userId"),
                                        array(
                                            'id'=>$id,
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
                                        'admin.reportCommunity.view',
                                        trans("messages.date"),
                                        array(
                                            'id'=>$id,
                                            'sortBy' =>  'community_reports.created_at',
                                            'order' => ($sortBy == 'community_reports.created_at' && $order == 'desc') ? 'asc' : 'desc'
                                        ),
                                        $query_string
                                    )
								}}
							@php getSortIcon($sortBy,$order,'community_reports.created_at') @endphp
						</th>
                        <th scope="col" class="w-10p">
                            {{ trans("messages.type") }}
                        </th>
                        <th scope="col" class="w-10p">
                            {{ trans("messages.message") }}
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
                            <td>
								{{ date(Config::get('constants.ADMIN_LISTING_DATE_FORMAT'),strtotime($record->created_at)) }}
							</td>
                            <td>
								{{$record->reportType}}
							</td>
                            <td>
								<span class="show-more" title="{{ trans('messages.view') }}" >
                                    <span class="excerpt">{{ excerpt($record->message, 30) }}</span>
                                    <span class="more-content" style="display: none;">{{ $record->message }}</span>
                                    <span class="show-more-text show_more_css" style="display: {{ strlen($record->message) > 30 ? 'inline' : 'none' }}">Show more</span>
                                    <span class="show-less-text show_more_css" style="display: none;">Show less</span>
                                </span>
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
<script>
    $(document).ready(function(){
        $('.show-more').click(function(e){
            e.preventDefault();
            var $moreContent = $(this).find('.more-content');
            var $excerpt = $(this).find('.excerpt');
            var $showMoreText = $(this).find('.show-more-text');
            var $showLessText = $(this).find('.show-less-text');
            $excerpt.toggle();
            $moreContent.toggle();
            $showMoreText.toggle();
            $showLessText.toggle();
        });
    });
    </script>



@stop
