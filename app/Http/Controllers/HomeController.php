<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Memo;

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
        //メモデータを取得
        $memos = Memo::select('memos.*')
        ->where('user_id','=',\Auth::id())
        ->whereNull('deleted_at')
        ->orderBy('updated_at','DESC')
        ->get();

        return view('create',['memos' => $memos]);
    }

    public function store(Request $request){
        $posts = $request->all();

        Memo::insert([
            'content' => $posts['content'],
            'user_id' => \Auth::id(),
        ]);

        return redirect()->route('home');
    }

    public function edit($id){
        $memos = Memo::select('memos.*')
        ->where('user_id','=',\Auth::id())
        ->whereNull('deleted_at')
        ->orderBy('updated_at','DESC')
        ->get();

        $edit_memo = Memo::find($id);

        return view('edit',[
            'memos' => $memos,
            'edit_memo' => $edit_memo,
        ]);
    }

    public function update(Request $request){
        $posts = $request->all();

        Memo::where('id',$posts['memo_id'])->update([
            'content' => $posts['content'],
            'user_id' => \Auth::id(),
        ]);

        return redirect()->route('home');
    }
}
