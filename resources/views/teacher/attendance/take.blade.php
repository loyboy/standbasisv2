@extends('layouts.dashboard')

@section('teacher')
<div class="gx-wrapper">

    <div class="animated slideInUpTiny animation-duration-3">

        <div class="page-heading">
            <h2 class="title">Attendance</h2>
        </div> 

        <div class="gx-entry-header"><h3 class="entry-heading"> Take An Attendance </h3></div>

        <div class="row">
                    <div class="col-lg-12">
                        <div class="gx-card ">
                            <div class="gx-card-header">
                                <h3 class="card-title">Select Your Class based on Time</h3>
                            </div>
                            <div class="gx-card-body">
                                <form class="form-horizontal">
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-lg-3 control-label">Pick a Class Subject Time Combination</label>
                                        <div class="col-sm-6 col-lg-5">
                                            <select class="select2 form-control form-control-lg select2-hidden-accessible" tabindex="-1" aria-hidden="true" id="input1">
                                                <option value="" >Select Class Today</option>
                                                
                                            </select>
                                        </div>
                                    </div>
                                    <div class="line-dashed"></div>

                                    <a href="javascript:void(0)" onclick="takeAttendance()" class="gx-btn gx-btn-primary text-uppercase btn-block"> Proceed </a>
                                   
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

    </div>

</div>

<script> 
    let teacher = {{ Auth::user()->teacher_id }};
    let token = '{{ Auth::user()->api_token }}';
    let xhr = new XMLHttpRequest();
    xhr.open('POST', '/attendances_getSubClassWithTime/'+teacher);
    xhr.responseType = 'json';
    let formData = new FormData();
    formData.append("api_token", token);
    xhr.send(formData);

    xhr.onload = function() {
        let responseObj = xhr.response;
        var selectinput = document.querySelector('#input1');
        var domdata = "";
        if (responseObj.message){
            alert(responseObj.message);
            return;
        }
       
        for ( let datarow of responseObj.data ){ 
           selectinput.options[selectinput.options.length] = new Option( datarow.ClassName + " " + datarow.Subject + " " + datarow.DayofWeek + " " + datarow.Time, datarow.TimetableSchID );         
        }
      
        //alert(responseObj.data); 
    };

    function takeAttendance(){
        let token = '{{ Auth::user()->api_token }}';
        
        var selectinput = document.querySelector('#input1');
        var val = selectinput.value;
        if(val === ""){
            alert('Please Select a Value from the List');
            return;
        }
        let xhr = new XMLHttpRequest();
        xhr.open('POST', '/attendances_getTimeofAtt/'+token+'/class/'+val);
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
            window.location.href = "/tatttakepupil/"+val;

        }

    }

</script>
@endsection

