<?php

use App\Models\Certificate;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

function certificate(array $attributes = []): Certificate
{
    return Certificate::create(array_merge([
        'ulb_code' => 'DA',
        'application_name' => 'BPC',
        'building_permission_no' => '1034LD26270015',
        'application_status' => 'Completed',
        'application_number' => '1034BDP26270032',
        'application_date' => '2026-06-07',
        'certificate_issue_date' => '2026-07-17',
        'certificate_expiry_date' => '2027-07-16',
    ], $attributes));
}

test('the verification page renders', function () {
    $this->get('/verify-certificate')
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('CertificateVerification')
            ->has('ulbs')
            ->has('applications')
            ->where('certificate', null)
        );
});

test('the captcha image is served and remembered in the session', function () {
    $response = $this->get('/verify-certificate/captcha');

    $response->assertOk()->assertHeader('Content-Type', 'image/png');

    expect(session('certificate_captcha'))->toMatch('/^\d{5}$/');
});

test('a certificate is returned when the captcha and details match', function () {
    certificate();

    $this->withSession(['certificate_captcha' => '43293'])
        ->post('/verify-certificate', [
            'ulb_code' => 'DA',
            'application_name' => 'BPC',
            'building_permission_no' => '1034LD26270015',
            'captcha' => '43293',
        ])
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('CertificateVerification')
            ->where('certificate.application_status', 'Completed')
            ->where('certificate.application_number', '1034BDP26270032')
            ->where('certificate.application_date', '07/06/2026')
            ->where('certificate.certificate_expiry_date', '16/07/2027')
            // Empty columns fall back to the dash the portal displays.
            ->where('certificate.developer_name', '-')
        );
});

test('a wrong captcha is rejected', function () {
    certificate();

    $this->withSession(['certificate_captcha' => '43293'])
        ->post('/verify-certificate', [
            'ulb_code' => 'DA',
            'application_name' => 'BPC',
            'building_permission_no' => '1034LD26270015',
            'captcha' => '11111',
        ])
        ->assertSessionHasErrors('captcha');
});

test('an unknown permission number reports no certificate', function () {
    certificate();

    $this->withSession(['certificate_captcha' => '43293'])
        ->post('/verify-certificate', [
            'ulb_code' => 'DA',
            'application_name' => 'BPC',
            'building_permission_no' => '0000LD00000000',
            'captcha' => '43293',
        ])
        ->assertSessionHasErrors('building_permission_no');
});

test('the pdf can only be downloaded after a successful verification', function () {
    Storage::fake('public');
    Storage::disk('public')->put('permissionPdf/bpc.pdf', UploadedFile::fake()->create('bpc.pdf')->get());

    $record = certificate(['certificate_pdf' => 'permissionPdf/bpc.pdf']);

    $this->get("/verify-certificate/{$record->id}/download/certificate")
        ->assertForbidden();

    $this->withSession(['certificate_verified_ids' => [$record->id]])
        ->get("/verify-certificate/{$record->id}/download/certificate")
        ->assertOk()
        ->assertDownload("{$record->building_permission_no}_certificate.pdf");
});

test('a missing drawing pdf returns not found', function () {
    Storage::fake('public');

    $record = certificate();

    $this->withSession(['certificate_verified_ids' => [$record->id]])
        ->get("/verify-certificate/{$record->id}/download/drawing")
        ->assertNotFound();
});
