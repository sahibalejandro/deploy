<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link href="https://fonts.googleapis.com/css?family=Lobster&text=Deployr" rel="stylesheet">
        <link rel="stylesheet"
            href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
            integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
            crossorigin="anonymous"
        >
        <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
        <title>{{ config('app.name') }}</title>
    </head>
    <body class="bg-light">
        <div id="app">
            <the-header></the-header>
            <alert-message></alert-message>
            <div class="container rounded bg-white shadow-sm mt-4 py-3">
                <router-view></router-view>
            </div>
            <the-footer></the-footer>
            <portal-target name="modal-outlet"></portal-target>
        </div>
        <script src="{{ mix('/js/app.js') }}"></script>
    </body>
</html>
