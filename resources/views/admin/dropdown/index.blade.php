@extends('admin.layouts.default_layout')
<link integrity="" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/css/bootstrap3/bootstrap-switch.min.css" rel="stylesheet">
@section('content')
<?php use App\Services\GeneralService; ?>
<div class="header d-flex align-items-center">
	<h1 class="page-header">
		{{{ ucwords(__('dropdowns.'.str_replace("_"," ",$type))) }}} {{ trans('messages.list') }}
	</h1>
	<ol class="breadcrumb ms-auto mb-0">
		<li><a href="{{ Route('admin.dashboard') }}"><em class="fa fa-dashboard"></em> {{ trans('messages.dashboard') }}</a></li>
		<li class="active">{{ ucwords(__('dropdowns.'.str_replace("_"," ",$type))) }}</li>
	</ol>
</div>
<section id="page-inner">
	<div class="panel panel-default">
		<div class="panel-body">

            {{ html()->modelForm(null, 'GET')->attributes(['class'=>'form-inline','role'=>'form','autocomplete' => 'off','onSubmit'=>'return checkDate();'])->open() }}

			{{ html()->hidden('display') }}
			<div class="form_row">
				<div class="form_fields d-flex mb-0 gap-2">
					<div class="form-group mb-0">
                        {{ html()->text('name',((isset($searchVariable['name'])) ?
						$searchVariable['name'] : ''))
                        ->attributes(['class' => 'form-control',
                        'placeholder' => trans("messages.name")]) }}
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
				<div class="form-action">
					<button class="btn theme_btn bg_theme btn-sm btnIcon" title="{{ trans('messages.search') }}" type="submit"><em class="fa-solid fa-magnifying-glass"></em> </button>
					<a title="{{ trans('messages.reset') }}" href="{{ url('/admin/dropdown/index/'.$type) }}" class="btn btn-sm border_btn btnIcon"><em class="fa fa-refresh"></em> </a>

					<div class="form-action_status">

						@can(strtoupper($type.'_ADD'))
						<a title="{{ trans('messages.add') }} {{{ ucwords(__('dropdowns.'.str_replace("_"," ",$type))) }}}" class="btn theme_btn bg_theme btn-sm py-2 btnIcon" href="{{ url('/admin/dropdown/add/'.$type) }}">
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
				<table class="@if (!$result->isEmpty()) table table-striped table-hover @endif">
					<caption>{{{ ucwords(__('dropdowns.'.str_replace("_"," ",$type))) }}} {{ trans('messages.list') }}</caption>
					@if (!$result->isEmpty())
					<thead>
						<tr>
							<th scope="col">S.No.</th>
							<th class="w-25p" scope="col">
								{{
                                        link_to_route(
                                        "admin.dropdown.index",
                                        trans("messages.name"),
                                        array(
                                        'name'=>   $type,
                                        'sortBy' => 'name',
                                        'order' => ($sortBy == 'name' && $order == 'desc') ? 'asc' : 'desc'
                                        ),
                                        $query_string
                                        )
                                    }}
								@php getSortIcon($sortBy,$order,'name') @endphp
							</th>

							@if($image_exist['image_show']==1)
							<th scope="col">{{trans("messages.image")}}</th>
							@endif

							<th class="w-25p" scope="col">
								{{
                                        link_to_route(
                                        "admin.dropdown.index",
                                        trans("messages.updatedOn"),

                                        array(
                                        'name'=>   $type,
                                        'sortBy' => $type.'.updated_at',
                                        'order' => ($sortBy == $type.'.updated_at' && $order == 'desc') ? 'asc' : 'desc'
                                        ),
                                        $query_string
                                        )
                                    }}
								@php getSortIcon($sortBy,$order,$type.'.updated_at') @endphp
							</th>
                            <th scope="col" class="w-10p">
                                {{
                                        link_to_route(
                                            "admin.dropdown.index",
                                            trans("messages.status"),
                                            array(
                                                'name'=>   $type,
                                                'sortBy' => $type.'.status',
                                                'order' => ($sortBy == $type.'.status' && $order == 'desc') ? 'asc' : 'desc'
                                            ),
                                        $query_string
                                        )
                                    }}
                                @php getSortIcon($sortBy,$order,'status') @endphp
                            </th>
							<th class="w-30p" scope="col">{{trans("messages.action")}}</th>
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
						@if(isset($result) && !$result->isEmpty())
						<?php $count = 1; ?>
						@foreach($result as $key=>$value)

						<tr>
							<td>{{ $i }}
								@php
								$i++;
								@endphp</td>
							<td>{{ isset($value->name) ? ($value->name) : ''}}</td>
							@if($image_exist['image_show']==1)
							<td>
								<img alt="{{$value->name}}" src="{{ $value->ThumbImage; }}" width="50">
								@endif

							</td>

							<td>{{ isset($value->created_at) ?  date(Config::get('constants.ADMIN_LISTING_DATE_FORMAT'),strtotime($value->updated_at)) : ''}}</td>
                            <td>
                                @can(strtoupper($type.'_CHANGE_STATUS'))

								@if($value->status == 1)


                                <div class="form-check form-switch pl-0">
                                    <input class="form-check-input status_any_item_drop_down" data-onlabel="On" checked type="checkbox" role="switch"  data-id="{{$value->id}}"  status='0' table_name="{{$type}}" >
                                    <span class="on">{{ trans('messages.active') }}</span><span class="off">{{ trans('messages.inActive') }}</span>
                                  </div>
                                @else
                                <div class="form-check form-switch pl-0">
                                    <input class="form-check-input status_any_item_drop_down"  type="checkbox" role="switch"  data-id="{{$value->id}}"  status='1'  table_name="{{$type}}" >
                                    <span class="on">{{ trans('messages.active') }}</span><span class="off">{{ trans('messages.inActive') }}</span>
                                  </div>
                                @endif


								{{-- <a title="{{ trans('messages.clickToDeactivate') }}" href="{{url('admin/dropdown/status/'.$value->id.'/'.$type.'/0')}}" class=" btn btn-success btn-small status_any_item"><span class="fa fa-check"></span> </a>
								@else

								<a title="{{ trans('messages.clickToActivate') }}" href="{{url('admin/dropdown/status/'.$value->id.'/'.$type.'/1')}}" class=" btn btn-warning btn-small status_any_item"><span class="fa fa-ban"></span></a>

                                @endif --}}

								@endcan
                            </td>
							<td>
								@can(strtoupper($type.'_EDIT'))
								<a href="{{ url('admin/dropdown/edit/'.$value->id.'/'.$type)}}" title="{{ trans('messages.edit') }}" class="btn btn-primary"><em class="fa fa-edit "></em></a>
								@endcan
								@can(strtoupper($type.'_DELETE'))
								<a style='display:none' href="{{ url('admin/dropdown/delete/'.$value->id.'/'.$type)}}" title="{{ trans('messages.edit') }}" class="btn btn-danger"><em class="fa fa-trash "></em></a>
								@endcan







							</td>
						</tr>
						<?php $count++;  ?>
						@endforeach
						@else
						<tr>
							<td colspan="5" class="text-center noRecord"><strong> {{ trans('messages.noRecordFound') }}</strong></td>
						</tr>
						@endif
					</tbody>
				</table>
				<div class="box-footer clearfix">
					@include('pagination.default', ['paginator' => $result])
				</div>
			</div>
		</div><!-- /.panel-body -->
	</div><!-- /.panel panel-default -->
</section>
<script>
    $(document).ready(function () {
        $('.status_any_item_drop_down').on('change', function() {
            status=$(this).attr('status');
            id=$(this).attr('data-id');

            var location= "{!! URL::to('admin/dropdown/status') !!}"+'/'+id+'/'+$(this).attr('table_name')+'/'+$(this).attr('status');

            Swal.fire({
                title: "Are you sure you want to change the status?",
                text: "",
                icon: "warning",
                showCancelButton: true,
                // confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes"
            }).then((result) => {
                confirmed=false;
                if(result.isConfirmed){
                    var confirmed = result.isConfirmed;
                }
                if (status) {
                    if (!confirmed) {
                        if(status==1){
                            $(this).prop('checked', false);
                        }else{
                        $(this).prop('checked', true);
                        }
                    }else{
                        block();
                        window.location.href = location;
                    }
                }
            });
        });
    });
</script>
@endsection

