<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Teacher;

class TeacherController extends Controller
{
//'index',
    public function __construct()
{
    $this->middleware('apiuser', ['only' => ['show']]);
    $this->middleware('apisuperuser', ['only' => ['store','update','destroy']]);
}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teachers = Teacher::all();

        return response()->json($teachers->toArray());
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
            'fname' => 'required|max:255',
            'lname' => 'required|string',
            'gender' => 'required|string|max:1',
            'agerange' => 'required|string',
            'bias' => 'required|string',
            'coursetype' => 'required|string',
            'qualification' => 'required|string',
            '_status' => 'required|numeric',
            '_type' => 'required|numeric',
            'school_id'=>'required|numeric'
        ]);

        if ($validator->fails()) {
           $data['status'] = "Failed";
           $data['message'] = $validator->errors();
           return response()->json($data);
        }

        $teacher = new Teacher;
        $teacher->fill($request->all());
        $teacher->save();

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
        $teacher = Teacher::findOrFail($id);
        $data['status'] = "Success";
        $data['message'] = $teacher;
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
            $teacher = Teacher::findOrFail($id);
            $teacher->fill($request->all());
            $teacher->save();
    
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
            $teacher = Teacher::findOrFail($id);
            $teacher->delete();
    
            $data['status'] = "Success";
            $data['message'] = $teacher;
    
            return response()->json($data);

        } catch (Exception $e) {
            $data['status'] = "Error";
            $data['message'] = $e->getMessage();
            return response()->json($data);
        }
    }
}
