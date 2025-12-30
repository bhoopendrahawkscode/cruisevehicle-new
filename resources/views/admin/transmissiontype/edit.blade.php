@extends('admin.layouts.default_layout')
@section('content')
    <?php use App\Constants\Constant; ?>

    <div class="header d-flex align-items-center">
        <h1 class="page-header">
            {{ trans('messages.edit') }} {{$title}}
        </h1>
        <ol class="breadcrumb ms-auto mb-0">
            <li><a href="{{ Route('admin.dashboard') }}"><em class="fa fa-dashboard"></em>
                    {{ trans('messages.dashboard') }}</a></li>
            <li class="active"><a href="{{ Route($listRoute) }}"><em class="fa fa-ticket"></em>
                    {{$title}} {{ trans('messages.list') }} </a></li>
            <li class="active">{{ trans('messages.add') }}</li>
        </ol>
    </div>
    <div id="page-inner">
        <div class="panel panel-default">
            <div class="panel-body">
                {{ html()->modelForm($model, 'POST')->route('admin.transmissiontype.update',$model)
            ->attributes(['id'=>'transmissiontypeForm','autocomplete' => 'off','enctype'=>'multipart/form-data'])->open() }}
              
              @include('admin.transmissiontype.form')
                {{ html()->closeModelForm() }}
            </div>
        </div>
    </div>
@endsection
