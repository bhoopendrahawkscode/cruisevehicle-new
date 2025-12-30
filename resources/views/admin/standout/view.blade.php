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
        <li><a href="{{route($listRoute)}}">{{$title}}</a></li>
        <li class="active">{{ trans("messages.view") }}</li>
    </ol>
</div>
<div id="page-inner">
    <div class=" panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="form-group d-flex gap-3">
                    <a href="{{ route($listRoute)}}" class="btn px-sm-5 font-semibold border_btn">
                        <em class="icon-refresh"></em> {{ trans("messages.back") }}</a>
                </div>
            </div>
            <div class="mb-0">
                <ul class="nav nav-tabs border-0" role="tabList">
                    @php $i = 0; @endphp
                    @foreach ($languages as $laguageRowTop)
                    <li class="nav-item d-none">
                        <a class="nav-link language_tab {{ $laguageRowTop->locale }}
                                @php if($i == 0){echo " active"; } @endphp" id="tabId{{ $laguageRowTop->id }}" data-bs-toggle="pill" href="#custom-tabs-{{ $laguageRowTop->id }}" role="tab" aria-selected="true">{{ $laguageRowTop->name }}
                        </a>
                    </li>
                    @php $i++; @endphp
                    @endforeach
                </ul>
                <div class="tab-content" id="tab-parent">
                    @php $j = 0; @endphp
                    @foreach ($languages as $languageRow)
                    <div language="{{ $languageRow->id }}" class="tab-pane   fade @php if($j == 0){echo " active show"; } @endphp" id="custom-tabs-{{ $languageRow->id }}" role="tabContent">
                        <div class="row">
                            <div class="col-2 ">
                                <strong>{{ trans('messages.headline') }} <span class="d-none">{{ '(' . $languageRow->name . ')' }}</span></strong>
                            </div>
                            <div class="col-10 ">
                                    <?php   echo !empty($data['name'][$languageRow->id])? $data['name'][$languageRow->id] : '';  ?>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-2 ">
                                <strong>{{ trans('messages.description') }} <span class="d-none">{{ '(' . $languageRow->name . ')' }}</span></strong>
                            </div>
                            <div class="col-10 ">
                                    <?php  echo !empty($data['description'][$languageRow->id])? $data['description'][$languageRow->id] : '';  ?>
                            </div>
                        </div>
                    </div>
                    @php $j++; @endphp
                    @endforeach
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-2 ">
                    <strong> {{ trans('messages.image') }}</strong>
                </div>
                <div class="col-10 ">
                    <img class="border border-1" alt="Image" src="{{ $data['thumbImage'] }}" width="100">
                </div>
            </div>
        </div>
    </div>
</div>
@include('admin.tabSelected')

@stop
