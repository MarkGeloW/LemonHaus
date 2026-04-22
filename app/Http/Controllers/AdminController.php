<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Inventory;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Display the Admin User Management & Audit Logs
     */
    public function index(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $tab = $request->get('tab', 'users');
        $users = User::orderBy('created_at', 'desc')->get();

        return view('admin.index', compact('users', 'tab'));
    }

    /**
     * Display the Inventory List
     */
    public function inventoryIndex()
    {
        $items = Inventory::orderBy('expiration_date', 'asc')->get();
        return view('admin.inventory', compact('items'));
    }

    /**
     * Show the Create Inventory Page
     */
    public function inventoryCreate()
    {
        return view('admin.inventory_create');
    }

    /**
     * Store a new User
     */
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

        if (class_exists(AuditLog::class)) {
            AuditLog::create([
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

    /**
     * Delete a User
     */
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

        if (class_exists(AuditLog::class)) {
            AuditLog::create([
                'user_id' => auth()->id(),
                'user_name' => auth()->user()->name,
                'action' => 'Deleted User',
                'details' => 'Deleted '.$deletedName.' with role '.$deletedRole,
            ]);
        }

        return redirect()->route('admin.index', ['tab' => 'users'])
            ->with('status', 'User deleted successfully.');
    }

    /**
     * Store New Inventory Item
     */
    public function storeInventory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'stock_level' => 'required|integer|min:0',
            'unit' => 'required|string',
            'min_stock' => 'required|integer|min:0',
            'date_added' => 'required|date',
            'expiration_date' => 'required|date|after_or_equal:date_added',
        ]);

        $item = Inventory::create($validated);

        if (class_exists(AuditLog::class)) {
            AuditLog::create([
                'user_id' => auth()->id(),
                'user_name' => auth()->user()->name,
                'action' => 'Added Inventory',
                'details' => "Added {$item->stock_level} {$item->unit} of {$item->name}",
            ]);
        }

        return redirect()->route('inventory.index')->with('success', 'Item added to inventory successfully.');
    }
}