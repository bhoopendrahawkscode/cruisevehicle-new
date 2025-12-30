@extends('admin.layouts.default_layout')
@section('content')
    <?php
    use App\Constants\Constant;
    use Illuminate\Support\Str;
    ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />




    <div class="header d-flex align-items-center">
        <h1 class="page-header">
            {{ trans('messages.subAdminActivityLogs') }} {{ trans('messages.view') }}

        </h1>
        <ol class="breadcrumb ms-auto mb-0">
            <li><a href="{{ Route('admin.dashboard') }}"><em class="fa fa-dashboard"></em> {{ trans('messages.dashboard') }}
                </a></li>
            <li><a href="{{ Route('admin.sub_admin.activity.logs.list') }}"><em class="fa fa-history"></em>
                    {{ trans('messages.subAdminActivityLogs') }} {{ trans('messages.list') }}
                </a></li>
            <li class="active">{{ $activity->log_name }}</li>
        </ol>
    </div>

    <div id="page-inner">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row mb-3">
                    <div class=" d-flex gap-3">
                        <a href="{{ route('admin.sub_admin.activity.logs.list') }}"
                            class="btn px-sm-5 font-semibold border_btn">
                            <em class="icon-refresh"></em> {{ trans('messages.back') }}</a>
                    </div>
                </div>
           

                @include('admin.actvitylogs.information_of_activity_log')

            </div>
        </div>
    </div>
    </div>
@endsection
