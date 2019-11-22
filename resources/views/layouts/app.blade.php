<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!-- Scripts -->
@include('layouts.admin-lite.includes.head')
<body class="sidebar-mini skin-blue">
{{--@include('layouts.admin-lite.includes.top-nav')--}}
<!-- Left side column. contains the logo and sidebar-->
{{--@include('layouts.admin-liloginte.includes.sidebar')--}}
<!-- Content Wrapper. Contains page content -->
          <div id="app">
           <app-home></app-home>
          </div>
<script src="{{ asset('js/app.js') }}" defer></script>
{{--@include('layouts.admin-lite.includes.footer')--}}
@include('layouts.admin-lite.scripts.scripts')
</body>
</html>

