<?php
namespace App\Services;

use App\Models\Image;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ImageService
{
    /**
     * Zapisuje plik fizycznie jako UUID, a w bazie zapisuje oryginalną nazwę.
     */
    public function storeImage(UploadedFile $file): Image
    {
        // pobieranie oryginalnej nazwy pliku
        $originalName = $file->getClientOriginalName();

        // generacja UUID i doklejanie rozszerzenia pliku
        $uuid = (string) Str::uuid();
        $diskName = $uuid . '.' . $file->getClientOriginalExtension();

        // zapis pliku na dysku w folderze 'images'
        $file->storeAs('images', $diskName, 'public');

        // zapisu rekordu w bazie danych
        return Image::create([
            'uuid' => $uuid,
            'filename' => $originalName,
            'uploadedAt' => now(),
        ]);
    }
}
