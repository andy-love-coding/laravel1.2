<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;
    // 1.创建授权策略：php artisan make:policy UserPolicy
    // 2.自动注册授权策略（即根据model找policy）：在 app/Providers/AuthServiceProvider.php 自动注册
    // 3.使用策略：$this->authorize('update', $user); 第一个为授权策略的名称，第二个为进行授权验证的数据。
 
    // 第一个参数默认为当前登录用户实例，第二个参数则为要进行授权的用户实例
    // 调用时，默认情况下，我们 不需要 传递当前登录用户至该方法内
    public function update(User $currentUser, User $user)
    {
        // 只能自己更新自己
        return $currentUser->id === $user->id;
    }

    public function destroy(User $currentUser, User $user)
    {
        // 只有管理可以删除，并且自己不能删除自己
        return $currentUser->is_admin && $currentUser->id !== $user->id;
    }
}
