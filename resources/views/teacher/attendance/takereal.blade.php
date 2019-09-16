@extends('layouts.dashboard')

@section('content')
<div class="gx-wrapper">

    <div class="animated slideInUpTiny animation-duration-3">

        <div class="page-heading">
            <h2 class="title">Attendance</h2>
        </div> 

        <div class="gx-entry-header"><h3 class="entry-heading"> Take An Attendance </h3></div>

        <div class="row">
            <div class="col-xl-12 col-sm-12">

                <div class="gx-card">
                    <div class="gx-card-header">
                        <h3 class="card-heading">Attendance Parameters</h3>
                        <p class="sub-heading">View Your Class Information</p>
                    </div>
                                <div class="gx-card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered mb-0">
                                            <thead>
                                            <tr>
                                                <th class="text-uppercase" scope="col"> Parameter</th>
                                                <th class="text-uppercase" scope="col"> Value</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <th scope="row">Teacher</th>
                                                <td>Chris</td>
                                              
                                            </tr>
                                            <tr>
                                                <th scope="row">Class Name</th>
                                                <td>Domnic</td>
                                               
                                            </tr>

                                            <tr>
                                                <th scope="row">Expected Time</th>
                                                <td>Domnic</td>
                                               
                                            </tr>

                                            <tr>
                                                <th scope="row">Subject</th>
                                                <td>Domnic</td>
                                               
                                            </tr>
                                          
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>  

                       
                            <div class="col-xl-12">
                            <div class="gx-entry-header">
                                <h3 class="entry-heading">Your Students</h3>
                            </div>
                            <div class="gx-card mb-0 px-0 pt-2 pb-3">
                                <div class="gx-card-body">
                                    <div class="table-responsive">
                                        <table class="table default-table table-hover">
                                            <thead> 
                                            <tr>
                                                <th class="text-uppercase" scope="col"> Name of Student</th>
                                                <th class="text-uppercase" scope="col"> Present?</th>
                                                <th class="text-uppercase" scope="col"> Excused?</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                             <tr>                                              
                                                <td>
                                                    <strong>Pupil Name</strong>
                                                </td>
                                                <td class="text-center">
                                                    <div class="form-checkbox">
                                                    <input type="checkbox" value="value2" name="name2">
                                                        
                                                    </div>
                                                 </td>
                                            
                                                <td class="text-center">
                                                    <i class="zmdi zmdi-check zmdi-hc-fw zmdi-hc-lg text-success"></i>
                                                </td>
                                            </tr>
                                           
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        
                    </div> 
        </div>

    </div>

</div>
@endsection