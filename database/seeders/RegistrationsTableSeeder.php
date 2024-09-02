<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegistrationsTableSeeder extends Seeder
{
    public function run()
    {
 
        $applicantTypeId = DB::table('applicant_types')->where('type', 'Non-Personnel')->value('id');
        $vehicleTypeId = DB::table('vehicle_types')->where('type', '2-wheel')->value('id');


        DB::table('registrations')->insert([
            'name' => 'Dela Cruz, Juan, Isko',
            'applicant_type_id' => $applicantTypeId,
            'id_no' => null, 
            'email' => 'test@example.com',
            'contact_no' => '9478135769',
            'vehicle_type_id' => $vehicleTypeId,
            'driver_license' => 'A00-00-000000',
            'vehicle_model' => 'Toyota Black',
            'plate_number' => 'NCT-1234',
            'or_number' => '0000-000000000000',
            'cr_number' => '000000000',
            'expiration' => '2026-01-08',
            'total_due' => 250.00,
            'scanned_or_cr' => base64_encode('dummy scanned_or_cr data'), 
            'scanned_license' => base64_encode('dummy scanned_license data'), 
        ]);
    }
}
