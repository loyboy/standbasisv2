<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('ass_id')->index();
            $table->unsignedBigInteger('enrol_id')->index();
            $table->date('_date');
            $table->integer('actual');
            $table->integer('max');
            $table->integer('perf'); //pls add trigger
            $table->timestamps();

            $table->foreign('ass_id')
            ->references('id')
            ->on('assessments')
            ->onDelete('cascade');

            $table->foreign('enrol_id')
            ->references('id')
            ->on('enrollments')
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
        Schema::dropIfExists('scores');
    }
}
