@extends('layouts.app')

@section('title', 'Productos')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Productos</h1>
    <a href="{{ route('products.create') }}" class="btn-brand px-4 py-2 rounded-lg text-sm">+ Nuevo Producto</a>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
    @forelse($products as $product)
        @php
            $primaryImage = $product->images->first();
            $statusColor = match($product->status) {
                'published' => 'bg-green-100 text-green-700',
                'private'   => 'bg-yellow-100 text-yellow-700',
                default     => 'bg-gray-100 text-gray-600',
            };
            $typeColor = $product->type === 'variable' ? 'text-purple-600' : 'text-blue-600';
        @endphp
        <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden flex flex-col hover:shadow-lg transition-shadow">

            {{-- Image --}}
            <a href="{{ route('products.show', $product) }}" class="block">
                <div class="relative w-full h-48 bg-gray-50">
                    @if($primaryImage)
                        <img src="{{ asset($primaryImage->image_path) }}"
                             alt="{{ $product->name }}"
                             class="w-full h-48 object-contain p-2"
                             onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                        <div style="display:none" class="absolute inset-0 items-center justify-center">
                            <svg class="w-12 h-12 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @else
                        <div class="flex items-center justify-center h-full">
                            <svg class="w-12 h-12 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                </div>
            </a>

            {{-- Body --}}
            <div class="p-4 flex flex-col flex-1">
                <div class="flex items-start justify-between gap-2 mb-2">
                    <a href="{{ route('products.show', $product) }}"
                       class="font-semibold text-gray-900 hover:text-teal-600 transition-colors leading-tight line-clamp-2 text-sm">
                        {{ $product->name }}
                    </a>
                    <span class="text-xs px-2 py-0.5 rounded-full shrink-0 font-medium {{ $statusColor }}">
                        {{ match($product->status) { 'published' => 'Publicado', 'private' => 'Privado', 'draft' => 'Borrador', default => ucfirst($product->status) } }}
                    </span>
                </div>

                <div class="text-xs text-gray-500 space-y-1 mb-3">
                    <p><span class="font-medium text-gray-600">SKU:</span> {{ $product->sku }}</p>
                    @if($product->brand)
                    <p><span class="font-medium text-gray-600">Marca:</span> {{ $product->brand }}</p>
                    @endif
                    <p><span class="font-medium text-gray-600">Tipo:</span>
                        <span class="{{ $typeColor }} font-medium">{{ $product->type === 'variable' ? 'Variable' : 'Simple' }}</span>
                    </p>
                </div>

                @if($product->wc_product_id)
                    <p class="text-xs mb-3" style="color:#31A6A8;">WC ID: {{ $product->wc_product_id }}</p>
                @endif

                {{-- Actions --}}
                <div class="mt-auto flex items-center gap-1.5 pt-3 border-t border-gray-100">
                    <a href="{{ route('products.show', $product) }}"
                       class="flex-1 text-center text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 px-2 py-1.5 rounded-lg transition-colors">
                        Ver
                    </a>
                    <a href="{{ route('products.edit', $product) }}"
                       class="flex-1 text-center text-xs bg-blue-50 hover:bg-blue-100 text-blue-700 px-2 py-1.5 rounded-lg transition-colors">
                        Editar
                    </a>
                    <form method="POST" action="{{ route('products.destroy', $product) }}"
                          onsubmit="return confirm('¿Eliminar {{ addslashes($product->name) }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="text-xs bg-red-50 hover:bg-red-100 text-red-700 px-2 py-1.5 rounded-lg transition-colors">
                            Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-3 py-20 text-center text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            <p class="text-sm">No se encontraron productos.</p>
        </div>
    @endforelse
</div>

<div class="mt-6">
    {{ $products->links() }}
</div>
@endsection
