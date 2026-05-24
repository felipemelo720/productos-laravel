@extends('layouts.app')

@section('title', 'Crear Usuario')

@section('head')
<style>
    .field-label { display:block;font-size:11px;font-weight:600;color:#9ca3af;text-transform:uppercase;letter-spacing:0.13em;margin-bottom:6px; }
    .field-input { width:100%;padding:10px 14px;font-size:0.875rem;background:#f9fafb;border:1px solid #e5e7eb;border-radius:10px;color:#111827;transition:border-color 0.18s,box-shadow 0.18s,background 0.18s; }
    .field-input:focus { outline:none;border-color:#31A6A8;background:#fff;box-shadow:0 0 0 3px rgba(49,166,168,0.11); }
    .field-input::placeholder { color:#c0c8d0; }
    .field-input.error { border-color:#f87171;background:#fff8f8; }
    select.field-input { appearance:none;cursor:pointer;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%239ca3af' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 12px center;background-size:14px;padding-right:36px; }
    .card { background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:24px; }
    .section-title { font-size:13px;font-weight:700;color:#374151;padding-bottom:14px;margin-bottom:18px;border-bottom:1px solid #f3f4f6;display:flex;align-items:center;gap:8px; }
    .section-title svg { color:#31A6A8; }
    .field-error { font-size:11.5px;color:#ef4444;margin-top:5px;display:flex;align-items:center;gap:4px; }
    .btn-primary { width:100%;padding:11px 16px;background:#31A6A8;color:#fff;font-size:0.875rem;font-weight:600;border-radius:10px;border:none;cursor:pointer;transition:background 0.18s,box-shadow 0.18s,transform 0.1s; }
    .btn-primary:hover { background:#2a9193;box-shadow:0 4px 18px rgba(49,166,168,0.32); }
    .btn-primary:active { transform:translateY(1px); }
    .btn-secondary { width:100%;padding:11px 16px;background:#f3f4f6;color:#6b7280;font-size:0.875rem;font-weight:500;border-radius:10px;border:1px solid #e5e7eb;text-align:center;transition:background 0.15s,color 0.15s;display:block; }
    .btn-secondary:hover { background:#e9eaec;color:#374151; }
    .pwd-wrap { position:relative; }
    .pwd-toggle { position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#9ca3af;padding:2px;transition:color 0.15s; }
    .pwd-toggle:hover { color:#6b7280; }
    @keyframes fadeIn { from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:translateY(0)} }
    .animate-in { animation:fadeIn 0.4s ease both; }
</style>
@endsection

@section('content')

<div class="flex items-center justify-between mb-7 animate-in">
    <div>
        <div class="flex items-center gap-2 text-xs text-gray-400 mb-1.5">
            <a href="{{ route('users.index') }}" class="hover:text-teal-600 transition-colors">Usuarios</a>
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            <span class="text-gray-600 font-medium">Nuevo usuario</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Crear usuario</h1>
    </div>
</div>

<form method="POST" action="{{ route('users.store') }}">
@csrf

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Main --}}
    <div class="lg:col-span-2 space-y-5 animate-in">
        <div class="card">
            <div class="section-title">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Información personal
            </div>
            <div class="space-y-4">
                <div>
                    <label class="field-label">Nombre completo <span class="text-red-400">*</span></label>
                    <input type="text" name="full_name" value="{{ old('full_name') }}" required autofocus
                           placeholder="Ej: Juan Pérez"
                           class="field-input @error('full_name') error @enderror">
                    @error('full_name')<p class="field-error"><svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01"/></svg>{{ $message }}</p>@enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="field-label">Nombre de usuario <span class="text-red-400">*</span></label>
                        <input type="text" name="username" value="{{ old('username') }}" required
                               placeholder="juan.perez"
                               class="field-input font-mono @error('username') error @enderror">
                        @error('username')<p class="field-error"><svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01"/></svg>{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="field-label">Email <span class="text-red-400">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               placeholder="juan@empresa.com"
                               class="field-input @error('email') error @enderror">
                        @error('email')<p class="field-error"><svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01"/></svg>{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="section-title">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                Contraseña
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="field-label">Contraseña <span class="text-red-400">*</span></label>
                    <div class="pwd-wrap">
                        <input type="password" id="pwd1" name="password" required
                               placeholder="••••••••"
                               class="field-input @error('password') error @enderror" style="padding-right:40px;">
                        <button type="button" class="pwd-toggle" onclick="togglePwd('pwd1','ico1a','ico1b')">
                            <svg id="ico1a" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg id="ico1b" class="w-4 h-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                    @error('password')<p class="field-error"><svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01"/></svg>{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="field-label">Confirmar contraseña <span class="text-red-400">*</span></label>
                    <div class="pwd-wrap">
                        <input type="password" id="pwd2" name="password_confirmation" required
                               placeholder="••••••••"
                               class="field-input" style="padding-right:40px;">
                        <button type="button" class="pwd-toggle" onclick="togglePwd('pwd2','ico2a','ico2b')">
                            <svg id="ico2a" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg id="ico2b" class="w-4 h-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="space-y-5 animate-in">
        <div class="card">
            <div class="section-title">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                Permisos
            </div>
            <div class="space-y-3 mb-5">
                <div>
                    <label class="field-label">Rol <span class="text-red-400">*</span></label>
                    <select name="role" class="field-input" required>
                        <option value="user"  {{ old('role', 'user') === 'user'  ? 'selected' : '' }}>Usuario</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrador</option>
                    </select>
                </div>
            </div>
            <div class="space-y-2.5">
                <button type="submit" class="btn-primary">Crear usuario</button>
                <a href="{{ route('users.index') }}" class="btn-secondary">Cancelar</a>
            </div>
        </div>
    </div>

</div>
</form>

<script>
function togglePwd(inputId, showId, hideId) {
    const f = document.getElementById(inputId);
    const s = document.getElementById(showId);
    const h = document.getElementById(hideId);
    if (f.type === 'password') { f.type='text'; s.classList.add('hidden'); h.classList.remove('hidden'); }
    else { f.type='password'; s.classList.remove('hidden'); h.classList.add('hidden'); }
}
</script>
@endsection
