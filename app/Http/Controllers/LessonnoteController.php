<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Lessonnote;
use App\LessonnoteManagement;
use App\LsnPerformance;
use App\LsnActivity;
use App\Assessment;
use App\Score;

use App\User;
use App\Teacher;
use App\Subject;
use App\ClassStream;
use App\Subjectclass;
use App\Term;
use App\Enrollment;

class LessonnoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('apiuser', ['only' => ['index','show']]);
    }
    /*** Helper Functions */
    public function getScoreClassWork($enrolid){ //Helpher One
        $scorescls = Score::where('enrol_id',$enrolid)->whereHas('assessment', function (Builder $query) {
            $query->where('_type', '=', "C"); //classwork
        })->first(); 
        //return $scorescls ? $scorescls->actual : "";
        $data['status'] = "Success";
        $data['data'] = $scorescls ? $scorescls->actual : "";
        return response()->json($data);
    }

    public function getScoreHomeWork($enrolid){ //Helpher Two
        $scorescls = Score::where('enrol_id',$enrolid)->whereHas('assessment', function (Builder $query) {
            $query->where('_type', '=', "A"); //homework
        })->first(); 
       // return $scorescls ? $scorescls->actual : "";
       $data['status'] = "Success";
       $data['data'] = $scorescls ? $scorescls->actual : "";
       return response()->json($data);
    }

    public function getPupilName($enrolid){ //Helpher Three
        $enrol = Enrollment::where('id',$enrolid)->first(); 
       // return $enrol ? $enrol->pupil->fname." ".$enrol->pupil->lname : "";
        $data['status'] = "Success";
        $data['data'] =  $enrol ? $enrol->pupil->fname." ".$enrol->pupil->lname : "";
        return response()->json($data);
    }

    public function getClassName($enrolid){ //Helpher Four
        $enrol = Enrollment::where('id',$enrolid)->first(); 
     //   return $enrol ? $enrol->classtream->title." ".$enrol->classtream->ext : "";
        $data['status'] = "Success";
        $data['data'] =  $enrol ? $enrol->classtream->title." ".$enrol->classtream->ext : "";
        return response()->json($data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lessonnote = Lessonnote::all();

        return response()->json($lessonnote->toArray());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $lesson = Lessonnote::findOrFail($id);
        $data['status'] = "Success";
        $data['message'] = $lesson;
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /////////////////////////////////////////Custom functions Here
     /**
     * Get Subject Class combination of teacher ONLY
     * @return \Illuminate\Http\Response
     */
    public function getSubjectClass($teaid)
    {
        //$subclass = Subjectclass::where('tea_id', $teaid)->get();
        $subclass = DB::select("SELECT MIN(su.ID) as ID, MIN(cs.title) as title, MIN(s.name) as namez, MIN(s.id) as subid, cs.category FROM subjectclasses su 
        INNER JOIN class_streams cs ON su.class_id = cs.id 
        INNER JOIN subjects s ON s.id = su.sub_id
        WHERE su.tea_id = :tea
        GROUP BY (cs.category)
        ",['tea' => $teaid]);
        
        $datablock = array();
      
        foreach($subclass as $sub){      
                        $cat = array("SubClassID" => $sub->ID, "ClassName" => $sub->title, "Subject" => $sub->namez, 
                        "SubjectId" => $sub->subid, "ClassCat" => $sub->category );
                        $datablock[] = $cat;                
        }
       
        if(!empty($datablock)){
            $data['status'] = "Success";
            $data['data'] = $datablock;
            return response()->json($data);
        }
        else{
            $data['status'] = "Failed";
            $data['message'] = "No Subject Class has been assigned to this Teacher";
            return response()->json($data);
        }
    }

     /**
     * Submit Lessonnote of Teacher
     * @return \Illuminate\Http\Response
     */
    public function submitLessonnote(Request $request)
    {
         $policy = 0;///if it matches the policy of the school--- me
         $fully = 1;///if it has come with the lessnnote+classwork+test+assignment -- head
         $qua = 1;///if it has met policy + fully + cycle + closure of this lessonnote -- 
         $flag = 0;///if the no. of students performance in assessment is ok -- teacher after scores added
         $perf = 0;///the total of done + policY + fully + qua
         $cycle = 1;///the no of cycles the lessonnote made
         $closure = 0;///the total of done + policY + fully + qua
         $mylessonnote = null;
         if ( $request->file('lsn') === null) {
            $data['status'] = "Failed";
            $data['message'] = "Lessonnote FIle not uploaded alongside form...";
            return response()->json($data);
         } 
    try{
        $week = $request->get('week');
        $subject = $request->get('subject');
        $classcat = $request->get('classcat');
        $tea = $request->get('teacher');
        $lessonnote_url = "LessonNote/". $tea ."/Template";
        Storage::disk('public')->makeDirectory($lessonnote_url);

        $mylessonnote = null;
        if ( $request->file('lsn') !== null) {
           $mylessonnote =  $request->file('lsn');   
        } 
        
        $cls = ClassStream::where('category', $classcat)->first();
        $sub = Subject::findOrFail($subject);
        $teacher = Teacher::findOrFail($tea);
        $term = Term::where('school_id', $teacher->school_id)->where('_status', 1)->first();

        $title = $teacher->fname.$teacher->id. "_Week ".$week. "_Term ".$term->term."_".$cls->title."_".$sub->name."_".date('Y');

        $time = DB::select('SELECT term , resumedate , ( week(curdate()) - week(resumedate) + 1 ) AS weeksout FROM terms WHERE _status = 1 AND school_id = :sch;', [ "sch" => $teacher->school_id ]);
                    
        foreach ($time as $t){
              $weeksout = $t->weeksout;
        }      

        if ($weeksout + 1 === $week){
            $policy = 1;
         }

         $perf = ($policy/3) * 100;
        
         //Check if it hass been rejected       

        $checkifweek = DB::select("SELECT * FROM lessonnotes WHERE tea_id = :id AND title = :ti " , ["id" => $teacher->id, "ti"=>$title ] ); 
        $teaidx = $teacher->id;
        if (!empty($checkifweek)){
            $lsnmanage = LessonnoteManagement::where('lsn_id', '=', $checkifweek[0]->id)->where('_revert', "!=" , "1970-10-10 00:00:00")->first();
            $lsnmanage2 = LessonnoteManagement::where('lsn_id', '=', $checkifweek[0]->id)->where('_submission', "!=" , "1970-10-10 00:00:00")->first();
           if ($lsnmanage !== null){
                $title = $teacher->fname.$teacher->id. "_Week ".$week. "_Term ".$term->term."_".$cls->title."_".$sub->name."_".date('Y')."_Resubmitted ".$lsnmanage->_cycle;
                $lsnmanage->_submission = "1970-10-10 00:00:00";
                $lsnmanage->_resubmission = date('Y-m-d H:i:s');
                $lsnmanage->_revert = "1970-10-10 00:00:00";
                $lsnmanage->_approval =  "1970-10-10 00:00:00";
                $lsnmanage->_exclosure = date("Y-m-d H:i:s",strtotime( "+10 days", strtotime ( date('d-m-Y H:i') ) ));
                $lsnmanage->_cycle = $lsnmanage->_cycle + 1;
                $lsn = Lessonnote::findOrFail($checkifweek[0]->id);
                $lsn->_file = $title.".docx"; 
                if ($mylessonnote !== null){
                    Storage::putFileAs(
                        "public/".$lessonnote_url , $mylessonnote, $title.".docx"
                    );
                }
                $lsn->save();  
                $lsnmanage->save();

                $data['status'] = "Success";
                $data['message'] = "Lessonnote has been resubmitted";
                return response()->json($data);
            }
            else if($lsnmanage2 !== null){
                $title = $teacher->fname.$teacher->id. "_Week ".$week. "_Term ".$term->term."_".$cls->title."_".$sub->name."_".date('Y')."_Replaced ".date('hh:ss');
                $lsnmanage->_submission = date('Y-m-d H:i:s');
                $lsnmanage->_resubmission = "1970-10-10 00:00:00";
                $lsnmanage->_revert = "1970-10-10 00:00:00";
                $lsnmanage->_approval =  "1970-10-10 00:00:00";
                $lsnmanage->_exclosure = date("Y-m-d H:i:s",strtotime( "+10 days", strtotime ( date('d-m-Y H:i') ) ));
                $lsn = Lessonnote::findOrFail($checkifweek[0]->id);
                $lsn->_file = $title.".docx"; 
                if ($mylessonnote !== null){
                    Storage::putFileAs(
                        "public/".$lessonnote_url , $mylessonnote, $title.".docx"
                    );
                }
                $lsn->save();  
                $lsnmanage->save();

                $data['status'] = "Success";
                $data['message'] = "Lessonnote has been Submitted, even Though You had submitted before.";
                return response()->json($data);
            }  
            else{
                $data['status'] = "Failed";
                $data['message'] = "Lessonnote has been submitted already / Please submit for another week.";
               // $data['message'] = $lsnmanage;
                return response()->json($data);
            }
         }

           //Storage::disk('local')->put('lessonnotes/'+$title.".docx", $mylessonnote);
        if ($mylessonnote !== null){           
            /* $mylessonnote->storeAs(
                 "public/".$lessonnote_url, $title.".docx"
             ); */
             Storage::putFileAs(
                 "public/".$lessonnote_url , $mylessonnote, $title.".docx"
             );
         }        

       

         $lsnobj = Lessonnote::create([
            'tea_id' =>  $tea,
            'sub_id' => $subject,
            'term_id' => $term->term,
            'title' => $title,
            'class_category' => $classcat,
            '_date' => date('Y-m-d'),
            'period' => $week,
            '_file' => $title.".docx" ]);

              //Create an assessment option
                $assobjhomework = Assessment::create([
                    'lsn_id' =>  $lsnobj->id,
                    'sub_id' => $subject,
                    'source' => 'nil',
                    'title' => $title . " Assignment",
                    '_date' => date('Y-m-d'),
                    '_type' => 'AS',
                ]);

                $assobjclswork = Assessment::create([
                    'lsn_id' =>  $lsnobj->id,
                    'sub_id' => $subject,
                    'source' => 'nil',
                    'title' => $title . " Classwork",
                    '_date' => date('Y-m-d'),
                    '_type' => 'CW',
                ]);

                $assobjtest = Assessment::create([
                    'lsn_id' =>  $lsnobj->id,
                    'sub_id' => $subject,
                    'source' => 'nil',
                    'title' => $title . " Test",
                    '_date' => date('Y-m-d'),
                    '_type' => 'TS',
                ]);

             
                ////////////////

            $sch = $teacher->school_id;
            
            $principal = User::where('_type', 1)->whereHas('teacher', function (Builder $query) use ($sch) {
                $query->where('school_id', '=', $sch);
            })->first();

            //First add to lessonnote management table
            LessonnoteManagement::create([
                'lsn_id' => $lsnobj->id,
                '_submission' => date('Y-m-d H:i:s'),
                '_exclosure' => date("Y-m-d H:i:s",strtotime( "+10 days", strtotime ( date('d-m-Y H:i') ) )),
                '_cycle' => 1                 
            ]);

            //owner now is the teacher --Put this to track his performance after taking this attendance
            LsnPerformance::create([
                'lsn_id' => $lsnobj->id,
                'policy' => $policy,
                'fully' => $fully,
                'qua' => $qua,
                'flag' => $flag ,
                'perf' => $perf                  
            ]);

            if ($principal !== null){
                $expected = strtotime( "+7 days", strtotime ( date('d-m-Y H:i') ) ); 
                $action = 0;
                //owner now is the head/principal --Put this so that we track his response time
                LsnActivity::create([
                    'lsn_id' => $lsnobj->id,
                    'owner' => $principal->teacher_id,
                    'ownertype' => 'Principal',
                    'expected' => $expected,
                    'action' => 0,
                    'slip' => 0                   
                ]);              
            }
            
            $data['status'] = "Success";
            $data['message'] = "Lessonnote has been submitted succesfully.";
            return response()->json($data);

        } catch (Exception $e) {
            $data['status'] = "Failed";
            $data['message'] = $e->getMessage();
            return response()->json($data);
        }


    }

     /**
     *  View Lessonnote of Teacher
     * @return \Illuminate\Http\Response
     */
    public function viewLessonnoteTeacher(Request $request, $teaid)
    {
        $mydate = "";
        if ( !is_null($request->get('_date')) ){
            $mydate = $request->get('_date');
        }
        $lsn = array();
        if ($mydate !== ""){
            $lsn = Lessonnote::where('_date', 'LIKE', "%".$mydate."%")->whereHas('teacher', function (Builder $query) use ($teaid) {
                $query->where('id', '=', $teaid);
            })->get();
        }
        else{
            $lsn = Lessonnote::whereHas('teacher', function (Builder $query) use ($teaid) {
                $query->where('id', '=', $teaid);
            })->get();
        }
       
        $datablock = array();
        
        if (!empty($lsn)){
            foreach ($lsn as $lessonnote){ 
                $lsnperf = LsnPerformance::where('lsn_id', $lessonnote->id)->first();
                $status = $this->getLessonnoteStatus($lessonnote->id);
                $datablock[] = array("id" => $lessonnote->id, "Subject" => $lessonnote->subject->name, "Title" => $lessonnote->title, "Status" => $status, "Filez" => $lessonnote->_file, "Perf" => $lsnperf->perf, "Comment" => $lessonnote->comment_principal ? $lessonnote->comment_principal : ""  );
            }
            $data['status'] = "Success";
            $data['message'] = "Your lessonnote data is provided....";
            $data['data'] = $datablock;
            return response()->json($data);
        }
        else { 
            $data['status'] = "Failed";
            $data['message'] = "Your lessonnote data is not available. Sorry";
            return response()->json($data);
        }
    }

    
    /**
     *  View students in that lessonnote that was submitted
     * @return \Illuminate\Http\Response
    */
    public function viewLessonnoteScores(Request $request, $lsnid, $task)
    {
        $mydate = "";

        $ls = Lessonnote::where("id",'=',$lsnid)->first();      

        $enrolcls = Enrollment::whereHas('classtream', function (Builder $query) use ($ls) {
            $query->where('category', $ls->class_category); 
        })->get(); 

        $datablock = array("id" => $ls->id, "Subject" => $ls->subject->name, "Title" => $ls->title, "ObjectPupils" => $enrolcls, "Task" => intval($task) );
     
        
     
        $data['status'] = "Success";
        $data['message'] = "Your lessonnote pupil data is provided....";
        $data['data'] = $datablock;
        return response()->json($data);
    }

    /**
     *  View scores
     * @return \Illuminate\Http\Response
    */
    public function viewLessonnoteTeacherScores(Request $request, $teacher)
    {
            $mydate = "";
       
            $assessment = array();

            $datablock = array();
        
            $lsn = Lessonnote::where("tea_id",'=',$teacher)->rightJoin('lessonnote_managements', 'lessonnote_managements.lsn_id', '=', 'lessonnotes.id')->where('lessonnote_managements._approval', '!=', "1970-10-10 00:00:00")->get();

            foreach ($lsn as $ls){
           
           $assessmentcls = Assessment::where('_type', '=', "CW")->whereHas('lessonnote', function (Builder $query) use ($ls) {
                $query->join('class_streams', 'lessonnotes.class_category', '=', 'class_streams.category')->join('enrollments', 'class_streams.id', '=', 'enrollments.class_id')->where('lessonnotes.id', '=', $ls->id); //classwork
            })->get(); 

            $assessmenthwk = Assessment::where('_type', '=', "AS")->whereHas('lessonnote', function (Builder $query) use ($ls) {
                $query->join('class_streams', 'lessonnotes.class_category', '=', 'class_streams.category')->join('enrollments', 'class_streams.id', '=', 'enrollments.class_id')->where('lessonnotes.id', '=', $ls->id); //homework
            })->get();

            $assessmenttest = Assessment::where('_type', '=', "TS")->whereHas('lessonnote', function (Builder $query) use ($ls) {
                $query->join('class_streams', 'lessonnotes.class_category', '=', 'class_streams.category')->join('enrollments', 'class_streams.id', '=', 'enrollments.class_id')->where('lessonnotes.id', '=', $ls->id); //homework
            })->get();
           
            $clswork = "0"; $homework = "0"; $test = "0";
            
            if ( count($assessmentcls) > 0){
                $scoreavg = Score::where('ass_id', '=', $assessmentcls->id)->count('perf');
                    if (null !== $scoreavg){
                        $clswork = "Added Scores to: ". $scoreavg." To ";
                    }                
            } 
    
            if (count($assessmenthwk) > 0){           
                $scoreavg = Score::where('ass_id', '=', $assessmenthwk->id)->count('perf');
                    if (null !== $scoreavg){
                        $homework = "Added Scores to: ". $scoreavg;
                    }                        
            }

            if (count($assessmenttest) > 0){           
                $scoreavg = Score::where('ass_id', '=', $assessmenttest->id)->count('perf');
                    if (null !== $scoreavg){
                        $test = "Added Scores to: ". $scoreavg;
                    }                        
            }

            $datablock[] = array("id" => $ls->lsn_id, "blob" => $lsn, "Subject" => $ls->subject->name, "Title" => $ls->title,  "Clswork" => $clswork ,  "Hmwork" => $homework , "Test" => $test);
      
        }
        
        $data['status'] = "Success";
        $data['message'] = "Your lessonnote pupil data is provided....";
        $data['data'] = $datablock;
        return response()->json($data);
    }

    /**
     *  
     * @return \Illuminate\Http\Response
     */
    public function viewLessonnoteTeacherAll(Request $request, $teaid)
    {
        $teach = Teacher::findOrFail($teaid); 
       //$data['message'] = "Your lessonnote data is provided....";
     //   $data['data'] = $teacher;
       // return response()->json($data);
        $mydate = "";
        if ( !is_null($request->get('_date')) ){
            $mydate = $request->get('_date');
        }
        $lsn = array();
        if ($mydate !== ""){
            $lsn = Lessonnote::where('_date', 'LIKE', "%".$mydate."%")->whereHas('teacher', function (Builder $query) use ($teach) {
                $query->where('school_id', '=', $teach->school_id);
            })->get();
        }
        else{
            $lsn = Lessonnote::whereHas('teacher', function (Builder $query) use ($teach) {
                $query->where('school_id', '=', $teach->school_id);
            })->get();
        }
       
        $datablock = array();
        
        if (!empty($lsn)){
            foreach ($lsn as $lessonnote){ 
                $lsnperf = LsnPerformance::where('lsn_id', $lessonnote->id)->first();
                $lsnmanage = LessonnoteManagement::where('lsn_id', $lessonnote->id)->first();
                $status = $this->getLessonnoteStatus($lessonnote->id);
                $datablock[] = array("id" => $lessonnote->id, "Subject" => $lessonnote->subject->name, "Title" => $lessonnote->title, "Status" => $status, "Filez" => $lessonnote->_file, "Perf" => $lsnperf->perf, "Teacher"=>$lessonnote->teacher->fname." ".$lessonnote->teacher->lname, "TeacherID" => $lessonnote->teacher->id, "Cycle" => $lsnmanage->_cycle , "Comment" => $lessonnote->comment_principal ? $lessonnote->comment_principal : ""  );
            }
            $data['status'] = "Success";
            $data['message'] = "Your lessonnote data is provided....";
            $data['data'] = $datablock;
            return response()->json($data);
        }
        else { 
            $data['status'] = "Failed";
            $data['message'] = "Your lessonnote data is not available. Sorry";
            return response()->json($data);
        }
    }

     /**
     *  View The flags of a lessonnote
     * @return \Illuminate\Http\Response
     */
    public function viewLessonnoteFlags(Request $request, $teaid)
    {
        {
            $mycycle = "";
            // $subclassx = "";
           //$tea = Auth::user()->teacher_id;
            $teacher = Teacher::findOrFail($teaid); 
            $term = Term::where('_status',1)->where('school_id',$teacher->school_id)->first();
             
             if ( !is_null($request->get('cycle')) ){
                 $mycycle = $request->get('cycle');
             }
     
             $lsn = null;  // not done
             $lsn2 = null; //late attendance    
             $lsn3 = null; //waivers available
             $lsn4 = null; //attendance approval delay available
             $lsn5 = null; //incomplete attendances
             $lsn6 = null;
             $lsn7 = null;
             $lsn8 = null;
             $lsn9 = null;
           
             if ($mycycle !== ""){

                  //Late Submission
                   $lsn = DB::select(
                    "SELECT COUNT(l.ID) as mylatesub
                    FROM lessonnotes l 
                    INNER JOIN lsn_performances t ON t.lsn_id = l.id
                    INNER JOIN lessonnote_managements c ON c.lsn_id = l.id
                    WHERE l.tea_id IN ( SELECT id FROM teachers WHERE school_id = :sch ) AND t.policy = 0  AND l.period = :per        
                    " , 
                    [ "sch" =>  $teacher->school_id, "per" => $mycycle ] );
                
                  //Late Re-Submission
                    $lsn2 = DB::select(
                        "SELECT COUNT(l.ID) as mylateresub
                        FROM lessonnotes l 
                        INNER JOIN lsn_performances t ON t.lsn_id = l.id
                        INNER JOIN lessonnote_managements c ON c.lsn_id = l.id
                        WHERE l.tea_id IN ( SELECT id FROM teachers WHERE school_id = :sch ) AND t.policy = 0  AND l.period = :per AND c._resubmission != :sub          
                        " , 
                        [ "sch" =>  $teacher->school_id, "per" => $mycycle , "sub" => "1970-10-10 00:00:00"] );
                    //Poor quality
                    $lsn3 = DB::select(
                            "SELECT COUNT(l.ID) as myquality
                            FROM lessonnotes l 
                            INNER JOIN lsn_performances t ON t.lsn_id = l.id
                            INNER JOIN lessonnote_managements c ON c.lsn_id = l.id
                            WHERE l.tea_id IN ( SELECT id FROM teachers WHERE school_id = :sch ) AND t.qua = 0  AND l.period = :per           
                            " , 
                            [ "sch" =>  $teacher->school_id, "per" => $mycycle ] );
                    // Poor performance
                    $lsn4 = DB::select(
                                "SELECT COUNT(l.ID) as myperf
                                FROM lessonnotes l 
                                INNER JOIN lsn_performances t ON t.lsn_id = l.id
                                INNER JOIN lessonnote_managements c ON c.lsn_id = l.id
                                WHERE l.tea_id IN ( SELECT id FROM teachers WHERE school_id = :sch ) AND t.perf <= 30  AND l.period = :per         
                                " , 
                                [ "sch" =>  $teacher->school_id, "per" => $mycycle ] );
                     //  Delayed action by Principal 
                    $lsn5 = DB::select(
                                    "SELECT COUNT(l.ID) as mydelay
                                    FROM lessonnotes l 
                                    INNER JOIN lsn_activities t ON t.lsn_id = l.id
                                    INNER JOIN lessonnote_managements c ON c.lsn_id = l.id
                                    WHERE l.tea_id IN ( SELECT id FROM teachers WHERE school_id = :sch ) AND t.actual IS NULL  AND l.period = :per         
                                    " , 
                                    [ "sch" =>  $teacher->school_id, "per" => $mycycle ] );
                   
                   // latter we do this late closure A, approved but not cloased
                   $lsn6 = DB::select(
                                        "SELECT COUNT(l.ID) as lateclosure
                                        FROM lessonnotes l 
                                        INNER JOIN lsn_activities t ON t.lsn_id = l.id
                                        INNER JOIN lessonnote_managements c ON c.lsn_id = l.id
                                        WHERE l.tea_id IN ( SELECT id FROM teachers WHERE school_id = :sch ) AND l.period = :per AND c._approval != :sub AND c._closure != :sub1       
                                        " , 
                                        [ "sch" =>  $teacher->school_id, "per" => $mycycle , "sub" => "1970-10-10 00:00:00", "sub1" => "1970-10-10 00:00:00"] );

                    // latter we do this late closure B -- Not so sure about this // 
                   $lsn7 = DB::select(
                        "SELECT COUNT(l.ID) as lateclosure
                        FROM lessonnotes l 
                        INNER JOIN lsn_activities t ON t.lsn_id = l.id
                        INNER JOIN lessonnote_managements c ON c.lsn_id = l.id
                        WHERE l.tea_id IN ( SELECT id FROM teachers WHERE school_id = :sch ) AND l.period = :per AND c._closure = :sub AND c._exclosure >= CURRENT_DATE         
                        " , 
                        [ "sch" =>  $teacher->school_id, "per" => $mycycle , "sub" => "1970-10-10 00:00:00"] );

                    // Non closure 14 days after the officuial day has passed
                   $lsn8 = DB::select(
                    "SELECT COUNT(l.ID) as nonclosure
                    FROM lessonnotes l 
                    INNER JOIN lsn_activities t ON t.lsn_id = l.id
                    INNER JOIN lessonnote_managements c ON c.lsn_id = l.id
                    WHERE l.tea_id IN ( SELECT id FROM teachers WHERE school_id = :sch ) AND l.period = :per AND c._closure = :sub AND datediff(CURRENT_DATE , c._exclosure) >= 14      
                    " , 
                    [ "sch" =>  $teacher->school_id, "per" => $mycycle , "sub" => "1970-10-10 00:00:00"] );

                    // Late closure response............. 3 = CLosure
                   $lsn9 = DB::select(
                    "SELECT COUNT(l.ID) as lateclosure
                    FROM lessonnotes l 
                    INNER JOIN lsn_activities t ON t.lsn_id = l.id
                    INNER JOIN lessonnote_managements c ON c.lsn_id = l.id
                    WHERE l.tea_id IN ( SELECT id FROM teachers WHERE school_id = :sch ) AND l.period = :per AND c._closure != :sub AND t.action = 3 AND t.slip = 0 AND t.actual != :act      
                    " , 
                    [ "sch" =>  $teacher->school_id, "per" => $mycycle , "sub" => "1970-10-10 00:00:00", "act" => "" ] );

    
             }
             else{
                 // those not done at all
                 $data['status'] = "Failed";
                 $data['message'] = "No lessonnote data available yet to show the flags generated";             
                 return response()->json($data);
             }
             
             if (!is_null($lsn) && !is_null($lsn2) && !is_null($lsn3) && !is_null($lsn4) && !is_null($lsn5) && !is_null($lsn6) && !is_null($lsn7)  && !is_null($lsn8) && !is_null($lsn9) )  {             
                
                $latesub = 0;
                $lateresub = 0;
                $quality = 0;
                $perf = 0;
                $delayed = 0;
                $closureA = 0;
                $closureB = 0;
                $nonclosure = 0;
                $lateclosure = 0;

                foreach ($lsn as $at1){
                    $latesub = intval($at1->mylatesub);
                }
                foreach ($lsn2 as $at1){
                    $lateresub = intval($at1->mylateresub);
                }
                foreach ($lsn3 as $at1){
                    $quality = intval($at1->myquality);
                }
                foreach ($lsn4 as $at1){
                    $perf = intval($at1->myperf);
                }
                foreach ($lsn5 as $at1){
                    $delayed = intval($at1->mydelay);
                }
                foreach ($lsn6 as $at1){
                    $closureA = intval($at1->lateclosure);
                }
                foreach ($lsn7 as $at1){
                    $closureB = intval($at1->lateclosure);
                }
                foreach ($lsn8 as $at1){
                    $nonclosure = intval($at1->nonclosure);
                }
                foreach ($lsn9 as $at1){
                    $lateclosure = intval($at1->lateclosure);
                }
                
                     $datablock = array("Week" => $mycycle, "LSubmit" => $latesub , "LRSubmit" => $lateresub, "Quality" => $quality, "Perf" => $perf, "Delay" => $delayed, "ClosureA" => $closureA, "ClosureB" => $closureB, "NClosure" => $nonclosure, "LClosure" => $lateclosure   );
                 
                 $data['status'] = "Success";
                 $data['message'] = "Your lessonnote flag data is provided....";
                 $data['data'] = $datablock;
                 return response()->json($data);
             }
     
             else { 
                 $data['status'] = "Failed";
                 $data['message'] = "Your lessonnote flag data is not available. Sorry";
                 return response()->json($data);
             }
        }
    
    }

      /**
     *  
     * @return \Illuminate\Http\Response
     */
    public function changeStatusLessonnote(Request $request, $lsnid, $idx)
    {
        $idx = intval($idx);
        
        $teaid = $request->get('teacher');   
        $comment = $request->get('comment');   
        
        $lsnreal = Lessonnote::where('id', $lsnid)->first();
        $lsn = LessonnoteManagement::where('lsn_id', $lsnid)->first();

        $data = array();

        if ($idx === 1){ // rejected, so I resubmit
            $lsn->_submission = "1970-10-10 00:00:00";
            $lsn->_revert = "1970-10-10 00:00:00";
            $lsn->_resubmission = date('Y-m-d H:i:s');
            $lsn->_cycle = $lsn->_cycle + 1;
            $lsn->save();
            $data['status'] = "Success";
            $data['message'] = "Your lessonnote management status has been changed to RESUBMITTED";
           
        }
        else if ($idx === 2){ //I launch it
            $lsn->_submission = "1970-10-10 00:00:00";
            $lsn->_resubmission = "1970-10-10 00:00:00";
            $lsn->_launch = date('Y-m-d H:i:s');
        
            $lsn->save();
            $data['status'] = "Success";
            $data['message'] = "Your lessonnote management status has been changed to LAUNCHED";
           
        }
        else if ($idx === 3){ //I close it
            $lsn->_submission = "1970-10-10 00:00:00";
            $lsn->_resubmission = "1970-10-10 00:00:00";
            $lsn->_launch = "1970-10-10 00:00:00";
            $lsn->_closure = date('Y-m-d H:i:s');
         
            $lsn->save();
            $data['status'] = "Success";
            $data['message'] = "Your lessonnote management status has been changed to CLOSED";
            
        }

        else if ($idx === 4){ //I reject it as a Principal
            if ($teaid !== null){
                $teacher = Teacher::findOrFail($teaid); 
                $lsnperf = LsnPerformance::where('lsn_id', $lsnid)->first();
                $lsnactivity = LsnActivity::where('lsn_id', $lsnid)->where('owner', $teacher->id)->first();
                $lsnperf->fully = 0;
                $lsnperf->qua = 0;               

                $actual = strtotime ( date('d-m-Y H:i') );
                $expected = $lsnactivity->expected;                
               
                $lsnactivity->action = 4;
                $lsnactivity->actual = $actual;
        
                $theslip = 0;
                  
                if ($expected <  $actual) { 
                 
                    $theslip = 1;
                }
        
                $lsnactivity->slip = $theslip;

                if ($lsn->_cycle > 2 && $lsn->_cycle <= 4){
                   
                    $lsnperf->perf = 30;
                }
                else if ($lsn->_cycle <= 2){
                    
                    $lsnperf->perf = 50;
                }
                else{
                    $lsnperf->perf = 10;
                }               
                
                if($comment !== null){
                    $lsnreal->comment_principal = $comment;
                    $lsnreal->save();
                }

                $lsnperf->save();
                $lsnactivity->save();
                
            }
            $lsn->_submission = "1970-10-10 00:00:00";
            $lsn->_resubmission = "1970-10-10 00:00:00";            
            $lsn->_revert = date('Y-m-d H:i:s');
            $lsn->_cycle = $lsn->_cycle + 1;
            $lsn->save();
            $data['status'] = "Success";
            $data['message'] = "Your lessonnote management status has been changed to REJECTED";
          
        }

        else if ($idx === 5){ //I approve it as a Principal       
            
           if ($teaid !== null){
                $teacher = Teacher::findOrFail($teaid); 
                $lsnperf = LsnPerformance::where('lsn_id', $lsnid)->first();
                $lsnactivity = LsnActivity::where('lsn_id', $lsnid)->where('owner', $teacher->id)->first();
                $lsnperf->fully = 1;
                $lsnperf->qua = 1;               

                $actual = strtotime ( date('d-m-Y H:i') );
                $expected = $lsnactivity->expected;                
               
                $lsnactivity->action = 5;
                $lsnactivity->actual = $actual;
        
                $theslip = 0;
                  
                if ($expected <  $actual) { 
                 
                    $theslip = 1;
                }
        
                $lsnactivity->slip = $theslip;

                if ($lsn->_cycle > 2 && $lsn->_cycle <= 4){
                   
                    $lsnperf->perf = 50;
                }
                else if ($lsn->_cycle <= 2){
                    
                    $lsnperf->perf = 100;
                }
                else{
                    $lsnperf->perf = 30;
                }               

                $lsnperf->save();
                $lsnactivity->save();
            }
            $lsn->_submission = "1970-10-10 00:00:00";
            $lsn->_resubmission = "1970-10-10 00:00:00";
            $lsn->_revert = "1970-10-10 00:00:00";
            $lsn->_approval = date('Y-m-d H:i:s');
            $lsn->_cycle = $lsn->_cycle + 1;
            $lsn->save();

            $data['status'] = "Success";
            $data['message'] = "Your lessonnote management status has been changed to APPROVED";
        }

       

        return response()->json($data);  

    }

    public function enterscore(Request $request){

        $scores = $request->input('score');
        $max = $request->input('max');
        $examid = $request->input('examid');
        $classid = $request->input('clsid');
       
        $scorex = implode(";", $scores);//turn into a concatenated string
        $scorearray = explode(";", $scorex);

        $totalpupils = session('ln_pupil');
        $i = 0;
        
         $checkifexists = DB::select("SELECT actual FROM scores WHERE ass_id = :ex " , ["ex"=>$examid ] ); 
         
         if ( count($checkifexists) <= 0 )   {  
        foreach ($totalpupils as $to) {
           
         $exam = new Score;
         $exam->actual = $scorearray[$i]; 
         $exam->max = $max;
         $exam->_date = date('Y-m-d');
         $exam->enrol_id = $to->pupid;      
         $exam->perf = 0;
         $exam->save(); 
         $i++;

                    }   } else {
             foreach ($totalpupils as $to) {
                 
           $exam = new Score;             
           $exam::where('exam_id',$examid )->update(['perf' => 0,'max' => $max,'_date' => date('Y-m-d'), 'actual' => $scorearray[$i]]);
              $i++;
                 
             }
                    }
        
        $request->session()->flash('ln_enterscore_success',1);
        
         return redirect('/tlsnscores');
    }

    private function getLessonnoteStatus($lsn){
        $mymsg = "";
     
        $results = DB::select("SELECT _submission,_resubmission,_revert,_approval,_launch,_closure FROM lessonnote_managements WHERE lsn_id = :lsn " , ["lsn" => $lsn ] );
        
        if (empty($results)){
            $mymsg =  "NOT DONE..";
        }
        else{
            foreach($results as $r){          
                if ('1970-10-10 00:00:00' !== $r->_closure){
                    $mymsg = "ARCHIVED..";
                }
                else if ('1970-10-10 00:00:00' !== $r->_launch){
                    $mymsg = "ACTIVE..";
                }
                else if ('1970-10-10 00:00:00' !== $r->_approval){
                    $mymsg = "APPROVED..";
                }
                else if ('1970-10-10 00:00:00' !== $r->_revert){
                    $mymsg = "REJECTED..";
                }
                else if ('1970-10-10 00:00:00' !== $r->_resubmission){
                    $mymsg = "RE-SUBMITTED..";
                }
                else if ('1970-10-10 00:00:00' !== $r->_submission){
                    $mymsg = "SUBMITTED..";
                }
                else{
                    $mymsg =  "NOT DONE..";
                }
            } 
        }
        return $mymsg;
    }
}
