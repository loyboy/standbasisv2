<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\TimetableSch;

class TimeTableSchController extends Controller
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
        $table = TimeTableSch::all();

        return response()->json($table->toArray());
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
            'sub_class' => 'required|numeric',
            'time_id' => 'required|numeric',
            'comment' => 'string'
        ]);

        if ($validator->fails()) {
           $data['status'] = "Failed";
           $data['message'] = $validator->errors();
           return response()->json($data);
        }

        $timex = new TimeTableSch;
        $timex->fill($request->all());
        $timex->save();

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
        $timex = TimetableSch::findOrFail($id);
        $data['status'] = "Success";
        $data['message'] = $timex;
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
            $timex = TimetableSch::findOrFail($id);
            $timex->fill($request->all());
            $timex->save();
    
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
            $timex = TimetableSch::findOrFail($id);
            $timex->delete();
    
            $data['status'] = "Success";
            $data['message'] = $timex;
    
            return response()->json($data);

        } catch (Exception $e) {
            $data['status'] = "Error";
            $data['message'] = $e->getMessage();
            return response()->json($data);
        }
    }
}
