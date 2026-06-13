<?php

namespace Database\Seeders;

use App\Models\Image;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ImageSeeder extends Seeder
{
    private const UUID = '11111111-1111-4111-8111-111111111111';

    private const FILENAME = 'placeholder.png';

    public function run(): void
    {
        $diskName = self::UUID . '.png';
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
            ['uuid' => self::UUID],
            [
                'filename' => self::FILENAME,
                'uploadedAt' => now(),
            ]
        );
    }
}
