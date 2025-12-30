@extends('admin.layouts.default_layout')

@section('content')
    <?php use App\Services\GeneralService; ?>

    <div class="header d-flex align-items-center">
        <h1 class="page-header">
            {{ trans('messages.view') }} {{ trans('messages.transmissionTypeDetails') }}
        </h1>
        <ol class="breadcrumb ms-auto mb-0">
            <li><a href="{{ Route('admin.dashboard') }}"><em class="fa fa-dashboard"></em> {{ trans('messages.dashboard') }}</a></li>
            <li><a href="{{ route($listRoute) }}">{{ trans('messages.transmissionTypeList') }}</a></li>
            <li class="active">{{ trans('messages.view') }}</li>
        </ol>
    </div>
    <div id="page-inner">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <h3>{{ trans('messages.transmissionTypeDetails') }}</h3>
                        <table class="table table-bordered mt-3">
                            <tr>
                                <th>{{ trans('messages.name') }}</th>
                                <td>{{ $transmissiontype->name }}</td>
                            </tr>

                            <tr>
                                <th>{{ trans('messages.status') }}</th>
                                <td>
                                    @if ($transmissiontype->status == 1)
                                        {{ trans('messages.active') }}
                                    @else
                                        {{ trans('messages.inactive') }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('messages.createdOn') }}</th>
                                <td>{{ date(Config::get('constants.ADMIN_LISTING_DATE_FORMAT'), strtotime($transmissiontype->created_at)) }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('messages.updatedOn') }}</th>
                                <td>{{ date(Config::get('constants.ADMIN_LISTING_DATE_FORMAT'), strtotime($transmissiontype->updated_at)) }}</td>
                            </tr>
                            <!-- Add more fields as needed -->
                        </table>
                        <a href="{{ route($listRoute) }}" class="btn btn-primary">{{ trans('messages.back') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
