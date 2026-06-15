<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Services\ImageService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ImageSeeder extends Seeder
{
    private const FILENAME = 'placeholder.png';

    public function run(): void
    {
        $uuid = ImageService::PLACEHOLDER_UUID;
        $diskName = $uuid . '.png';
        $targetPath = 'images/' . $diskName;
        $source = public_path('assets/placeholder.png');

        if (!Storage::disk('public')->exists($targetPath)) {
            if (!file_exists($source)) {
                $this->command->warn('Brak pliku źródłowego: ' . $source);

                return;
            }

            Storage::disk('public')->put($targetPath, File::get($source));
        }

        Image::firstOrCreate(
            ['uuid' => $uuid],
            [
                'filename' => self::FILENAME,
                'uploadedAt' => now(),
            ]
        );
    }
}
