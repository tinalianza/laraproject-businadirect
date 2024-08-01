<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateColumnsForFilePaths extends Migration
{
    public function up()
    {
        Schema::table('registrations', function (Blueprint $table) {
            // Update the column type for file paths
            $table->longText('scanned_or_cr')->change();
            $table->longText('scanned_license')->change();
            $table->longText('scanned_id')->nullable()->change();
            $table->longText('certificate')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('registrations', function (Blueprint $table) {
            // Rollback the column types to their original types
            $table->text('scanned_or_cr')->change();
            $table->text('scanned_license')->change();
            $table->text('scanned_id')->nullable()->change();
            $table->text('certificate')->nullable()->change();
        });
    }
}
