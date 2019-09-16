@extends('layouts.dashboard')

@section('content')
  <!--gx-wrapper-->
  <div class="gx-wrapper">

<div class="animated slideInUpTiny animation-duration-3">

    <div class="page-heading">
        <h2 class="title">Add Scores to an Assessment</h2>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="gx-card">
                <div class="gx-card-header">
                    <h3 class="card-heading">Choose a Lessonnote Assessment and Add Scores to them</h3>
                </div>
                <div class="gx-card-body">
                    <div class="table-responsive">

                        <table class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Lessonnote Name </th>
                                <th>Classwork</th>
                                <th>Assignment</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="gradeX">
                                <td>1</td>
                                <td>English JSS1 2019 1st term</td>
                                <td>Add Scores</td>
                                <td>Add Scores</td>
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