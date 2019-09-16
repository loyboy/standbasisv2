<?php

use Illuminate\Database\Seeder;
//php artisan make:seeder UsersTableSeeder
//First run composer dump-autoload
// Then php artisan db:seed (runs DatabaseSeeder Class only)

// php artisan db:seed --class=UsersTableSeeder

//php artisan migrate:refresh --seed (to add the seeds into the database)

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         //$this->call(UsersTableSeeder::class);
    }
}
