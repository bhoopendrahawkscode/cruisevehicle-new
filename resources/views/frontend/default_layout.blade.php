<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="shortcut icon" type="image/x-icon" href="{{ url('img/favicon.ico')}}">
        <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/bootstrap.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/owl.carousel.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/custom.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/developer.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/media.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('public/css/toastr.min.css') }}" >
        <title>{{ $pageTitle ?? '' }}</title>
    </head>

    <body>
        <div class="full_container">
            @include('frontend.include.header')
            <div class="inner_body_section">
                @yield('content')
            </div>
            @include('frontend.include.footer')
        </div>

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script type="text/javascript" src="{{ asset('frontend/js/jquery-3.4.1.js') }}"></script>
        <script type="text/javascript" src="{{ asset('frontend/js/popper.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('frontend/js/bootstrap.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('frontend/js/owl.carousel.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('frontend/js/custom.js') }}"></script>
        <script type="text/javascript" src="{{ asset ('public/js/toastr.min.js') }}"></script>
        @stack('scripts')
    </body>
</html>
