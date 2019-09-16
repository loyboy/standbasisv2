<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimetablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timetables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('school_id')->index();
            $table->time('_time');
            $table->integer('_day');
            $table->string('affected')->nullable(); // IDs of all affected teachers used in Comma separated format
            $table->integer('waiver')->default(0); // if that day and time is excluded 0- Nope; 1 - Yes
            $table->string('waiver_desc')->nullable();
            $table->timestamps();

            $table->foreign('school_id')
            ->references('id')
            ->on('schools')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('timetables');
    }
}
