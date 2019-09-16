<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssessmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('lsn_id')->index()->nullable();
            $table->unsignedBigInteger('sub_id')->index();
            $table->string('source');
            $table->string('title');
            $table->date('_date');
            $table->integer('_type'); // Type of Assessment
            $table->string('ext')->nullable();
            $table->timestamps();

            $table->foreign('lsn_id')
            ->references('id')
            ->on('lessonnotes')
            ->onDelete('cascade');

            $table->foreign('sub_id')
            ->references('id')
            ->on('subjects')
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
        Schema::dropIfExists('assessments');
    }
}
