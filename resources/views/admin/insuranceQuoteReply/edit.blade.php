@extends('admin.layouts.default_layout')
@section('content')
<?php use \App\Constants\Constant; ?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<div class="header d-flex align-items-center">
    <h1 class="page-header">
        {{ trans('messages.editInsuranceQuote') }}
    </h1>
    <ol class="breadcrumb ms-auto mb-0">
        <li><a href="{{ Route('admin.dashboard') }}"> <em class="fa fa-dashboard"></em>{{ trans('messages.dashboard') }}</a></li>
        <li><a href="{{ route('admin.insuranceQuoteReply.list') }}">{{ trans('messages.insuranceQuoteReplyList') }}</a></li>
        <li class="active">{{ trans("messages.edit") }}</li>
    </ol>
</div>
<div id="page-inner">
    <div class="panel panel-default">
        <div class="panel-body">
            {{ html()->modelForm($model, 'POST', route('admin.insuranceQuoteReply.update', $model->id))
            ->attributes(['role' =>'form','id'=>"insuranceQuoteForm",'autocomplete' => 'off'])->open() }}
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="request_reference_number">{{ trans('messages.requestReferenceNumber') }}</label>
                        {{ html()->text('request_reference_number', $model->request_reference_number)->attributes(['class' => 'form-control', 'readonly' => 'readonly']) }}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="user_name">{{ trans('messages.nameOfUser') }}</label>
                        {{ html()->text('user_name', $model->user->full_name)->attributes(['class' => 'form-control', 'readonly' => 'readonly']) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="vehicle_value_to_be_insured">{{ trans('messages.vehicleValueToBeInsured') }}</label>
                        {{ html()->text('vehicle_value_to_be_insured', $model->vehicle_value_to_be_insured)->attributes(['class' => 'form-control', 'readonly' => 'readonly']) }}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="premium_proposed">{{ trans('messages.premiumProposed') }} <span class="red_lab"> *</span></label>
                        {{ html()->text('premium_proposed', $model->premium_proposed)->attributes(['class' => 'form-control', 'placeholder' => trans("messages.premiumProposed")]) }}
                        @if ($errors->has('premium_proposed'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('premium_proposed') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="comment">{{ trans('messages.comment') }}</label>
                        {{ html()->textarea('comment', $model->comment)->attributes(['class' => 'form-control', 'placeholder' => trans("messages.comment")]) }}
                        @if ($errors->has('comment'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('comment') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="status">{{ trans('messages.status') }} <span class="red_lab"> *</span></label>
                        {{ html()->select('status', [
                            '1' => trans('messages.Premium Quoted'),
                            '2' => trans('messages.Awaiting Reply'),
                            '3' => trans('messages.Declined')
                        ], $model->status)->attributes(['class' => 'form-control select2']) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group d-flex gap-3">
                        <button type="submit" class="btn theme_btn bg_theme font-semibold border-0 fs-6 px-sm-5">{{ trans('messages.submit') }}</button>
                        <a href="{{ route('admin.insuranceQuote.list') }}" class="btn px-sm-5 font-semibold border_btn"><em class="icon-refresh"></em> {{ trans("messages.cancel") }}</a>
                    </div>
                </div>
            </div>
            {{ html()->closeModelForm() }}
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.select2').select2();

        if($('#status').val() == '1'){
            setTimeout(function(){ $('.select2').val("1").trigger('change') }, 10);
        }
    });
</script>
@if(env('ENABLE_CLIENT_VALIDATION'))
<script>
    $(function() {
        $("#insuranceQuoteForm").validate({
            rules: {
                premium_proposed: {
                    required: true,
                    number: true,
                    min: 0
                },
                comment: {
                    maxlength: 500
                },
                status: {
                    required: true
                }
            },
            messages: {
                comment: {
                    maxlength: "{{ trans('messages.max500') }}"
                },
            },
            errorClass: "help-inline",
            errorElement: "span",
            highlight: function(element, errorClass, validClass) {
                $(element).parents('.form-group').addClass('error');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).parents('.form-group').removeClass('error');
                $(element).parents('.form-group').addClass('success');
            }
        });
    });
</script>
@endif
@stop
