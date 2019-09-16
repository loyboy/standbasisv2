<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Teacher;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
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
        $user = User::all();

        return response()->json($user->toArray());
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
            'username' => 'required|unique:users|max:255',
            'password' => 'required|string|min:6',
            'name' => 'required|string',
            '_type' => 'required|numeric',
            '_status' => 'required|numeric',
            'teacher_id' => 'required|numeric|unique:users',
            
        ]);

        if ($validator->fails()) {
           $data['status'] = "Failed";
           $data['message'] = $validator->errors();
           return response()->json($data);
        }

        /*$user = new User;
        $user->fill($request->all());
        $user->save();*/
        User::create([
            'username' => $request->input('username'),
            '_type' => $request->input('_type'),
            'teacher_id' => $request->input('teacher_id'),
            'name' => $request->input('name'),
            'password' => Hash::make($request->input('password')),
            'api_token' => Str::random(60),
        ]);

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
        $user = User::findOrFail($id);
        $data['status'] = "Success";
        $data['message'] = $user;
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
            $user = User::findOrFail($id);
            $user->fill($request->all());
            $user->save();
    
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
            $user = User::findOrFail($id);
            $user->delete();
    
            $data['status'] = "Success";
            $data['message'] = $user;
    
            return response()->json($data);

        } catch (Exception $e) {
            $data['status'] = "Error";
            $data['message'] = $e->getMessage();
            return response()->json($data);
        }
    }

    /////////////////////////////////////////Custom functions Here
     /**
     * 
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $u = $request->input('username');
        $p = $request->input('password');
        if (Auth::attempt(['username' => $u, 'password' => $p])) {
            $user = User::where('username', $u)->first();
            if ($user->_type === 0){
                $data['status'] = "Success";
                $data['token'] = $user->api_token;
                $teacher = Teacher::where('id', $user->teacher_id)->first();
                $data['teacher'] = $teacher;
                return response()->json($data);
            }
            if ($user->_type === 1){//if a Head of School
                $data['status'] = "Success";
                $data['token'] = $user->api_token;
                $head = Teacher::where('id', $user->teacher_id)->first();
                $data['head'] = $head;
                return response()->json($data);
            }
            if ($user->_type === 2){//if a Supervisor of School
                $data['status'] = "Success";
                $data['token'] = $user->api_token;
                $supervisor = Teacher::where('id', $user->teacher_id)->first();
                $data['supervisor'] = $supervisor;
                return response()->json($data);
            }
            if ($user->_type === 4){//if a Parent of a School
                $data['status'] = "Success";
                $data['token'] = $user->api_token;
                $parent = Teacher::where('id', $user->teacher_id)->first();
                $data['parent'] = $parent;
                return response()->json($data);
            }
            else{
                $data['status'] = "Success";
                $data['token'] = $user->api_token;
                return response()->json($data);
            }           
        }
        else{
            $data['status'] = "Error";
            $data['message'] = "Your login details are incorrect";
            return response()->json($data);
        }
    }
}
