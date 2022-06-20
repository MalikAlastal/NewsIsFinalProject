<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $admins = Admin::get();

        return response()->json($admins);
    }

    public function login(Request $request){

        $validated = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ], [],
            [
                'email' => 'الإيميل',
                'password' => 'كلمة المرور',
            ]
        );

        if ($validated->fails()){
            $msg = 'خطأ في إدخال البيانات';
            $error = $validated->errors();

            return response()->json(compact('msg', 'error'));
        }

        $admin  = Admin::where('email' , $request->email)->first() ;

        if (!$admin){
            return response()->json(['message'=>'الإيميل غير صحيح'] , 401);
        }

        if (Hash::check($request->password , $admin->password )){
            $token = $admin->createToken('Laravel Password Grant Client')->accessToken;
            $response = ['token'=>$token];
            return response()->json($response , 200);
        }
        else {
            $response = ['message'=>'خطأ في اسم المستخدم أو كلمة المرور'];
            return response()->json($response , 401 );
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:admins',
            'password' => 'required',
            'gender' => 'required|in:male,female'
        ], [],
            [
                'name' => 'الاسم',
                'email' => 'الإيميل',
                'password' => 'كلمة المرور',
                'gender' => 'الجنس'
            ]
        );

        if ($validated->fails()) {
            $msg = 'خطأ في إدخال البيانات';
            $error = $validated->errors();

            return response()->json(compact('msg', 'error'));
        }

        $admin = new Admin();
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->password = Hash::make($request->password);
        $admin->gender = $request->gender;

        $admin->save();
        return response()->json(['msg' => 'تمت الإضافة بنجاح']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
