@extends('layouts.app')

@section('title', $product->name)

@section('head')
<style>
    .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 16px; padding: 24px; }

    .section-title {
        font-size: 11px; font-weight: 700; color: #9ca3af; letter-spacing: 0.1em;
        text-transform: uppercase; padding-bottom: 12px; margin-bottom: 16px;
        border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; gap: 7px;
    }
    .section-title svg { color: #31A6A8; flex-shrink: 0; }

    .badge {
        display: inline-flex; align-items: center;
        padding: 4px 12px; border-radius: 999px;
        font-size: 11px; font-weight: 700; letter-spacing: 0.03em;
    }

    /* Gallery */
    .gallery-main {
        width: 100%; aspect-ratio: 4/3; max-height: 340px;
        background: #f9fafb; border-radius: 14px; overflow: hidden;
        border: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: center;
        position: relative;
    }
    .gallery-main img {
        width: 100%; height: 100%; object-fit: contain; padding: 20px;
        transition: opacity 0.2s ease;
    }
    .gallery-thumb {
        aspect-ratio: 1; border-radius: 10px; overflow: hidden;
        border: 2px solid #e5e7eb; background: #f9fafb;
        cursor: pointer; transition: border-color 0.15s, transform 0.15s;
        display: flex; align-items: center; justify-content: center;
    }
    .gallery-thumb.active { border-color: #31A6A8; }
    .gallery-thumb:hover { border-color: #31A6A8; transform: scale(1.04); }
    .gallery-thumb img { width: 100%; height: 100%; object-fit: contain; padding: 4px; }

    /* Detail rows */
    .detail-row {
        display: flex; justify-content: space-between; align-items: baseline;
        padding: 9px 0; border-bottom: 1px solid #f9fafb;
        font-size: 0.8125rem;
    }
    .detail-row:last-child { border-bottom: none; padding-bottom: 0; }
    .detail-row:first-child { padding-top: 0; }
    .detail-label { color: #9ca3af; font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: 0.08em; }
    .detail-value { color: #111827; font-weight: 500; text-align: right; max-width: 60%; }

    /* Buttons */
    .btn-primary {
        display: flex; align-items: center; justify-content: center; gap: 7px;
        width: 100%; padding: 11px 16px; background: #31A6A8; color: #fff;
        font-size: 0.875rem; font-weight: 600; border-radius: 11px; border: none;
        cursor: pointer; transition: background 0.15s, box-shadow 0.15s, transform 0.1s;
        text-decoration: none;
    }
    .btn-primary:hover { background: #2a9193; box-shadow: 0 4px 18px rgba(49,166,168,0.3); color: #fff; }

    .btn-secondary {
        display: flex; align-items: center; justify-content: center; gap: 7px;
        width: 100%; padding: 10px 16px; background: #eff6ff; color: #3b82f6;
        font-size: 0.875rem; font-weight: 600; border-radius: 11px; border: none;
        cursor: pointer; transition: background 0.15s; text-decoration: none;
    }
    .btn-secondary:hover { background: #dbeafe; color: #2563eb; }

    .btn-ghost {
        display: flex; align-items: center; justify-content: center; gap: 7px;
        width: 100%; padding: 9px 16px; background: transparent; color: #9ca3af;
        font-size: 0.8125rem; font-weight: 500; border-radius: 11px;
        border: 1px solid #e5e7eb; cursor: pointer; transition: all 0.15s; text-decoration: none;
    }
    .btn-ghost:hover { background: #f9fafb; color: #6b7280; border-color: #d1d5db; }

    .btn-danger {
        display: flex; align-items: center; justify-content: center; gap: 7px;
        width: 100%; padding: 9px 16px; background: transparent; color: #f87171;
        font-size: 0.8125rem; font-weight: 500; border-radius: 11px;
        border: 1px solid #fecaca; cursor: pointer; transition: all 0.15s;
    }
    .btn-danger:hover { background: #fff5f5; border-color: #fca5a5; color: #ef4444; }
    .btn-primary:active, .btn-secondary:active, .btn-ghost:active, .btn-danger:active { transform: translateY(1px); }

    /* Hero */
    .hero-card {
        background: linear-gradient(135deg, #f0fdfd 0%, #fff 60%);
        border: 1px solid #e5e7eb; border-radius: 16px; padding: 28px 32px;
    }

    @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
    .animate-in { animation: fadeIn 0.35s ease both; }
    .animate-in:nth-child(1) { animation-delay: 0.02s; }
    .animate-in:nth-child(2) { animation-delay: 0.07s; }
    .animate-in:nth-child(3) { animation-delay: 0.12s; }
</style>
@endsection

@section('content')

@php
    $primaryImage = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
    $statusColor = match($product->status) {
        'published' => 'bg-green-100 text-green-700',
        'private'   => 'bg-yellow-100 text-yellow-700',
        default     => 'bg-gray-100 text-gray-500',
    };
    $statusLabel = match($product->status) {
        'published' => 'Publicado',
        'private'   => 'Privado',
        'draft'     => 'Borrador',
        default     => ucfirst($product->status),
    };
    $typeColor = $product->type === 'variable' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700';
    $typeLabel = $product->type === 'variable' ? 'Variable' : 'Simple';
@endphp

{{-- Breadcrumb --}}
<div class="flex items-center gap-2 text-xs text-gray-400 mb-5 animate-in">
    <a href="{{ route('products.index') }}" class="hover:text-teal-600 transition-colors">Productos</a>
    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
    <span class="text-gray-600 font-medium truncate max-w-sm">{{ $product->name }}</span>
</div>

{{-- Hero --}}
<div class="hero-card mb-6 animate-in">
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div class="flex-1 min-w-0">
            <div class="flex flex-wrap items-center gap-2 mb-2">
                <span class="badge {{ $statusColor }}">{{ $statusLabel }}</span>
                <span class="badge {{ $typeColor }}">{{ $typeLabel }}</span>
                @if($product->wc_product_id)
                    <span class="badge bg-teal-50 text-teal-600">WC #{{ $product->wc_product_id }}</span>
                @endif
            </div>
            <h1 class="text-2xl font-bold text-gray-900 leading-snug mb-1">{{ $product->name }}</h1>
            <p class="text-sm text-gray-400 font-mono">SKU: {{ $product->sku }}{{ $product->brand ? ' · ' . $product->brand : '' }}</p>
        </div>
        <div class="flex flex-wrap sm:flex-nowrap gap-2 shrink-0">
            <a href="{{ route('products.edit', $product) }}" class="btn-primary" style="width:auto;padding:10px 20px;">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Editar
            </a>
            <form method="POST" action="{{ route('products.export', $product) }}">
                @csrf
                <button type="submit" class="btn-secondary" style="width:auto;padding:10px 20px;">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    Exportar WC
                </button>
            </form>
            <form method="POST" action="{{ route('products.duplicate', $product) }}">
                @csrf
                <button type="submit" class="btn-ghost" style="width:auto;padding:10px 20px;">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    Duplicar
                </button>
            </form>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Left: images + descriptions + attributes --}}
    <div class="lg:col-span-2 space-y-5 animate-in">

        {{-- Image gallery --}}
        <div class="card" style="padding: 20px;">
            @if($product->images->count())
                <div class="gallery-main mb-3" id="gallery-main">
                    <img id="main-img"
                         src="{{ asset($primaryImage->image_path) }}"
                         alt="{{ $product->name }}"
                         onerror="this.parentElement.innerHTML='<div style=\'display:flex;align-items:center;justify-content:center;height:100%;\'><svg style=\'width:64px;height:64px;color:#e5e7eb;\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\' stroke-width=\'1\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z\'/></svg></div>'">
                </div>
                @if($product->images->count() > 1)
                <div class="grid grid-cols-6 gap-2">
                    @foreach($product->images as $img)
                    <div class="gallery-thumb {{ $img->is_primary ? 'active' : '' }}"
                         onclick="switchImage('{{ asset($img->image_path) }}', this)">
                        <img src="{{ asset($img->image_path) }}" alt=""
                             onerror="this.parentElement.style.background='#f3f4f6'">
                    </div>
                    @endforeach
                </div>
                @endif
            @else
                <div class="w-full aspect-square bg-gray-50 rounded-xl flex flex-col items-center justify-center gap-3">
                    <svg class="w-14 h-14 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-xs text-gray-300">Sin imágenes</p>
                </div>
            @endif
        </div>

        {{-- Descriptions --}}
        @if($product->short_description || $product->description)
        <div class="card">
            <div class="section-title">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/></svg>
                Descripciones
            </div>
            @if($product->short_description)
            <div class="mb-5">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Descripción corta</p>
                <p class="text-sm text-gray-700 leading-relaxed">{{ $product->short_description }}</p>
            </div>
            @endif
            @if($product->description)
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Descripción completa</p>
                <div class="text-sm text-gray-700 leading-relaxed prose prose-sm max-w-none">
                    {!! nl2br(e($product->description)) !!}
                </div>
            </div>
            @endif
        </div>
        @endif

        {{-- Attributes --}}
        @if($product->attributes->count())
        <div class="card">
            <div class="section-title">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                Atributos
            </div>
            <div>
                @foreach($product->attributes as $attr)
                <div class="detail-row">
                    <span class="detail-label">{{ $attr->name }}</span>
                    <span class="detail-value">{{ $attr->value }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>

    {{-- Sidebar --}}
    <div class="space-y-5 animate-in">

        {{-- Details --}}
        <div class="card">
            <div class="section-title">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Detalles
            </div>
            <div>
                <div class="detail-row">
                    <span class="detail-label">SKU</span>
                    <span class="detail-value font-mono">{{ $product->sku }}</span>
                </div>
                @if($product->brand)
                <div class="detail-row">
                    <span class="detail-label">Marca</span>
                    <span class="detail-value">{{ $product->brand }}</span>
                </div>
                @endif
                <div class="detail-row">
                    <span class="detail-label">Imágenes</span>
                    <span class="detail-value">{{ $product->images->count() }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Creado por</span>
                    <span class="detail-value">{{ $product->user->full_name ?? '—' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Creado</span>
                    <span class="detail-value">{{ $product->created_at->format('d/m/Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Actualizado</span>
                    <span class="detail-value">{{ $product->updated_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>

        {{-- Categories & Tags --}}
        @if($product->categories->count() || $product->tags->count())
        <div class="card" style="padding: 0; overflow: hidden;">
            @if($product->categories->count())
            <div style="padding: 18px 20px; {{ $product->tags->count() ? 'border-bottom: 1px solid #f3f4f6;' : '' }}">
                <div style="display:flex; align-items:center; gap:7px; margin-bottom:10px;">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color:#31A6A8;"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    <span style="font-size:10px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.1em;">Categorías</span>
                    <span style="margin-left:auto;font-size:11px;font-weight:600;background:#f0fdfd;color:#31A6A8;padding:1px 8px;border-radius:999px;">{{ $product->categories->count() }}</span>
                </div>
                <div class="flex flex-wrap gap-1.5">
                    @foreach($product->categories as $cat)
                        <span style="display:inline-flex;align-items:center;padding:4px 10px;background:#f0fdfd;color:#0f766e;border-radius:6px;font-size:11px;font-weight:500;border:1px solid #99f6e4;">{{ $cat->name }}</span>
                    @endforeach
                </div>
            </div>
            @endif
            @if($product->tags->count())
            <div style="padding: 18px 20px;">
                <div style="display:flex; align-items:center; gap:7px; margin-bottom:10px;">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color:#a78bfa;"><path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                    <span style="font-size:10px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.1em;">Etiquetas WC</span>
                    <span style="margin-left:auto;font-size:11px;font-weight:600;background:#f5f3ff;color:#7c3aed;padding:1px 8px;border-radius:999px;">{{ $product->tags->count() }}</span>
                </div>
                <div class="flex flex-wrap gap-1.5">
                    @foreach($product->tags as $tag)
                        <span style="display:inline-flex;align-items:center;padding:4px 10px;background:#f5f3ff;color:#6d28d9;border-radius:6px;font-size:11px;font-weight:500;border:1px solid #ddd6fe;">{{ $tag->name }}</span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        @endif

        {{-- Danger zone --}}
        <div style="border-radius:16px;overflow:hidden;border:1px solid #fecaca;">
            <div style="background:linear-gradient(135deg,#fff1f2,#fff8f8);padding:16px 20px;border-bottom:1px solid #fecaca;display:flex;align-items:center;gap:8px;">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color:#f87171;flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                <span style="font-size:11px;font-weight:700;color:#ef4444;text-transform:uppercase;letter-spacing:0.08em;">Zona peligrosa</span>
            </div>
            <div style="background:#fff8f8;padding:16px 20px;">
                <p style="font-size:12px;color:#9ca3af;margin-bottom:12px;line-height:1.5;">Esta acción eliminará permanentemente el producto y no se puede deshacer.</p>
                <form method="POST" action="{{ route('products.destroy', $product) }}"
                      onsubmit="return confirm('¿Eliminar {{ addslashes($product->name) }}? Esta acción no se puede deshacer.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Eliminar producto
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
function switchImage(src, thumbEl) {
    const img = document.getElementById('main-img');
    img.style.opacity = '0';
    setTimeout(() => { img.src = src; img.style.opacity = '1'; }, 150);
    document.querySelectorAll('.gallery-thumb').forEach(t => t.classList.remove('active'));
    thumbEl.classList.add('active');
}
</script>
@endsection
