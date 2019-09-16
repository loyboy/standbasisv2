@extends('layouts.dashboard')

@section('content')
  <!--gx-wrapper-->
  <div class="gx-wrapper">

<div class="animated slideInUpTiny animation-duration-3">

    <div class="page-heading">
        <h2 class="title">View Attendance Data</h2>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="gx-card">
                <div class="gx-card-header">
                    <h3 class="card-heading">All attendances done by you up to date</h3>
                </div>
                <div class="gx-card-body">
                    <div class="table-responsive">

                        <table class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Subject & Class</th>
                                <th>Expected Time</th>
                                <th>Actual Time</th>
                                <th>Time Difference</th>
                                <th>Photo</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="gradeX">
                                <td>1</td>
                                <td>English & JSS1A</td>
                                <td>8:10:00 AM</td>
                                <td>8:05:00 AM</td>
                                <td>5 minutes late</td>
                                <td>View</td>
                            </tr>              
                            </tfoot>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
<!--/gx-wrapper-->
@endsection