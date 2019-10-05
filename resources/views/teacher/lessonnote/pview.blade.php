@extends('layouts.dashboard')

@section('principal')
  <!--gx-wrapper-->
  <div class="gx-wrapper">

<div class="animated slideInUpTiny animation-duration-3">

    <div class="page-heading">
        <h2 class="title">View Lessonnote Data from Teachers</h2>
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
                                <th>Teacher Name </th>
                                <th>Lessonnote Name </th>
                                <th>Current Status</th>
                                <th> Current Perfomance </th>  
                                <th> Your Action</th>                             
                                <th>View</th>
                            </tr>
                            </thead>
                            <tbody id="tbody1">
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

       // let datebox = $('#attdate').val();
        let xhr = new XMLHttpRequest();
        xhr.open('POST', '/lessonnotes_viewLsnAll/'+teacher);
        xhr.responseType = 'json';
        let formData = new FormData();
        //formData.append("_date", datebox);
        formData.append("api_token", token);
       // document.getElementById('loading').style.display = 'block';
        xhr.send(formData);

        xhr.onload = function() {
            let responseObj = xhr.response;
            if (responseObj.status === "Failed"){
                alert(responseObj.message);
               // document.getElementById('loading').style.display = 'none';
                return;
            }
            
            let attend = responseObj.data;
            var i = 0;
           
            for ( let datarow of attend ){ 
                var tablex = document.getElementById('tbody1').insertRow(i);
               // tablex.empty();
               // tablex;
                var cell0 = tablex.insertCell(0);
                var cell1 = tablex.insertCell(1);
                var cell2 = tablex.insertCell(2);
                var cell3 = tablex.insertCell(3);
                var cell4 = tablex.insertCell(4);
                var cell5 = tablex.insertCell(5);
                var cell6 = tablex.insertCell(6);
               
                let mycell4 = "";

                tablex.className = "datarow";
                cell0.innerHTML = "<strong>"+ (i + 1) + "</strong>";
                cell1.innerHTML = "<strong>"+ datarow.Teacher + "</strong>";
                
                cell2.innerHTML = "<strong>"+ datarow.Title + "</strong>";
                cell3.innerHTML = "<strong>"+ datarow.Status + "</strong>";                
                cell4.innerHTML = "<strong>"+ datarow.Perf + "%" + "</strong>";

                if (datarow.Status === "SUBMITTED.."){
                    mycell4 += "<strong> <a class='btn btn-primary white' onclick='changeStatus("+ datarow.id + ", 5)' >  Approve!! </a> </strong> <br>"; 
                    mycell4 += "<strong> <a class='btn btn-warning white' onclick='changeStatus("+ datarow.id + ", 4)' >  Decline!! </a> </strong> <br>";                 
                }
                else if (datarow.Status === "RESUBMITTED.."){
                    mycell4 += "<strong> <a class='btn btn-primary white' onclick='changeStatus("+ datarow.id + ", 5)' >  Approve!! </a> </strong> <br>"; 
                    mycell4 += "<strong> <a class='btn btn-warning white' onclick='changeStatus("+ datarow.id + ", 4)' >  Decline!! </a> </strong> <br>";                  
                }

                else if (datarow.Status === "REJECTED.."){
                    mycell4 += "<strong> <a class='btn btn-secondary white' >  Reverted!! </a> </strong> <br>"; 
                }

                else if (datarow.Status === "APPROVED.."){
                    mycell4 += "<strong> <a class='btn btn-info white' >  Approved!! </a> </strong> <br>"; 
                }

                cell5.innerHTML =  mycell4;
                cell6.innerHTML = `<strong> <a class="btn btn-success" href="{{ asset('storage/LessonNote/${teacher}/Template/${datarow.Filez}') }}" >  View File </a> </strong> <br>`;                 
              
                i++;
            }
            //document.getElementById('loading').style.display = 'none';
        } 
    
</script>
@endsection

@section('myscript')
    <script>
         function changeStatus(lsn,idx){       
            let xhr = new XMLHttpRequest();
            xhr.open('POST', '/lessonnotes_statusLsn/'+lsn+'/id/'+idx);
            xhr.responseType = 'json';
            let formData = new FormData();
            formData.append("api_token", token);
            xhr.send(formData);

            xhr.onload = function() {
                let responseObj = xhr.response;
                if (responseObj.status === "Failed"){
                    alert(responseObj.message);
                    document.getElementById('loading').style.display = 'none';
                    return;
                }
                alert(responseObj.message);
                
            }
         }
    </script>
@endsection
