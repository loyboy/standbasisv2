<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubjectclassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subjectclasses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('tea_id')->index();
            $table->unsignedBigInteger('class_id')->index();
            $table->unsignedBigInteger('sub_id')->index();
            //$table->unsignedBigInteger('time_id')->index();
            $table->string('title')->nullable();
            $table->integer('delegated')->nullable()->default(0); // Teacher to take over from this present Teacher
            $table->integer('totalcount')->nullable()->default(-1);
            $table->integer('totalslot')->nullable()->default(0);
            $table->timestamps();

            $table->foreign('tea_id')
            ->references('id')
            ->on('teachers')
            ->onDelete('cascade');

            $table->foreign('class_id')
            ->references('id')
            ->on('class_streams')
            ->onDelete('cascade');

            $table->foreign('sub_id')
            ->references('id')
            ->on('subjects')
            ->onDelete('cascade');

           /* $table->foreign('time_id')
            ->references('id')
            ->on('timetables')
            ->onDelete('cascade');*/
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subjectclasses');
    }
}
