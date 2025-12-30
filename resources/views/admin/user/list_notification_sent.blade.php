@extends('admin.layouts.default_layout')
@section('content')
<?php use App\Services\GeneralService; ?>
<div class="header d-flex align-items-center">
    <h1 class="page-header">
    {{ trans('messages.notificationList') }}
    </h1>
    <ol class="breadcrumb ms-auto mb-0">
        <li><a href="{{ Route('admin.dashboard') }}"><em class="fa fa-dashboard"></em> {{ trans('messages.dashboard') }}</a></li>
        <li class="active">{{ trans('messages.notificationList') }}</li>
    </ol>
</div>
@php
$arrayNotificationType= Config::get('constants.arrayNotificationType');
@endphp
<div id="page-inner">
    <div class="panel panel-default">
        <div class="panel-body form_mobile">
            {{ html()->modelForm(null, 'GET')->route('admin.ListNotification')
             ->attributes(['class'=>'form-inline','role'=>'form','autocomplete' => 'off','onSubmit'=>'return checkDate();'])->open() }}
            {{ html()->hidden('display') }}
            {{ html()->hidden('type',$type) }}
            <div class="form_row">
                <div class="form_fields d-flex mb-0 gap-2">
                    <div class="form-group mb-0">
                        {{ html()->text('title',((isset($searchVariable['title'])) ?
                        $searchVariable['title'] : ''))
                        ->attributes(['class' => 'form-control',
                        'placeholder' => trans("messages.searchBy")." ".trans('messages.title')]) }}
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
                    <a title="{{ trans('messages.reset') }}" href="{{Route('admin.ListNotification')}}?type={{$type}}" class="btn btn-sm border_btn btnIcon">
                        <em class='fa fa-refresh '></em>
                    </a>
                    <div class="form-action_status">

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
                    <caption>{{ trans('messages.notificationList') }}</caption>
                    @if (!$result->isEmpty())
                    <thead>
                        <tr>
                            <th scope="col">
                                {{ trans('messages.sNo') }}
                            </th>
                            <th scope="col">
                                {{
                                    link_to_route(
                                        'admin.ListNotification',
                                        trans('Receiver Name'),
                                        [
                                            'sortBy' => 'full_name',
                                            'order' => $sortBy == 'full_name' && $order == 'desc' ? 'asc' : 'desc'
                                        ],
                                        $query_string,
                                    )
                                }}
                                @php getSortIcon($sortBy,$order,'title') @endphp
                            </th>
                            <th scope="col">
                                {{
                                    link_to_route(
                                        'admin.ListNotification',
                                        trans('messages.title'),
                                        [
                                            'sortBy' => 'title',
                                            'order' => $sortBy == 'title' && $order == 'desc' ? 'asc' : 'desc'
                                        ],
                                        $query_string,
                                    )
                                }}
                                @php getSortIcon($sortBy,$order,'title') @endphp
                            </th scope="col">
                            <th scope="col" class="w-45p">
                                {{ trans('messages.description') }}
                            </th>

                            <th scope="col">
                                {{
                                    link_to_route(
                                        'admin.ListNotification',
                                        trans('messages.receivedDate'),
                                        [
                                            'sortBy' => 'user_notifications.created_at',
                                            'order' => $sortBy == 'user_notifications.created_at' && $order == 'desc' ? 'asc' : 'desc'
                                        ],
                                        $query_string

                                    )
                                }}
                                    @php getSortIcon($sortBy,$order,'user_notifications.created_at') @endphp
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
                                @php
                                echo $record->full_name;
                                @endphp
                            </td>
                            <td>
                                <?php if($record->status == 0){
                                        echo  '<span style="color:#ee0c85;">'.$record->title.'</span>';

                                    }else{ echo   $record->title;  }
                                ?>
                            </td>
                            <td>
                                {{ $record->description }}
                            </td>
                            <td>
                                {{ date(Config::get('constants.ADMIN_LISTING_DATE_FORMAT'), strtotime($record->created_at)) }}
                            </td>
                            <td>
                                @can('NOTIFICATION_DELETE')
                                <a class="btn btn-primary delete_any_item" data-id="{{ URL::to('admin/list-notification/delete/' . $record->id) }}" title="{{ trans('Delete') }}" href="javascript::void(0);">
                                    <em class="fa fa-trash"></em>
                                </a>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="8" class="text-center noRecord"><strong>{{ "No record found!" }}</strong></td>
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
