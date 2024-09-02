<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyIdNoColumnInRegistrationsTable extends Migration
{
    public function up()
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->unsignedBigInteger('id_no')->nullable()->change(); 
        });
    }

    public function down()
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->bigInteger('id_no')->nullable(false)->change();
        });
    }
}
