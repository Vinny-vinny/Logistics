<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap-toggle.min.css')}}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('assets/fonts/font-awesome.min.css')}}">

    <!-- Ionicons -->
    <link rel="stylesheet" href="{{asset('assets/css/ionicons.min.css')}}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('assets/css/AdminLTE.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/_all-skins.min.css')}}">

    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('assets/css/_all.css')}}">

    <link rel="stylesheet" href="{{asset('assets/css/select2.min.css')}}">

    <!-- Ionicons -->
    <link rel="stylesheet" href="{{asset('assets/css/ionicons.min.css')}}">
    <link rel="stylesheet"
          href="{{asset('assets/css/bootstrap-datetimepicker.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/jquery.dataTables.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/jquery-ui.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/lib/print.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/jquery.timepicker.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/lib/bootstrap-datepicker.css')}}">
    <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet">
    <!-- Google Font -->
    <link rel="stylesheet"
          href="{{asset('assets/css/css.css')}}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
        .img-circle{
            border-radius: 50% !important;
        }
    </style>

    @yield('css')
</head>
