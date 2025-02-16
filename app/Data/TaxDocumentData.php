<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class TaxDocumentData extends Data
{
    public function __construct(public int $file_id, public string $name) {}
}
