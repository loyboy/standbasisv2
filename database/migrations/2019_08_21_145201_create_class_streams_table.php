<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassStreamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_streams', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('school_id')->index();
            $table->string('title');
            $table->string('ext')->nullable();
            $table->integer('category');  //  1 - Primary 1, 2 - Primary 2
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
        Schema::dropIfExists('class_streams');
    }
}
