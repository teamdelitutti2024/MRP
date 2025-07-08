@php
    $theme = 'light';
    if (date('G') < 7 || date('G') > 17) {
        $theme = 'dark';
    }
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Deli Tutti ERP</title>

<link href="{{ url('/css/' . $theme . '/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ url('/css/' . $theme . '/css/brain-theme.css') }}" rel="stylesheet" type="text/css">
<link href="{{ url('/css/' . $theme . '/css/styles.css') }}" rel="stylesheet" type="text/css">
<link href="{{ url('/css/' . $theme . '/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ url('/css/common.css') }}" rel="stylesheet" type="text/css">
<link href='http://fonts.googleapis.com/css?family=Cuprum' rel='stylesheet' type='text/css'>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>

<script type="text/javascript" src="{{ url('js/plugins/interface/collapsible.min.js') }}"></script>

<script type="text/javascript" src="{{ url('js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ url('js/application_blank.js') }}"></script>

@yield('css')

</head>

<body class="full-width">

    <!-- Page container -->
    <div class="page-container container-fluid">
    
        <!-- Page content -->
        <div class="page-content">


            @yield('content')

        
        </div>
        <!-- /page content -->

    </div>
    <!-- page container -->

    @yield('scripts')
    
</body>
</html>
