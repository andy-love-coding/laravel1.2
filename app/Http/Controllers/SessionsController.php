<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class SessionsController extends Controller
{
    public function __construct()
    {
        // 只让未登录用户访问：登录页面
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }
    public function create()
    {
        return view('sessions.create');
    }

    public function store(Request $request)
    {
        $credentials = $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if(Auth::attempt($credentials, $request->has('remember'))) {
            // 登录成功
            session()->flash('success', '欢迎回来！');

            // intended() 友好转向（登录成功后跳转到上一次尝试访问的页面），并提供一个默认地址
            $fallback = route('users.show', Auth::user());
            return redirect()->intended($fallback); 
            // return redirect()->route('users.show', [Auth::user()]);
        } else {
            // 登录失败
            session()->flash('danger', '很抱歉，您的邮箱和密码不匹配');
            return redirect()->back()->withInput(); // 使用 withInput() 后模板里 old('email') 将能获取到上一次用户提交的内容
        }
    }

    public function destroy()
    {
        Auth::logout();
        session()->flash('success', '您已成功退出');
        return redirect('login');
    }
}
