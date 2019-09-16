<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('fname');
            $table->string('lname');
            $table->char('gender',1);
            $table->string('agerange')->nullable();
            $table->string('bias',100)->nullable();
            $table->string('coursetype',100)->nullable();
            $table->string('qualification',100)->nullable();
            $table->integer('experience')->nullable();
            $table->text('photo')->nullable();
            $table->integer('_status')->nullable()->default(1);
            $table->integer('_type')->nullable()->default(0);
            $table->unsignedBigInteger('school_id')->index();
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
        Schema::dropIfExists('teachers');
    }
}
