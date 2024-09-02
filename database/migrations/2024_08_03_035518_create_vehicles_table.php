<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
{

    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehicle_owner_id');
            $table->string('model_color');
            $table->string('plate_no')->unique();
            $table->date('expiry_date');
            $table->string('copy_driver_license')->nullable();
            $table->string('copy_cor')->nullable();
            $table->string('copy_school_id')->nullable();
            $table->unsignedBigInteger('vehicle_type_id');
            $table->string('claiming_status')->nullable();
            $table->string('vehicle_status')->nullable();
            $table->date('apply_date')->nullable();
            $table->date('issued_date')->nullable();
            $table->date('sticker_expiry')->nullable();
            $table->timestamps();
            $table->date('expiration')->nullable();
            $table->string('or_no')->unique();
            $table->string('cr_no')->unique();
            $table->string('copy_or_cr')->nullable();
        });
    }


    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
}
