<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'sentAt' => $this->sentAt?->toIso8601String(),
            'sender' => $this->whenLoaded('sender', fn () => new UserResource($this->sender)),
            'senderId' => $this->senderId,
        ];
    }
}
