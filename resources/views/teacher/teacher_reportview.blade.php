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
            <td style=" text-align: center; "> <?php echo SubjectController::getSubjectAttendanceTeacher($t->tea_id, $t->class_id, $s['id']); ?> </td>

        <?php } ?>
          
        </tr>
        <?php } ?>      


    </tbody>
  </table>
</div>

    </div>

    </body>

</html>