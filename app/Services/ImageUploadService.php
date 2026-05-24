<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class ImageUploadService
{
    public function uploadProductImage(int $productId, UploadedFile $file, bool $isPrimary = false): string
    {
        $dir = public_path("assets/uploads/products/{$productId}");
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $prefix   = $isPrimary ? 'main' : 'gallery';
        $ext      = $file->getClientOriginalExtension() ?: $file->guessExtension();
        $filename = "{$prefix}_" . uniqid('', true) . ".{$ext}";

        $file->move($dir, $filename);

        return "assets/uploads/products/{$productId}/{$filename}";
    }

    public function deleteProductImage(string $imagePath): bool
    {
        $fullPath = public_path($imagePath);
        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }
        return false;
    }
}
