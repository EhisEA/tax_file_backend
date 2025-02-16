<?php

namespace App\Actions;

use App\Data\TaxDocumentData;
use App\Models\File;
use App\Models\TaxDocumentKind;
use Illuminate\Support\Collection;

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
     * @param  array<int, TaxDocumentData>  $documents
     * @return Collection<int, array<string, int>>
     */
    public function execute(array $documents): Collection
    {
        $tax_documents = collect();

        foreach ($documents as $document) {
            $file = File::whereId($document->file_id)->sole();

            $document_kind = TaxDocumentKind::whereName(
                $document->name
            )->sole();

            $tax_documents->push([
                'kind_id' => $document_kind->id,
                'file_id' => $file->id,
            ]);
        }

        return $tax_documents;
    }
}
