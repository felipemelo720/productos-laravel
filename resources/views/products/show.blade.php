@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="max-w-2xl">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">{{ $product->name }}</h1>
        <div class="flex gap-2">
            <a href="{{ route('products.edit', $product) }}" class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded">Edit</a>
            <form method="POST" action="{{ route('products.export', $product) }}" style="display:inline;">
                @csrf
                <button type="submit" class="px-3 py-1 bg-green-100 text-green-700 rounded">Export to WC</button>
            </form>
            <form method="POST" action="{{ route('products.destroy', $product) }}" style="display:inline;" onsubmit="return confirm('Delete?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3 py-1 bg-red-100 text-red-700 rounded">Delete</button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded shadow p-6 space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="font-semibold">SKU</label>
                <p>{{ $product->sku }}</p>
            </div>
            <div>
                <label class="font-semibold">Type</label>
                <p>{{ ucfirst($product->type) }}</p>
            </div>
            <div>
                <label class="font-semibold">Status</label>
                <p>{{ ucfirst($product->status) }}</p>
            </div>
            <div>
                <label class="font-semibold">WC Product ID</label>
                <p>{{ $product->wc_product_id ?? 'Not exported' }}</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="font-semibold">Regular Price</label>
                <p>${{ number_format($product->regular_price, 2) }}</p>
            </div>
            <div>
                <label class="font-semibold">Sale Price</label>
                <p>${{ number_format($product->sale_price ?? 0, 2) }}</p>
            </div>
        </div>

        <div>
            <label class="font-semibold">Short Description</label>
            <p>{{ $product->short_description }}</p>
        </div>

        <div>
            <label class="font-semibold">Description</label>
            <p>{{ $product->description }}</p>
        </div>

        <div>
            <label class="font-semibold">Brand</label>
            <p>{{ $product->brand }}</p>
        </div>

        <div>
            <label class="font-semibold">Created by</label>
            <p>{{ $product->user->full_name ?? 'N/A' }}</p>
        </div>
    </div>

    <a href="{{ route('products.index') }}" class="mt-6 inline-block px-4 py-2 border rounded">Back to Products</a>
</div>
@endsection
