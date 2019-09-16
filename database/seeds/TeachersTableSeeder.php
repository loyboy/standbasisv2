<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TeachersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    
    public function run()
    {
        DB::table('teachers')->insert([
            "fname"=> "Standbasis Fname",
            "lname"=> "Admin",
            "gender"=> "M",
            "agerange"=> "15-40",
            "bias"=> "Secondary",
           
            "coursetype"=> "Professional",
            "qualification"=> "WASSCE",
            "_status"=> 1,
            "_type"=> 1,
            "school_id"=> 2,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
}
