@extends('layouts.app')

@section('title', 'Editar — ' . $product->name)

@section('head')
<style>
    .field-label {
        display: block;
        font-size: 11px;
        font-weight: 600;
        color: #9ca3af;
        text-transform: uppercase;
        letter-spacing: 0.13em;
        margin-bottom: 6px;
    }
    .field-input {
        width: 100%;
        padding: 10px 14px;
        font-size: 0.875rem;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        color: #111827;
        transition: border-color 0.18s ease, box-shadow 0.18s ease, background 0.18s ease;
    }
    .field-input:focus {
        outline: none;
        border-color: #31A6A8;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(49,166,168,0.11);
    }
    .field-input::placeholder { color: #c0c8d0; }
    .field-input.error { border-color: #f87171; background: #fff8f8; }
    textarea.field-input { resize: vertical; min-height: 90px; }
    select.field-input {
        appearance: none; cursor: pointer;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%239ca3af' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 12px center; background-size: 14px; padding-right: 36px;
    }

    .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 24px; }
    .section-title {
        font-size: 13px; font-weight: 700; color: #374151; letter-spacing: 0.01em;
        padding-bottom: 14px; margin-bottom: 18px; border-bottom: 1px solid #f3f4f6;
        display: flex; align-items: center; gap: 8px;
    }
    .section-title svg { color: #31A6A8; }

    .btn-primary {
        width: 100%; padding: 11px 16px; background: #31A6A8; color: #fff;
        font-size: 0.875rem; font-weight: 600; border-radius: 10px; border: none;
        cursor: pointer; transition: background 0.18s, box-shadow 0.18s, transform 0.1s; letter-spacing: 0.02em;
    }
    .btn-primary:hover { background: #2a9193; box-shadow: 0 4px 18px rgba(49,166,168,0.32); }
    .btn-primary:active { transform: translateY(1px); }
    .btn-secondary {
        width: 100%; padding: 11px 16px; background: #f3f4f6; color: #6b7280;
        font-size: 0.875rem; font-weight: 500; border-radius: 10px; border: 1px solid #e5e7eb;
        text-align: center; transition: background 0.15s, color 0.15s; display: block;
    }
    .btn-secondary:hover { background: #e9eaec; color: #374151; }

    .price-prefix {
        position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
        font-size: 0.8rem; color: #9ca3af; font-weight: 500; pointer-events: none;
    }
    .price-input { padding-left: 30px !important; }

    .field-hint { font-size: 11px; color: #b0b8c1; margin-top: 5px; }
    .field-error { font-size: 11.5px; color: #ef4444; margin-top: 5px; display: flex; align-items: center; gap: 4px; }

    .img-thumb {
        position: relative; aspect-ratio: 1; border-radius: 10px; overflow: hidden;
        border: 2px solid #e5e7eb; background: #f9fafb; group;
    }
    .img-thumb.is-primary { border-color: #31A6A8; }
    .img-thumb img { width: 100%; height: 100%; object-fit: contain; padding: 4px; position: relative; z-index: 1; }
    .img-thumb .delete-btn {
        position: absolute; top: 4px; right: 4px; width: 22px; height: 22px;
        background: rgba(239,68,68,0.85); color: white; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 14px; font-weight: 700; cursor: pointer;
        opacity: 0.6; transition: opacity 0.15s, transform 0.15s;
        border: none; line-height: 1; z-index: 2;
    }
    .img-thumb:hover .delete-btn { opacity: 1; transform: scale(1.1); }
    .img-thumb .primary-badge {
        position: absolute; bottom: 4px; left: 4px;
        font-size: 9px; font-weight: 700; letter-spacing: 0.05em;
        background: #31A6A8; color: white; padding: 2px 6px; border-radius: 4px; text-transform: uppercase;
    }

    @keyframes fadeSlide {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .animate-in { animation: fadeSlide 0.4s ease both; }
    .animate-in:nth-child(1) { animation-delay: 0.03s; }
    .animate-in:nth-child(2) { animation-delay: 0.08s; }
    .animate-in:nth-child(3) { animation-delay: 0.13s; }
</style>
@endsection

@section('content')

{{-- Header --}}
<div class="flex items-center justify-between mb-7 animate-in">
    <div>
        <div class="flex items-center gap-2 text-xs text-gray-400 mb-1.5">
            <a href="{{ route('products.index') }}" class="hover:text-teal-600 transition-colors">Productos</a>
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route('products.show', $product) }}" class="hover:text-teal-600 transition-colors truncate max-w-xs">{{ $product->name }}</a>
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            <span class="text-gray-600 font-medium">Editar</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Editar producto</h1>
    </div>
</div>

<form id="edit-form" method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data">
@csrf
@method('PUT')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Main column --}}
    <div class="lg:col-span-2 space-y-5 animate-in">

        {{-- Basic info --}}
        <div class="card">
            <div class="section-title">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                Información básica
            </div>

            <div class="space-y-4">
                <div>
                    <label class="field-label">Nombre del producto <span class="text-red-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required autofocus
                           class="field-input @error('name') error @enderror">
                    @error('name')<p class="field-error"><svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01"/></svg>{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="field-label">SKU <span class="text-red-400">*</span></label>
                        <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" required
                               class="field-input font-mono @error('sku') error @enderror">
                        @error('sku')<p class="field-error"><svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01"/></svg>{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="field-label">Marca</label>
                        <input type="text" name="brand" value="{{ old('brand', $product->brand) }}"
                               placeholder="Ej: Dentsply, 3M, LM..."
                               class="field-input">
                    </div>
                </div>
            </div>
        </div>

        {{-- Images --}}
        <div class="card">
            <div class="section-title">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Imágenes
            </div>

            {{-- Existing images --}}
            @php $allImages = $product->images()->orderByDesc('is_primary')->orderBy('sort_order')->get(); @endphp
            @if($allImages->count())
            <div class="mb-4">
                <label class="field-label mb-3">Imágenes actuales — hover para eliminar</label>
                <div class="grid grid-cols-4 gap-2" id="existing-images">
                    @foreach($allImages as $img)
                    <div class="img-thumb {{ $img->is_primary ? 'is-primary' : '' }}" id="thumb-{{ $img->id }}">
                        <img src="{{ asset($img->image_path) }}"
                             alt=""
                             onerror="this.src=''; this.parentElement.style.background='#f3f4f6'">
                        <button type="button" class="delete-btn"
                                onclick="markDelete({{ $img->id }})"
                                title="Eliminar imagen">×</button>
                        @if($img->is_primary)
                            <span class="primary-badge">Principal</span>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Upload new --}}
            <label class="field-label">Agregar imágenes</label>
            <label id="drop-zone"
                   class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-gray-200 rounded-xl bg-gray-50 cursor-pointer hover:border-teal-400 hover:bg-teal-50 transition-colors"
                   ondragover="event.preventDefault()" ondrop="handleDrop(event)">
                <svg class="w-6 h-6 text-gray-300 mb-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                <span class="text-sm text-gray-400">Arrastra o <span class="text-teal-600 font-medium">elige archivos</span></span>
                <span class="text-xs text-gray-300 mt-0.5">JPG, PNG, WEBP — máx. 5MB c/u</span>
                <input type="file" name="images[]" id="img-input" multiple accept="image/*" class="hidden"
                       onchange="previewImages(this.files)">
            </label>

            <div id="img-preview" class="grid grid-cols-4 gap-2 mt-3 hidden"></div>
        </div>

        {{-- Descriptions --}}
        <div class="card">
            <div class="section-title">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/></svg>
                Descripciones
            </div>
            <div class="space-y-4">
                <div>
                    <label class="field-label">Descripción corta</label>
                    <textarea name="short_description" class="field-input" rows="2"
                              placeholder="Resumen breve del producto...">{{ old('short_description', $product->short_description) }}</textarea>
                    <p class="field-hint">Aparece en previsualizaciones y listados.</p>
                </div>
                <div>
                    <label class="field-label">Descripción completa</label>
                    <textarea name="description" class="field-input" rows="5"
                              placeholder="Descripción detallada, características, usos clínicos...">{{ old('description', $product->description) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Attributes --}}
        <div class="card">
            <div class="section-title">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                Atributos
            </div>

            <div id="attr-rows" class="space-y-3">
                @php
                    $existingAttrs = old('attributes') !== null
                        ? old('attributes')
                        : $product->attributes->map(fn($a) => ['name' => $a->name, 'value' => $a->value])->toArray();
                    if (empty($existingAttrs)) { $existingAttrs = [['name' => '', 'value' => '']]; }
                @endphp
                @foreach ($existingAttrs as $i => $attr)
                <div class="attr-row grid grid-cols-[1fr_1fr_auto] gap-3 items-start">
                    <div>
                        @if ($i === 0)<label class="field-label">Nombre</label>@endif
                        <input type="text" name="attributes[{{ $i }}][name]"
                               value="{{ $attr['name'] ?? '' }}"
                               list="attr-name-list"
                               placeholder="Ej: Color, Tamaño"
                               class="field-input @error("attributes.$i.name") error @enderror">
                        @error("attributes.$i.name")
                            <p class="field-error"><svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01"/></svg>{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        @if ($i === 0)<label class="field-label">Valor</label>@endif
                        <input type="text" name="attributes[{{ $i }}][value]"
                               value="{{ $attr['value'] ?? '' }}"
                               placeholder="Ej: Azul, Grande"
                               class="field-input @error("attributes.$i.value") error @enderror">
                        @error("attributes.$i.value")
                            <p class="field-error"><svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01"/></svg>{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="button" onclick="removeAttrRow(this)"
                            class="{{ $i === 0 ? 'mt-[26px]' : '' }} h-[42px] w-[42px] flex items-center justify-center rounded-lg border border-gray-200 text-gray-400 hover:text-red-500 hover:border-red-300 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
                @endforeach
            </div>

            <datalist id="attr-name-list">
                @foreach ($attributeNames as $attrName)
                    <option value="{{ $attrName }}"></option>
                @endforeach
            </datalist>

            <button type="button" onclick="addAttrRow()"
                    class="mt-3 inline-flex items-center gap-1.5 text-sm font-medium text-teal-600 hover:text-teal-700 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Añadir atributo
            </button>
        </div>

    </div>

    {{-- Sidebar --}}
    <div class="space-y-5 animate-in">

        {{-- Actions --}}
        <div class="card">
            <div class="section-title">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Publicación
            </div>

            <div class="space-y-2.5">
                <button type="submit" class="btn-primary">Guardar cambios</button>
                <a href="{{ route('products.show', $product) }}" class="btn-secondary">Cancelar</a>
            </div>
        </div>

        {{-- Marca --}}
        <div class="card">
            <div class="section-title" style="justify-content:space-between;">
                <span style="display:flex;align-items:center;gap:8px;">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                    Marca
                </span>
                @if($wcBrands->isEmpty())
                    <button type="button" onclick="syncWcData()" class="text-xs text-teal-600 hover:text-teal-800 font-medium">Sincronizar</button>
                @endif
            </div>
            @if($wcBrands->isNotEmpty())
                <select name="wc_brand_id" class="field-input">
                    <option value="">— Sin marca —</option>
                    @foreach($wcBrands as $brand)
                        <option value="{{ $brand->wc_brand_id }}"
                            {{ old('wc_brand_id', $product->wc_brand_id) == $brand->wc_brand_id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                    @endforeach
                </select>
            @else
                <p class="text-xs text-gray-400">Sin datos. Sincroniza primero.</p>
            @endif
        </div>

        {{-- Categorías --}}
        <div class="card">
            <div class="section-title" style="justify-content:space-between;">
                <span style="display:flex;align-items:center;gap:8px;">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    Categorías
                </span>
                <button type="button" onclick="syncWcData()" class="text-xs text-teal-600 hover:text-teal-800 font-medium">Sincronizar</button>
            </div>
            @php $selectedCatIds = $product->categories->pluck('id')->toArray(); @endphp
            @if($wcCategories->isNotEmpty())
                <div style="max-height:160px;overflow-y:auto;overflow-x:hidden;border:1px solid #e5e7eb;border-radius:10px;scrollbar-width:thin;padding:8px;">
                    @foreach($wcCategories as $cat)
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:0.875rem;color:#374151;padding:4px;border-radius:6px;">
                        <input type="checkbox" name="categories[]" value="{{ $cat->id }}"
                               {{ in_array($cat->id, $selectedCatIds) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                        {{ $cat->name }}
                    </label>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-gray-400">Sin datos. Sincroniza primero.</p>
            @endif
        </div>

        {{-- Etiquetas WC --}}
        <div class="card">
            <div class="section-title" style="justify-content:space-between;">
                <span style="display:flex;align-items:center;gap:8px;">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                    Etiquetas WC
                </span>
                <button type="button" onclick="syncWcData()" class="text-xs text-teal-600 hover:text-teal-800 font-medium">Sincronizar</button>
            </div>
            @php $selectedTagIds = $product->tags->pluck('id')->toArray(); @endphp
            @if($wcTags->isNotEmpty())
                <div style="max-height:160px;overflow-y:auto;overflow-x:hidden;border:1px solid #e5e7eb;border-radius:10px;scrollbar-width:thin;padding:8px;">
                    @foreach($wcTags as $tag)
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:0.875rem;color:#374151;padding:4px;border-radius:6px;">
                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                               {{ in_array($tag->id, $selectedTagIds) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                        {{ $tag->name }}
                    </label>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-gray-400">Sin datos. Sincroniza primero.</p>
            @endif
        </div>

        {{-- Meta --}}
        <div class="card">
            <div class="section-title">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Información
            </div>
            <div class="space-y-2.5 text-xs">
                <div class="flex justify-between">
                    <span class="text-gray-400">ID interno</span>
                    <span class="font-mono text-gray-600">#{{ $product->id }}</span>
                </div>
                @if($product->wc_product_id)
                <div class="flex justify-between">
                    <span class="text-gray-400">WooCommerce ID</span>
                    <span class="font-mono" style="color:#31A6A8;">{{ $product->wc_product_id }}</span>
                </div>
                @endif
                <div class="flex justify-between">
                    <span class="text-gray-400">Creado</span>
                    <span class="text-gray-600">{{ $product->created_at->format('d/m/Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Actualizado</span>
                    <span class="text-gray-600">{{ $product->updated_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Imágenes</span>
                    <span class="text-gray-600">{{ $product->images()->count() }}</span>
                </div>
            </div>
        </div>

    </div>
</div>

</form>

<script>
function syncWcData() {
    const btn = event.target;
    btn.textContent = '...';
    btn.disabled = true;
    fetch('{{ route('wc.sync') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                         || '{{ csrf_token() }}',
            'Accept': 'application/json',
        }
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            alert(d.message);
            location.reload();
        } else {
            alert('Error: ' + d.message);
            btn.textContent = 'Sincronizar';
            btn.disabled = false;
        }
    })
    .catch(() => {
        alert('Error de red');
        btn.textContent = 'Sincronizar';
        btn.disabled = false;
    });
}

const deleteQueue = new Set();

function markDelete(id) {
    const thumb = document.getElementById('thumb-' + id);
    if (!thumb) return;

    thumb.style.opacity = '0.3';
    thumb.style.pointerEvents = 'none';
    deleteQueue.add(id);

    let input = document.getElementById('del-' + id);
    if (!input) {
        input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'delete_images[]';
        input.id = 'del-' + id;
        input.value = id;
        document.getElementById('edit-form').appendChild(input);
    }
}

function previewImages(files) {
    const preview = document.getElementById('img-preview');
    preview.innerHTML = '';
    if (!files.length) { preview.classList.add('hidden'); return; }
    preview.classList.remove('hidden');
    const hasPrimary = document.querySelector('.img-thumb.is-primary') !== null;
    Array.from(files).forEach((file, i) => {
        const reader = new FileReader();
        reader.onload = e => {
            const div = document.createElement('div');
            div.className = 'relative aspect-square rounded-lg overflow-hidden border border-gray-200 bg-gray-50';
            const isFirst = !hasPrimary && i === 0;
            div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-contain p-1">
                ${isFirst ? '<span style="position:absolute;bottom:4px;left:4px;font-size:9px;font-weight:700;background:#31A6A8;color:white;padding:2px 6px;border-radius:4px;text-transform:uppercase;">Principal</span>' : ''}`;
            preview.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
}

function handleDrop(e) {
    e.preventDefault();
    const input = document.getElementById('img-input');
    input.files = e.dataTransfer.files;
    previewImages(e.dataTransfer.files);
}

let attrIndex = document.querySelectorAll('#attr-rows .attr-row').length;

function addAttrRow() {
    const i = attrIndex++;
    const wrap = document.createElement('div');
    wrap.className = 'attr-row grid grid-cols-[1fr_1fr_auto] gap-3 items-start';
    wrap.innerHTML = `
        <div>
            <input type="text" name="attributes[${i}][name]" list="attr-name-list"
                   placeholder="Ej: Color, Tamaño" class="field-input">
        </div>
        <div>
            <input type="text" name="attributes[${i}][value]"
                   placeholder="Ej: Azul, Grande" class="field-input">
        </div>
        <button type="button" onclick="removeAttrRow(this)"
                class="h-[42px] w-[42px] flex items-center justify-center rounded-lg border border-gray-200 text-gray-400 hover:text-red-500 hover:border-red-300 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
        </button>`;
    document.getElementById('attr-rows').appendChild(wrap);
}

function removeAttrRow(btn) {
    const rows = document.querySelectorAll('#attr-rows .attr-row');
    if (rows.length <= 1) {
        btn.closest('.attr-row').querySelectorAll('input').forEach(i => i.value = '');
        return;
    }
    btn.closest('.attr-row').remove();
}
</script>
@endsection
