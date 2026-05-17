<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();

        $auditLogs = AuditLog::latest()
            ->limit(100)
            ->get();

        return view('admin.index', compact('users', 'auditLogs'));
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'role' => 'required|in:admin,cashier,kitchen,inventory',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        AuditLog::create([
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'action' => 'Created User',
            'details' => 'Created ' . $user->name . ' account with role ' . ucfirst($user->role) . '.',
        ]);

        return redirect()
            ->route('admin.index', ['tab' => 'users'])
            ->with('success', 'User created successfully.');
    }

    public function destroyUser(User $user)
    {
        if (auth()->id() === $user->id) {
            return redirect()
                ->route('admin.index', ['tab' => 'users'])
                ->with('error', 'You cannot delete your own account.');
        }

        $deletedUserName = $user->name;
        $deletedUserRole = $user->role;

        $user->delete();

        AuditLog::create([
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'action' => 'Deleted User',
            'details' => 'Deleted ' . $deletedUserName . ' account with role ' . ucfirst($deletedUserRole) . '.',
        ]);

        return redirect()
            ->route('admin.index', ['tab' => 'users'])
            ->with('success', 'User deleted successfully.');
    }
}