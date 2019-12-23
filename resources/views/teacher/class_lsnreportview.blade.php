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
                elem.setAttribute("download", "standbasis_class_lessonnote_report.xls"); // Choose the file name
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
                <p class="lead text-white">Standbasis Report for Class': <?php echo ClassStreamController::getClassName($classid) ?>  Lessonnote Performance </p>
                <a id="downloadLink" class="btn btn-danger" onclick="exportExcel(this)">Export to excel</a>
            </div>         

    <div class="JStableOuter" >
    <p> 
        <span> CW :  </span> <span> Classwork Score </span>
        <span> AS :  </span> <span> Assignment Score </span>
        <span> TS :  </span> <span> Test Score </span>
    </p>

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
  
    </thead>
    <tbody>
      
        <?php
               $pup = PupilController::getAllPupilsInClass($classid);
               foreach ($pup as $p){
        ?>
        <tr>
            <td style=" text-align: center; "> <?php echo PupilController::getPupilName($p['id']); ?></td>
        <?php
               $sub = SubjectController::getSubjectAll($school);
               foreach ($sub as $s){                     
        ?>
            <td style=" text-align: center; "> <?php echo SubjectController::getSubjectLessonnote($p['pupil_id'], $s['id']); ?> </td>

        <?php } ?>
          
        </tr>
        <?php } ?>    

    </tbody>
  </table>
</div>

    </div>

    </body>

</html>