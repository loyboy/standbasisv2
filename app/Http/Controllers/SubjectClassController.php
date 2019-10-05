<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Subjectclass;

class SubjectClassController extends Controller
{
    public function __construct()
    {
        $this->middleware('apiuser', ['only' => ['show','findTeaSubjects']]);
        $this->middleware('apisuperuser', ['only' => ['store','update','destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subjects = Subjectclass::all();

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
            'tea_id' => 'required|numeric',
            'class_id' => 'required|numeric',
            'sub_id' => 'required|numeric',
            'title' => 'required|string',
            'delegated' => 'sometimes|numeric',
        ]);

        if ($validator->fails()) {
           $data['status'] = "Failed";
           $data['message'] = $validator->errors();
           return response()->json($data);
        }

        $subject = new Subjectclass;
        $subject->fill($request->all());
        $subject->save();

        $data['status'] = "Success";
        $data['data'] = $request->all();

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
       
            $subject =  Subjectclass::findOrFail($id);
            $data['status'] = "Success";
            $data['data'] = $subject;
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
            $subject = Subjectclass::findOrFail($id);
            $subject->fill($request->all());
            $subject->save();
    
            $data['status'] = "Success";
            $data['data'] = $request->all();
    
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
            $subject = Subjectclass::findOrFail($id);
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

    /////Custom methods
    public function findTeaSubjects($id, $type)
    {

        if ($type === 'normal'){
            $subject =  Subjectclass::where("tea_id", $id)->where("delegated", 0)->get();
            $datablock = array();
            if (count($subject) <= 0){
                $data['status'] = "Failed";
                $data['message'] = "This teacher has no Subject Class assigned to him yet/ Incorrect Teacher ID";
                return response()->json($data);
            }else{       
                     
                foreach($subject as $sub){
                    if ($sub->delegated !== 0){
                        $data['status'] = "Failed";
                        $data['message'] = "This Subject Class has been given to another Teacher";
                        return response()->json($data);
                    }
                    $cat = array("ClassName" => $sub->classstream->title, "ClassId" => $sub->classstream->id , 
                    "Subject" => $sub->subject->name, "SubjectId" => $sub->subject->id, "SubjectClassId" => $sub->id );
                    $datablock[] = $cat;
                }            
                $data['status'] = "Success";
                $data['data'] = $datablock;
                return response()->json($data);
            }
        }
        else if ($type === 'delegated'){
            $subject =  Subjectclass::where("delegated",$id)->get();

            if (empty($subject)){
                $data['status'] = "Failed";
                $data['message'] = "This teacher has no Delegated Subject Class assigned to him yet/ Incorrect Teacher ID";
                return response()->json($data);
            }else{            
                foreach($subject as $sub){
                    $cat = array("ClassName" => $sub->classstream->title, "ClassId" => $sub->classstream->id , 
                    "Subject" => $sub->subject->name, "SubjectId" => $sub->subject->id );
                    $datablock[] = $cat;
                }            
                $data['status'] = "Success";
                $data['data'] = $datablock;
                return response()->json($data);
            }
        }
    }
}
