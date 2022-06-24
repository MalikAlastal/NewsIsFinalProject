<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\NewsComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            'image_path' => 'mimes:jpeg,jpg,png,gif|sometimes',
            'comment' => 'required',
            'news_id' => 'required|exists:news,id',
        ], [],
            [
                'image_path' => 'الصورة',
                'comment' => 'نص التعليق',
                'news_id' => 'الخبر',
            ]
        );

        if ($validated->fails()) {
            $msg = 'خطأ في إدخال البيانات';
            $error = $validated->errors();

            return response()->json(compact('msg', 'error') , 422);
        }

        $image_path = $request->file('image_path')->store('public/image');

        if (!$image_path){
            $image_path = '' ;
        }

        $comment = new NewsComment();

        $comment->image_path = $image_path ;
        $comment->comment = $request->comment;
        $comment->news_id = $request->news_id;

        $comment->save();
        return response()->json(['msg' => 'تمت الإضافة بنجاح'] , 200);
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validated = Validator::make($request->all(), [
            'image_path' => 'mimes:jpeg,jpg,png,gif|sometimes',
            'comment' => 'required',
            'news_id' => 'required|exists:news,id',
        ], [],
            [
                'image_path' => 'الصورة',
                'comment' => 'نص التعليق',
                'news_id' => 'الخبر',
            ]
        );

        if ($validated->fails()) {
            $msg = 'خطأ في إدخال البيانات';
            $error = $validated->errors();

            return response()->json(compact('msg', 'error') , 422);
        }



        $comment = NewsComment::Find($id);

        if (!$comment){
            $msg = 'خطأ في تعديل البيانات';
            $error = 'التعليق الذي تحاول تعديله غير موجود';
            return response()->json(compact('msg', 'error') , 422);
        }

        $image_path = $request->image_path ;

        if ($image_path){
            $image_path = $request->file('image_path')->store('public/image');
            $comment->image_path = $image_path ;

        }

        $comment->comment = $request->comment;
        $comment->news_id = $request->news_id;

        $comment->save();
        return response()->json(['msg' => 'تمت التعديل بنجاح'] , 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $comment = NewsComment::where('id' , $id)->first();

        if (!$comment){
            $msg = 'خطأ في تعديل البيانات';
            $error = 'التعليق الذي تحاول حذفه غير موجود';
            return response()->json(compact('msg', 'error') , 422);
        }

        $comment->delete();
        return response()->json(['msg'=>'تم الحذف بنجاح']);
    }
}
