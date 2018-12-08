<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">

   <!-- CSRF Token -->
   <meta name="csrf-token" content="{{ csrf_token() }}">

   <title>{{ config('app.name', 'Pool Server') }}</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">

   <!-- Styles -->
   <link href="{{ asset('css/app.css') }}" rel="stylesheet">
   <link rel="stylesheet" href="/css/admin_panel.css">
   @yield('css')
</head>
<body>
   <div id="app">

       @include('admin_panel.nav')

       <div class="container">

           @include('admin_panel.flashes')

           @yield('content')

       </div>
   </div>

   <!-- Scripts -->
   <script
 src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
 integrity="sha256-3edrmyuQ0w65f8gfBsqowzjJe2iM6n0nKciPUp8y+7E="
 crossorigin="anonymous"></script>
 <script src="{{ asset('js/app.js')}}"></script>
   @yield('js')
</body>
</html>