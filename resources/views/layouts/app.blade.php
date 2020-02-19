<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('/images/logo-gaz.png') }}" />
    <link rel="apple-touch-icon" type="image/png" href="{{ asset('/images/logo-gaz.png') }}" />
    <!-- app.js -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/datatable.min.css') }}">
    <!-- bootstrap -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap/bootstrap.css') }}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>
<body >
<div class="adm">

    @include('layouts._navbar')
    @include('layouts._sidebar')

    <main class="py-4 content_section" id="my-app" >
        @include('layouts._flash')
        @yield('content')
    </main>


</div>

<!-- jqeury -->
<script src="{{ asset('js/jquery/jquery.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/jquery/popper.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/bootstrap/bootstrap.min.js') }}"  type="text/javascript"></script>
<script src="{{ asset('js/datatable.min.js') }}" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="{{ asset('js/sweetalert2@9.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/main_o.js') }}"></script>
<script src="{{ asset('js/app.js') }}" defer></script>
@stack('scripts')
</body>
</html>
