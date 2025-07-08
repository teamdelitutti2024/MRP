@php
    $theme = 'light';
    $logo = 'new_logo_black';
    if (date('G') < 7 || date('G') > 17) {
        $theme = 'dark';
        $logo = 'new_logo_white';
    }
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Deli Tutti ERP</title>

    <link href="{{ url('/css/' . $theme . '/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ url('/css/' . $theme . '/css/brain-theme.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ url('/css/' . $theme . '/css/styles.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ url('/css/' . $theme . '/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ url('/css/common.css') }}" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Cuprum' rel='stylesheet' type='text/css'>

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>

    <script type="text/javascript" src="{{ url('js/plugins/forms/uniform.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/plugins/forms/inputmask.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/plugins/forms/inputlimit.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/plugins/forms/validate.min.js') }}"></script>

    <script type="text/javascript" src="{{ url('js/plugins/interface/prettify.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/plugins/interface/collapsible.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/plugins/interface/datatables.min.js') }}"></script>

    <script type="text/javascript" src="{{ url('js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/application_blank.js') }}"></script>

    <script type="text/javascript" src="{{ url('js/custom.js') }}"></script>

    @yield('header')

</head>

<body>

    <!-- Navbar -->
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <div class="hidden-lg pull-right">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-right">
                        <span class="sr-only">Toggle navigation</span>
                        <i class="fa fa-chevron-down"></i>
                    </button>

                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar">
                        <span class="sr-only">Toggle sidebar</span>
                        <i class="fa fa-bars"></i>
                    </button>
                </div>

                @include('layouts.elements.topbar_left')
            </div>

            @include('layouts.elements.topbar_right')
        </div>
    </div>
    <!-- /navbar -->


    <!-- Page header -->
    <div class="container-fluid">
        <div class="page-header">
            <div class="logo"><a href="index.html" title=""><img src="{{ url('/img/' . $logo . '.png') }}" alt="" style="max-height: 100px;"></a></div>
            @include('layouts.elements.header_nav_blocks')
        </div>
    </div>
    <!-- /page header -->


    <!-- Page container -->
    <div class="page-container container-fluid">
        
        <!-- Sidebar -->
        <div class="sidebar collapse">
            @include('layouts.elements.main_menu')
        </div>
        <!-- /sidebar -->

    
        <!-- Page content -->
        <div class="page-content">

            @yield('content')

            <!-- Footer -->
            <div class="footer">
                &copy; Copyright {{ date('Y') }}. Todos los derechos reservados. It's Brain admin theme by <a href="#" title="">Eugene Kopyov</a> | Programación por Dulce Imperio Factory
            </div>
            <!-- /footer -->

        
        </div>
        <!-- /page content -->

    </div>
    <!-- page container -->

    @yield('footer')

</body>
</html>
