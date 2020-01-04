<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class FollowersTableSeeder extends Seeder
{

    public function run()
    {
        $users = User::all();
        $user = $users->first();
        $user_id = $user->id;

        // 1号用户 关注 其他所有用户
        $followers = $users->slice($user_id);
        $follower_ids = $followers->pluck('id')->toArray();
        $user->follow($follower_ids);

        // 其他所有用户 关注 1号用户
        foreach($followers as $follower) {
            $follower->follow($user_id);
        }
    }
}