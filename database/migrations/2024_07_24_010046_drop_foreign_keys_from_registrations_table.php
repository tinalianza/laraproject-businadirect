<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropForeignKeysFromRegistrationsTable extends Migration
{
    public function up()
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropForeign(['id_no']); // Drop foreign key constraint
        });
    }

    public function down()
    {
        // You might need to re-add the foreign key constraint in the down method
    }
}
