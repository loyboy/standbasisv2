<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username')->unique();
            $table->string('name');
            $table->string('password');
            $table->integer('_type');
            $table->integer('_status')->nullable()->default(1);
            $table->unsignedBigInteger('teacher_id')->index(); // All staff are added into teacher table
            $table->string('api_token', 80)->unique()->nullable()->default(null);
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('teacher_id')
            ->references('id')
            ->on('teachers')
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
        Schema::dropIfExists('users');
    }
}
