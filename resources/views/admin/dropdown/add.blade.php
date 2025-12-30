@extends('admin.layouts.default_layout')
@php
use App\Services\CommonService;
@endphp
@section('content')
<script type="text/javascript" integrity="" src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.0.1/ckeditor.js"></script>
<div class="header d-flex align-items-center">
	<h1 class="page-header">
		{{ trans("messages.add") }} {{{ ucwords(__('dropdowns.'.str_replace("_"," ",$type))) }}}
	</h1>
	<ol class="breadcrumb ms-auto mb-0">
		<li><a href="{{ Route('admin.dashboard') }}"><em class="fa fa-dashboard"></em> {{ trans('messages.dashboard') }}</a></li>
		<li class="active"><a href="{{ route('admin.dropdown.index',['name'=>$type]) }}">{{{ ucwords(__('dropdowns.'.str_replace("_"," ",$type))) }}} </a></li>
		<li class="active">{{ trans("messages.add") }}</li>
	</ol>
</div>
<section id="page-inner">
	<div class="panel panel-default">
        {{ html()->modelForm(null, 'POST', route('admin.dropdown.save', ['name' => $type]))
        ->attributes(['id'=>'dropDownForm','autocomplete' => 'off','enctype'=>'multipart/form-data'])
        ->open() }}
		<div class="panel-body">
			@include('admin.dropdown.form')


			<div class="form-group d-flex gap-3">
				<button type="submit" class="btn theme_btn bg_theme font-semibold border-0 fs-6 px-sm-5" id="btnSubmit">{{ trans('messages.submit') }}</button>
				<a href="{{ url('/admin/dropdown/index/'.$type) }}" class="btn px-sm-5 font-semibold border_btn">{{ trans("messages.cancel") }}</a>
			</div>
		</div>
        {{ html()->closeModelForm() }}
	</div>
</section>
@include('admin.tabSelected')
@stop
