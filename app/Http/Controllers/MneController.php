<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Pupil;
use App\Teacher;
use App\Subjectclass;
use Illuminate\Support\Facades\DB;

class MneController extends Controller
{
    
       public function getSavedValues(Request $request){
        $sd = $request->input('sd');
        $sch = $request->input('sch');
        $ed = $request->input('ed');
        $tr = $request->input('tr');
        session()->flash('searchdata.sch', $sch);
        session()->flash('searchdata.sd', $sd);
        session()->flash('searchdata.ed', $ed);
        session()->flash('searchdata.tr', $tr);
          $mymsg = array(
            'done'=> 1         
          );
          return response()->json($mymsg);
       }

       public function getSavedValuesLsn(Request $request){
        $sd = $request->input('sd');
        $sch = $request->input('sch');
        $ed = $request->input('ed');
        $tr = $request->input('tr');
        session()->flash('searchdata.sd', $sd);
        session()->flash('searchdata.sch', $sch);
        session()->flash('searchdata.ed', $ed);
        session()->flash('searchdata.tr', $tr);
          $mymsg = array(
            'done'=> 1         
          );
          return response()->json($mymsg);
       }
       //////////////////////////////////////////////TEACHER
       public function loadteachermne_student_gen(Request $request){
         
        $typeofuser = $request->input('_type');

        $dateofreq2 = $request->input('sdate'); //start date
        
        $dateofreq = $request->input('edate'); //end date
        
        $dateofreq = date('Y-m-d', strtotime('+1 days', strtotime($dateofreq)));//add 1 day to original date to give accuracy
        
        $tea =  $request->input('tea'); //teacher ID
         
        $term = explode(';', $request->input('term') ); //Term ID of the school
        $termval = array( 1 => "1ST TERM" , 2 => "2ND TERM", 3 => "3RD TERM");
        
        $termid = intval( $term[0] );  $termname = intval( $term[1] );
         
        $valofreq = explode(';', $request->input('stu') ); //Student ID + EnrolID

        $enrolid = intval($valofreq[0]); $pupid = intval($valofreq[1]);
        
         ////////////////////////////////////////////ATTENDANCE
         //no. of times present 
         $results = DB::select(" SELECT IFNULL(COUNT(r.att_id),0) AS present, 
         ( SELECT CONCAT(fname,' ',lname) FROM pupils WHERE id = :pupid2 ) AS stuname, 
         ( SELECT class_id FROM enrollments WHERE id = :pupid3 AND term_id = :term ) AS clsid 
         FROM rowcalls r  JOIN attendances a 
         ON r.att_id = a.id 
         WHERE r._status = 1 AND a._date <= :dat AND a._date >= :dat2
         AND r.pup_id = :pupid AND a.sub_class_id IN ( SELECT id FROM subjectclasses WHERE tea_id = :tea ) AND a._done = 1 AND a._desc LIKE :desf " ,
         [ "dat" => $dateofreq , "dat2" => $dateofreq2, "pupid" => $pupid, "pupid2" => $pupid , "pupid3" =>$enrolid, "tea" => $tea, "desf" => '%'.$termval[$termname].'%', "term" => $termid  ] ); 
         
         //total no. of times attendance was taken 
         $results2 = DB::select(" SELECT IFNULL(COUNT(a.id),0) AS total
         FROM attendances a 
         JOIN rowcalls r 
         ON r.att_id = a.id 
         WHERE a._date <= :dat AND a._date >= :dat2 AND a.sub_class_id IN ( SELECT id FROM subjectclasses WHERE tea_id = :tea ) AND r.pup_id = :pupid AND a._desc LIKE :des " ,
          ["tea" => $tea, "dat" => $dateofreq,"dat2" => $dateofreq2, "pupid" => $pupid ,  "des" =>  '%'.$termval[$termname].'%' ] ); 
           
           $present = 0;//no. of times present
           $nameofstu = "";//name of student
           $total = 0;//total times attendance taken
           $clsid = null;
           
           if ($results != null){    
           foreach ($results as $r){ $present = $r->present; $nameofstu = $r->stuname; $clsid = $r->clsid; } 
           }
           else { $perf = 0; $nameofstu = $this->getStudentName($pupid); $clsid = $this->getStudentClassID($pupid,$termid );  }
          if ($results2 != null){   
              foreach ($results2 as $r){ $total = $r->total; }
          }
           else { $perf = 0; $nameofstu = $this->getStudentName($pupid); $clsid = $this->getStudentClassID($pupid,$termid);  }
           
           if ($present == 0 && $total == 0){ $perf = 0; } 
           else{ $perf = (intval($present)/intval($total)) * 100; }
           
           $clsname = $this->getClassName($clsid);
           
            $theattend = array(
               '_perf'=> $perf,
               '_name' => $nameofstu,
               '_class' => $clsname,
               '_datenow' => date('Y-m-d H:i:s')
              
           );
       
           $theattendsub = $this->getTypeAttendanceS( $tea, $pupid,$dateofreq,$dateofreq2,$termid, $typeofuser);
           ////////////////////////////////////////////END ATTENDANCE
          
           ////////////////////////////////////////////EXAMINATIONS
          $theclw = $this->getTypeAssessmentG('CW', $tea, $enrolid , $pupid, $dateofreq,$dateofreq2,$termid,$typeofuser );
             
           $theclwsub =  $this->getTypeAssessmentS('CW', $tea, $enrolid , $pupid, $dateofreq, $dateofreq2, $termid ,$typeofuser );
           
           $theass = $this->getTypeAssessmentG('AS', $tea, $enrolid , $pupid, $dateofreq, $dateofreq2, $termid ,$typeofuser);
           
           $theasssub = $this->getTypeAssessmentS('AS', $tea, $enrolid , $pupid, $dateofreq,$dateofreq2,$termid ,$typeofuser );
           
           $thetest = $this->getTypeAssessmentG('TS', $tea, $enrolid , $pupid, $dateofreq,$dateofreq2,$termid,$typeofuser);
           
           $thetestsub = $this->getTypeAssessmentS('TS', $tea, $enrolid , $pupid, $dateofreq,$dateofreq2,$termid  ,$typeofuser);
           
           $themidterm = $this->getTypeAssessmentG('MT', $tea, $enrolid , $pupid, $dateofreq,$dateofreq2,$termid,$typeofuser);
           
           $themidtermsub = $this->getTypeAssessmentS('MT', $tea, $enrolid , $pupid, $dateofreq,$dateofreq2,$termid ,$typeofuser);
           
           $theterminal = $this->getTypeAssessmentG('TE', $tea, $enrolid , $pupid, $dateofreq,$dateofreq2,$termid,$typeofuser); 
             
           ///////////////////////////////////////////END EXAMINATIONS
           
           /** **/
           
          $mymsg = array(
               '_att1'=> $theattend,
               '_att11'=> $theattendsub,
               '_att2' => $theclw,
               '_att3' => $theclwsub,
               '_att4' => $theass,
               '_att5' => $theasssub,
               '_att6' => $thetest,
               '_att7' => $thetestsub,
               '_att8' => $themidterm,
               '_att9' => $themidtermsub,
               '_att10' => $theterminal
              
           ); 

         /*  $mymsg = array(
            '_att1'=> $theattend ,         
            '_att11'=> $theattendsub,
        ); */
         
           
            return response()->json($mymsg);
        
   }

    /////////////////////////////////////////////////////////////////STUDENT
    
    private function getTypeAssessmentG ( $type, $teaid, $enrol, $pup, $d, $d2 , $term, $typeofuser ){
         
        if ( $typeofuser === "teacher" ){
            $resultsclw = DB::select(" SELECT IFNULL(AVG(s.perf),0) as perf FROM scores s 
            WHERE s.enrol_id = :pup 
            AND s.ass_id IN ( SELECT e.id FROM assessments e JOIN lessonnote_managements l 
            ON l.lsn_id = e.lsn_id 
            WHERE e._type = :typ AND l._approval != :appr AND l._submission <= :dat AND l._submission >= :dat2 AND l.lsn_id IN ( SELECT id FROM lessonnotes WHERE tea_id = :tea AND term_id = :term ) ) ",
            
            [ "pup" => $enrol, "tea" => $teaid, "dat" => $d, "dat2" => $d2, "typ" => $type , "appr" => "1970-10-10 00:00:00",  "term" => $term ]);
        }

        if ($typeofuser === "principal" ){
          $resultsclw = DB::select(" SELECT IFNULL(AVG(s.perf),0) as perf FROM scores s 
          WHERE s.enrol_id = :pup 
          AND s.exam_id IN 
          ( SELECT EXAM_ID FROM exams e JOIN lessonnotes l ON l.LSN_ID = e.LSN_ID WHERE e._TYPE = :typ AND l._APPROVAL != :appr AND l._SUBMISSION <= :dat AND l._SUBMISSION >= :dat2 AND l.school_sch_id = :sch )  ",
          [ "pup" => $v, "sch" => session('general.school_id'), "dat" => $d, "dat2" => $d2, "typ" => $type , "appr" => "1970-10-10 00:00:00" ]);
             
         }
        
         //
          $results = DB::select(" SELECT 
          ( SELECT CONCAT(fname,' ',lname) FROM pupils WHERE id = :pupid2 ) AS stuname, 
          ( SELECT class_id FROM enrollments WHERE id = :pupid3 ) AS clsid FROM rowcalls 
          WHERE _status = 1 AND pup_id = :pupid 
          AND att_id IN ( SELECT id FROM attendances WHERE term = :term )
          " , [ "pupid" => $pup, "pupid2" =>$pup, "pupid3" => $enrol, "term" => $term ] ); 
          
            $clsid = null;//class id
            $nameofstu = "";//name of student
            $perf = 0;
            
              if ($results != null){    
            foreach ($results as $r){ $nameofstu = $r->stuname; $clsid = $r->clsid; } 
            }
            else { $nameofstu = $this->getStudentName($pup); $clsid = $this->getStudentClassID($pup,$term);  }
             
             if ($resultsclw != null){   
            foreach ($resultsclw as $r){ $perf = $r->perf; }
             }
              else { $perf = 0; $nameofstu = $this->getStudentName($pup); $clsid = $this->getStudentClassID($pup,$term);  }
              
             $clsname = $this->getClassName($clsid);
            
              $mymsg = array(
                '_perf'=> $perf,
                '_name' => $nameofstu,
                '_class' => $clsname,
                '_datenow' => date('Y-m-d H:i:s')
               
            );
            return $mymsg;
     }
     
    private function getTypeAttendanceS($teaid, $pup, $d, $d2, $term, $typeofuser){
         
         $sub = array();  
         $subnames = array();
         $resultarray = array();
         $tea =  $teaid; 
        
          if ( $typeofuser === "teacher" ){
          //1st get subject of student by teacher attendance
         // $resultsubject = DB::select(" SELECT DISTINCT a.sub_id as subid, a.class_id as clsid FROM subjectclasses a JOIN enrollments p ON a.class_id = p.class_id WHERE a.tea_id = :tea  AND p.term_id = :term" ,[ "tea" => 10,  "term" => 1 ]);
          $resultsubject = DB::select(" SELECT DISTINCT a.sub_id as subid FROM subjectclasses a JOIN enrollments p ON a.class_id = p.class_id WHERE a.tea_id = :tea AND p.pupil_id = :pup  AND p.term_id = :term" ,[ "tea" => $tea, "pup" => $pup , "term" => $term ]);
          }
        /* else if (Auth::user()->_type === 1){
           $resultsubject = DB::select(" SELECT DISTINCT a.sub_id as subid FROM subjectclasses a JOIN enrollments p ON a.class_id = p.class_id WHERE p.pupil_id = :pup ",[ "pup" => $pup ]);
             
          }
         else if (Auth::user()->_type === 2){
           $resultsubject = DB::select(" SELECT DISTINCT a.sub_id as subid FROM subjectclasses a JOIN enrollments p ON a.class_id = p.class_id WHERE p.pupil_id = :pup  ",[ "pup" => $pup ]);
             
          }
          else if (Auth::user()->_type === 3){
           $resultsubject = DB::select(" SELECT DISTINCT a.sub_id as subid FROM subjectclasses a JOIN enrollments p ON a.class_id = p.class_id WHERE p.pupil_id = :pup ",[ "pup" => $pup ]);
             
          }*/

          foreach ($resultsubject as $r){ 
              $subn = $r->subid;
              $sub[] = $subn;
              $subnames[$subn] = $this->getSubjectName($subn);
          }  
          
        //load perfomance of student in Attendance per subject offered......
         
        foreach ($sub as $s) {
         
          if ( $typeofuser === "teacher"  ){              
              //no. of times present 
                $results = DB::select(" SELECT IFNULL(COUNT(r.att_id),0) AS present FROM rowcalls r 
                JOIN attendances a 
                ON a.id = r.att_id
                WHERE r._status = 1 AND r.pup_id = :pupid 
                AND a.sub_class_id IN ( SELECT id FROM subjectclasses WHERE tea_id = :tea AND sub_id = :sub ) 
                AND a.term = :term AND a._date <= :dat AND a._date >= :dat2 " ,
                [ "dat" => $d , "dat2" => $d2, "pupid" => $pup, "tea" => $tea, "term" => $term, "sub" => $s ] ); 

              /*  SELECT IFNULL(COUNT(r.att_id),0) AS present FROM rowcalls r JOIN attendances a ON a.id = r.att_id 
                WHERE r._status = 1 AND r.pup_id = 5 
                AND a.sub_class_id IN ( SELECT id FROM subjectclasses WHERE tea_id = 10 AND sub_id = 7 ) 
                AND a.term = 1 AND a._date <= "2020-11-26" AND a._date >= "2020-11-20"*/
                
                //total no. of times attendance was taken 
              $results2 = DB::select(" SELECT IFNULL(COUNT(r.att_id),0) AS total FROM attendances a 
              JOIN rowcalls r 
              ON r.att_id = a.id 
              WHERE a._date <= :dat AND a._date >= :dat2 AND a.sub_class_id IN ( SELECT id FROM subjectclasses WHERE tea_id = :tea AND sub_id = :sub ) 
              AND r.pup_id = :pupid AND a.term = :term " , [ "tea" => $tea, "dat" => $d, "dat2" => $d2, "pupid" => $pup, "term" => $term, "sub" => $s ] ); 
          }  

           if ( $typeofuser === "principal" ){
          //no. of times present 
              $results = DB::select(" SELECT IFNULL(COUNT(ATT_ID),0) AS present FROM rowcall WHERE _STATUS = 1 AND PUPIL_ID = :pupid AND ATT_ID IN ( SELECT ATT_ID FROM attendance WHERE _datetime <= :dat AND _datetime >= :dat2 AND school_sch_id = :sch AND _desc LIKE :des AND sub_id = :sub ) " , [ "dat" => $d , "dat2" => $d2, "pupid" => $v, "sch" => session('general.school_id'), "des" => '%'.$t.'%', "sub" => $s ] ); 
              
              //total no. of times attendance was taken 
              $results2 = DB::select(" SELECT IFNULL(COUNT(a.ATT_ID),0) AS total FROM attendance a JOIN rowcall r ON r.ATT_ID = a.ATT_ID WHERE a._datetime <= :dat AND a._datetime >= :dat2 AND a.school_sch_id = :sch AND r.PUPIL_ID = :pupid AND a._desc LIKE :des AND a.SUB_ID = :sub " , [ "sch" => session('general.school_id'), "dat" => $d, "dat2" => $d2, "pupid" => $v, "des" => '%'.$t.'%', "sub" => $s ] ); 
          }  
            
            $present = 0;//no. of times present
            $nameofstu = "";//name of student
            $total = 0;//total times attendance taken
            $clsid = null;
            $perf = 0;
                
           /* foreach ($results as $r){ $present = $r->present; } 
            foreach ($results2 as $r){ $total = $r->total; }*/
            
             if ($results != null){    
            foreach ($results as $r){ $present = $r->present; } 
            }
           
             if ($results2 != null){   
            foreach ($results2 as $r){ $total = $r->total; }
             }
          
            if ($present != 0 && $total != 0){
            $perf = intval($present)/intval($total) * 100;
                
            }
            
            $resultarray[$s] =  $perf;
             
        }
            
              $mymsg = array(
                '_subid' => $sub,
                '_arrayperf'=> $resultarray,
                '_subjects' => $subnames,
                '_datenow' => date('Y-m-d H:i:s')
              );
            
         return $mymsg;
    }     
    private function getTypeAssessmentS($type, $teaid, $enrol, $pup,  $d, $d2, $term, $typeofuser){
         
         $sub = array();  
         $subnames = array();
         $resultarray = array();
         $tea =  $teaid; 
         
         $nameofstu = "";//name of student
         $clsid = null;
        if ( $typeofuser === "teacher" ){
          //1st get subject of student by teacher attendance
          $resultsubject = DB::select(" SELECT DISTINCT a.sub_id as subid FROM subjectclasses a JOIN enrollments p ON a.class_id = p.class_id WHERE a.tea_id = :tea AND p.pupil_id = :pup AND p.term_id = :term ",[ "tea" => $tea, "pup" => $pup, "term" => $term ]);
        }
        
        if (session('head.head_id') || session('supervisor.sup_id') || session('ministry.min_id') ){
         $resultsubject = DB::select(" SELECT DISTINCT a.SUB_ID as subid FROM attendances a JOIN pupil p ON a.CLASS_ID = p.CLASS_ID WHERE p.PUP_ID = :pup ",[ "pup" => $v ]);             
        }
          foreach ($resultsubject as $r){ 
              $subn = $r->subid;
              $sub[] = $subn;
              $subnames[$subn] = $this->getSubjectName($subn);
          }  
          
          $results = DB::select(" SELECT ( SELECT CONCAT(fname,' ',lname) FROM pupils WHERE id = :pupid2 ) AS stuname, 
          ( SELECT class_id FROM enrollments WHERE id = :pupid3 ) AS clsid FROM rowcalls
           WHERE _status = 1 AND pup_id = :pupid 
           AND att_id IN ( SELECT id FROM attendances WHERE term = :term )
           " , [ "pupid" => $pup, "pupid2" => $pup, "pupid3" => $enrol ,  "term" => $term ] ); 
         
          foreach ($results as $r){ $nameofstu = $r->stuname; $clsid = $r->clsid; } 
          $i = 0;
          $clsname = $this->getClassName($clsid);
          
         //load perfomance of student per subject offered......
        foreach ($sub as $s) {

              if ( $typeofuser === "teacher" ){
                  $resultarray[$s] =  DB::select(" SELECT IFNULL(AVG(s.perf),0) as perf FROM scores s WHERE s.enrol_id = :pup
                      AND s.ass_id 
                      IN ( SELECT e.id FROM assessments e JOIN lessonnote_managements l 
                      ON l.lsn_id = e.lsn_id 
                      WHERE e._type = :typ AND l._approval != :appr AND l._submission <= :dat AND l._submission >= :dat2 
                      AND l.lsn_id IN ( SELECT id FROM lessonnotes WHERE sub_id = :sub AND tea_id = :tea AND term_id = :term ) ) ",

                  [ "pup" => $enrol, "tea" => $tea, "dat" => $d, "dat2" => $d2, "typ" => $type, "appr" => "1970-10-10 00:00:00" , "sub" => $s, "term" => $term ]);
              }
              
               if (session('head.head_id') || session('supervisor.sup_id') || session('ministry.min_id') ){
                   $resultarray[$s] =  DB::select(" SELECT IFNULL(AVG(s._PERFORMANCE),0) as perf FROM scores s WHERE s.pupil_id = :pup AND s.exam_id IN ( SELECT EXAM_ID FROM exam e JOIN lessonnotes l ON l.LSN_ID = e.LSN_ID WHERE e._TYPE = :typ AND l._APPROVAL != :appr AND l._SUBMISSION <= :dat AND l._SUBMISSION >= :dat2 AND l.SUB_ID = :sub AND l.school_sch_id = :sch )  ",
            [ "pup" => $pup, "sch" => session('general.school_id'), "dat" => $d, "dat2" => $d2, "typ" => $type, "appr" => "1970-10-10 00:00:00" , "sub" => $s ]);
            
               }
             
        }
            //AVG(s._PERFORMANCE) as perf
              $mymsg = array(
                '_subid' => $sub,
                '_arrayperf'=> $resultarray,
                '_subjects' => $subnames,
                '_name' => $nameofstu,
                '_class' => $clsname,
                '_datenow' => date('Y-m-d H:i:s')
              );
            
         return $mymsg;
      }



      ////TEACHER MNE -----
      public function loadteachermne_teacher_gen(Request $request){

        $typeofuser = $request->input('_type');
        
        $dateofreq2 = $request->input('sdate'); //start date
        
        $dateofreq = $request->input('edate'); //end date
        
        $dateofreq = date('Y-m-d', strtotime('+1 days', strtotime($dateofreq)));//add 1 day to original date to give accuracy
         
        $term = $request->input('term'); //Term of the school

        $valofreq =  $request->input('tea'); //teacher ID
         
        $term = explode(';', $request->input('term') ); //Term ID of the school
        $termval = array( 1 => "1ST TERM" , 2 => "2ND TERM", 3 => "3RD TERM");
        
        $termid = intval( $term[0] );  $termname = intval( $term[1] );
         
        
          ////////////////////////////////////////////ATTENDANCE
          
          // $results = DB::select(" SELECT COUNT(st.ATT_ID) as present, ( SELECT CONCAT(_FNAME,' ',_LNAME) FROM teacher WHERE tea_id = :id2 ) AS teaname FROM attendance st WHERE st.tea_id = :id AND st._DESC LIKE :des AND st._DONE = 1 AND st._DATETIME >= :dat AND st._DATETIME <= :dat2 ", [ "id" => $valofreq , "id2" => $valofreq, "des" => '%'.$term.'%' , "dat" => $dateofreq2, "dat2" => $dateofreq ]);
          
         //  $results2 = DB::select(" SELECT COUNT(st.ATT_ID) as total FROM attendance st WHERE st.tea_id = :id AND st._desc LIKE :dat AND st._datetime >= :d AND st._datetime <= :d2 ", [ "id" => $valofreq, "dat" => '%'.$term.'%',"d" => $dateofreq2, "d2" => $dateofreq  ]);
         
             //no. of times present 
         $results = DB::select(" SELECT IFNULL(COUNT(a.id),0) AS present, 
         ( SELECT CONCAT(fname,' ',lname) FROM teachers WHERE id = :teaid ) AS teaname       
         FROM attendances a       
         WHERE a._date <= :dat AND a._date >= :dat2 AND a.term = :term
         AND  a.sub_class_id IN ( SELECT id FROM subjectclasses WHERE tea_id = :teaid2 ) AND a._desc LIKE :desf AND a._done = 1" ,
         [ "dat" => $dateofreq , "dat2" => $dateofreq2, "teaid" => $valofreq , "teaid2" => $valofreq , "desf" => '%'.$termval[$termname].'%', "term" => $termid  ] ); 
         
         //total no. of times attendance was taken 
         $results2 = DB::select(" SELECT IFNULL(COUNT(a.id),0) AS total
         FROM attendances a  
         WHERE a._date <= :dat 
         AND a._date >= :dat2 
         AND a.sub_class_id IN ( SELECT id FROM subjectclasses WHERE tea_id = :tea ) AND a._desc LIKE :des " ,
        [ "dat" => $dateofreq, "dat2" => $dateofreq2, "tea" => $valofreq ,  "des" =>  '%'.$termval[$termname].'%' ] );            

           $present = 0;//no. of times present
           $nameoftea = "";//name of student
           $total = 0;//total times attendance taken
          
           $perf = 0;
         
               
           foreach ($results as $r){ $present = $r->present; $nameoftea = $r->teaname; } 
           foreach ($results2 as $r){ $total = $r->total; }
            if ($present != 0 && $total != 0){
                   $perf = intval($present)/intval($total) * 100;
           }
           
           $theattend = array(
               '_perf'=> $perf,
               '_name' => $nameoftea,
               '_class' => "Nil",
               '_datenow' => date('Y-m-d H:i:s')
              
           );
          
          $theattendsub = $this->TgetTypeAttendanceS(  $valofreq, $dateofreq,$dateofreq2, $termid, $typeofuser );
          ///////////////////////////////////////////END ATTENDANCE
          
          //////////////////////////////////////////EXAMINATION
           $theclw = $this->TgetTypeAssessment('CW',$valofreq,$dateofreq,$dateofreq2,$termid);
           $thetest = $this->TgetTypeAssessment('TS',$valofreq,$dateofreq,$dateofreq2,$termid);
           $theassign = $this->TgetTypeAssessment('AS',$valofreq,$dateofreq,$dateofreq2,$termid);
           
           //$lsnaverage = intval(($theclw->_perf + $thetest->_perf + $theassign->_perf)/3);
           
           $themidterm = $this->TgetTypeAssessment('MT',$valofreq,$dateofreq,$dateofreq2,$termid);
           $theterminal = $this->TgetTypeAssessment('TE',$valofreq,$dateofreq,$dateofreq2,$termid);
           //////////////////////////////////////////END EXAMINATION
           
           /////////////////////////////////////////EXAMINATION BY SUBJECT
           $theclwsub = $this->TgetTypeAssessmentS('CW',$valofreq,$dateofreq,$dateofreq2,$termid, 'teacher');
           $thetestsub = $this->TgetTypeAssessmentS('TS',$valofreq,$dateofreq,$dateofreq2,$termid, 'teacher');
           $theassignsub = $this->TgetTypeAssessmentS('AS',$valofreq,$dateofreq,$dateofreq2,$termid, 'teacher');
           $themidtermsub = $this->TgetTypeAssessmentS('MT',$valofreq,$dateofreq,$dateofreq2,$termid, 'teacher');
           $theterminalsub = $this->TgetTypeAssessmentS('TE',$valofreq,$dateofreq,$dateofreq2,$termid, 'teacher');
           ////////////////////////////////////////
           
           /////////////////////////////////////////QUALITY OF LESSONOTE
           $resultsquality = DB::select(" SELECT IFNULL(AVG(perf),0) AS myperf FROM lsn_performances WHERE lsn_id IN (SELECT lsn_id FROM lessonnote_managements WHERE _submission <= :dat AND _submission >= :dat2  AND lsn_id IN ( SELECT id FROM lessonnotes WHERE tea_id = :teaid )  ) " , [ "teaid" => $valofreq, "dat" => $dateofreq, "dat2" => $dateofreq2, ] ); 
           foreach ($resultsquality as $r){ $lsnquality = $r->myperf; }
           ////////////////////////////////////////QUALITY OF LESSONOTE BY SUBJECT
           $thelsnquabysub = $this->TgetTypeLsnManagementS($valofreq,$dateofreq,$dateofreq2,$termid,'teacher');
           
           
           /////////////////////////////////////////QUALITY OF ATTENDANCE
            $resultsquality2 = DB::select("SELECT IFNULL(AVG(perf),0) AS myperf FROM att_performances WHERE att_id IN (SELECT id FROM attendances WHERE _date <= :dat AND _date >= :dat2 AND sub_class_id IN ( SELECT id FROM subjectclasses WHERE tea_id = :teaid )  ) " , [ "teaid" => $valofreq, "dat" => $dateofreq, "dat2" => $dateofreq2 ] ); 
           foreach ($resultsquality2 as $r){ $attquality = $r->myperf; }
           ////////////////////////////////////////QUALITY OF ATTENDANCE BY SUBJECT
           $theattquabysub = $this->TgetTypeAttManagementS($valofreq,$dateofreq,$dateofreq2,$termid,'teacher'); 
           
           /*
            '_att3' => $thetest,
               '_att4' => $theassign,
               '_att5' => $themidterm,
               '_att6' => $theterminal,
               '_att7' => $lsnaverage,
               '_att8' => $lsnquality,
               '_att9' => $attquality,
               '_att10' => $theclwsub,
               '_att11' => $thetestsub,
               '_att12' => $theassignsub,
               '_att13' => $themidtermsub,
               '_att14' => $theterminalsub,
               '_att15' => $thelsnquabysub,
               '_att16' => $theattquabysub
           
           */
           $mymsg = array(
               '_att1'=> $theattend,
               '_att1b' => $theattendsub, 
               '_att2' => $theclw,
               '_att3' => $thetest,
               '_att4' => $theassign,
               '_att5' => $themidterm,
               '_att6' => $theterminal,
               '_att8' => $lsnquality,
               '_att9' => $attquality,
               '_att10' => $theclwsub,
               '_att11' => $thetestsub,
               '_att12' => $theassignsub,
               '_att13' => $themidtermsub,
               '_att14' => $theterminalsub,
               '_att15' => $thelsnquabysub,
               '_att16' => $theattquabysub
              
               
           );
           
            return response()->json($mymsg);
        
    }

private function TgetTypeAttendanceS($tea, $d, $d2, $term, $type){
      //teacher,enddate,startdate,term
      $sub = array();  
      $subnames = array();
      $resultarray = array();
      
      //1st get subject of student by teacher attendance
       /*$resultsubject = DB::select(" SELECT DISTINCT a.sub_id as subid, a.CLASS_ID as clsid 
       FROM attendance a JOIN pupil p ON a.CLASS_ID = p.CLASS_ID WHERE a.TEA_ID = :tea ",[ "tea" => $tea ]);*/
       
       $resultsubject = DB::select(" SELECT DISTINCT a.sub_id as subid, a.class_id as clsid FROM subjectclasses a JOIN enrollments p ON a.class_id = p.class_id WHERE a.tea_id = :tea  AND p.term_id = :term" ,[ "tea" => $tea,  "term" => $term ]);
       
        $k = 0;
       foreach ($resultsubject as $r){ 
           $subn = $r->subid; $clsid = $r->clsid;
           $sub[$k][$subn] = $clsid;
           $subnames[$k][$subn] = $this->getSubjectName($subn);
           $k++;
       }  
       
     //load perfomance of student in Attendance per subject offered......
    
         $i = 0;
         foreach ($sub as $s1) {
          
            foreach ($s1 as $s2 => $vd){
      
      /* $results = DB::select(" SELECT IFNULL(COUNT(st.ATT_ID),0) as present FROM attendance st
        WHERE st.tea_id = :id AND st._desc LIKE :des AND st._done = 1 
        AND st._datetime >= :dat AND st._datetime <= :dat2
        AND st.sub_id = :sub AND st.class_id = :cls ", [ "id" => $v , "des" => '%'.$t.'%' , "dat" => $d2, "dat2" => $d , "sub" => $s2 , "cls" => $vd ]);
        
       $results2 = DB::select("SELECT IFNULL(COUNT(st.ATT_ID),0) as total FROM attendance st WHERE st.tea_id = :id AND st._desc LIKE :dat AND st._datetime >= :d AND st._datetime <= :d2 AND st.sub_id = :sub AND st.class_id = :cls ", [ "id" => $v, "dat" => '%'.$t.'%',"d" => $d2, "d2" => $d , "sub" => $s2 , "cls" => $vd  ]);
       */

       //no. of times present 
       $results = DB::select(" SELECT IFNULL( COUNT(a.id), 0) AS present FROM attendances a 
       WHERE a.sub_class_id IN ( SELECT id FROM subjectclasses WHERE tea_id = :tea AND sub_id = :sub ) 
       AND a.term = :term AND a._date <= :dat AND a._date >= :dat2 AND a._done = 1" ,
       [ "dat" => $d , "dat2" => $d2, "tea" => $tea, "term" => $term, "sub" => $s2 ] ); 

     /*  SELECT IFNULL(COUNT(r.att_id),0) AS present FROM rowcalls r JOIN attendances a ON a.id = r.att_id 
       WHERE r._status = 1 AND r.pup_id = 5 
       AND a.sub_class_id IN ( SELECT id FROM subjectclasses WHERE tea_id = 10 AND sub_id = 7 ) 
       AND a.term = 1 AND a._date <= "2020-11-26" AND a._date >= "2020-11-20"*/
       
       //total no. of times attendance was taken 
        $results2 = DB::select(" SELECT IFNULL(COUNT(a.id),0) AS total FROM attendances a 
        WHERE a._date <= :dat AND a._date >= :dat2 AND a.sub_class_id IN ( SELECT id FROM subjectclasses WHERE tea_id = :tea AND sub_id = :sub ) 
        AND a.term = :term " , [ "tea" => $tea, "dat" => $d, "dat2" => $d2, "term" => $term, "sub" => $s2 ] ); 

         $present = 0;//no. of times present
         $total = 0;//total times attendance taken
         $perf = 0;
             
         foreach ($results as $r){ $present = $r->present; } 
         foreach ($results2 as $r){ $total = $r->total; }
         
         if ($present != 0 && $total != 0){
        
             $perf = (intval($present)/intval($total)) * 100;
             
         }         
              $resultarray[$i][$s2] =  $perf; 
            }
         $i++;
         }
         
           $mymsg = array(
             '_subid' => $sub,
             '_arrayperf'=> $resultarray,
             '_subjects' => $subnames,
             '_datenow' => date('Y-m-d H:i:s')
           );
         
      return $mymsg;
 } 


 ///y Subjects of Assessments

 private function TgetTypeAssessmentS( $type , $tea, $d, $d2, $term , $usertype){
      
    $sub = array();  
    $subnames = array();
    $resultarray = array();    
   
    $resultsubject = DB::select(" SELECT DISTINCT a.sub_id as subid, a.class_id as clsid FROM subjectclasses a 
    JOIN enrollments p ON a.class_id = p.class_id WHERE a.tea_id = :tea AND p.term_id = :term" ,[ "tea" => $tea,  "term" => $term ]);
      
     //1st get subject of student by teacher attendance
    /* $resultsubject = DB::select(" SELECT DISTINCT a.SUB_ID as subid, a.CLASS_ID as clsid FROM attendance a 
     JOIN pupil p ON a.CLASS_ID = p.CLASS_ID WHERE a.TEA_ID = :tea ",[ "tea" => $v ]);*/
     $count = 0;
     $k = 0;
     
     foreach ($resultsubject as $r){ 
         $subn = $r->subid; $clsid = $r->clsid;
         
         $sub[$k][$subn] = $this->getClassName($clsid);
         $subnames[$k][$subn] = $this->getSubjectName($subn);
         
         $count++;$k++;
     } 

     $i = 0;
     $j = 0;

 
     $sch = $this->getschoolId( $tea );
     
      foreach ($sub as $s1) {
        
          foreach ($s1 as $s2 => $vd){
       
            $cls = $this->getClassID($vd,$sch);

            $category = $this->getClassCategory( $cls );
       
           /* $resultarray[$j][$s2] = DB::select(" SELECT IFNULL(AVG(s.perf),0) as perf FROM scores s 
            WHERE s.ass_id 
            IN ( SELECT EXAM_ID FROM exam e JOIN lessonnote l ON l.LSN_ID = e.LSN_ID WHERE e._TYPE = :typ 
            AND l._APPROVAL != :appr AND l._SUBMISSION <= :dat AND l._SUBMISSION >= :dat2 AND l.SUB_ID = :sub AND l.TEA_ID = :tea AND l.CLASS_ID = :cls )  ",
            [  "tea" => $v, "dat" => $d, "dat2" => $d2, "typ" => $type, "appr" => "1970-10-10 00:00:00" , "sub" => $s2 , "cls" => $cls]); */
            
            $resultarray[$j][$s2] = DB::select(" SELECT IFNULL(AVG(s.perf),0) as perf FROM scores s 
            WHERE s.ass_id IN ( SELECT e.id FROM assessments e JOIN lessonnote_managements l 
            ON l.lsn_id = e.lsn_id 
            WHERE e._type = :typ AND l._approval != :appr AND l._submission <= :dat 
            AND l._submission >= :dat2 AND l.lsn_id IN ( SELECT id FROM lessonnotes WHERE tea_id = :tea AND sub_id = :sub AND term_id = :term AND class_category = :cat ) ) ",
            
            [ "tea" => $tea, "dat" => $d, "dat2" => $d2, "typ" => $type , "appr" => "1970-10-10 00:00:00", "sub" => $s2 , "term" => $term, "cat" => $category ]);

            }
       $j++;
     }
       
       $results = DB::select(" SELECT CONCAT(fname,' ',lname) as teaname FROM teachers WHERE id = :teaid " , [ "teaid" => $tea ] ); 
     
       $nameofstu = "";//name of teacher
       
       foreach ($results as $r){ $nameofstu = $r->teaname; } 
       
        $mymsg = array(
           '_subid' => $sub,
           '_count' => $count,
           '_arrayperf'=> $resultarray,
           '_subjects' => $subnames,
           '_name' => $nameofstu,
           '_datenow' => date('Y-m-d H:i:s')
         );
       
        return $mymsg;
} 
///By Subjects of Lessonnote
private function TgetTypeLsnManagementS($tea, $d, $d2, $term, $type){
 
    $sub = array();  
    $subnames = array();
    $resultarray = array();
   
     //1st get subject of student by teacher attendance
   //  $resultsubject = DB::select(" SELECT DISTINCT a.SUB_ID as subid, a.CLASS_ID as clsid FROM attendance a JOIN pupil p ON a.CLASS_ID = p.CLASS_ID WHERE a.TEA_ID = :tea ",[ "tea" => $v ]);
      
     $resultsubject = DB::select(" SELECT DISTINCT a.sub_id as subid, a.class_id as clsid FROM subjectclasses a 
     JOIN enrollments p ON a.class_id = p.class_id WHERE a.tea_id = :tea AND p.term_id = :term" ,[ "tea" => $tea,  "term" => $term ]);
   
     $k = 0;
     foreach ($resultsubject as $r){ 
         $subn = $r->subid; $clsid = $r->clsid;
         $sub[$k][$subn] = $clsid;
         $subnames[$k][$subn] = $this->getSubjectName($subn);
         $k++;
     }  
     $i = 0;

       $sch = $this->getschoolId( $tea );

       foreach ($sub as $s1) {
        
          foreach ($s1 as $s2 => $vd){    

            $cls = $this->getClassID($vd,$sch);

            $category = $this->getClassCategory( $cls );

              $resultarray[$i][$s2] =  DB::select(" SELECT IFNULL(AVG(perf),0) AS myperf FROM lsn_performances
              WHERE lsn_id IN (SELECT lsn_id FROM lessonnote_managements WHERE _submission <= :dat 
              AND _submission >= :dat2 AND lsn_id IN 
              ( SELECT id FROM lessonnotes WHERE tea_id = :tea AND sub_id = :sub AND term_id = :term AND class_category = :cat ) ) " , 
              [ "tea" => $tea, "dat" => $d, "dat2" => $d2, "sub" => $s2,  "term" => $term, "cat" => $category ] ); 

          }
       $i++;    
     }
       
       $results = DB::select(" SELECT CONCAT(fname,' ',lname) as teaname FROM teachers WHERE id = :teaid " , [ "teaid" => $tea ] ); 
     
       $nameofstu = "";//name of teacher
       
       foreach ($results as $r){ $nameofstu = $r->teaname; } 
       
        $mymsg = array(
           '_subid' => $sub,
           '_arrayperf'=> $resultarray,
           '_subjects' => $subnames,
           '_name' => $nameofstu,
           '_datenow' => date('Y-m-d H:i:s')
         );
       
        return $mymsg;
} 
///By Subjects of Attendance
private function TgetTypeAttManagementS($tea, $d, $d2, $term, $type){
 
    $sub = array();  
    $subnames = array();
    $resultarray = array();
   
     //1st get subject of student by teacher attendance
   /*  $resultsubject = DB::select(" SELECT DISTINCT a.SUB_ID as subid, a.CLASS_ID as clsid FROM attendance a 
     JOIN pupil p ON a.CLASS_ID = p.CLASS_ID WHERE a.TEA_ID = :tea ",[ "tea" => $v ]); */
      
     $resultsubject = DB::select(" SELECT DISTINCT a.sub_id as subid, a.class_id as clsid FROM subjectclasses a 
     JOIN enrollments p ON a.class_id = p.class_id WHERE a.tea_id = :tea AND p.term_id = :term" ,[ "tea" => $tea,  "term" => $term ]);
   
     $k = 0;
     foreach ($resultsubject as $r){ 
         $subn = $r->subid; $clsid = $r->clsid;
         $sub[$k][$subn] = $clsid;
         $subnames[$k][$subn] = $this->getSubjectName($subn);
         $k++;
     }  
     
       $i = 0;

       $sch = $this->getschoolId( $tea );

       foreach ($sub as $s1) {
        
          foreach ($s1 as $s2 => $vd){
       
            /*  $resultarray[$i][$s2] =  DB::select("SELECT IFNULL(AVG(_perf),0) AS myperf FROM master_att_head 
              WHERE ATT_ID IN 
              (SELECT ATT_ID FROM attendance WHERE _datetime <= :dat AND _datetime >= :dat2 AND tea_id = :teaid AND sub_id = :sub AND class_id = :cls ) " , [ "teaid" => $v, "dat" => $d, "dat2" => $d2, "sub" => $s2, "cls" => $vd ] ); 
            */
              $cls = $this->getClassID($vd,$sch);

              $category = $this->getClassCategory( $cls );
  
                $resultarray[$i][$s2] =  DB::select(" SELECT IFNULL(AVG(perf),0) AS myperf FROM att_performances
                WHERE att_id IN (SELECT id FROM attendances WHERE _date <= :dat AND _date >= :dat2 AND term = :term 
                AND sub_class_id IN 
                ( SELECT id FROM subjectclasses WHERE tea_id = :tea AND sub_id = :sub ) ) " , 
                [ "tea" => $tea, "dat" => $d, "dat2" => $d2, "sub" => $s2,  "term" => $term ] ); 
           } 
         $i++;        
       }
       
       $results = DB::select(" SELECT CONCAT(fname,' ',lname) as teaname FROM teachers WHERE id = :teaid " , [ "teaid" => $tea ] ); 
     
       $nameofstu = "";//name of teacher
       
       foreach ($results as $r){ $nameofstu = $r->teaname; } 
       
        $mymsg = array(
           '_subid' => $sub,
           '_arrayperf'=> $resultarray,
           '_subjects' => $subnames,
           '_name' => $nameofstu,
           '_datenow' => date('Y-m-d H:i:s')
         );
       
        return $mymsg;
} 
///Of Assessments $type, $v, $d, $d2
private function TgetTypeAssessment(  $type , $tea, $d, $d2, $term){
   
  /* $resultsassign = DB::select(" SELECT IFNULL(AVG(s.perf),0) as perf FROM score s WHERE s.exam_id IN ( SELECT EXAM_ID FROM exam e JOIN lessonnote l ON l.LSN_ID = e.LSN_ID WHERE e._TYPE = :typ AND l._APPROVAL != :appr AND l._SUBMISSION <= :dat AND l._SUBMISSION >= :dat2 AND l.TEA_ID = :tea )",
   [ "tea" => $v, "dat" => $d ,"dat2" => $d2 , "typ" => $type , "appr" => "1970-10-10 00:00:00" ]);  */ 

   $resultsassign = DB::select(" SELECT IFNULL(AVG(s.perf),0) as perf FROM scores s 
   WHERE s.ass_id IN ( SELECT e.id FROM assessments e JOIN lessonnote_managements l 
   ON l.lsn_id = e.lsn_id 
   WHERE e._type = :typ AND l._approval != :appr AND l._submission <= :dat 
   AND l._submission >= :dat2 AND l.lsn_id IN ( SELECT id FROM lessonnotes WHERE tea_id = :tea  AND term_id = :term  ) ) ",
   
   [ "tea" => $tea, "dat" => $d, "dat2" => $d2, "typ" => $type , "appr" => "1970-10-10 00:00:00", "term" => $term ]);

   foreach ($resultsassign as $r){ $perf = $r->perf; }
    
   $results = DB::select(" SELECT CONCAT(fname,' ',lname) as teaname FROM teachers WHERE id = :teaid " , [ "teaid" => $tea ] ); 
     
       $nameofstu = "";//name of student
       $perf = 0;
       
       foreach ($results as $r){ $nameofstu = $r->teaname; } 
       foreach ($resultsassign as $r){ $perf = $r->perf; } 
       
        $mymsg = array(
           '_perf' => $perf,
           '_name' => $nameofstu,
           '_class' =>"Nil",
           '_datenow' => date('Y-m-d H:i:s')
          
       );
       return $mymsg;
}

/////////////////////////////CLASS

public function loadteachermne_class_gen(Request $request){
        
  $dateofreq2 = $request->input('sdate'); //start date
  
  $dateofreq = $request->input('edate'); //end date
  
  $dateofreq = date('Y-m-d', strtotime('+1 days', strtotime($dateofreq)));//add 1 day to original date to give accuracy
  
  $valofreq =  $request->input('cla'); //class ID

  $tea =  $request->input('tea');

  $type =  $request->input('_type');

  //$term = $request->input('term');

  /////////////////////////
   
  $term = explode(';', $request->input('term') ); //Term ID of the school
  $termval = array( 1 => "1ST TERM" , 2 => "2ND TERM", 3 => "3RD TERM");
  
  $termid = intval( isset($term[0]) ? $term[0] : '' );  //$termname = intval( isset($term[1]) ? $term[1] : '' );
  
    ////////////////////////////////////////////ATTENDANCE
    
    // $results = DB::select("SELECT COUNT(ATT_ID) AS present, ( SELECT _title FROM CLASS_ WHERE cls_id = :cls2 ) AS clsname FROM rowcall WHERE _STATUS = 1 AND ATT_ID IN ( SELECT ATT_ID FROM attendance WHERE _datetime <= :dat AND _datetime >= :dat2 AND class_id = :cls AND tea_id = :tea ) " , [ "tea" => session('teacher.teacher_id'), "dat" => $dateofreq , "dat2" => $dateofreq2, "cls" => $valofreq , "cls2" => $valofreq ] ); 
      //total no. of times attendance was taken 
    // $results2 = DB::select(" SELECT COUNT(ATT_ID) AS total, ( SELECT COUNT(pup_id) FROM pupil WHERE class_id = :cls2 ) AS totalpupil FROM attendance WHERE _datetime <= :dat AND _datetime >= :dat2 AND class_id = :cls AND tea_id = :tea " , [ "tea" => session('teacher.teacher_id') , "cls" => $valofreq,"cls2" => $valofreq, "dat" => $dateofreq,"dat2" => $dateofreq2 ] ); //get attendance 
   
          //no. of times present 
          $results = DB::select(" SELECT IFNULL(COUNT(r.id),0) AS present, 
          ( SELECT title FROM class_streams WHERE id = :cls2 ) AS clsname       
          FROM rowcalls r      
          WHERE r._status = 1 AND r.att_id IN ( SELECT id FROM attendances WHERE _date <= :dat AND _date >= :dat2 AND term = :term AND  
          sub_class_id IN ( SELECT id FROM subjectclasses WHERE tea_id = :tea AND class_id = :cls ) ) " ,
          [ "dat" => $dateofreq , "dat2" => $dateofreq2, "tea" => $tea , "term" => $termid , "cls" => $valofreq, "cls2" => $valofreq  ] ); 
          
          //total no. of times attendance was taken 
          $results2 = DB::select(" SELECT IFNULL(COUNT(a.id),0) AS total ,
          ( SELECT COUNT(id) FROM enrollments WHERE class_id = :cls2 ) AS totalpupil
          FROM attendances a  
          WHERE a._date <= :dat 
          AND a._date >= :dat2 
          AND a.term = :term
          AND a.sub_class_id IN ( SELECT id FROM subjectclasses WHERE tea_id = :tea AND class_id = :cls ) " ,
         [ "dat" => $dateofreq, "dat2" => $dateofreq2, "tea" => $tea ,   "cls" => $valofreq, "cls2" => $valofreq, "term" => $termid  ] );     

     $present = 0;//no. of times present
     $nameofclass = "";//name of student
     $total = 0;//total times attendance taken
     $totalpupil = 0;
    
     $perf = 0;
         
     foreach ($results as $r){ $present = $r->present; $nameofclass = $r->clsname; } 
     foreach ($results2 as $r){ $total = $r->total; $totalpupil = $r->totalpupil; }
     $totalnew = $totalpupil * $total;
      if ($present != 0 && $total != 0){
     $perf = intval($present)/intval($totalnew) * 100;
          
      }
     
     $theattend = array(
         '_perf'=> $perf,
         '_name' => "Nil",
         '_class' => $nameofclass,
         '_datenow' => date('Y-m-d H:i:s')
        
     );
    
    $theattendsub = $this->CgetTypeAttendanceS( $valofreq, $dateofreq, $dateofreq2, $tea, $termid , $type);
    ///////////////////////////////////////////END ATTENDANCE
    
    //////////////////////////////////////////EXAMINATION
      $theclw = $this->CgetTypeAssessment('CW',$valofreq,$dateofreq,$dateofreq2, $tea, $termid , $type);
      $thetest = $this->CgetTypeAssessment('TS',$valofreq,$dateofreq,$dateofreq2, $tea, $termid , $type);
      $theassign = $this->CgetTypeAssessment('AS',$valofreq,$dateofreq,$dateofreq2, $tea, $termid , $type);
     
     //$lsnaverage = intval(($theclw->_perf + $thetest->_perf + $theassign->_perf)/3);
     
     $themidterm = $this->CgetTypeAssessment('MT',$valofreq,$dateofreq,$dateofreq2, $tea, $termid , $type);
     $theterminal = $this->CgetTypeAssessment('TE',$valofreq,$dateofreq,$dateofreq2, $tea, $termid , $type);
     //////////////////////////////////////////END EXAMINATION
     
     /////////////////////////////////////////EXAMINATION BY SUBJECT
     $theclwsub = $this->CgetTypeAssessmentS('CW',$valofreq,$dateofreq,$dateofreq2, $tea, $termid , $type);
     $thetestsub = $this->CgetTypeAssessmentS('TS',$valofreq,$dateofreq,$dateofreq2, $tea, $termid , $type);
     $theassignsub = $this->CgetTypeAssessmentS('AS',$valofreq,$dateofreq,$dateofreq2, $tea, $termid , $type);
     $themidtermsub = $this->CgetTypeAssessmentS('MT',$valofreq,$dateofreq,$dateofreq2, $tea, $termid , $type);
     $theterminalsub = $this->CgetTypeAssessmentS('TE',$valofreq,$dateofreq,$dateofreq2, $tea, $termid , $type);
     ////////////////////////////////////////
     
 
     $mymsg = array(
         '_att1b' => $theattendsub, 
         '_att1' => $theattend,
         '_att2' => $theclw,
         '_att3' => $thetest,
         '_att4' => $theassign,
         '_att5' => $themidterm,
         '_att6' => $theterminal,
         '_att7' => $theclwsub,
         '_att8' => $thetestsub,
         '_att9' => $theassignsub,
         '_att10' => $themidtermsub,
         '_att11' => $theterminalsub
     );
     
      return response()->json($mymsg);
  
}

/////////////////////////////////////////////////////////////////CLASS FUNCTIONS
    
  private function CgetTypeAssessment($type, $v, $d, $d2,  $tea, $termid , $typeofuser){
         
  //  if ( $type === 'teacher' ){

        $resultsclw = DB::select(" SELECT IFNULL(AVG(s.perf),0) as perf FROM scores s 
        WHERE s.enrol_id IN ( SELECT id FROM enrollments WHERE class_id = :cls ) 
        AND s.ass_id IN ( SELECT a.id FROM assessments a JOIN lessonnote_managements l 
        ON l.lsn_id = a.lsn_id 
        WHERE a._type = :typ AND l._approval != :appr AND l._submission <= :dat 
        AND l._submission >= :dat2 AND l.lsn_id IN ( SELECT id FROM lessonnotes WHERE tea_id = :tea AND term_id = :term  ) ) ",
        
        [ "tea" => $tea, "dat" => $d, "dat2" => $d2, "typ" => $type , "appr" => "1970-10-10 00:00:00", "term" => $termid , "cls" => $v ]);

  //  }
    
    if (session('head.head_id') || session('supervisor.sup_id') || session('ministry.min_id') ){
          /*$resultsclw = DB::select(" SELECT IFNULL(AVG(s._PERFORMANCE),0) as perf FROM score s WHERE s.pupil_id IN (SELECT pup_id FROM pupil WHERE class_id = :cls) AND s.exam_id IN ( SELECT EXAM_ID FROM exam e JOIN lessonnote l ON l.LSN_ID = e.LSN_ID WHERE e._TYPE = :typ AND l.school_sch_id = :sch AND l._APPROVAL != :appr AND l._SUBMISSION <= :dat AND l._SUBMISSION >= :dat2 ) ",
          [ "cls" => $v, "sch" => session('general.school_id'), "dat" => $d, "dat2" => $d2, "typ" => $type , "appr" => "1970-10-10 00:00:00" ]);*/
    }
  
   $results = DB::select(" SELECT title FROM class_streams WHERE id = :cls  " , [ "cls" => $v ] );    
    
     $nameofstu = "";//name of student
     $perf = 0;
     
     foreach ($results as $r){ $nameofstu = $r->title; } 
     foreach ($resultsclw as $r){ $perf = $r->perf; } 
     
       $mymsg = array(
         '_perf'=> $perf,
         '_name' => "Nil",
         '_class' => $nameofstu,
         '_datenow' => date('Y-m-d H:i:s')
        
     );
     return $mymsg;
}

private function CgetTypeAttendanceS($v, $d, $d2, $tea, $termid , $type){
  
  $sub = array();  
  $subnames = array();
  $resultarray = array();
 
 
  //if ( $type === 'teacher' ){
   
   $resultsubject = DB::select(" SELECT DISTINCT a.sub_id as subid, a.class_id as clsid FROM subjectclasses a JOIN enrollments p ON a.class_id = p.class_id WHERE a.tea_id = :tea  AND p.term_id = :term" ,[ "tea" => $tea,  "term" => $termid ]);
         
  //}
    
   foreach ($resultsubject as $r){ 
       $subn = $r->subid;
       $sub[] = $subn;
       $subnames[$subn] = $this->getSubjectName($subn);
   }  
   
 //load perfomance of student in Attendance per subject offered......
  
 foreach ($sub as $s) {
   //  if ( $type === 'teacher' ){
    
   // $results = DB::select("SELECT COUNT(ATT_ID) AS present, ( SELECT _title FROM CLASS_ WHERE cls_id = :cls2 ) AS clsname FROM rowcall WHERE _STATUS = 1 AND ATT_ID IN ( SELECT ATT_ID FROM attendance WHERE _datetime <= :dat AND _datetime >= :dat2 AND class_id = :cls AND _desc LIKE :des AND sub_id = :sub AND tea_id = :tea ) " , [ "tea" => session('teacher.teacher_id') , "dat" => $d , "dat2" => $d2, "cls" => $v , "cls2" => $v, "sub" => $s, "des" => '%'.$t.'%'  ] );  
      //total no. of times attendance was taken 
   // $results2 = DB::select(" SELECT COUNT(ATT_ID) AS total, ( SELECT COUNT(pup_id) FROM pupil WHERE class_id = :cls2 ) AS totalpupil FROM attendance WHERE _datetime <= :dat AND _datetime >= :dat2 AND class_id = :cls AND _desc LIKE :des AND sub_id = :sub AND tea_id = :tea " , [ "tea" => session('teacher.teacher_id') , "cls" => $v ,"cls2" => $v , "dat" => $d ,"dat2" => $d2, "des" => '%'.$t.'%', "sub" => $s ] ); //get attendance 
    
     //no. of times present 
     $results = DB::select(" SELECT IFNULL(COUNT(a.id),0) AS present, 
     ( SELECT title FROM class_streams WHERE id = :cls2 ) AS clsname       
     FROM attendances a JOIN rowcalls r
     ON r.att_id = a.id  
     WHERE r._status = 1 AND  a._date <= :dat AND a._date >= :dat2 AND a.term = :term AND a._done = 1 AND  
     a.sub_class_id IN ( SELECT id FROM subjectclasses WHERE tea_id = :tea AND class_id = :cls AND sub_id = :sub ) " ,
     [ "dat" => $d , "dat2" => $d2, "tea" => $tea , "term" => $termid , "cls" => $v, "cls2" => $v , "sub" => $s ] ); 
     
     //total no. of times attendance was taken 
     $results2 = DB::select(" SELECT IFNULL(COUNT(a.id),0) AS total ,
     ( SELECT COUNT(id) FROM enrollments WHERE class_id = :cls2 ) AS totalpupil
     FROM attendances a  
     WHERE a._date <= :dat 
     AND a._date >= :dat2 
     AND a.term = :term
     AND a.sub_class_id IN ( SELECT id FROM subjectclasses WHERE tea_id = :tea AND class_id = :cls AND sub_id = :sub ) " ,
    [ "dat" => $d, "dat2" => $d2, "tea" => $tea ,   "cls" => $v, "cls2" => $v, "term" => $termid, "sub" => $s  ] );  
  
  // }

     if (session('head.head_id') || session('supervisor.sup_id') || session('ministry.min_id') ) {
   
      /* $results = DB::select("SELECT COUNT(ATT_ID) AS present, ( SELECT _title FROM CLASS_ WHERE cls_id = :cls2 ) AS clsname FROM rowcall WHERE _STATUS = 1 AND ATT_ID IN ( SELECT ATT_ID FROM attendance WHERE _datetime <= :dat AND _datetime >= :dat2 AND class_id = :cls AND _desc LIKE :des AND sub_id = :sub AND school_sch_id = :tea ) " , [ "tea" => session('general.school_id') , "dat" => $d , "dat2" => $d2, "cls" => $v , "cls2" => $v, "sub" => $s, "des" => '%'.$t.'%'  ] );  
      //total no. of times attendance was taken 
    $results2 = DB::select(" SELECT COUNT(ATT_ID) AS total, ( SELECT COUNT(pup_id) FROM pupil WHERE class_id = :cls2 ) AS totalpupil FROM attendance WHERE _datetime <= :dat AND _datetime >= :dat2 AND class_id = :cls AND _desc LIKE :des AND sub_id = :sub AND school_sch_id = :tea " , [ "tea" => session('general.school_id') , "cls" => $v ,"cls2" => $v , "dat" => $d ,"dat2" => $d2, "des" => '%'.$t.'%', "sub" => $s ] ); //get attendance 
     */

     }
 
     
     $present = 0;//no. of times present
     $nameofstu = "";//name of student
     $total = 0;//total times attendance taken
     $clsid = '';
     $totalpupil = 0;
     $perf = 0;
         
     foreach ($results as $r){ $present = $r->present; } 
     foreach ($results2 as $r){ $total = $r->total; $totalpupil = $r->totalpupil; }
     
     $totalnew = $totalpupil * $total;
       if ($present != 0 && $total != 0){
     $perf = intval($present)/intval($totalnew) * 100;}
    
     $resultarray[$s] =  $perf;
      
 }
     
       $mymsg = array(
         '_subid' => $sub,
         '_arrayperf'=> $resultarray,
         '_subjects' => $subnames,
         '_datenow' => date('Y-m-d H:i:s')
       );
     
  return $mymsg;
} 

private function CgetTypeAssessmentS($type, $v, $d, $d2, $tea, $termid , $typeofuser ){
  
  $sub = array();  
  $subnames = array();
  $resultarray = array();
  
  $nameofstu = "";//name of student
  $clsid = '';
 
   //1st get subject of student by teacher attendance
   // if (  $typeofuser === 'teacher' ){
     
        $resultsubject = DB::select(" SELECT DISTINCT a.sub_id as subid, a.class_id as clsid FROM subjectclasses a JOIN enrollments p ON a.class_id = p.class_id WHERE a.tea_id = :tea  AND p.term_id = :term" ,[ "tea" => $tea,  "term" => $termid ]);
 
   // }
    
    if ( session('head.head_id') || session('supervisor.sup_id') || session('ministry.min_id') ){
        //$resultsubject = DB::select(" SELECT DISTINCT a.SUB_ID as subid FROM attendance a JOIN pupil p ON a.CLASS_ID = p.CLASS_ID WHERE p.CLASS_ID = :cls ",[ "cls" => $v ]);
    }

   foreach ($resultsubject as $r){ 
       $subn = $r->subid;
       $sub[] = $subn;
       $subnames[$subn] = $this->getSubjectName($subn);
   }  
   
  // $results = DB::select(" SELECT _title FROM CLASS_ WHERE cls_id = :cls  " , [ "cls" => $v ] ); 
   $results = DB::select(" SELECT title FROM class_streams WHERE id = :cls  " , [ "cls" => $v ] );
   
   foreach ($results as $r){ $nameofstu = $r->title;  }  
   
  //load perfomance of student per subject offered......
 foreach ($sub as $s) {
      
     //if (  $typeofuser === 'teacher'  ){

        $resultarray[$s] = DB::select(" SELECT IFNULL(AVG(s.perf),0) as perf FROM scores s 
        WHERE s.enrol_id IN ( SELECT id FROM enrollments WHERE class_id = :cls ) 
        AND s.ass_id IN ( SELECT a.id FROM assessments a JOIN lessonnote_managements l 
        ON l.lsn_id = a.lsn_id 
        WHERE a._type = :typ AND l._approval != :appr AND l._submission <= :dat 
        AND l._submission >= :dat2 AND l.lsn_id IN ( SELECT id FROM lessonnotes WHERE tea_id = :tea AND term_id = :term AND sub_id = :sub  ) ) ",
        
        [ "tea" => $tea, "dat" => $d, "dat2" => $d2, "typ" => $type , "appr" => "1970-10-10 00:00:00", "term" => $termid , "cls" => $v, "sub" => $s ]);

     // }
     
     if (session('head.head_id') || session('supervisor.sup_id') || session('ministry.min_id') ){
      
     } 
      
  }

     //AVG(s._PERFORMANCE) as perf
       $mymsg = array(
         '_subid' => $sub,
         '_arrayperf'=> $resultarray,
         '_subjects' => $subnames,
         '_name' => "Nil",
         '_class' => $nameofstu,
         '_datenow' => date('Y-m-d H:i:s')
       );
     
  return $mymsg;
}  


      ///////////////Privat emEthods

        private function getschoolId($tea){
            $schid = "";    
            $name = DB::select("SELECT school_id FROM teachers WHERE id = :id " , [ "id" => $tea ] ); //get 
            foreach($name as $n){
            $schid = $n->school_id;  
            }   
            return intval($schid);
        }

        private function getteacherName($tea){
            $tea_name = "";    
            $name = DB::select("SELECT CONCAT(lname,' ',fname) as myname FROM teachers WHERE id = :id " , [ "id" => $tea ] ); //get 
            foreach($name as $n){
            $tea_name = $n->myname;  
            }   
            return $tea_name;
        }
        
         private function getStudentName($pup_id){
            $tea_cl = "";
            $name = DB::select(" SELECT CONCAT(fname,' ',lname) AS stuname FROM pupils WHERE id = :pupid  " , [ "pupid" => $pup_id ] ); 
            foreach($name as $n){
              $tea_cl = $n->stuname;  
            }   
            return $tea_cl;
        }
        
         private function getStudentClassID($pup_id , $term){
            $tea_cl = "";
            $name = DB::select(" SELECT class_id AS clsid FROM enrollments WHERE pupil_id = :pupid AND term_id = :term " , [ "pupid" => $pup_id, "term" => $term ] ); 
            foreach($name as $n){
              $tea_cl = $n->clsid;  
            }   
            return $tea_cl;
        }
        
        private function getClassName($cls_id){
            $tea_cl = "";
            $name = DB::select("SELECT title as myname FROM class_streams WHERE id = :id " , [ "id" => $cls_id ] ); 
            foreach($name as $n){
              $tea_cl = $n->myname;  
            }   
            return $tea_cl;
        }
        
         private function getClassID($cls_name,$sch){
            $tea_id = "";
            $name = DB::select("SELECT id as myid FROM class_streams WHERE title = :tit AND school_id = :sch " , [ "tit" => $cls_name, "sch" => $sch ] ); 
            foreach($name as $n){
              $tea_id = $n->myid;  
            }   
            return $tea_id;
        }

        private function getClassCategory($cls_id){
          $cat = "";
          $name = DB::select("SELECT category FROM class_streams WHERE id = :id " , [ "id" => $cls_id ] ); 
          foreach($name as $n){
            $cat = $n->category;  
          }   
          return $cat;
      }
        
        private function getSubjectName($sub_id){
            $tea_cl = "";
            $name = DB::select("SELECT name as myname FROM subjects WHERE id = :id " , [ "id" => $sub_id ] ); 
            foreach($name as $n){
              $tea_cl = $n->myname;  
            }   
            return $tea_cl;
        }


      }
