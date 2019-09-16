<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => "Standbasis Admin",
            'username' => "Standbasis One",
            '_type' => 9,
            '_status' => 1,
            'teacher_id' => 22,
            'password' => Hash::make('standbasis_one'),
            'api_token' => 'e6yheu372j2u383ik4i494k412ap10oslfkio21mxc',
            'remember_token' => Str::random(10),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at'=> date('Y-m-d H:i:s')
        ]);
    }
}
