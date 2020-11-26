<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Term;
use App\Teacher;

class TermController extends Controller
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
        $term = Term::all();

        return response()->json($term->toArray());
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
            'term' => 'required|numeric',
            'session' => 'required|string',
            'resumedate' => 'string',
            'status' => 'numeric',
            'holiday' => 'string',
            'school_id' => 'required|numeric'
        ]);

        if ($validator->fails()) {
           $data['status'] = "Failed";
           $data['message'] = $validator->errors();
           return response()->json($data);
        }

        $timex = new Term;
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
        $timex = Term::findOrFail($id);
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
            $timex = Term::findOrFail($id);
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
            $timex = Term::findOrFail($id);
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

     /**
     * Gt the current Term date of resumption
     * @return \Illuminate\Http\Response
     */
    public function getTermDate($teaid)
    {
        try{
        $datablock = array();
        $tea = Teacher::findOrFail($teaid);
        $terms = Term::where('school_id',$tea->school_id)->get();
        foreach($terms as $t){
            $datablock[] = array("Term" => $t->term , "Termid" => $t->id );
        }
        $data['status'] = "Success";
        $data['data'] =  $datablock;
        return response()->json($data);
        } catch (Exception $e) {
            $data['status'] = "Error";
            $data['message'] = $e->getMessage();
            return response()->json($data);
        }
    }

    
}
