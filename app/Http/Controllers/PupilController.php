<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Pupil;

class PupilController extends Controller
{
    public function __construct()
    {
        $this->middleware('apiuser', ['only' => ['index','show']]);
        $this->middleware('apisuperuser', ['only' => ['store','update','destroy']]);
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
}
