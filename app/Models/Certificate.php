<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = [
        'ulb_code',
        'application_name',
        'building_permission_no',
        'application_status',
        'application_number',
        'application_date',
        'architect_engineer_no',
        'architect_engineer_name',
        'structure_engineer_no',
        'structure_engineer_name',
        'clerk_of_works_no',
        'clerk_of_works_name',
        'developer_no',
        'developer_name',
        'owner_name',
        'owner_address',
        'applicant_name',
        'applicant_address',
        'administrative_zone',
        'administrative_ward',
        'district',
        'taluka',
        'city_village',
        'tp_scheme_number',
        'tp_scheme_name',
        'revenue_survey_no',
        'city_survey_no',
        'final_plot_no',
        'original_plot_no',
        'sub_plot_no',
        'tikka_no',
        'site_address',
        'block_tenement_no',
        'max_height_of_building',
        'odps_application_no',
        'certificate_issue_date',
        'certificate_expiry_date',
        'mobile_number',
        'email_id',
        'certificate_pdf',
        'drawing_pdf',
    ];

    protected function casts(): array
    {
        return [
            'application_date' => 'date',
            'certificate_issue_date' => 'date',
            'certificate_expiry_date' => 'date',
        ];
    }

    /**
     * Match the three values entered on the verification form.
     */
    public function scopeMatching(Builder $query, string $ulbCode, string $applicationName, string $permissionNo): Builder
    {
        return $query->where('ulb_code', $ulbCode)
            ->where('application_name', $applicationName)
            ->where('building_permission_no', $permissionNo);
    }

    /**
     * Payload rendered by the verification page. Dates use the dd/mm/yyyy
     * format the portal displays and empty values fall back to a dash.
     */
    public function toVerificationArray(): array
    {
        $dash = fn (?string $value) => filled($value) ? $value : '-';

        return [
            'id' => $this->id,
            'application_status' => $dash($this->application_status),
            'application_number' => $dash($this->application_number),
            'application_date' => $this->formatDate($this->application_date),
            'architect_engineer_no' => $dash($this->architect_engineer_no),
            'architect_engineer_name' => $dash($this->architect_engineer_name),
            'structure_engineer_no' => $dash($this->structure_engineer_no),
            'structure_engineer_name' => $dash($this->structure_engineer_name),
            'clerk_of_works_no' => $dash($this->clerk_of_works_no),
            'clerk_of_works_name' => $dash($this->clerk_of_works_name),
            'developer_no' => $dash($this->developer_no),
            'developer_name' => $dash($this->developer_name),
            'owner_name' => $dash($this->owner_name),
            'owner_address' => $dash($this->owner_address),
            'applicant_name' => $dash($this->applicant_name),
            'applicant_address' => $dash($this->applicant_address),
            'administrative_zone' => $dash($this->administrative_zone),
            'administrative_ward' => $dash($this->administrative_ward),
            'district' => $dash($this->district),
            'taluka' => $dash($this->taluka),
            'city_village' => $dash($this->city_village),
            'tp_scheme_number' => $dash($this->tp_scheme_number),
            'tp_scheme_name' => $dash($this->tp_scheme_name),
            'revenue_survey_no' => $dash($this->revenue_survey_no),
            'city_survey_no' => $dash($this->city_survey_no),
            'final_plot_no' => $dash($this->final_plot_no),
            'original_plot_no' => $dash($this->original_plot_no),
            'sub_plot_no' => $dash($this->sub_plot_no),
            'tikka_no' => $dash($this->tikka_no),
            'site_address' => $dash($this->site_address),
            'block_tenement_no' => $dash($this->block_tenement_no),
            'max_height_of_building' => $dash($this->max_height_of_building),
            'odps_application_no' => $dash($this->odps_application_no),
            'certificate_issue_date' => $this->formatDate($this->certificate_issue_date),
            'certificate_expiry_date' => $this->formatDate($this->certificate_expiry_date),
            'mobile_number' => $this->mobile_number,
            'email_id' => $this->email_id,
            'has_certificate_pdf' => filled($this->certificate_pdf),
            'has_drawing_pdf' => filled($this->drawing_pdf),
        ];
    }

    protected function formatDate($date): string
    {
        return $date?->format('d/m/Y') ?: '-';
    }
}
