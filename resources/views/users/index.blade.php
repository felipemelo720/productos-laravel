@extends('layouts.app')

@section('title', 'Usuarios')

@section('head')
<style>
    .card { background:#fff; border:1px solid #e5e7eb; border-radius:14px; overflow:hidden; }
    .badge { display:inline-flex;align-items:center;padding:2px 10px;border-radius:999px;font-size:11px;font-weight:600; }
    .avatar {
        width:34px;height:34px;border-radius:50%;
        display:flex;align-items:center;justify-content:center;
        font-size:13px;font-weight:700;flex-shrink:0;
        background:#f0fafa;color:#31A6A8;
    }
    .row-action { font-size:12px;font-weight:500;padding:5px 10px;border-radius:7px;transition:background 0.15s,color 0.15s; }
    .row-action-edit  { background:#eff6ff;color:#3b82f6; }
    .row-action-edit:hover  { background:#dbeafe; }
    .row-action-delete { background:#fff5f5;color:#ef4444; }
    .row-action-delete:hover { background:#fee2e2; }
    @keyframes fadeIn { from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:translateY(0)} }
    .animate-in { animation:fadeIn 0.4s ease both; }
</style>
@endsection

@section('content')

<div class="flex items-center justify-between mb-6 animate-in">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Usuarios</h1>
        <p class="text-xs text-gray-400 mt-0.5">{{ $users->count() }} usuario{{ $users->count() !== 1 ? 's' : '' }} registrado{{ $users->count() !== 1 ? 's' : '' }}</p>
    </div>
    <div class="flex items-center gap-2">
        @if($trashCount > 0)
        <a href="{{ route('users.trash') }}"
           class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-gray-500 hover:bg-gray-100 transition-colors border border-gray-200 relative">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            Papelera
            <span class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">{{ $trashCount }}</span>
        </a>
        @endif
        <a href="{{ route('users.create') }}" class="btn-brand px-4 py-2 rounded-lg text-sm font-semibold">+ Nuevo usuario</a>
    </div>
</div>

<div class="card animate-in">
    <table class="w-full">
        <thead>
            <tr style="border-bottom:1px solid #f3f4f6;">
                <th class="px-6 py-3.5 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Usuario</th>
                <th class="px-6 py-3.5 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Email</th>
                <th class="px-6 py-3.5 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Rol</th>
                <th class="px-6 py-3.5 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Estado</th>
                <th class="px-6 py-3.5 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr class="border-t border-gray-50 hover:bg-gray-50/60 transition-colors">
                <td class="px-6 py-3.5">
                    <div class="flex items-center gap-3">
                        <div class="avatar">{{ strtoupper(substr($user->full_name, 0, 1)) }}</div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ $user->full_name }}</p>
                            <p class="text-xs text-gray-400 font-mono">{{ $user->username }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-3.5 text-sm text-gray-600">{{ $user->email }}</td>
                <td class="px-6 py-3.5">
                    @if($user->role === 'admin')
                        <span class="badge bg-purple-100 text-purple-700">Admin</span>
                    @else
                        <span class="badge bg-blue-100 text-blue-700">Usuario</span>
                    @endif
                </td>
                <td class="px-6 py-3.5">
                    <span class="badge {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $user->is_active ? 'Activo' : 'Inactivo' }}
                    </span>
                </td>
                <td class="px-6 py-3.5">
                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('users.edit', $user) }}" class="row-action row-action-edit">Editar</a>
                        <form method="POST" action="{{ route('users.destroy', $user) }}"
                              onsubmit="return confirm('¿Eliminar a {{ addslashes($user->full_name) }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="row-action row-action-delete">Eliminar</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-16 text-center">
                    <svg class="w-10 h-10 mx-auto mb-3 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <p class="text-sm text-gray-400">No hay usuarios registrados.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
