<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WooCommerceService
{
    private string $baseUrl;
    private string $consumerKey;
    private string $consumerSecret;
    private int $maxRetries = 3;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.woocommerce.store_url'), '/');
        $this->consumerKey = config('services.woocommerce.consumer_key');
        $this->consumerSecret = config('services.woocommerce.consumer_secret');
    }

    public function getCategories()
    {
        return Cache::remember('wc_categories', 3600, function () {
            return $this->get('/products/categories', ['per_page' => 100]);
        });
    }

    public function getTags()
    {
        return Cache::remember('wc_tags', 3600, function () {
            return $this->get('/products/tags', ['per_page' => 100]);
        });
    }

    public function getAttributes()
    {
        return Cache::remember('wc_attributes', 3600, function () {
            return $this->get('/products/attributes', ['per_page' => 100]);
        });
    }

    public function getBrands()
    {
        return Cache::remember('wc_brands', 3600, function () {
            return $this->get('/products/brands', ['per_page' => 100]);
        });
    }

    public function createProduct(array $data)
    {
        return $this->post('/products', $data);
    }

    public function updateProduct(int $wcProductId, array $data)
    {
        return $this->put("/products/{$wcProductId}", $data);
    }

    public function createVariation(int $wcProductId, array $data)
    {
        return $this->post("/products/{$wcProductId}/variations", $data);
    }

    public function updateVariation(int $wcProductId, int $variationId, array $data)
    {
        return $this->put("/products/{$wcProductId}/variations/{$variationId}", $data);
    }

    public function getProduct(int $wcProductId)
    {
        return $this->get("/products/{$wcProductId}");
    }

    public function uploadMedia(string $filePath, string $fileName)
    {
        return $this->postFile($filePath, $fileName);
    }

    private function get(string $endpoint, array $params = [])
    {
        return $this->request('GET', $endpoint, params: $params);
    }

    private function post(string $endpoint, array $data = [])
    {
        return $this->request('POST', $endpoint, data: $data);
    }

    private function put(string $endpoint, array $data = [])
    {
        return $this->request('PUT', $endpoint, data: $data);
    }

    private function postFile(string $filePath, string $fileName)
    {
        // WordPress media API lives at wp/v2/media, not wc/v3
        $url = "{$this->baseUrl}/wp-json/wp/v2/media";
        $url = $this->addAuth($url);

        for ($attempt = 0; $attempt < $this->maxRetries; $attempt++) {
            try {
                $response = Http::attach('file', fopen($filePath, 'r'), $fileName)
                    ->post($url);

                if ($response->successful()) {
                    return $response->json();
                }

                if ($response->serverError()) {
                    sleep(2 ** $attempt);
                    continue;
                }

                Log::error("WC uploadMedia failed: {$response->status()} {$response->body()}");
                return null;
            } catch (\Exception $e) {
                Log::error("WC uploadMedia error: {$e->getMessage()}");
                if ($attempt === $this->maxRetries - 1) throw $e;
                sleep(2 ** $attempt);
            }
        }

        return null;
    }

    private function request(string $method, string $endpoint, array $data = [], array $params = [])
    {
        $url = "{$this->baseUrl}/wp-json/wc/v3{$endpoint}";
        $url = $this->addAuth($url);

        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        for ($attempt = 0; $attempt < $this->maxRetries; $attempt++) {
            try {
                $response = Http::$method($url, $data);

                if ($response->successful()) {
                    return $response->json();
                }

                if ($response->serverError() && $attempt < $this->maxRetries - 1) {
                    sleep(2 ** $attempt);
                    continue;
                }

                Log::error("WC $method $endpoint failed: {$response->status()} {$response->body()}");
                return null;
            } catch (\Exception $e) {
                Log::error("WC request error: {$e->getMessage()}");
                if ($attempt === $this->maxRetries - 1) throw $e;
                sleep(2 ** $attempt);
            }
        }

        return null;
    }

    private function addAuth(string $url): string
    {
        $separator = parse_url($url, PHP_URL_QUERY) ? '&' : '?';
        return "{$url}{$separator}consumer_key={$this->consumerKey}&consumer_secret={$this->consumerSecret}";
    }
}
