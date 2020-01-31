<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreEmploymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pre_employments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pre_emp_code');
            $table->integer('job_title');
            $table->foreign('job_title')->references('id')->on('positions')->onDelete('cascade');
            $table->integer('section');
            $table->foreign('section')->references('id')->on('departments')->onDelete('cascade');
            $table->integer('site');
            $table->foreign('site')->references('id')->on('sites')->onDelete('cascade');
            $table->integer('position_status');
            $table->foreign('position_status')->references('id')->on('position_status')->onDelete('cascade');
            $table->decimal('amount',10,2);
            $table->string('request_supervisor');
            $table->integer('project_manager_approval')->nullable();
            $table->integer('hr_manager_approval')->nullable();
            $table->date('start_date');
            $table->date('project_manager_approval_date')->nullable();
            $table->date('hr_manager_approval_date')->nullable();
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
        Schema::dropIfExists('pre_employments');
    }
}
