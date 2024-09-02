<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRegistrationNoToTransactionsTable extends Migration
{
    public function up()
    {
        Schema::table('transaction', function (Blueprint $table) {
            $table->string('registration_no')->nullable(); 
        });
    }

    public function down()
    {
        Schema::table('transaction', function (Blueprint $table) {
            $table->dropColumn('registration_no');
        });
    }
}
