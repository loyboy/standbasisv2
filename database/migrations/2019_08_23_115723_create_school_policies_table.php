<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchoolPoliciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_policies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sch_id')->index();
            $table->integer('fair')->nullable();
            $table->integer('late')->nullable();
            $table->integer('signoff')->nullable();
            $table->integer('accept_tea')->nullable();
            $table->integer('accept_head')->nullable();
            $table->integer('lsn_submit')->nullable();
            $table->integer('lsn_resubmit')->nullable();
            $table->integer('lsn_action')->nullable();
            $table->integer('lsn_cycle')->nullable();
            $table->integer('lsn_closure')->nullable();
            $table->timestamps();

            $table->foreign('sch_id')
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
        Schema::dropIfExists('school_policies');
    }
}
