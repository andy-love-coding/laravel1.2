<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\User;
use App\Models\Status;

class StatusPolicy
{
    use HandlesAuthorization;

    // 第一个参数默认为当前登录用户实例，第二个参数则为要进行授权的入参
    // 调用时，默认情况下，我们 不需要 传递当前登录用户至该方法内
    public function destroy(User $user, Status $status)
    {
        return $user->id === $status->user_id;
    }
}
