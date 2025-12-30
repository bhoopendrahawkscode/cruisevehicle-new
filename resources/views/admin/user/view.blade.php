@extends('admin.layouts.default_layout')
@section('content')
@php
use App\Services\CommonService;

@endphp
<div class="header d-flex align-items-center">
    <h1 class="page-header">{{ trans("messages.view") }} {{ trans('messages.user') }}</h1>
    <ol class="breadcrumb ms-auto mb-0">
        <li><a href="{{ Route('admin.dashboard') }}"><em class="fa fa-dashboard"></em>
                {{ trans("messages.dashboard") }}</a></li>
        <li><a href="{{route('admin.listUsers')}}"> {{ trans('messages.user') }} {{ trans('messages.list') }}</a></li>
        <li class="active">{{ trans("messages.view") }}</li>
    </ol>
</div>
<div id="page-inner">
    <div class=" panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="form-group d-flex gap-3">
                    <a href="{{ route('admin.listUsers')}}" class="btn px-sm-5 font-semibold border_btn">
                        <em class="icon-refresh"></em> {{ trans("messages.back") }}</a>
                </div>
            </div>


            <div class="row mt-3">
                <div class="col-2 ">
                    <strong>{{ trans('messages.fullName') }}</strong>
                </div>
                <div class="col-4 text-start">
                    {{$data['full_name']}}
                </div>
                <div class="col-2 ">
                    <strong>{{ trans('messages.email') }}</strong>
                </div>
                <div class="col-4 text-start">
                    {{$data['email']}}
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-2 ">
                    <strong>{{ trans('messages.phoneNo') }}</strong>
                </div>
                <div class="col-4 text-start">
                    {{ !empty($data->country_code) ? '+'.$data->country_code : '-' }}
                    {{ !empty($data->mobile_no) ? $data->mobile_no : '-' }}
                </div>
                <div class="col-2 ">
                    <strong>{{ trans('messages.createdOn') }}</strong>
                </div>
                <div class="col-4 text-start">
                    {{ date(Config::get('constants.ADMIN_LISTING_DATE_FORMAT'), strtotime($data->created_at)) }}
                </div>
            </div>


        </div>
    </div>
</div>
@include('admin.tabSelected')

@stop
