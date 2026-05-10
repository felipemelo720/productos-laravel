@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-3xl font-bold mb-6">Edit User</h1>

    <form method="POST" action="{{ route('users.update', $user) }}" class="bg-white rounded shadow p-6 space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block font-semibold mb-2">Username *</label>
            <input type="text" name="username" class="w-full px-3 py-2 border rounded" value="{{ old('username', $user->username) }}" required>
        </div>

        <div>
            <label class="block font-semibold mb-2">Email *</label>
            <input type="email" name="email" class="w-full px-3 py-2 border rounded" value="{{ old('email', $user->email) }}" required>
        </div>

        <div>
            <label class="block font-semibold mb-2">Full Name *</label>
            <input type="text" name="full_name" class="w-full px-3 py-2 border rounded" value="{{ old('full_name', $user->full_name) }}" required>
        </div>

        <div>
            <label class="block font-semibold mb-2">Role *</label>
            <select name="role" class="w-full px-3 py-2 border rounded" required>
                <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>

        <div>
            <label class="block font-semibold mb-2">Status</label>
            <select name="is_active" class="w-full px-3 py-2 border rounded">
                <option value="1" {{ $user->is_active ? 'selected' : '' }}>Active</option>
                <option value="0" {{ !$user->is_active ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div>
            <label class="block font-semibold mb-2">New Password (leave blank to keep current)</label>
            <input type="password" name="password" class="w-full px-3 py-2 border rounded">
        </div>

        <div class="flex gap-2 pt-4">
            <button type="submit" class="btn-brand px-4 py-2 rounded">Update User</button>
            <a href="{{ route('users.index') }}" class="px-4 py-2 border rounded">Cancel</a>
        </div>
    </form>
</div>
@endsection
