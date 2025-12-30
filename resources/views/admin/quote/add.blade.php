@extends('admin.layouts.default_layout')
@php
use App\Services\CommonService;

@endphp
@section('content')
<div class="header d-flex align-items-center">
    <h1 class="page-header">
        {{ trans("messages.add") }} {{$title}}
    </h1>
    <ol class="breadcrumb ms-auto mb-0">
        <li><a href="{{ Route('admin.dashboard') }}"><em class="fa fa-dashboard"></em>
                {{ trans("messages.dashboard") }}</a></li>
        <li><a href="{{route($listRoute)}}">{{$title}} {{ trans("messages.list") }}</a></li>
        <li class="active">{{ trans("messages.add") }}</li>
    </ol>
</div>
<div id="page-inner">
    <div class="panel panel-default">
        <div class="panel-body">
            <?php if(!empty($allDays)) { ?>
            {{ html()->modelForm(null, 'POST')->route($saveRoute)
                ->attributes(['id'=>$formId,'autocomplete' => 'off','enctype'=>'multipart/form-data'])->open() }}
                @include($formPath)
                <div class="form-group d-flex gap-3">
                    <button type="submit" id="btnSubmit" class="btn theme_btn bg_theme font-semibold border-0 fs-6 px-sm-5">
                        {{trans("messages.submit")}}
                    </button>
                    <a href="{{ route($listRoute)}}" class="btn px-sm-5 font-semibold border_btn">
                        <em class="icon-refresh"></em> {{trans("messages.cancel")}}</a>
                </div>
                {{ html()->closeModelForm() }}
            <?php }else{ ?>
                {{trans("messages.noDay")}}
                <br/><br/><br/>
                <div class="form-group d-flex gap-3">
                    <a href="{{ route($listRoute)}}" class="btn px-sm-5 font-semibold border_btn">
                        <em class="icon-refresh"></em> {{ trans("messages.back") }}</a>
                </div>
           <?php } ?>
        </div>
    </div>
</div>
@include('admin.tabSelected')

@stop
