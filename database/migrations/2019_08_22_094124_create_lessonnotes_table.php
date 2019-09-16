<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLessonnotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lessonnotes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('tea_id')->index();
            $table->unsignedBigInteger('sub_id')->index();
            $table->unsignedBigInteger('term_id')->index();
            $table->string('title',100);
            $table->integer('class_category'); //use class Category here to avoid duplication
            $table->date('_date'); //mutable on submit
            $table->string('comment_principal',400)->nullable();
            $table->string('comment_admin',400)->nullable();
            $table->integer('period');// Week 1-12
            $table->string('_file');
            $table->timestamps();

            $table->foreign('tea_id')
            ->references('id')
            ->on('teachers')
            ->onDelete('cascade');

            $table->foreign('sub_id')
            ->references('id')
            ->on('subjects')
            ->onDelete('cascade');

            $table->foreign('term_id')
            ->references('id')
            ->on('terms')
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
        Schema::dropIfExists('lessonnotes');
    }
}
