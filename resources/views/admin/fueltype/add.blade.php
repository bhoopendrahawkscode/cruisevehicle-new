@extends('admin.layouts.default_layout')
@section('content')
    <?php use App\Constants\Constant; ?>

    <div class="header d-flex align-items-center">
        <h1 class="page-header">
            {{ trans('messages.add') }} {{$title}}
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
                {{ html()->modelForm(null, 'POST')->route('admin.fueltype.save')
            ->attributes(['id'=>'fueltypeForm','autocomplete' => 'off','enctype'=>'multipart/form-data'])->open() }}
              
              @include('admin.fueltype.form')
                {{ html()->closeModelForm() }}
            </div>
        </div>
    </div>
@endsection
