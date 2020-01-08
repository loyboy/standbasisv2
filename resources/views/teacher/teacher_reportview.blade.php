<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
    <?php use App\Http\Controllers\SubjectController;?>
    <?php use App\Http\Controllers\TeacherController;?>
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
                elem.setAttribute("download", "standbasis_teacher_attendance_report.xls"); // Choose the file name
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
                <p class="lead text-white">Standbasis Report for All Teacher's Attendance</p>
                <br>
                <a id="downloadLink" class="btn btn-danger" onclick="exportExcel(this)">Export to excel</a>
            </div>   

            <div class="row" style="border: 1px solid #000; border-radius: 3px; "> 
                        <div class="container" style="padding: 2%;"> 
                        <div class="row col-12" style=" ">
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
                                    <button class="btn btn-danger btn-block" onclick="getClassData()" > Perform Search </button>
                                </div>
                        </div>
                        
                        <div class="row col-6" style=""> 
                        <label> Search Student Name:  </label>
                            <input type="text" class="form-control" id="mySearch" onkeyup="mySearch()" placeholder="Search for Student names...">
                        </div> 
            </div>       

    <div class="JStableOuter">

  <table id = "mytable">
    <thead>
      <tr style="top: 0px" >
        <th style="left: 0px" > Teacher Name </th>
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
   
    </thead>
    <tbody>
      
        <?php
               $teacher = TeacherController::getAllTeachersinSchool($school);
               foreach ($teacher as $t){
        ?>
        <tr>
            <td style=" text-align: center; "> <?php echo TeacherController::getTeacherName($t->tea_id). " ". ClassStreamController::getClassName($t->class_id);  ?></td>
        <?php
               $sub = SubjectController::getSubjectAll($school);
               foreach ($sub as $s){                     
        ?>
           <?php  if(session()->has('searchdata.sd')) { ?>
            <td style=" text-align: center; "> <?php echo SubjectController::getSubjectAttendanceTeacher($t->tea_id, $t->class_id, $s['id'],session('searchdata.sd'), session('searchdata.ed'), session('searchdata.tr')); ?> </td>

            <?php } else {  ?>

                <td style=" text-align: center; "> <?php echo SubjectController::getSubjectAttendanceTeacher($t->tea_id, $t->class_id, $s['id']); ?> </td>


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

                let thedate = $('#thedate').val();
                let thedate2 = $('#thedate2').val();
                let theterm = $('#theterm').val(); 
                var thetermval = "";

                if (Number(theterm) === 1){
                    thetermval = "1st Term";
                }
                else if (Number(theterm) === 2){
                    thetermval = "2nd Term";
                }
                else if (Number(theterm) === 3){
                    thetermval = "3rd Term";
                }

                if ($('#stid').contents().length == 0){
                    $('#stid').html(thedate); 
                }
                if ($('#etid').contents().length == 0){
                    $('#etid').html(thedate2); 
                }
                if ($('#trid').contents().length == 0){
                    $('#trid').html(thetermval); 
                }
                
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

        function mySearch() {
            // Declare variables
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("mySearch");
            filter = input.value.toUpperCase();
            table = document.getElementById("mytable");
            tr = table.getElementsByClassName("myrow");

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[0];
                if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
                }
            }
        }


    </script>

    </body>

</html>