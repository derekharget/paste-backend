<?php

namespace Database\Seeders;

use App\Models\Paste;
use App\Models\User;
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

        User::truncate();
        Paste::truncate();

        // $this->call('UsersTableSeeder');
        //User::factory(5)->create();
        Paste::factory(15)->create();
        // Number of users and posts to be created
//        $userCreate = 10;
//        $postsPerUser = 1;
//
//        while($userCreate > 0){
//            $user = User::factory(1)->create();
//            print_r($user);
//
//            Paste::factory($postsPerUser)->create([
//                'user_id' => $user->id
//            ]);
//
//            $userCreate--;
//        }


    }
}
