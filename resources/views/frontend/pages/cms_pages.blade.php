@extends('frontend.default_layout')
@section('content')
<section class="breadcrumb_sec">
    <div class="container">
        <ol class="breadcrumb">
          <li class="breadcrumb-item d-flex align-items-center"><a href="">Home</a></li>
          <li class="breadcrumb-item active d-flex align-items-center">{!! $data->title !!}</li>
        </ol>
    </div>
</section>
<section class="content_block py-4 py-lg-5">
    <div class="container">
        <h1 class="inner_heading_title pb-4 mb-5">{!!   $data->title  !!}</h1>
        {!! $data->body !!}
    </div>
</section>

@endsection
