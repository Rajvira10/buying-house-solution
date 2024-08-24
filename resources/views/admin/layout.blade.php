<!DOCTYPE html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    @include('admin.head-links')

    <title>@yield('title', 'NDJ Group')</title>



</head>

<body>

    <div id="layout-wrapper">

        @include('admin.top-header')

        @include('admin.sidebar')

        @yield('content')

        @include('admin.theme-customizer')

    </div>

    @include('admin.scripts')

    @yield('custom-script')

</body>

</html>
