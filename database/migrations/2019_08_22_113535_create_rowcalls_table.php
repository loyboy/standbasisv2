<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRowcallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rowcalls', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pup_id')->index();
            $table->unsignedBigInteger('att_id')->index();
            $table->string('pupil_name');
            $table->integer('_status'); // 0 - Absent, 1- Present
            $table->string('remark')->nullable()->default("");  // Y or N
            $table->timestamps();

            $table->foreign('pup_id')
            ->references('id')
            ->on('pupils')
            ->onDelete('cascade');

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
        Schema::dropIfExists('rowcalls');
    }
}
