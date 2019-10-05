<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLsnPerformancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lsn_performances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('lsn_id')->index();
            $table->integer('fully')->default(0);
            $table->integer('qua')->default(0);
            $table->integer('flag')->default(0);
            $table->integer('policy')->default(0);
            $table->integer('perf')->default(0);

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
        Schema::dropIfExists('lsn_performances');
    }
}
