<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('applicant_type');
            $table->string('employee_id')->nullable();
            $table->string('email');
            $table->string('contact_no');
            $table->string('vehicle_type');
            $table->string('driver_license');
            $table->string('vehicle_model');
            $table->string('plate_number');
            $table->string('or_cr_number');
            $table->date('expiration');
            $table->string('scanned_or_cr');
            $table->string('scanned_license');
            $table->string('certificate');
            $table->string('scanned_id');
            $table->decimal('total_due', 10, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('applications');
    }
};
