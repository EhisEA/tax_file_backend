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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone_number');
        });

        Schema::table('user_profiles', function (Blueprint $table) {
            $table->string('phone_number');
            $table->string('middle_name')->nullable()->change();
            $table->timestamp('date_of_birth')->nullable()->change();
            $table->string('social_insurance_number')->nullable()->change();
            $table->string('gender')->nullable()->change();
            $table->string('marital_status')->nullable()->change();
            $table->string('occupation')->nullable()->change();
            $table->string('preferred_language')->nullable()->change();

            // contact info
            $table->string('care_of')->nullable()->change();
            $table->string('rural_route_address')->nullable()->change();
            $table->string('mailing_street_address')->nullable()->change();
            $table->string('mailing_unit')->nullable()->change();
            $table->string('mailing_po_box')->nullable()->change();
            $table->string('mailing_postal_code')->nullable()->change();
            $table->string('mailing_city')->nullable()->change();
            $table->string('mailing_province')->nullable()->change();
            $table->string('residential_street_address')->nullable()->change();
            $table->string('residential_unit')->nullable()->change();
            $table->string('residential_city')->nullable()->change();
            $table->string('residential_province')->nullable()->change();
            $table->string('residential_postal_code')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_number');
        });

        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropColumn('phone_number');
            $table->string('middle_name')->change();
            $table->timestamp('date_of_birth')->change();
            $table->string('social_insurance_number')->change();
            $table->string('gender')->change();
            $table->string('marital_status')->change();
            $table->string('occupation')->change();
            $table->string('preferred_language')->change();

            // contact info
            $table->string('care_of')->change();
            $table->string('rural_route_address')->change();
            $table->string('mailing_street_address')->change();
            $table->string('mailing_unit')->change();
            $table->string('mailing_po_box')->change();
            $table->string('mailing_postal_code')->change();
            $table->string('mailing_city')->change();
            $table->string('mailing_province')->change();
            $table->string('residential_street_address')->change();
            $table->string('residential_unit')->change();
            $table->string('residential_city')->change();
            $table->string('residential_province')->change();
            $table->string('residential_postal_code')->change();
        });
    }
};
