<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Subject;
use App\Pupil;
use App\Term;
use App\Teacher;

class SubjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('apiuser', ['only' => ['show']]);
        $this->middleware('apisuperuser', ['only' => ['store','update','destroy']]);
    }
     /*** Helper Functions */
     public static function getSubjectName($subid){ //Helpher One
        $subject = Subject::where('id',$subid)->first();        
        return  $subject->name;
    }

    public static function getSubjectAll($school){ //Helpher One                
   
        $subjects  = Subject::where('school','LIKE', $school.";")->get();
       
        return $subjects->toArray();

    } 

    // You may add other parameters to granulate the search.......like term and year
    public static function getSubjectAttendance($pupid, $subid, $dstartdate = "", $denddate = "", $dterm = ""){ 
        
        $subject = Subject::where('id',$subid)->first(); 

        $theterm = array(1 => '1st Term', 2 => '2nd Term', 3 => '3rd Term');

        $pupil = Pupil::where('id', $pupid)->first();

        $term = Term::where('school_id',$pupil->school_id)->latest('id')->first();

        if ($dstartdate === "" || $denddate === "" || $dterm === ""){
            $startdate = $term->resumedate;
            $enddate = date('Y-m-d');
            $termval =  intval($term->term);
            //no. of times present 
            $results = DB::select(" SELECT COUNT(ATT_ID) AS present FROM rowcalls WHERE _STATUS = 1 AND PUP_ID = :pupid AND ATT_ID IN ( SELECT id FROM attendances WHERE _date <= :endd AND _date >= :startd AND _desc LIKE :des AND sub_class_id IN (SELECT id FROM subjectclasses WHERE sub_id = :sub)  ) " , [ "endd" => $enddate , "startd" => $startdate, "pupid" => $pupid, "des" => '%'.$theterm[$termval].'%' , "sub" => $subid ] ); 
            
            //total no. of times attendance was taken ,
            $results2 = DB::select(" SELECT COUNT(a.id) AS total FROM attendances a JOIN rowcalls r ON r.ATT_ID = a.id WHERE a.sub_class_id IN (SELECT id FROM subjectclasses WHERE sub_id = :sub) AND a._date <= :endd AND a._date >= :startd AND r.PUP_ID = :pupid AND a._desc LIKE :des" , [ "endd" => $enddate , "startd" => $startdate, "pupid" => $pupid ,  "des" => '%'.$theterm[$termval].'%', "sub" => $subid  ] ); 
        } else {
            $startdate = $dstartdate;
            $enddate = $denddate;
            $termval =  intval($dterm);
            //no. of times present 
            $results = DB::select(" SELECT COUNT(ATT_ID) AS present FROM rowcalls WHERE _STATUS = 1 AND PUP_ID = :pupid AND ATT_ID IN ( SELECT id FROM attendances WHERE _date <= :endd AND _date >= :startd AND _desc LIKE :des AND sub_class_id IN (SELECT id FROM subjectclasses WHERE sub_id = :sub)  ) " , [ "endd" => $enddate , "startd" => $startdate, "pupid" => $pupid, "des" => '%'.$theterm[$termval].'%' , "sub" => $subid ] ); 
            
            //total no. of times attendance was taken 
            $results2 = DB::select(" SELECT COUNT(a.id) AS total FROM attendances a JOIN rowcalls r ON r.ATT_ID = a.id WHERE a.sub_class_id IN (SELECT id FROM subjectclasses WHERE sub_id = :sub) AND a._date <= :endd AND a._date >= :startd AND r.PUP_ID = :pupid AND a._desc LIKE :des" , [ "endd" => $enddate , "startd" => $startdate, "pupid" => $pupid ,  "des" => '%'.$theterm[$termval].'%', "sub" => $subid  ] ); 
        }

            $perf = 0;
            $present = 0;//no. of times present
            $total = 0;//total times attendance taken
                
            foreach ($results as $r){ $present = $r->present; } 
            foreach ($results2 as $r){ $total = $r->total; }
            if ($total === 0){
                $perf = 0;
            }  
            else{
                $perf = intval($present)/intval($total) * 100;
            }
      
            return $perf === 0 ? "---" : intval($perf)." %";
    }

    //lessonnote 
    public static function getSubjectLessonnote($pupid, $subid,$dsweek = "", $deweek = "", $dterm = ""){ 
        
        $subject = Subject::where('id',$subid)->first(); 

        $theterm = array(1 => '1st Term', 2 => '2nd Term', 3 => '3rd Term');

        $pupil = Pupil::where('id', $pupid)->first();

        $term = Term::where('school_id',$pupil->school_id)->latest('id')->first();

        if ($dsweek === "" || $deweek === "" || $dterm === ""){
           

            //AND l._SUBMISSION <= :dat AND l._SUBMISSION >= :dat2
        $resultclw =  DB::select(" SELECT IFNULL(AVG(s.perf),0) as perf FROM scores s WHERE s.enrol_id = :pup AND s.ass_id IN ( SELECT a.id FROM assessments a JOIN lessonnote_managements l ON l.lsn_id = a.lsn_id JOIN lessonnotes ln ON ln.id = a.lsn_id WHERE a._TYPE = :typ AND l._APPROVAL != :appr AND ln.SUB_ID = :sub  )  ",
        [ "pup" => $pupid, "typ" => "CW", "appr" => "1970-10-10 00:00:00" , "sub" => $subid ]);
         
        $resulthmwk =  DB::select(" SELECT IFNULL(AVG(s.perf),0) as perf FROM scores s WHERE s.enrol_id = :pup AND s.ass_id IN ( SELECT a.id FROM assessments a JOIN lessonnote_managements l ON l.lsn_id = a.lsn_id JOIN lessonnotes ln ON ln.id = a.lsn_id WHERE a._TYPE = :typ AND l._APPROVAL != :appr AND ln.SUB_ID = :sub )  ",
        [ "pup" => $pupid, "typ" => "AS", "appr" => "1970-10-10 00:00:00" , "sub" => $subid ]);
       
        $resulttest =  DB::select(" SELECT IFNULL(AVG(s.perf),0) as perf FROM scores s WHERE s.enrol_id = :pup AND s.ass_id IN ( SELECT a.id FROM assessments a JOIN lessonnote_managements l ON l.lsn_id = a.lsn_id JOIN lessonnotes ln ON ln.id = a.lsn_id WHERE a._TYPE = :typ AND l._APPROVAL != :appr AND ln.SUB_ID = :sub )  ",
        [ "pup" => $pupid, "typ" => "TS", "appr" => "1970-10-10 00:00:00" , "sub" => $subid ]);
       
            $clw = 0; $hmwork = 0; $test = 0;
            if ($resultclw[0] !== null){
                   $clw = intval($resultclw[0]->perf);
                }
            if ($resulthmwk[0] !== null){
                    $hmwork = intval($resulthmwk[0]->perf);
                 }  
            if ($resulttest[0] !== null){
                    $test = intval($resulttest[0]->perf);
                }
        }
        else{

            //AND l._SUBMISSION <= :dat AND l._SUBMISSION >= :dat2
        $resultclw =  DB::select(" SELECT IFNULL(AVG(s.perf),0) as perf FROM scores s WHERE s.enrol_id = :pup AND s.ass_id IN ( SELECT a.id FROM assessments a JOIN lessonnote_managements l ON l.lsn_id = a.lsn_id JOIN lessonnotes ln ON ln.id = a.lsn_id WHERE a._TYPE = :typ AND l._APPROVAL != :appr AND ln.SUB_ID = :sub AND ln.PERIOD >= :per AND ln.PERIOD <= :per2 AND ln.TERM_ID = :term )  ",
        [ "pup" => $pupid, "typ" => "CW", "appr" => "1970-10-10 00:00:00" , "sub" => $subid, "per" => dsweek , "per2" => deweek, "term" => $dterm  ]);
         
        $resulthmwk =  DB::select(" SELECT IFNULL(AVG(s.perf),0) as perf FROM scores s WHERE s.enrol_id = :pup AND s.ass_id IN ( SELECT a.id FROM assessments a JOIN lessonnote_managements l ON l.lsn_id = a.lsn_id JOIN lessonnotes ln ON ln.id = a.lsn_id WHERE a._TYPE = :typ AND l._APPROVAL != :appr AND ln.SUB_ID = :sub AND ln.PERIOD >= :per AND ln.PERIOD <= :per2 AND ln.TERM_ID = :term)  ",
        [ "pup" => $pupid, "typ" => "AS", "appr" => "1970-10-10 00:00:00" , "sub" => $subid , "per" => dsweek , "per2" => deweek, "term" => $dterm ]);
       
        $resulttest =  DB::select(" SELECT IFNULL(AVG(s.perf),0) as perf FROM scores s WHERE s.enrol_id = :pup AND s.ass_id IN ( SELECT a.id FROM assessments a JOIN lessonnote_managements l ON l.lsn_id = a.lsn_id JOIN lessonnotes ln ON ln.id = a.lsn_id WHERE a._TYPE = :typ AND l._APPROVAL != :appr AND ln.SUB_ID = :sub AND ln.PERIOD >= :per AND ln.PERIOD <= :per2 AND ln.TERM_ID = :term)  ",
        [ "pup" => $pupid, "typ" => "TS", "appr" => "1970-10-10 00:00:00" , "sub" => $subid, "per" => dsweek , "per2" => deweek, "term" => $dterm  ]);
       
            $clw = 0; $hmwork = 0; $test = 0;
            if ($resultclw[0] !== null){
                   $clw = intval($resultclw[0]->perf);
                }
            if ($resulthmwk[0] !== null){
                    $hmwork = intval($resulthmwk[0]->perf);
                 }  
            if ($resulttest[0] !== null){
                    $test = intval($resulttest[0]->perf);
                }

        }
        
        
      
            return " CW: ". $clw . "% <br> ". " HW:  ". $hmwork . "% <br>" . " TS:  ". $test. "% ";
    }
    //
    public static function getSubjectAttendanceTeacher($teaid, $classid, $subid,  $dstartdate = "", $denddate = "", $dterm = ""){ 
        
        $subject = Subject::where('id',$subid)->first(); 

        $theterm = array(1 => '1st Term', 2 => '2nd Term', 3 => '3rd Term');

        $teacher = Teacher::where('id', $teaid)->first();

        $term = Term::where('school_id',$teacher->school_id)->latest('id')->first();

        if ($dstartdate === "" || $denddate === "" || $dterm === ""){

        $startdate = $term->resumedate;
        $enddate = date('Y-m-d');
        $termval =  intval($term->term);

          //no. of times present 
          $results = DB::select(" SELECT COUNT(a.id) AS present FROM attendances a WHERE a.sub_class_id IN (SELECT id FROM subjectclasses WHERE sub_id = :sub AND tea_id = :tea AND class_id = :cls ) AND a._date <= :endd AND a._date >= :startd AND a._desc LIKE :dess AND a._done = 1" , [ "endd" => $enddate , "startd" => $startdate,  "tea" => $teaid , "dess" => '%'.$theterm[$termval].'%' , "sub" => $subid, "cls" => $classid ] ); 
          
          //total no. of times attendance was taken 
          $results2 = DB::select(" SELECT COUNT(a.id) AS total FROM attendances a WHERE a.sub_class_id IN (SELECT id FROM subjectclasses WHERE sub_id = :sub AND tea_id = :tea AND class_id = :cls ) AND a._date <= :endd AND a._date >= :startd AND a._desc LIKE :dess" , [ "endd" => $enddate , "startd" => $startdate, "tea" => $teaid ,  "dess" => '%'.$theterm[$termval].'%', "sub" => $subid, "cls" => $classid  ] ); 
        }
        
        else{

            $startdate = $dstartdate;
            $enddate = $denddate;
            $termval =  intval($dterm);

               //no. of times present 
          $results = DB::select(" SELECT COUNT(a.id) AS present FROM attendances a WHERE a.sub_class_id IN (SELECT id FROM subjectclasses WHERE sub_id = :sub AND tea_id = :tea AND class_id = :cls ) AND a._date <= :endd AND a._date >= :startd AND a._desc LIKE :dess AND a._done = 1" , [ "endd" => $enddate , "startd" => $startdate,  "tea" => $teaid , "dess" => '%'.$theterm[$termval].'%' , "sub" => $subid, "cls" => $classid ] ); 
          
          //total no. of times attendance was taken 
          $results2 = DB::select(" SELECT COUNT(a.id) AS total FROM attendances a WHERE a.sub_class_id IN (SELECT id FROM subjectclasses WHERE sub_id = :sub AND tea_id = :tea AND class_id = :cls ) AND a._date <= :endd AND a._date >= :startd AND a._desc LIKE :dess" , [ "endd" => $enddate , "startd" => $startdate, "tea" => $teaid ,  "dess" => '%'.$theterm[$termval].'%', "sub" => $subid, "cls" => $classid  ] ); 

        }
            $perf = 0;
            $present = 0;//no. of times present
            $total = 0;//total times attendance taken
                
            foreach ($results as $r){ $present = $r->present; } 
            foreach ($results2 as $r){ $total = $r->total; }
            if ($total === 0){
                $perf = 0;
            }  
            else{
                $perf = intval($present)/intval($total) * 100;
            }
      
            return $perf === 0 ? "---" : intval($perf)." %";
    }

    //lessonnote 
    public static function getSubjectLessonnoteTeacher($pupid, $subid,$dsweek = "", $deweek = "", $dterm = ""){ 
        
        $subject = Subject::where('id',$subid)->first(); 

        $theterm = array(1 => '1st Term', 2 => '2nd Term', 3 => '3rd Term');

        $pupil = Pupil::where('id', $pupid)->first();

        $term = Term::where('school_id',$pupil->school_id)->latest('id')->first();

        if ($dsweek === "" || $deweek === "" || $dterm === ""){
           

            //AND l._SUBMISSION <= :dat AND l._SUBMISSION >= :dat2
        $resultclw =  DB::select(" SELECT IFNULL(AVG(s.perf),0) as perf FROM scores s WHERE s.enrol_id = :pup AND s.ass_id IN ( SELECT a.id FROM assessments a JOIN lessonnote_managements l ON l.lsn_id = a.lsn_id JOIN lessonnotes ln ON ln.id = a.lsn_id WHERE a._TYPE = :typ AND l._APPROVAL != :appr AND ln.SUB_ID = :sub  )  ",
        [ "pup" => $pupid, "typ" => "CW", "appr" => "1970-10-10 00:00:00" , "sub" => $subid ]);
         
        $resulthmwk =  DB::select(" SELECT IFNULL(AVG(s.perf),0) as perf FROM scores s WHERE s.enrol_id = :pup AND s.ass_id IN ( SELECT a.id FROM assessments a JOIN lessonnote_managements l ON l.lsn_id = a.lsn_id JOIN lessonnotes ln ON ln.id = a.lsn_id WHERE a._TYPE = :typ AND l._APPROVAL != :appr AND ln.SUB_ID = :sub )  ",
        [ "pup" => $pupid, "typ" => "AS", "appr" => "1970-10-10 00:00:00" , "sub" => $subid ]);
       
        $resulttest =  DB::select(" SELECT IFNULL(AVG(s.perf),0) as perf FROM scores s WHERE s.enrol_id = :pup AND s.ass_id IN ( SELECT a.id FROM assessments a JOIN lessonnote_managements l ON l.lsn_id = a.lsn_id JOIN lessonnotes ln ON ln.id = a.lsn_id WHERE a._TYPE = :typ AND l._APPROVAL != :appr AND ln.SUB_ID = :sub )  ",
        [ "pup" => $pupid, "typ" => "TS", "appr" => "1970-10-10 00:00:00" , "sub" => $subid ]);
       
            $clw = 0; $hmwork = 0; $test = 0;
            if ($resultclw[0] !== null){
                   $clw = intval($resultclw[0]->perf);
                }
            if ($resulthmwk[0] !== null){
                    $hmwork = intval($resulthmwk[0]->perf);
                 }  
            if ($resulttest[0] !== null){
                    $test = intval($resulttest[0]->perf);
                }
        }
        else{

            //AND l._SUBMISSION <= :dat AND l._SUBMISSION >= :dat2
        $resultclw =  DB::select(" SELECT IFNULL(AVG(s.perf),0) as perf FROM scores s WHERE s.enrol_id = :pup AND s.ass_id IN ( SELECT a.id FROM assessments a JOIN lessonnote_managements l ON l.lsn_id = a.lsn_id JOIN lessonnotes ln ON ln.id = a.lsn_id WHERE a._TYPE = :typ AND l._APPROVAL != :appr AND ln.SUB_ID = :sub AND ln.PERIOD >= :per AND ln.PERIOD <= :per2 AND ln.TERM_ID = :term )  ",
        [ "pup" => $pupid, "typ" => "CW", "appr" => "1970-10-10 00:00:00" , "sub" => $subid, "per" => dsweek , "per2" => deweek, "term" => $dterm  ]);
         
        $resulthmwk =  DB::select(" SELECT IFNULL(AVG(s.perf),0) as perf FROM scores s WHERE s.enrol_id = :pup AND s.ass_id IN ( SELECT a.id FROM assessments a JOIN lessonnote_managements l ON l.lsn_id = a.lsn_id JOIN lessonnotes ln ON ln.id = a.lsn_id WHERE a._TYPE = :typ AND l._APPROVAL != :appr AND ln.SUB_ID = :sub AND ln.PERIOD >= :per AND ln.PERIOD <= :per2 AND ln.TERM_ID = :term)  ",
        [ "pup" => $pupid, "typ" => "AS", "appr" => "1970-10-10 00:00:00" , "sub" => $subid , "per" => dsweek , "per2" => deweek, "term" => $dterm ]);
       
        $resulttest =  DB::select(" SELECT IFNULL(AVG(s.perf),0) as perf FROM scores s WHERE s.enrol_id = :pup AND s.ass_id IN ( SELECT a.id FROM assessments a JOIN lessonnote_managements l ON l.lsn_id = a.lsn_id JOIN lessonnotes ln ON ln.id = a.lsn_id WHERE a._TYPE = :typ AND l._APPROVAL != :appr AND ln.SUB_ID = :sub AND ln.PERIOD >= :per AND ln.PERIOD <= :per2 AND ln.TERM_ID = :term)  ",
        [ "pup" => $pupid, "typ" => "TS", "appr" => "1970-10-10 00:00:00" , "sub" => $subid, "per" => dsweek , "per2" => deweek, "term" => $dterm  ]);
       
            $clw = 0; $hmwork = 0; $test = 0;
            if ($resultclw[0] !== null){
                   $clw = intval($resultclw[0]->perf);
                }
            if ($resulthmwk[0] !== null){
                    $hmwork = intval($resulthmwk[0]->perf);
                 }  
            if ($resulttest[0] !== null){
                    $test = intval($resulttest[0]->perf);
                }

        }
        
        
      
            return " CW: ". $clw . "% <br> ". " HW:  ". $hmwork . "% <br>" . " TS:  ". $test. "% ";
    }

////////////////////////////////////////////////////////////////////////////
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subjects = Subject::all();

        return response()->json($subjects->toArray());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'category' => 'required|string'
        ]);

        if ($validator->fails()) {
           $data['status'] = "Failed";
           $data['message'] = $validator->errors();
           return response()->json($data);
        }

        $subject = new Subject;
        $subject->fill($request->all());
        $subject->save();

        $data['status'] = "Success";
        $data['message'] = $request->all();

        return response()->json($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $subject = Subject::findOrFail($id);
        $data['status'] = "Success";
        $data['message'] = $subject;
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $subject = Subject::findOrFail($id);
            $subject->fill($request->all());
            $subject->save();
    
            $data['status'] = "Success";
            $data['message'] = $request->all();
    
            return response()->json($data);

        } catch (Exception $e) {
            $data['status'] = "Error";
            $data['message'] = $e->getMessage();
            return response()->json($data);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $subject = Subject::findOrFail($id);
            $subject->delete();
    
            $data['status'] = "Success";
            $data['message'] = $subject;
    
            return response()->json($data);

        } catch (Exception $e) {
            $data['status'] = "Error";
            $data['message'] = $e->getMessage();
            return response()->json($data);
        }
    }
}
