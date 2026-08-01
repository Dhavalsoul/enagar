<?php

namespace App\Http\Controllers;

use App\Models\PaymentReceipt;
use BaconQrCode\Common\ErrorCorrectionLevel;
use BaconQrCode\Encoder\Encoder;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class WelcomeController extends Controller
{
    /**
     * Receipt PDF used when the receipt row has no file of its own.
     */
    protected const DEFAULT_RECEIPT_PDF = 'paymentPdf/digigov_1.pdf';

    /**
     * Page the building permission QR code opens when scanned.
     */
    protected const VERIFY_CERTIFICATE_URL = 'https://enagar-mahuva.in/verify-certificate';

    /**
     * Display the welcome page.
     */
    public function index(): Response
    {
        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    }

    /**
     * Generate a PNG QR code pointing at the receipt PDF and store it under
     * qrcodes/ on the public disk. Regenerating overwrites the previous image.
     */
    public function generateQr(PaymentReceipt $paymentReceipt): RedirectResponse
    {
        $png = $this->qrPng($this->receiptPdfUrl($paymentReceipt));

        $qr_image = 'qrcodes/receipt_'.$paymentReceipt->id.'.png';

        Storage::disk('public')->put($qr_image, $png);

        $paymentReceipt->update(['qr_image' => $qr_image]);

        return back()->with('status', 'qr-code-generated');
    }

    /**
     * Public URL that opens the receipt PDF in the browser.
     */
    protected function receiptPdfUrl(PaymentReceipt $paymentReceipt): string
    {
        $path = $paymentReceipt->payment_receipt ?: self::DEFAULT_RECEIPT_PDF;

        return Storage::disk('public')->url(ltrim($path, '/'));
    }

    /**
     * Render $data as a PNG QR code.
     *
     * simple-qrcode renders PNG through BaconQrCode's Imagick backend, so it
     * falls back to a GD renderer when the imagick extension is unavailable.
     */
    protected function qrPng(string $data, int $size = 300, int $margin = 4): string
    {
        if (extension_loaded('imagick')) {
            return QrCode::format('png')
                ->size($size)
                ->margin($margin)
                ->errorCorrection('H')
                ->generate($data);
        }

        $matrix = Encoder::encode($data, ErrorCorrectionLevel::H())->getMatrix();
        $modules = $matrix->getWidth();

        $scale = max(1, (int) floor($size / ($modules + $margin * 2)));
        $dimension = ($modules + $margin * 2) * $scale;

        $image = imagecreatetruecolor($dimension, $dimension);
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);
        imagefilledrectangle($image, 0, 0, $dimension - 1, $dimension - 1, $white);

        for ($y = 0; $y < $modules; $y++) {
            for ($x = 0; $x < $modules; $x++) {
                if ($matrix->get($x, $y)) {
                    $left = ($x + $margin) * $scale;
                    $top = ($y + $margin) * $scale;
                    imagefilledrectangle($image, $left, $top, $left + $scale - 1, $top + $scale - 1, $black);
                }
            }
        }

        ob_start();
        imagepng($image);

        return ob_get_clean();
    }

    /**
     * Generate a QR code for the receipt PDF and return its public URL.
     */
    public function getQrCode(): JsonResponse
    {
        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        // $png = $this->qrPng($disk->url(self::DEFAULT_RECEIPT_PDF));
        $png = $this->qrPng($disk->url(self::DEFAULT_RECEIPT_PDF), 300, 0);
        $qr_image = 'qrcodes/receipt_payment_'.time().'.png';

        $stored = $disk->put($qr_image, $png);

        return response()->json([
            'status' => $stored ? 'QR generated' : 'QR Not Generated.',
            'path' => $stored ? $disk->url($qr_image) : null,
        ]);
    }

    /**
     * Generate the QR code stamped on the building permission PDF. Scanning it
     * opens the certificate verification page in the browser.
     */
    public function permissionQrcode(): JsonResponse
    {
        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        // Keep the default quiet zone so phone cameras lock on to the code.
        $png = $this->qrPng(self::VERIFY_CERTIFICATE_URL, 298, 2);

        $qr_image = 'qrcodes/permission_'.time().'.png';

        $stored = $disk->put($qr_image, $png);

        return response()->json([
            'status' => $stored ? 'QR generated' : 'QR Not Generated.',
            'url' => self::VERIFY_CERTIFICATE_URL,
            'path' => $stored ? $disk->url($qr_image) : null,
        ]);
    }
}
