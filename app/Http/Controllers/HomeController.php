<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Memo;
use App\Models\Tag;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('create');
    }

    public function create()
    {
        return view('create');
    }

    public function store(Request $request)
    {
        $data = $request->all();
        // dd($data);
        // POSTされたデータをDB（memosテーブル）に挿入
        // MEMOモデルにDBへ保存する命令を出す

        // 先にタグ情報をINSERTする
        // 同じタグがすでに存在するか確認
        $exist_tag = Tag::where('name', $data['tag'])->where('user_id', $data['user_id'])
            ->first();
       
        if(empty($exist_tag)) {
            $tag_id = Tag::insertGetId(array(
                'name' => $data['tag'],
                'user_id' => $data['user_id']
            ));
        } else {
            $tag_id = $exist_tag['id'];
        }

        $memo_id = Memo::insertGetId(array(
            'content' => $data['content'],
            'user_id' => $data['user_id'], 
            'tag_id' => $tag_id,
            'status' => 1
        ));
        
        // リダイレクト処理
        return redirect()->route('home');
    }

    public function edit($id){
        // 該当するIDのメモをデータベースから取得
        $user = \Auth::user();
        $memo = Memo::where('status', 1)->where('id', $id)->where('user_id', $user['id'])
          ->first();
    
        //取得したメモをViewに渡す
        return view('edit',compact('memo'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        Memo::where('id', $id)->update(array(
            'content' => $data['content'],
            'tag_id' => $data['tag_id']
        ));
        
        // リダイレクト処理
        return redirect()->route('home');
    }

    public function delete(Request $request, $id)
    {
        $data = $request->all();
        Memo::where('id', $id)->update(array(
            'status' => 2,
        ));
        
        // リダイレクト処理
        return redirect()->route('home')->with('success', 'メモを削除しました');
    }
}
