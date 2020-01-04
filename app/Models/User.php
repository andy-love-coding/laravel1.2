<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    // boot 方法会在用户模型类完成初始化之后进行加载，因此我们对事件的「监听」需要放在该方法中。
    public static function boot()
    {
        parent::boot();

        // 用户激活步骤
        // 1.在注册用户时，即用户模型生成之前，随机生成激活码
        // 2.注册成功后，发送激活邮件，邮件中包含激活链接，链接中含激活码
        // 3.点击激活链接，清除该用户模型的激活码，激活该用户
        
        // creating 用于「监听」模型被创建之前的事件
        static::creating(function($user) {
            $user->activation_token = Str::random(10);
        });
    }

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function gravatar($size = '100')
    {
        $hash = md5(strtolower(trim($this->attribute['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }

    public function statuses()
    {
        return $this->hasMany(Status::class);
    }

    public function feed()
    {
        return $this->statuses()
                    ->orderBy('created_at', 'desc');
    }

    // 本model为：关注人（博主）。关注人的粉丝列表
    public function followers()
    {
        // $this-> belongsToMany(关联表model，中间表表名，中间表中本model的关联ID，中间表中关联model的关联ID);
        // 第二参数为中间表表名，第三参数为本model关联键user_id，第四参数为关联model中关联键follower_id
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'follower_id');
    }

    // 本model为：粉丝。粉丝的关注人列表
    public function followings()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'user_id');
    }

    // 关注
    public function follow($user_ids)
    {
        if (!is_array($user_ids)) {
            $user_ids = compact('user_ids');
        }
        $this->followings()->sync($user_ids, false);
    }

    // 取关
    public function unfollow($user_ids)
    {
        if (!is_array($user_ids)) {
            $user_ids = compact('user_ids');
        }
        $this->followings()->detach($user_ids);
    }

    // 是否关注了
    public function isFollowing($user_id)
    {
        return $this->followings()->contains($user_id);
    }
}
