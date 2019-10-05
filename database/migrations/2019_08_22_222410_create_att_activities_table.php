<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('att_activities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('att_id')->index();
            $table->integer('owner');
            $table->string('ownertype'); // type of user
            $table->string('expected'); // timestamps
            $table->string('actual')->nullable(); // timestamps
            $table->integer('slip'); //0 - in time, 1 - late time
            $table->string('_comment')->nullable();
            $table->integer('_action')->nullable(); // 0- not good, 1 - approved
            $table->timestamps();

            $table->foreign('att_id')
            ->references('id')
            ->on('attendances')
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
        Schema::dropIfExists('att_activities');
    }
}
