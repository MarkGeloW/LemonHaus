<?php



namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class AdminController extends Controller
{
    public function index(Request $request)
{
    if (auth()->user()->role !== 'admin') {
        abort(403);
    }

    $tab = $request->get('tab', 'users');
    $users = \App\Models\User::orderBy('created_at', 'desc')->get();

    return view('admin.index', compact('users', 'tab'));
}

public function storeUser(Request $request)
{
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'username' => ['required', 'string', 'max:255', 'unique:users,username'],
        'email' => ['required', 'email', 'max:255', 'unique:users,email'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
        'role' => ['required', 'in:admin,cashier,kitchen,inventory'],
    ]);

    $user = User::create([
        'name' => $validated['name'],
        'username' => $validated['username'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role' => $validated['role'],
    ]);

    if (class_exists(\App\Models\AuditLog::class)) {
        \App\Models\AuditLog::create([
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'action' => 'Created User',
            'details' => 'Created '.$user->name.' with role '.$user->role,
        ]);
    }

    return redirect()
        ->route('admin.index', ['tab' => 'users'])
        ->with('success', 'User added successfully.');
}

    public function destroyUser(User $user)
    {
        abort_unless(auth()->check() && auth()->user()->role === 'admin', 403);

        if (auth()->id() === $user->id) {
            return redirect()->route('admin.index', ['tab' => 'users'])
                ->withErrors(['delete' => 'You cannot delete your own account.']);
        }

        $deletedName = $user->name;
        $deletedRole = $user->role;

        $user->delete();

        if (class_exists(\App\Models\AuditLog::class)) {
            \App\Models\AuditLog::create([
                'user_id' => auth()->id(),
                'user_name' => auth()->user()->name,
                'action' => 'Deleted User',
                'details' => 'Deleted '.$deletedName.' with role '.$deletedRole,
            ]);
        }

        return redirect()->route('admin.index', ['tab' => 'users'])
            ->with('status', 'User deleted successfully.');
    }
}