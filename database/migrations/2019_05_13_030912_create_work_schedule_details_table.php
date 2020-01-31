<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkScheduleDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_schedule_details', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('user_id')->nullable()->default(12)->unique();
            $table->string('user_email')->unique();
            $table->string('working_mode');
            $table->string('day');
            $table->string('resumption_time');
            $table->string('closing_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_schedule_details');
    }
}
