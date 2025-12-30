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
                        {{ html()->text('name',((isset($searchVariable['name'])) ?
						$searchVariable['name'] : ''))
                        ->attributes(['class' => 'form-control',
                        'placeholder' => trans("messages.searchBy")." ".trans('messages.question')]) }}
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
						<th scope="col" class="w-5p">
							{{ trans("messages.sNo") }}
						</th>
						<th scope="col" class="w-45p">
							{{
                                    link_to_route(
                                        $listRoute,
                                        trans("messages.question"),
                                        array(
                                            'sortBy' => 'name',
                                            'order' => ($sortBy == 'name' && $order == 'desc') ? 'asc' : 'desc'
                                        ),
                                        $query_string
                                    )
								}}
							@php getSortIcon($sortBy,$order,'name') @endphp
						</th>

						<th scope="col" class="w-20p">
							{{
                                    link_to_route(
                                        $listRoute,
                                        trans("messages.createdOn"),
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
								{{$record->name}}
							</td>
                            <td>
								{{ date(Config::get('constants.ADMIN_LISTING_DATE_FORMAT'),strtotime($record->created_at)) }}
							</td>

							<td>
								@can($editPermission)
								<a style='display:none;' title="{{ trans('messages.edit') }}" href="{{URL::to('admin/'.$listUrl.'/edit/'.$record->id)}}" class="btn btn-primary">
									<em class="fa fa-pencil"></em>
								</a>
								@endcan
								@can($deletePermission)

                                <a class="btn btn-danger delete_any_item" data-id="{{ URL::to('admin/'.$listUrl.'/delete/' . $record->id) }}" title="{{ trans('Delete') }}" href="javascript::void(0);">
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
