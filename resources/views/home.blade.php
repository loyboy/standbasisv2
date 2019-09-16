@extends('layouts.app')

@section('content')
 <!--gx-wrapper-->
            <div class="gx-wrapper">
                <div class="dashboard animated slideInUpTiny animation-duration-3">
                    <div class="page-heading">
                        <h2 class="title">Dashboard - Default</h2>
                    </div>
                    <div class="row">
                        <div class="col-xl-5 col-sm-12">
                            <div class="gx-card p-0 overflow-hidden gx-card-full-height">
                                <div class="gx-card-header pt-3 px-4">
                                    <h3 class="card-heading">User Statstics</h3>
                                </div>
                                <div class="gx-card-body">
                                    <div id="c3-chart" class="c3-chart" style="height: 200px;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-7 col-12">
                            <div class="row">
                                <div class="col-xl-8 col-lg-6 col-md-7 col-12">
                                    <div class="row">
                                        <div class="col-sm-6 col-12">
                                            <div class="gx-card net-chart">
                                                <div class="gx-card-thumb bg-secondary">
                                                    <i class="zmdi zmdi-mic-outline"></i>
                                                </div>
                                                <div class="gx-card-body br-break">
                                                    <h4 class="mb-0"><strong>23</strong></h4>
                                                    <p class="mb-0">iDeas</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-12">
                                            <div class="gx-card net-chart">
                                                <div class="gx-card-thumb bg-teal">
                                                    <i class="zmdi zmdi-collection-folder-image"></i>
                                                </div>
                                                <div class="gx-card-body br-break">
                                                    <h4 class="mb-0"><strong>387</strong></h4>
                                                    <p class="mb-0">Docs</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="img-overlay-card ripple-effect">
                                                <div class="center-crop-img">
                                                    <img src="http://via.placeholder.com/1280x300" alt="city">
                                                </div>
                                                <div class="gx-cart-ab layer">
                                                    <div class="row text-center w-100">
                                                        <div class="col-sm-6 text-truncate">
                                                            <i class="zmdi zmdi-pin text-white mr-2"></i>
                                                            <span>11 Cities </span>
                                                        </div>
                                                        <div class="col-sm-6 text-truncate"><i
                                                                class="zmdi zmdi-coffee text-white mr-2"></i><span>2987 Coffees </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6 col-md-5 col-12">
                                    <div class="card gx-chart-or gx-card-full-height d-flex flex-column justify-content-between">
                                        <div class="chart-header">
                                            <div class="chart-title">
                                                <h3>Orders</h3>
                                                <span class="d-block mb-1 text-muted">25 New Placed</span>
                                                <span class="d-block mb-1 text-muted">13 Processed</span>
                                                <span class="d-block text-muted">07 Pending</span>
                                            </div>
                                        </div>
                                        <div class="gx-card-body">
                                            <div id="c8-chart" style="height: 85px;width: 100%;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-3 col-sm-5 col-12">
                            <div class="gx-card gx-card-full-height text-center overflow-hidden">
                                <div class="gx-card-header-color mb-0">
                                    <div class="gx-card-header-top mb-2">
                                        <a href="javascript:void(0)" class="gx-menu-icon mr-auto">
                                            <span class="menu-icon icon-grey"></span>
                                        </a>
                                        <div class="dropdown">
                                            <a class="dropdown-more dropdown-toggle" href="#" role="button"
                                               id="dropdownMenuLink1"
                                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                                                <i class="zmdi zmdi-more-vert zmdi-hc-fw zmdi-hc-lg"></i>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-right"
                                                 aria-labelledby="dropdownMenuLink1">
                                                <a class="dropdown-item" href="#">Action</a>
                                                <a class="dropdown-item" href="#">Another action</a>
                                                <a class="dropdown-item" href="#">Something else here</a>
                                            </div>
                                        </div>
                                    </div>

                                    <img class="user-avatar size-80 mb-3"
                                         src="http://via.placeholder.com/150x150" alt="Team Member">
                                    <div class="gx-card-hd-content">
                                        <h4 class="mb-0">Chris Harris</h4>
                                        <p class="sub-heading mb-0">Graphic Designer</p>
                                    </div>
                                </div>
                                <div class="gx-card-body">
                                    <p class="card-text">Cenas in erat accumsan, hendrerit lorem vel, pulni odio
                                        coletium indo.</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-sm-7 col-12">
                            <div class="gx-card gx-card-full-height">
                                <div class="gx-card-header d-flex mb-2">
                                    <div class="mr-auto">
                                        <h3 class="card-heading">New Connections</h3>
                                        <p class="sub-heading">3 This week</p>
                                    </div>
                                    <div class="dropdown">
                                        <a class="dropdown-more dropdown-toggle" href="#" role="button"
                                           id="dropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true"
                                           aria-expanded="false">
                                            <i class="zmdi zmdi-chevron-down zmdi-hc-fw zmdi-hc-lg"></i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right"
                                             aria-labelledby="dropdownMenuLink2">
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="default-table table-sm table remove-table-border mb-0 ">
                                        <tbody>
                                        <tr>
                                            <td class="size-60">
                                                <img alt="Domnic Brown" src="http://via.placeholder.com/150x150"
                                                     class="user-avatar">
                                            </td>
                                            <td>
                                                <h4 class="user-name">Domnic Brown</h4>
                                                <p class="user-description">@dom</p>
                                            </td>
                                            <td class="text-right">
                                                <a class="dropdown-more" href="javascript:void(0)">
                                                    <i class="zmdi zmdi-more-vert zmdi-hc-fw zmdi-hc-lg"></i>
                                                </a>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="size-60">
                                                <img alt="Michael Dolgov" src="http://via.placeholder.com/150x150"
                                                     class="user-avatar">
                                            </td>
                                            <td>
                                                <h4 class="user-name">Michael Dolgov</h4>
                                                <p class="user-description">@m.dolgov</p>
                                            </td>
                                            <td class="text-right">
                                                <a class="dropdown-more" href="javascript:void(0)">
                                                    <i class="zmdi zmdi-more-vert zmdi-hc-fw zmdi-hc-lg"></i>
                                                </a>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="size-60">
                                                <img alt="Ron Doe" src="http://via.placeholder.com/150x150"
                                                     class="user-avatar">
                                            </td>
                                            <td>
                                                <h4 class="user-name">Ron Doe</h4>
                                                <p class="user-description">@rond</p>
                                            </td>
                                            <td class="text-right">
                                                <a class="dropdown-more" href="javascript:void(0)">
                                                    <i class="zmdi zmdi-more-vert zmdi-hc-fw zmdi-hc-lg"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-5 col-12">
                            <div class="gx-card gx-card-full-height">
                                <div class="gx-card-header d-flex ">
                                    <div class="mr-auto">
                                        <h3 class="card-heading">Recent Activities</h3>
                                        <p class="sub-heading">Last activity was 2 days ago</p>
                                    </div>
                                    <div class="dropdown">
                                        <a class="dropdown-more dropdown-toggle" href="#" role="button"
                                           id="dropdownMenuLink3" data-toggle="dropdown" aria-haspopup="true"
                                           aria-expanded="false">
                                            <i class="zmdi zmdi-chevron-down zmdi-hc-fw zmdi-hc-lg"></i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right"
                                             aria-labelledby="dropdownMenuLink3">
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="media social-list-line">
                                    <div class="bg-white border-primary user-avatar size-40 z-index-20 mr-3">
                                        <i class="zmdi zmdi-apple text-primary zmdi-hc-fw zmdi-hc-lg"></i>
                                    </div>
                                    <div class="media-body mb-3">
                                        <h4 class="mb-1">iOS app launched</h4>
                                        <p class="meta-date">20 March, 2017</p>
                                    </div>
                                </div>

                                <div class="media social-list-line">
                                    <div class="bg-white border-deep-orange user-avatar size-40 z-index-20 mr-3">
                                        <i class="zmdi zmdi-android text-deep-orange zmdi-hc-fw zmdi-hc-lg"></i>
                                    </div>
                                    <div class="media-body mb-3">
                                        <h4 class="mb-1">Android app launched</h4>
                                        <p class="meta-date">27 Feb, 2017</p>
                                    </div>
                                </div>
                                <div class="media social-list-line">
                                    <div class="bg-white border-info user-avatar size-40 z-index-20 mr-3">
                                        <i class="zmdi zmdi-dropbox text-info zmdi-hc-fw zmdi-hc-lg"></i>
                                    </div>
                                    <div class="media-body mb-2">
                                        <h4 class="mb-1">PSD file added to dropbox</h4>
                                        <p class="meta-date mb-0">24 Feb, 2017</p></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-5 col-12">
                            <div class="gx-card gx-card-full-height">
                                <div class="row">
                                    <div class="col-sm-5 align-self-end">
                                        <h2 class="chart-f30 font-weight-light mb-1">$685K+</h2>
                                        <span class="sub-heading ">Post 9 month data</span>
                                    </div>
                                    <div class="col-sm-7">
                                        <div id="ch-chart6" style="height: 90px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-5 col-sm-8 col-12 order-lg-3 ">
                            <div class="gx-card gx-card-full-height">
                                <div class="row">
                                    <div class="col-sm-4 col-12">
                                        <div class="gx-card-body">
                                            <div id="ct-chart9"></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-8 col-12 text-sm-left text-center align-self-center">
                                        <div class="jr-card-header mb-2">
                                            <h3 class="card-heading mb-1">Google insight score</h3>
                                            <p class="sub-heading">Analyzed 3 days ago</p>
                                        </div>
                                        <a href="javascript:void(0)"
                                           class="gx-btn text-uppercase gx-btn-primary gx-btn-sm">
                                            <i class="zmdi zmdi-refresh-sync zmdi-hc-fw "></i>
                                            <span>Refresh</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-sm-4 col-12">
                            <div class="gx-card px-2 py-5 py-sm-0 gx-card-full-height height-150 d-flex align-items-center justify-content-center">
                                <div class="text-center">
                                    <img class="d-inline-block mb-3" src="images/dashboard/download.png" alt="image">
                                    <h5 class="font-weight-semibold mb-0">Download v 3.0</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-4 col-sm-6 col-12">
                            <div class="gx-card gx-card-full-height">
                                <div class="gx-card-header d-flex">
                                    <div class="mr-auto">
                                        <h3 class="card-heading d-inline-block mb-0">Current Projects</h3>
                                    </div>
                                    <div class="dropdown">
                                        <a class="dropdown-more dropdown-toggle" href="#" role="button"
                                           id="dropdownMenuLink4" data-toggle="dropdown" aria-haspopup="true"
                                           aria-expanded="false">
                                            <i class="zmdi zmdi-chevron-down zmdi-hc-fw zmdi-hc-lg"></i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right"
                                             aria-labelledby="dropdownMenuLink4">
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="gx-card-body">
                                    <div class="media project-list">
                                        <span class="circle-shape bg-green"></span>
                                        <div class="media-body">
                                            <a class="project-name" href="javascript:void(0)">Chatbull iOS App</a>
                                            <span class="meta-date">Deadline: 23 March, 2018</span>
                                        </div>
                                    </div>

                                    <div class="media project-list">
                                        <span class="circle-shape bg-amber"></span>
                                        <div class="media-body">
                                            <a class="project-name" href="javascript:void(0)">Mouldifi Admin</a>
                                            <span class="meta-date">Deadline: 02 Dec, 2018</span>
                                        </div>
                                    </div>

                                    <div class="media project-list">
                                        <span class="circle-shape bg-red"></span>
                                        <div class="media-body">
                                            <a class="project-name" href="javascript:void(0)">Jumbo React App</a>
                                            <span class="meta-date">Deadline: 14 Dec, 2018</span>
                                        </div>
                                    </div>

                                    <div class="media project-list">
                                        <span class="circle-shape bg-green"></span>
                                        <div class="media-body">
                                            <a class="project-name" href="javascript:void(0)">Jumbo WordPress Theme</a>
                                            <span class="meta-date">Deadline: 23 Dec, 2018</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-sm-6 col-12">
                            <div class="gx-card gx-card-full-height">
                                <div class="gx-card-header d-flex">
                                    <div class="mr-auto">
                                        <h3 class="card-heading d-inline-block mb-0">My Todos</h3>
                                    </div>
                                    <div class="dropdown">
                                        <a class="dropdown-more dropdown-toggle" href="#" role="button"
                                           id="dropdownMenuLink5" data-toggle="dropdown" aria-haspopup="true"
                                           aria-expanded="false">
                                            <i class="zmdi zmdi-chevron-down zmdi-hc-fw zmdi-hc-lg"></i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right"
                                             aria-labelledby="dropdownMenuLink5">
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="todo-cell-group">
                                    <div class="todo-cell d-flex align-items-center">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input"
                                                   id="customControlInline1">
                                            <label class="custom-control-label" for="customControlInline1">
                                                <span class="text-grey d-block">Mouldifi</span>
                                                <span>Design wireframes for the latest pages added in the SRS doc.</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="todo-cell d-flex align-items-center">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input"
                                                   id="customControlInline2" checked="checked">
                                            <label class="custom-control-label" for="customControlInline2">
                                                <span class="text-grey font-13 d-block">Jumbo React</span>
                                                <span><del>Add a new mailbox app under app modules section</del></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="todo-cell d-flex align-items-center">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input"
                                                   id="customControlInline3">
                                            <label class="custom-control-label" for="customControlInline3">
                                                <span class="text-grey font-13 d-block">Chatbull iOS App</span>
                                                <span>Integrate push notifications with the latest APIs</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-12">
                            <div class="gx-card gx-card-full-height">
                                <div class="gx-card-header d-flex mb-2">
                                    <div class="mr-auto">
                                        <h3 class="card-heading mb-0">
                                            <i class="zmdi zmdi-chart-donut zmdi-hc-fw text-primary text-lighten-2 mr-2"></i>
                                            Marketing Campaign
                                        </h3>
                                    </div>
                                    <div class="dropdown">
                                        <a class="dropdown-more dropdown-toggle" href="#" role="button"
                                           id="dropdownMenuLink6" data-toggle="dropdown" aria-haspopup="true"
                                           aria-expanded="false">
                                            <i class="zmdi zmdi-chevron-down zmdi-hc-fw zmdi-hc-lg"></i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right"
                                             aria-labelledby="dropdownMenuLink6">
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table marketing-table remove-table-border mb-0">
                                        <tbody>
                                        <tr>
                                            <td class="size-50">
                                                <div class="size-40 bg-indigo lighten-1 d-flex-xy-ctr">
                                                    <i class="zmdi zmdi-facebook text-white zmdi-hc-2x"></i>
                                                </div>
                                            </td>
                                            <td>
                                                <h5 class="user-name">Facebook Ads</h5>
                                                <p class="user-description">63 Likes, 387 Shares</p>
                                            </td>
                                            <td class="text-center">
                                                <div class=" text-green text-accent-4 font-13">
                                                    <i class="zmdi zmdi-trending-up d-block"></i>
                                                    +20%
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="size-50">
                                                <div class="size-40 bg-light-blue accent-2 d-flex-xy-ctr">
                                                    <i class="zmdi zmdi-twitter text-white zmdi-hc-2x"></i>
                                                </div>
                                            </td>
                                            <td>
                                                <h5 class="user-name">Twitter Ads</h5>
                                                <p class="user-description">43 Likes, 545 Shares</p>
                                            </td>
                                            <td class="text-center">
                                                <div class="text-danger font-13">
                                                    <i class="zmdi zmdi-trending-down d-block"></i>
                                                    -05%
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="size-50">
                                                <div class="size-40 bg-brown lighten-1 d-flex-xy-ctr">
                                                    <i class="zmdi zmdi-instagram text-white zmdi-hc-2x"></i>
                                                </div>
                                            </td>
                                            <td>
                                                <h5 class="user-name">Instagram</h5>
                                                <p class="user-description">83 Follows, 210 Likes</p>
                                            </td>
                                            <td class="text-center">
                                                <div class=" text-green text-accent-4 font-13">
                                                    <i class="zmdi zmdi-trending-up d-block"></i>
                                                    +11%
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="size-50">
                                                <div class="size-40 bg-light-blue darken-3 d-flex-xy-ctr">
                                                    <i class="zmdi zmdi-linkedin text-white zmdi-hc-2x"></i>
                                                </div>
                                            </td>
                                            <td>
                                                <h5 class="user-name">LinkedIn</h5>
                                                <p class="user-description">23 Follows, 764 Likes</p>
                                            </td>
                                            <td class="text-center">
                                                <div class=" text-green text-accent-4 font-13">
                                                    <i class="zmdi zmdi-trending-up d-block"></i>
                                                    +67%
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8 col-12">
                            <div class="gx-card gx-card-full-height">
                                <div class="gx-card-header d-flex">
                                    <div class="mr-auto">
                                        <h3 class="card-heading">Weather Today</h3>
                                        <p class="sub-heading">Monday, 12:45 PM, Mostly Sunny</p>
                                    </div>
                                    <div class="dropdown">
                                        <a class="dropdown-more dropdown-toggle" href="#" role="button"
                                           id="dropdownMenuLink7" data-toggle="dropdown" aria-haspopup="true"
                                           aria-expanded="false">
                                            <i class="zmdi zmdi-chevron-down zmdi-hc-fw zmdi-hc-lg"></i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right"
                                             aria-labelledby="dropdownMenuLink7">
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="gx-card-body">
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <div class="temp-section mb-4 mb-sm-0 text-center text-sm-left">
                                                <p>
                                                    <img src="images/sun-lg.png" alt="Sun">
                                                </p>
                                                <h2 class="temp-point">32.3 <sup>0</sup>C</h2>
                                            </div>
                                        </div>
                                        <div class="col-sm-7 text-center text-sm-left">
                                            <ul class="temp-week-list">
                                                <li>
                                                    <img class="img-fluid" src="images/sun.png" alt="Sun">
                                                    <span class="temp-point">23 <sup>0</sup>C</span>
                                                    <span class="date">Thursday, 4 Nov</span>
                                                </li>
                                                <li>
                                                    <img class="img-fluid" src="images/sunny.png" alt="Sunny">
                                                    <span class="temp-point">34 <sup>0</sup>C</span>
                                                    <span class="date">Wednesday, 3 Nov</span>
                                                </li>
                                                <li>
                                                    <img class="img-fluid" src="images/clouds.png" alt="Cloud">
                                                    <span class="temp-point">20 <sup>0</sup>C</span>
                                                    <span class="date">Tuesday, 2 Nov</span>
                                                </li>
                                                <li>
                                                    <img class="img-fluid" src="images/sunny.png" alt="Sunny">
                                                    <span class="temp-point">28 <sup>0</sup>C</span>
                                                    <span class="date">Monday, 1 Nov</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="gx-card gx-card-full-height">
                                <p class="sub-heading">Word Of The Day</p>

                                <h2 class="mb-2">Be-nev-o-lent</h2>
                                <span class="text-grey d-inline-block mb-4">adjective</span>

                                <p class="h3 mb-lg-4 mb-3">Well meaning and kindly “a benevolent smile”</p>

                                <a class="card-link" href="javascript:void(0)">Learn More Words</a>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="gx-card">
                                <div class="gx-card-header d-sm-flex justify-content-sm-between">
                                    <div class="order-sm-1 float-left float-sm-none">
                                        <h3 class="card-heading">Sales Report</h3>
                                        <p class="sub-heading">This Financial Year</p>
                                    </div>

                                    <div class="dropdown order-sm-3 float-right float-sm-none">
                                        <a class="dropdown-more dropdown-toggle" href="#" role="button"
                                           id="dropdownMenuLink8" data-toggle="dropdown" aria-haspopup="true"
                                           aria-expanded="false">
                                            <i class="zmdi zmdi-chevron-down zmdi-hc-fw zmdi-hc-lg"></i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right"
                                             aria-labelledby="dropdownMenuLink8">
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                        </div>
                                    </div>

                                    <ul class="nav nav-pill-sales order-sm-2" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-toggle="tab" href="#all" role="tab"
                                               aria-controls="chatList" aria-selected="true">All</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#europe" role="tab"
                                               aria-controls="contacts" aria-selected="true">Europe</a>
                                        </li>
                                    </ul>
                                </div>

                                <div class="tab-content">
                                    <div id="all" class="tab-pane active">
                                        <div class="row">
                                            <div class="col-xl-3 col-12">
                                                <div class="height-150 mb-4 mb-xl-0">
                                                    <canvas id="doughnut-chart"></canvas>
                                                </div>
                                            </div>

                                            <div class="col-xl-9 col-12">
                                                <p class="text-grey">
                                                    Dummy content Cenas in erat accumsan, hendrerit lorem vel, pulvinar
                                                    odio. Quisque
                                                    eu conva. hendrerit lorem vel, pulvinar odio. Quisque eu conva.
                                                </p>
                                                <div class="row mb-3 mb-md-5 mb-xl-0">
                                                    <div class="col-sm-6 col-12">
                                                        <div class=" d-flex align-items-center">
                                                            <i class="flag flag-32 flag-gb"></i>
                                                            <div class="px-3">United Kingdom</div>
                                                            <span class="ml-auto px-3 badge-pill badge badge-outline">27</span>
                                                        </div>
                                                        <div class="d-flex align-items-center"><i
                                                                class="flag flag-32 flag-fr"></i>
                                                            <div class="px-3">France</div>
                                                            <span class="ml-auto px-3 badge-pill badge badge-outline">81</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-12">
                                                        <div class=" d-flex align-items-center">
                                                            <i class="flag flag-32 flag-de"></i>
                                                            <div class="px-3">Germany</div>
                                                            <span class="ml-auto px-3 badge-pill badge badge-outline">33</span>
                                                        </div>
                                                        <div class="d-flex align-items-center">
                                                            <i class="flag flag-32 flag-es"></i>
                                                            <div class="px-3">Spain</div>
                                                            <span class="ml-auto px-3 badge-pill badge badge-outline">79</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="europe" class="tab-pane">
                                        <div class="row">
                                            <div class="col-xl-3 col-12">
                                                <div class="height-150 mb-4 mb-xl-0">
                                                    <canvas id="doughnut-chart-2"></canvas>
                                                </div>
                                            </div>

                                            <div class="col-xl-9 col-12">
                                                <p class="text-grey">
                                                    Dummy content Cenas in erat accumsan, hendrerit lorem vel, pulvinar
                                                    odio. Quisque
                                                    eu conva. hendrerit lorem vel, pulvinar odio. Quisque eu conva.
                                                </p>
                                                <div class="row mb-3 mb-md-5 mb-xl-0">
                                                    <div class="col-sm-6 col-12">
                                                        <div class=" d-flex align-items-center">
                                                            <i class="flag flag-32 flag-england"></i>
                                                            <div class="px-3">England</div>
                                                            <span class="ml-auto px-3 badge-pill badge badge-outline">27</span>
                                                        </div>
                                                        <div class="d-flex align-items-center"><i
                                                                class="flag flag-32 flag-ar"></i>
                                                            <div class="px-3">Argentina</div>
                                                            <span class="ml-auto px-3 badge-pill badge badge-outline">81</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-12">
                                                        <div class=" d-flex align-items-center">
                                                            <i class="flag flag-32 flag-cn"></i>
                                                            <div class="px-3">China</div>
                                                            <span class="ml-auto px-3 badge-pill badge badge-outline">33</span>
                                                        </div>
                                                        <div class="d-flex align-items-center">
                                                            <i class="flag flag-32 flag-in"></i>
                                                            <div class="px-3">India</div>
                                                            <span class="ml-auto px-3 badge-pill badge badge-outline">79</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/gx-wrapper-->
@endsection
