<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_details', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('user_id')->nullable()->default(12)->unique();
            $table->string('user_email')->unique();
            $table->string('company');
            $table->string('site');
            $table->string('department');
            $table->string('job_position');
            $table->string('employee_id');
            $table->string('start_date');
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
        Schema::dropIfExists('company_details');
    }
}
