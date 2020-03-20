<?php

declare(strict_types=1);

use Hyperf\Database\Seeders\Seeder;

class CreateUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //创建管理员
        \App\Model\Blog\User::query()->create([
            'name' => '小希',
            'email' => '13177839316@163.com',
            'password' => password_hash('123456'.config('halt'),PASSWORD_BCRYPT),
            'is_admin' => 1,
        ]);

        // 创建标签
        \App\Model\Blog\Tag::query()->create(['name' => 'Laravel']);
        \App\Model\Blog\Tag::query()->create(['name'=>'Hyperf','type'=>1]);

        //创建文章
       \App\Model\Blog\Article::query()->create([
            'title'   => 'Fast',
            'content' => 'this is a fast blog',
            'tag_id'  => 1,
        ]);
        \App\Model\Blog\Article::query()->create([
            'title'   => 'Fast hyperf',
            'content' => 'this is a fast blog',
            'tag_id'  => 2,
        ]);
    }
}
