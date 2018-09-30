<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name') }}</title>
    </head>
    <body>
        <p>
            <a href="/">Dashboard</a> -
            Signed as: {{ auth()->user()->name }}
        </p>
        {!! alert() !!}
        @yield('content')
    </body>
</html>
