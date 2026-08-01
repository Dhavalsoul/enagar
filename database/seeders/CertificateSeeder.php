<?php

namespace Database\Seeders;

use App\Models\Certificate;
use Illuminate\Database\Seeder;

class CertificateSeeder extends Seeder
{
    /**
     * Seed a certificate that can be verified from /verify-certificate.
     */
    public function run(): void
    {
        Certificate::updateOrCreate(
            [
                'ulb_code' => 'DA',
                'application_name' => 'BPC',
                'building_permission_no' => '1034LD26270015',
            ],
            [
                'application_status' => 'Completed',
                'application_number' => '1034BDP26270032',
                'application_date' => '2026-06-07',
                'architect_engineer_no' => '1034ERH0710261011',
                'architect_engineer_name' => 'PARTH RASIKBHAI PATTHAR',
                'owner_name' => 'KANCHANBEN BHAVESHBHAI MAKWANA,VINODBHAI BHAVESHBHAI MAKWANA',
                'owner_address' => 'KUMBHAN MAHUVA BHAVNAGAR KUMBHAN , MAHUVA - 364290',
                'applicant_name' => 'KANCHANBEN BHAVESHBHAI MAKWANA,VINODBHAI BHAVESHBHAI MAKWANA',
                'applicant_address' => 'KUMBHAN MAHUVA BHAVNAGAR KUMBHAN , MAHUVA - 364290',
                'administrative_zone' => 'DEFAULT ZONE AUTHORITY',
                'administrative_ward' => 'DEFAULT WARD',
                'district' => 'BHAVNAGAR',
                'taluka' => 'MAHUVA',
                'city_village' => 'MAHUVA',
                'tp_scheme_name' => 'NA',
                'revenue_survey_no' => '317 PAIKI',
                'city_survey_no' => '7975',
                'tikka_no' => '88',
                'site_address' => 'PLOT NO. - 214, R.S.NO. - 317 PAIKI, C.T.S.NO. - 7975, NUTAN NAGAR YOJNA NO. - 2 AT - MAHUVA, DIST - BHAVNAGAR',
                'odps_application_no' => 'ODPS/2026/077353',
                'certificate_issue_date' => '2026-07-17',
                'certificate_expiry_date' => '2027-07-16',
                'certificate_pdf' => 'paymentPdf/digigov_1.pdf',
            ],
        );
    }
}
