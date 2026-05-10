<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductTag;
use App\Models\ProductAttribute;
use App\Models\ProductVersion;
use App\Models\ExportLog;
use App\Services\WooCommerceService;
use App\Services\ImageUploadService;
use App\Services\OpenAIService;
use App\Services\ImagifyService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

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

        $products = $query->paginate(20);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = ProductCategory::all();
        $tags = ProductTag::all();
        $attributes = ProductAttribute::all();
        return view('products.create', compact('categories', 'tags', 'attributes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products',
            'type' => 'required|in:simple,variable',
            'regular_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string',
            'brand' => 'nullable|string',
            'custom_tags' => 'nullable|string',
            'status' => 'required|in:draft,published,private',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['created_by'] = auth()->id();

        $product = Product::create($validated);
        $this->createVersion($product, 'create', 'Product created');

        return redirect()->route('products.show', $product)->with('success', 'Product created');
    }

    public function show(Product $product)
    {
        $this->authorize('view', $product);
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product);
        $categories = ProductCategory::all();
        $tags = ProductTag::all();
        $attributes = ProductAttribute::all();
        return view('products.edit', compact('product', 'categories', 'tags', 'attributes'));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => "required|string|unique:products,sku,{$product->id}",
            'type' => 'required|in:simple,variable',
            'regular_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string',
            'brand' => 'nullable|string',
            'custom_tags' => 'nullable|string',
            'status' => 'required|in:draft,published,private',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $product->update($validated);
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
        $newProduct->wc_product_id = null;
        $newProduct->save();

        // Copy images, variations, attributes
        foreach ($product->images as $image) {
            $newProduct->images()->create($image->only(['image_path', 'is_primary', 'sort_order']));
        }
        foreach ($product->variations as $variation) {
            $newProduct->variations()->create($variation->only(['sku', 'regular_price', 'sale_price', 'image_path', 'is_default']));
        }
        $newProduct->attributes()->attach($product->attributes);

        $this->createVersion($newProduct, 'duplicate', 'Duplicated from product #' . $product->id);

        return redirect()->route('products.show', $newProduct)->with('success', 'Product duplicated');
    }

    public function createInWoocommerce(Product $product, WooCommerceService $wc): JsonResponse
    {
        $this->authorize('update', $product);

        try {
            $payload = $this->buildWCPayload($product);

            // Upload images
            if ($product->images()->exists()) {
                $payload['images'] = [];
                foreach ($product->images as $image) {
                    $media = $wc->uploadMedia($image->image_path, basename($image->image_path));
                    if ($media) {
                        $payload['images'][] = ['id' => $media['id']];
                    }
                }
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
            'name' => $product->name,
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

        if ($product->categories()->exists()) {
            $payload['categories'] = $product->categories->map(fn($c) => ['id' => $c->wc_category_id])->toArray();
        }

        if ($product->tags()->exists()) {
            $payload['tags'] = $product->tags->map(fn($t) => ['id' => $t->wc_tag_id])->toArray();
        }

        return $payload;
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
            'categories_json' => $product->categories->map(fn($c) => $c->only('id', 'name'))->toJson(),
            'attributes_json' => $product->attributes->map(fn($a) => $a->only('id', 'name', 'value'))->toJson(),
            'images_json' => $product->images->map(fn($i) => $i->only('image_path', 'is_primary'))->toJson(),
            'variations_json' => $product->variations->map(fn($v) => $v->only('sku', 'regular_price', 'sale_price'))->toJson(),
            'wc_tags_json' => $product->tags->map(fn($t) => $t->only('id', 'name'))->toJson(),
            'change_type' => $changeType,
            'change_description' => $changeDescription,
            'created_by' => auth()->id(),
        ]);
    }
}
