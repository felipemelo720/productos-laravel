<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class ImagifyService
{
    private string $apiKey;
    private string $apiUrl = 'https://app.imagify.io/api/upload';

    public function __construct()
    {
        $this->apiKey = config('services.imagify.key');
    }

    public function compressImage(string $filePath): bool
    {
        try {
            if (!file_exists($filePath)) {
                Log::error("File not found for compression: {$filePath}");
                return false;
            }

            // Backup original
            $backupPath = $filePath . '.bak';
            if (!copy($filePath, $backupPath)) {
                Log::warning("Failed to create backup: {$filePath}");
            }

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}"
            ])->attach('file', fopen($filePath, 'r'), basename($filePath))
                ->post($this->apiUrl);

            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data['output']['url'])) {
                    // Download optimized image and replace original
                    $optimized = Http::get($data['output']['url'])->body();
                    if (file_put_contents($filePath, $optimized)) {
                        Log::info("Image optimized: {$filePath}");
                        return true;
                    }
                }
            }

            Log::error("Imagify compression failed: {$response->status()} {$response->body()}");
            // Restore from backup on failure
            if (file_exists($backupPath)) {
                copy($backupPath, $filePath);
                unlink($backupPath);
            }
            return false;
        } catch (\Exception $e) {
            Log::error("Imagify error: {$e->getMessage()}");
            return false;
        }
    }

    public function compressProductImages(int $productId): int
    {
        $optimized = 0;
        $imagesPath = storage_path("app/products/{$productId}");

        if (!is_dir($imagesPath)) {
            return 0;
        }

        foreach (glob("{$imagesPath}/*.{jpg,jpeg,png}", GLOB_BRACE) as $imagePath) {
            if ($this->compressImage($imagePath)) {
                $optimized++;
            }
        }

        return $optimized;
    }
}
