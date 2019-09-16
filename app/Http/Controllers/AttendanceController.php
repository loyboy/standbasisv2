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
        
        $subclass = TimetableSch::whereHas('subclass', function (Builder $query) use ($teaid) {
            $query->where('tea_id', '=', $teaid);
        })->get();

        $datablock = array();
        if (empty($subclass)){
            $data['status'] = "Failed";
            $data['message'] = "This teacher has no Timetable assigned to him yet/ Incorrect Teacher ID";
            return response()->json($data);
        }else{            
            foreach($subclass as $sub){
                $cat = array("SubClassID" => $sub->subclass->id, "ClassName" => $sub->subclass->classstream->title, "ClassId" => $sub->subclass->classstream->id , 
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
        if ($user->teacher_id !== null && $user->_type === 0){// Make sure it is a teacher
            //the day
           $theday = intval(date('N'));
           //the time
           $thetime = date('H:i:s');
           $thetime = strtotime($thetime);

          //Get the subjects this teacher teaches
            $tea_subjects = $this->getTeacherSubject($user->teacher_id, $classid, $theday);
            if (!empty($tea_subjects)){
                foreach($tea_subjects as $ts){ 
                        $timeobj = Timetable::findOrFail($ts->TIME_ID);                   
                        $thetime2 = $timeobj->_time; //Expected time of Class
                        $thetime2 = strtotime($thetime2);
                        //
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
                            $data['data'] = array("Teacher" => $teacher->fname." ".$teacher->lname, "Class" => $subclass->classstream->title, "Subject" => $subclass->subject->name, "Term" => $term->term, "TermID" => $term->id, "Pupils" => $datablock);
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

        $mydate = '%'.date('Y-m-d').'%';
        $attendance = Attendance::where('sub_class_id',$subclass)->where('_done',0)->where('term',$termid)->where('time_id',$timeid)->where('_date','LIKE',$mydate)->first();
        if ($attendance !== null){
            if($request->get('image') !== null){
                $attendance->image = $request->get('image');
            }
            $attendance->_done = 1;
            $attendance->save();

            foreach($pupils as $p){
                Rowcall::create([
                    'att_id' =>  $attendance->id,
                    'pup_id' => $p->PupilID,
                    'pupil_name' => $p->PupilName,
                    '_status' => $p->Present,
                    'remark' => $p->Comment                   
                ]);
            }
            $data['status'] = "Sucess";
            $data['message'] = "Attendance has been submitted succesfully.";
            return response()->json($data);
        }
        else{
            $data['status'] = "Failed";
            $data['message'] = "That Attendance has been taken already/You are taking it at the wrong time.";
            return response()->json($data);
        } 
/*
        $data['status'] = "Failed";
        $data['message'] = $pupils;
        return response()->json($data);*/

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
        , [ "tea" => $tea_id , "tea2" => '%'.$tea_id.'%', "tea3" => $tea_id, "tea4" => '%'.$tea_id.'%', "dayz" => $dayz, "dayz2" => $dayz, "cls" => $classid, "cls2" => $classid ] );
        return $teasubjects;
    }
}
