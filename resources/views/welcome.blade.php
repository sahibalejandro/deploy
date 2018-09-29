<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name') }}</title>
    </head>
    <body>
        <h1>Dashboard</h1>

        <h2>Sites</h2>
        @foreach ($sites as $site)
            <div>
                {{ $site->name }}
            </div>
        @endforeach

    </body>
</html>
