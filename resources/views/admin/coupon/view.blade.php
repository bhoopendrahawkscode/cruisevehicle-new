@extends('admin.layouts.default_layout')
@section('content')
    <?php use App\Constants\Constant; ?>

    <div class="header d-flex align-items-center">
        <h1 class="page-header">
           {{ trans('messages.coupon') }}   {{ trans('messages.details') }}
        </h1>
        <ol class="breadcrumb ms-auto mb-0">
            <li><a href="{{ Route('admin.dashboard') }}"><em class="fa fa-dashboard"></em>
                    {{ trans('messages.dashboard') }}</a></li>
            <li class="active"><a href="{{ Route('admin.coupons.list') }}"><em class="fa fa-ticket"></em>
                    {{ trans('messages.coupon') }} {{ trans('messages.details') }} </a></li>
            <li class="active">{{$coupon->name}}</li>
        </ol>
    </div>
    <div id="page-inner">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row mb-3">
                    <div class=" d-flex gap-3">
                        <a href="{{ route('admin.coupons.list') }}"
                            class="btn px-sm-5 font-semibold border_btn">
                            <em class="icon-refresh"></em> {{ trans('messages.back') }}</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
