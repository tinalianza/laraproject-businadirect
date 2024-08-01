<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReaddForeignKeysToRegistrationsTable extends Migration
{
    public function up()
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->foreign('id_no')
                ->references('id_no')
                ->on('employees')
                ->onDelete('restrict');
                
            // Add foreign key for students (applicant_type_id = 3)
            $table->foreign('id_no')
                ->references('id_no')
                ->on('students')
                ->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropForeign(['id_no']); // Drop foreign key constraint
        });
    }
}
