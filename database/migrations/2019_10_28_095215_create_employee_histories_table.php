<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('emp_id');
            $table->string('type');
            $table->string('employee_name');
            $table->string('employee_company');
            $table->string('employee_department');
            $table->string('employee_site');
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
        Schema::dropIfExists('employee_histories');
    }
}
