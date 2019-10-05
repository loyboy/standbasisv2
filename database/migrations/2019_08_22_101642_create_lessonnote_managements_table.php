<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLessonnoteManagementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lessonnote_managements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('lsn_id')->index();
            $table->datetime('_submission')->nullable()->default("1970-10-10 00:00:00");
            $table->datetime('_resubmission')->nullable()->default("1970-10-10 00:00:00");
            $table->datetime('_revert')->nullable()->default("1970-10-10 00:00:00");
            $table->datetime('_approval')->nullable()->default("1970-10-10 00:00:00");
            $table->integer('_cycle')->default(1);
            $table->datetime('_launch')->nullable()->default("1970-10-10 00:00:00");
            $table->datetime('_closure')->nullable()->default("1970-10-10 00:00:00");
            $table->datetime('_exclosure')->nullable()->default("1970-10-10 00:00:00");
            $table->timestamps();

            $table->foreign('lsn_id')
            ->references('id')
            ->on('lessonnotes')
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
        Schema::dropIfExists('lessonnote_managements');
    }
}
