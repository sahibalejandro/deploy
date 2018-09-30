<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name') }}</title>
    </head>
    <body>
        <div id="app">
            <the-header></the-header>
            <alert-message></alert-message>
            <router-view></router-view>
            <the-footer></the-footer>
        </div>
        <script src="/js/app.js"></script>
    </body>
</html>
