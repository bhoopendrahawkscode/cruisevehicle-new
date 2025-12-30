@extends('admin.layouts.default_layout')

@section('content')
    <?php use App\Services\GeneralService;
    use App\Constants\Constant;
    ?>

    <div class="header d-flex align-items-center">
        <h1 class="page-header">
            {{ $title }} {{ trans('messages.list') }}
        </h1>
        <ol class="breadcrumb ms-auto mb-0">
            <li><a href="{{ Route('admin.dashboard') }}"><em class="fa fa-dashboard"></em> {{ trans('messages.dashboard') }}
                </a></li>
            <li class="active">{{ $title }} {{ trans('messages.list') }}</li>
        </ol>
    </div>
    <x-media-manager :result="$result" :sortBy="$sortBy" :queryString="$query_string" :listRoute="$listRoute" :order="$order" />

    @stop
