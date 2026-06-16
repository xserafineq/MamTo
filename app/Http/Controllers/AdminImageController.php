<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateAdminImageRequest;
use App\Models\Image;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminImageController extends Controller
{
    public function index(): View
    {
        $images = Image::query()
            ->orderByDesc('uploadedAt')
            ->paginate(20)
            ->withQueryString();

        return view('admin.images', compact('images'));
    }

    public function update(UpdateAdminImageRequest $request, Image $image, ImageService $imageService): RedirectResponse
    {
        try {
            $imageService->replaceImage($image, $request->file('image'));
        } catch (\RuntimeException $exception) {
            return redirect()
                ->route('admin.images.index')
                ->withErrors(['image' => $exception->getMessage()]);
        }

        return redirect()
            ->route('admin.images.index')
            ->with('success', 'Zdjęcie zostało podmienione.');
    }

    public function destroy(Image $image, ImageService $imageService): RedirectResponse
    {
        try {
            $imageService->deleteImage($image);
        } catch (\RuntimeException $exception) {
            return redirect()
                ->route('admin.images.index')
                ->withErrors(['image' => $exception->getMessage()]);
        }

        return redirect()
            ->route('admin.images.index')
            ->with('success', 'Zdjęcie zostało usunięte.');
    }
}
