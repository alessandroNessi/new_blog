<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call('UsersTableSeeder');
        $posts=new Collection();
        $users=User::factory()->count(10)->create();
        foreach($users as $user){
            $posts=$posts->merge(Post::factory(['user_id'=>$user->id])->count(2)->create());
        }
        $usersIds=$users->pluck('id')->toArray();
        $postIds=$posts->pluck('id')->toArray();
        for($i=0;$i<50;$i++){
            Comment::factory(['user_id'=>$usersIds[array_rand($usersIds)],'post_id'=>$postIds[array_rand($postIds)]])->create();
        }
        // dd($postIds);
    }
}
