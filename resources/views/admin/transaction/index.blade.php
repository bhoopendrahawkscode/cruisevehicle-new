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
                        {{ html()->text('name',((isset($searchVariableTrans['name'])) ?
						$searchVariableTrans['name'] : ''))
                        ->attributes(['class' => 'form-control',
                        'placeholder' => trans("messages.searchBy")." ".trans('messages.transactionId')]) }}
					</div>
					<div class="form-group mb-0 calendarIcon">
                        {{ html()->text('from',((isset($searchVariableTrans['from'])) ?
                        $searchVariableTrans['from'] : ''))
                        ->attributes(['class' => 'form-control datepicker','onkeydown'=>'return false;',
                        'placeholder' => trans('messages.fromDate')]) }}
					</div>
					<div class="form-group mb-0 calendarIcon">
						{{ html()->text('to',((isset($searchVariableTrans['to'])) ?
                        $searchVariableTrans['to'] : ''))
                        ->attributes(['class' => 'form-control datepicker','onkeydown'=>'return false;',
                        'placeholder' => trans('messages.toDate')]) }}
					</div>
				</div>
				<div class="form-action ">
					<button title="{{ trans('messages.search') }}" type="submit" class="btn theme_btn bg_theme btn-sm btnIcon"><em class="fa-solid fa-magnifying-glass"></em></button>
					<a title="{{ trans('messages.reset') }}" href="{{Route($listRoute)}}" class="btn btn-sm border_btn btnIcon"><em class='fa fa-refresh '></em></a>
					<a href="{{url('admin/export/Transaction/xls')}}" class="btn btn-sm border_btn" title="Export Xls">Export Xls</a>
                    <a href="{{url('admin/export/Transaction/csv')}}" class="btn btn-sm border_btn" title="Export Csv">Export Csv</a>
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

                        <th scope="col" class="w-10p">
							{{
                                    link_to_route(
                                        $listRoute,
                                        trans("messages.transactionId"),
                                        array(
                                            'sortBy' => 'transaction_id',
                                            'order' => ($sortBy == 'transaction_id' && $order == 'desc') ? 'asc' : 'desc'
                                        ),
                                        $query_string
                                    )
								}}
							@php getSortIcon($sortBy,$order,'transaction_id') @endphp
						</th>
                        <th scope="col" class="w-10p">
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
                                        trans("messages.fullName"),
                                        array(
                                            'sortBy' => 'users.full_name',
                                            'order' => ($sortBy == 'users.full_name' && $order == 'desc') ? 'asc' : 'desc'
                                        ),
                                        $query_string
                                    )
								}}
							@php getSortIcon($sortBy,$order,'users.full_name') @endphp
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

                        <th scope="col" class="w-10p">
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
                        <th scope="col" class="w-25p">
							{{
                                    link_to_route(
                                        $listRoute,
                                        trans("messages.subscriptionName"),
                                        array(
                                            'sortBy' => 'subscription_translations.name',
                                            'order' => ($sortBy == 'subscription_translations.name' && $order == 'desc') ? 'asc' : 'desc'
                                        ),
                                        $query_string
                                    )
								}}
							@php getSortIcon($sortBy,$order,'subscription_translations.name') @endphp
						</th>

                        <th scope="col" class="w-10p">
							{{
                                    link_to_route(
                                        $listRoute,
                                        trans("messages.totalEarning"),
                                        array(
                                            'sortBy' => 'amount',
                                            'order' => ($sortBy == 'amount' && $order == 'desc') ? 'asc' : 'desc'
                                        ),
                                        $query_string
                                    )
								}}
							@php getSortIcon($sortBy,$order,'amount') @endphp
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
								{{$record->transaction_id}}
							</td>
                            <td>
								{{$record->userId}}
							</td>
                            <td>
								{{$record->fullName}}
							</td>
                            <td>
								{{$record->userName}}
							</td>
                            <td>
								{{ $record->created }}
							</td>

                            <td>
								{{$record->subscriptionName}}
							</td>
                            <td>
								{{$record->amount}}
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
