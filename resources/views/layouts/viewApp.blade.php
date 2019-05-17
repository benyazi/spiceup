<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        Screen page
    </title>
    <!-- Scripts -->
    <script src="{{ asset('js/screenView/app.js') }}" defer></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <!-- Styles -->
    <link href="{{ asset('css/screenView/screenView.css') }}" rel="stylesheet">
</head>
<body>
    <div id="screen-view-app">
    @yield('content')
    </div>
    <!--
    *************************************************
    *************************************************
    *************************************************
    *************************************************
    ******************* PRIVET KOTIK ****************
    ************ Если есть что сказать  *************
    ***************** Пиши в телегу *****************
    ******************** @ben_yazi ******************
    *************************************************
    *************************************************
    *************************************************
    *************************************************
     -->
</body>
</html>
