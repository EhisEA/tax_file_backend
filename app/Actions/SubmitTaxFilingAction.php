<?php

declare(strict_types=1);

namespace App\Actions;

use App\Data\TaxFilingData;
use App\Models\TaxFiling;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\LaravelData\Optional;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class SubmitTaxFilingAction
{
    /**
     * Create a new class instance.
     */
    public function __construct() {}

    public function execute(TaxFilingData $data): TaxFiling
    {
        $user = Auth::user();

        // make sure filing year is set since this validation
        // is not done for drafts
        if ($data->filing_year instanceof Optional) {
            throw new BadRequestHttpException('filing_year is required');
        }

        $createQuery = $data->except('documents', 'draft');
        if ($data->draft === false) {
            $data->submitted_at = now();
        }

        DB::beginTransaction();

        $tax_filing = $user->tax_filings()->create($createQuery->toArray());

        $tax_documents = app(MakeTaxDocumentsAction::class)->execute(
            $data->documents
        );

        $tax_filing->documents()->createMany($tax_documents);

        DB::commit();

        return $tax_filing;
    }
}
