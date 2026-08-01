<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();

            // Search keys used by the public verification form
            $table->string('ulb_code', 50);
            $table->string('application_name', 100);
            $table->string('building_permission_no', 50);

            $table->string('application_status', 50)->nullable();
            $table->string('application_number', 50)->nullable();
            $table->date('application_date')->nullable();

            $table->string('architect_engineer_no', 50)->nullable();
            $table->string('architect_engineer_name', 150)->nullable();
            $table->string('structure_engineer_no', 50)->nullable();
            $table->string('structure_engineer_name', 150)->nullable();
            $table->string('clerk_of_works_no', 50)->nullable();
            $table->string('clerk_of_works_name', 150)->nullable();
            $table->string('developer_no', 50)->nullable();
            $table->string('developer_name', 150)->nullable();

            $table->string('owner_name', 255)->nullable();
            $table->string('owner_address', 500)->nullable();
            $table->string('applicant_name', 255)->nullable();
            $table->string('applicant_address', 500)->nullable();

            $table->string('administrative_zone', 100)->nullable();
            $table->string('administrative_ward', 100)->nullable();
            $table->string('district', 100)->nullable();
            $table->string('taluka', 100)->nullable();
            $table->string('city_village', 100)->nullable();

            $table->string('tp_scheme_number', 50)->nullable();
            $table->string('tp_scheme_name', 100)->nullable();
            $table->string('revenue_survey_no', 50)->nullable();
            $table->string('city_survey_no', 50)->nullable();
            $table->string('final_plot_no', 50)->nullable();
            $table->string('original_plot_no', 50)->nullable();
            $table->string('sub_plot_no', 50)->nullable();
            $table->string('tikka_no', 50)->nullable();
            $table->string('site_address', 500)->nullable();
            $table->string('block_tenement_no', 50)->nullable();
            $table->string('max_height_of_building', 50)->nullable();
            $table->string('odps_application_no', 50)->nullable();

            $table->date('certificate_issue_date')->nullable();
            $table->date('certificate_expiry_date')->nullable();

            $table->string('mobile_number', 15)->nullable();
            $table->string('email_id', 100)->nullable();

            // Files served by the DOWNLOAD PDF / DOWNLOAD DRAWING PDF buttons
            $table->string('certificate_pdf')->nullable();
            $table->string('drawing_pdf')->nullable();

            $table->timestamps();

            $table->unique(['ulb_code', 'application_name', 'building_permission_no'], 'certificates_lookup_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
