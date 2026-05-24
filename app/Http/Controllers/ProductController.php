<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\ProductCategory;
use App\Models\ProductTag;
use App\Models\ProductAttribute;
use App\Models\ProductVersion;
use App\Models\ExportLog;
use App\Services\WooCommerceService;
use App\Services\ImageUploadService;
use App\Services\OpenAIService;
use App\Services\ImagifyService;
use App\Mail\ProductSentMail;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('sku', 'like', "%{$request->search}%");
        }

        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $products = $query->orderByDesc('id')->with(['images' => fn($q) => $q->where('is_primary', true)->limit(1)])->paginate(20);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $attributeNames = ProductAttribute::select('name')->distinct()->orderBy('name')->pluck('name');
        $wcBrands       = ProductBrand::orderBy('name')->get();
        $wcCategories   = ProductCategory::orderBy('name')->get();
        $wcTags         = ProductTag::orderBy('name')->get();
        return view('products.create', compact('attributeNames', 'wcBrands', 'wcCategories', 'wcTags'));
    }

    public function store(Request $request, ImageUploadService $imageService)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'sku'              => 'required|string|unique:products',
            'description'      => 'nullable|string',
            'short_description'=> 'nullable|string',
            'brand'            => 'nullable|string',
            'wc_brand_id'      => 'nullable|integer',
            'custom_tags'      => 'nullable|string',
            'categories'       => 'nullable|array',
            'categories.*'     => 'integer',
            'tags'             => 'nullable|array',
            'tags.*'           => 'integer',
            'images'           => 'nullable|array',
            'images.*'         => 'image|max:5120',
            'attributes'       => 'nullable|array',
            'attributes.*.name'  => 'required_with:attributes.*.value|nullable|string|max:255',
            'attributes.*.value' => 'required_with:attributes.*.name|nullable|string|max:255',
        ]);

        $attributes         = $validated['attributes'] ?? [];
        $incomingCategories = $validated['categories'] ?? [];
        $incomingTags       = $validated['tags'] ?? [];
        unset($validated['attributes'], $validated['categories'], $validated['tags']);

        // Resolve brand name from wc_brand_id if provided
        if (!empty($validated['wc_brand_id'])) {
            $brand = ProductBrand::where('wc_brand_id', $validated['wc_brand_id'])->first();
            if ($brand) {
                $validated['brand'] = $brand->name;
            }
        }

        $validated['status']     = 'published';
        $validated['type']       = 'simple';
        $validated['slug']       = $this->uniqueSlug($validated['name']);
        $validated['created_by'] = auth()->id();

        $product = Product::create($validated);

        if (!empty($incomingCategories)) {
            $product->categories()->sync($incomingCategories);
        }

        if (!empty($incomingTags)) {
            $tagData = ProductTag::whereIn('id', $incomingTags)->get()
                ->mapWithKeys(fn($t) => [$t->id => ['tag_name' => $t->name]]);
            $product->tags()->sync($tagData);
        }

        foreach ($attributes as $attr) {
            if (empty($attr['name']) || empty($attr['value'])) {
                continue;
            }
            $product->attributes()->create([
                'name' => trim($attr['name']),
                'value' => trim($attr['value']),
            ]);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $isPrimary = $index === 0;
                $path = $imageService->uploadProductImage($product->id, $file, $isPrimary);
                $product->images()->create([
                    'image_path' => $path,
                    'is_primary' => $isPrimary,
                    'sort_order' => $index,
                ]);
            }
        }

        $this->createVersion($product, 'create', 'Product created');

        return redirect()->route('products.show', $product)->with('success', 'Producto creado');
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'producto';
        $slug = $base;
        $i = 2;
        while (Product::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }
        return $slug;
    }

    public function show(Product $product)
    {
        $this->authorize('view', $product);
        $product->load([
            'images' => fn($q) => $q->orderByDesc('is_primary')->orderBy('sort_order'),
            'attributes',
            'categories',
            'tags',
        ]);
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product);
        $product->load('attributes', 'categories', 'tags');
        $attributeNames = ProductAttribute::select('name')->distinct()->orderBy('name')->pluck('name');
        $wcCategories   = ProductCategory::orderBy('name')->get();
        $wcBrands       = ProductBrand::orderBy('name')->get();
        $wcTags         = ProductTag::orderBy('name')->get();
        return view('products.edit', compact('product', 'attributeNames', 'wcCategories', 'wcBrands', 'wcTags'));
    }

    public function update(Request $request, Product $product, ImageUploadService $imageService)
    {
        $this->authorize('update', $product);

        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'sku'              => "required|string|unique:products,sku,{$product->id}",
            'description'      => 'nullable|string',
            'short_description'=> 'nullable|string',
            'brand'            => 'nullable|string',
            'wc_brand_id'      => 'nullable|integer',
            'custom_tags'      => 'nullable|string',
            'categories'       => 'nullable|array',
            'categories.*'     => 'integer',
            'tags'             => 'nullable|array',
            'tags.*'           => 'integer',
            'images'           => 'nullable|array',
            'images.*'         => 'image|max:5120',
            'delete_images'    => 'nullable|array',
            'delete_images.*'  => 'integer',
            'attributes'       => 'nullable|array',
            'attributes.*.name'  => 'required_with:attributes.*.value|nullable|string|max:255',
            'attributes.*.value' => 'required_with:attributes.*.name|nullable|string|max:255',
        ]);

        $incomingAttributes = $validated['attributes'] ?? [];
        $incomingCategories = $validated['categories'] ?? [];
        $incomingTags       = $validated['tags'] ?? [];
        unset($validated['attributes'], $validated['categories'], $validated['tags']);

        // Resolve brand name from wc_brand_id if provided
        if (!empty($validated['wc_brand_id'])) {
            $brand = ProductBrand::where('wc_brand_id', $validated['wc_brand_id'])->first();
            if ($brand) {
                $validated['brand'] = $brand->name;
            }
        }

        $validated['slug'] = Str::slug($validated['name']);
        $product->update($validated);

        // Sync categories
        $product->categories()->sync($incomingCategories);

        // Sync tags
        $tagPivot = [];
        foreach ($incomingTags as $tagId) {
            $tag = ProductTag::find($tagId);
            if ($tag) {
                $tagPivot[$tagId] = ['tag_name' => $tag->name, 'is_custom' => false];
            }
        }
        $product->tags()->sync($tagPivot);

        $product->attributes()->delete();
        foreach ($incomingAttributes as $attr) {
            if (empty($attr['name']) || empty($attr['value'])) {
                continue;
            }
            $product->attributes()->create([
                'name' => trim($attr['name']),
                'value' => trim($attr['value']),
            ]);
        }

        if ($request->filled('delete_images')) {
            $toDelete = $product->images()->whereIn('id', $request->delete_images)->get();
            foreach ($toDelete as $img) {
                $imageService->deleteProductImage($img->image_path);
                $img->delete();
            }
            if (!$product->images()->where('is_primary', true)->exists()) {
                $product->images()->oldest()->limit(1)->update(['is_primary' => true]);
            }
        }

        if ($request->hasFile('images')) {
            $hasPrimary = $product->images()->where('is_primary', true)->exists();
            foreach ($request->file('images') as $index => $file) {
                $isPrimary = !$hasPrimary && $index === 0;
                $path = $imageService->uploadProductImage($product->id, $file, $isPrimary);
                $product->images()->create([
                    'image_path' => $path,
                    'is_primary' => $isPrimary,
                    'order'      => $product->images()->count(),
                ]);
                if ($isPrimary) $hasPrimary = true;
            }
        }

        $this->createVersion($product, 'update', 'Product updated');

        return redirect()->route('products.show', $product)->with('success', 'Product updated');
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);
        $this->createVersion($product, 'delete', 'Product deleted');
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted');
    }

    public function duplicate(Product $product)
    {
        $this->authorize('view', $product);

        $newProduct = $product->replicate();
        $newProduct->slug = Str::slug($product->name . ' copy-' . time());
        $newProduct->sku = $product->sku . '-copia-' . time();
        $newProduct->wc_product_id = null;
        $newProduct->save();

        // Copy images, variations, attributes
        foreach ($product->images as $image) {
            $newProduct->images()->create($image->only(['image_path', 'is_primary', 'sort_order']));
        }
        foreach ($product->variations as $variation) {
            $newProduct->variations()->create($variation->only(['sku', 'regular_price', 'sale_price', 'image_path', 'is_default']));
        }
        foreach ($product->attributes as $attribute) {
            $newProduct->attributes()->create($attribute->only(['name', 'value', 'wc_attribute_id', 'wc_term_id']));
        }

        $this->createVersion($newProduct, 'duplicate', 'Duplicated from product #' . $product->id);

        return redirect()->route('products.show', $newProduct)
            ->with('success', 'Producto duplicado correctamente.')
            ->with('warning', 'El SKU fue modificado automáticamente, recuerda actualizarlo.');
    }

    public function export(Product $product, WooCommerceService $wc)
    {
        $response = $this->createInWoocommerce($product, $wc);
        $data = $response->getData(true);

        if (!empty($data['success'])) {
            return redirect()->route('products.show', $product)->with('success', $data['message'] ?? 'Product exported to WooCommerce');
        }

        return redirect()->back()->with('error', $data['message'] ?? 'Export failed');
    }

    public function createInWoocommerce(Product $product, WooCommerceService $wc): JsonResponse
    {
        $this->authorize('update', $product);

        $product->load('images', 'attributes', 'categories', 'tags');

        $action = $product->wc_product_id ? 'updated' : 'created';

        try {
            $payload = $this->buildWCPayload($product);

            if ($product->images->isNotEmpty()) {
                $payload['images'] = $product->images->map(fn($image) => [
                    'src' => url($image->image_path),
                ])->toArray();
            }

            // Create or update
            if ($product->wc_product_id) {
                $wc->updateProduct($product->wc_product_id, $payload);
                $wcId = $product->wc_product_id;
            } else {
                $result = $wc->createProduct($payload);
                if (!$result || !isset($result['id'])) {
                    throw new \Exception('Failed to create product in WooCommerce');
                }
                $wcId = $result['id'];
                $product->update(['wc_product_id' => $wcId]);
            }

            // Handle variations if variable product
            if ($product->type === 'variable' && $product->variations()->exists()) {
                foreach ($product->variations as $variation) {
                    $varPayload = [
                        'sku' => $variation->sku,
                        'regular_price' => $variation->regular_price,
                        'sale_price' => $variation->sale_price,
                    ];

                    if ($variation->wc_variation_id) {
                        $wc->updateVariation($wcId, $variation->wc_variation_id, $varPayload);
                    } else {
                        $varResult = $wc->createVariation($wcId, $varPayload);
                        if ($varResult) {
                            $variation->update(['wc_variation_id' => $varResult['id']]);
                        }
                    }
                }
            }

            // Log export
            ExportLog::create([
                'product_id' => $product->id,
                'product_name' => $product->name,
                'user_id' => auth()->id(),
                'user_name' => auth()->user()->full_name,
                'status' => 'exitoso',
                'wc_product_id' => $wcId,
            ]);

            $this->notifyProductSent($product, $action, $wcId);

            return response()->json(['success' => true, 'message' => 'Product exported to WooCommerce']);
        } catch (\Exception $e) {
            Log::error("WC Export failed for product {$product->id}: {$e->getMessage()}");
            ExportLog::create([
                'product_id' => $product->id,
                'product_name' => $product->name,
                'user_id' => auth()->id(),
                'user_name' => auth()->user()->full_name,
                'status' => 'fallido',
                'error_msg' => $e->getMessage(),
            ]);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function generateDescription(Product $product, OpenAIService $openai): JsonResponse
    {
        $this->authorize('update', $product);

        try {
            $result = $openai->generateProductDescription([
                'name' => $product->name,
                'brand' => $product->brand,
                'type' => $product->type,
                'current' => $product->description,
            ]);

            if (!$result) {
                return response()->json(['success' => false, 'message' => 'Failed to generate descriptions'], 400);
            }

            return response()->json([
                'success' => true,
                'short_description' => $result['short_description'],
                'description' => $result['description'],
            ]);
        } catch (\Exception $e) {
            Log::error("Description generation failed: {$e->getMessage()}");
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function checkWcStatus(Product $product, WooCommerceService $wc): JsonResponse
    {
        $this->authorize('view', $product);

        try {
            if (!$product->wc_product_id) {
                return response()->json(['status' => 'not_exported']);
            }

            $wcProduct = $wc->getProduct($product->wc_product_id);
            if (!$wcProduct) {
                return response()->json(['status' => 'error']);
            }

            $status = $wcProduct['status'] ?? 'unknown';
            $product->update([
                'wc_publication_status' => $status,
                'wc_status_checked_at' => now(),
            ]);

            return response()->json(['status' => $status]);
        } catch (\Exception $e) {
            Log::error("WC Status check failed: {$e->getMessage()}");
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }
    }

    public function checkWcStatusBulk(Request $request, WooCommerceService $wc): JsonResponse
    {
        try {
            $productIds = $request->input('product_ids', []);
            $products = Product::whereIn('id', $productIds)->exported()->get();

            $results = [];
            foreach ($products as $product) {
                if (!$product->wc_product_id) {
                    $results[$product->id] = 'not_exported';
                    continue;
                }

                $wcProduct = $wc->getProduct($product->wc_product_id);
                $status = $wcProduct['status'] ?? 'error';
                $product->update([
                    'wc_publication_status' => $status,
                    'wc_status_checked_at' => now(),
                ]);
                $results[$product->id] = $status;
            }

            return response()->json(['success' => true, 'results' => $results]);
        } catch (\Exception $e) {
            Log::error("Bulk WC Status check failed: {$e->getMessage()}");
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function syncWooCommerceData(WooCommerceService $wc): JsonResponse
    {
        try {
            $categories = $wc->getCategories();
            $catCount = 0;
            if ($categories) {
                foreach ($categories as $item) {
                    ProductCategory::updateOrCreate(
                        ['wc_category_id' => $item['id']],
                        ['name' => $item['name']]
                    );
                }
                $catCount = count($categories);
            }

            $brands = $wc->getBrands();
            $brandCount = 0;
            if ($brands) {
                foreach ($brands as $item) {
                    ProductBrand::updateOrCreate(
                        ['wc_brand_id' => $item['id']],
                        ['name' => $item['name'], 'slug' => $item['slug'] ?? null]
                    );
                }
                $brandCount = count($brands);
            }

            $tags = $wc->getTags();
            $tagCount = 0;
            if ($tags) {
                foreach ($tags as $item) {
                    ProductTag::updateOrCreate(
                        ['wc_tag_id' => $item['id']],
                        ['name' => $item['name']]
                    );
                }
                $tagCount = count($tags);
            }

            \Illuminate\Support\Facades\Cache::forget('wc_categories');
            \Illuminate\Support\Facades\Cache::forget('wc_brands');
            \Illuminate\Support\Facades\Cache::forget('wc_tags');

            return response()->json([
                'success' => true,
                'message' => "Sincronizado: {$catCount} categorías, {$brandCount} marcas, {$tagCount} etiquetas",
            ]);
        } catch (\Exception $e) {
            Log::error("WC Sync failed: {$e->getMessage()}");
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function optimizeImages(Product $product, ImagifyService $imagify): JsonResponse
    {
        $this->authorize('update', $product);

        try {
            $count = $imagify->compressProductImages($product->id);
            return response()->json([
                'success' => true,
                'message' => "Optimized {$count} images",
                'count' => $count,
            ]);
        } catch (\Exception $e) {
            Log::error("Image optimization failed: {$e->getMessage()}");
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    private function buildWCPayload(Product $product): array
    {
        $payload = [
            'name' => $product->brand ? "{$product->name} - {$product->brand}" : $product->name,
            'sku' => $product->sku,
            'type' => $product->type,
            'status' => $product->status === 'published' ? 'publish' : 'draft',
            'regular_price' => (string)$product->regular_price,
            'description' => $product->description,
            'short_description' => $product->short_description,
        ];

        if ($product->sale_price) {
            $payload['sale_price'] = (string)$product->sale_price;
        }

        if ($product->categories->isNotEmpty()) {
            $payload['categories'] = $product->categories->map(fn($c) => ['id' => $c->wc_category_id])->toArray();
        }

        if ($product->tags->isNotEmpty()) {
            $payload['tags'] = $product->tags->map(fn($t) => ['id' => $t->wc_tag_id])->toArray();
        }

        if ($product->attributes->isNotEmpty()) {
            $isVariable = $product->type === 'variable';
            $payload['attributes'] = $product->attributes
                ->groupBy(fn($a) => strtolower(trim($a->name)))
                ->map(fn($group) => [
                    'name'      => $group->first()->name,
                    'options'   => $group->pluck('value')->filter()->values()->toArray(),
                    'visible'   => true,
                    'variation' => $isVariable,
                ])
                ->values()
                ->toArray();
        }

        if ($product->wc_brand_id) {
            $payload['brands'] = [['id' => $product->wc_brand_id]];
        }

        return $payload;
    }

    private function notifyProductSent(Product $product, string $action, int $wcProductId): void
    {
        $recipients = config('mail.admin_recipients', []);

        if (empty($recipients)) {
            return;
        }

        try {
            $userName = auth()->user()?->full_name ?? auth()->user()?->name;

            Mail::to($recipients)->send(new ProductSentMail(
                product: $product,
                action: $action,
                userName: $userName,
                wcProductId: $wcProductId,
            ));
        } catch (\Throwable $e) {
            Log::error("ProductSentMail failed for product {$product->id}: {$e->getMessage()}");
        }
    }

    private function createVersion(Product $product, string $changeType, string $changeDescription): void
    {
        $nextVersion = ProductVersion::where('product_id', $product->id)->max('version_number') ?? 0;
        $nextVersion++;

        ProductVersion::create([
            'product_id' => $product->id,
            'version_number' => $nextVersion,
            'name' => $product->name,
            'slug' => $product->slug,
            'short_description' => $product->short_description,
            'description' => $product->description,
            'regular_price' => $product->regular_price,
            'sale_price' => $product->sale_price,
            'sku' => $product->sku,
            'brand' => $product->brand,
            'status' => $product->status,
            'type' => $product->type,
            'custom_tags' => $product->custom_tags,
            'internal_observation' => $product->internal_observation,
            'categories_json' => rescue(fn() => $product->categories->map(fn($c) => $c->only('id', 'name'))->toJson(), '[]', false),
            'attributes_json' => rescue(fn() => $product->attributes->map(fn($a) => $a->only('id', 'name', 'value'))->toJson(), '[]', false),
            'images_json' => rescue(fn() => $product->images->map(fn($i) => $i->only('image_path', 'is_primary'))->toJson(), '[]', false),
            'variations_json' => rescue(fn() => $product->variations->map(fn($v) => $v->only('sku', 'regular_price', 'sale_price'))->toJson(), '[]', false),
            'wc_tags_json' => rescue(fn() => $product->tags->map(fn($t) => $t->only('id', 'name'))->toJson(), '[]', false),
            'change_type' => $changeType,
            'change_description' => $changeDescription,
            'created_by' => auth()->id(),
        ]);
    }
}
