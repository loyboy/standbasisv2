<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\SchoolPolicy;

class SchoolpolicyController extends Controller
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
        $sch = SchoolPolicy::all();

        return response()->json($sch->toArray());
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
            'fair' => 'required|numeric',
            'late' => 'required|numeric',
            'signoff' => 'required|numeric',
            'accept_tea' => 'required|numeric',
            'accept_head' => 'required|numeric',
            'lsn_submit' => 'required|numeric',
            'lsn_resubmit' => 'required|numeric',
            'lsn_action' => 'required|numeric',
            'lsn_cycle' => 'required|numeric',
            'lsn_closure' => 'required|numeric',
            'sch_id' => 'required|numeric'
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
        $sch = SchoolPolicy::findOrFail($id);
        $data['status'] = "Success";
        $data['message'] = $sch;
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
            $sch = SchoolPolicy::findOrFail($id);
            $sch->fill($request->all());
            $sch->save();
    
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
            $sch = SchoolPolicy::findOrFail($id);
            $sch->delete();
    
            $data['status'] = "Success";
            $data['message'] = $sch;
    
            return response()->json($data);

        } catch (Exception $e) {
            $data['status'] = "Error";
            $data['message'] = $e->getMessage();
            return response()->json($data);
        }
    }
}
