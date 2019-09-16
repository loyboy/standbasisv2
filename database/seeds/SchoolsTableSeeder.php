<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SchoolsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('schools')->insert([
            'name' => "Standbasis Test School",
            '_type' => 'Primary',
            'town' => 'Uyo',
            'lga' => 'Uyo LGA',
            'state' => 'Akwa Ibom state',
            'owner' => 'Private',
            'polregion' => 'SS',
            'operator' => 'Standbasis Board',
            'residence' => 'Boarding',
            'address' => 'Plot 117 K Line, Ewet Housing Estate, Uyo, Akwa Ibom state',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at'=> date('Y-m-d H:i:s')
        ]);
    }
}
