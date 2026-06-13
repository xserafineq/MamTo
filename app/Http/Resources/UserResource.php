<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $ratingsLoaded = $this->relationLoaded('ratingsReceived');
        $averageRating = $ratingsLoaded
            ? round((float) $this->ratingsReceived->avg('rating'), 1)
            : null;

        return [
            'id' => $this->id,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'email' => $this->when($request->user()?->id === $this->id, $this->email),
            'phoneNumber' => $this->when($request->user()?->id === $this->id, $this->phoneNumber),
            'isAdmin' => (bool) $this->isAdmin,
            'isMainAdmin' => (bool) $this->isMainAdmin,
            'joinedAt' => $this->joinedAt?->toIso8601String(),
            'lastOnline' => $this->lastOnline?->toIso8601String(),
            'averageRating' => $averageRating,
        ];
    }
}
