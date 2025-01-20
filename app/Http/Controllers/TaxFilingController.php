<?php

use App\Models\TaxDocument;

namespace App\Http\Controllers;

use App\Actions\ValidateTaxFilingAction;
use App\Exceptions\InvalidTaxFilingException;
use App\Http\Requests\SubmitDraftTaxFilingRequest;
use App\Http\Requests\SubmitTaxFilingRequest;
use App\Http\Requests\UpdateDraftTaxFilingRequest;
use App\Http\Resources\TaxFilingResource;
use App\Models\File;
use App\Models\TaxDocumentkind;
use App\Models\TaxFiling;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaxFilingController extends Controller
{
    public function index()
    {
    }

    public function show(TaxFiling $taxFiling)
    {
    }

    public function submit(SubmitTaxFilingRequest $request): TaxFilingResource
    {
        $data = $request->validated();

        DB::beginTransaction();

        $taxFiling = TaxFiling::query()->create(collect($data)->except('documents')
            ->merge(['submitted_at' => Carbon::today()])->toArray());

        $tax_documents = collect();
        foreach ($data['documents'] as $document) {
            $file = File::query()->where('id', "=", $document["file_id"])->sole();
            $document_kind = TaxDocumentkind::query()->where('kind_id', '=', $document['kind_id'])->sole();

            $tax_documents->push([
                'kind_id' => $document_kind->id,
                'file_id' => $file->id
            ]);
        }

        $taxFiling->documents()->createMany($tax_documents);

        DB::commit();

        return new TaxFilingResource($taxFiling);
    }

    public function storeDraft(SubmitDraftTaxFilingRequest $request): TaxFilingResource
    {
        $data = $request->validated();

        DB::beginTransaction();

        $create_query = collect($data)->except('documents')->filter(function ($value, $key) {
            return !is_null($value);
        });

        $taxFiling = TaxFiling::query()->create($create_query->toArray());

        if (isset($data['documents'])) {
            $tax_documents = collect();

            foreach ($data['documents'] as $document) {
                $file = File::query()->where('id', "=", $document["file_id"])->sole();
                $document_kind = TaxDocumentkind::query()->where('kind_id', '=', $document['kind_id'])->sole();

                $tax_documents->push([
                    'kind_id' => $document_kind->id,
                    'file_id' => $file->id
                ]);
            }

            $taxFiling->documents()->createMany($tax_documents);
        }

        DB::commit();

        return new TaxFilingResource($taxFiling);
    }

    public function updateDraft(UpdateDraftTaxFilingRequest $request, TaxFiling $taxFiling): TaxFilingResource
    {
        $data = $request->validated();
        $taxFiling->load('documents');

        DB::beginTransaction();

        $update_query = collect($data)->except('documents')->filter(function ($value, $key) {
            return !is_null($value);
        });

        $taxFiling->update($update_query);

        if (isset($data['documents'])) {
            $tax_documents = collect();

            foreach ($data['documents'] as $document) {
                $file = File::query()->where('id', "=", $document["file_id"])->sole();
                $document_kind = TaxDocumentkind::query()->where('kind_id', '=', $document['kind_id'])->sole();

                // update existing documents of this type with the new uploaded files
                /* @var TaxDocument $existing_document */
                $existing_document = $taxFiling->documents->firstWhere('kind_id', '=', $document_kind->id);
                $existing_document?->update(['file_id', $file->id]);

                $tax_documents->push([
                    'kind_id' => $document_kind->id,
                    'file_id' => $file->id
                ]);
            }

            $taxFiling->documents()->createMany($tax_documents);
        }

        DB::commit();

        return new TaxFilingResource($taxFiling);
    }

    /**
     * @throws InvalidTaxFilingException
     */
    public function submitDraft(TaxFiling $taxFiling, ValidateTaxFilingAction $validateTaxFilingAction): TaxFilingResource
    {
        return new TaxFilingResource($validateTaxFilingAction->execute($taxFiling));
    }

    public function getDocumentKinds(Request $request, TaxFiling $taxFiling): JsonResponse
    {
        $kinds = TaxDocumentkind::all();
        return response()->json($kinds);
    }
}
