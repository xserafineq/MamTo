<?php

namespace App\Http\Resources;

use App\Models\Category;
use App\Services\AuctionService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuctionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $viewer = $request->user();
        $isOwner = $viewer && (int) $viewer->id === (int) $this->userId;
        $isAdmin = $viewer && $viewer->isAdmin;
        $displayPhone = null;
        if ($this->relationLoaded('user') && $this->user) {
            $displayPhone = app(AuctionService::class)->formatDisplayPhone(
                $this->user->phoneNumber,
                $isOwner || $isAdmin,
            );
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'negotiable' => (bool) $this->negotiable,
            'salaryType' => $this->salaryType,
            'location' => $this->location,
            'status' => $this->status,
            'approved' => (bool) $this->approved,
            'isJobOffer' => Category::requiresApproval($this->categoryId),
            'createdAt' => $this->createdAt?->toIso8601String(),
            'updatedAt' => $this->updatedAt?->toIso8601String(),
            'category' => $this->whenLoaded('category', fn () => new CategoryResource($this->category)),
            'image' => $this->whenLoaded('image', fn () => new ImageResource($this->image)),
            'additionalImages' => $this->whenLoaded(
                'additionalImages',
                fn () => ImageResource::collection($this->additionalImages),
            ),
            'user' => $this->whenLoaded('user', fn () => new UserResource($this->user)),
            'sellerPhone' => $this->when($this->relationLoaded('user'), $displayPhone),
            'isOwner' => $isOwner,
        ];
    }
}
