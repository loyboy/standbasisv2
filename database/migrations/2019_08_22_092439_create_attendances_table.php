<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sub_class_id')->index();
            $table->unsignedBigInteger('time_id')->index();
            $table->unsignedBigInteger('term')->index();
            $table->datetime('_date'); //just use date_time
            $table->char('period',2); // (M)orning, (A)fternoon , (C)lose
            $table->mediumText('image')->nullable(); // it will be a base64 image stream
            $table->integer('_done')->default(0); // 1- yes, 0 - Nope
            $table->integer('_delegated')->nullable()->default(0);
            $table->string('_desc')->nullable();
            $table->timestamps();

            $table->foreign('sub_class_id')
            ->references('id')
            ->on('subjectclasses')
            ->onDelete('cascade');

            $table->foreign('term')
            ->references('id')
            ->on('terms')
            ->onDelete('cascade');

            $table->foreign('time_id')
            ->references('id')
            ->on('timetables')
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
        Schema::dropIfExists('attendances');
    }
}
