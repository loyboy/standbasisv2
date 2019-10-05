@extends('layouts.dashboard')

@section('teacher')
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
                                <th>Subject & Class</th>
                                <th>Class Time</th>
                                <th>Submission Time</th>
                                <th>Class Performance</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody id="tbody1" style="overflow: scroll; ">
                                <tr> <td colspan="6" style="text-align: center;"> Attendance data will display here...  </td> </tr>
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

    let xhr2 = new XMLHttpRequest();
        xhr2.open('POST', '/subjectclasses_findTeaSub/'+teacher+'/type/normal');
        xhr2.responseType = 'json';
        let formData2 = new FormData();
        formData2.append("api_token", token);
        xhr2.send(formData2);

        xhr2.onload = function() {
            let responseObj = xhr2.response;
            if (responseObj.status === "Failed"){
                alert(responseObj.message);
                return;
            }
            var selectinput = document.querySelector('#subclass');

            for ( let datarow of responseObj.data ){ 
                selectinput.options[selectinput.options.length] = new Option( datarow.Subject + " " + datarow.ClassName , datarow.SubjectClassId );         
            }
        }
    
        let xhr = new XMLHttpRequest();
        xhr.open('POST', '/attendances_viewAtt/'+teacher);
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
                
                tablex.className = "datarow";
                cell0.innerHTML = "<strong>"+ (i + 1) + "</strong>";
                cell1.innerHTML = "<strong>"+ datarow.Subclass + "</strong>";
                cell2.innerHTML = "<strong>"+ datarow.ExpTime + "</strong>";
                cell3.innerHTML = "<strong>"+ datarow.ActTime + "</strong>";
                cell4.innerHTML = "<strong>"+ datarow.Perf + "%" + "</strong>";
                cell5.innerHTML = "<strong> <a class='btn btn-primary' onclick='showAttendance("+datarow.id+")' > View Attendance </a> </strong>";
               //cell3.innerHTML = "<div class='form-checkbox'> <input type='checkbox' disabled name='excused[]' id='stad" + datarow.PupilID + "' class='excusedform' value='yes'> </div>";
                i++;
            }
        } 
</script>
@endsection



@section('myscript')
<script>
    $(document).ready(function(){
   
    });
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

    function getAttendanceOnChange(){
        let datebox = $('#attdate').val();
        //let subclassbox = $('#subclass').val();
        let xhr = new XMLHttpRequest();
        xhr.open('POST', '/attendances_viewAtt/'+teacher);
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
            $('#tbody1').empty();
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
              
                tablex.className = "datarow";
                cell0.innerHTML = "<strong>"+ (i + 1) + "</strong>";
                cell1.innerHTML = "<strong>"+ datarow.Subclass + "</strong>";
                cell2.innerHTML = "<strong>"+ datarow.ExpTime + "</strong>";
                cell3.innerHTML = "<strong>"+ datarow.ActTime + "</strong>";
                cell4.innerHTML = "<strong>"+ datarow.Perf + "%" + "</strong>";
                cell5.innerHTML = "<strong> <a class='btn btn-primary' onclick='showAttendance("+datarow.id+")' > View Attendance </a> </strong>";
               //cell3.innerHTML = "<div class='form-checkbox'> <input type='checkbox' disabled name='excused[]' id='stad" + datarow.PupilID + "' class='excusedform' value='yes'> </div>";
                i++;
            }
            document.getElementById('loading').style.display = 'none';
        } 
    }
</script>
@endsection

@include('teacher.modals.modal')