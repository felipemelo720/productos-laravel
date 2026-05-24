<?php

namespace App\Console\Commands;

use App\Models\ProductBrand;
use App\Models\ProductCategory;
use App\Models\ProductTag;
use App\Services\WooCommerceService;
use Illuminate\Console\Command;

class SyncWooCommerceData extends Command
{
    protected $signature = 'wc:sync {--type=all : categories, brands, tags, or all}';
    protected $description = 'Sync WooCommerce categories, brands and tags to local DB';

    public function handle(WooCommerceService $wc): int
    {
        $type = $this->option('type');

        if ($type === 'all' || $type === 'categories') {
            $this->syncCategories($wc);
        }

        if ($type === 'all' || $type === 'brands') {
            $this->syncBrands($wc);
        }

        if ($type === 'all' || $type === 'tags') {
            $this->syncTags($wc);
        }

        return 0;
    }

    private function syncCategories(WooCommerceService $wc): void
    {
        $this->info('Syncing categories...');
        $items = $wc->getCategories();

        if (!$items) {
            $this->error('Failed to fetch categories');
            return;
        }

        foreach ($items as $item) {
            ProductCategory::updateOrCreate(
                ['wc_category_id' => $item['id']],
                ['name' => $item['name']]
            );
        }

        $this->info('Categories synced: ' . count($items));
    }

    private function syncBrands(WooCommerceService $wc): void
    {
        $this->info('Syncing brands...');
        $items = $wc->getBrands();

        if (!$items) {
            $this->error('Failed to fetch brands');
            return;
        }

        foreach ($items as $item) {
            ProductBrand::updateOrCreate(
                ['wc_brand_id' => $item['id']],
                ['name' => $item['name'], 'slug' => $item['slug'] ?? null]
            );
        }

        $this->info('Brands synced: ' . count($items));
    }

    private function syncTags(WooCommerceService $wc): void
    {
        $this->info('Syncing tags...');
        $items = $wc->getTags();

        if (!$items) {
            $this->error('Failed to fetch tags');
            return;
        }

        foreach ($items as $item) {
            ProductTag::updateOrCreate(
                ['wc_tag_id' => $item['id']],
                ['name' => $item['name']]
            );
        }

        $this->info('Tags synced: ' . count($items));
    }
}
