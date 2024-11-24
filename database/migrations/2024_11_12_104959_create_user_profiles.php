<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();

            // personal info
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->timestamp('date_of_birth');
            $table->string('social_insurance_number');
            $table->string('gender');
            $table->string('marital_status');
            $table->string('occupation');
            $table->string('preferred_language');

            // contact info
            $table->string('care_of');
            $table->string('rural_route_address');
            $table->string('mailing_street_address');
            $table->string('mailing_unit');
            $table->string('mailing_po_box');
            $table->string('mailing_postal_code');
            $table->string('mailing_city');
            $table->string('mailing_province');
            $table->string('residential_street_address');
            $table->string('residential_unit');
            $table->string('residential_city');
            $table->string('residential_province');
            $table->string('residential_postal_code');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
