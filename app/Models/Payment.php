<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Str;

/**
 *
 *
 * @property string $id
 * @property string $invoice_id
 * @property string|null $stripe_payment_intent_id
 * @property string $total
 * @property string $charged_amount
 * @property string $discount
 * @property PaymentStatus $status
 * @property int $user_id
 * @property int $tax_filing_id
 * @property string|null $completed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\TaxFiling|null $taxFilings
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereChargedAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereStripePaymentIntentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereTaxFilingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereUserId($value)
 * @mixin \Eloquent
 */
class Payment extends Model
{
    use HasUlids;

    protected $fillable = [
        "invoice_id",
        "total",
        "discount",
        "charged_amount",
        "status",
        "user_id",
        "stripe_payment_intent_id",
        "completed_at",
    ];

    protected function casts()
    {
        return [
            "status" => PaymentStatus::class,
        ];
    }

    public function taxFilings(): BelongsTo
    {
        return $this->belongsTo(TaxFiling::class);
    }
}
