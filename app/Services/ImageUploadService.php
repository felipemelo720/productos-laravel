<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageUploadService
{
    public function uploadProductImage(int $productId, UploadedFile $file): string
    {
        $path = "products/{$productId}";
        $filename = time() . '_' . $file->getClientOriginalName();
        return Storage::putFileAs($path, $file, $filename);
    }

    public function deleteProductImage(string $path): bool
    {
        return Storage::delete($path);
    }
}
