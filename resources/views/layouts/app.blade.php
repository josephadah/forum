<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    {{-- scripts --}}
    <script>
        window.App = {!! json_encode([
                'csrfToken' => csrf_token(),
                'user' => Auth::user(),
                'signedIn' => Auth::check()
            ]); !!};
    </script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @yield('header')

    <style>
        body {
            margin-bottom: 20px;
        }
        .level {
            display: flex;
        }
        .flex {
            flex: 1;
            margin-top: 0;
        }
        .mr-1 {
            margin-right: 1em;
        }
        [v-cloak] {
            display: none;
        }
        .ml-a {margin-left: auto;}
    </style>
</head>
<body>
    <div id="app">
        @include('layouts.nav')

        @yield('content')

        <flash message="{{ session('flash') }}"></flash>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
