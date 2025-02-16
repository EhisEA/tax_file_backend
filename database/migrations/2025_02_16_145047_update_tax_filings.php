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
        Schema::table('tax_filings', function (Blueprint $table) {
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('social_insurance_number')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('street_address')->nullable();
            $table->string('postal_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tax_filings', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'middle_name',
                'last_name',
                'social_insurance_number',
                'phone_number',
                'city',
                'province',
                'street_address',
                'postal_code',
            ]);
        });
    }
};
