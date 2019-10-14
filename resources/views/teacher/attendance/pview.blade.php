@extends('layouts.dashboard')

@section('principal')
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
                    <h3 class="card-heading">All attendances done by Teachers up to date</h3>
                </div>
               
                <div class="gx-card-body">
                
                    <div class=""> 

                            <div class="form-group row">
                                <label class="col-md-4 col-sm-3 control-label">Search Date</label>
                                    <div class="col-md-4 col-sm-6">
                                        <input type="date" value="" class="form-control" onchange="getAttendanceOnChange()" id="attdate"/>
                                    </div>
                            </div>

                           <!-- <div class="form-group row">
                                <label class="col-md-4 col-sm-3 control-label">Search SubjectClass</label>
                                    <div class="col-md-4 col-sm-6">
                                        <select class="form-control" id="subclass" onchange="getAttendanceOnChange()">

                                        </select>
                                    </div>
                            </div>-->
                    </div>

                    <div class="table-responsive" style=" height: 400px; overflow-y: auto; overflow-x: hidden; ">

                        <table id="mydatatable" class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Teacher Name</th>
                                <th>Subject & Class</th>
                                <th>Class Time</th>
                                <th>Submission Time</th>
                                <th>Class Performance</th>
                                <th>View</th>
                                <th>Your Action</th>
                            </tr>
                            </thead>
                            <tbody id="tbody1" style="overflow: scroll; ">
                                <tr> <td colspan="8" style="text-align: center;"> Attendance data will display here...  </td> </tr>
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
    let datex = {{ date('Y-m-d') }};
    
        let xhr = new XMLHttpRequest();
        xhr.open('POST', '/attendances_viewAttAll/'+teacher);
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

            if (attend.length === 0) {
                alert("There is no value to display, because you have no data.");
               // document.getElementById('loading').style.display = 'none';
                return;
            }
            //$('#mydatatable').find('tbody').empty();

            for ( let datarow of attend ){ 
                var tablex = document.getElementById('tbody1').insertRow(i);               
                //tablex.;
                var cell0 = tablex.insertCell(0);
                var cell1 = tablex.insertCell(1);
                var cell2 = tablex.insertCell(2);
                var cell3 = tablex.insertCell(3);
                var cell4 = tablex.insertCell(4);
                var cell5 = tablex.insertCell(5);
                var cell6 = tablex.insertCell(6);
                var cell7 = tablex.insertCell(7);
                
                tablex.className = "datarow";
                cell7.className = "attendlist";

                cell0.innerHTML = "<strong>"+ (i + 1) + "</strong>";
                cell1.innerHTML = "<strong>"+ datarow.Teacher + "</strong>";
                cell2.innerHTML = "<strong>"+ datarow.Subclass + "</strong>";
                cell3.innerHTML = "<strong>"+ datarow.ExpTime + "</strong>";
                cell4.innerHTML = "<strong>"+ datarow.ActTime + "</strong>";
                cell5.innerHTML = "<strong>"+ datarow.Perf + " %" + "</strong>";
                
                cell6.innerHTML = "<strong> <a class='btn btn-primary' onclick='showAttendance("+datarow.id+")' > View Attendance </a> </strong>";

                var ht1 = "";
                if ( datarow.Action === "No action yet"){
                    cell7.innerHTML = "<strong> <a class='btn btn-success' onclick='approveAtt("+datarow.id+")' > Approve </a> <br> <a class='btn btn-danger' onclick='disapproveAtt("+datarow.id+")' > Disapprove </a>  </strong> ";
 
                }

                else if ( datarow.Action === "Approved"){
                    cell7.innerHTML = "<strong> <a class='btn btn-success' > Approved by You </a> </strong> ";
 
                }

                else if ( datarow.Action === "Declined"){
                    cell7.innerHTML = "<strong> <a class='btn btn-danger' onclick='viewComment("+datarow.id+")' > Declined by You, View Comment </a> </strong> ";
 
                }
               //cell3.innerHTML = "<div class='form-checkbox'> <input type='checkbox' disabled name='excused[]' id='stad" + datarow.PupilID + "' class='excusedform' value='yes'> </div>";
                i++;
            }
        } 
</script>
@endsection


@section('myscript')
<script>
    
    function showAttendance(idx){
         $.ajax({
            type: "POST",           
            url: "/attendances_viewAttLog/"+idx,
            dataType: "json",
            data: {
                api_token:token 
            }, 
            success: function(data) {               
              var stx = JSON.parse(JSON.stringify(data));
              $('#attimg').attr("src",stx._IMAGE);
             // $('#comments_attendance').html(stx._COMMENT);
              $('#myatt_table').html(stx._TEXT);
              $('#mytitle').html(stx._TITLE);
              $('#getattendance').modal({
                     backdrop: 'static',
                     keyboard: false
                 },'show');
             console.log("Do something in Attendacne"); 
               
            }
        });     
    }

    function approveAtt(idx){

        $('#headapproveatt').modal({
                     backdrop: 'static',
                     keyboard: false
                 },'show');
        $('#head_modal_att_id').val(idx);
    }

    function disapproveAtt(idx){
        $('#comment_box').removeAttr('disabled');
        $('#headapproveatt2').modal({
                backdrop: 'static',
                keyboard: false
            },'show');
        $('#head_modal_att_id2').val(idx);
    }

   function attendAtt(){
    console.log("Attendance approved");
    
    let val = $('#head_modal_att_id').val();
    let com = "Attendance has been approved!";
    let attend = 1;  


    var obj =  $(this).find('.attendlist');
    $.ajax({
            type: "POST",           
            url: "/attendances_attendAtt/"+val,
            dataType: "json",
            data: {
                api_token:token , comment: com, decision: attend
            }, 
            success: function(data) {               
              var data = JSON.parse(JSON.stringify(data));
               alert(data.message);
               let myhtml = "<strong> <a class='btn btn-info' > Approved by You </a> </strong> ";
               obj.html(myhtml);

               $('#headapproveatt').modal({
                     backdrop: 'static',
                     keyboard: false
                 },'hide');
            }
    }); 



   }

   function attendAttDis(){
    
    let val = $('#head_modal_att_id2').val();
    let com = $('#comment_box').val();
    let attend = 0;

    $.ajax({
            type: "POST",           
            url: "/attendances_attendAtt/"+val,
            dataType: "json",
            data: {
                api_token:token , comment: com, decision: attend
            }, 
            success: function(data) {               
              var data = JSON.parse(JSON.stringify(data));
              
               alert(data.message);

               $('#headapproveatt2').modal({
                     backdrop: 'static',
                     keyboard: false
                 },'hide');
            }
    }); 
    
   }

   function viewComment(idx){
        $.ajax({
                type: "POST",           
                url: "/attendances_attendAttComment/"+idx,
                dataType: "json",
                data: {
                    api_token:token
                }, 
                success: function(data) {               
                var dataval = JSON.parse(JSON.stringify(data));
                
                $('#comment_box').attr('disabled','disabled');
                $('#headapproveatt2').modal({
                        backdrop: 'static',
                        keyboard: false
                    },'show');

                $('#comment_box').val(dataval.Comment);
                }
        }); 
   }

   function getAttendanceOnChange(){
        let datebox = $('#attdate').val();
        //let subclassbox = $('#subclass').val();
        let xhr = new XMLHttpRequest();
        xhr.open('POST', '/attendances_viewAttAll/'+teacher);
        xhr.responseType = 'json';
        let formData = new FormData();
        formData.append("_date", datebox);
       // formData.append("subclass", subclassbox);
        formData.append("api_token", token);
        document.getElementById('loading').style.display = 'block';
        xhr.send(formData);

        xhr.onload = function() {
            let responseObj = xhr.response;
            if (responseObj.status === "Failed"){
                alert(responseObj.message);
                document.getElementById('loading').style.display = 'none';
                return;
            }
            let attend = responseObj.data;
            var i = 0;
            
            if (attend.length === 0) {
                alert("There is no value for that Date. Try another date, Thank you.");
                document.getElementById('loading').style.display = 'none';
                return;
            }
            $('#mydatatable').find('tbody').empty();
            for ( let datarow of attend ){ 
                var tablex = document.getElementById('tbody1').insertRow(i);               
                //tablex.;
                var cell0 = tablex.insertCell(0);
                var cell1 = tablex.insertCell(1);
                var cell2 = tablex.insertCell(2);
                var cell3 = tablex.insertCell(3);
                var cell4 = tablex.insertCell(4);
                var cell5 = tablex.insertCell(5);
                var cell6 = tablex.insertCell(6);
                var cell7 = tablex.insertCell(7);
                
                tablex.className = "datarow";
                cell7.className = "attendlist";

                cell0.innerHTML = "<strong>"+ (i + 1) + "</strong>";
                cell1.innerHTML = "<strong>"+ datarow.Teacher + "</strong>";
                cell2.innerHTML = "<strong>"+ datarow.Subclass + "</strong>";
                cell3.innerHTML = "<strong>"+ datarow.ExpTime + "</strong>";
                cell4.innerHTML = "<strong>"+ datarow.ActTime + "</strong>";
                cell5.innerHTML = "<strong>"+ datarow.Perf + " %" + "</strong>";
                
                cell6.innerHTML = "<strong> <a class='btn btn-primary' onclick='showAttendance("+datarow.id+")' > View Attendance </a> </strong>";

                var ht1 = "";
                if ( datarow.Action === "No action yet"){
                    cell7.innerHTML = "<strong> <a class='btn btn-success' onclick='approveAtt("+datarow.id+")' > Approve </a> <br> <a class='btn btn-danger' onclick='disapproveAtt("+datarow.id+")' > Disapprove </a>  </strong> ";
 
                }

                else if ( datarow.Action === "Approved"){
                    cell7.innerHTML = "<strong> <a class='btn btn-success' > Approved by You </a> </strong> ";
 
                }

                else if ( datarow.Action === "Declined"){
                    cell7.innerHTML = "<strong> <a class='btn btn-danger' onclick='viewComment("+datarow.id+")' > Declined by You, View Comment </a> </strong> ";
 
                }
               //cell3.innerHTML = "<div class='form-checkbox'> <input type='checkbox' disabled name='excused[]' id='stad" + datarow.PupilID + "' class='excusedform' value='yes'> </div>";
                i++;
            }
            document.getElementById('loading').style.display = 'none';
        }
          
    }
</script>
@endsection

@include('teacher.modals.modal')