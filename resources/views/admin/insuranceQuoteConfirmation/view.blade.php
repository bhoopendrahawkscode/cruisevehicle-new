@extends('admin.layouts.default_layout')

@section('content')
    <?php use App\Services\GeneralService; ?>

    <div class="header d-flex align-items-center">
        <h1 class="page-header">
            {{ trans('messages.view') }} {{ trans('messages.insuranceQuoteDetails') }}
        </h1>
        <ol class="breadcrumb ms-auto mb-0">
            <li><a href="{{ Route('admin.dashboard') }}"><em class="fa fa-dashboard"></em> {{ trans('messages.dashboard') }}</a></li>
            <li><a href="{{ route($listRoute) }}">{{ trans('messages.insuranceQuoteList') }} </a></li>
            <li class="active">{{ trans('messages.view') }}</li>
        </ol>
    </div>
    <div id="page-inner">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <h3>{{ trans('messages.insuranceQuoteDetails') }}</h3>
                        <table class="table table-bordered mt-3">
                            <tr>
                                <th>{{ trans('messages.requestReferenceNumber') }}</th>
                                <td>{{ $insuranceQuote->request_reference_number }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('messages.nameOfUser') }}</th>
                                <td>{{ $insuranceQuote->name_of_user }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('messages.vehicleValueToBeInsured') }}</th>
                                <td>{{ $insuranceQuote->vehicle_value_to_be_insured }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('messages.premiumProposed') }}</th>
                                <td>{{ $insuranceQuote->premium_proposed }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('messages.comment') }}</th>
                                <td>{{ $insuranceQuote->comment }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('messages.insuranceCoverType') }}</th>
                                <td>
                                    @if ($insuranceQuote->insurance_cover_type == 1)
                                        {{ trans('messages.coverTypeOne') }}
                                    @elseif ($insuranceQuote->insurance_cover_type == 2)
                                        {{ trans('messages.coverTypeTwo') }}
                                    @else
                                        {{ trans('messages.notAvailable') }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('messages.insurancePeriodRequested') }}</th>
                                <td>{{ $insuranceQuote->insurance_period_requested }} months</td>
                            </tr>
                            <tr>
                                <th>{{ trans('messages.status') }}</th>

                                <td>
                                    @switch($insuranceQuote->status)
                                        @case(1)
                                            {{ trans('messages.Premium Quoted') }}
                                            @break
                                        @case(2)
                                            {{ trans('messages.Awaiting Reply') }}
                                            @break
                                        @case(3)
                                            {{ trans('messages.Declined') }}
                                            @break
                                        @default
                                            {{ trans('messages.notAvailable') }}
                                    @endswitch
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('messages.createdOn') }}</th>
                                <td>{{ date(Config::get('constants.ADMIN_LISTING_DATE_FORMAT'), strtotime($insuranceQuote->created_at)) }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('messages.updatedOn') }}</th>
                                <td>{{ date(Config::get('constants.ADMIN_LISTING_DATE_FORMAT'), strtotime($insuranceQuote->updated_at)) }}</td>
                            </tr>
                        </table>
                        <a href="{{ route($listRoute) }}" class="btn btn-primary">{{ trans('messages.back') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
