<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\ClassStream;

class ClassStreamController extends Controller
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
        $classes = ClassStream::all();

        return response()->json($classes->toArray());
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
            'title' => 'required|string',
            'category' => 'required|numeric',
            'school_id' => 'required|numeric',
            'ext' => 'string'
        ]);

        if ($validator->fails()) {
           $data['status'] = "Failed";
           $data['message'] = $validator->errors();
           return response()->json($data);
        }

        $classx = new ClassStream;
        $classx->fill($request->all());
        $classx->save();

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
        $classx = ClassStream::findOrFail($id);
        $data['status'] = "Success";
        $data['message'] = $classx;
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
            $classx = ClassStream::findOrFail($id);
            $classx->fill($request->all());
            $classx->save();
    
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
            $classx = ClassStream::findOrFail($id);
            $classx->delete();
    
            $data['status'] = "Success";
            $data['message'] = $classx;
    
            return response()->json($data);

        } catch (Exception $e) {
            $data['status'] = "Error";
            $data['message'] = $e->getMessage();
            return response()->json($data);
        }
    }
}
