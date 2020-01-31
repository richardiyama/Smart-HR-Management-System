<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalaryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salary_details', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('user_id')->nullable()->default(12)->unique();
            $table->string('user_email')->unique();
            $table->string('salary');
            $table->string('bank_name');
            $table->string('account_id');
            $table->string('pension_fund');
            $table->string('pension_id');
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
        Schema::dropIfExists('salary_details');
    }
}
