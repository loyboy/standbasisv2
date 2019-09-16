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
            $table->datetime('_submission')->nullable();
            $table->datetime('_resubmission')->nullable();
            $table->datetime('_revert')->nullable();
            $table->datetime('_approval')->nullable();
            $table->integer('_cycle')->default(1);
            $table->datetime('_launch')->nullable();
            $table->datetime('_closure')->nullable();
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
