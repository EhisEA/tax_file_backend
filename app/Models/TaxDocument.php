<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $kind_id
 * @property int $tax_filing_id
 * @property int $file_id
 * @property-read \App\Models\File $file
 * @property-read \App\Models\TaxDocumentKind $kind
 * @property-read \App\Models\TaxFiling $taxFiling
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxDocument newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxDocument newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxDocument query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxDocument whereFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxDocument whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxDocument whereKindId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxDocument whereTaxFilingId($value)
 *
 * @mixin \Eloquent
 */
class TaxDocument extends Model
{
    public $timestamps = false;

    protected $with = ['kind', 'file'];

    protected $fillable = ['kind_id', 'file_id'];

    public function kind(): BelongsTo
    {
        return $this->belongsTo(TaxDocumentKind::class, 'kind_id');
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
