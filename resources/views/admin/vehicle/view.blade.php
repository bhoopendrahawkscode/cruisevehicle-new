@extends('admin.layouts.default_layout')
@section('content')
@php
use App\Services\CommonService;
@endphp
<div class="header d-flex align-items-center">
    <h1 class="page-header">{{ trans("messages.view") }} {{ trans('messages.vehicle') }}</h1>
    <ol class="breadcrumb ms-auto mb-0">
        <li><a href="{{ Route('admin.dashboard') }}"><em class="fa fa-dashboard"></em>
                {{ trans("messages.dashboard") }}</a></li>
        <li><a href="{{route('admin.vehicle.list')}}"> {{ trans('messages.vehicle') }} {{ trans('messages.list') }}</a></li>
        <li class="active">{{ trans("messages.view") }}</li>
    </ol>
</div>

<div id="page-inner" class="container-fluid">
    <div class="panel panel-default">
        <div class="panel-body">
            <!-- Back Button -->
            <div class="row mb-4">
                <div class="form-group d-flex gap-3">
                    <a href="{{ route('admin.vehicle.list') }}" class="btn btn-primary px-sm-5 font-semibold d-flex align-items-center">
                        <em class="icon-refresh me-2"></em> {{ trans("messages.back") }}
                    </a>
                </div>
            </div>
    <div class=" row">
        <div class=" col-lg-6">
            <!-- Vehicle Information Section -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><em class="fa fa-car me-2"></em> {{ trans('Vehicle information') }}</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-7 col-lg-3"><strong>{{ trans('messages.owner_name') }}</strong></div>
                        <div class="col-5 col-lg-9">{{ $result['owner_name'] ?? '' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-7 col-lg-3"><strong>{{ trans('Owner address') }}</strong></div>
                        <div class="col-5 col-lg-9">{{ $result->owner_address ?? '' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-7 col-lg-3"><strong>{{ trans('messages.reg_mark') }}</strong></div>
                        <div class="col-5 col-lg-9">{{ $result->reg_no ?? '' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-7 col-lg-3"><strong>{{ trans('messages.brand') }}</strong></div>
                        <div class="col-5 col-lg-9">{{ $result['brand']->name ?? '' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-7 col-lg-3"><strong>{{ trans('messages.model') }}</strong></div>
                        <div class="col-5 col-lg-9">{{ $result['model']->name ?? '' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-7 col-lg-3"><strong>{{ trans('messages.fuel_used') }}</strong></div>
                        <div class="col-5 col-lg-9">{{ $result['fuelType']->name ?? '' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-7 col-lg-3"><strong>{{ trans('messages.engine_capacity') }}</strong></div>
                        <div class="col-5 col-lg-9">{{ $result['engineCapacity']->capacity ?? '' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-7 col-lg-3"><strong>{{ trans('messages.transmission_type') }}</strong></div>
                        <div class="col-5 col-lg-9">{{ $result['transmissionType']->name ?? '' }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <!-- Insurance Information Section -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><em class="fa fa-shield-alt me-2"></em> {{ trans('messages.insurance_information') }}</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-5 col-lg-3"><strong>{{ trans('messages.insurance_company') }}</strong></div>
                        <div class="col-7 col-lg-9">{{ $result['InsuranceCompany']->full_name ?? '' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-5 col-lg-3"><strong>{{ trans('messages.sum_assured_value') }}</strong></div>
                        <div class="col-7 col-lg-9">{{ $result->sum_assured_value?'INR. '.$result->sum_assured_value:'N/A' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-5 col-lg-3"><strong>{{ trans('messages.insurance_expiry_date') }}</strong></div>
                        <div class="col-7 col-lg-9">{{ $result->insurance_expiry_date ?? '' }}</div>
                    </div>
                </div>
            </div>
        </div>

            <!-- Road Tax Section -->
            <div class="col-lg-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><em class="fa fa-road me-2"></em> {{ trans('messages.road_tax') }}</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-12 col-md-3"><strong>{{ trans('messages.road_tax_certificate') }}</strong></div>
                        <div class="col-12 col-md-4">
                            @if($result->RoadCertificate)
                            <a title="Download" target="_blank" href="{{ $result->RoadCertificate }}" class="btn btn-outline-info d-inline-flex align-items-center">
                                <em class="fa fa-download me-2"></em> {{ trans('Download Certificate') }}
                            </a>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12 col-md-3"><strong>{{ trans('Road tax expiry date') }}</strong></div>
                        <div class="col-12 col-md-9">{{ $result->due_renewal_date ?? '' }}</div>
                    </div>
                </div>
            </div>
        </div>
            <div class="col-lg-6">
            <!-- Certificate of Fitness Section -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning text-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><em class="fa fa-certificate me-2"></em> {{ trans('Certificate of Fitness') }}</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-12 col-md-3"><strong>{{ trans('messages.fitness_certificate') }}</strong></div>
                        <div class="col-12 col-md-9">
                            @if($result->FitnessAttachment)
                            <a title="Download" target="_blank" href="{{ $result->FitnessAttachment }}" class="btn btn-outline-warning d-inline-flex align-items-center">
                                <em class="fa fa-download me-2"></em> {{ trans('Download Fitness Certificate') }}
                            </a>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12 col-md-3"><strong>{{ trans('messages.fitness_expiry_date') }}</strong></div>
                        <div class="col-12 col-md-9">{{ $result->fitness_expiry_date ?? '' }}</div>
                    </div>
                </div>
            </div>
        </div>

        </div>
        </div>
    </div>
</div>


@include('admin.tabSelected')
@stop
