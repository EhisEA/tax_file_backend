<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaxDocument extends Model
{
    protected $with = ["kind", "file", "taxFiling"];
    protected $fillable = ['kind_id', 'file_id'];

    public function kind(): BelongsTo
    {
        return $this->belongsTo(TaxDocumentkind::class, "kind_id");
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    public function taxFiling(): BelongsTo
    {
        return $this->belongsTo(TaxFiling::class);
    }
}
