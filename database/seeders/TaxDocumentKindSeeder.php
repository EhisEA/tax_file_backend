<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaxDocumentKindSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tax_document_kinds')->insertOrIgnore([
            ['name' => 'T4 Statement of Remuneration Paid'],
            [
                'name' => 'T4A Statement of Pension, Retirement, Annuity, and Other Income',
            ],
            ['name' => 'T4A(OAS) Statement of Old Age Security'],
            ['name' => 'T4A(P) Statement of Canada Pension Plan Benefits'],
            [
                'name' => 'T4E Statement of Employment Insurance and Other Benefits',
            ],
            ['name' => 'T4FHSA First Home Savings Account Statement'],
            [
                'name' => 'T4RIF Statement of income from a Registered Retirement Income Fund',
            ],
            ['name' => 'T4RSP Statement of RRSP Income'],
            [
                'name' => 'T5 Statement of Investment Income - slip information for individuals',
            ],
            ['name' => 'T5007 Statement of Benefits'],
            [
                'name' => 'T5008 Statement of Securities Transactions - slip information for individuals',
            ],
            ['name' => 'T5013 Statement of Partnership income'],
            ['name' => 'T5018 Statement of Contract Payments'],
            [
                'name' => 'T3 Statement of Trust Income Allocations and Designations – slip information for individuals',
            ],
            ['name' => 'T2202 Tuition Enrolment Certificate'],
            ['name' => 'T1204 Government Services Contract Payments'],
            ['name' => 'RC62 Universal Child Care Benefit statement'],
            [
                'name' => 'RC210 Working Income Tax Benefit Advance Payments Statement',
            ],
            [
                'name' => 'RRSP contribution receipt – slip information for individuals',
            ],
            [
                'name' => 'PRPP contribution receipt – slip information for individuals',
            ],
            [
                'name' => 'Expenses/Expenditure',
            ],
            [
                'name' => 'Reciepts',
            ],
        ]);
    }
}
