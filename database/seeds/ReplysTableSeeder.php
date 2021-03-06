<?php

use Illuminate\Database\Seeder;
use App\Models\Reply;
use App\Models\User;
use App\Models\Topic;

class ReplysTableSeeder extends Seeder
{
    public function run()
    {
        // 所有用户ID数组 [1,2,3]
        $user_ids = User::all()->pluck('id')->toArray();

        // 所有话题ID数组 [1,2,3]
        $topic_ids = Topic::all()->pluck('id')->toArray();

        // 获取Faker 实例
        $faker = app(Faker\Generator::class);

        $replys = factory(Reply::class)
            ->times(50)
            ->make()
            ->each(function ($reply, $index) use ($user_ids,$topic_ids,$faker) {

                $reply->user_id = $faker->randomElement($user_ids);
                $reply->topic_id = $faker->randomElement($topic_ids);
        });

        Reply::insert($replys->toArray());
    }

}

