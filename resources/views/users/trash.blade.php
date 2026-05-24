@extends('layouts.app')

@section('title', 'Papelera — Usuarios')

@section('head')
<style>
    .card { background:#fff;border:1px solid #e5e7eb;border-radius:14px;overflow:hidden; }
    .badge { display:inline-flex;align-items:center;padding:2px 10px;border-radius:999px;font-size:11px;font-weight:600; }
    .avatar { width:34px;height:34px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;flex-shrink:0;background:#fef2f2;color:#ef4444; }
    .row-action { font-size:12px;font-weight:500;padding:5px 10px;border-radius:7px;transition:background 0.15s,color 0.15s;border:none;cursor:pointer; }
    .row-action-restore { background:#f0fdf4;color:#16a34a; }
    .row-action-restore:hover { background:#dcfce7; }
    .row-action-force { background:#fff5f5;color:#ef4444; }
    .row-action-force:hover { background:#fee2e2; }
    @keyframes fadeIn { from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:translateY(0)} }
    .animate-in { animation:fadeIn 0.4s ease both; }
</style>
@endsection

@section('content')

<div class="flex items-center justify-between mb-6 animate-in">
    <div>
        <div class="flex items-center gap-2 text-xs text-gray-400 mb-1.5">
            <a href="{{ route('users.index') }}" class="hover:text-teal-600 transition-colors">Usuarios</a>
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            <span class="text-gray-600 font-medium">Papelera</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Papelera</h1>
        <p class="text-xs text-gray-400 mt-0.5">{{ $users->count() }} usuario{{ $users->count() !== 1 ? 's' : '' }} eliminado{{ $users->count() !== 1 ? 's' : '' }}</p>
    </div>
    <a href="{{ route('users.index') }}"
       class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-gray-500 hover:bg-gray-100 transition-colors border border-gray-200">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Volver a usuarios
    </a>
</div>

@if(session('success'))
<div class="mb-5 flex items-center gap-3 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm animate-in">
    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    {{ session('success') }}
</div>
@endif

<div class="card animate-in">
    @if($users->isEmpty())
        <div class="py-20 text-center">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            <p class="text-sm text-gray-400">La papelera está vacía.</p>
        </div>
    @else
    <table class="w-full">
        <thead>
            <tr style="border-bottom:1px solid #f3f4f6;">
                <th class="px-6 py-3.5 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Usuario</th>
                <th class="px-6 py-3.5 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Email</th>
                <th class="px-6 py-3.5 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Rol</th>
                <th class="px-6 py-3.5 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Eliminado</th>
                <th class="px-6 py-3.5 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr class="border-t border-gray-50 hover:bg-gray-50/60 transition-colors">
                <td class="px-6 py-3.5">
                    <div class="flex items-center gap-3">
                        <div class="avatar">{{ strtoupper(substr($user->full_name, 0, 1)) }}</div>
                        <div>
                            <p class="text-sm font-semibold text-gray-400 line-through">{{ $user->full_name }}</p>
                            <p class="text-xs text-gray-300 font-mono">{{ $user->username }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-3.5 text-sm text-gray-400">{{ $user->email }}</td>
                <td class="px-6 py-3.5">
                    @if($user->role === 'admin')
                        <span class="badge bg-purple-50 text-purple-400">Admin</span>
                    @else
                        <span class="badge bg-blue-50 text-blue-400">Usuario</span>
                    @endif
                </td>
                <td class="px-6 py-3.5 text-xs text-gray-400">
                    {{ $user->deleted_at->format('d/m/Y H:i') }}
                </td>
                <td class="px-6 py-3.5">
                    <div class="flex items-center gap-1.5">
                        <form method="POST" action="{{ route('users.restore', $user->id) }}">
                            @csrf
                            <button type="submit" class="row-action row-action-restore">Restaurar</button>
                        </form>
                        <form method="POST" action="{{ route('users.forceDelete', $user->id) }}"
                              onsubmit="return confirm('¿Eliminar permanentemente a {{ addslashes($user->full_name) }}? Esta acción no se puede deshacer.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="row-action row-action-force">Eliminar para siempre</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection
