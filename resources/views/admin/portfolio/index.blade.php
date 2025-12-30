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
		<li class="active">{{$title}}</li>
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
                        {{ html()->text('title',((isset($searchVariable['title'])) ?
						$searchVariable['title'] : ''))
                        ->attributes(['class' => 'form-control',
                        'placeholder' => trans("messages.title")]) }}
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
					<button title="{{ trans('messages.search') }}" type="submit" class="btn theme_btn bg_theme btn-sm btnIcon"><em class="fa-solid fa-magnifying-glass"></em></button>
					<a title="{{ trans('messages.reset') }}" href="{{Route($listRoute)}}" class="btn btn-sm border_btn btnIcon">
						<em class='fa fa-refresh '></em>
                    </a>
                    <a href="{{url('admin/export/Portfolio/xls')}}" class="btn btn-sm border_btn" title="Export Xls">Export Xls</a>
                    <a href="{{url('admin/export/Portfolio/csv')}}" class="btn btn-sm border_btn" title="Export Csv">Export Csv</a>
					<div class="form-action_status">
						@can($addPermission)
						<a title="{{ trans('messages.addNew') }} {{$title}} " href="{{ Route($addRoute)}}" class="btn theme_btn bg_theme btn-sm py-2 btnIcon">
							<em class='fa fa-add '></em>
                        </a>
						@endcan
					</div>
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
						<tr>
							<th scope="col" class="w-5p">
								{{ trans("messages.sNo") }}
							</th>
							<th scope="col" class="w-10p">{{trans("messages.image")}}</th>
                            <th scope="col" class="w-15p">
								{{
										link_to_route(
										$listRoute,
										trans("messages.title"),
										array(
										'sortBy' => 'title',
										'order' => ($sortBy == 'title' && $order == 'desc') ? 'asc' : 'desc'
										),
										$query_string
										)
									}}
								@php getSortIcon($sortBy,$order,'title') @endphp
							</th>
                            <th scope="col" class="w-15p">
								{{
										link_to_route(
										$listRoute,
										trans("messages.url"),
										array(
										'sortBy' => 'url',
										'order' => ($sortBy == 'url' && $order == 'desc') ? 'asc' : 'desc'
										),
										$query_string
										)
									}}
								@php getSortIcon($sortBy,$order,'url') @endphp
							</th>
							<th scope="col" class="w-20p">
								{{
										link_to_route(
										$listRoute,
										trans("messages.updatedOn"),
										array(

										'sortBy' => $mainTable.'.updated_at',
										'order' => ($sortBy == $mainTable.'.updated_at' && $order == 'desc') ? 'asc' : 'desc'
										),
										$query_string
										)
									}}
								@php getSortIcon($sortBy,$order, $mainTable.'.updated_at') @endphp
							</th>
                            <th scope="col" class="w-10p">
								{{
										link_to_route(
										$listRoute,
										trans("messages.status"),
										array(

										'sortBy' => 'status',
										'order' => ($sortBy == 'status' && $order == 'desc') ? 'asc' : 'desc'
										),
										$query_string
										)
									}}
								@php getSortIcon($sortBy,$order,'status') @endphp
							</th>
							<th scope="col" class="w-10p">
								{{trans("messages.action")}}
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
						@if(!$result->isEmpty())
						@foreach($result as $key => $record)
						<tr>
							<td> {{ $i }}
								@php
								$i++;
								@endphp
							</td>
							<td>
								<img class="border border-1" alt="" src="{{ $record->thumbImage }}" width="50">
							</td>
                            <td class="breakAll">
								{{ $record->title }}
							</td>
                            <td class="breakAll">
								{{ $record->url }}
							</td>
							<td>
								{{ $record->created }}
							</td>
                            <td>
                                @can($statusPermission)
                                @if ($record->getRawOriginal('status') == 1)

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
								@can($editPermission)
								<a title="{{ trans('messages.edit') }}" href="{{URL::to('admin/'.$listUrl.'/edit/'.$record->id)}}" class="btn btn-primary">
									<em class="fa fa-pencil"></em>
								</a>
								@endcan
								@can($deletePermission)
                                <a class="btn btn-primary delete_any_item" data-id="{{ URL::to('admin/'.$listUrl.'/delete/' . $record->id) }}" title="{{ trans('Delete') }}" href="javascript::void(0);">
                                    <em class="fa fa-trash"></em>
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
<script>
    $(document).ready(function () {
        $('.delete_any_item').on('click', function() {
            status=$(this).attr('status');
            var location=$(this).attr('data-id');
            Swal.fire({
                title: "Are you sure you want to delete this?",
                text: "",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes"
            }).then((result) => {
                confirmed=false;
                if(result.isConfirmed){
                    window.location.href = location;
                }
            });
        });
    });
</script>

@stop
