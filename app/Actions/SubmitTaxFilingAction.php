<?php

namespace App\Actions;

use App\Models\TaxFiling;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubmitTaxFilingAction
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * @return TaxFiling
     * @param array $data
     */
    public function execute(array $data): TaxFiling
    {
        /* @var User $user */
        $user = Auth::user();

        DB::beginTransaction();

        $tax_filing = $user->tax_filings()->create(
            collect($data)
                ->except("documents")
                ->merge(["submitted_at" => Carbon::today()])
                ->toArray()
        );

        $tax_documents = app(MakeTaxDocumentsAction::class)->execute(
            $data["documents"]
        );

        $tax_filing->documents()->createMany($tax_documents);

        DB::commit();

        return $tax_filing;
    }
}
