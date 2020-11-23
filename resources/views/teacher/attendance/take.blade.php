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

                       <!--
                        <div class="gx-card">
                                <div class="gx-card-header">
                                    <h3 class="card-heading">Upload Offline Attendance Data</h3>
                                    <p class="sub-heading">View Your Offline Information</p>
                                </div>
                                <div class="gx-card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="text-uppercase" scope="col"> Parameter</th>
                                                    <th class="text-uppercase" scope="col"> Value</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <th scope="row">No. of Offline Data Not Sent</th>
                                                <td id="td11"></td>
                                              
                                            </tr>
                                            <tr>
                                                <th scope="row">Date when Last Attendance was Taken</th>
                                                <td id="td12"></td>
                                               
                                            </tr>

                                            <tr>
                                                
                                                <td colspan="2" > <button id="updatebut" class="btn btn-block btn-success" onclick="updatedataincloud()">  Upload Data to Database  </button> </td>
                                               
                                            </tr>
                                          
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                        </div>  -->                 

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

@section('myscript')
<script> 
   let dd = "{{ date('H:i:s', strtotime('2020-01-20')) }}";
     const delay = ms => new Promise(res => setTimeout(res, ms));
     
     function b64toBlob(dataURI) {
            var byteString = atob(dataURI);
            var ab = new ArrayBuffer(byteString.length);
            var ia = new Uint8Array(ab);
            for (var i = 0; i < byteString.length; i++) {
                ia[i] = byteString.charCodeAt(i);
            }
            return new Blob([ab], { type: 'image/png' });
    }

    function updatedata1(val) {
        //
        $('#td11').html(val);
        console.log('Update the data OFFLINE DATA');
    }
    function updatedata2(val) {
        //
        $('#td12').html(val);
        console.log('Update the data date');
    }

     function updatedataincloud() {
        var finished = false;
        document.getElementById('loading').style.display = 'block';
        dbobject.allDocs({include_docs: true, descending: true},async function(err, doc) {  

            for (let datarow of doc.rows){
                console.log("Upload to cloud: "+datarow.doc.sent);
               if (datarow.doc.sent === 0){               
                           
                            let formData = new FormData();
                            
                            let att = datarow.doc.table_id;
                            let dateuse = datarow.doc.dateuse;
                            let sc = datarow.doc.subclass; 
                            let td = datarow.doc.timeid;
                            let tm = datarow.doc.termid;
                            let pupdata = datarow.doc.pupilsdata;
                            let pic = null;
                       /*     dbobject.getAttachment(datarow.doc._id, 'attendance.png', function(err, blob_buffer) {
                                if (err) {
                                    return console.log(err);
                                } else {
                                    console.log("Blob buffer: " + JSON.strigify(blob_buffer) );
                                    if (blob_buffer){
                                        pic = b64toBlob(blob_buffer);
                                    }
                                }
                            }); */
                          //  await delay(400)
                            formData.append("attid", att);
                            formData.append("dateuse", dateuse);
                            formData.append("subclass", sc);
                            formData.append("timeid", td);
                            formData.append("termid", tm);
                            formData.append("pupilsdata", pupdata);
                            if (pic){                       
                                formData.append("image", pic);
                            }

                        let doc = {
                            sent: 1,
                            _rev: datarow.doc._rev,
                            _id: datarow.doc._id
                            }

                            $.ajax({
                            url: '/attendances_submitAttOffline', 
                            type: "POST", 
                            cache: false,
                            contentType: false,
                            processData: false,
                            data: formData 
                            }).done(function(e){

                                dbobject.put(doc);

                                alert(e.message);
                            }).fail(function(e){
                                // Report that there is a problem!
                                    alert(e.responseText);
                            });
                            alert("You have successfully updated all the Attendance information");
                           
                        }
                        else {
                            break;
                        }
                    }
                });
                document.getElementById('loading').style.display = 'none';
             

        
    }

   

    $(document).ready( async function() { 
        //get the number of Data not sent yet...
        var getdata = 0;
        var getdate = '';

        dbobject.allDocs({include_docs: true, descending: true}, function(err, doc) {
            getdate =  new Date(doc.rows[0].doc._id).toLocaleString('en-GB');  
            console.log('The date'+ getdate);
            for (let datarow of doc.rows){
                console.log("Data row: "+ datarow.doc.sent)
                if (datarow.doc.sent === 0){                   
                    getdata++;
                }
            }
        });

        await delay(800)

        updatedata1(getdata);
        updatedata2(getdate);
      
    });

    function showTodos() {
                dbobject.allDocs({include_docs: true, descending: true}, function(err, doc) {
                    console.log(doc.rows);
                });
    }
    showTodos();
</script>
@endsection

