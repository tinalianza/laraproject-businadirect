<?php

namespace App\Services;

use BaconQrCode\Renderer\Image\Png;
use BaconQrCode\Writer;
use Illuminate\Support\Facades\Storage;

class PaymentService
{
    /**
     * Generate GCash QR code and save as JPG image.
     *
     * @param float $amount
     * @return string  Path to the generated QR code image
     */
    public function generateGcashQrCode($amount)
    {
        // Setup QR code renderer
        $renderer = new Png();
        $renderer->setWidth(400); // Adjust QR code size as needed
        $writer = new Writer($renderer);

        // Generate QR code content (replace with actual GCash QR code generation logic)
        $qrCodeContent = "gcash://scan/?amount={$amount}";

        // Generate QR code image
        $qrCodeImage = $writer->writeString($qrCodeContent);

        // Save QR code image to public/images/ as JPG
        $qrCodePath = 'public/images/';
        $filename = 'GCash_QR.jpg'; // Use JPG extension
        Storage::put($qrCodePath . $filename, $qrCodeImage);

        // Return path to the saved QR code image
        return asset('images/' . $filename);
    }
}
