<?php

namespace App\Http\Controllers;

use App\Actions\MakeTaxDocumentsAction;
use App\Actions\SubmitTaxFilingAction;
use App\Data\TaxFilingData;
use App\Http\Resources\TaxFilingCollection;
use App\Http\Resources\TaxFilingResource;
use App\Models\TaxDocumentKind;
use App\Models\TaxFiling;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaxFilingController extends Controller
{
    /**
     * @return TaxFilingCollection
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($request->string('status') == 'draft') {
            $draft = TaxFiling::whereUserId($user->id)->whereSubmittedAt(null)->paginate();

            return new TaxFilingCollection($draft);
        }

        $filings = $user->tax_filings()->paginate();

        return new TaxFilingCollection($filings);
    }

    /**
     * @return TaxFilingResource|JsonResponse
     */
    public function show(Request $request, TaxFiling $taxFiling)
    {
        $user = Auth::user();

        if ($taxFiling->user_id !== $user->id) {
            return response()->json(['message' => 'Tax filing not found'], 404);
        }

        return new TaxFilingResource($taxFiling);
    }

    public function submit(
        TaxFilingData $data,
        SubmitTaxFilingAction $submitTaxFilingAction
    ): TaxFilingResource {
        $taxFiling = $submitTaxFilingAction->execute($data);

        return new TaxFilingResource($taxFiling);
    }

    public function updateDraft(
        Request $request,
        TaxFiling $taxFiling,
        MakeTaxDocumentsAction $makeTaxDocumentsAction
    ): TaxFilingResource {
        $data = TaxFilingData::validateAndCreate(array_merge($request->all(), ['draft' => true]));
        $taxFiling->load('documents');

        DB::beginTransaction();

        $updateQuery = $data->except('documents', 'draft', 'filing_year');
        $taxFiling->update($updateQuery->toArray());

        if ($data->documents !== null) {
            $documents = $makeTaxDocumentsAction->execute(
                $data->documents
            );

            $taxFiling->documents()->delete();
            $taxFiling->documents()->createMany($documents);
        }

        DB::commit();

        return new TaxFilingResource($taxFiling->refresh());
    }

    public function submitDraft(TaxFiling $taxFiling): TaxFilingResource|JsonResponse
    {
        $user = Auth::user();
        $user->load('tax_filings');

        if ($taxFiling->user_id !== $user->id) {
            return response()->json(['message' => 'Tax filing not found'], 404);
        }

        $getTaxDocuments = fn () => $taxFiling->documents->map(
            fn ($document) => [
                'name' => $document['kind']['name'],
                'file_id' => $document['file']['id'],
            ]);

        TaxFilingData::validate(
            array_merge($taxFiling->toArray(),
                ['documents' => $getTaxDocuments()->toArray()])
        );

        return new TaxFilingResource($taxFiling);
    }

    public function getDocumentKinds(): JsonResponse
    {
        return response()->json(TaxDocumentKind::all());
    }
}
