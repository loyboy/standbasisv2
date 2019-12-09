@extends('layouts.dashboard')

@section('principal')
  <!--gx-wrapper-->
  <div class="gx-wrapper">

<div class="animated slideInUpTiny animation-duration-3">

    <div class="page-heading">
        <h2 class="title">View Attendance Flags Data</h2>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="gx-card">
                <div class="gx-card-header">
                    <h3 class="card-heading">All attendance flags triggered within system</h3>
                </div>
               
                <div class="gx-card-body">
                
                    <div class=""> 

                            <div class="form-group row">
                                <label class="col-md-4 col-sm-3 control-label">Search Date</label>
                                    <div class="col-md-4 col-sm-6">
                                        <input type="date" value="" class="form-control" onchange="getAttendanceOnChange()" id="attdate"/>
                                    </div>
                            </div>

                      
                    </div>

                    <div class="table-responsive" style=" height: 400px; overflow-y: auto; overflow-x: hidden; ">
                       Your Search Date: <label class="col-md-4 col-sm-3 control-label" > <strong id="mylabel"> 2019-10-10 </strong>  </label>
                        <table id="mydatatable" class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                               
                                <th>Flag Type</th>
                                <th>Flag count</th>
                                <th>Head Teacher remarks</th>
                              
                               
                            </tr>
                            </thead>

                            <tbody id="tbody1" style="overflow: scroll; ">
                                <tr> <td colspan="3" style="text-align: center;"> Attendance Flags data will display here...  </td> </tr>
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

@endsection

@section('admin')
  <!--gx-wrapper-->
  <div class="gx-wrapper">

<div class="animated slideInUpTiny animation-duration-3">

    <div class="page-heading">
        <h2 class="title">View Attendance Flags Data</h2>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="gx-card">
                <div class="gx-card-header">
                    <h3 class="card-heading">All attendance flags triggered within system</h3>
                </div>
               
                <div class="gx-card-body">
                
                    <div class=""> 

                            <div class="form-group row">
                                <label class="col-md-4 col-sm-3 control-label">Search Date</label>
                                    <div class="col-md-4 col-sm-6">
                                        <input type="date" value="" class="form-control" onchange="getAttendanceOnChange()" id="attdate"/>
                                    </div>
                            </div>

                      
                    </div>

                    <div class="table-responsive" style=" height: 400px; overflow-y: auto; overflow-x: hidden; ">
                       Your Search Date: <label class="col-md-4 col-sm-3 control-label" > <strong id="mylabel"> 2019-10-10 </strong>  </label>
                        <table id="mydatatable" class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                               
                                <th>Flag Type</th>
                                <th>Flag count</th>
                                <th>Head Teacher remarks</th>
                              
                               
                            </tr>
                            </thead>

                            <tbody id="tbody1" style="overflow: scroll; ">
                                <tr> <td colspan="3" style="text-align: center;"> Attendance Flags data will display here...  </td> </tr>
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

@endsection


@section('owner')
  <!--gx-wrapper-->
  <div class="gx-wrapper">

<div class="animated slideInUpTiny animation-duration-3">

    <div class="page-heading">
        <h2 class="title">View Attendance Flags Data</h2>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="gx-card">
                <div class="gx-card-header">
                    <h3 class="card-heading">All attendance flags triggered within system</h3>
                </div>
               
                <div class="gx-card-body">
                
                    <div class=""> 

                            <div class="form-group row">
                                <label class="col-md-4 col-sm-3 control-label">Search Date</label>
                                    <div class="col-md-4 col-sm-6">
                                        <input type="date" value="" class="form-control" onchange="getAttendanceOnChange()" id="attdate"/>
                                    </div>
                            </div>

                      
                    </div>

                    <div class="table-responsive" style=" height: 400px; overflow-y: auto; overflow-x: hidden; ">
                       Your Search Date: <label class="col-md-4 col-sm-3 control-label" > <strong id="mylabel"> 2019-10-10 </strong>  </label>
                        <table id="mydatatable" class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                               
                                <th>Flag Type</th>
                                <th>Flag count</th>
                                <th>Head Teacher remarks</th>
                              
                               
                            </tr>
                            </thead>

                            <tbody id="tbody1" style="overflow: scroll; ">
                                <tr> <td colspan="3" style="text-align: center;"> Attendance Flags data will display here...  </td> </tr>
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

@endsection


@section('myscript')
<script>
        let teacher = {{ Auth::user()->teacher_id }};
        let token = '{{ Auth::user()->api_token }}';
        let datex = '{{ date("Y-m-d") }}';
    $(document).ready(function(){  
        let xhr = new XMLHttpRequest();
        xhr.open('POST', '/attendances_getFlags/'+teacher);
        xhr.responseType = 'json';
        let formData = new FormData();
        formData.append("api_token", token);
        formData.append("_date", datex);
        xhr.send(formData);

        xhr.onload = function() {
            let responseObj = xhr.response;
            if (responseObj.status === "Failed"){
                alert(responseObj.message);
                return;
            }
            
            let datarow = responseObj.data;
            var i = 0;
            
                var tablex = document.getElementById('tbody1').insertRow(0);
                var cell10 = tablex.insertCell(0);
                var cell11 = tablex.insertCell(1);
                var cell12 = tablex.insertCell(2);
               
              
                cell10.className = "_toolbar widthplus";

                cell10.innerHTML = "<strong>"+ "Student Absence" + "</strong>";
                cell11.innerHTML = "<strong>"+ datarow.SAbsent + "</strong>";
             //   cell12.innerHTML = "<strong>"+ datarow.SAbsent + "</strong>";

                var tablex2 = document.getElementById('tbody1').insertRow(1);
                var cell11 = tablex2.insertCell(0);
                var cell13 = tablex2.insertCell(1);
                var cell14 = tablex2.insertCell(2);
               
              
                cell11.className = "_toolbar";

                cell11.innerHTML = "<strong>"+ "Teacher Subject-Class Absence" + "</strong>";
                cell13.innerHTML = "<strong>"+ datarow.TAbsent + " periods" + " out of "+ datarow.TTotal +  "</strong>";

                var tablex3 = document.getElementById('tbody1').insertRow(1);
                var cell15 = tablex3.insertCell(0);
                var cell16 = tablex3.insertCell(1);
                var cell17 = tablex3.insertCell(2);
               
              
                cell15.className = "_toolbar";

                cell15.innerHTML = "<strong>"+ "Late Class" + "</strong>";
                cell16.innerHTML = "<strong>"+ datarow.LClass + "</strong>";

                var tablex4 = document.getElementById('tbody1').insertRow(1);
                var cell18 = tablex4.insertCell(0);
                var cell19 = tablex4.insertCell(1);
                var cell20 = tablex4.insertCell(2);
               
              
                cell18.className = "_toolbar";

                cell18.innerHTML = "<strong>"+ "Attendance Approval Delay" + "</strong>";
                cell19.innerHTML = "<strong>"+ datarow.ADelay + "</strong>";

                
                var tablex5 = document.getElementById('tbody1').insertRow(1);
                var cell21 = tablex5.insertCell(0);
                var cell22 = tablex5.insertCell(1);
                var cell23 = tablex5.insertCell(2);
               
              
                cell21.className = "_toolbar";

                cell21.innerHTML = "<strong>"+ "Incomplete Submission  (No Images included)" + "</strong>";
                cell22.innerHTML = "<strong>"+ datarow.Incomplete + "</strong>";
                
                let mylabel = document.querySelector('#mylabel');
                mylabel.innerHTML = datarow._date;
               //
        } 
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
        xhr.open('POST', '/attendances_getFlags/'+teacher);
        xhr.responseType = 'json';
        let formData = new FormData();
        formData.append("api_token", token);
        formData.append("_date", datebox);
        document.getElementById('loading').style.display = 'block';
        xhr.send(formData);

        xhr.onload = function() {
            let responseObj = xhr.response;
            if (responseObj.status === "Failed"){
                document.getElementById('loading').style.display = 'none';
                alert(responseObj.message);
                return;
            }
            $('#tbody1').empty();
            let datarow = responseObj.data;
            var i = 0;
            
                var tablex = document.getElementById('tbody1').insertRow(0);
                var cell10 = tablex.insertCell(0);
                var cell11 = tablex.insertCell(1);
                var cell12 = tablex.insertCell(2);
               
              
                cell10.className = "_toolbar widthplus";

                cell10.innerHTML = "<strong>"+ "Student Absence" + "</strong>";
                cell11.innerHTML = "<strong>"+ datarow.SAbsent + "</strong>";
              

                var tablex2 = document.getElementById('tbody1').insertRow(1);
                var cell11 = tablex2.insertCell(0);
                var cell13 = tablex2.insertCell(1);
                var cell14 = tablex2.insertCell(2);
               
              
                cell11.className = "_toolbar";

                cell11.innerHTML = "<strong>"+ "Teacher Subject-Class Absence" + "</strong>";
                cell13.innerHTML = "<strong>"+ datarow.TAbsent + " periods" + " out of "+ datarow.TTotal +  "</strong>";

                var tablex3 = document.getElementById('tbody1').insertRow(1);
                var cell15 = tablex3.insertCell(0);
                var cell16 = tablex3.insertCell(1);
                var cell17 = tablex3.insertCell(2);
               
              
                cell15.className = "_toolbar";

                cell15.innerHTML = "<strong>"+ "Late Class" + "</strong>";
                cell16.innerHTML = "<strong>"+ datarow.LClass + "</strong>";

                var tablex4 = document.getElementById('tbody1').insertRow(1);
                var cell18 = tablex4.insertCell(0);
                var cell19 = tablex4.insertCell(1);
                var cell20 = tablex4.insertCell(2);
               
              
                cell18.className = "_toolbar";

                cell18.innerHTML = "<strong>"+ "Attendance Approval Delay" + "</strong>";
                cell19.innerHTML = "<strong>"+ datarow.ADelay + "</strong>";

                
                var tablex5 = document.getElementById('tbody1').insertRow(1);
                var cell21 = tablex5.insertCell(0);
                var cell22 = tablex5.insertCell(1);
                var cell23 = tablex5.insertCell(2);
               
              
                cell21.className = "_toolbar";

                cell21.innerHTML = "<strong>"+ "Incomplete Submission (No Images included)" + "</strong>";
                cell22.innerHTML = "<strong>"+ datarow.Incomplete + "</strong>";
                
                let mylabel = document.querySelector('#mylabel');
                mylabel.innerHTML = datarow._date;
               
                document.getElementById('loading').style.display = 'none';
        } 
    }
</script>
@endsection

@include('teacher.modals.modal')