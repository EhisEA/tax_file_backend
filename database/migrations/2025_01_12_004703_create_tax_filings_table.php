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
        Schema::create("tax_filings", function (Blueprint $table) {
            $table->id();
            $table->integer("filing_year");

            // Basic info
            $table->string("employment_status")->nullable();
            $table->boolean("is_filing_for_self")->nullable();
            $table->boolean("is_first_time_filing")->nullable();
            $table->boolean("is_indigenous")->nullable();

            // Income and employment info
            $table->string("income_type_received")->nullable();
            $table->string("business_activity_category")->nullable();
            $table->boolean("has_income_from_rentals")->nullable();
            $table->boolean("has_income_from_investments")->nullable();

            // Residency and foreign assets
            $table->boolean("was_resident_of_canada_for_tax_year")->nullable();
            $table->boolean("has_held_foreign_property_over_100k")->nullable();
            $table->boolean("has_received_foreign_income")->nullable();

            // Personal and family situation
            $table->string("marital_status")->nullable();
            $table->boolean("qualifies_for_disability_tax_credit")->nullable();
            $table->boolean("is_foster_parent")->nullable();
            $table
                ->boolean("is_caregiver_for_disabled_family_member")
                ->nullable();

            // Special occupations and volunteer work
            $table->string("special_occupations_work_in")->nullable();
            $table->boolean("is_creative_pro_with_unique_expenses")->nullable();
            $table->boolean("is_farmer_or_fisher")->nullable();

            // Deductions and credits
            $table->string("savings_plans_contributed_to")->nullable();
            $table->boolean("is_working_from_home")->nullable();
            $table->boolean("has_made_charitable_donations")->nullable();
            $table->boolean("has_incurred_child_care_expenses")->nullable();
            $table->boolean("has_attended_post_secondary")->nullable();
            $table
                ->boolean("is_incurring_medical_expenses_for_self_or_dependent")
                ->nullable();
            $table
                ->boolean("has_completed_eligible_home_renovations")
                ->nullable();
            $table
                ->boolean("has_contributed_to_employment_expenses")
                ->nullable();
            $table
                ->boolean("has_lived_in_remote_area_for_six_months")
                ->nullable();

            // Final Confirmation
            $table->string("extra_considerations")->nullable();

            $table->timestamp("submitted_at")->nullable();
            $table->foreignId("user_id")->constrained("users");

            $table->timestamps();
        });

        Schema::create("tax_document_kinds", function (Blueprint $table) {
            $table->id();
            $table->string("name")->unique();
        });

        Schema::create("tax_documents", function (Blueprint $table) {
            $table->id();

            $table
                ->foreignId("kind_id")
                ->references("id")
                ->on("tax_document_kinds");

            $table
                ->foreignId("tax_filing_id")
                ->references("id")
                ->on("tax_filings");

            $table->foreignId("file_id")->references("id")->on("files");

            $table->unique(["kind_id", "file_id", "tax_filing_id"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("tax_documents");
        Schema::dropIfExists("tax_document_kinds");
        Schema::dropIfExists("tax_filings");
    }
};
