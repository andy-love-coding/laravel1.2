<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // php artisan migrate:refresh // 重置数据库
        // php artisan db:seed // 执行数据填充

        // php artisan db:seed --class=UsersTableSeeder // 单独指定填充文件，进行数据填充

        // php artisan migrate:refresh --seed // 重置与填充一起来
        
        Model::unguard();

        $this->call(UsersTableSeeder::class);
        $this->call(StatusesTableSeeder::class);

        Model::reguard();
    }
}
