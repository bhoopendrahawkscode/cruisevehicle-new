@extends('admin.layouts.default_layout')
@section('content')
    <?php use App\Constants\Constant; ?>

    <div class="header d-flex align-items-center">
        <h1 class="page-header">
            {{ trans('messages.edit') }} {{ trans('messages.coupon') }}
        </h1>
        <ol class="breadcrumb ms-auto mb-0">
            <li><a href="{{ Route('admin.dashboard') }}"><em class="fa fa-dashboard"></em>
                    {{ trans('messages.dashboard') }}</a></li>
            <li class="active"><a href="{{ Route('admin.coupons.list') }}"><em class="fa fa-ticket"></em>
                    {{ trans('messages.coupon') }} {{ trans('messages.list') }} </a></li>
            <li class="active">{{ trans('messages.edit') }}</li>
        </ol>
    </div>
    <div id="page-inner">
        <div class="panel panel-default">
            <div class="panel-body">
              {{ html()->modelForm($coupon, 'POST')->route('admin.coupons.update',$coupon)
              ->attributes(['id'=>'couponForm','autocomplete' => 'off','enctype'=>'multipart/form-data'])->open() }}
                
              @include('admin.coupon.form')
                {{ html()->closeModelForm() }}
            </div>
        </div>
    </div>
@endsection
