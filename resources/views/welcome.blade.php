<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel</title>

        <meta property="og:title" content="<?php echo $meta['title']; ?>">
        <meta property="og:description" content="<?php echo $meta['description']; ?>">

        {{-- <link rel="stylesheet" href="{{ asset('resources/css/app.css') }}"> --}}
    </head>
    <body class="antialiased">
        <div id="app"></div>

        <script>
            window.Laravel = {!! json_encode([
                'isAuthenticated' => Auth::check(),
                'userName' => Auth::check() ? Auth::user()->name : null,
            ]) !!};
        </script>
       @vite('resources/js/app.js')
    </body>
</html>
