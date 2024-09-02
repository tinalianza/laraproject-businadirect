<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRegistrationNoColumnToTransactionsTable extends Migration
{
    public function up()
    {
        Schema::table('transaction', function (Blueprint $table) {
            $table->string('registration_no')->nullable()->after('reference_no');
        });
    }

    public function down()
    {
        Schema::table('transaction', function (Blueprint $table) {
            $table->dropColumn('registration_no');
        });
    }
}
