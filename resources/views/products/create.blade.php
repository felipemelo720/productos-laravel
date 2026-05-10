@extends('layouts.app')

@section('title', 'Create Product')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-3xl font-bold mb-6">Create Product</h1>

    <form method="POST" action="{{ route('products.store') }}" class="bg-white rounded shadow p-6 space-y-4">
        @csrf

        <div>
            <label class="block font-semibold mb-2">Name *</label>
            <input type="text" name="name" class="w-full px-3 py-2 border rounded @error('name') border-red-500 @enderror" value="{{ old('name') }}" required>
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-semibold mb-2">SKU *</label>
            <input type="text" name="sku" class="w-full px-3 py-2 border rounded @error('sku') border-red-500 @enderror" value="{{ old('sku') }}" required>
            @error('sku') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-2">Type *</label>
                <select name="type" class="w-full px-3 py-2 border rounded" required>
                    <option value="simple">Simple</option>
                    <option value="variable">Variable</option>
                </select>
            </div>
            <div>
                <label class="block font-semibold mb-2">Status *</label>
                <select name="status" class="w-full px-3 py-2 border rounded" required>
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                    <option value="private">Private</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-2">Regular Price *</label>
                <input type="number" step="0.01" name="regular_price" class="w-full px-3 py-2 border rounded" required>
            </div>
            <div>
                <label class="block font-semibold mb-2">Sale Price</label>
                <input type="number" step="0.01" name="sale_price" class="w-full px-3 py-2 border rounded">
            </div>
        </div>

        <div>
            <label class="block font-semibold mb-2">Short Description</label>
            <textarea name="short_description" class="w-full px-3 py-2 border rounded" rows="2"></textarea>
        </div>

        <div>
            <label class="block font-semibold mb-2">Description</label>
            <textarea name="description" class="w-full px-3 py-2 border rounded" rows="4"></textarea>
        </div>

        <div>
            <label class="block font-semibold mb-2">Brand</label>
            <input type="text" name="brand" class="w-full px-3 py-2 border rounded">
        </div>

        <div>
            <label class="block font-semibold mb-2">Custom Tags (CSV)</label>
            <input type="text" name="custom_tags" class="w-full px-3 py-2 border rounded">
        </div>

        <div class="flex gap-2 pt-4">
            <button type="submit" class="btn-brand px-4 py-2 rounded">Create Product</button>
            <a href="{{ route('products.index') }}" class="px-4 py-2 border rounded">Cancel</a>
        </div>
    </form>
</div>
@endsection
