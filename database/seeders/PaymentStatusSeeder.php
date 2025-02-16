<?php

namespace Database\Seeders;

use App\Enums\PaymentStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentStatuses = [];
        foreach (PaymentStatus::cases() as $status) {
            $paymentStatuses[] = ['name' => $status->value];
        }

        DB::table('payment_status')->insertOrIgnore($paymentStatuses);
    }
}
