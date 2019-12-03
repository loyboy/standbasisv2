<?php //-----THIS IS THE PAGE TO SEE ACTIVE LESSONNOTE BY TEACHER IN LESSONOTE ?>
<?php include 'includes/header_admin.php' ?>
<body data-open="click" data-menu="vertical-menu" data-col="2-columns" class="vertical-layout vertical-menu 2-columns menu-expanded content-detached-right-sidebar fixed-navbar">

<!-- SIDEBAR + NAVBAR + FUNCTION -->
 <?php include 'includes/navbar.blade.php'; ?>
 <?php include 'functions/lessonnote/index.php' ?>        
 <?php include 'includes/sidebar_lessonnote.php'; ?>
        
<div class="app-content content">
    <div class="content-wrapper">
          <!--The Main Header-->
     
      <div class="content-detached content-left">
        
        <!-- Basic Tables start -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title"> 
                Enter Examination Scores for Lessonnote. </h4>
               
              </div>
         

              <div class="card-content collapse show">
                <!--Card 1-->
                
                <!--End Card 1-->

                 <!--Card 2-->
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 " style="width:100%;"> 

                            <div class="card-body" style="margin-top: 5px;">

                            <div class="row">
                            <?php include 'includes/lsn_header.php'; ?>
                            </div>

                       <?php 
                       
                       //1st get the Class that belongs to the Lessonnote ID that has been passed...
                                    $typ = array('AS'=> "Assignment", 'CW' => "Classwork", 'TS' => "Test" );
                                    $mytyp = session('ln_enterscore_type');
                                    $mylsn = session('ln_enterscore_lsn');
                                    
                       // $cls_id = DB::select("SELECT class_id FROM lessonnote WHERE lsn_id = :id " , [ "id" => $mylsn ] );
                        $cls_id = DB::table('lessonnote')->where('lsn_id',$mylsn)->value('class_id');
                   
                       //2nd get the pupils that belong to that Class and School...
                        $pupilname_ = DB::select('CALL getPUPname_(?,?);', [$cls_id,session('general.school_id')]);
                        if (count($pupilname_) > 0){
                        session(['ln_pupil' => $pupilname_ ]);    
                        }
                       
                       ?>

                        <form  method="post" onkeypress="return event.keyCode != 13;" action=<?php echo action('LessonNoteController@enterscore'); ?>>
                      
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
                      <td colspan="1" class="_toolbar"><a><?php echo $r->Namez; ?></a></td>
                     
                      <td colspan="1" class="_toolbar">
                          <input type="number" size="20" class="form-control scoreinput" required="required" placeholder="Enter score for student" name="score[]" value="<?php echo getgeneralscore($mylsn,$r->PUP_ID,$mytyp); ?>" >
                      </td>
                      
                  </tr>
                  <tr><td colspan="2">
                       <?php echo csrf_field(); ?>
                       <input type="hidden" name="examid" value="<?php echo checkforExamGeneral($mylsn,$mytyp); ?>">
                        <input type="hidden" name="clsid" value="<?php echo $cls_id; ?>">
                      </td> </tr>
                  <?php }} ?>
                  
                  <tr><td colspan="2"><input type= "submit" class="btn btn-block btn-primary" id="enterscore" value="Submit Scores"> </td></tr>
                              
                                
                                              
                        </table>

                        </form>
                            
                        </div>

                        </div>
                       

                        
                    
                    </div><!--row end-->
                       

              </div>
              <!--card-content end-->

           </div><!--card end-->    

                 <!--end Card 2-->
                 
    
              </div>
            </div>
          </div>
           <!-- sidebar detached right-->
          
            
            <!-- sidebar detached right-->
        </div>

      </div>
  
      


 <!-- END Attendance Main page -->
    <!-- /#wrapper -->
<?php include 'includes/footer_admin.php'; 
       
        