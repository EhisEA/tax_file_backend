<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReferralResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this["id"],
            "referrer_id" => $this["referrer_id"],
            "status" =>
                $this["tax_filed_at"] === null ? "In progress" : "Completed",
            "created_at" => $this["created_at"],
            "amount_earned" => 100,
            "referree_id" => $this["referree_id"],
            "referree_name" => $this->whenLoaded("user.profile")
                ? $this["user"]["profile"]["first_name"] .
                    " " .
                    $this["user"]["profile"]["last_name"]
                : null,
        ];
    }
}
