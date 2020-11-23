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

    <!-- Styles  <link rel="stylesheet" href="css/app.css"> -->   
   
    <!-- Font Material stylesheet -->
    <link rel="stylesheet" href="{{ asset('theme/fonts/material-design-iconic-font/css/material-design-iconic-font.min.css') }}">
    <!-- /font material stylesheet -->

    <!-- sprite-flags-master stylesheet -->
   <!-- <link rel="stylesheet" href="{{ asset('theme/fonts/sprite-flags-master/sprite-flags-32x32.css') }}"> -->
    <!-- /sprite-flags-master stylesheet -->

    <link rel="stylesheet" href="{{ asset('theme/fonts/fontawesome/css/font-awesome.min.css') }}">

    <!--Weather stylesheet -->
    <link rel="stylesheet" href="{{ asset('theme/fonts/weather-icons-master/css/weather-icons.min.css') }}">
    <!--/Weather stylesheet -->

    <!-- Bootstrap stylesheet -->
    <link href=" {{ asset('theme/css/mouldifi-bootstrap.css') }} " rel="stylesheet">
    <!-- /bootstrap stylesheet -->

    <!-- Perfect Scrollbar stylesheet -->
    <link href="{{ asset('node_modules/perfect-scrollbar/css/perfect-scrollbar.css') }} " rel="stylesheet">
    <!-- /perfect scrollbar stylesheet -->

    <!-- c3 Chart's css file -->
    <link href="{{ asset('node_modules/c3/c3.min.css') }}" rel="stylesheet">
    <!-- /c3 chart stylesheet -->

    <!-- Chartists Chart's css file -->
    <link href="{{ asset('node_modules/chartist/dist/chartist.min.css') }}" rel="stylesheet">
    <!-- /chartists chart stylesheet -->

    <!-- Mouldifi-core stylesheet -->
    <link href="{{ asset('theme/css/mouldifi-core.css') }}" rel="stylesheet">
    <!-- /mouldifi-core stylesheet -->

    <!-- Color-Theme stylesheet -->
    <link id="override-css-id" href="{{ asset('theme/css/theme-indigo.min.css') }}" rel="stylesheet">
    <!-- Color-Theme stylesheet -->

    @yield('mycss')

    <style>
        .centertext{
            text-align: 'center';
        }
        #loading-image {
          position: absolute;
          top: 160px;
          left: 640px;
          z-index: 100;
        }
._toolbar{
    background: #17375e repeat; 
    width: 40px;
    color: #fff;
    text-align: center;
    
}
._toolbar a{
    color:  #fff;
    font-weight: bold;
    height:70px;
}
._toolbar ul li a{
    font-weight: bold;
}
._toolbar_submenu{
    background:  #f9be77;
}
._toolbar_submenu2{
    background:  #17375e repeat;
    
}
._toolbar_submenu3{
    background:  #17375e repeat;
    
}
._toolbar ._toolbar_submenu2 a, ._toolbar ._toolbar_submenu3 a{
    color: #fff;
}
._toolbar ._toolbar_submenu2 li{
    border-bottom: 2px solid #fff;
}
.menu_toolbar{
    color:#E4A16A;
}

.st_table{
   table-layout: fixed;
}
.st_table tr td{
    width: 50px;
}

.st_table tr td.textc{
    width: 30px;
    height: 20px;
    color: #fff;
    background:  #1f497d repeat;
}

.st_table tr td.textc a{
    color: #fff;
}

.lighter{
    background: #17375e;
}

.widthplus{
    width: 40%;
}

.myhide{
    display: none;
}

#videox {
   width: "100%";
}

.dataTable > thead > tr > th[class*="sort"]:after{
    content: "" !important;
}

table.dataTable thead > tr > th.sorting_asc, 
table.dataTable thead > tr > th.sorting_desc, 
table.dataTable thead > tr > th.sorting, 
table.dataTable thead > tr > td.sorting_asc, 
table.dataTable thead > tr > td.sorting_desc, 
table.dataTable thead > tr > td.sorting {
    padding-right: inherit;
}

*[contenteditable="true"]{display: inline-block;}
    </style>
</head>
    <body id="body" data-theme="amber">
    
    <div id="loading" style="display: none;">
      <img id="loading-image" src="<?php echo asset("images/loading.gif");?>" alt="Loading..." />
    </div>

<!-- Page container -->
<div class="gx-container">

    <!-- Page Sidebar -->
    <div id="menu" class="side-nav gx-sidebar">
        <div class="navbar-expand-lg">
            <!-- Sidebar header  -->
            <div class="sidebar-header">
                <a class="site-logo" href="/">
                    <img src="{{ asset('standbasis1.png') }}" alt="Standbasis" title="Standbasis">
                </a>
            </div>
            <!-- /sidebar header -->

            <!-- Main navigation -->
            <div id="main-menu" class="main-menu navbar-collapse collapse">
                <ul class="nav-menu">
                    
                   <li class="nav-header"> <a href="/"> Main </a> </li>
                   @if (Auth::check() && ( Auth::user()->_type == 0 || Auth::user()->_type == 8 ) )
                        <li class="menu open">
                            <a href="javascript:void(0)">
                                <i class="zmdi zmdi-view-dashboard zmdi-hc-fw"></i>
                                <span class="nav-text">Attendance</span>
                            </a>
                            <ul class="sub-menu" display="block">
                                <li><a href="/tatttake"><span class="nav-text">Take Attendance</span></a></li>
                                <li><a href="/tattviews"><span class="nav-text">View Attendance</span></a></li>                          
                            </ul>
                        </li>

                        <li class="menu open">
                            <a href="javascript:void(0)">
                                <i class="zmdi zmdi-hc-fw zmdi-view-compact"></i>
                                <span class="nav-text">Lessonnote</span>
                            </a>
                            <ul class="sub-menu" display="block">
                                <li><a href="/tlsnsubmit"><span class="nav-text"> Submit Lessonnote </span></a></li>
                                <li><a href="/tlsnview"><span class="nav-text"> View Submitted Lessonnote </span></a></li>  
                                <li><a href="/tlsnscores"><span class="nav-text"> Add Scores </span></a></li>  
                                                        
                            </ul>
                        </li>

                        <li class="menu open">
                            <a href="javascript:void(0)">
                                <i class="zmdi zmdi-hc-fw zmdi-view-compact"></i>
                                <span class="nav-text">M&E</span>
                            </a>
                            <ul class="sub-menu" display="block">
                                <li><a href="/tmneview"><span class="nav-text"> View M&E </span></a></li>
                                                    
                            </ul>
                        </li>
                    @endif

                    @if (Auth::check() && ( Auth::user()->_type == 6 ) )
                        <li class="menu open">
                            <a href="javascript:void(0)">
                                <i class="zmdi zmdi-view-dashboard zmdi-hc-fw"></i>
                                <span class="nav-text">Attendance</span>
                            </a>
                            <ul class="sub-menu" display="block">
                                <li><a href="/ptwards"><span class="nav-text">My Wards Attendance</span></a></li>
                                                 
                            </ul>
                        </li>

                        <li class="menu open">
                            <a href="javascript:void(0)">
                                <i class="zmdi zmdi-hc-fw zmdi-view-compact"></i>
                                <span class="nav-text">Scores</span>
                            </a>
                            <ul class="sub-menu" display="block">
                                <li><a href=""><span class="nav-text"> My Wards Scores  </span></a></li>                  
                            </ul>
                        </li>
                    @endif
                    
                    @if (Auth::check() && Auth::user()->_type == 1)
                        <li class="menu open">
                            <a href="javascript:void(0)">
                                <i class="zmdi zmdi-view-dashboard zmdi-hc-fw"></i>
                                <span class="nav-text">Attendance</span>
                            </a>
                            <?php $date = date('Y-m-d');  ?>
                            <ul class="sub-menu" display="block">                               
                                <li><a href="/pattviews"><span class="nav-text">View Attendances</span></a></li>      
                                <li><a href="<?php echo "/pdaily/".$date; ?>"><span class="nav-text">View Daily Attendance Timetable</span></a></li>                          
                            </ul>
                        </li>

                        <li class="menu open">
                            <a href="javascript:void(0)">
                                <i class="zmdi zmdi-hc-fw zmdi-view-compact"></i>
                                <span class="nav-text">Lessonnote</span>
                            </a>
                            <ul class="sub-menu" display="block">
                                <li><a href="/plsnview"><span class="nav-text"> View Submitted Lessonnote </span></a></li>                          
                            </ul>
                        </li>

                        <li class="menu open">
                            <a href="javascript:void(0)">
                                <i class="zmdi zmdi-hc-fw zmdi-view-compact"></i>
                                <span class="nav-text">Flags</span>
                            </a>
                            <ul class="sub-menu" display="block">
                                <li><a href="/pattflags"><span class="nav-text"> View Attendance Flags </span></a></li>
                                <li><a href="/plsnflags"><span class="nav-text"> View Lessonnote Flags </span></a></li>
                                                    
                            </ul>
                        </li>
                    @endif

                    @if (Auth::check() && Auth::user()->_type == 2)
                        <li class="menu open">
                            <a href="javascript:void(0)">
                                <i class="zmdi zmdi-hc-fw zmdi-view-compact"></i>
                                <span class="nav-text">View Flags</span>
                            </a>
                            <ul class="sub-menu" display="block">
                                <li><a href="/pattflags"><span class="nav-text"> View Attendance Flags </span></a></li>
                                <li><a href="/plsnflags"><span class="nav-text"> View Lessonnote Flags </span></a></li>                                                    
                            </ul>
                        </li>
                    @endif

                    @if (Auth::check() && Auth::user()->_type == 3)
                        <li class="menu open">
                            <a href="javascript:void(0)">
                                <i class="zmdi zmdi-hc-fw zmdi-view-compact"></i>
                                <span class="nav-text">View Flags</span>
                            </a>
                            <ul class="sub-menu" display="block">
                                <li><a href="/pattflags"><span class="nav-text"> View Attendance Flags </span></a></li>
                                <li><a href="/plsnflags"><span class="nav-text"> View Lessonnote Flags </span></a></li>                                                    
                            </ul>
                        </li>
                    @endif
                   
                </ul>
            </div>
            <!-- /main navigation -->
        </div>
    </div>
    <!-- /page sidebar -->

    <!-- Main Container -->
    <div class="gx-main-container">

        <!-- Main Header -->
        <header class="main-header">
            <div class="gx-toolbar">
                <div class="sidebar-mobile-menu">
                    <a class="gx-menu-icon menu-toggle" href="#menu">
                        <span class="menu-icon icon-grey"></span>
                    </a>
                </div>

             

                <ul class="quick-menu header-notifications ml-auto">
                    <li class="dropdown language-menu d-none">
                        <a href="javascript:void(0)" data-toggle="dropdown" aria-haspopup="true"
                           class="d-inline-block flag-icon" aria-expanded="true">
                            <i class="flag flag-32 flag-us"></i>
                        </a>

                        <div role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
                            <div class="messages-list">
                                <ul class="list-unstyled">
                                    <li class="media">
                                        <i class="flag flag-32 flag-us"></i>
                                        <a href="javascript:void(0)" class="media-body align-self-center">
                                            English
                                        </a>
                                    </li>
                                    <li class="media">
                                        <i class="flag flag-32 flag-cn"></i>
                                        <a href="javascript:void(0)" class="media-body align-self-center">
                                            Chinese
                                        </a>
                                    </li>
                                    <li class="media">
                                        <i class="flag flag-32 flag-es"></i>
                                        <a href="javascript:void(0)" class="media-body align-self-center">
                                            Spanish
                                        </a>
                                    </li>
                                    <li class="media">
                                        <i class="flag flag-32 flag-fr"></i>
                                        <a href="javascript:void(0)" class="media-body align-self-center">
                                            French
                                        </a>
                                    </li>
                                    <li class="media">
                                        <i class="flag flag-32 flag-it"></i>
                                        <a href="javascript:void(0)" class="media-body align-self-center">
                                            Italian
                                        </a>
                                    </li>
                                    <li class="media">
                                        <i class="flag flag-32 flag-sa"></i>
                                        <a href="javascript:void(0)" class="media-body align-self-center">
                                            Arabic
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li>

                    <li class="nav-searchbox dropdown d-inline-block d-sm-none d-none">
                        <a href="javascript:void(0)" data-toggle="dropdown" aria-haspopup="true" class="d-inline-block icon-btn" aria-expanded="false">
                            <i class="zmdi zmdi-search zmdi-hc-fw"></i>
                        </a>
                        <div aria-hidden="true" class="p-0 dropdown-menu dropdown-menu-right search-bar right-side-icon search-dropdown">
                            <div class="form-group">
                                <input class="form-control border-0" placeholder="" value="" type="search">
                                <button class="search-icon"><i class="zmdi zmdi-search zmdi-hc-lg"></i></button>
                            </div>
                        </div>

                    </li>

                    <li class="dropdown" style="display: none;">
                        <a href="javascript:void(0)" data-toggle="dropdown" aria-haspopup="true" class="d-inline-block" aria-expanded="true">
                            <i class="zmdi zmdi-notifications-active icons-alert animated infinite wobble"></i>
                        </a>

                        <div role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
                            <div class="gx-card-header d-flex align-items-center">
                                <div class="mr-auto">
                                    <h3 class="card-heading">Notifications</h3>
                                </div>
                            </div>

                            <div class="dropdown-menu-perfectscrollbar">
                                <div class="messages-list">
                                    <ul class="list-unstyled">
                                        <li class="media">
                                            <img alt="stella-johnson" src="https://via.placeholder.com/150x150"
                                                 class="size-40 mr-2 user-avatar">
                                            <div class="media-body align-self-center">
                                                <p class="sub-heading mb-0">Stella Johnson has recently posted an
                                                    album</p>
                                                <a href="javascript:void(0)" class="gx-btn gx-flat-btn gx-btn-xs mb-0">
                                                    <i class="zmdi zmdi-thumb-up text-blue zmdi-hc-fw"></i>
                                                </a>
                                                <span class="meta-date"><small>4:10 PM</small></span>
                                            </div>
                                        </li>

                                        <li class="media">
                                            <img alt="domnic-harris" src="https://via.placeholder.com/150x150"
                                                 class="size-40 mr-2 user-avatar">
                                            <div class="media-body align-self-center">
                                                <p class="sub-heading mb-0">Alex Brown has shared Martin Guptil's
                                                    post</p>
                                                <a href="javascript:void(0)" class="gx-btn gx-flat-btn gx-btn-xs mb-0">
                                                    <i class="zmdi zmdi-comment-text text-grey zmdi-hc-fw"></i>
                                                </a>
                                                <span class="meta-date"><small>5:18 PM</small></span>
                                            </div>
                                        </li>

                                        <li class="media">
                                            <img alt="domnic-brown" src="https://via.placeholder.com/150x150"
                                                 class="size-40 mr-2 user-avatar">
                                            <div class="media-body align-self-center">
                                                <p class="sub-heading mb-0">Domnic Brown has sent you a group invitation
                                                    for Global Health</p>
                                                <a href="javascript:void(0)" class="gx-btn gx-flat-btn gx-btn-xs mb-0">
                                                    <i class="zmdi zmdi-card-giftcard text-info zmdi-hc-fw"></i>
                                                </a>
                                                <span class="meta-date"><small>5:36 PM</small></span>
                                            </div>
                                        </li>

                                        <li class="media">
                                            <img alt="john-smith" src="https://via.placeholder.com/150x150"
                                                 class="size-40 mr-2 user-avatar">
                                            <div class="media-body align-self-center">
                                                <p class="sub-heading mb-0">John Smith has birthday today</p>
                                                <a href="javascript:void(0)" class="gx-btn gx-flat-btn gx-btn-xs mb-0">
                                                    <i class="zmdi zmdi-cake text-warning zmdi-hc-fw"></i>
                                                </a>
                                                <span class="meta-date"><small>5:54 PM</small></span>
                                            </div>
                                        </li>

                                        <li class="media">
                                            <img alt="jimmy-jo" src="https://via.placeholder.com/150x150"
                                                 class="size-40 mr-2 user-avatar">
                                            <div class="media-body align-self-center">
                                                <p class="sub-heading mb-0">Chris has updated his profile picture</p>
                                                <a href="javascript:void(0)" class="gx-btn gx-flat-btn gx-btn-xs mb-0">
                                                    <i class="zmdi zmdi-account-box-o text-grey zmdi-hc-fw"></i>
                                                </a>
                                                <span class="meta-date"><small>5:25 PM</small></span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="dropdown d-none">
                        <a href="javascript:void(0)" data-toggle="dropdown" aria-haspopup="true" class="d-inline-block" aria-expanded="true">
                            <i class="zmdi zmdi-comment-alt-text icons-alert zmdi-hc-fw"></i>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" data-placement="bottom-end" data-x-out-of-boundaries="">
                            <div class="gx-card-header d-flex align-items-center">
                                <div class="mr-auto">
                                    <h3 class="card-heading">Messages</h3>
                                </div>
                            </div>

                            <div class="dropdown-menu-perfectscrollbar1">
                                <div class="messages-list">
                                    <ul class="list-unstyled">
                                        <li class="media">
                                            <div class="user-thumb">
                                                <img alt="Domnic Brown" src="https://via.placeholder.com/150x150"
                                                     class="rounded-circle size-40 user-avatar">
                                                <span class="badge badge-danger rounded-circle">5</span>
                                            </div>

                                            <div class="media-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h5 class="text-capitalize user-name mb-0">
                                                        <a href="javascript:void(0)">Domnic Brown</a>
                                                    </h5>
                                                    <span class="meta-date"><small>6:19 PM</small></span>
                                                </div>
                                                <p class="sub-heading">There are many variations of passages of...</p>

                                                <a href="javascript:void(0)" class="gx-btn gx-flat-btn gx-btn-xs text-muted">
                                                    <i class="zmdi zmdi-mail-reply"></i>
                                                    <span>Reply</span>
                                                </a>

                                                <a href="javascript:void(0)" class="gx-btn gx-flat-btn gx-btn-xs text-muted">
                                                    <i class="zmdi zmdi-eye"></i>
                                                    <span>Read</span>
                                                </a>
                                            </div>
                                        </li>

                                        <li class="media">
                                            <div class="user-thumb">
                                                <img alt="John Smith" src="https://via.placeholder.com/150x150"
                                                     class="rounded-circle size-40 user-avatar">
                                                <span class="badge badge-danger rounded-circle"></span>
                                            </div>

                                            <div class="media-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h5 class="text-capitalize user-name mb-0">
                                                        <a href="javascript:void(0)">John Smith</a>
                                                    </h5>
                                                    <span class="meta-date"><small>4:18 PM</small></span>
                                                </div>
                                                <p class="sub-heading">Lorem Ipsum is simply dummy text of the...</p>
                                                <a href="javascript:void(0)" class="gx-btn gx-flat-btn gx-btn-xs text-muted">
                                                    <i class="zmdi zmdi-mail-reply"></i>
                                                    <span>Reply</span>
                                                </a>

                                                <a href="javascript:void(0)" class="gx-btn gx-flat-btn gx-btn-xs text-muted">
                                                    <i class="zmdi zmdi-eye"></i>
                                                    <span>Read</span>
                                                </a>
                                            </div>
                                        </li>

                                        <li class="media">
                                            <div class="user-thumb">
                                                <img alt="John Smith" src="https://via.placeholder.com/150x150"
                                                     class="size-40 rounded-circle user-avatar">
                                                <span class="badge badge-danger rounded-circle">8</span>
                                            </div>

                                            <div class="media-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h5 class="text-capitalize user-name mb-0">
                                                        <a href="javascript:void(0)">John Smith</a>
                                                    </h5>
                                                    <span class="meta-date"><small>7:10 PM</small></span>
                                                </div>
                                                <p class="sub-heading">The point of using Lorem Ipsum is that it
                                                    has...</p>

                                                <a href="javascript:void(0)" class="gx-btn gx-flat-btn gx-btn-xs text-muted">
                                                    <i class="zmdi zmdi-mail-reply"></i>
                                                    <span>Reply</span>
                                                </a>

                                                <a href="javascript:void(0)" class="gx-btn gx-flat-btn gx-btn-xs text-muted">
                                                    <i class="zmdi zmdi-eye"></i>
                                                    <span>Read</span>
                                                </a>
                                            </div>
                                        </li>

                                        <li class="media">
                                            <div class="user-thumb">
                                                <img alt="alex dolgove" src="https://via.placeholder.com/150x150"
                                                     class="size-40 rounded-circle user-avatar">
                                                <span class="badge badge-danger rounded-circle"></span>
                                            </div>

                                            <div class="media-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h5 class="text-capitalize user-name mb-0">
                                                        <a href="javascript:void(0)">alex dolgove</a>
                                                    </h5>
                                                    <span class="meta-date"><small>5:10 PM</small></span>
                                                </div>
                                                <p class="sub-heading">It is a long established fact that a reader
                                                    will...</p>

                                                <a href="javascript:void(0)" class="gx-btn gx-flat-btn gx-btn-xs text-muted">
                                                    <i class="zmdi zmdi-mail-reply"></i>
                                                    <span>Reply</span>
                                                </a>

                                                <a href="javascript:void(0)" class="gx-btn gx-flat-btn gx-btn-xs text-muted">
                                                    <i class="zmdi zmdi-eye"></i>
                                                    <span>Read</span>
                                                </a>
                                            </div>

                                        </li>

                                        <li class="media">
                                            <div class="user-thumb">
                                                <img alt="Domnic Harris" src="https://via.placeholder.com/150x150"
                                                     class="size-40 rounded-circle user-avatar">

                                                <span class="badge badge-danger rounded-circle">3</span>
                                            </div>
                                            <div class="media-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h5 class="text-capitalize user-name mb-0">
                                                        <a href="javascript:void(0)">Domnic Harris</a>
                                                    </h5>
                                                    <span class="meta-date"><small>7:35 PM</small></span>
                                                </div>
                                                <p class="sub-heading">All the Lorem Ipsum generators on the...</p>

                                                <a href="javascript:void(0)" class="gx-btn gx-flat-btn gx-btn-xs text-muted">
                                                    <i class="zmdi zmdi-mail-reply"></i>
                                                    <span>Reply</span>
                                                </a>

                                                <a href="javascript:void(0)" class="gx-btn gx-flat-btn gx-btn-xs text-muted">
                                                    <i class="zmdi zmdi-eye"></i>
                                                    <span>Read</span>
                                                </a>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>

                    

                    <li class="dropdown user-nav">
                        <a href="#" class="gx-btn gx-flat-btn gx-btn-primary gx-btn-sm  ml-2 ml-xl-3 my-sm-0 mr-0">{{ Auth::user()->name }}</a>

                        <a class="dropdown-toggle no-arrow d-inline-block" href="#" role="button" id="userInfo"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                @if (Auth::user()->_type == 0)
                                    <img class="user-avatar border-0 size-40"  src="{{ asset('images/letterT.png') }}" width="150" height="150" alt="User">
                                @endif

                                @if (Auth::user()->_type == 1)
                                    <img class="user-avatar border-0 size-40"  src="{{ asset('images/letterP.jpg') }}" width="150" height="150" alt="User">
                                @endif

                                @if (Auth::user()->_type == 2)
                                    <img class="user-avatar border-0 size-40"  src="{{ asset('images/letterA.png') }}" width="150" height="150" alt="User">
                                @endif

                                @if (Auth::user()->_type == 3)
                                    <img class="user-avatar border-0 size-40"  src="{{ asset('images/letterG.png') }}" width="150" height="150" alt="User">
                                @endif
                           
                                @if (Auth::user()->_type == 6)
                                    <img class="user-avatar border-0 size-40"  src="{{ asset('images/letterG.png') }}" width="150" height="150" alt="User">
                                @endif
                        </a>

                       
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userInfo">
                                <div class="user-profile">
                                @if (Auth::user()->_type == 0 || Auth::user()->_type == 8 )
                                    <img class="user-avatar border-0 size-40"  src="{{ asset('images/letterT.png') }}"  width="150" height="150" alt="User">
                                @endif

                                @if (Auth::user()->_type == 1)
                                    <img class="user-avatar border-0 size-40"  src="{{ asset('images/letterP.jpg') }}"  width="150" height="150" alt="User">
                                @endif

                                @if (Auth::user()->_type == 2)
                                    <img class="user-avatar border-0 size-40"  src="{{ asset('images/letterA.png') }}"  width="150" height="150" alt="User">
                                @endif

                                @if (Auth::user()->_type == 3)
                                    <img class="user-avatar border-0 size-40"  src="{{ asset('images/letterG.png') }}"  width="150" height="150" alt="User">
                                @endif

                                @if (Auth::user()->_type == 6)
                                    <img class="user-avatar border-0 size-40"  src="{{ asset('images/letterG.png') }}"  width="150" height="150" alt="User">
                                @endif
                                        
                                    <div class="user-detail ml-2">
                                        <h4 class="user-name mb-0">{{ Auth::user()->name }}</h4>
                                        @if (Auth::user()->_type == 0)
                                            <small>Teacher</small>
                                        @endif
                                        @if (Auth::user()->_type == 8)
                                            <small class="danger">Test Teacher</small>
                                        @endif
                                        @if (Auth::user()->_type == 6)
                                            <small class="danger">Parent/Guardian</small>
                                        @endif
                                        
                                        @if (Auth::user()->_type == 1)
                                            <small>Principal</small>
                                        @endif
                                        @if (Auth::user()->_type == 2)
                                            <small>Administrator</small>
                                        @endif
                                        @if (Auth::user()->_type == 3)
                                            <small>School Owner</small>
                                        @endif
                                        
                                    </div>
                                </div>
                                <a class="dropdown-item" href="/logoutuser">
                                    <i class="zmdi zmdi-sign-in zmdi-hc-fw mr-1"></i>
                                    Logout
                                </a>
                            </div>
                     
                    </li>
                </ul>
            </div>
        </header>
        <!-- /main header -->

        <!-- Main Content -->
        <div class="gx-main-content">
                @if (Auth::check() && ( Auth::user()->_type == 0 || Auth::user()->_type == 9 || Auth::user()->_type == 8 ) )
                    @yield('teacher')
                @elseif (Auth::check() && Auth::user()->_type == 6 )
                    @yield('parent')
                @elseif (Auth::check() && Auth::user()->_type == 1 )
                    @yield('principal')
                @elseif (Auth::check() && Auth::user()->_type == 2)
                    @yield('admin')
                @elseif (Auth::check() && Auth::user()->_type == 3)
                    @yield('owner')
                @else
                    @yield('content')
                @endif

            <!-- Footer -->
            <footer class="gx-footer">
                <div class="d-flex flex-row justify-content-between">
                    <p> Copyright Standbasis © 2018 - <?php echo date('Y') ?></p>                    
                </div>
            </footer>
            <!-- /footer -->

        </div>
        <!-- /main content -->

    </div>
    <!-- /main container -->

  

</div>
<!-- /page container -->

<!-- Right Sidebar-->
<div id="colorSidebar" class="app-sidebar-content right-sidebar" style="display: none;">
    <div class="color-theme">
        <div class="color-theme-header">
            <h3 class="color-theme-title">Service Panel</h3>
            <a href="javascript:void(0)" class="action-btn" id="close-setting-panel">
                <i class="zmdi zmdi-close text-white"></i>
            </a>
        </div>

        <div class="color-theme-body">
            <h3>Light Sidenav</h3>
            <ul class="color-option">
                <li>
                    <a href="javascript:void(0)" class="bg-indigo gx-theme" data-theme="indigo"></a>
                </li>
                <li>
                    <a href="javascript:void(0)" class="bg-cyan gx-theme" data-theme="cyan"></a>
                </li>
                <li>
                    <a href="javascript:void(0)" class="bg-amber gx-theme" data-theme="amber"></a>
                </li>
                <li>
                    <a href="javascript:void(0)" class="bg-deep-orange gx-theme" data-theme="deep-orange"></a>
                </li>
                <li>
                    <a href="javascript:void(0)" class="bg-pink gx-theme" data-theme="pink"></a>
                </li>
                <li>
                    <a href="javascript:void(0)" class="bg-blue gx-theme" data-theme="blue"></a>
                </li>
                <li>
                    <a href="javascript:void(0)" class="bg-deep-purple gx-theme" data-theme="deep-purple"></a>
                </li>
                <li>
                    <a href="javascript:void(0)" class="bg-green gx-theme" data-theme="green"></a>
                </li>
            </ul>
            <h3>Dark Sidenav</h3>
            <ul class="color-option cr-op-dark-sidebar">
                <li>
                    <a href="javascript:void(0)" class="bg-indigo gx-theme" data-theme="dark-indigo"></a>
                </li>
                <li>
                    <a href="javascript:void(0)" class="bg-cyan gx-theme" data-theme="dark-cyan"></a>
                </li>
                <li>
                    <a href="javascript:void(0)" class="bg-amber gx-theme" data-theme="dark-amber"></a>
                </li>
                <li>
                    <a href="javascript:void(0)" class="bg-deep-orange gx-theme" data-theme="dark-deep-orange"></a>
                </li>
                <li>
                    <a href="javascript:void(0)" class="bg-pink gx-theme" data-theme="dark-pink"></a>
                </li>
                <li>
                    <a href="javascript:void(0)" class="bg-blue gx-theme" data-theme="dark-blue"></a>
                </li>
                <li>
                    <a href="javascript:void(0)" class="bg-deep-purple gx-theme" data-theme="dark-deep-purple"></a>
                </li>
                <li>
                    <a href="javascript:void(0)" class="bg-green gx-theme" data-theme="dark-green"></a>
                </li>
            </ul>
            <h3>Dark Theme</h3>
            <div class="material-switch">
                <input id="switch-dark-theme" name="switch-dark-theme" type="checkbox" data-theme="dark" />
                <label for="switch-dark-theme" class="label-default"></label>
            </div>
        </div>
    </div>
</div>
<!-- /Right Sidebar-->

<!-- Menu Backdrop -->
<div class="menu-backdrop fade"></div>
<!-- /menu backdrop -->
          
    

    <!--Load JQuery-->
    <script src="{{ asset('node_modules/jquery/dist/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/pouchdb@7.1.1/dist/pouchdb.min.js"> </script>
    <script src="https://github.com/pouchdb/pouchdb/releases/download/7.1.1/pouchdb.find.js"> </script>
    <script> 
        var dbobject = new PouchDB('standbasis');
    </script>
    @yield('myscript')
    <script>  
    $(document).ready(function() { 
        $(document).ajaxStop(function(){
                 console.log('ajax stop general');
               $('#loading').hide();
             });
        $(document).ajaxStart(function(){
                 console.log('ajax start general');
              $('#loading').show();
             });
        }); 
    </script>
    <!--Bootstrap JQuery-->
    <script src="{{ asset('node_modules/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <!--Perfect Scrollbar JQuery-->
    <script src="{{ asset('node_modules/perfect-scrollbar/dist/perfect-scrollbar.min.js') }}"></script>
    <!--Big Slide JQuery-->
    <script src="{{ asset('node_modules/bigslide/dist/bigSlide.min.js') }}"></script>
    <!--chart-->
   <!-- <script src="node_modules/d3/d3.min.js"></script>
    <script src="node_modules/c3/c3.min.js"></script>
    <script src="node_modules/chartist/dist/chartist.min.js"></script>
    <script src="node_modules/chart.js/dist/Chart.bundle.min.js"></script> -->

    <script src="{{ asset('node_modules/datatables.net/js/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('node_modules/datatables.net-bs4/js/dataTables.bootstrap4.js') }}"></script>

    <!--Custom JQuery-->
    <script src="{{ asset('theme/js/functions.js') }}"></script>
    <script src="{{ asset('theme/js/custom/data-tables.js') }}"></script>
    <script src="{{ asset('theme/js/custom/chart/dashboard-chart.js') }}"></script>

   
</body>
</html>
