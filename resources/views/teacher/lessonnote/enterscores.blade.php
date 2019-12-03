@extends('layouts.dashboard')
@include('teacher.functions.lessonnote.index')

@section('teacher')
  <!--gx-wrapper-->
  <div class="gx-wrapper">

<div class="animated slideInUpTiny animation-duration-3">

    <div class="page-heading">
        <h2 class="title">Add Scores to an Assessment </h2>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="gx-card">
                <div class="gx-card-header">
                    <h3 class="card-heading">Choose a Lessonnote Assessment and Add Scores to them </h3>
                </div>
                <div class="gx-card-body">
                <div class="card-content collapse show">
                <!--Card 1-->
                
                <!--End Card 1-->

                 <!--Card 2-->
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 " style="width:100%;"> 

                            <div class="card-body" style="margin-top: 5px;">
                    <?php 
                       
                       //1st get the Class that belongs to the Lessonnote ID that has been passed...
                                    $typ = array('AS'=> "Assignment", 'CW' => "Classwork", 'TS' => "Test" );
                                    $mytyp = session('ln_enterscore_type');
                                    $mylsn = session('ln_enterscore_lsn');
                                    
                       // $cls_id = DB::select("SELECT class_id FROM lessonnote WHERE lsn_id = :id " , [ "id" => $mylsn ] );
                        $cls_category = DB::table('lessonnotes')->where('id',$mylsn)->value('class_category'); //single_value
                   
                       //2nd get the pupils that belong to that Class and School...
                       $pupils = DB::select(
                        "SELECT en.id as pupid, CONCAT(p.lname,' ',p.fname) as namez
                        FROM enrollments en 
                        INNER JOIN class_streams cl ON en.class_id = cl.id
                        INNER JOIN pupils p ON en.pupil_id = p.id
                        WHERE cl.category = :cat       
                        " , [ "cat" => $cls_category ] );
                      
                        if (count($pupils) > 0){
                        session(['ln_pupil' => $pupils ]);    
                        }
                       
                       ?>

                        <form  method="post" onkeypress="return event.keyCode != 13;" action=<?php echo action('LessonnoteController@enterscore'); ?>>
                      
                        <table class="table table-bordered table-hover st_table" cellspacing="2" cellpadding="10" style="margin-top: 40px; "  >
                                   
                                <tr> <td>Type of Examination :</td> <td> <?php echo $typ[$mytyp]; ?></td></tr> 
                                <tr> <td>Lesson Note Name :</td> <td> <?php echo getLNName($mylsn); ?></td></tr> 
                                <tr> <td>Maximum Score :</td> <td> <input type="number" required="required" name="max" id="maximum" value="<?php echo getmaxscore($mylsn,$mytyp); ?>" class=" form-control-sm"> </td></tr> 
                                <tr><td colspan="2"></td> </tr>
               <?php   
                $register_stu = session('ln_pupil');        
                if (count($register_stu) > 0) {
                  foreach ($register_stu as $r) { ?>
                  <tr>
                      <td colspan="1" class="_toolbar"><a><?php echo $r->namez; ?></a></td>
                     
                      <td colspan="1" class="_toolbar">
                          <input type="number" size="20" class="form-control scoreinput" required="required" placeholder="Enter score for student" name="score[]" value="<?php echo getgeneralscore($mylsn,$r->pupid,$mytyp); ?>" >
                      </td>
                      
                  </tr>
                  <tr><td colspan="2">
                       <?php echo csrf_field(); ?>
                       <input type="hidden" name="examid" value="<?php echo checkforExamGeneral($mylsn,$mytyp); ?>">
                        <input type="hidden" name="clsid" value="<?php echo $cls_category; ?>">
                      </td> </tr>
                  <?php }} ?>
                  
                  <tr><td colspan="2"><input type= "submit" class="btn btn-block btn-primary" id="enterscore" value="Submit Scores"> </td></tr>     
                        
                        </table>

                        </form>
                            
                        </div>

                        </div>  
                    
                    </div>                    

                    </div><!--row end-->

                </div>
            </div>
        </div>
    </div>
</div>

</div>

@endsection


@section('myscript')
<script>

        //check the scoreinputs
        $(document).on('change','.scoreinput',function(e){
            var max = parseInt($('#maximum').val());
            
            if (max === null || max === 0 ){
                alert('Please enter the Maximum Value for this test...');
            }
            
            var current = parseInt($(this).val());
            
            if (current > max) {
                alert('This value you inputted is greater than the Maximum Value');
                $('#enterscore').attr("disabled","disabled");
            }
            else{
                $('#enterscore').removeAttr("disabled");
            }
        });

</script>
@endsection


