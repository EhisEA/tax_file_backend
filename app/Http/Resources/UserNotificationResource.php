<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserNotificationResource extends JsonResource
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
            'type' => $this['type'],
            'data' => $this['data'],
            'read_at' => $this['read_at']?->toDateTimeString(),
            'is_read' => $this['read_at'] !== null,
            'created_at' => $this['created_at']->toDateTimeString(),
            'user_id' => $this['notifiable_id'],
        ];
    }
}
