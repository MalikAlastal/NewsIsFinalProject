<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\News;
use App\Models\NewsComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Nette\Utils\DateTime;

class NewsController extends Controller
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

    public function getAllNews(): \Illuminate\Http\JsonResponse
    {
        $news = News::with('news_categories' , 'news_comments')->get();

        return response()->json($news);
    }

    public function filterNews(Request $request): \Illuminate\Http\JsonResponse
    {

        $category_title = $request->category_title ;
        $news_date = $request->news_date ;
        $news_text = $request->news_text ;

        if (!$news_date){
            $news_date = News::orderBy('news_date' , 'asc')->first()->news_date;
        }

        $news = News::select('news.id' , 'news_date' , 'news_categories.category_title' ,'news_text' )->
        join('news_categories', 'news.category_id' , '=',  'news_categories.id')->
        when($category_title , function ($where) use($request , $category_title){
            $where-> where('news_categories.category_title','like' , '%'.$category_title.'%');
        })->
        when($news_date , function ($where) use($request , $news_date){
            $where->where('news_date' ,'>=', $news_date);
        })->
        when($news_text , function ($where) use($request , $news_text){
            $where->where('news_text','like' , '%'.$news_text.'%');
        })->
        paginate(5);

        return response()->json($news);
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
            'news_date' => 'required|date_format:Y-m-d|before_or_equal:',
            'news_text' => 'required',
            'category_id' => 'required|exists:news_categories,id',
        ], [],
            [
                'news_date' => 'تاريخ الخبر',
                'news_text' => 'نص الخبر',
                'category_id ' => 'تصنيف الخبر',
            ]
        );

        if ($validated->fails()) {
            $msg = 'خطأ في إدخال البيانات';
            $error = $validated->errors();

            return response()->json(compact('msg', 'error') , 422);
        }

        $news = new News() ;

        $news->news_date = $request->news_date ;
        $news->news_text = $request->news_text ;
        $news->category_id = $request->category_id ;

        $news->save() ;
        return response()->json(['msg' => 'تمت الإضافة بنجاح'] , 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $news = News::Find($id);

        return response()->json($news);
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
            'news_date' => 'required|date_format:Y-m-d|before_or_equal:',
            'news_text' => 'required',
            'category_id' => 'required|exists:news_categories,id',
        ], [],
            [
                'news_date' => 'تاريخ الخبر',
                'news_text' => 'نص الخبر',
                'category_id ' => 'تصنيف الخبر',
            ]
        );

        if ($validated->fails()) {
            $msg = 'خطأ في إدخال البيانات';
            $error = $validated->errors();

            return response()->json(compact('msg', 'error') , 422);
        }

        $news = News::Find($id) ;

        if (!$news){
            $msg = 'خطأ في تعديل البيانات';
            $error = 'الخبر الذي تحاول تعديله غير موجود';
            return response()->json(compact('msg', 'error') , 422);
        }

        $news->news_date = $request->news_date ;
        $news->news_text = $request->news_text ;
        $news->category_id = $request->category_id ;

        $news->save() ;
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
        NewsComment::where('news_id' , $id)->delete();
        $news = News::Find($id);

        $news->delete();
        return response()->json(['msg'=>'تم الحذف بنجاح']);
    }
}
