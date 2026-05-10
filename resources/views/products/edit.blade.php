@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-3xl font-bold mb-6">Edit Product</h1>

    <form method="POST" action="{{ route('products.update', $product) }}" class="bg-white rounded shadow p-6 space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block font-semibold mb-2">Name *</label>
            <input type="text" name="name" class="w-full px-3 py-2 border rounded" value="{{ old('name', $product->name) }}" required>
        </div>

        <div>
            <label class="block font-semibold mb-2">SKU *</label>
            <input type="text" name="sku" class="w-full px-3 py-2 border rounded" value="{{ old('sku', $product->sku) }}" required>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-2">Type *</label>
                <select name="type" class="w-full px-3 py-2 border rounded" required>
                    <option value="simple" {{ $product->type === 'simple' ? 'selected' : '' }}>Simple</option>
                    <option value="variable" {{ $product->type === 'variable' ? 'selected' : '' }}>Variable</option>
                </select>
            </div>
            <div>
                <label class="block font-semibold mb-2">Status *</label>
                <select name="status" class="w-full px-3 py-2 border rounded" required>
                    <option value="draft" {{ $product->status === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ $product->status === 'published' ? 'selected' : '' }}>Published</option>
                    <option value="private" {{ $product->status === 'private' ? 'selected' : '' }}>Private</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-2">Regular Price *</label>
                <input type="number" step="0.01" name="regular_price" class="w-full px-3 py-2 border rounded" value="{{ $product->regular_price }}" required>
            </div>
            <div>
                <label class="block font-semibold mb-2">Sale Price</label>
                <input type="number" step="0.01" name="sale_price" class="w-full px-3 py-2 border rounded" value="{{ $product->sale_price }}">
            </div>
        </div>

        <div>
            <label class="block font-semibold mb-2">Short Description</label>
            <textarea name="short_description" class="w-full px-3 py-2 border rounded" rows="2">{{ $product->short_description }}</textarea>
        </div>

        <div>
            <label class="block font-semibold mb-2">Description</label>
            <textarea name="description" class="w-full px-3 py-2 border rounded" rows="4">{{ $product->description }}</textarea>
        </div>

        <div>
            <label class="block font-semibold mb-2">Brand</label>
            <input type="text" name="brand" class="w-full px-3 py-2 border rounded" value="{{ $product->brand }}">
        </div>

        <div>
            <label class="block font-semibold mb-2">Custom Tags (CSV)</label>
            <input type="text" name="custom_tags" class="w-full px-3 py-2 border rounded" value="{{ $product->custom_tags }}">
        </div>

        <div class="flex gap-2 pt-4">
            <button type="submit" class="btn-brand px-4 py-2 rounded">Update Product</button>
            <a href="{{ route('products.show', $product) }}" class="px-4 py-2 border rounded">Cancel</a>
        </div>
    </form>
</div>
@endsection
