<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Standbasis') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
   
    <link rel="stylesheet" href="css/app.css">
    <!-- Font Material stylesheet -->
    <link rel="stylesheet" href="{{ asset('theme/fonts/material-design-iconic-font/css/material-design-iconic-font.min.css') }}">
    <!-- /font material stylesheet -->

    <!-- sprite-flags-master stylesheet -->
    <link rel="stylesheet" href="theme/fonts/sprite-flags-master/sprite-flags-32x32.css">
    <!-- /sprite-flags-master stylesheet -->

    <!--Weather stylesheet -->
    <link rel="stylesheet" href="theme/fonts/weather-icons-master/css/weather-icons.min.css">
    <!--/Weather stylesheet -->

    <!-- Bootstrap stylesheet -->
    <link href="theme/css/mouldifi-bootstrap.css" rel="stylesheet">
    <!-- /bootstrap stylesheet -->

    <!-- Perfect Scrollbar stylesheet -->
    <link href="node_modules/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet">
    <!-- /perfect scrollbar stylesheet -->

    <!-- c3 Chart's css file -->
    <link href="node_modules/c3/c3.min.css" rel="stylesheet">
    <!-- /c3 chart stylesheet -->

    <!-- Chartists Chart's css file -->
    <link href="node_modules/chartist/dist/chartist.min.css" rel="stylesheet">
    <!-- /chartists chart stylesheet -->

    <!-- Mouldifi-core stylesheet -->
    <link href="theme/css/mouldifi-core.css" rel="stylesheet">
    <!-- /mouldifi-core stylesheet -->

    <!-- Color-Theme stylesheet -->
    <link id="override-css-id" href="theme/css/theme-indigo.min.css" rel="stylesheet">
    <!-- Color-Theme stylesheet -->
</head>
    <body id="body" data-theme="amber">
   
       <!-- Loader Backdrop -->
<div class="loader-backdrop">
    <!-- Loader -->
    <div class="loader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
        </svg>
    </div>
    <!-- /loader-->
</div>
<!-- loader backdrop -->

<!-- Page container -->
<div class="gx-container">

   

    <!-- Main Container -->
    <div class="gx-main-container" style="overflow: scroll; ">

        <!-- Main Header -->
       

                            <nav class="navbar navbar-expand-lg border bg-primary text-white">
                                <a href="javascript:void(0)" class="gx-menu-icon mr-2 mr-xl-3 d-flex" aria-label="Menu">
                                    <span class="menu-icon"></span>
                                </a>
                                <h3 class="m-0">STANDBASIS</h3>
                                
                                <div class="collapse navbar-collapse justify-content-end"> 
                                @if (!Auth::check())
                                    <a href="/login" class="gx-btn gx-flat-btn gx-btn-primary gx-btn-sm  ml-2 ml-xl-3 my-sm-0 mr-0">LOGIN</a>
                                @endif                                   
                                @if (Auth::check() && Auth::user()->_type === 0)
                                    <a href="/tlsnsubmit" class="gx-btn gx-flat-btn gx-btn-primary gx-btn-sm  ml-2 ml-xl-3 my-sm-0 mr-0">LESSONNOTE</a>

                                    <a href="/tattview" class="gx-btn gx-flat-btn gx-btn-primary gx-btn-sm  ml-2 ml-xl-3 my-sm-0 mr-0">ATTENDANCE</a>

                                    <a href="/tmneview" class="gx-btn gx-flat-btn gx-btn-primary gx-btn-sm  ml-2 ml-xl-3 my-sm-0 mr-0">M&E</a>                               
                                @endif
                                    <div class="ml-3 ml-xl-5 d-none d-md-block">
                                       <p style="padding: 0 0 0 30%;"> <img alt="Standbasis Teacher" src="https://via.placeholder.com/150x150" class="rounded-circle size-40"/> </p>
                                        <label> <b> Welcome, User </b> </label>
                                    </div>

                                </div>
                            </nav>
        <!-- /main header -->

        <!-- Main Content -->
        <div class="gx-main-content">
            
            @yield('content')

            <!-- Footer -->
            <footer class="gx-footer">
                <div class="d-flex flex-row justify-content-between">
                    <p> Copyright STANDBASIS © 2019</p>                  
                </div>
            </footer>
            <!-- /footer -->

        </div>
        <!-- /main content -->

    </div>
    <!-- /main container -->

</div>
<!-- /page container -->



<!-- Menu Backdrop -->
<div class="menu-backdrop fade"></div>
<!-- /menu backdrop -->
             
    

    <!--Load JQuery-->
    <script src="node_modules/jquery/dist/jquery.min.js"></script>
    <!--Bootstrap JQuery-->
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!--Perfect Scrollbar JQuery-->
    <script src="node_modules/perfect-scrollbar/dist/perfect-scrollbar.min.js"></script>
    <!--Big Slide JQuery-->
    <script src="node_modules/bigslide/dist/bigSlide.min.js"></script>
    <!--chart-->
    <script src="node_modules/d3/d3.min.js"></script>
    <script src="node_modules/c3/c3.min.js"></script>
    <script src="node_modules/chartist/dist/chartist.min.js"></script>
    <script src="node_modules/chart.js/dist/Chart.bundle.min.js"></script>

    <!--Custom JQuery-->
    <script src="theme/js/functions.js"></script>
    <script src="theme/js/custom/chart/dashboard-chart.js"></script>

</body>
</html>
