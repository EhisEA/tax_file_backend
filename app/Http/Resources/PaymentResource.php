<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this['id'],
            'invoice_id' => $this['invoice_id'],
            'total' => $this['total'],
            'charged_amount' => $this['charged_amount'],
            'discount' => $this['dicount'] ?? 0,
            'status' => $this['status'],
            'completed_at' => $this['completed_at'],
            'created_at' => $this['created_at'],
        ];
    }
}
