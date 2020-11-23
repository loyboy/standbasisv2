@extends('layouts.dashboard')

@section('teacher')
<div class="gx-wrapper">

    <div class="animated slideInUpTiny animation-duration-3">

        <div class="page-heading">
            <h2 class="title">Take Attendance</h2>
        </div>

        <div class="row"> 
                    <div class="col-xl-12" id="alertdiv"> </div>

                    <div class="col-xl-4 col-md-5 col-12">
                            <div class="card gx-card-full-height">
                                <img src="http://via.placeholder.com/500x330" id="imgholder" alt="Attendance Card Image" class="card-img-top">
                                <div class="card-body">
                                    <h3 class="card-title mb-0">Your Photo</h3>                                   
                                    <hr>
                                    <h3 class="card-title mb-0">Today's Attendance Time</h3></div>
                                <div class="card-mt-footer">
                                    <div class="card-mt-footer-inner">
                                        <a href="javascript:void(0)" class="gx-btn gx-flat-btn gx-btn-sm">
                                            <i class="zmdi zmdi-time zmdi-hc-lg"></i>
                                            Time
                                        </a>
                                        <a href="javascript:void(0)" class="gx-btn gx-flat-btn gx-btn-sm">
                                            {{ date('H:i') }}
                                        </a>
                                     
                                    </div>
                                  
                                </div>
                            </div>
                    </div>

                    <div class="col-xl-8 col-md-7 col-12">
                            <div class="gx-entry-header">
                                <h3 class="entry-heading">Your Students</h3>
                            </div>
                            <div class="gx-card mb-0 px-0 pt-2 pb-3">
                                <div class="gx-card-body" style="max-height: 400px; overflow: scroll;">
                                    <div class="table-responsive">
                                        <table class="table table-striped mb-0" id="tableofatt">
                                            <thead class="thead-light"> 
                                            <tr>
                                                <th class="text-uppercase" scope="col"> Name of Student</th>
                                                <th class="text-uppercase" scope="col"> Present?</th>
                                                <th class="text-uppercase" scope="col"> Excused?</th>
                                            </tr>
                                            </thead>
                                            <form method="post" action=<?php echo action('AttendanceController@submitAttendance'); ?> > 
                                                <?php echo csrf_field(); ?>
                                                <tbody id="tbody1">
                                            
                                                
                                                </tbody>
                                            </form>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        
                    </div> 
                    <div class="col-xl-12 col-sm-12">
                            <div class="gx-card">
                                <div class="gx-card-header">
                                    <h3 class="card-heading">Attendance Parameters</h3>
                                    <p class="sub-heading">View Your Class Information</p>
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
                                                <th scope="row">Teacher</th>
                                                <td id="td11"></td>
                                              
                                            </tr>
                                            <tr>
                                                <th scope="row">Class Name</th>
                                                <td id="td12"></td>
                                               
                                            </tr>

                                            <tr>
                                                <th scope="row">Expected Time</th>
                                                <td id="td13"></td>
                                               
                                            </tr>

                                            <tr>
                                                <th scope="row">Subject</th>
                                                <td id="td14"></td>
                                               
                                            </tr>
                                          
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                    </div>  
        </div>

    </div>

</div>

<div class="modal fade" id="getcamerapage" tabindex="-1" role="dialog" aria-labelledby="backupAccount" style="display: none; " aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="max-width: 1000px;">
                                        <div class="modal-content">
                                            <div class="modal-header bg-info border-0">
                                                <h4 class="modal-title" id=""> Take Snapshot <i class="fa fa-fw fa-camera"></i></h4>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>    
                                                </button>
                                            </div>
                                            <div class="modal-body"> <!-- -->
                                                <video id="videox" width="100%" height="450" autoplay></video>
                                                <canvas width="400" height="350" id="mycanvas" style="display:none;"> </canvas>
                                                <div class="row">
                                                    <div class="col-sm-6 "><button type="button" onclick="getcam()"  class="btn btn-block btn-primary">Switch On Camera!</button></div>
                                                    <div class="col-sm-6"><button type="button" id="snap" onclick="takePicture()" class="btn btn-block btn-primary">Take Snapshot!</button></div>
                                                </div>
                                                <p></p>
                                                <div class="row">
                                                    <div class="col-sm-12"><div class="timer">00:05</div></div>
                                                </div>                 
                                            </div>
                                            <div class="modal-footer">
                                                    <button type="button" class="btn btn-info btn-block" data-dismiss="modal">Close</button>                    
                                            </div>
                                        
                                        </div>                                        
                                    </div>
                                </div>

<script>    
    let teacher = {{ Auth::user()->teacher_id }};
    let token = '{{ Auth::user()->api_token }}';
    let val = {{ $val }};
    
    let xhr = new XMLHttpRequest();
        xhr.open('POST', '/attendances_getTimeofAtt/'+token+'/class/'+val);
        xhr.responseType = 'json';
        let formData = new FormData();
        formData.append("api_token", token);
        xhr.send(formData);

        xhr.onload = function() {
            let responseObj = xhr.response;
            if (responseObj.status === "Failed"){
                alert(responseObj.message);
                window.location.href = "/tatttake";
            } 
            
            var teaname = document.getElementById('td11');
            var clsname = document.getElementById('td12');
            var exptime = document.getElementById('td13');
            var subject = document.getElementById('td14');

            teaname.innerHTML = responseObj.data.Teacher;
            clsname.innerHTML = responseObj.data.Class;
            subject.innerHTML = responseObj.data.Subject;
            exptime.innerHTML = responseObj.data.ExpTime;

            let subclassid =  responseObj.data.SubClassID;
            let timeid =  responseObj.data.TimeID;
            let termid =  responseObj.data.TermID;
            let attid =  responseObj.data.Attid;

            let pupils = responseObj.data.Pupils;
            var i = 0;

            for ( let datarow of pupils ){ 
                var tablex = document.getElementById('tbody1').insertRow(i);
                var cell1 = tablex.insertCell(0);
                var cell2 = tablex.insertCell(1);
                var cell3 = tablex.insertCell(2);
                tablex.className = "datarow";
                cell1.innerHTML = "<strong class='pupilname'>"+ datarow.PupilName + "</strong>";
                cell2.innerHTML = "<div class='form-checkbox'> <input checked='checked' type='checkbox' name='present[]' class='presentform' value='" + datarow.PupilID + "'> </div>";
                cell3.innerHTML = "<div class='form-checkbox'> <input type='checkbox' disabled name='excused[]' id='stad" + datarow.PupilID + "' class='excusedform' value='yes'> </div>";
                i++;
            }

            var tablebody = document.getElementById('tbody1');
            var htmltoadd = " <tr><td colspan='3'> <input type='hidden' id='picblob' name='picblob' value='' /> <input type='hidden' id='subclass' name='subclass' value='"+ subclassid + "' /> <input type='hidden' id='timeid' name='timeid' value='"+ timeid + "' /> <input type='hidden' id='termid' name='termid' value='"+ termid + "' /> <input type='hidden' id='attval' name='attval' value='"+ attid + "' /> </td> </tr>  ";
            htmltoadd += "<tr> <td colspan='3'> <input class='col-md-12 col-sm-12 btn btn-danger btn-block' type='button' onclick='showdialogcam()' value='Click here to Snap Attendance Image' /> </td> </tr>";
            htmltoadd += "<tr> <td colspan='3'> <button type='button' name='sattend' id='sattendbut' onclick='submitAttendance()' class='btn btn-block btn-primary'>Click here to Save Attendance...</button> </td> </tr>";
            tablebody.insertAdjacentHTML( 'beforeend', htmltoadd);
            //alert(responseObj.data.Pupils);           

        }

</script>
@endsection

@section('myscript')
    <script>
        //let token = '{{ Auth::user()->api_token }}';        

        function convertTable(){
                var array1 = [];
                $("#tbody1 .datarow").each(function () {                  
                        var firstTableData = {};
                        firstTableData.PupilName = $(this).find('.pupilname').text().trim();
                        firstTableData.PupilID = $(this).find('.presentform').val();
                        if ($(this).find('.excusedform') !== null && $(this).find('.excusedform').is(":checked") ){
                            firstTableData.Comment = "Excused";
                        }
                        else{
                            firstTableData.Comment = "Nil";
                        }
                        if ($(this).find('.presentform') !== null && $(this).find('.presentform').is(":checked") ){
                            firstTableData.Present = 1;
                        }
                        else{
                            firstTableData.Present = 0;
                        }
                        array1.push(firstTableData);                    
                });
               // alert(JSON.stringify(array1));
               return JSON.stringify(array1);
        }

        function b64toBlob(dataURI) {
            var byteString = atob(dataURI.split(',')[1]);
            var ab = new ArrayBuffer(byteString.length);
            var ia = new Uint8Array(ab);
            for (var i = 0; i < byteString.length; i++) {
                ia[i] = byteString.charCodeAt(i);
            }
            return new Blob([ab], { type: 'image/png' });
        }

        async function checkNetConnection(){
            var xhr = new XMLHttpRequest();
            var file = "https://standbasislive.com/Standbasislogo.png";          
            xhr.open('HEAD', file , false);
            try {
            xhr.send();
            if (xhr.status >= 200 && xhr.status < 304) {
            return true;
            } else {
            return false;
            }
            } catch (e) {
            return false;
            }
        }

        function submitAttendance(){
            let datenow = "{{ date('Y-m-d H:i:s') }}";
            let pupilsdata = convertTable();
            let formData = new FormData();
            
            let att = $("#attval").val();
            let sc = $("#subclass").val(); 
            let td = $("#timeid").val();
            let tm = $("#termid").val();
            let pic = $("#picblob").val();
           
            formData.append("subclass", sc);
            formData.append("timeid", td);
            formData.append("termid", tm);
            formData.append("pupilsdata", pupilsdata);
            if (pic !== ""){
                    var blob = b64toBlob(pic);
                    formData.append("image", blob);
            }

            $.ajax({
                url: '/attendances_submitAtt', 
                type: "POST", 
                cache: false,
                contentType: false,
                processData: false,
                data: formData 
                }).done(function(e){                
                    alert(e.message);
                    
                }).fail(function(e){
                    // Report that there is a problem!
                        alert(e.responseText);
                });

           
        }                    
        
        $(document).ready(function() {           
           // showTodos();
         
            $(".presentform").on('change', function() {
                var inputid = $( this ).parent().parent().parent().find(".excusedform").attr('id');
                console.log("exuseform "+ inputid);
                if ( $(this).is(":checked") ){     
                    console.log("yeap ");                
                    //$(this).parent().addClass('present'); 
                    $("#"+inputid).attr('disabled',''); 
                    $("#"+inputid).val("");                     
                } 
                else {        
                    console.log("Nope");                 
                    $("#"+inputid).removeAttr('disabled'); 
                    $("#"+inputid).val("yes");
                };   
           });
        });      

    //////////////////////    
    //countdown for webcam
    function countdown(){
        //set interval for countdown
        var interval = setInterval(function() {
        var timer = $('.timer').html();
        timer = timer.split(':');
        var minutes = parseInt(timer[0], 10);
        var seconds = parseInt(timer[1], 10);
        seconds -= 1;
        if (minutes < 0) return clearInterval(interval);
        if (minutes < 10 && minutes.length !== 2) minutes = '0' + minutes;
        if (seconds < 0 && minutes !== 0) {
            minutes -= 1;
            seconds = 59;
        }
        else if (seconds < 10 && length.seconds !== 2) seconds = '0' + seconds;
        $('.timer').html(minutes + ':' + seconds);
        
        if (minutes === 0 && seconds === 0)
            clearInterval(interval);
        }, 1000);
    }
    ///////////////////////////////////////
    //declare video,snapshot and audio manipulation
    var canvas = document.getElementById('mycanvas');
    var videox = document.getElementById('videox');
    var audio = document.getElementById("audio");
    var localstream;
    //////////////////////////////////////
    //convert the canvas image to image source code
    function convertCanvasToImage(canvas) {
        var image = new Image();
        image.src = canvas.toDataURL("image/jpg");
        return image;
    }
    //////////////////////////////////////
    //show dialog for webcam to display
    function showdialogcam(){
       // $('#dlg').removeClass('hidediv');
       //  $.unblockUI();
         $('#getcamerapage').modal({
                     backdrop: 'static',
                     keyboard: false
                 },'show');
        console.log("Do something in Register");
       
    }
    /////////////////////////////////////
    //switch off webcam
    function vidOff() {
      videox.pause();
      videox.src = "";
      localstream.getTracks()[0].stop();
      console.log("Vid off");

    }
 
    /////////////////////////////////////
    // Trigger photo to be taken
    //get image src url
    var imgsrc = null;
    function takePicture(){
            var canvasobj = document.getElementById('mycanvas');
            var context = canvasobj.getContext('2d');
            console.log("Yeap, I was clicked");
            countdown();//countdown to the time that the Photo will be taken
            setTimeout(function(){
            //begin the visual countdown timer
            context.drawImage(videox, 0, 0, 450, 300 );
            imgsrc = canvasobj.toDataURL("image/jpg");
            $('#picblob').val(imgsrc);
            $('#imgholder').attr('src',imgsrc);
            vidOff();
            
            //audio.play();
            $('#sattendbut').removeAttr('disabled');
            console.log("Attribute source pix: "+ $('#picblob').val());
            let alertdiv = "<div class='bg-success text-white alert alert-dismissible fade show border border-success' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'> × </span></button>Your attendance PHOTO has been attached!</div>"
            $('#alertdiv').html(alertdiv);
        }, 5000);
         $('.timer').html('00:05');
    };
    ////////////////////////////////////
    //Switch on webcam
      function getcam(){  
        var video = document.getElementById('videox');
      // Get access to the camera!
        if(navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            console.log("yeap, seen the camera");
        // Not adding `{ audio: true }` since we only want video now
        navigator.mediaDevices.getUserMedia({ video: {
            width: {
            min: 720,
            ideal: 1366,
            max: 1920,
            },
            height: {
            min: 480,
            ideal: 1080,
            max: 1440
            }  , facingMode: { exact: "user" }   } }).then(function(stream) {
                    video.srcObject=stream;
                    localstream = stream;
                    video.play();
                });
            $('#snap').removeAttr("disabled");
        }
        else{
            console.log("Nope, not seen the camera");
        }
    } //, facingMode: { exact: "environment" }
    </script>
@endsection


