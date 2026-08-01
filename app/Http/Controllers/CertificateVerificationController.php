<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CertificateVerificationController extends Controller
{
    /**
     * Session key holding the answer to the captcha currently on screen.
     */
    protected const CAPTCHA_SESSION_KEY = 'certificate_captcha';

    /**
     * Session key holding the ids the visitor has verified. Only these may be
     * downloaded, so the PDF routes cannot be walked by guessing ids.
     */
    protected const VERIFIED_SESSION_KEY = 'certificate_verified_ids';

    /**
     * ULB types offered in the "ULB Name" dropdown.
     */
    protected const ULBS = [
        ['value' => 'MC', 'label' => 'Municipal Corporation'],
        ['value' => 'MUNI', 'label' => 'Municipality'],
        ['value' => 'DA', 'label' => 'Development Authority / Development Authority Area'],
        ['value' => 'GP', 'label' => 'Gram Panchayat'],
    ];

    /**
     * Certificates that can be verified from this page.
     */
    protected const APPLICATIONS = [
        ['value' => 'BPC', 'label' => 'Building Permission Certificate'],
        ['value' => 'BUC', 'label' => 'Building Use Permission Certificate'],
        ['value' => 'PLC', 'label' => 'Plinth Level Certificate'],
        ['value' => 'FNOC', 'label' => 'Fire NOC Certificate'],
    ];

    /**
     * Display the certificate verification form.
     */
    public function index(): Response
    {
        return Inertia::render('CertificateVerification', [
            'ulbs' => self::ULBS,
            'applications' => self::APPLICATIONS,
            'certificate' => null,
        ]);
    }

    /**
     * Validate the captcha, look the certificate up and re-render the page
     * with its details filled in.
     */
    public function verify(Request $request): Response
    {
        $validated = $request->validate([
            'ulb_code' => ['required', 'string', 'in:'.implode(',', array_column(self::ULBS, 'value'))],
            'application_name' => ['required', 'string', 'in:'.implode(',', array_column(self::APPLICATIONS, 'value'))],
            'building_permission_no' => ['required', 'string', 'max:50'],
            'captcha' => ['required', 'string'],
        ]);

        $expected = $request->session()->pull(self::CAPTCHA_SESSION_KEY);

        if (! $expected || ! hash_equals($expected, trim($validated['captcha']))) {
            throw ValidationException::withMessages([
                'captcha' => 'The captcha you entered is incorrect. Please try again.',
            ]);
        }

        $certificate = Certificate::query()
            ->matching(
                $validated['ulb_code'],
                $validated['application_name'],
                trim($validated['building_permission_no']),
            )
            ->first();

        if (! $certificate) {
            throw ValidationException::withMessages([
                'building_permission_no' => 'No certificate found for the details entered.',
            ]);
        }

        $request->session()->push(self::VERIFIED_SESSION_KEY, $certificate->id);

        return Inertia::render('CertificateVerification', [
            'ulbs' => self::ULBS,
            'applications' => self::APPLICATIONS,
            'certificate' => $certificate->toVerificationArray(),
        ]);
    }

    /**
     * Serve the captcha image and remember its answer for this session.
     */
    public function captcha(Request $request): HttpResponse
    {
        $code = (string) random_int(10000, 99999);

        $request->session()->put(self::CAPTCHA_SESSION_KEY, $code);

        return response($this->captchaPng($code))
            ->header('Content-Type', 'image/png')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }

    /**
     * Download the certificate or drawing PDF of an already verified record.
     */
    public function download(Request $request, Certificate $certificate, string $type): StreamedResponse
    {
        abort_unless(in_array($type, ['certificate', 'drawing'], true), 404);

        abort_unless(
            in_array($certificate->id, $request->session()->get(self::VERIFIED_SESSION_KEY, []), true),
            403,
        );

        $file = $type === 'drawing' ? $certificate->drawing_pdf : $certificate->certificate_pdf;
        $path = 'permissionPdf/'.$file;
        
        // dd($path);
        abort_if(blank($path) || ! Storage::disk('public')->exists($path), 404);

        return Storage::disk('public')->download(
            $path,
            $certificate->building_permission_no.'_'.$type.'.pdf',
        );
    }

    /**
     * Segments lit for each digit, keyed a-g clockwise from the top bar.
     */
    protected const DIGIT_SEGMENTS = [
        '0' => 'abcdef',
        '1' => 'bc',
        '2' => 'abged',
        '3' => 'abgcd',
        '4' => 'fgbc',
        '5' => 'afgcd',
        '6' => 'afgedc',
        '7' => 'abc',
        '8' => 'abcdefg',
        '9' => 'abcdfg',
    ];

    /**
     * Draw $code as the bold strike-through captcha shown next to the input.
     *
     * The digits are drawn from filled rectangles rather than a font so the
     * image stays crisp and no TTF file has to ship with the app.
     */
    protected function captchaPng(string $code, int $width = 180, int $height = 60): string
    {
        $image = imagecreatetruecolor($width, $height);
        $background = imagecolorallocate($image, 255, 255, 255);
        $ink = imagecolorallocate($image, 17, 17, 17);
        imagefilledrectangle($image, 0, 0, $width - 1, $height - 1, $background);

        $digits = str_split($code);
        $digitWidth = 22;
        $digitHeight = 36;
        $gap = 10;

        $x = (int) (($width - (count($digits) * $digitWidth + (count($digits) - 1) * $gap)) / 2);
        $baseY = (int) (($height - $digitHeight) / 2);

        foreach ($digits as $digit) {
            // A small vertical wobble keeps the digits from lining up exactly.
            $this->drawDigit($image, $digit, $x, $baseY + random_int(-3, 3), $digitWidth, $digitHeight, 6, $ink);
            $x += $digitWidth + $gap;
        }

        imagesetthickness($image, 3);
        imageline($image, 8, (int) ($height * 0.66), $width - 8, (int) ($height * 0.34), $ink);

        ob_start();
        imagepng($image);
        imagedestroy($image);

        return ob_get_clean();
    }

    /**
     * Render one seven-segment digit inside the given box.
     */
    protected function drawDigit($image, string $digit, int $x, int $y, int $w, int $h, int $t, int $color): void
    {
        $segments = self::DIGIT_SEGMENTS[$digit] ?? '';
        $mid = (int) ($y + $h / 2);
        $half = (int) ($t / 2);

        $boxes = [
            'a' => [$x, $y, $x + $w, $y + $t],
            'b' => [$x + $w - $t, $y, $x + $w, $mid],
            'c' => [$x + $w - $t, $mid, $x + $w, $y + $h],
            'd' => [$x, $y + $h - $t, $x + $w, $y + $h],
            'e' => [$x, $mid, $x + $t, $y + $h],
            'f' => [$x, $y, $x + $t, $mid],
            'g' => [$x, $mid - $half, $x + $w, $mid + $half],
        ];

        foreach ($boxes as $segment => [$x1, $y1, $x2, $y2]) {
            if (str_contains($segments, $segment)) {
                imagefilledrectangle($image, $x1, $y1, $x2, $y2, $color);
            }
        }
    }
}
