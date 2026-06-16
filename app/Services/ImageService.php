<?php

namespace App\Services;

use App\Models\Auction;
use App\Models\Image;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    public const PLACEHOLDER_UUID = '11111111-1111-4111-8111-111111111111';

    /**
     * Zapisuje plik fizycznie jako UUID, a w bazie zapisuje oryginalną nazwę.
     */
    public function storeImage(UploadedFile $file): Image
    {
        $originalName = $file->getClientOriginalName();
        $uuid = (string) Str::uuid();
        $diskName = $uuid . '.' . $file->getClientOriginalExtension();
        $stored = $file->storeAs('images', $diskName, 'public');

        if ($stored === false) {
            throw new \RuntimeException('Nie udało się zapisać pliku na dysku.');
        }

        return Image::create([
            'uuid' => $uuid,
            'filename' => $originalName,
            'uploadedAt' => now(),
        ]);
    }

    public function isPlaceholder(Image $image): bool
    {
        return $image->uuid === self::PLACEHOLDER_UUID;
    }

    public function getPlaceholderId(): ?int
    {
        return Image::query()
            ->where('uuid', self::PLACEHOLDER_UUID)
            ->value('id');
    }

    public function deleteImage(Image $image): void
    {
        if ($this->isPlaceholder($image)) {
            throw new \RuntimeException('Nie można usunąć domyślnego zdjęcia systemowego.');
        }

        $placeholderId = $this->getPlaceholderId();

        if (! $placeholderId) {
            throw new \RuntimeException('Brak domyślnego zdjęcia w systemie.');
        }

        DB::transaction(function () use ($image, $placeholderId) {
            Auction::query()
                ->where('imageId', $image->id)
                ->update(['imageId' => $placeholderId]);

            $image->additionalAuctions()->detach();

            if ($image->fileExists()) {
                Storage::disk('public')->delete($image->diskPath());
            }

            $image->delete();
        });
    }

    public function replaceImage(Image $image, UploadedFile $file): Image
    {
        $oldPath = $image->diskPath();

        $diskName = $image->uuid . '.' . $file->getClientOriginalExtension();
        $stored = $file->storeAs('images', $diskName, 'public');

        if ($stored === false) {
            throw new \RuntimeException('Nie udało się zapisać pliku na dysku.');
        }

        if ($oldPath !== 'images/' . $diskName && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }

        $image->update([
            'filename' => $file->getClientOriginalName(),
            'uploadedAt' => now(),
        ]);

        return $image->fresh();
    }
}
