<?php

namespace App\Actions;

use App\Models\File;
use App\Models\TaxDocumentKind;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class MakeTaxDocumentsAction
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * @return Collection<int, array<string, int>>
     * @param array $documents
     */
    public function execute(array $documents): Collection
    {
        $tax_documents = collect();

        Log::info("creating document");
        foreach ($documents as $document) {
            $file = File::whereId($document["file_id"])->sole();

            $document_kind = TaxDocumentKind::whereName(
                $document["name"]
            )->sole();

            $tax_documents->push([
                "kind_id" => $document_kind->id,
                "file_id" => $file->id,
            ]);
        }
        Log::info("documents created");

        return $tax_documents;
    }
}
