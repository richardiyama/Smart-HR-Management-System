<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('bvn')->nullable()->default(12)->unique();
            $table->string('fname');
            $table->string('lname');
            $table->string('mname');
            $table->string('dob');
            $table->string('gender');
            $table->string('nationality');
            $table->string('license');
            $table->string('phone_no');
            $table->string('email')->unique();
            $table->string('address');
            $table->string('status');
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
        Schema::dropIfExists('employees');
    }
}
