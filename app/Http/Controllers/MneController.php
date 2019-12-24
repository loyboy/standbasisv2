<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MneController extends Controller
{
    
       public function getSavedValues(Request $request, $teaid){
        $sd = $request->input('sd');
        $ed = $request->input('ed');
        $tr = $request->input('tr');
        session()->flash('searchdata.sd', $sd);
        session()->flash('searchdata.ed', $ed);
        session()->flash('searchdata.tr', $tr);
          $mymsg = array(
            'done'=> 1         
          );
          return response()->json($mymsg);
       }
       //////////////////////////////////////////////TEACHER
       public function loadteachermne_student_gen(Request $request){
         
        $dateofreq2 = $request->input('sdate'); //start date
        
        $dateofreq = $request->input('edate'); //end date
        
        $dateofreq = date('Y-m-d', strtotime('+1 days', strtotime($dateofreq)));//add 1 day to original date to give accuracy
        
        $tea =  $request->input('tea'); //teacher ID
         
        $term = $request->input('term'); //Term of the school
        $termval = array("1st Term" => 1, "2nd Term" => 2, "3rd Term" => 3);
        $termval2 = $termval[$term];
         
        $valofreq = $request->input('stu'); //Student ID
        
         ////////////////////////////////////////////ATTENDANCE
         //no. of times present 
         $results = DB::select(" SELECT IFNULL(COUNT(ATT_ID),0) AS present, ( SELECT CONCAT(fname,' ',lname) FROM pupil WHERE id = :pupid2 ) AS stuname, ( SELECT class_id FROM enrollments WHERE pupil_id = :pupid3 AND term_id = :term ) AS clsid FROM rowcalls WHERE _STATUS = 1 AND PUP_ID = :pupid AND ATT_ID IN ( SELECT ATT_ID FROM attendances WHERE _date <= :dat AND _date >= :dat2 AND tea_id = :tea AND _desc LIKE :des ) " , [ "dat" => $dateofreq , "dat2" => $dateofreq2, "pupid" => $valofreq, "pupid2" => $valofreq, "pupid3" => $valofreq, "tea" => $tea, "des" => '%'.$term.'%', "term" => $termval2  ] ); 
         
         //total no. of times attendance was taken 
         $results2 = DB::select(" SELECT IFNULL(COUNT(a.ATT_ID),0) AS total FROM attendances a JOIN rowcalls r ON r.ATT_ID = a.ATT_ID WHERE a._date <= :dat AND a._date >= :dat2 AND a.tea_id = :teaid AND r.PUP_ID = :pupid AND a._desc LIKE :des" , ["teaid" => $tea, "dat" => $dateofreq,"dat2" => $dateofreq2, "pupid" => $valofreq ,  "des" => '%'.$term.'%' ] ); 
           
           $present = 0;//no. of times present
           $nameofstu = "";//name of student
           $total = 0;//total times attendance taken
           $clsid = null;
           
           if ($results != null){    
           foreach ($results as $r){ $present = $r->present; $nameofstu = $r->stuname; $clsid = $r->clsid; } 
           }
           else { $perf = 0; $nameofstu = $this->getStudentName($valofreq); $clsid = $this->getStudentClassID($valofreq);  }
            if ($results2 != null){   
           foreach ($results2 as $r){ $total = $r->total; }
            }
             else { $perf = 0; $nameofstu = $this->getStudentName($valofreq); $clsid = $this->getStudentClassID($valofreq);  }
           
           if ($present == 0 && $total == 0){ $perf = 0; } 
           else{ $perf = (intval($present)/intval($total)) * 100; }
           
           $clsname = $this->getClassName($clsid);
           
            $theattend = array(
               '_perf'=> $perf,
               '_name' => $nameofstu,
               '_class' => $clsname,
               '_datenow' => date('Y-m-d H:i:s')
              
           );
       
           $theattendsub = $this->getTypeAttendanceS($valofreq,$dateofreq,$dateofreq2,$term);
           ////////////////////////////////////////////END ATTENDANCE
          
           ////////////////////////////////////////////EXAMINATIONS
           $theclw = $this->getTypeAssessmentG('CW',$valofreq,$dateofreq,$dateofreq2);
             
           $theclwsub =  $this->getTypeAssessmentS('CW',$valofreq,$dateofreq,$dateofreq2);
           
           $theass = $this->getTypeAssessmentG('AS',$valofreq,$dateofreq,$dateofreq2);
           
           $theasssub = $this->getTypeAssessmentS('AS',$valofreq,$dateofreq,$dateofreq2);
           
           $thetest = $this->getTypeAssessmentG('TS',$valofreq,$dateofreq,$dateofreq2);
           
           $thetestsub = $this->getTypeAssessmentS('TS',$valofreq,$dateofreq,$dateofreq2);
           
           $themidterm = $this->getTypeAssessmentG('MT',$valofreq,$dateofreq,$dateofreq2);
           
           $themidtermsub = $this->getTypeAssessmentS('MT',$valofreq,$dateofreq,$dateofreq2);
           
           $theterminal = $this->getTypeAssessmentG('TE',$valofreq,$dateofreq,$dateofreq2);
             
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
         
           
            return response()->json($mymsg);
        
   }

    /////////////////////////////////////////////////////////////////STUDENT
    
    private function getTypeAssessmentG($type, $v, $d, $d2){
         
        if (Auth::user()->_type === 0){
         $resultsclw = DB::select(" SELECT IFNULL(AVG(s.perf),0) as perf FROM scores s WHERE s.enrol_id = :pup AND s.ass_id IN ( SELECT ID FROM assessments e JOIN lessonnote_managements l ON l.lsn_id = e.lsn_id WHERE e._TYPE = :typ AND l._APPROVAL != :appr AND l._SUBMISSION <= :dat AND l._SUBMISSION >= :dat2 AND l.TEA_ID = :tea )  ",
        [ "pup" => $v, "tea" => session('teacher.teacher_id'), "dat" => $d, "dat2" => $d2, "typ" => $type , "appr" => "1970-10-10 00:00:00" ]);
         }
        if ( Auth::user()->_type === 1 ){
         $resultsclw = DB::select(" SELECT IFNULL(AVG(s._PERFORMANCE),0) as perf FROM score s WHERE s.pupil_id = :pup AND s.exam_id IN ( SELECT EXAM_ID FROM exam e JOIN lessonnote l ON l.LSN_ID = e.LSN_ID WHERE e._TYPE = :typ AND l._APPROVAL != :appr AND l._SUBMISSION <= :dat AND l._SUBMISSION >= :dat2 AND l.school_sch_id = :sch )  ",
        [ "pup" => $v, "sch" => session('general.school_id'), "dat" => $d, "dat2" => $d2, "typ" => $type , "appr" => "1970-10-10 00:00:00" ]);
             
         }
        
         //
          $results = DB::select(" SELECT ( SELECT CONCAT(fname,' ',lname) FROM pupils WHERE id = :pupid2 ) AS stuname, ( SELECT CLASS_ID FROM enrollments WHERE pupil_id = :pupid3 ) AS clsid FROM rowcalls WHERE _STATUS = 1 AND PUPIL_ID = :pupid " , [ "pupid" => $v, "pupid2" => $v, "pupid3" => $v  ] ); 
          
            $clsid = null;//class id
            $nameofstu = "";//name of student
            $perf = 0;
            
              if ($results != null){    
            foreach ($results as $r){ $nameofstu = $r->stuname; $clsid = $r->clsid; } 
            }
            else { $nameofstu = $this->getStudentName($v); $clsid = $this->getStudentClassID($v);  }
             
             if ($resultsclw != null){   
            foreach ($resultsclw as $r){ $perf = $r->perf; }
             }
              else { $perf = 0; $nameofstu = $this->getStudentName($v); $clsid = $this->getStudentClassID($v);  }
              
             $clsname = $this->getClassName($clsid);
            
              $mymsg = array(
                '_perf'=> $perf,
                '_name' => $nameofstu,
                '_class' => $clsname,
                '_datenow' => date('Y-m-d H:i:s')
               
            );
            return $mymsg;
     }
     
    private function getTypeAttendanceS($v, $d, $d2, $t){
         
         $sub = array();  
         $subnames = array();
         $resultarray = array();
         $tea =  session('teacher.teacher_id'); 
        
          if (Auth::user()->_type === 0){
          //1st get subject of student by teacher attendance
          $resultsubject = DB::select(" SELECT DISTINCT a.SUB_ID as subid FROM subjectclasses a JOIN enrollments p ON a.CLASS_ID = p.CLASS_ID WHERE a.TEA_ID = :tea AND p.PUPIL_ID = :pup ",[ "tea" => $tea, "pup" => $v ]);
          }
          if (Auth::user()->_type === 1){
           $resultsubject = DB::select(" SELECT DISTINCT a.SUB_ID as subid FROM subjectclasses a JOIN enrollments p ON a.CLASS_ID = p.CLASS_ID WHERE p.PUPIL_ID = :pup ",[ "pup" => $v ]);
             
          }
          if (Auth::user()->_type === 2){
           $resultsubject = DB::select(" SELECT DISTINCT a.SUB_ID as subid FROM subjectclasses a JOIN enrollments p ON a.CLASS_ID = p.CLASS_ID WHERE p.PUPIL_ID = :pup  ",[ "pup" => $v ]);
             
          }
           if (Auth::user()->_type === 3){
           $resultsubject = DB::select(" SELECT DISTINCT a.SUB_ID as subid FROM subjectclasses a JOIN enrollments p ON a.CLASS_ID = p.CLASS_ID WHERE p.PUPIL_ID = :pup ",[ "pup" => $v ]);
             
          }
          foreach ($resultsubject as $r){ 
              $subn = $r->subid;
              $sub[] = $subn;
              $subnames[$subn] = $this->getSubjectName($subn);
          }  
          
        //load perfomance of student in Attendance per subject offered......
         
        foreach ($sub as $s) {
         
          if (Auth::user()->_type === 0){
              
         //no. of times present 
          $results = DB::select(" SELECT IFNULL(COUNT(ATT_ID),0) AS present FROM rowcalls WHERE _STATUS = 1 AND PUPIL_ID = :pupid AND ATT_ID IN ( SELECT ATT_ID FROM attendance WHERE _datetime <= :dat AND _datetime >= :dat2 AND tea_id = :tea AND _desc LIKE :des AND sub_id = :sub ) " , [ "dat" => $d , "dat2" => $d2, "pupid" => $v, "tea" => $tea, "des" => '%'.$t.'%', "sub" => $s ] ); 
          
          //total no. of times attendance was taken 
         $results2 = DB::select(" SELECT IFNULL(COUNT(a.ATT_ID),0) AS total FROM attendance a JOIN rowcall r ON r.ATT_ID = a.ATT_ID WHERE a._datetime <= :dat AND a._datetime >= :dat2 AND a.tea_id = :teaid AND r.PUPIL_ID = :pupid AND a._desc LIKE :des AND a.SUB_ID = :sub " , [ "teaid" => $tea, "dat" => $d, "dat2" => $d2, "pupid" => $v, "des" => '%'.$t.'%', "sub" => $s ] ); 
          }  
           if (session('head.head_id') || session('supervisor.sup_id') || session('ministry.min_id') ){
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
     
    private function getTypeAssessmentS($type, $v, $d, $d2){
         
         $sub = array();  
         $subnames = array();
         $resultarray = array();
         $tea =  session('teacher.teacher_id'); 
         
         $nameofstu = "";//name of student
         $clsid = null;
        if (session('teacher.teacher_id')){
          //1st get subject of student by teacher attendance
          $resultsubject = DB::select(" SELECT DISTINCT a.SUB_ID as subid FROM attendance a JOIN pupil p ON a.CLASS_ID = p.CLASS_ID WHERE a.TEA_ID = :tea AND p.PUP_ID = :pup ",[ "tea" => $tea, "pup" => $v ]);
        }
        
        if (session('head.head_id') || session('supervisor.sup_id') || session('ministry.min_id') ){
         $resultsubject = DB::select(" SELECT DISTINCT a.SUB_ID as subid FROM attendance a JOIN pupil p ON a.CLASS_ID = p.CLASS_ID WHERE p.PUP_ID = :pup ",[ "pup" => $v ]);
             
        }
          foreach ($resultsubject as $r){ 
              $subn = $r->subid;
              $sub[] = $subn;
              $subnames[$subn] = $this->getSubjectName($subn);
          }  
          
          $results = DB::select(" SELECT ( SELECT CONCAT(_FNAME,' ',_LNAME) FROM pupil WHERE pup_id = :pupid2 ) AS stuname, ( SELECT CLASS_ID FROM pupil WHERE pup_id = :pupid3 ) AS clsid FROM rowcall WHERE _STATUS = 1 AND PUPIL_ID = :pupid " , [ "pupid" => $v, "pupid2" => $v, "pupid3" => $v  ] ); 
         
          foreach ($results as $r){ $nameofstu = $r->stuname; $clsid = $r->clsid; } 
          $i = 0;
          $clsname = $this->getClassName($clsid);
          
         //load perfomance of student per subject offered......
        foreach ($sub as $s) {
              if (session('teacher.teacher_id')){
            $resultarray[$s] =  DB::select(" SELECT IFNULL(AVG(s._PERFORMANCE),0) as perf FROM score s WHERE s.pupil_id = :pup AND s.exam_id IN ( SELECT EXAM_ID FROM exam e JOIN lessonnote l ON l.LSN_ID = e.LSN_ID WHERE e._TYPE = :typ AND l._APPROVAL != :appr AND l._SUBMISSION <= :dat AND l._SUBMISSION >= :dat2 AND l.SUB_ID = :sub AND l.TEA_ID = :tea )  ",
            [ "pup" => $v, "tea" => $tea, "dat" => $d, "dat2" => $d2, "typ" => $type, "appr" => "1970-10-10 00:00:00" , "sub" => $s ]);
              }
              
               if (session('head.head_id') || session('supervisor.sup_id') || session('ministry.min_id') ){
                   $resultarray[$s] =  DB::select(" SELECT IFNULL(AVG(s._PERFORMANCE),0) as perf FROM score s WHERE s.pupil_id = :pup AND s.exam_id IN ( SELECT EXAM_ID FROM exam e JOIN lessonnote l ON l.LSN_ID = e.LSN_ID WHERE e._TYPE = :typ AND l._APPROVAL != :appr AND l._SUBMISSION <= :dat AND l._SUBMISSION >= :dat2 AND l.SUB_ID = :sub AND l.school_sch_id = :sch )  ",
            [ "pup" => $v, "sch" => session('general.school_id'), "dat" => $d, "dat2" => $d2, "typ" => $type, "appr" => "1970-10-10 00:00:00" , "sub" => $s ]);
            
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
            $name = DB::select("SELECT CONCAT(lname,' ',fname) as myname FROM teachers WHERE tea_id = :id " , [ "id" => $tea ] ); //get 
            foreach($name as $n){
            $tea_name = $n->myname;  
            }   
            return $tea_name;
        }
        
         private function getStudentName($pup_id){
            $tea_cl = "";
            $name = DB::select(" SELECT CONCAT(fname,' ',lname) AS stuname FROM pupils WHERE pup_id = :pupid  " , [ "pupid" => $pup_id ] ); 
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
            $name = DB::select("SELECT CLS_ID as myid FROM class_streams WHERE title = :tit AND school_id = :sch " , [ "tit" => $cls_name, "sch" => $sch ] ); 
            foreach($name as $n){
              $tea_id = $n->myid;  
            }   
            return $tea_id;
        }
        
        private function getSubjectName($sub_id){
            $tea_cl = "";
            $name = DB::select("SELECT name as myname FROM subjects WHERE sub_id = :id " , [ "id" => $sub_id ] ); 
            foreach($name as $n){
              $tea_cl = $n->myname;  
            }   
            return $tea_cl;
        }
}
