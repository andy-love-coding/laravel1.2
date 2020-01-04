<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Status;
use Auth;

class StatusesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // store 和 destroy 都需要登录
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'content' => 'required|max:40'
        ]);

        // Atuh::user()->statuses()->create() 方法创建的微博，会自动与用户进行关联
        Auth::user()->statuses()->create([
            'content' => $request['content']
        ]);

        session()->flash('success', '发布成功！');
        return redirect()->back(); // 返回至上一次发出请求的页面
    }

    public function destroy(Status $status)
    {
        $this->authorize('destroy', $status);
        $status->delete();
        session()->flash('success', '微博已被成功删除！');
        return redirect()->back();
    }
}
