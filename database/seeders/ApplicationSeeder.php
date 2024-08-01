<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Application;

class ApplicationSeeder extends Seeder
{
    public function run()
    {
        Application::create([
            'name' => 'Doe, John, A.',
            'applicant_type' => 'BU Personnel',
            'employee_id' => '12345',
            'email' => 'john.doe@example.com',
            'contact_no' => '09123456789',
            'vehicle_type' => '4-wheel',
            'driver_license' => 'A00-00-000000',
            'vehicle_model' => 'Toyota Black',
            'plate_number' => 'ABC-1234',
            'or_number' => '1234-123456789012',
            'cr_number' => '123456789',
            'expiration' => '2025-12-31',
            'total_due' => 500.00,
        ]);
    }
}

