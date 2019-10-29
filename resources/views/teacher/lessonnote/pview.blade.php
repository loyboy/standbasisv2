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

                        <table id="mydatatable" class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Teacher Name </th>
                                <th>Lessonnote Name </th>
                                <th>Current Status</th>
                                <th> Current Quality </th>
                                <th> No. of Trips </th>  
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

    function approveLsn(idx){

        $('#headapprovelsn').modal({
                    backdrop: 'static',
                    keyboard: false
                },'show');
        $('#head_modal_lsn_id').val(idx);
    }

    function disapproveAtt(idx){
        $('#comment_box').removeAttr('disabled');
        $('#headapprovelsn2').modal({
                backdrop: 'static',
                keyboard: false
            },'show');
        $('#head_modal_lsn_id2').val(idx);
    }

    function attendLsn(){
    console.log("Lessonnote approved");

    let val = $('#head_modal_lsn_id').val();
    let com = "Lessonnote has been approved!";
    let lesson = 5;  

    var obj =  $(this).find('.attendlist');
        $.ajax({
            type: "POST",           
            url: "/lessonnotes_attendAtt/"+val,
            dataType: "json",
            data: {
                api_token:token , comment: com, decision: attend
            }, 
            success: function(data) {               
                var data = JSON.parse(JSON.stringify(data));
                alert(data.message);
                let myhtml = "<strong> <a class='btn btn-info' > Approved by You </a> </strong> ";
                obj.html(myhtml);

                $('#headapprovelsn').modal({
                        backdrop: 'static',
                        keyboard: false
                    },'hide');
            }
        });
    }

function attendAttDis(){

let val = $('#head_modal_att_id2').val();
let com = $('#comment_box').val();
let lesson = 4;

$.ajax({
    type: "POST",           
    url: "/lessonnotes_attendAtt/"+val,
    dataType: "json",
    data: {
        api_token:token , comment: com, decision: attend
    }, 
    success: function(data) {               
      var data = JSON.parse(JSON.stringify(data));
      
       alert(data.message);

       $('#headapprovelsn2').modal({
             backdrop: 'static',
             keyboard: false
         },'hide');
    }
}); 

}

</script>
@endsection

@section('myscript')
    <script>
     $(document).ready(function(){ 
        
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
                return;
            }
            
            let attend = responseObj.data;
            var i = 0;
           
            var table = $('#mydatatable').DataTable({
                "columns": [
                    { "orderable": false },
                    { "orderable": false },
                    { "orderable": false },
                    null,
                    null,
                    null,
                    { "orderable": false }
                ]     
            });

            for ( let datarow of attend ){ 
               // var tablex = document.getElementById('tbody1').insertRow(i);
               // tablex.empty();
               // tablex;
                var cell0 = "";
                var cell1 = "";
                var cell2 = "";
                var cell3 = "";
                var cell4 = "";
                var cell5 = "";
                var cell6 = "";
                var cell7 = "";
               
                let mycell4 = "";

              //  tablex.className = "datarow";
                cell0 = "<td> <strong>"+ (i + 1) + "</strong> </td> ";
                cell1 = "<td> <strong>"+ datarow.Teacher + "</strong> </td>";
                
                cell2 = "<td> <strong>"+ datarow.Title + "</strong> </td>";
                cell3 = "<td> <strong>"+ datarow.Status + "</strong> </td>";                
                cell4 = "<td> <strong>"+ datarow.Perf + "%" + "</strong> </td>";

                if (datarow.Status === "SUBMITTED.."){
                    mycell4 += "<td> <strong> <a class='btn btn-primary white' onclick='changeStatus("+ datarow.id + ", 5)' >  Approve!! </a> </strong> <br>"; 
                    mycell4 += "<strong> <a class='btn btn-warning white' onclick='changeStatus("+ datarow.id + ", 4)' >  Decline!! </a> </strong> <br> </td>";                 
                }
                else if (datarow.Status === "RE-SUBMITTED.."){
                    mycell4 += "<td> <strong> <a class='btn btn-primary white' onclick='changeStatus("+ datarow.id + ", 5)' >  Approve!! </a> </strong> <br>"; 
                    mycell4 += "<strong> <a class='btn btn-warning white' onclick='changeStatus("+ datarow.id + ", 4)' >  Decline!! </a> </strong> <br> </td>";                  
                }

                else if (datarow.Status === "REJECTED.."){
                    mycell4 += "<td> <strong> <a class='btn btn-secondary white' onclick='alert( Your Comment: \r\r " + datarow.Comment + ")' >  Reverted!!  </a> </strong> <br> </td>"; 
                }

                else if (datarow.Status === "APPROVED.."){
                    mycell4 += "<td> <strong> <a class='btn btn-info white' >  Approved!! </a> </strong> <br> </td>"; 
                }

                cell5 =  mycell4;
                cell6 = `<td> <strong> <a class="btn btn-success" href="{{ asset('storage/LessonNote/${datarow.TeacherID}/Template/${datarow.Filez}') }}" >  View File </a> </strong> <br> </td>`;                 
              
                cell7 = "<td> <strong>"+ datarow.Cycle + "</strong> </td>";
                var newRow = "<tr>" + cell0 + cell1 + cell2 + cell3 + cell4 + cell7 + cell5 + cell6 + "</tr>";
                
                table.row.add($(newRow)).draw();

                i++;
            }
            //document.getElementById('loading').style.display = 'none';
        } 

     });


         function changeStatus(lsn,idx){      
             if (Number(idx) === 5){                
                if ( window.confirm("Are you sure you want to Approve this lessonnote?") ) {
                    let xhr = new XMLHttpRequest();
                    xhr.open('POST', '/lessonnotes_statusLsn/'+lsn+'/id/'+idx);
                    xhr.responseType = 'json';
                    let formData = new FormData();
                    formData.append("api_token", token);
                    formData.append("teacher", teacher);
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
            } 

            else if (Number(idx) === 4){
                var comment = prompt("Please enter your Comment(s) About this Lessonnote..");
                
                if (comment === null){ alert("You cancelled this action!"); return; }
                
                if ( comment !== "" || comment !== null ) {
                    let xhr = new XMLHttpRequest();
                    xhr.open('POST', '/lessonnotes_statusLsn/'+lsn+'/id/'+idx);
                    xhr.responseType = 'json';
                    let formData = new FormData();
                    formData.append("api_token", token);
                    formData.append("teacher", teacher);
                    formData.append("comment", comment);
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
            }
           
         }
    </script>
@endsection

@include('teacher.modals.modal')