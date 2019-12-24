<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
    <?php use App\Http\Controllers\SubjectController;?>
    <?php use App\Http\Controllers\PupilController;?>
    <?php use App\Http\Controllers\ClassStreamController;?>
   
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Standbasis Report') }}</title>

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <link href="{{ asset('css/mytable.css') }}" rel="stylesheet">
  
    <script> 

        function exportExcel(elem) {
                var table = document.getElementById("mytable");
                var html = table.outerHTML;
                var url = 'data:application/vnd.ms-excel,' + escape(html); // Set your html table into url 
                elem.setAttribute("href", url);
                elem.setAttribute("download", "standbasis_class_attendance_report.xls"); // Choose the file name
                return false;
        }
    
        $(document).ready(function() {

            $('.JStableOuter table').scroll(function(e) { 
            
                $('.JStableOuter thead').css("left", -$(".JStableOuter tbody").scrollLeft()); 
                $('.JStableOuter thead th:nth-child(1)').css("left", $(".JStableOuter table").scrollLeft() -0 ); 
                $('.JStableOuter tbody td:nth-child(1)').css("left", $(".JStableOuter table").scrollLeft()); 

                $('.JStableOuter thead').css("top", -$(".JStableOuter tbody").scrollTop());
                $('.JStableOuter thead tr th').css("top", $(".JStableOuter table").scrollTop()); 

            });
        });

    </script>
    <!-- Styles -->
    </head>
    
    <body id="body">

    <div class="row border border-default">         
	
            <div class="bg-primary text-center p-2 col-md-12">
                <p class="lead text-white">Standbasis Report for Class': <?php echo ClassStreamController::getClassName($classid) ?>  Attendance</p>
                <br>
                <a id="downloadLink" class="btn btn-danger" onclick="exportExcel(this)">Export to excel</a>
            </div>         

            <div class="JStableOuter" >

                    <div class="row"> 
                        <div class="container"> 
                        <div class="row col-12" style="margin-left: 4px; ">
                                <div class="form-group col-3">
                                    <label> Start Date: </label>
                                    <input type="date" name="mydate" class="form-control" id="thedate" value="">
                                </div>
                                
                                
                                <div class="form-group col-3">
                                    <label> End Date: </label>
                                    <input type="date" name="mydate2" class="form-control" id="thedate2" value="<?php echo date('Y-m-d'); ?>">
                                </div>  
                                
                            
                                <div class="form-group col-3">
                                    <label> Term: </label>
                                    <select class="form-control"  name="head_year" id="theterm">
                                        <option value="">Select.. </option>
                                        <option value="1"> 1st Term </option>
                                        <option value="2"> 2nd Term </option>
                                        <option value="3"> 3rd Term </option>
                                    </select>
                                </div>  

                                <div class="form-group col-3">
                                    <label> Action:  </label>
                                    <button class="btn btn-danger" onclick="getClassData()" > Perform Search </button>
                                </div>
                        </div>
                    </div>
 
  <table id = "mytable">
    <thead>
     
      <tr style="top: 0px" >
        <th style="left: 0px" > Student Name </th>
        <?php
               $school = Auth::user()->teacher->school_id;
               $sub = SubjectController::getSubjectAll($school);
               foreach ($sub as $s){
        ?>
            <th class="blueHead twist"> <?php echo $s['name']; ?> </th>

        <?php } ?>

      </tr>
        <tr> 
            <td colspan="10"> 
                <b> Start date: </b> <span id="stid"> <?php  if( session()->has('searchdata') ) { echo session('searchdata.sd'); } ?>  </span> &nbsp;  &nbsp;  &nbsp;  &nbsp; &nbsp;  &nbsp; 
                <b> End date: </b> <span id="etid">   <?php  if( session()->has('searchdata') ) { echo session('searchdata.ed'); } ?></span> &nbsp;  &nbsp;  &nbsp;  &nbsp; &nbsp;  &nbsp; 
                <b> Term: </b> <span id="trid">  <?php  if( session()->has('searchdata') ) { echo session('searchdata.tr'); } ?></span> 
            </td>
        </tr>
     <!-- <tr style="top: 0px" >
        <th style="left: 0px" ></th>
        <th> <p> $16,417,480 </p> </th>
        <th class="profitCol" > <p> $2,412,287 </p> </th>
        <th class="revenueCol" > <p> $18,829,767 </p> </th>
        <th></th>
        <th></th>
        <th> <p> $748,371 </p> </th>
        <th> <p> $249,938 </p> </th>
        <th> <p> $17,831,458 </p> </th>
        <th> <p>$150,825</p> </th>
        <th></th>

        <th class="lightBlueBox" >P/L</th>
        <th class="lightBlueBox" > <p>Dec 16</p> <span class="profitCol" >$500</span> </th>
        <th class="lightBlueBox" >  <p>Dec 17</p> <span class="profitCol" >$68,128</span> </th>
        <th class="lightBlueBox" >  <p>Dec 17</p> <span class="profitCol" >$68,638</span> </th>
        <th class="lightBlueBox" >  <p>Dec 17</p><span class="negativeCost" > -$79,052 </span> </th>
         <th class="lightBlueBox" >  <p>Dec 17</p> <span class="profitCol" >$68,638</span> </th>

      </tr> -->
    </thead>
    <tbody>
      
        <?php
               $pup = PupilController::getAllPupilsInClass($classid);
               foreach ($pup as $p){
        ?>
        <tr>
            <td style=" text-align: center; " > <?php echo PupilController::getPupilName($p['id']); ?></td>
        <?php
               $sub = SubjectController::getSubjectAll($school);
               foreach ($sub as $s){  

        ?>

        <?php  if(session()->has('searchdata.sd')) { ?>
            
            <td style=" text-align: center; " > <?php echo SubjectController::getSubjectAttendance($p['pupil_id'], $s['id'],session('searchdata.sd'),session('searchdata.ed'), session('searchdata.tr') ); ?> </td>

        <?php } else {  ?>
        
            <td style=" text-align: center; " > <?php echo SubjectController::getSubjectAttendance($p['pupil_id'], $s['id'] ); ?> </td>

        <?php } } ?>
          
        </tr>
        <?php } ?>       


    </tbody>
  </table>
</div>

    </div>

    <script>
        let teacher = {{ Auth::user()->teacher_id }};
        let token = '{{ Auth::user()->api_token }}';    
    
        let xhr = new XMLHttpRequest();
        xhr.open('POST', '/terms_getDate/'+teacher);
        xhr.responseType = 'json';
        let formData = new FormData();
        formData.append("api_token", token);
        xhr.send(formData);

        xhr.onload = function() {
                let responseObj = xhr.response;
              
                console.log("First date Data: " + responseObj.data);              
                document.getElementById('thedate').value = responseObj.data.date;
                var headyear = document.querySelector('#theterm');
                console.log("The head year: " + headyear);
                headyear.value = responseObj.data.term;
        }

        function getClassData(){
            let sd = document.querySelector('#thedate');
            let ed = document.querySelector('#thedate2');
            let tr = document.querySelector('#theterm');

            let xhr = new XMLHttpRequest();
            xhr.open('POST', '/mne_setValues');
            xhr.responseType = 'json';
            let formData = new FormData();
            formData.append("api_token", token);
            formData.append("sd", sd.value);
            formData.append("ed", ed.value);
            formData.append("tr", tr.value);
            xhr.send(formData);

            xhr.onload = function() {
                console.log("Seen data inside of Class data ");
                setTimeout(function(){ location.reload(); },200);
            }
        }


    </script>

    </body>

</html>