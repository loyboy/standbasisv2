<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\Attendance;
use App\User;
use App\Teacher;
use App\TimetableSch;
use App\Timetable;
use App\Subjectclass;
use App\Term;
use App\Enrollment;
use App\Rowcall;
use App\AttActivity;
use App\AttPerformance;
use App\Pupil;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('apiuser', ['only' => ['index','show','getTimeOfAttendance','getSubjectClass']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $attendance = Attendance::all();

        return response()->json($attendance->toArray());
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
        $att = Attendance::findOrFail($id);
        $data['status'] = "Success";
        $data['message'] = $att;
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
        $subclass = Subjectclass::where('tea_id', $teaid)->get();
        $datablock = array();
        foreach($subclass as $sub){
            $cat = array("SubClassID" => $sub->id, "ClassName" => $sub->classstream->title, "ClassId" => $sub->classstream->id , 
            "Subject" => $sub->subject->name, "SubjectId" => $sub->subject->id );
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
     * Get Subject Class combination of teacher with Time
     * @return \Illuminate\Http\Response
     */
    public function getSubjectClassWithTime($teaid)
    {
        $subjects = array(1 => "Monday", 2 => "Tuesday", 3 => "Wednesday" , 4 => "Thursday", 5 => "Friday", 6 => "Saturday", 7 => "Sunday");
        
        $subclassdel = TimetableSch::whereHas('subclass', function (Builder $query) use ($teaid) {
            $query->where('delegated', '=', $teaid);
        });

        $subclass = TimetableSch::whereHas('subclass', function (Builder $query) use ($teaid) {
            $query->where('tea_id', '=', $teaid)->where('delegated', '=', 0);
        })->union($subclassdel)->get();

        $datablock = array();
        if (empty($subclass)){
            $data['status'] = "Failed";
            $data['message'] = "This teacher has no Timetable assigned to him yet/ Incorrect Teacher ID";
            return response()->json($data);
        }else{            
            foreach($subclass as $sub){
                $cat = array("TimetableSchID" => $sub->id, "ClassName" => $sub->subclass->classstream->title."".$sub->subclass->classstream->ext , "ClassId" => $sub->subclass->classstream->id , 
                "Subject" => $sub->subclass->subject->name, "SubjectId" => $sub->subclass->subject->id, "Time" => $sub->timetable->_time, 
                "DayofWeek" => $subjects[$sub->timetable->_day], "TimeID" => $sub->timetable->id );
                $datablock[] = $cat;
            }            
            $data['status'] = "Success";
            $data['data'] = $datablock;
            return response()->json($data);
        }
       
    }
    /**
     * Get Time of Attendance if it has reached
     * @return \Illuminate\Http\Response
     */
    public function getTimeOfAttendance($apitoken,$classid)
    {
        //i usee token here to add some security to the request
        $user = User::where('api_token', $apitoken)->first();

        if ($user->teacher_id !== null && $user->_type === 8){
             //the day
           $theday = intval(date('N'));
           //the time
           $thetime = date('H:i:s');
           $thetime = strtotime($thetime);
            // get the class id from the Timetable sch
           $timesch = TimetableSch::findOrFail($classid); 
           $theclassid = $timesch->subclass->classstream->id;
            //timetable id
           $thetimeid = $timesch->timetable->id;
         
          //Get the subjects this teacher teaches
            $tea_subjects = $this->getTeacherSubject($user->teacher_id, $theclassid, $theday);
            
            if (!empty($tea_subjects)){
                foreach($tea_subjects as $ts){ 
                        $timeobj = Timetable::findOrFail($ts->TIME_ID);                   
                        $thetime2 = $timeobj->_time; //Expected time of Class
                        $thetime2 = strtotime($thetime2);                        
                        
                        //allow 1 hour before his time i.e 0 - 60 mins allowance
                        if ( (($thetime - $thetime2)/60) >= 0 && ($thetime - $thetime2)/60 <= 1440 ){
                            //get the current term
                           // $term = Term::where('_status',1)->where('school_id',)->first();
                           //Get teacher
                           //get Subject class
                           //Get term
                           //Get Enrollment information
                            $datablock = array();
                            $teacher = Teacher::findOrFail($ts->TEA_ID); 
                            $subclass = Subjectclass::findOrFail($ts->subclass);
                            $term = Term::where('_status',1)->where('school_id',$teacher->school_id)->first();
                            $enrolment = Enrollment::where('term_id',$term->id)->where('class_id',$ts->CLASS_ID)->get();
                            foreach ($enrolment as $enrol){
                                $datablock[] = array("PupilName" => $enrol->pupil->fname." ".$enrol->pupil->lname, "PupilID" => $enrol->pupil->id, "Present"=> 1, "Comment" => "No excuse", "EnrollmentID" => $enrol->id);
                            }
                            $data['status'] = "Success";
                            $data['message'] = "Yes, your class has already begun";
                            $data['data'] = array("Teacher" => $teacher->fname." ".$teacher->lname, "ExpTime" => $timeobj->_time, "Class" => $subclass->classstream->title, "Subject" => $subclass->subject->name, "SubClassID"  =>  $subclass->id, "TimeID" => $thetimeid, "Term" => $term->term, "TermID" => $term->id, "Pupils" => $datablock);
                            return response()->json($data);
                        }
                        else{
                            $data['status'] = "Failed";
                            $data['message'] = "Not yet Time for Your Class";
                            return response()->json($data);
                        }  
        
                }
            }
            else{
                $data['status'] = "Failed";
                $data['message'] = "This test teacher has no subject classes TODAY.";
                return response()->json($data);
            }
        }

        else if ($user->teacher_id !== null && $user->_type === 0){// Make sure it is a teacher
            //the day
           $theday = intval(date('N'));
           //the time
           $thetime = date('H:i:s');
           $thetime = strtotime($thetime);
            // get the class id from the Timetable sch
           $timesch = TimetableSch::findOrFail($classid); 
           $theclassid = $timesch->subclass->classstream->id;
            //timetable id
           $thetimeid = $timesch->timetable->id;
         
          //Get the subjects this teacher teaches
            $tea_subjects = $this->getTeacherSubject($user->teacher_id, $theclassid, $theday);
            if (!empty($tea_subjects)){
                foreach($tea_subjects as $ts){ 
                        $timeobj = Timetable::findOrFail($ts->TIME_ID);                   
                        $thetime2 = $timeobj->_time; //Expected time of Class
                        $thetime2 = strtotime($thetime2);
                        
                        
                        //allow 1 hour before his time i.e 0 - 60 mins allowance
                        if ( (($thetime - $thetime2)/60) >= 0 && ($thetime - $thetime2)/60 <= 60 ){
                            //get the current term
                           // $term = Term::where('_status',1)->where('school_id',)->first();
                           //Get teacher
                           //get Subject class
                           //Get term
                           //Get Enrollment information
                            $datablock = array();
                            $teacher = Teacher::findOrFail($ts->TEA_ID); 
                            $subclass = Subjectclass::findOrFail($ts->subclass);
                            $term = Term::where('_status',1)->where('school_id',$teacher->school_id)->first();
                            $enrolment = Enrollment::where('term_id',$term->id)->where('class_id',$ts->CLASS_ID)->get();
                            foreach ($enrolment as $enrol){
                                $datablock[] = array("PupilName" => $enrol->pupil->fname." ".$enrol->pupil->lname, "PupilID" => $enrol->pupil->id, "Present"=> 1, "Comment" => "No excuse", "EnrollmentID" => $enrol->id);
                            }
                            $data['status'] = "Success";
                            $data['message'] = "Yes, your class has already begun";
                            $data['data'] = array("Teacher" => $teacher->fname." ".$teacher->lname, "ExpTime" => $timeobj->_time, "Class" => $subclass->classstream->title, "Subject" => $subclass->subject->name, "SubClassID"  =>  $subclass->id, "TimeID" => $thetimeid, "Term" => $term->term, "TermID" => $term->id, "Pupils" => $datablock);
                            return response()->json($data);
                        }
                        else{
                            $data['status'] = "Failed";
                            $data['message'] = "Not yet Time for Your Class";
                            return response()->json($data);
                        }  
        
                }
            }
            else{
                $data['status'] = "Failed";
                $data['message'] = "This teacher has no subject classes TODAY.";
                return response()->json($data);
            }

        }
        else{
            $data['status'] = "Failed";
            $data['message'] = "This user is probably not a Teacher!, token incorrect";
            return response()->json($data);
        }
       
    }

    /**
     * Custom method for submiting an attendance
     * @return \Illuminate\Http\Response
     */
    public function submitAttendance(Request $request)
    {
        //$request->all()
        $subclass = $request->get('subclass');
        $timeid = $request->get('timeid');
        $termid = $request->get('termid');
        $pupils = json_decode($request->get('pupilsdata'));
        $base64 = "";
        if ( $request->file('image') !== null) {
            //$data = ;
            $type = 'png';
            $base64 = "data:image/" . $type . ";base64,". base64_encode(file_get_contents($request->file('image')));    
        }      

        $mydate = '%'.date('Y-m-d').'%';
        try {
        $attendance = Attendance::where('sub_class_id',$subclass)->where('_done',0)->where('term',$termid)->where('time_id',$timeid)->where('_date','LIKE',$mydate)->first();
        if ($attendance !== null){
            $policy = 0;///if it matches the policy of the school
            $fully = 0;///if it has come with the tables + photo 
            $qua = 0;///if it has met policy + fully to the very last
            $flag = 0;///if the no. of students present is ok
            $perf = 0;///the total of done + policY + fully + qua

           
            $timetable = TimeTable::findOrFail($timeid); 
            $expecttime = $timetable->_time;
            $expectedtime = strtotime($expecttime);
            $actualtime = strtotime(date('H:i:s'));

            if ( ( ($actualtime - $expectedtime) / 60) >= 0 && ($actualtime - $expectedtime)/60 <= 10 ){
                $policy = 1;
            }
            if ($base64 !== ""){
                $attendance->image = $base64;
                $fully = 1;// Presense of Image
            }
            $qua =  ($policy + $fully )/2 ;//Quality of Attendacne
            $perf =  (($policy + $fully + $qua )/3) * 100; // Performance of Attendance

            $attendance->_done = 1;
            $attendance->_date = date('Y-m-d H:i:s');           

            $totalpresent = 0; $totalabsent = 0; $totalpupil = 0;
            foreach($pupils as $p){
                
                if (intval($p->Present) !== 0){
                    $totalpresent++;
                }
                else{
                    $totalabsent++;
                }
                $totalpupil++;
                Rowcall::create([
                    'att_id' =>  $attendance->id,
                    'pup_id' => $p->PupilID,
                    'pupil_name' => $p->PupilName,
                    '_status' => $p->Present,
                    'remark' => $p->Comment                   
                ]);
            }

            $sch = $attendance->subclass->teacher->school_id;
            
            $principal = User::where('_type', 1)->whereHas('teacher', function (Builder $query) use ($sch) {
                $query->where('school_id', '=', $sch);
            })->first();

            $flag = ($totalpresent/$totalpupil) * 100;
            //owner now is the teacher --Put this to track his performance after taking this attendance
            AttPerformance::create([
                'att_id' =>  $attendance->id,
                'policy' => $policy,
                'fully' => $fully,
                'qua' => $qua,
                'flag' => $flag ,
                'perf' => $perf                  
            ]);

            if ($principal !== null){
                $expected = strtotime( "+12 hours", strtotime ( date('d-m-Y H:i') ) ); 
                $action = 0;
                //owner now is the head/principal --Put this so that we track his response time
                AttActivity::create([
                    'att_id' =>  $attendance->id,
                    'owner' => $principal->teacher_id,
                    'ownertype' => 'Principal',
                    'expected' => $expected,
                    'slip' => 0                   
                ]);              
            }

            $attendance->save();
            $data['status'] = "Success";
            $data['message'] = "Attendance has been submitted succesfully.";
            return response()->json($data);
        }
        else{
            $data['status'] = "Failed";
            $data['message'] = "That Attendance has been taken already/You are taking it at the wrong time.";
            return response()->json($data);
        } 

    } catch (Exception $e) {
        $data['status'] = "Failed";
        $data['message'] = $e->getMessage();
        return response()->json($data);
    }
/*
        $data['status'] = "Failed";
        $data['message'] = $pupils;
        return response()->json($data);*/

    }

     /**
     * Custom method for viewing an attendance
     * @return \Illuminate\Http\Response
     */
    public function viewAttendance(Request $request, $teaid)
    {
        $mydate = "";
       // $subclassx = "";
        
        if ( !is_null($request->get('_date')) ){
            $mydate = $request->get('_date');
        }

        $att = array();       
      
        if ($mydate !== ""){
            $att = Attendance::where('_date', 'LIKE', "%".$mydate."%")->whereHas('subclass', function (Builder $query) use ($teaid) {
                $query->where('tea_id', '=', $teaid)->where('_done', '=', 1);
            })->get();
        }
        else{
            $att = Attendance::whereHas('subclass', function (Builder $query) use ($teaid) {
                $query->where('tea_id', '=', $teaid)->where('_done', '=', 1);
            })->get();
        }
       
        $datablock = array();
        
        if (!empty($att)){
            foreach ($att as $attendance){ 

                $attperf = AttPerformance::where('att_id', $attendance->id)->first();
                
                $attactivity = AttActivity::where('att_id', $attendance->id)->first();
                
                $datablock[] = array("id" => $attendance->id,"Subclass" => $attendance->subclass->subject->name. " ".$attendance->subclass->classstream->title , "ExpTime" => $attendance->timetable->_time, "ActTime" => $attendance->_date, "Perf" => $attperf->flag, "Comment" => $attactivity->_comment, "Action" => $attactivity->_action );
            }

            $data['status'] = "Success";
            $data['message'] = "Your attendance data is provided....";
            $data['data'] = $datablock;
            return response()->json($data);
        }

        else { 
            $data['status'] = "Failed";
            $data['message'] = "Your attendance data is not available. Sorry";
            return response()->json($data);
        }
    }

      /**
     * Custom method for viewing an attendance by Principal
     * @return \Illuminate\Http\Response
     */
    public function viewAttendanceAll(Request $request, $teaid)
    {
       $mydate = "";
       // $subclassx = "";
      //$tea = Auth::user()->teacher_id;
       $teacher = Teacher::findOrFail($teaid); 
       $term = Term::where('_status',1)->where('school_id',$teacher->school_id)->first();
        
        if ( !is_null($request->get('_date')) ){
            $mydate = $request->get('_date');
        }

        $att = array();       
      
        if ($mydate !== ""){
            $att = Attendance::where('_date', 'LIKE', "%".$mydate."%")->where('term', '=', $term->term)->where('_done', '=', 1)->get();
        }
        else{
            $att = Attendance::where('term', '=', $term->term)->where('_done', '=', 1)->get();
        }
       
        $datablock = array();
        
        if (!empty($att)){
            
            $action = array( "1" => "Approved", "0" => "Declined", "" => "No action yet");
            foreach ($att as $attendance){ 
                $thecomment = ""; $theaction = "";
                $attperf = AttPerformance::where('att_id', $attendance->id)->first();
                $attavt = AttActivity::where('att_id', $attendance->id)->where('owner', $teaid)->whereNotNull('_action')->first();
                if ($attavt !== null){
                    $thecomment = $attavt->_comment;
                    $theaction = $attavt->_action;
                }
                $datablock[] = array("id" => $attendance->id,"Subclass" => $attendance->subclass->subject->name. " ".$attendance->subclass->classstream->title , "ExpTime" => $attendance->timetable->_time, "ActTime" => $attendance->_date, "Perf" => $attperf->flag, "Teacher" => $attendance->subclass->teacher->fname." ".$attendance->subclass->teacher->lname, 
                "Comment" => $thecomment, "Action" => $action[$theaction] );
            }
            $data['status'] = "Success";
            $data['message'] = "Your attendance data is provided....";
            $data['data'] = $datablock;
            return response()->json($data);
        }

        else { 
            $data['status'] = "Failed";
            $data['message'] = "Your attendance data is not available. Sorry";
            return response()->json($data);
        }
    }

    /**
     * 
     * @return \Illuminate\Http\Response
     */
    public function attendTo(Request $request, $attid)
    {
        try {
        $att = AttActivity::where('att_id', $attid)->first();
        $c = $request->get('comment');
        $d = $request->get('decision');
        
        $actual = strtotime ( date('d-m-Y H:i') );
        $expected = $att->expected;
        
        if ( $c !== "" ){
            $att->_comment = $c;
        }
        $att->_action = $d;
        $att->actual = $actual;

        $theslip = 0;
          
        if ($expected <  $actual) { 
         
            $theslip = 1;
        }

        $att->slip = $theslip;

        $att->save();

        $data['status'] = "Success";
        $data['message'] = "Your Comment has been added to that attendance from the Teacher.";
        return response()->json($data);
        } catch (Exception $e) {
            $data['status'] = "Failed";
            $data['message'] = $e->getMessage();
            return response()->json($data);
        }
    }    

    /**
     * 
     * @return \Illuminate\Http\Response
     */
    public function attendViewComment(Request $request, $attid)
    {
        try {
        $att = AttActivity::where('att_id', $attid)->first();

        $data['status'] = "Success";
        $data['data'] = array("Comment" => $att->_comment);
        
        $data['message'] = "Your Comment has been loaded for that teacher.";
        
        return response()->json($data);
        } catch (Exception $e) {
            $data['status'] = "Failed";
            $data['message'] = $e->getMessage();
            return response()->json($data);
        }
    }    

         /**
     * Custom method for getting attendance flags
     * @return \Illuminate\Http\Response
     */
    public function viewAttendanceFlags(Request $request, $teaid)
    {
        $mydate = "";
        // $subclassx = "";
       //$tea = Auth::user()->teacher_id;
        $teacher = Teacher::findOrFail($teaid); 
        $term = Term::where('_status',1)->where('school_id',$teacher->school_id)->first();
         
         if ( !is_null($request->get('_date')) ){
             $mydate = $request->get('_date');
         }
 
         $att = null;  // not done
         $att2 = array(); //late attendance    
         $att3 = null; //waivers available
         $att4 = array(); //attendance approval delay available
         $att5 = array(); //incomplete attendances
       
         if ($mydate !== ""){
             $att = Attendance::where('_date', 'LIKE', "%".$mydate."%")->where('term', '=', $term->term)->where('_done', '=', 0)->count();
             
             $att2 = DB::select(
                "SELECT COUNT(s.ID) as mylate
                FROM attendances s 
                INNER JOIN att_performances t ON t.att_id = s.id
                INNER JOIN subjectclasses c ON c.id = s.sub_class_id
                WHERE c.tea_id IN ( SELECT id FROM teachers WHERE school_id = :sch ) AND t.policy = 0  AND s._date LIKE :dat AND s._done = 1          
                " , 
                [ "sch" =>  $teacher->school_id, "dat" => "%".$mydate."%" ] );

                $att3 = Attendance::where('_date', 'LIKE', "%".$mydate."%")->where('term', '=', $term->term)->where('_done', '=', -1)->count();

                $att4 = DB::select(
                    "SELECT COUNT(s.ID) as mydelay
                    FROM attendances s 
                    INNER JOIN att_activities t ON t.att_id = s.id
                    INNER JOIN subjectclasses c ON c.id = s.sub_class_id
                    WHERE c.tea_id IN ( SELECT id FROM teachers WHERE school_id = :sch ) AND t._action IS NULL  AND s._date LIKE :dat AND s._done = 1            
                    " , 
                    [ "sch" =>  $teacher->school_id, "dat" => "%".$mydate."%" ] );

                $att5 = DB::select(
                        "SELECT COUNT(s.ID) as fully
                        FROM attendances s 
                        INNER JOIN att_performances t ON t.att_id = s.id
                        INNER JOIN subjectclasses c ON c.id = s.sub_class_id
                        WHERE c.tea_id IN ( SELECT id FROM teachers WHERE school_id = :sch ) AND t.fully = 0  AND s._date LIKE :dat AND s._done = 1            
                        " , 
                        [ "sch" =>  $teacher->school_id, "dat" => "%".$mydate."%" ] ); 
                        
                $att6 = DB::select(
                            "SELECT COUNT(s.ID) as absentstudent
                            FROM attendances s 
                            INNER JOIN rowcalls t ON t.att_id = s.id
                            INNER JOIN subjectclasses c ON c.id = s.sub_class_id
                            WHERE c.tea_id IN ( SELECT id FROM teachers WHERE school_id = :sch ) AND t._status = 0  AND s._date LIKE :dat AND s._done = 1            
                            " , 
                            [ "sch" =>  $teacher->school_id, "dat" => "%".$mydate."%" ] );

                $att7 = Attendance::where('_date', 'LIKE', "%".$mydate."%")->where('term', '=', $term->term)->count();            
         }
         else{
             // those not done at all
             $data['status'] = "Failed";
             $data['message'] = "No attendance data available yet because you have not chosen a Date...";             
             return response()->json($data);
         }
         
         if (!is_null($att) && !empty($att2) && !is_null($att3) && !empty($att4) && !empty($att5) && !empty($att6) )  {             
            
            $late = 0;
            $delay = 0;
            $fully = 0;
            $absentstudent = 0;
            foreach ($att2 as $at1){
                $late = intval($at1->mylate);
            }
            foreach ($att4 as $at1){
                $delay = intval($at1->mydelay);
            }
            foreach ($att5 as $at1){
                $fully = intval($at1->fully);
            }
            foreach ($att6 as $at1){
                $absentstudent = intval($at1->absentstudent);
            }
                 $datablock = array("_date" => $mydate, "TAbsent" => $att , "TTotal" => $att7 ,  "SAbsent" => $absentstudent, "LClass" => $late, "Incomplete" => $fully, "ADelay" => $delay );
             
             $data['status'] = "Success";
             $data['message'] = "Your attendance flag data is provided....";
             $data['data'] = $datablock;
             return response()->json($data);
         }
 
         else { 
             $data['status'] = "Failed";
             $data['message'] = "Your attendance data is not available. Sorry";
             return response()->json($data);
         }
    }

     /**
     * Custom method for viewing an attendance Log information for Teacher
     * @return \Illuminate\Http\Response
     */
    public function viewAttendanceLog($attid)
    { 
            $arrstatus = array(1=> '<b style="color: green;"> PRESENT </b>', 0 => '<b style="color: red;">ABSENT </b>');
          
            $att = Attendance::where('id', $attid)->first();
            $rowcall = Rowcall::where('att_id', $attid)->get();

            if (!is_null($att)){
                if ($att->image != null || $att->image != ""){
                $myimage = $att->image;                    
                }
                else {
                $myimage = "https://www.standbasis.com/images/no-image-avail.png";
                }
                 $myattdate = $att->_date;
            }

            $attactivity = AttActivity::where('att_id', $attid)->first();
            
            $myechostring = "<table class='table table-bordered'>
            <thead class='bg-danger' style='color: #fff;'>
            <tr><td>Name</td><td>Status</td><td>Remark</td></tr></thead><tbody>";
            foreach ($rowcall as $row){              
               // $myechostring .= "<tr><td>".$row->pupil->fname + " " + $row->pupil->lname ."</td><td>".$row->_status."</td><td>".$row->remark."</td></tr>";  
               $myechostring .= "<tr><td>".$row->pupil->fname." ".$row->pupil->lname ."</td><td>".$arrstatus[$row->_status]."</td><td>".$row->remark."</td></tr>";  
            }

            $myechostring .= "</tbody></table>";
            
            $mymsg = array(
                '_TITLE'=> "View Attendance on <b>".$myattdate."</b>",
                '_IMAGE'=> $myimage,
                '_COMMENT' => $attactivity !== null ? $attactivity->_comment : "",
                '_TEXT'=> $myechostring                
            );

            return response()->json($mymsg);

    }

    // The stautus of the Attendance Ward
    public function viewWardsAtt(Request $request, $parent)
    { 
        $mydate = "";

        $termval = array(1 => "First Term", 2 => "Second Term", 3 => "Third Term");

        if ( !is_null($request->get('_date')) ){
            $mydate = $request->get('_date');
        }

        try {

            $datablock = array();
            $headerblock = array();
          //  $pupil = Pupil::where('guardian', $parent)->get();
            
            $enrol = Enrollment::whereHas('pupil', function (Builder $query) use ($parent) {
                $query->where('guardian', $parent);
            })->get();
            
            if (!empty($enrol)){

                if ($mydate === ""){
                    $mydate = date("Y-m-d");
                }

                $dayofweek = date('N', strtotime($mydate));

                foreach ($enrol as $p){
                    $cls = $p->class_id;
                    $pup = $p->pupil_id;

                   
                    // first check if the term is active
                    $pupil = Pupil::where('id', $pup)->first();

                /*    $timesch = TimetableSch::whereHas('timetable', function (Builder $query) use ($dayofweek) {
                        $query->where('_day', '=', $dayofweek);
                    })->whereHas('subclass', function (Builder $query) use ($cls) {
                        $query->where('class_id', '=', $cls);
                    })->get();*/

                    $timesch = DB::select(
                        "SELECT s.sub_class , s.time_id
                        FROM timetable_sches s 
                        INNER JOIN subjectclasses t ON t.ID = s.SUB_CLASS 
                        INNER JOIN timetables t1 ON t1.ID = s.TIME_ID 
                        WHERE t1._day = :dayz AND t.class_id = :cls                       
                        " , 
                        [ "dayz" =>  $dayofweek, "cls" =>  $cls ] );
                    

                    if (!empty($timesch)){
                        $statuscomment = "No Attendance Taken Yet";
                        $remarkcomment = "Nil";
                        foreach ($timesch as $t){  
                            $sub = $t->sub_class;
                            $subclass = Subjectclass::where('id', $t->sub_class)->first();

                            $timetable = Timetable::where('id', $t->time_id)->first();
                            
                           $rowcall = Rowcall::whereHas('attendance', function (Builder $query) use ($sub,$mydate){
                                $query->where('_done', '=', '1')->where('sub_class_id', '=', $sub)->where('_date', 'LIKE', '%'.$mydate.'%');
                            })->whereHas('pupil', function (Builder $query) use ($pupil) {
                                $query->where('id', '=', $pupil->id);
                            })->first();

                          //  $rowcall = Rowcall::join('attendances', 'rowcalls.att_id', '=', 'attendances.id')->join('pupils', 'rowcalls.pup_id', '=', 'pupils.id')->where('attendances._done',1)->where('attendances.sub_class_id',$sub)->where('pupils.id',$pupil->id)->first();

                            if (!is_null($rowcall)){
                                $statuscomment = $rowcall->_status;
                                $remarkcomment = $rowcall->remark;
                            }                            
                           
                            $datablock[] = array("Pupil" => $pupil->fname.' '.$pupil->lname ,"Subclass" => $subclass->subject->name." ".$subclass->classstream->title , 
                            "Time" => $timetable->_time, "Timeid" => $timetable->id, "Present" => $statuscomment, "Remark" => $remarkcomment , "Rowcall" => $rowcall );
                  
                        }
                    }
                }

                //schoolname...parentname...Term name...Class name
                $teacher = Teacher::findOrFail($parent);
                $term = Term::where("school_id","=",$teacher->school_id)->first();
                $headerblock = array("Parent" => $teacher->fname." ".$teacher->lname, "School" => $teacher->school->name, "Term" => $termval[$term->term] );

                $data['status'] = "Success";
                $data['message'] = "Your attendance data is provided....";
                $data['data'] = array("table" => $datablock, "header" => $headerblock, "timesch" => $timesch, "Date" => $mydate);
                return response()->json($data);

             /*   $data['status'] = "Success";
                $data['data'] = array("Pupil" => $pupil);                
                $data['message'] = "Your Pupils are listed in the Daat field";
                
                return response()->json($data);*/
            }

            else{
                $data['status'] = "Failed";             
                $data['message'] = "No wards have been attached to this Parent";
                
                return response()->json($data);
            }

            } catch (Exception $e) {
                $data['status'] = "Failed";
                $data['message'] = $e->getMessage();
                return response()->json($data);
            }
    }

    /**
     * 
     * Get teacher teaching subjects 
     */
    private function getTeacherSubject($tea_id, $classid, $dayz){
        // timetable.affected must never be null for the query to run well
        $teasubjects = DB::select( 
        "SELECT s.CLASS_ID, s.ID as subclass, t.TIME_ID, s.TEA_ID, s.DELEGATED FROM subjectclasses s JOIN timetable_sches t ON t.SUB_CLASS = s.ID WHERE s.DELEGATED = 0 AND s.TEA_ID = :tea AND s.CLASS_ID = :cls AND t.TIME_ID IN (SELECT id FROM timetables ts WHERE ts._day = :dayz AND ts.affected NOT LIKE :tea2 ) 
            UNION 
        SELECT s.CLASS_ID, s.ID as subclass, t.TIME_ID, s.TEA_ID, s.DELEGATED FROM subjectclasses s JOIN timetable_sches t ON t.SUB_CLASS = s.ID WHERE s.DELEGATED = :tea3 AND s.CLASS_ID = :cls2 AND t.TIME_ID IN (SELECT id FROM timetables ts WHERE ts._day = :dayz2 AND ts.affected NOT LIKE :tea4 ) " 
        , [ "tea" => $tea_id , "tea2" => '%'.$tea_id.',%', "tea3" => $tea_id, "tea4" => '%'.$tea_id.',%', "dayz" => $dayz, "dayz2" => $dayz, "cls" => $classid, "cls2" => $classid ] );
        return $teasubjects;
    }
}
