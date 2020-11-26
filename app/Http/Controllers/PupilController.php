<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Pupil;
use App\Teacher;
use App\Subjectclass;
use App\Enrollment;
use App\Term;

class PupilController extends Controller
{
    public function __construct()
    {
        $this->middleware('apiuser', ['only' => ['index','show']]);
        $this->middleware('apisuperuser', ['only' => ['store','update','destroy']]);
    }
    
    //Helper Function
    public static function getAllPupilsInClass($cls)
    {
        $enrol = Enrollment::where('class_id',$cls)->get();
       
        return $enrol->toArray();
    }

    public static function getPupilName($enrolid){ //Helpher Three
        $enrol = Enrollment::where('id',$enrolid)->first(); 
       // return $enrol ? $enrol->pupil->fname." ".$enrol->pupil->lname : "";
      //  $data['status'] = "Success";
     //   $data['data'] =  $enrol ? $enrol->pupil->fname." ".$enrol->pupil->lname : "";
        return $enrol ? $enrol->pupil->fname." ".$enrol->pupil->lname : "";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pupils = Pupil::all();

        return response()->json($pupils->toArray());
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
            'fname' => 'required|string',
            'lname' => 'required|string',
            'gender' => 'required|string|max:1',
            'entry' => 'required|string',
            'status' => 'required|numeric',
            'school_id' => 'required|numeric'
        ]);

        if ($validator->fails()) {
           $data['status'] = "Failed";
           $data['message'] = $validator->errors();
           return response()->json($data);
        }

        $pupil = new Pupil;
        $pupil->fill($request->all());
        $pupil->save();

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
        $pupil = Pupil::findOrFail($id);
        $data['status'] = "Success";
        $data['message'] = $pupil;
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
            $pupil = Pupil::findOrFail($id);
            $pupil->fill($request->all());
            $pupil->save();
    
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
            $pupil = Pupil::findOrFail($id);
            $pupil->delete();
    
            $data['status'] = "Success";
            $data['message'] = $pupil;
    
            return response()->json($data);

        } catch (Exception $e) {
            $data['status'] = "Error";
            $data['message'] = $e->getMessage();
            return response()->json($data);
        }
    }

    /**
     * Get the pupil for a teacher
     * @return \Illuminate\Http\Response
     */
    public function getPupilForTeacher($teaid)
    {
        try{
            $tea = Teacher::findOrFail($teaid);
            $datablock= array();
            $subclass = Subjectclass::where('tea_id',$teaid)->get();
            $term = Term::where('school_id',$tea->school_id)->where('_status',1)->first();

            foreach ($subclass as $sc){ 
                $enrol = Enrollment::where('class_id',$sc->class_id)->where('term_id',$term->term)->get();
                foreach ($enrol as $en){
                    $datablock[] = array("PupilName" => $en->pupil->fname." ".$en->pupil->lname, "PupilId" => $en->pupil->id, "ClassID" => $en->classtream->id , "ClassName" => $en->classtream->title );
                }
            }

            $data['status'] = "Success";
            $data['message'] = "Pupil data is good xxx";
            $data['data'] =  $datablock;

            return response()->json($data);
        } catch (Exception $e) {
                $data['status'] = "Error";
                $data['message'] = $e->getMessage();
                return response()->json($data);
        }
    }

    
    /**
     * Get the Class for a teacher
     * @return \Illuminate\Http\Response
     */
    public function getClassForTeacher($teaid)
    {
        try{
            $tea = Teacher::findOrFail($teaid);
            $datablock= array();
           // $subclass = SubjectClass::where('tea_id',$teaid)->get();
            $term = Term::where('school_id',$tea->school_id)->where('_status',1)->first();

            //foreach ($subclass as $sc){ 
                $enrol = DB::select("SELECT cs.title as title, su.class_id  FROM subjectclasses su 
                INNER JOIN class_streams cs ON su.class_id = cs.id 
                INNER JOIN subjects s ON s.id = su.sub_id
                WHERE su.tea_id = :tea
                GROUP BY (su.class_id)
                ",['tea' => $teaid]);

                //$enrol = Enrollment::where('class_id',$sc->class_id)->where('term_id',$term->term)->groupBy('class_id')->get();
                foreach ($enrol as $en){
                    $datablock[] = array("ClassID" => $en->class_id , "ClassName" => $en->title );
                }
           // }

            $data['status'] = "Success";
            $data['message'] = "Pupil data is good";
            $data['data'] = $datablock;
            return response()->json($data);
        } catch (Exception $e) {
                $data['status'] = "Error";
                $data['message'] = $e->getMessage();
                return response()->json($data);
        }
    }


}
