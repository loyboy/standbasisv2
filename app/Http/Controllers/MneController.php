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
         AND r.pup_id = :pupid AND a.sub_class_id IN ( SELECT id FROM subjectclasses WHERE tea_id = :tea ) AND a._desc LIKE :desf " ,
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
          $theclw = $this->getTypeAssessmentG('CW', $tea, $enrolid , $pupid, $dateofreq,$dateofreq2,$termid );
             
           $theclwsub =  $this->getTypeAssessmentS('CW', $tea, $enrolid , $pupid, $dateofreq, $dateofreq2, $termid );
           
           $theass = $this->getTypeAssessmentG('AS', $tea, $enrolid , $pupid, $dateofreq, $dateofreq2, $termid );
           
           $theasssub = $this->getTypeAssessmentS('AS', $tea, $enrolid , $pupid, $dateofreq,$dateofreq2,$termid );
           
           $thetest = $this->getTypeAssessmentG('TS', $tea, $enrolid , $pupid, $dateofreq,$dateofreq2,$termid);
           
           $thetestsub = $this->getTypeAssessmentS('TS', $tea, $enrolid , $pupid, $dateofreq,$dateofreq2,$termid);
           
           $themidterm = $this->getTypeAssessmentG('MT', $tea, $enrolid , $pupid, $dateofreq,$dateofreq2,$termid);
           
           $themidtermsub = $this->getTypeAssessmentS('MT', $tea, $enrolid , $pupid, $dateofreq,$dateofreq2,$termid);
           
           $theterminal = $this->getTypeAssessmentG('TE', $tea, $enrolid , $pupid, $dateofreq,$dateofreq2,$termid); 
             
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
            AND s.ass_id IN ( SELECT id FROM assessments e JOIN lessonnote_managements l 
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
          ( SELECT class_id FROM enrollments WHERE pupil_id = :pupid3 ) AS clsid FROM rowcalls 
          WHERE _status = 1 AND pupil_id = :pupid 
          AND att_id IN ( SELECT id FROM attendances WHERE term = :term )
          " , [ "pupid" => $pup, "pupid2" =>$pup, "pupid3" => $pup, "term" => $term ] ); 
          
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
     
    private function getTypeAssessmentS($type, $teaid, $enrol, $pup,  $d, $d2, $term){
         
         $sub = array();  
         $subnames = array();
         $resultarray = array();
         $tea =  $teaid; 
         
         $nameofstu = "";//name of student
         $clsid = null;
        if (session('teacher.teacher_id')){
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
          ( SELECT class_id FROM pupils WHERE id = :pupid3 ) AS clsid FROM rowcalls
           WHERE _status = 1 AND pupil_id = :pupid 
           AND att_id IN ( SELECT id FROM attendances WHERE term = :term )
           " , [ "pupid" => $pup, "pupid2" => $pup, "pupid3" => $pup ,  "term" => $term ] ); 
         
          foreach ($results as $r){ $nameofstu = $r->stuname; $clsid = $r->clsid; } 
          $i = 0;
          $clsname = $this->getClassName($clsid);
          
         //load perfomance of student per subject offered......
        foreach ($sub as $s) {

              if (session('teacher.teacher_id')){
                  $resultarray[$s] =  DB::select(" SELECT IFNULL(AVG(s.perf),0) as perf FROM scores s WHERE s.enrol_id = :pup
                      AND s.ass_id 
                      IN ( SELECT id FROM assessments e JOIN lessonnotes l 
                      ON l.id = e.lsn_id 
                      WHERE e._type = :typ AND l.term_id = :term AND l._approval != :appr AND l._submission <= :dat AND l._submission >= :dat2 
                      AND l.sub_id = :sub AND l.tea_id = :tea )  ",
                  [ "pup" => $pup, "tea" => $tea, "dat" => $d, "dat2" => $d2, "typ" => $type, "appr" => "1970-10-10 00:00:00" , "sub" => $s, "term" => $term ]);
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



      ///////////////Privat emEthods

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
        
        private function getSubjectName($sub_id){
            $tea_cl = "";
            $name = DB::select("SELECT name as myname FROM subjects WHERE id = :id " , [ "id" => $sub_id ] ); 
            foreach($name as $n){
              $tea_cl = $n->myname;  
            }   
            return $tea_cl;
        }
}
