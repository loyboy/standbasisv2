@extends('layouts.dashboard')

@section('teacher')
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

                        <table id = "mydatatable" class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Lessonnote Name </th>
                                <th>Classwork</th>
                                <th>Assignment</th>
                            </tr>
                            </thead>

                            <tbody>
                                            
                            </tbody>

                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
<!--/gx-wrapper-->
<script>
    let teacher = {{ Auth::user()->teacher_id }};
    let token = '{{ Auth::user()->api_token }}'; 
</script>
@endsection

@section('myscript')
<script>
 $(document).ready(function(){ 

       // let datebox = $('#attdate').val();
        let xhr = new XMLHttpRequest();
        xhr.open('POST', '/lessonnotes_viewLsnScores/'+teacher);
        xhr.responseType = 'json';
        let formData = new FormData();
        formData.append("api_token", token);
    
        xhr.send(formData);

        xhr.onload = function() {
            let responseObj = xhr.response;
            if (responseObj.status === "Failed"){
                alert(responseObj.message);
                return;
            }
            
            let attend = responseObj.data;
            var i = 0;
           
            var table = $('#mydatatable').DataTable({});

            for ( let datarow of attend ){ 
                var cell0 = "";
                var cell1 = "";
                var cell2 = "";
                var cell3 = "";

                cell0 = "<td> <strong>"+ (i + 1) + "</strong> </td> ";
                cell1 = "<td> <strong>"+ datarow.Title + "</strong> </td>";

                if (datarow.Clswork === "0"){                    
                    cell2 += "<td> <strong> <a href='#' class='btn btn-primary white' onclick='addScores("+ datarow.id + ", 1)' >  Add Classwork Scores </a> </strong> <br>"; 
                }
                else{                    
                    cell2 += "<td> <strong> <a href='#' class='btn btn-primary white' >" + datarow.Clswork + " </a> </strong> <br>";                 
                }

                if (datarow.Hmwork === "0"){                    
                    cell3 += "<td> <strong> <a href='#' class='btn btn-primary white' onclick='addScores("+ datarow.id + ", 2)' >  Add Homework Scores </a> </strong> <br>"; 
                }
                else{                    
                    cell3 += "<td> <strong> <a href='#' class='btn btn-primary white' >" + datarow.Hmwork + " </a> </strong> <br>";                 
                }
        
              
                var newRow = "<tr>" + cell0 + cell1 + cell2 + cell3 + "</tr>";
                
                table.row.add($(newRow)).draw();

                i++;
            }
        
        }
  });

    function addScores(lsn, task){
        
        let xhr = new XMLHttpRequest();
        xhr.open('POST', '/lessonnotes_getScores/'+lsn+'/task/'+task);
        xhr.responseType = 'json';
        let formData = new FormData();
        formData.append("api_token", token);
        document.getElementById('loading').style.display = 'block';
    
        xhr.send(formData);

        xhr.onload = function() {
            let responseObj = xhr.response;
            if (responseObj.status === "Failed"){
                document.getElementById('loading').style.display = 'none';    
                alert(responseObj.message);
                return;
            }

            document.getElementById('loading').style.display = 'none';
            
            $('#teacheraddscores').modal({
                     backdrop: 'static',
                     keyboard: false
            },'show');
    
        }    
    }
</script>
@endsection

@include('teacher.modals.modal')