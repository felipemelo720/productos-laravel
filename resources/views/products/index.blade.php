@extends('layouts.app')

@section('title', 'Products')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">Products</h1>
    <a href="{{ route('products.create') }}" class="btn-brand px-4 py-2 rounded">+ New Product</a>
</div>

<div class="bg-white rounded shadow p-4 mb-6">
    <form method="GET" class="flex gap-4">
        <input type="text" name="search" placeholder="Search..." class="flex-1 px-3 py-2 border rounded" value="{{ request('search') }}">
        <select name="type" class="px-3 py-2 border rounded">
            <option value="">All Types</option>
            <option value="simple" {{ request('type') === 'simple' ? 'selected' : '' }}>Simple</option>
            <option value="variable" {{ request('type') === 'variable' ? 'selected' : '' }}>Variable</option>
        </select>
        <button type="submit" class="btn-brand px-4 py-2 rounded">Filter</button>
    </form>
</div>

<div class="grid gap-4">
    @forelse($products as $product)
        <div class="bg-white rounded shadow p-4 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-semibold">{{ $product->name }}</h3>
                <p class="text-gray-600">SKU: {{ $product->sku }} | Type: {{ $product->type }}</p>
                <p class="text-sm text-gray-500">Status: {{ $product->status }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('products.show', $product) }}" class="px-3 py-1 bg-blue-100 text-blue-700 rounded">View</a>
                <a href="{{ route('products.edit', $product) }}" class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded">Edit</a>
                <form method="POST" action="{{ route('products.export', $product) }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="px-3 py-1 bg-green-100 text-green-700 rounded">Export</button>
                </form>
            </div>
        </div>
    @empty
        <p class="text-gray-600">No products found.</p>
    @endforelse
</div>

{{ $products->links() }}
@endsection
