@extends('admin.layouts.default_layout')
@section('content')
@php
use App\Services\CommonService;

@endphp
<div class="header d-flex align-items-center">
    <h1 class="page-header">{{ trans("messages.view") }} {{ trans('messages.help_support') }}</h1>
    <ol class="breadcrumb ms-auto mb-0">
        <li><a href="{{ Route('admin.dashboard') }}"><em class="fa fa-dashboard"></em>
                {{ trans("messages.dashboard") }}</a></li>
        <li><a href="{{route('admin.contactList')}}"> {{ trans('messages.help_support') }}</a></li>
        <li class="active">{{ trans("messages.view") }}</li>
    </ol>
</div>
<div id="page-inner">
    <div class=" panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="form-group d-flex gap-3">
                    <a href="{{ route('admin.contactList')}}" class="btn px-sm-5 font-semibold border_btn">
                        <em class="icon-refresh"></em> {{ trans("messages.back") }}</a>
                </div>
            </div>
            <div class="row">

                <div class="col-2 ">
                    <strong>{{ trans('messages.contactName') }}</strong>
                </div>
                <div class="col-4 text-start">
                    {{$data['name']}}
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-2 ">
                <strong>{{ trans('messages.phoneNo') }}</strong>
                </div>
                <div class="col-4 text-start">
                    {{$data['mobile_no']}}
                </div>
                <div class="col-2 ">
                    <strong>{{ trans('messages.email') }}</strong>
                </div>
                <div class="col-4 text-start">
                    {{$data['email']}}
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-2 ">
                <strong>{{ trans('messages.subject') }}</strong>
                </div>
                <div class="col-4 text-start">
                    {{$data['subject']}}
                </div>
                <div class="col-2 ">
                    <strong>{{ trans('Description') }}</strong>
                </div>
                <div class="col-4 text-start">
                    {{$data['message']}}
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-2 ">
                    <strong>{{ trans('messages.created_at') }}</strong>
                </div>
                <div class="col-4 text-start">
                    {{ date(Config::get('constants.ADMIN_LISTING_DATE_FORMAT'), strtotime($data->created_at)) }}
                </div>
            </div>


        </div>
    </div>


    <div class="panel panel-default">
        <div class="panel-body">
            {{ html()->modelForm(null, 'POST')->route('admin.contact.sendReply')
             ->attributes(['id'=>"sendSupportReport",'class'=>'form-inline','role'=>'form','autocomplete' => 'off'])->open() }}
             {{ html()->hidden('id',$id)->attributes(['class' => 'form-control','placeholder' => trans("messages.Title")]) }}

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group  <?php echo ($errors->first('message')) ? 'has-error' : ''; ?>">
                        <label for="message" class='message_data'>{{trans('messages.support_message')}} <span class="red_lab">*</span></label>
                        {{ html()->textarea('message',null)->attributes(['id'=>'languages_body','class'=>'form-control languages_body','maxlength'=>'5000']) }}
                        <div class="error-message msg_error help-inline">
                            <?php echo $errors->first('message'); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="header" class="font-medium fs-6 mb-3">Select Ticket Status</label>
                        {{ html()->select('ticket_status',$ticketStatusList)->attributes(['class' => 'form-control  formValidate','required'=>'required']) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <button type="submit" class="btn theme_btn bg_theme font-semibold border-0 fs-6 px-sm-5">{{ trans('Reply') }}</button>
                        <a href="{{ route('admin.contactList')}}" class="btn px-sm-5 font-semibold border_btn">
                            <em class="icon-refresh"></em> {{ trans("Close") }}</a>
                    </div>

                </div>
            </div>
            {{ html()->closeModelForm() }}
        </div>
    </div>

    @if(count($repliedMessages)>0)
    <div class="panel panel-default" style="border-radius: 8px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);">
        <div class="panel-body" style="padding: 20px;">
            <!-- Header Section -->
            <div style="background-color: #1E1F1F; border-radius: 8px 8px 0 0; padding: 15px;">
                <strong style="text-align: center; font-weight: bold; color: white; margin: 0;">Message History</strong>
            </div>
            <!-- Content Section -->
            <div class="row" style="margin-top: 20px;">
                @foreach($repliedMessages as $key => $message)
                <div class="col-md-6" style="margin-bottom: 20px;">
                    <div style="background-color: #f8f9fa; border-radius: 5px; padding: 20px; border-left: 5px solid #1E1F1F;">
                        <p style="font-size: 16px; color: #555;">
                            {{ $message['description'] }}
                            <br>
                            <span style="font-size: 14px; color: #999;">(Reply By - {{ $message['replyBy'] }}) - {{ date('d-M-Y', strtotime($message['created_at'])) }}</span>
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>



@endif



</div>
@include('admin.tabSelected')
<script>
    $(document).ready(function() {
          $('.select2').select2();
      });
  $(function() {
      // Form Validation
      $("#sendSupportReport").validate({
          rules: {

              message: {
                  required: true,
                  minlength: 2,
                  notNumber: true,
              },

              ticket_status: {
                  required: true,
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
          },


      });
  });
</script>


@stop
