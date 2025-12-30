@extends('admin.layouts.default_layout')
@section('content')
    <?php use App\Constants\Constant; ?>

    <div class="header d-flex align-items-center">
        <h1 class="page-header">
           Bulk Upload Brand & Model
        </h1>
        <ol class="breadcrumb ms-auto mb-0">
            <li><a href="{{ Route('admin.dashboard') }}"><em class="fa fa-dashboard"></em>
                    {{ trans('messages.dashboard') }}</a></li>
                    
            <li class="active">Bulk Upload Brand & Model</li>
        </ol>
    </div>
    <div id="page-inner">
        <div class="panel panel-default">
            <div class="panel-body">
                {{ html()->modelForm(null, 'POST')->route('admin.brand.import')
            ->attributes(['id'=>'brandForm','autocomplete' => 'off','enctype'=>'multipart/form-data'])->open() }}
        @csrf
        <div class="col-md-6">
        <div class="form-group">
        <input type="file" name="file" required>
        </div>
        </div>
        <div class="form-group d-flex gap-3">
            <button type="submit"
                class="btn theme_btn bg_theme font-semibold border-0 fs-6 px-sm-5">{{ trans('messages.submit') }}</button>
                <a href="{{route($listRoute)}}" class="btn px-sm-5 font-semibold border_btn"><em
                        class="icon-refresh"></em> {{ trans('messages.cancel') }}</a>
            </div>
   

                {{ html()->closeModelForm() }}
            </div>
        </div>
    </div>
@endsection
