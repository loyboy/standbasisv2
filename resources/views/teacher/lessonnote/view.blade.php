@extends('layouts.dashboard')

@section('content')
  <!--gx-wrapper-->
  <div class="gx-wrapper">

<div class="animated slideInUpTiny animation-duration-3">

    <div class="page-heading">
        <h2 class="title">View Lessonnote Data</h2>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="gx-card">
                <div class="gx-card-header">
                    <h3 class="card-heading">All lessonnotes and thier Status updates for this Term</h3>
                </div>
                <div class="gx-card-body">
                    <div class="table-responsive">

                        <table class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Lessonnote Name </th>
                                <th>Status</th>
                                <th>Cycles</th>
                                <th>Last Action date</th>
                                <th>Last Action</th>
                                <th>View</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="gradeX">
                                <td>1</td>
                                <td>English JSS1 2019 1st term</td>
                                <td>Fresh</td>
                                <td>0</td>
                                <td>2019-09-12</td>
                                <td>Submitted</td>
                                <td>Click Here</td>
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