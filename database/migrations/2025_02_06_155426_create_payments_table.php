<?php

use App\Enums\PaymentStatus;
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
        Schema::create('payments', function (Blueprint $table) {
            $table->ulid('id')->primary();

            $table->string('invoice_id');
            $table->string('stripe_payment_intent_id')->nullable();

            $table->float('total');
            $table->float('charged_amount');
            $table->float('discount')->default(0);
            $table->string('status')->default(PaymentStatus::PENDING->value);

            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('tax_filing_id')->constrained('tax_filings');

            $table->timestamp('completed_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
