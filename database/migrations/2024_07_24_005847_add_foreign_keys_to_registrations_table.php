<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToRegistrationsTable extends Migration
{
    public function up()
    {
        Schema::table('registrations', function (Blueprint $table) {
            // Add foreign key for employees (applicant_type_id = 1)
            $table->unsignedBigInteger('id_no')->change();
            $table->foreign('id_no')
                ->references('id_no')
                ->on('employees')
                ->onDelete('restrict');

            // Add foreign key for students (applicant_type_id = 3)
            $table->unsignedBigInteger('id_no')->change();
            $table->foreign('id_no')
                ->references('id_no')
                ->on('students')
                ->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropForeign(['id_no']);
        });
    }
}
