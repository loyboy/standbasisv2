@extends('layouts.dashboard')

@section('parent')
  <!--gx-wrapper-->
  <div class="gx-wrapper">

<div class="animated slideInUpTiny animation-duration-3">

    <div class="page-heading">
        <h2 class="title">View Attendance Data of Your Children</h2>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="gx-card">
                <div class="gx-card-header">
                    <h3 class="card-heading">All attendances of your Wards Today</h3>
                </div>
               
                <div class="gx-card-body">
                
                    <div class=""> 

                            <div class="form-group row">
                                <label class="col-md-4 col-sm-3 control-label">Search Date:</label>
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
                    Your Search Date: <label class="col-md-4 col-sm-3 control-label" > <strong id="mylabel"> 2019-10-10 </strong>  </label>
                        <table id="mydatatable" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="_toolbar"><a href="#"> S/N </a></th>
                                    <th class="_toolbar"><a href="#">Ward's Name</a></th>
                                    <th class="_toolbar"><a href="#">Subject Class</a></th>
                                    <th class="_toolbar"><a href="#">Time</a></th>
                                    <th class="_toolbar"><a href="#">Attendance Status</a></th>
                                    <th class="_toolbar"><a href="#">Remark</a></th>                                
                                </tr>
                            </thead>

                            <tbody id="tbody1" style="overflow: scroll; ">
                                <tr> <td colspan="5" style="text-align: center;"> Attendance data will display here...  </td> </tr>
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
        xhr.open('POST', '/attendances_viewWards/'+teacher);
        xhr.responseType = 'json';
        let formData = new FormData();
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
            
            document.getElementById('loading').style.display = 'none';
            let attend = responseObj.data.table;
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

                cell0.innerHTML = "<strong>"+ (i + 1) + "</strong>";
                cell1.innerHTML = "<strong>"+ datarow.Pupil + "</strong>";
                cell2.innerHTML = "<strong>"+ datarow.Subclass + "</strong>";
                cell3.innerHTML = "<strong>"+ datarow.Time + "</strong>";
                cell4.innerHTML = "<strong>"+ datarow.Present + "</strong>";
                cell5.innerHTML = "<strong>"+ datarow.Remark + "</strong>";
               
               //cell3.innerHTML = "<div class='form-checkbox'> <input type='checkbox' disabled name='excused[]' id='stad" + datarow.PupilID + "' class='excusedform' value='yes'> </div>";
                i++;
            }
            let mylabel = document.querySelector('#mylabel');
            mylabel.innerHTML = responseObj.data.Date;
        } 
</script>
@endsection


@section('myscript')
<script>
  
   function getAttendanceOnChange(){
        let datebox = $('#attdate').val();
        //let subclassbox = $('#subclass').val();
        let xhr = new XMLHttpRequest();
        xhr.open('POST', '/attendances_viewWards/'+teacher);
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
            let attend = responseObj.data.table;
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

                cell0.innerHTML = "<strong>"+ (i + 1) + "</strong>";
                cell1.innerHTML = "<strong>"+ datarow.Pupil + "</strong>";
                cell2.innerHTML = "<strong>"+ datarow.Subclass + "</strong>";
                cell3.innerHTML = "<strong>"+ datarow.Time + "</strong>";
                cell4.innerHTML = "<strong>"+ datarow.Present + "</strong>";
                cell5.innerHTML = "<strong>"+ datarow.Remark + "</strong>";
             
                i++;
            }
            let mylabel = document.querySelector('#mylabel');
            mylabel.innerHTML = responseObj.data.Date;
            document.getElementById('loading').style.display = 'none';
        }
          
    }
</script>
@endsection

@include('teacher.modals.modal')