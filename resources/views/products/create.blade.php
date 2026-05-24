@extends('layouts.app')

@section('title', 'Crear Producto')

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

    select.field-input { appearance: none; cursor: pointer; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%239ca3af' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 12px center; background-size: 14px; padding-right: 36px; }

    .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 24px; }

    .section-title {
        font-size: 13px;
        font-weight: 700;
        color: #374151;
        letter-spacing: 0.01em;
        padding-bottom: 14px;
        margin-bottom: 18px;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .section-title svg { color: #31A6A8; }

    .btn-primary {
        width: 100%;
        padding: 11px 16px;
        background: #31A6A8;
        color: #fff;
        font-size: 0.875rem;
        font-weight: 600;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        transition: background 0.18s ease, box-shadow 0.18s ease, transform 0.1s ease;
        letter-spacing: 0.02em;
    }
    .btn-primary:hover { background: #2a9193; box-shadow: 0 4px 18px rgba(49,166,168,0.32); }
    .btn-primary:active { transform: translateY(1px); }

    .btn-secondary {
        width: 100%;
        padding: 11px 16px;
        background: #f3f4f6;
        color: #6b7280;
        font-size: 0.875rem;
        font-weight: 500;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        text-align: center;
        transition: background 0.15s ease, color 0.15s ease;
        display: block;
    }
    .btn-secondary:hover { background: #e9eaec; color: #374151; }

    .price-prefix {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 0.8rem;
        color: #9ca3af;
        font-weight: 500;
        pointer-events: none;
    }
    .price-input { padding-left: 30px !important; }

    .field-hint { font-size: 11px; color: #b0b8c1; margin-top: 5px; }
    .field-error { font-size: 11.5px; color: #ef4444; margin-top: 5px; display: flex; align-items: center; gap: 4px; }

    .status-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; flex-shrink: 0; }

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

{{-- Page header --}}
<div class="flex items-center justify-between mb-7 animate-in">
    <div>
        <div class="flex items-center gap-2 text-xs text-gray-400 mb-1.5">
            <a href="{{ route('products.index') }}" class="hover:text-teal-600 transition-colors">Productos</a>
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            <span class="text-gray-600 font-medium">Nuevo producto</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Crear producto</h1>
    </div>
</div>

<form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
@csrf

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
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus
                           placeholder="Ej: Kit de Blanqueamiento Dental Premium"
                           class="field-input @error('name') error @enderror">
                    @error('name')
                        <p class="field-error"><svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01"/></svg>{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="field-label">SKU <span class="text-red-400">*</span></label>
                        <input type="text" name="sku" value="{{ old('sku') }}" required
                               placeholder="000000000000"
                               class="field-input font-mono @error('sku') error @enderror">
                        @error('sku')
                            <p class="field-error"><svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01"/></svg>{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="field-label">Marca</label>
                        @if($wcBrands->isNotEmpty())
                            <select name="wc_brand_id" class="field-input">
                                <option value="">— Sin marca —</option>
                                @foreach($wcBrands as $brand)
                                    <option value="{{ $brand->wc_brand_id }}" {{ old('wc_brand_id') == $brand->wc_brand_id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <input type="text" name="brand" value="{{ old('brand') }}"
                                   placeholder="Ej: Dentsply, 3M, LM..."
                                   class="field-input">
                        @endif
                    </div>
                </div>
            </div>
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
                    <textarea name="short_description"
                              placeholder="Resumen breve del producto (aparece en listados)..."
                              class="field-input" rows="2">{{ old('short_description') }}</textarea>
                    <p class="field-hint">Máximo 2-3 líneas. Aparece en previsualizaciones.</p>
                </div>

                <div>
                    <label class="field-label">Descripción completa</label>
                    <textarea name="description"
                              placeholder="Descripción detallada, características, usos clínicos..."
                              class="field-input" rows="5">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Images --}}
        <div class="card">
            <div class="section-title">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Imágenes
            </div>

            <label class="field-label">Subir imágenes</label>
            <label id="drop-zone"
                   class="flex flex-col items-center justify-center w-full h-36 border-2 border-dashed border-gray-200 rounded-xl bg-gray-50 cursor-pointer hover:border-teal-400 hover:bg-teal-50 transition-colors"
                   ondragover="event.preventDefault()" ondrop="handleDrop(event)">
                <svg class="w-8 h-8 text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                <span class="text-sm text-gray-400">Arrastra o <span class="text-teal-600 font-medium">elige archivos</span></span>
                <span class="text-xs text-gray-300 mt-1">JPG, PNG, WEBP — máx. 5MB c/u</span>
                <input type="file" name="images[]" id="img-input" multiple accept="image/*" class="hidden"
                       onchange="previewImages(this.files)">
            </label>

            <div id="img-preview" class="grid grid-cols-4 gap-2 mt-3 hidden"></div>

            @error('images.*')
                <p class="field-error mt-2">{{ $message }}</p>
            @enderror
        </div>

        {{-- Attributes --}}
        <div class="card">
            <div class="section-title">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                Atributos
            </div>

            <div id="attr-rows" class="space-y-3">
                @php
                    $oldAttrs = old('attributes', [['name' => '', 'value' => '']]);
                    if (empty($oldAttrs)) { $oldAttrs = [['name' => '', 'value' => '']]; }
                @endphp
                @foreach ($oldAttrs as $i => $attr)
                <div class="attr-row grid grid-cols-[1fr_1fr_auto] gap-3 items-start">
                    <div>
                        @if ($i === 0)<label class="field-label">Nombre</label>@endif
                        <input type="text" name="attributes[{{ $i }}][name]"
                               value="{{ $attr['name'] ?? '' }}"
                               list="attr-name-list"
                               placeholder="Ej: Color, Tamaño, Material"
                               class="field-input @error("attributes.$i.name") error @enderror">
                        @error("attributes.$i.name")
                            <p class="field-error"><svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01"/></svg>{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        @if ($i === 0)<label class="field-label">Valor</label>@endif
                        <input type="text" name="attributes[{{ $i }}][value]"
                               value="{{ $attr['value'] ?? '' }}"
                               placeholder="Ej: Azul, Grande, Titanio"
                               class="field-input @error("attributes.$i.value") error @enderror">
                        @error("attributes.$i.value")
                            <p class="field-error"><svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01"/></svg>{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="button" onclick="removeAttrRow(this)"
                            class="{{ $i === 0 ? 'mt-[26px]' : '' }} h-[42px] w-[42px] flex items-center justify-center rounded-lg border border-gray-200 text-gray-400 hover:text-red-500 hover:border-red-300 transition-colors"
                            title="Eliminar atributo">
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
            <p class="field-hint">Atributos del producto (ej. Color → Azul). Se reutilizan los existentes por nombre.</p>
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
                <button type="submit" class="btn-primary">
                    Crear producto
                </button>
                <a href="{{ route('products.index') }}" class="btn-secondary">
                    Cancelar
                </a>
            </div>
        </div>

        {{-- Categorías --}}
        <div class="card">
            <div class="section-title">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                Categorías
            </div>
            @if($wcCategories->isNotEmpty())
                <div style="max-height:160px;overflow-y:auto;overflow-x:hidden;border:1px solid #e5e7eb;border-radius:10px;scrollbar-width:thin;padding:8px;">
                    @foreach($wcCategories as $cat)
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:0.875rem;color:#374151;padding:4px;border-radius:6px;">
                        <input type="checkbox" name="categories[]" value="{{ $cat->id }}"
                               {{ in_array($cat->id, (array) old('categories', [])) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                        {{ $cat->name }}
                    </label>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-gray-400">Sin datos.</p>
            @endif
        </div>

        {{-- Etiquetas --}}
        <div class="card">
            <div class="section-title">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                Etiquetas
            </div>
            @if($wcTags->isNotEmpty())
                <div style="max-height:160px;overflow-y:auto;overflow-x:hidden;border:1px solid #e5e7eb;border-radius:10px;scrollbar-width:thin;padding:8px;">
                    @foreach($wcTags as $tag)
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:0.875rem;color:#374151;padding:4px;border-radius:6px;">
                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                               {{ in_array($tag->id, (array) old('tags', [])) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                        {{ $tag->name }}
                    </label>
                    @endforeach
                </div>
            @else
                <label class="field-label">Tags personalizados</label>
                <input type="text" name="custom_tags" value="{{ old('custom_tags') }}"
                       placeholder="implante, cirugía, esterilización  (separados por coma)"
                       class="field-input">
                <p class="field-hint">Separar con comas. Ej: ortodoncia, brackets, alambre</p>
            @endif
        </div>

    </div>
</div>

</form>

<script>
function previewImages(files) {
    const preview = document.getElementById('img-preview');
    preview.innerHTML = '';
    if (!files.length) { preview.classList.add('hidden'); return; }
    preview.classList.remove('hidden');
    Array.from(files).forEach((file, i) => {
        const reader = new FileReader();
        reader.onload = e => {
            const div = document.createElement('div');
            div.className = 'relative aspect-square rounded-lg overflow-hidden border border-gray-200 bg-gray-50';
            div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-contain p-1">
                ${i === 0 ? '<span class="absolute top-1 left-1 text-[10px] bg-teal-500 text-white px-1.5 py-0.5 rounded font-medium">Principal</span>' : ''}`;
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
                   placeholder="Ej: Color, Tamaño, Material" class="field-input">
        </div>
        <div>
            <input type="text" name="attributes[${i}][value]"
                   placeholder="Ej: Azul, Grande, Titanio" class="field-input">
        </div>
        <button type="button" onclick="removeAttrRow(this)"
                class="h-[42px] w-[42px] flex items-center justify-center rounded-lg border border-gray-200 text-gray-400 hover:text-red-500 hover:border-red-300 transition-colors"
                title="Eliminar atributo">
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
