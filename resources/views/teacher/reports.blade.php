<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
    <?php use App\Http\Controllers\SchoolController;?>
    <?php use App\Http\Controllers\SubjectController;?>
    <?php use App\Http\Controllers\PupilController;?>
    <?php use App\Http\Controllers\ClassStreamController;?>
   
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Standbasis Report') }}</title>

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <link href="{{ asset('css/report.css') }}" rel="stylesheet">
  
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
    
    <body class="c74">
      <p class="c75" id="h.gjdgxs"><span class="c35">Teacher, Student, class and school Comprehensive Report</span></p>
      <a id="t.ea327aea34bcfcf407483f935dafdfbcbf9e0aa5"></a><a id="t.0"></a>
      <div class="row" style="border: 1px solid #000; border-radius: 3px; "> 
                        <div class="container" style="padding: 2%;"> 
                        <div class="row col-12" style=" ">

                                <div class="form-group col-3">
                                    <label> School </label>
                                    <select class="form-control form-control-lg" tabindex="-1" aria-hidden="true" id="t_sch" name="t_sch">
                                       <option value="" >Select A School</option>                                                
                                    </select>
                                </div>

                                <div class="form-group col-3">
                                    <label> Start Date: </label>
                                    <input type="date" name="t_mydate" class="form-control" id="t_thedate" value="">
                                </div>
                                
                                
                                <div class="form-group col-3">
                                    <label> End Date: </label>
                                    <input type="date" name="t_mydate2" class="form-control" id="t_thedate2" value="<?php echo date('Y-m-d'); ?>">
                                </div>  
                                
                            
                                <div class="form-group col-3">
                                    <label> Term: </label>
                                    <select class="form-control"  name="t_head_year" id="t_theterm">
                                        <option value="">Select.. </option>
                                        <option value="1"> 1st Term </option>
                                        <option value="2"> 2nd Term </option>
                                        <option value="3"> 3rd Term </option>
                                    </select>
                                </div>  

                                <div class="form-group col-3">
                                    <label> Action:  </label>
                                    <button class="btn btn-danger btn-block" onclick="getTeacherData()" > Perform Search </button>
                                </div>
                        </div>
                        
                     <!--   <div class="row col-6" style=""> 
                        <label> Search Student Name:  </label>
                            <input type="text" class="form-control" id="mySearch" onkeyup="mySearch()" placeholder="Search for Student names...">
                        </div> -->
            </div>
            <?php
             $school = "";
             $sd = "";
             $ed = "";
             $term = "";
             if( session()->has('searchdata') ) 
               { 
                  $sd =  session('searchdata.sd'); $ed =  session('searchdata.ed');  $term = session('searchdata.tr'); $school = session('searchdata.sch'); 
               } 
               if ($school !== ''){
                   $subjects = SubjectController::getSubjectAll($school);
               }
            ?>
      <table class="c25">
         <tbody>
            <tr class="c70">
               <td class="c9 c84" colspan="17" rowspan="1">
                  <p class="c1"><span class="c51 c40 c20"></span></p>
                  <p class="c30"><span class="c40 c20">[<?php if ($school !== ''){ SchoolController::getSchoolName($school); } ?>] Teacher Performance Record for the period: <?php echo $sd; ?> to <?php echo $ed; ?></span></p>
                  <p class="c1"><span class="c4"></span></p>
               </td>
            </tr>
            <tr class="c81">
               <td class="c23" colspan="1" rowspan="2">
                  <p class="c1"><span class="c2"></span></p>
                  <p class="c1"><span class="c2"></span></p>
                  <p class="c1"><span class="c2"></span></p>
                  <p class="c1"><span class="c2"></span></p>
                  <p class="c1"><span class="c2"></span></p>
                  <p class="c1"><span class="c2"></span></p>
                  <p class="c1"><span class="c2"></span></p>
                  <p class="c1"><span class="c2"></span></p>
                  <p class="c1"><span class="c2"></span></p>
                  <p class="c30"><span class="c2">Teacher Staff ID</span></p>
               </td>
               <td class="c34" colspan="1" rowspan="2">
                  <p class="c17"><span class="c2"></span></p>
                  <p class="c17"><span class="c2"></span></p>
                  <p class="c17"><span class="c2"></span></p>
                  <p class="c17"><span class="c2"></span></p>
                  <p class="c17"><span class="c2"></span></p>
                  <p class="c17"><span class="c2"></span></p>
                  <p class="c17"><span class="c2"></span></p>
                  <p class="c17"><span class="c2"></span></p>
                  <p class="c17"><span class="c2"></span></p>
                  <p class="c52"><span class="c2">Teacher Name</span></p>
               </td>
               <td class="c41" colspan="1" rowspan="2">
                  <p class="c52"><span class="c7">Class Taught</span></p>
               </td>
               <?php
                  if ($school !== ''){
                  foreach ($subjects as $s){
               ?>
                  <td class="c85" colspan="8" rowspan="1">
                     <p class="c6"><span class="c7"> <?php echo $s['name']; ?></span></p>
                  </td>
               <?php } } ?>
             <!--  <td class="c41" colspan="9" rowspan="1">
                  <p class="c6"><span class="c7">Subject 2</span></p>
               </td>
               <td class="c85" colspan="8" rowspan="1">
                  <p class="c6"><span class="c7">Subject n</span></p>
               </td> -->
               <td class="c13 c9" colspan="1" rowspan="1">
                  <p class="c17"><span class="c7"></span></p>
                  <p class="c17"><span class="c7"></span></p>
                  <p class="c30"><span class="c29">Performance</span></p>
               </td>
            </tr>
            <tr class="c83">
               <?php
                  if ($school !== ''){
                  foreach ($subjects as $s){
               ?>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Attendance Performance</span></p>
                  <p class="c6"><span class="c2">Performance</span></p>
               </td>
               <td class="c15 c56" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Attendance Management</span></p>
               </td>
               <td class="c42 c56" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Lesson Notes Performance</span></p>
                  <p class="c6"><span class="c2">performance</span></p>
               </td>
               <td class="c15 c56" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Lesson Notes Management</span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Class Work Performance</span></p>
               </td>
               <td class="c42" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Homework Performance</span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Mid-term Performance</span></p>
               </td>
               <td class="c21" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Terminal Exam Performance</span></p>
                  <p class="c6 c58"><span class="c2"></span></p>
               </td>
               <?php } } ?>
               <!--
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Attendance Performance</span></p>
                  <p class="c6"><span class="c2">Performance</span></p>
               </td>
               <td class="c15 c56" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Attendance Management</span></p>
               </td>
               <td class="c15 c56" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Lesson Notes Performance</span></p>
                  <p class="c6"><span class="c2">performance</span></p>
               </td>
               <td class="c42 c56" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Lesson Notes Management</span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Class Work Performance</span></p>
               </td>
               <td class="c15" colspan="2" rowspan="1">
                  <p class="c6"><span class="c2">Homework Performance</span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Mid-term Performance</span></p>
               </td>
               <td class="c21" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Terminal Exam Performance</span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Attendance Performance</span></p>
                  <p class="c6"><span class="c2">Performance</span></p>
               </td>
               <td class="c15 c56" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Attendance Management</span></p>
               </td>
               <td class="c42 c56" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Lesson Notes Performance</span></p>
                  <p class="c6"><span class="c2">performance</span></p>
               </td>
               <td class="c15 c56" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Lesson Notes Management</span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Class Work Performance</span></p>
               </td>
               <td class="c42" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Homework Performance</span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Mid-term Performance</span></p>
               </td>
               <td class="c21" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Terminal Exam Performance</span></p>
               </td> -->

               <td class="c13" colspan="1" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
                  <p class="c1"><span class="c2"></span></p>
                  <p class="c30"><span class="c2">Aggregate based on school grade computation policy</span></p>
               </td>
            </tr>


            <tr class="c60">
               <td class="c23" colspan="1" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
               </td>
               <td class="c34" colspan="1" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
               </td>
               <td class="c61" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c42" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c42" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c21" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c42" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c15" colspan="2" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c21" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c42" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c42" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c21" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c13" colspan="1" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
               </td>
            </tr>

            <!--
            <tr class="c60">
               <td class="c23" colspan="1" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
               </td>
               <td class="c34" colspan="1" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
               </td>
               <td class="c61" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c42" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c42" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c21" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c42" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c15" colspan="2" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c21" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c42" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c42" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c21" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c13" colspan="1" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
               </td>
            </tr>
            <tr class="c60">
               <td class="c23" colspan="1" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
               </td>
               <td class="c34" colspan="1" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
               </td>
               <td class="c61" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c42" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c42" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c21" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c42" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c15" colspan="2" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c21" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c42" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c42" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c15" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c21" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c13" colspan="1" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
               </td>
            </tr>
            --> 

         </tbody>
      </table>
      <p class="c24"><span class="c35"></span></p>
      <a id="t.e201a0a311d08feb0bb860fbd7b57e5653398728"></a><a id="t.1"></a>


      







      <table class="c25">
         <tbody>
            <tr class="c67">
               <td class="c9 c69" colspan="12" rowspan="1">
                  <p class="c1"><span class="c4"></span></p>
                  <p class="c30"><span class="c40 c20">[School Name] Student Performance Record &nbsp;for the period: X to Y</span></p>
                  <p class="c1"><span class="c4"></span></p>
               </td>
            </tr>
            <tr class="c32">
               <td class="c55" colspan="1" rowspan="2">
                  <p class="c1"><span class="c2"></span></p>
                  <p class="c1"><span class="c2"></span></p>
                  <p class="c1"><span class="c2"></span></p>
                  <p class="c1"><span class="c2"></span></p>
                  <p class="c1"><span class="c2"></span></p>
                  <p class="c1"><span class="c2"></span></p>
                  <p class="c1"><span class="c2"></span></p>
                  <p class="c1"><span class="c2"></span></p>
                  <p class="c1"><span class="c2"></span></p>
                  <p class="c30"><span class="c2">Student ID</span></p>
               </td>
               <td class="c46" colspan="1" rowspan="2">
                  <p class="c17"><span class="c2"></span></p>
                  <p class="c17"><span class="c2"></span></p>
                  <p class="c17"><span class="c2"></span></p>
                  <p class="c17"><span class="c2"></span></p>
                  <p class="c17"><span class="c2"></span></p>
                  <p class="c17"><span class="c2"></span></p>
                  <p class="c17"><span class="c2"></span></p>
                  <p class="c17"><span class="c2"></span></p>
                  <p class="c17"><span class="c2"></span></p>
                  <p class="c52"><span class="c2">Student Name</span></p>
               </td>
               <td class="c41" colspan="1" rowspan="2">
                  <p class="c17"><span class="c7"></span></p>
                  <p class="c17"><span class="c7"></span></p>
                  <p class="c17"><span class="c7"></span></p>
                  <p class="c52"><span class="c7">Class </span></p>
               </td>
               <td class="c41" colspan="5" rowspan="1">
                  <p class="c6"><span class="c7">Subject 1</span></p>
               </td>
               <td class="c41" colspan="6" rowspan="1">
                  <p class="c6"><span class="c7">Subject 2</span></p>
               </td>
               <td class="c41" colspan="5" rowspan="1">
                  <p class="c6"><span class="c7">Subject n</span></p>
               </td>
               <td class="c26 c9" colspan="1" rowspan="1">
                  <p class="c17"><span class="c7"></span></p>
                  <p class="c17"><span class="c7"></span></p>
                  <p class="c30"><span class="c29">Performance</span></p>
               </td>
            </tr>
            <tr class="c72">
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Attendance</span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Class work</span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Home work</span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Mid-term</span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Terminal Exam Performance</span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Attendance</span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Class work</span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Home work</span></p>
               </td>
               <td class="c8" colspan="2" rowspan="1">
                  <p class="c6"><span class="c2">Mid-term</span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Terminal Exam Performance</span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Attendance</span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Class work</span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Home work</span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Mid-term</span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Terminal Exam Performance</span></p>
               </td>
               <td class="c13" colspan="2" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
                  <p class="c30"><span class="c2">Aggregate based on school grade computation policy</span></p>
                  <p class="c1"><span class="c2"></span></p>
                  <p class="c1"><span class="c2"></span></p>
               </td>
            </tr>
            <tr class="c37">
               <td class="c55" colspan="1" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
               </td>
               <td class="c46" colspan="1" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
               </td>
               <td class="c61" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="2" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c13" colspan="2" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
               </td>
            </tr>
            <tr class="c37">
               <td class="c55" colspan="1" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
               </td>
               <td class="c46" colspan="1" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
               </td>
               <td class="c61" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="2" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c13" colspan="2" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
               </td>
            </tr>
            <tr class="c37">
               <td class="c55" colspan="1" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
               </td>
               <td class="c46" colspan="1" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
               </td>
               <td class="c61" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="2" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c13" colspan="2" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
               </td>
            </tr>
         </tbody>
      </table>



      <p class="c24"><span class="c35"></span></p>
      <p class="c24"><span class="c35"></span></p>
      <p class="c24"><span class="c35"></span></p>
      <a id="t.f909400da77686b712ffc3c3d4ef5eb2955ee11c"></a><a id="t.2"></a>
      <table class="c25">
         <tbody>
            <tr class="c16">
               <td class="c27 c9" colspan="21" rowspan="1">
                  <p class="c1"><span class="c51 c40 c20"></span></p>
                  <p class="c30"><span class="c40 c20">[School Name] Principal Performance Record for the period: X to Y</span></p>
                  <p class="c1"><span class="c4"></span></p>
               </td>
            </tr>
            <tr class="c79">
               <td class="c43" colspan="1" rowspan="2">
                  <p class="c1"><span class="c2"></span></p>
                  <p class="c1"><span class="c2"></span></p>
                  <p class="c1"><span class="c2"></span></p>
                  <p class="c1"><span class="c2"></span></p>
                  <p class="c1"><span class="c2"></span></p>
                  <p class="c1"><span class="c2"></span></p>
                  <p class="c1"><span class="c2"></span></p>
                  <p class="c1"><span class="c2"></span></p>
                  <p class="c1"><span class="c2"></span></p>
                  <p class="c30"><span class="c2">Staff ID</span></p>
               </td>
               <td class="c31" colspan="1" rowspan="2">
                  <p class="c17"><span class="c2"></span></p>
                  <p class="c17"><span class="c2"></span></p>
                  <p class="c17"><span class="c2"></span></p>
                  <p class="c17"><span class="c2"></span></p>
                  <p class="c17"><span class="c2"></span></p>
                  <p class="c17"><span class="c2"></span></p>
                  <p class="c17"><span class="c2"></span></p>
                  <p class="c17"><span class="c2"></span></p>
                  <p class="c17"><span class="c2"></span></p>
                  <p class="c52"><span class="c2">Name</span></p>
               </td>
               <td class="c5" colspan="1" rowspan="2">
                  <p class="c52"><span class="c7">Class Taught</span></p>
               </td>
               <td class="c62" colspan="5" rowspan="1">
                  <p class="c6"><span class="c7">Subject 1</span></p>
               </td>
               <td class="c62" colspan="5" rowspan="1">
                  <p class="c6"><span class="c7">Subject 2</span></p>
               </td>
               <td class="c62" colspan="5" rowspan="1">
                  <p class="c6"><span class="c7">Subject n</span></p>
               </td>
               <td class="c10" colspan="1" rowspan="2">
                  <p class="c6"><span class="c20">Attendance Administration</span></p>
               </td>
               <td class="c10" colspan="1" rowspan="2">
                  <p class="c6"><span class="c2">Attendance </span></p>
                  <p class="c6"><span class="c20">Administration query resolution</span></p>
               </td>
               <td class="c10" colspan="2" rowspan="2">
                  <p class="c6"><span class="c20">Lesson Notes administration</span></p>
               </td>
               <td class="c10" colspan="1" rowspan="2">
                  <p class="c6"><span class="c20">Lesson Noted Management query resolution</span></p>
               </td>
               <td class="c53 c9" colspan="1" rowspan="1">
                  <p class="c17"><span class="c7"></span></p>
                  <p class="c17"><span class="c7"></span></p>
                  <p class="c30"><span class="c29">Performance</span></p>
               </td>
            </tr>
            <tr class="c48">
               <td class="c3" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Attendance</span></p>
               </td>
               <td class="c3" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Class work</span></p>
               </td>
               <td class="c18" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Home work</span></p>
               </td>
               <td class="c3" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Mid-term</span></p>
               </td>
               <td class="c18" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Terminal Exam Performance</span></p>
               </td>
               <td class="c3" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Attendance</span></p>
               </td>
               <td class="c3" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Class work</span></p>
               </td>
               <td class="c18" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Home work</span></p>
               </td>
               <td class="c3" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Mid-term</span></p>
               </td>
               <td class="c18" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Terminal Exam Performance</span></p>
               </td>
               <td class="c3" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Attendance</span></p>
               </td>
               <td class="c3" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Class work</span></p>
               </td>
               <td class="c18" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Home work</span></p>
               </td>
               <td class="c3" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Mid-term</span></p>
               </td>
               <td class="c18" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Terminal Exam Performance</span></p>
               </td>
               <td class="c53" colspan="1" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
                  <p class="c1"><span class="c2"></span></p>
                  <p class="c30"><span class="c2">Aggregate based on school grade computation policy</span></p>
               </td>
            </tr>
            <tr class="c19">
               <td class="c43" colspan="1" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
               </td>
               <td class="c31" colspan="1" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
               </td>
               <td class="c50" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c3" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c3" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c18" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c3" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c18" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c3" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c3" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c18" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c3" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c18" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c3" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c3" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c18" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c3" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c18" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c54" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c54" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c54" colspan="2" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c54" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c53" colspan="1" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
               </td>
            </tr>
            <tr class="c19">
               <td class="c43" colspan="1" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
               </td>
               <td class="c31" colspan="1" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
               </td>
               <td class="c50" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c3" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c3" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c18" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c3" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c18" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c3" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c3" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c18" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c3" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c18" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c3" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c3" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c18" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c3" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c18" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c54" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c54" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c54" colspan="2" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c54" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c53" colspan="1" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
               </td>
            </tr>
            <tr class="c19">
               <td class="c43" colspan="1" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
               </td>
               <td class="c31" colspan="1" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
               </td>
               <td class="c50" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c3" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c3" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c18" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c3" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c18" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c3" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c3" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c18" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c3" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c18" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c3" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c3" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c18" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c3" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c18" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c54" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c54" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c54" colspan="2" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c54" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c53" colspan="1" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
               </td>
            </tr>
         </tbody>
      </table>











      <p class="c24"><span class="c35"></span></p>
      <p class="c24"><span class="c35"></span></p>
      <a id="t.148f3a7f1c00f82d132f5ec43f5366d8bea6a483"></a><a id="t.3"></a>
      <table class="c25">
         <tbody>
            <tr class="c67">
               <td class="c69 c9" colspan="20" rowspan="1">
                  <p class="c1"><span class="c4"></span></p>
                  <p class="c30"><span class="c20 c40">[School Name] Class Performance Record &nbsp;for the period: X to Y</span></p>
                  <p class="c1"><span class="c4"></span></p>
               </td>
            </tr>
            <tr class="c32">
               <td class="c55" colspan="1" rowspan="2">
                  <p class="c1"><span class="c2"></span></p>
                  <p class="c1"><span class="c2"></span></p>
                  <p class="c1"><span class="c2"></span></p>
                  <p class="c1"><span class="c2"></span></p>
                  <p class="c1"><span class="c2"></span></p>
                  <p class="c1"><span class="c2"></span></p>
                  <p class="c1"><span class="c2"></span></p>
                  <p class="c1"><span class="c2"></span></p>
                  <p class="c1"><span class="c2"></span></p>
                  <p class="c30"><span class="c2">Class Title</span></p>
               </td>
               <td class="c59" colspan="5" rowspan="1">
                  <p class="c6"><span class="c7">Subject 1</span></p>
               </td>
               <td class="c41" colspan="5" rowspan="1">
                  <p class="c6"><span class="c7">Subject 2</span></p>
               </td>
               <td class="c41" colspan="5" rowspan="1">
                  <p class="c6"><span class="c7">Subject 3</span></p>
               </td>
               <td class="c41" colspan="6" rowspan="1">
                  <p class="c6"><span class="c7">Subject 4</span></p>
               </td>
               <td class="c41" colspan="5" rowspan="1">
                  <p class="c6"><span class="c7">Subject n</span></p>
               </td>
               <td class="c9 c26" colspan="1" rowspan="1">
                  <p class="c17"><span class="c7"></span></p>
                  <p class="c17"><span class="c7"></span></p>
                  <p class="c30"><span class="c29">Performance</span></p>
               </td>
            </tr>
            <tr class="c72">
               <td class="c47" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Attendance</span></p>
               </td>
               <td class="c47" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Class work</span></p>
               </td>
               <td class="c47" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Home work</span></p>
               </td>
               <td class="c47" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Mid-term</span></p>
               </td>
               <td class="c47" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Terminal Exam Performance</span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Attendance</span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Class work</span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Home work</span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Mid-term</span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Terminal Exam Performance</span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Attendance</span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Class work</span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Home work</span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Mid-term</span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Terminal Exam Performance</span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Attendance</span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Class work</span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Home work</span></p>
               </td>
               <td class="c8" colspan="2" rowspan="1">
                  <p class="c6"><span class="c2">Mid-term</span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Terminal Exam Performance</span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Attendance</span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Class work</span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Home work</span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Mid-term</span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Terminal Exam Performance</span></p>
               </td>
               <td class="c13" colspan="2" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
                  <p class="c30"><span class="c2">Aggregate based on school grade computation policy</span></p>
                  <p class="c1"><span class="c2"></span></p>
                  <p class="c1"><span class="c2"></span></p>
               </td>
            </tr>
            <tr class="c37">
               <td class="c55" colspan="1" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
               </td>
               <td class="c47" colspan="1" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
               </td>
               <td class="c47" colspan="1" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
               </td>
               <td class="c47" colspan="1" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
               </td>
               <td class="c47" colspan="1" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
               </td>
               <td class="c47" colspan="1" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="2" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c13" colspan="2" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
               </td>
            </tr>
            <tr class="c37">
               <td class="c55" colspan="1" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
               </td>
               <td class="c47" colspan="1" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
               </td>
               <td class="c47" colspan="1" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
               </td>
               <td class="c47" colspan="1" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
               </td>
               <td class="c47" colspan="1" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
               </td>
               <td class="c47" colspan="1" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="2" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c13" colspan="2" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
               </td>
            </tr>
            <tr class="c37">
               <td class="c55" colspan="1" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
               </td>
               <td class="c47" colspan="1" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
               </td>
               <td class="c47" colspan="1" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
               </td>
               <td class="c47" colspan="1" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
               </td>
               <td class="c47" colspan="1" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
               </td>
               <td class="c47" colspan="1" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="2" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c8" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c13" colspan="2" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
               </td>
            </tr>
         </tbody>
      </table>












      <p class="c24"><span class="c35"></span></p>
      <p class="c24"><span class="c35"></span></p>
      <a id="t.0cb15f3ea2ea3a2bc2979758482273c46f39eaf7"></a><a id="t.4"></a>
      <table class="c25">
         <tbody>
            <tr class="c16">
               <td class="c9 c27" colspan="28" rowspan="1">
                  <p class="c1"><span class="c40 c20 c51"></span></p>
                  <p class="c30"><span class="c40 c20">[School Name] School Performance Record for the period: X to Y</span></p>
                  <p class="c1"><span class="c4"></span></p>
               </td>
            </tr>
            <tr class="c79">
               <td class="c86" colspan="5" rowspan="1">
                  <p class="c6 c58"><span class="c7"></span></p>
                  <p class="c6"><span class="c7">Subject 1</span></p>
                  <p class="c6 c58"><span class="c7"></span></p>
               </td>
               <td class="c78" colspan="5" rowspan="1">
                  <p class="c6"><span class="c7">Subject 2</span></p>
               </td>
               <td class="c78" colspan="5" rowspan="1">
                  <p class="c6"><span class="c7">Subject 3</span></p>
               </td>
               <td class="c78" colspan="5" rowspan="1">
                  <p class="c6"><span class="c7">Subject 4</span></p>
               </td>
               <td class="c78" colspan="5" rowspan="1">
                  <p class="c6"><span class="c7">Subject n</span></p>
               </td>
               <td class="c10" colspan="1" rowspan="2">
                  <p class="c6"><span class="c20">Attendance Administration</span></p>
               </td>
               <td class="c10" colspan="1" rowspan="2">
                  <p class="c6"><span class="c2">Attendance </span></p>
                  <p class="c6"><span class="c20">Administration query resolution</span></p>
               </td>
               <td class="c10" colspan="2" rowspan="2">
                  <p class="c6"><span class="c20">Lesson Notes administration</span></p>
               </td>
               <td class="c10" colspan="1" rowspan="2">
                  <p class="c6"><span class="c20">Lesson Noted Management query resolution</span></p>
               </td>
               <td class="c9 c53" colspan="1" rowspan="1">
                  <p class="c17"><span class="c7"></span></p>
                  <p class="c17"><span class="c7"></span></p>
                  <p class="c30"><span class="c29">Performance</span></p>
               </td>
            </tr>
            <tr class="c48">
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Attendance</span></p>
               </td>
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Class work</span></p>
               </td>
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Home work</span></p>
               </td>
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Mid-term</span></p>
               </td>
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Terminal Exam </span></p>
               </td>
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Attendance</span></p>
               </td>
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Class work</span></p>
               </td>
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Home work</span></p>
               </td>
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Mid-term</span></p>
               </td>
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Terminal Exam </span></p>
               </td>
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Attendance</span></p>
               </td>
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Class work</span></p>
               </td>
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Home work</span></p>
               </td>
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Mid-term</span></p>
               </td>
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Terminal Exam </span></p>
               </td>
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Attendance</span></p>
               </td>
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Class work</span></p>
               </td>
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Home work</span></p>
               </td>
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Mid-term</span></p>
               </td>
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Terminal Exam </span></p>
               </td>
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Attendance</span></p>
               </td>
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Class work</span></p>
               </td>
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Home work</span></p>
               </td>
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Mid-term</span></p>
               </td>
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c6"><span class="c2">Terminal Exam </span></p>
               </td>
               <td class="c53" colspan="1" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
                  <p class="c1"><span class="c2"></span></p>
                  <p class="c30"><span class="c2">Aggregate based on school grade computation policy</span></p>
               </td>
            </tr>
            <tr class="c19">
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
                  <p class="c1"><span class="c14"></span></p>
                  <p class="c1"><span class="c14"></span></p>
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c11" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c54" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c54" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c54" colspan="2" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c54" colspan="1" rowspan="1">
                  <p class="c1"><span class="c14"></span></p>
               </td>
               <td class="c53" colspan="1" rowspan="1">
                  <p class="c1"><span class="c2"></span></p>
               </td>
            </tr>
         </tbody>
      </table>
      <p class="c24"><span class="c35"></span></p>
      <p class="c24"><span class="c35"></span></p>
  

    <script>
        let token = 'juyupackcnmluyrgejaicapyqeyathqbzrximmsuwakuawik';    
    
        let xhr = new XMLHttpRequest();
        xhr.open('GET', '/schools');
        xhr.responseType = 'json';
        let formData = new FormData();
        formData.append("api_token", token);
        xhr.send(formData);

        xhr.onload = function() {               
            let responseObj = xhr.response;
            var selectinput = document.querySelector('#t_sch');                       
            for ( let datarow of responseObj ){ 
               selectinput.options[selectinput.options.length] = new Option( datarow.name, datarow.id );         
            }                
        }

        function getTeacherData(){
            let sch = document.querySelector('#t_sch');
            if(sch === ""){ alert("Please select a school in the box."); return;}
            let sd = document.querySelector('#t_thedate');
            let ed = document.querySelector('#t_thedate2');
            let tr = document.querySelector('#t_theterm');

            let xhr = new XMLHttpRequest();
            xhr.open('POST', '/mne_setValues');
            xhr.responseType = 'json';
            let formData = new FormData();
            formData.append("api_token", token);
            formData.append("sch", sch.value);
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