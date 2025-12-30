@extends('admin.layouts.default_layout')

@section('content')
    <div class="header d-flex align-items-center">
        <h1 class="page-header">
            {{ trans('messages.reply') }}
        </h1>
        <ol class="breadcrumb ms-auto mb-0">
            <li><a href="{{ route('admin.dashboard') }}"><em class="fa fa-dashboard"></em> {{ trans('messages.dashboard') }}</a></li>
            <li><a href="{{ route('admin.insuranceQuoteReply.list') }}">{{ trans('messages.insuranceQuoteReplyList') }}</a></li>
            <li class="active">{{ trans('messages.reply') }}</li>
        </ol>
    </div>
    <div id="page-inner">
        <div class="panel panel-default">
            <div class="panel-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                {{ html()->modelForm(null, 'POST', route('admin.insuranceQuoteReply.replySubmit'))
                    ->attributes(['role' => 'form', 'id' => 'replyForm', 'autocomplete' => 'off'])
                    ->open() }}

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="requestReferenceNumber">{{ trans('messages.requestReferenceNumber') }}</label>
                                <input type="text" id="requestReferenceNumber" name="request_reference_number" class="form-control" value="{{ $insuranceQuote->request_reference_number }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for="userName">{{ trans('messages.nameOfUser') }}</label>
                                <input type="text" id="userName" class="form-control" value="{{ $insuranceQuote->user->full_name }}" readonly>
                                <input type="hidden" name="user_id" value="{{ $insuranceQuote->user_id }}">
                            </div>

                            <div class="form-group">
                                <label for="vehicleValueToBeInsured">{{ trans('messages.vehicleValueToBeInsured') }}</label>
                                <input type="text" id="vehicleValueToBeInsured" name="vehicle_value_to_be_insured" class="form-control" value="{{ $insuranceQuote->vehicle_value_to_be_insured }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="premiumProposed">{{ trans('messages.premiumProposed') }}<span class="red_lab"> *</span></label>
                                <input type="text" name="premium_proposed" id="premiumProposed" class="form-control" value="{{ old('premium_proposed') }}">
                                @error('premium_proposed')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="status">{{ trans('messages.status') }}<span class="red_lab"> *</span></label>
                                {{ html()->select('status', [
                                    '1' => trans('messages.Premium Quoted'),
                                    '2' => trans('messages.Awaiting Reply'),
                                    '3' => trans('messages.Declined')
                                ], old('status'))->attributes(['class' => 'form-control select2']) }}
                                @error('status')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="comment">{{ trans('messages.comment') }}</label>
                                <textarea name="comment" id="comment" class="form-control">{{ old('comment') }}</textarea>
                                @error('comment')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">{{ trans('messages.submit') }}</button>
                    <a href="{{ route('admin.insuranceQuoteReply.list') }}" class="btn btn-secondary">{{ trans('messages.back') }}</a>

                {{ html()->closeModelForm() }}
            </div>
        </div>
    </div>

    @push('scripts')
        <!-- Include jQuery Validation Plugin -->
        <script src="https://cdn.jsdelivr.net/jquery.validation/1.19.5/jquery.validate.min.js"></script>
        <script>
            $(function() {
                $("#replyForm").validate({
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
                            required: true,
                            number: true
                        }
                    },
                    messages: {
                        comment: {
                            maxlength: "{{ trans('messages.max500') }}"
                        },
                        status: {
                            number: "{{ trans('messages.statusNumber') }}"
                        }
                    },
                    errorClass: "text-danger",
                    highlight: function(element) {
                        $(element).closest('.form-group').addClass('has-error');
                    },
                    unhighlight: function(element) {
                        $(element).closest('.form-group').removeClass('has-error');
                    }
                });
            });
        </script>
    @endpush
@stop
