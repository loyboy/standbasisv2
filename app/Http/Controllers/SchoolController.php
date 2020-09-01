<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\School;

class SchoolController extends Controller
{
//'index',
    public function __construct()
{
    $this->middleware('apiuser', ['only' => ['show']]);
    $this->middleware('apisuperuser', ['only' => ['store','update','destroy']]);
}
 /*** Helper Functions */
    public static function getSchoolName($schid){ //Helpher One
        $sch = School::where('id',$schid)->first();        
        return  $sch->name;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $schools = School::all();

        return response()->json($schools->toArray());
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
            'name' => 'required|unique:schools|max:255',
            '_type' => 'required|string',
            'town' => 'required|string',
            'lga' => 'required|string',
            'state' => 'required|string',
            'owner' => 'required|string',
            'polregion' => 'required|string',
            'operator' => 'required|string',
            'residence' => 'required|string',
            'address' => 'required|string',
        ]);

        if ($validator->fails()) {
           $data['status'] = "Failed";
           $data['message'] = $validator->errors();
           return response()->json($data);
        }

        $school = new School;
        $school->fill($request->all());
        $school->save();

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
        $school = School::findOrFail($id);
        $data['status'] = "Success";
        $data['message'] = $school;
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
            $school = School::findOrFail($id);
            $school->fill($request->all());
            $school->save();
    
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
            $school = School::findOrFail($id);
            $school->delete();
    
            $data['status'] = "Success";
            $data['message'] = $school;
    
            return response()->json($data);

        } catch (Exception $e) {
            $data['status'] = "Error";
            $data['message'] = $e->getMessage();
            return response()->json($data);
        }
    }
}
