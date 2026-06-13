<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $userId = $request->user()?->id;
        $lastMessage = $this->when(
            isset($this->lastMessage),
            fn () => $this->lastMessage ? new MessageResource($this->lastMessage) : null,
        );

        $otherParticipant = null;
        if (isset($this->otherParticipant) && $this->otherParticipant instanceof User) {
            $otherParticipant = new UserResource($this->otherParticipant);
        } elseif ($userId && $this->relationLoaded('seller') && $this->relationLoaded('buyer')) {
            $other = (int) $this->buyerId === (int) $userId ? $this->seller : $this->buyer;
            $otherParticipant = new UserResource($other);
        }

        return [
            'id' => $this->id,
            'auction' => $this->whenLoaded('auction', fn () => new AuctionResource($this->auction)),
            'seller' => $this->whenLoaded('seller', fn () => new UserResource($this->seller)),
            'buyer' => $this->whenLoaded('buyer', fn () => new UserResource($this->buyer)),
            'otherParticipant' => $otherParticipant,
            'lastMessage' => $lastMessage,
            'isUnread' => (bool) ($this->isUnread ?? false),
            'messages' => $this->whenLoaded(
                'messages',
                fn () => MessageResource::collection($this->messages),
            ),
        ];
    }
}
