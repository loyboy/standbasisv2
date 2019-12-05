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
                <p class="lead text-white">Standbasis Report for Class's: <?php echo ClassStreamController::getClassName($classid) ?>  Attendance</p>
            </div>         

    <div class="JStableOuter" >
  <table>
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
            <td> <?php echo PupilController::getPupilName($p['id']); ?></td>
        <?php
               $sub = SubjectController::getSubjectAll($school);
               foreach ($sub as $s){                     
        ?>
            <td> <?php echo SubjectController::getSubjectAttendance($p['pupil_id'], $s['id']); ?> </td>

        <?php } ?>
          
        </tr>
        <?php } ?>  
      


    </tbody>
  </table>
</div>

    </div>

    </body>

</html>