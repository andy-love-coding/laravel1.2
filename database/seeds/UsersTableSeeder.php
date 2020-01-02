<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{

    public function run()
    {
        $users = factory(User::class)->times(50)->make();
        // makeVisible 方法临时显示 User 模型里指定的隐藏属性 $hidden，
        // 接着我们使用了 insert 方法来将生成假用户列表数据批量插入到数据库中
        User::insert($users->makeVisible(['password','remember_token'])->toArray());

        $user = User::find(1);
        $user->name = 'Andy';
        $user->email = '000@qq.com';
        $user->save();
    }
}
