<!DOCTYPE html>
<html lang="en">

<head>
    @yield('header')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        @include('layout.navigation')

        @include('pages.school.inc.aside')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            @yield('content-header')
            <!-- /.content-header -->

            <!-- Main content -->
            @yield('content')
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        @include('layout.footer')

    </div>
    <!-- ./wrapper -->

    @include('layout.script')
</body>

</html>
