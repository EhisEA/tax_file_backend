<?php

namespace App\Http\Controllers;

use App\Actions\SubmitTaxFilingAction;
use App\Http\Resources\TaxFilingCollection;
use App\Actions\MakeTaxDocumentsAction;
use App\Actions\ValidateTaxFilingAction;
use App\Exceptions\InvalidTaxFilingException;
use App\Http\Requests\SubmitDraftTaxFilingRequest;
use App\Http\Requests\SubmitTaxFilingRequest;
use App\Http\Requests\UpdateDraftTaxFilingRequest;
use App\Http\Resources\TaxFilingResource;
use App\Models\TaxDocument;
use App\Models\TaxDocumentKind;
use App\Models\TaxFiling;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TaxFilingController extends Controller
{
    /**
     * @return TaxFilingCollection
     */
    public function index(Request $request)
    {
        /* @var User $user */
        $user = Auth::user();

        $filings = $user->tax_filings()->paginate();

        return new TaxFilingCollection($filings);
    }

    /**
     * @return TaxFilingResource|JsonResponse
     */
    public function show(Request $request, int $filing_id)
    {
        /* @var User $user */
        $user = $request->user();

        $user->load("tax_filings");

        foreach ($user->tax_filings as $filing) {
            if ($filing->id === $filing_id) {
                return new TaxFilingResource($filing);
            }
        }

        return response()->json(["message" => "Tax filing not found"], 404);
    }

    public function submit(
        SubmitTaxFilingRequest $request,
        SubmitTaxFilingAction $submit_tax_filing_action
    ): TaxFilingResource {
        /* @var User $user */
        $data = $request->validated();

        $tax_filing = $submit_tax_filing_action->execute($data);

        return new TaxFilingResource($tax_filing);
    }

    public function storeDraft(
        SubmitDraftTaxFilingRequest $request,
        MakeTaxDocumentsAction $make_tax_documents_action
    ): TaxFilingResource {
        /* @var User $user */
        $user = $request->user();

        $data = $request->validated();

        DB::beginTransaction();

        $create_query = collect($data)
            ->except("documents")
            ->filter(function ($value, $key) {
                return !is_null($value);
            });

        $taxFiling = $user->tax_filings()->create($create_query->toArray());

        if (isset($data["documents"])) {
            $tax_documents = $make_tax_documents_action->execute(
                $data["documents"]
            );

            $taxFiling->documents()->createMany($tax_documents);
        }

        DB::commit();

        return new TaxFilingResource($taxFiling);
    }

    public function updateDraft(
        UpdateDraftTaxFilingRequest $request,
        TaxFiling $tax_filing,
        MakeTaxDocumentsAction $make_tax_documents_action
    ): TaxFilingResource {
        $data = $request->validated();
        $tax_filing->load("documents");

        DB::beginTransaction();

        $update_query = collect($data)
            ->except("documents")
            ->filter(function ($value, $key) {
                return !is_null($value);
            });

        $tax_filing->update($update_query->toArray());

        if (isset($data["documents"])) {
            Log::info("doc u ment");
            $tax_documents = $make_tax_documents_action->execute(
                $data["documents"]
            );

            $documents = collect();

            foreach ($tax_documents as $document) {
                $document_exists = $tax_filing->documents->first(function (
                    TaxDocument $tax_document
                ) use ($document) {
                    return $tax_document->kind_id === $document["kind_id"] &&
                        $tax_document->file_id === $document["file_id"];
                });

                if ($document_exists) {
                    continue;
                }

                $documents->push($document);
            }

            $tax_filing->documents()->createMany($documents);
        }

        DB::commit();

        return new TaxFilingResource($tax_filing->refresh());
    }

    /**
     * @throws InvalidTaxFilingException
     */
    public function submitDraft(
        Request $request,
        TaxFiling $taxFiling,
        ValidateTaxFilingAction $validateTaxFilingAction
    ): TaxFilingResource|JsonResponse {
        /* @var User $user */
        $user = $request->user();

        $user->load("tax_filings");

        Log::info("hello");

        if ($user->tax_filings->doesntContain($taxFiling)) {
            return response()->json(["message" => "Tax filing not found"], 404);
        }

        Log::info("hello1");
        return new TaxFilingResource(
            $validateTaxFilingAction->execute($taxFiling)
        );
    }

    public function getDocumentKinds(): JsonResponse
    {
        $kinds = TaxDocumentKind::all();
        return response()->json($kinds);
    }
}
