@extends('layouts.app')

@section('title', 'Create User')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-3xl font-bold mb-6">Create User</h1>

    <form method="POST" action="{{ route('users.store') }}" class="bg-white rounded shadow p-6 space-y-4">
        @csrf

        <div>
            <label class="block font-semibold mb-2">Username *</label>
            <input type="text" name="username" class="w-full px-3 py-2 border rounded @error('username') border-red-500 @enderror" value="{{ old('username') }}" required>
            @error('username') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-semibold mb-2">Email *</label>
            <input type="email" name="email" class="w-full px-3 py-2 border rounded @error('email') border-red-500 @enderror" value="{{ old('email') }}" required>
            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-semibold mb-2">Full Name *</label>
            <input type="text" name="full_name" class="w-full px-3 py-2 border rounded @error('full_name') border-red-500 @enderror" value="{{ old('full_name') }}" required>
            @error('full_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-semibold mb-2">Password *</label>
            <input type="password" name="password" class="w-full px-3 py-2 border rounded @error('password') border-red-500 @enderror" required>
            @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-semibold mb-2">Confirm Password *</label>
            <input type="password" name="password_confirmation" class="w-full px-3 py-2 border rounded" required>
        </div>

        <div>
            <label class="block font-semibold mb-2">Role *</label>
            <select name="role" class="w-full px-3 py-2 border rounded" required>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <div class="flex gap-2 pt-4">
            <button type="submit" class="btn-brand px-4 py-2 rounded">Create User</button>
            <a href="{{ route('users.index') }}" class="px-4 py-2 border rounded">Cancel</a>
        </div>
    </form>
</div>
@endsection
