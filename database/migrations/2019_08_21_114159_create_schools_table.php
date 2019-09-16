<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchoolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('_type');
            $table->string('town');
            $table->string('lga');
            $table->string('state');
            $table->string('owner');
            $table->char('polregion',2);
            $table->string('faith')->nullable();
            $table->string('operator');
            $table->string('gender')->nullable(); // mixed or same
            $table->string('residence'); // boarding / not
            $table->integer('population')->nullable();
            $table->string('logo')->nullable(); // a file path is better here
            $table->string('location')->nullable();
            $table->string('address');
            $table->string('email')->unique()->nullable();
            $table->string('phone')->unique()->nullable();

            $table->integer('sri')->nullable()->default(0);
            $table->string('mission')->nullable();
            $table->string('rating')->nullable(); //a picture file path again here
            $table->string('tour')->nullable(); // a ppt or word document file path here too
            $table->string('calendar')->nullable(); //a picture or pdf file path here

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schools');
    }
}
