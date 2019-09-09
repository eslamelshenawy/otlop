<!DOCTYPE html>
<html>
<head>
    @include('backend.component.head')
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

   @include('backend.component.header')
    <!-- Left side column. contains the logo and sidebar -->

       @include('backend.component.main-sidebar')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
       @yield('content-header')

        <!-- Main content -->
        <section class="content">
            @include('backend.component.messages')
            @include('backend.component._session')
           {{-- @include('backend.component._errors')--}}

           @yield('main-content')

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->


       @include('backend.component.footer')

    <!-- Control Sidebar -->
    @include('backend.component.control-sidebar')
    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

@include('backend.component.script')
</body>
</html>
