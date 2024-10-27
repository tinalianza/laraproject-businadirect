<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\VehicleOwner; // Assuming your vehicle owners are stored in this model
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Storage;

class GenerateQrCodesForOldUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qr:generate-for-old-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate QR codes for users registered before QR code generation was implemented';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Fetch all users who don't have a QR code yet
        $usersWithoutQr = VehicleOwner::whereNull('qr_code')->get();

        if ($usersWithoutQr->isEmpty()) {
            $this->info('No users found without QR codes.');
            return 0;
        }

        foreach ($usersWithoutQr as $user) {
            // Prepare QR code data
            $qrData = 'Vehicle Model: ' . $user->vehicle->model_color . "\nPlate Number: " . $user->vehicle->plate_no;

            // Generate QR code
            $qrCode = new QrCode($qrData);
            $writer = new PngWriter();
            $result = $writer->write($qrCode);

            // Store the QR code
            $qrCodePath = 'public/qrcodes/' . $user->id . '.png';
            Storage::put($qrCodePath, $result->getString());

            // Update user record with the path of the QR code
            $user->update(['qr_code' => str_replace('public/', '', $qrCodePath)]);

            $this->info('QR code generated for User ID: ' . $user->id);
        }

        $this->info('QR codes generated for all applicable users.');
        return 0;
    }
}
