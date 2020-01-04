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
}
