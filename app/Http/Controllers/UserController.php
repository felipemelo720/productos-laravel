<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users      = User::all();
        $trashCount = User::onlyTrashed()->count();
        return view('users.index', compact('users', 'trashCount'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username'  => 'required|string|unique:users',
            'email'     => 'required|email|unique:users',
            'full_name' => 'required|string',
            'password'  => 'required|string|min:8|confirmed',
            'role'      => 'required|in:admin,user',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        User::create($validated);

        return redirect()->route('users.index')->with('success', 'Usuario creado correctamente.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'username'  => "required|string|unique:users,username,{$user->id}",
            'email'     => "required|email|unique:users,email,{$user->id}",
            'full_name' => 'required|string',
            'role'      => 'required|in:admin,user',
            'is_active' => 'boolean',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($request->password);
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', "Usuario \"{$user->full_name}\" movido a la papelera.");
    }

    public function trash()
    {
        $users = User::onlyTrashed()->latest('deleted_at')->get();
        return view('users.trash', compact('users'));
    }

    public function restore(int $id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();
        return redirect()->route('users.trash')->with('success', "Usuario \"{$user->full_name}\" restaurado.");
    }

    public function forceDelete(int $id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $name = $user->full_name;
        $user->forceDelete();
        return redirect()->route('users.trash')->with('success', "Usuario \"{$name}\" eliminado permanentemente.");
    }
}
