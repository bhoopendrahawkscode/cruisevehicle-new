@extends('admin.layouts.default_layout')
@section('content')
@php
use App\Services\CommonService;

@endphp
<div class="header d-flex align-items-center">
    <h1 class="page-header">{{ trans("messages.view") }} {{$title}}</h1>
    <ol class="breadcrumb ms-auto mb-0">
        <li><a href="{{ Route('admin.dashboard') }}"><em class="fa fa-dashboard"></em>
                {{ trans("messages.dashboard") }}</a></li>
        <li><a href="{{route($listRoute)}}">{{$title}} {{ trans("messages.list") }}</a></li>
        <li class="active">{{ trans("messages.view") }}</li>
    </ol>
</div>
<div id="page-inner">
    <div class=" panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="form-group d-flex gap-3">
                    <?php if(isset($_GET['refer']) && $_GET['refer'] == 'blog_reports' ){
                        ?>
                            <a href="{{ route("admin.reportPostlist")}}" class="btn px-sm-5 font-semibold border_btn">
                                <em class="icon-refresh"></em> {{ trans("messages.back") }}</a>
                        <?php
                    }elseif(isset($_GET['refer']) && $_GET['refer'] == 'comment_reports' ){
                        ?>
                            <a href="{{ route("admin.reportCommentlist")}}" class="btn px-sm-5 font-semibold border_btn">
                                <em class="icon-refresh"></em> {{ trans("messages.back") }}</a>
                        <?php
                    }else{
                        ?>
                        <a href="{{ route($listRoute)}}" class="btn px-sm-5 font-semibold border_btn">
                            <em class="icon-refresh"></em> {{ trans("messages.back") }}</a>
                        <?php
                    } ?>

                </div>
            </div>
            <div class="row mt-3">
                <div class="col-2 ">
                    <strong>{{ trans('messages.userId') }}</strong>
                </div>
                <div class="col-10 ">
                    {{ $data['userId'] }}
                </div>
            </div>
             <div class="row mt-3">
                <div class="col-2 ">
                    <strong> {{ trans('messages.fullName') }}</strong>
                </div>
                <div class="col-10 ">
                    {{ $data['fullName'] }}
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-2 ">
                    <strong>{{ trans('messages.username') }}</strong>
                </div>
                <div class="col-10 ">
                    {{ $data['userName'] }}
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-2 ">
                    <strong>{{ trans('messages.communityName') }}</strong>
                </div>
                <div class="col-10 ">
                    @if(empty($data->communityName))
                    -
                    @else
                        {{$data->communityName}}
                    @endif
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-2 ">
                    <strong>{{ trans('messages.communityType') }}</strong>
                </div>
                <div class="col-10 ">
                    @if(empty($data->communityType))
                        -
                    @else
                        {{getCommunityName($data->communityType)}}
                    @endif
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-2 ">
                    <strong>{{ trans('messages.content') }}</strong>
                </div>
                <div class="col-10 ">
                   {{$data->content}}
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-2 ">
                    <strong> {{ trans('messages.createdOn') }}</strong>
                </div>
                <div class="col-10 ">
                    {{ date(Config::get('constants.ADMIN_LISTING_DATE_FORMAT'),strtotime($data->created_at)) }}
                </div>
            </div>
        </div>
    </div>
</div>
@include('admin.tabSelected')

@stop
