@extends('layouts.dashboard')

@section('content')
<div class="gx-wrapper">

<div class="animated slideInUpTiny animation-duration-3">

    <div class="page-heading">
        <h2 class="title">Welcome to Standbasis!!</h2>
    </div> 

    <div class="gx-entry-header"><h3 class="entry-heading">What would you like to do?</h3></div>

    <div class="row">
                        <div class="col-xl-4 col-sm-6 col-12">
                            <div class="bg-primary text-white card">
                                <div class="card-header border-0">Take Attendance of A Class</div>
                                <div class="card-body">
                                <h5 class="card-title mb-3">Here you Can:</h5>
                                    <h6 class="card-subtitle mb-4 text-white">Take Attendance</h6>
                                    <h6 class="card-subtitle mb-4 text-white">Snap Attendance Picture</h6>
                                    <h6 class="card-subtitle mb-4 text-white">Submit Attendance</h6>
                                    
                                        <a href="javascript:void(0)" class="btn btn-light">Click Here</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-4 col-sm-6 col-12">
                            <div class="bg-success text-white card">
                                <div class="card-header border-0">Submit A Lessonnote</div>
                                <div class="card-body">
                                <h5 class="card-title mb-3">Here you Can:</h5>
                                    <h6 class="card-subtitle mb-4 text-white">Download Lessonnote Template</h6>
                                    <h6 class="card-subtitle mb-4 text-white">Submit Lessnnote File</h6>
                                    <h6 class="card-subtitle mb-4 text-white">Review Lessonnote File</h6>
                                    
                                        <a href="javascript:void(0)" class="btn btn-light">Click Here</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-sm-6 col-12">
                            <div class="bg-danger text-white card">
                                <div class="card-header border-0">View Measurement & Evaluation</div>
                                <div class="card-body">
                                <h5 class="card-title mb-3">Here you Can:</h5>
                                    <h6 class="card-subtitle mb-4 text-white">View Attendance Flags</h6>
                                    <h6 class="card-subtitle mb-4 text-white">View Lessonnote Flags</h6>
                                    <h6 class="card-subtitle mb-4 text-white">Print Reports of Your Performance</h6>
                                    
                                        <a href="javascript:void(0)" class="btn btn-light">Click Here</a>
                                </div>
                            </div>
                        </div>
                      
                    </div>

</div>
@endsection