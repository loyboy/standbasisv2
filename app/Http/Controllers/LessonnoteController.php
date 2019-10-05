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
        $subclass = DB::select("SELECT ANY_VALUE(su.ID) as ID, ANY_VALUE(cs.title) as title, ANY_VALUE(s.name) as namez, ANY_VALUE(s.id) as subid, cs.category FROM subjectclasses su 
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
         $fully = 0;///if it has come with the lessnnote+classwork+test+assignment -- head
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

        //Storage::disk('local')->put('lessonnotes/'+$title.".docx", $mylessonnote);
        if ($mylessonnote !== null){           
           /* $mylessonnote->storeAs(
                "public/".$lessonnote_url, $title.".docx"
            ); */
            Storage::putFileAs(
                "public/".$lessonnote_url , $mylessonnote, $title.".docx"
            );
        }

        $checkifweek = DB::select("SELECT * FROM lessonnotes WHERE tea_id = :id AND title = :ti " , ["id" => $teacher->id, "ti"=>$title ] ); 

        if (!empty($checkifweek)){
            $data['status'] = "Failed";
            $data['message'] = "Lessonnote has been submitted already / Please submit for another week.";
            return response()->json($data);
         }

         $time = DB::select('SELECT term , resumedate , ( week(curdate()) - week(resumedate) + 1 ) AS weeksout FROM terms WHERE _status = 1 AND school_id = :sch;', [ "sch" => $teacher->school_id ]);
                    
         foreach ($time as $t){
               $weeksout = $t->weeksout;
         }

         if ($weeksout === $week){
            $policy = 1;
         }

         $perf = intval($policy/3) * 100;

         $lsnobj = Lessonnote::create([
            'tea_id' =>  $tea,
            'sub_id' => $subject,
            'term_id' => $term->term,
            'title' => $title,
            'class_category' => $classcat,
            '_date' => date('Y-m-d'),
            'period' => $week,
            '_file' => $title.".docx" ]);

            $sch = $teacher->school_id;
            
            $principal = User::where('_type', 1)->whereHas('teacher', function (Builder $query) use ($sch) {
                $query->where('school_id', '=', $sch);
            })->first();

            //First add to lessonnote management table
            LessonnoteManagement::create([
                'lsn_id' => $lsnobj->id,
                '_submission' => date('Y-m-d H:i:s'),
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
                $expected = strtotime( "+16 hours", strtotime ( date('d-m-Y H:i') ) ); 
                $action = 0;
                //owner now is the head/principal --Put this so that we track his response time
                LsnActivity::create([
                    'lsn_id' => $lsnobj->id,
                    'owner' => $principal->id,
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
                $datablock[] = array("id" => $lessonnote->id, "Subject" => $lessonnote->subject->name, "Title" => $lessonnote->title, "Status" => $status, "Filez" => $lessonnote->_file, "Perf" => $lsnperf->perf  );
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
     *  View Lessonnote of Teacher
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
                
                $status = $this->getLessonnoteStatus($lessonnote->id);
                $datablock[] = array("id" => $lessonnote->id, "Subject" => $lessonnote->subject->name, "Title" => $lessonnote->title, "Status" => $status, "Filez" => $lessonnote->_file, "Perf" => $lsnperf->perf, "Teacher"=>$lessonnote->teacher->fname." ".$lessonnote->teacher->lname,  );
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
                    WHERE l.tea_id IN ( SELECT id FROM teachers WHERE school_id = :sch ) AND t.policy = 0  AND l.period = :per AND c._submission != :sub          
                    " , 
                    [ "sch" =>  $teacher->school_id, "per" => $mycycle , "sub" => "1970-10-10 00:00:00"] );
                
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
                            WHERE l.tea_id IN ( SELECT id FROM teachers WHERE school_id = :sch ) AND t.qua = 0  AND l.period = :per AND c._approval != :sub          
                            " , 
                            [ "sch" =>  $teacher->school_id, "per" => $mycycle , "sub" => "1970-10-10 00:00:00"] );
                    // Poor performance
                    $lsn4 = DB::select(
                                "SELECT COUNT(l.ID) as myperf
                                FROM lessonnotes l 
                                INNER JOIN lsn_performances t ON t.lsn_id = l.id
                                INNER JOIN lessonnote_managements c ON c.lsn_id = l.id
                                WHERE l.tea_id IN ( SELECT id FROM teachers WHERE school_id = :sch ) AND t.perf <= 30  AND l.period = :per AND c._approval != :sub          
                                " , 
                                [ "sch" =>  $teacher->school_id, "per" => $mycycle , "sub" => "1970-10-10 00:00:00"] );
                     //  Delayed action by Somebody 
                    $lsn5 = DB::select(
                                    "SELECT COUNT(l.ID) as mydelay
                                    FROM lessonnotes l 
                                    INNER JOIN lsn_activities t ON t.lsn_id = l.id
                                    INNER JOIN lessonnote_managements c ON c.lsn_id = l.id
                                    WHERE l.tea_id IN ( SELECT id FROM teachers WHERE school_id = :sch ) AND t.slip = 0  AND l.period = :per AND c._approval != :sub          
                                    " , 
                                    [ "sch" =>  $teacher->school_id, "per" => $mycycle , "sub" => "1970-10-10 00:00:00"] );
                   
                   // latter we do this late closure A
                   $lsn6 = DB::select(
                                        "SELECT COUNT(l.ID) as lateclosure
                                        FROM lessonnotes l 
                                        INNER JOIN lsn_activities t ON t.lsn_id = l.id
                                        INNER JOIN lessonnote_managements c ON c.lsn_id = l.id
                                        WHERE l.tea_id IN ( SELECT id FROM teachers WHERE school_id = :sch ) AND l.period = :per AND c._approval != :sub AND c._exclosure >= CURRENT_DATE         
                                        " , 
                                        [ "sch" =>  $teacher->school_id, "per" => $mycycle , "sub" => "1970-10-10 00:00:00"] );

                    // latter we do this late closure B -- Not so sure about this
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
                    WHERE l.tea_id IN ( SELECT id FROM teachers WHERE school_id = :sch ) AND l.period = :per AND c._closure = :sub AND datediff(CURRENT_DATE , c._exclosure) >= 14 AND  c._exclosure != :sub2       
                    " , 
                    [ "sch" =>  $teacher->school_id, "per" => $mycycle , "sub" => "1970-10-10 00:00:00", "sub2" => "1970-10-10 00:00:00"] );

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
    public function changeStatusLessonnote($lsnid, $idx)
    {
        $idx = intval($idx);
        $lsn = LessonnoteManagement::where('lsn_id', $lsnid)->first();

        if ($idx === 1){ // rejected, so I resubmit
            $lsn->_submission = "1970-10-10 00:00:00";
            $lsn->_revert = "1970-10-10 00:00:00";
            $lsn->_resubmission = date('Y-m-d H:i:s');
            $lsn->save();
            $data['status'] = "Success";
            $data['message'] = "Your lessonnote management status has been changed to RESUBMITTED";
            return response()->json($data);
        }
        else if ($idx === 2){ //I launch it
            $lsn->_submission = "1970-10-10 00:00:00";
            $lsn->_resubmission = "1970-10-10 00:00:00";
            $lsn->_launch = date('Y-m-d H:i:s');
            $lsn->save();
            $data['status'] = "Success";
            $data['message'] = "Your lessonnote management status has been changed to LAUNCHED";
            return response()->json($data);
        }
        else if ($idx === 3){ //I close it
            $lsn->_submission = "1970-10-10 00:00:00";
            $lsn->_resubmission = "1970-10-10 00:00:00";
            $lsn->_launch = "1970-10-10 00:00:00";
            $lsn->_closure = date('Y-m-d H:i:s');
            $lsn->save();
            $data['status'] = "Success";
            $data['message'] = "Your lessonnote management status has been changed to CLOSED";
            return response()->json($data);
        }

        else if ($idx === 4){ //I reject it as a Principal
            $lsn->_submission = "1970-10-10 00:00:00";
            $lsn->_resubmission = "1970-10-10 00:00:00";            
            $lsn->_revert = date('Y-m-d H:i:s');
            $lsn->save();
            $data['status'] = "Success";
            $data['message'] = "Your lessonnote management status has been changed to REJECTED";
            return response()->json($data);
        }

        else if ($idx === 5){ //I approve it as a Principal
            $lsn->_submission = "1970-10-10 00:00:00";
            $lsn->_resubmission = "1970-10-10 00:00:00";
            $lsn->_revert = "1970-10-10 00:00:00";
            $lsn->_approval = date('Y-m-d H:i:s');
            $lsn->save();
            $data['status'] = "Success";
            $data['message'] = "Your lessonnote management status has been changed to APPROVED";
            return response()->json($data);
        }

        
       

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
