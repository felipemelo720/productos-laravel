@extends('layouts.app')

@section('title', 'Users')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">Users</h1>
    <a href="{{ route('users.create') }}" class="btn-brand px-4 py-2 rounded">+ New User</a>
</div>

<div class="bg-white rounded shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-6 py-3 text-left">Name</th>
                <th class="px-6 py-3 text-left">Email</th>
                <th class="px-6 py-3 text-left">Role</th>
                <th class="px-6 py-3 text-left">Status</th>
                <th class="px-6 py-3 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr class="border-t">
                    <td class="px-6 py-3">{{ $user->full_name }}</td>
                    <td class="px-6 py-3">{{ $user->email }}</td>
                    <td class="px-6 py-3">
                        <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-sm">{{ ucfirst($user->role) }}</span>
                    </td>
                    <td class="px-6 py-3">
                        <span class="px-2 py-1 {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} rounded text-sm">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-3 flex gap-2">
                        <a href="{{ route('users.edit', $user) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                        <form method="POST" action="{{ route('users.destroy', $user) }}" style="display:inline;" onsubmit="return confirm('Delete?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-3 text-center text-gray-600">No users found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
