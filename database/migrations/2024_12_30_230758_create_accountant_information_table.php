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
        Schema::create('accountant_information', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_id')->constrained('accountant_profiles')->cascadeOnDelete();

            // Personal Information
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('social_security_number')->nullable();
            $table->string('gender')->nullable();
            $table->string('religion')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('residential_address')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable();
            $table->string('province')->nullable();
            $table->string('city')->nullable();
            $table->foreignId('profile_picture_id')->nullable()->constrained('files');

            // Professional Information
            $table->string('business_name')->nullable();
            $table->string('office_address')->nullable();
            $table->string('professional_designation')->nullable();
            $table->string('netfile_number')->nullable();
            $table->integer('years_of_experience')->nullable();
            $table->string('office_postal_code')->nullable();

            // Identification documents
            $table->foreignId('proof_of_identity_id')->nullable()->constrained('files');
            $table->foreignId('proof_of_address_id')->nullable()->constrained('files');
            $table->foreignId('professional_qualification_document_id')->nullable()->constrained('files');
            $table->foreignId('proof_of_business_registration_id')->nullable()->constrained('files');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accountant_information');
    }
};
