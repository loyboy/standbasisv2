<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttPerformancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('att_performances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('att_id')->index();
            $table->integer('fully')->default(0);
            $table->integer('qua')->default(0);
            $table->integer('flag')->default(0);
            $table->integer('policy')->default(0);
            $table->integer('perf')->default(0);
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
        Schema::dropIfExists('att_performances');
    }
}
