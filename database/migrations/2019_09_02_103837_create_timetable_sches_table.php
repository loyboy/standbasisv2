<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimetableSchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timetable_sches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sub_class')->index();
            $table->unsignedBigInteger('time_id')->index();
            $table->string('comment')->nullable();
            $table->timestamps();

            $table->foreign('sub_class')
            ->references('id')
            ->on('subjectclasses')
            ->onDelete('cascade');

            $table->foreign('time_id')
            ->references('id')
            ->on('timetables')
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
        Schema::dropIfExists('timetable_sches');
    }
}
